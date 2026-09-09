<?php
/**
 * Real Multisite isolation acceptance for Registry and Phase 6 Evidence Export.
 *
 * Executed through WP-CLI inside a wp-env Multisite installation.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Export\EvidenceSnapshotBuilder;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;

if ( ! is_multisite() ) {
	fwrite( STDERR, "Expected a Multisite WordPress runtime.\n" );
	exit( 1 );
}

$network        = get_network();
$main_site_id   = get_main_site_id( $network->id );
$secondary_path = trailingslashit( $network->path ) . 'phase2-runtime-secondary/';
$secondary_id   = get_blog_id_from_url( $network->domain, $secondary_path );

if ( ! $secondary_id ) {
	$secondary_id = wpmu_create_blog(
		$network->domain,
		$secondary_path,
		'Phase 2 Runtime Secondary',
		1,
		array( 'public' => 0 ),
		$network->id
	);
}

if ( is_wp_error( $secondary_id ) || ! $secondary_id ) {
	fwrite( STDERR, "Could not create the secondary Multisite blog.\n" );
	exit( 1 );
}

$failures = array();
$builder  = new EvidenceSnapshotBuilder();

switch_to_blog( $main_site_id );
delete_option( WordPressOptionsRegistryRepository::OPTION_NAME );
$main_repository = new WordPressOptionsRegistryRepository();
$main_registry   = new AiSystemsRegistry();
$main_registry->put(
	new AiSystem(
		'main-runtime-system',
		'Main Runtime Assistant',
		AiSystem::TYPE_ASSISTANT,
		'manual'
	)
);

if ( ! $main_repository->save( $main_registry ) ) {
	$failures[] = 'Main-site registry could not be saved.';
}
restore_current_blog();

switch_to_blog( $secondary_id );
delete_option( WordPressOptionsRegistryRepository::OPTION_NAME );
$secondary_repository = new WordPressOptionsRegistryRepository();

if ( 0 !== $secondary_repository->load()->count() ) {
	$failures[] = 'Secondary site inherited registry data from the main site.';
}

$secondary_registry = new AiSystemsRegistry();
$secondary_registry->put(
	new AiSystem(
		'secondary-runtime-system',
		'Secondary Runtime Assistant',
		AiSystem::TYPE_ASSISTANT,
		'manual'
	)
);

if ( ! $secondary_repository->save( $secondary_registry ) ) {
	$failures[] = 'Secondary-site registry could not be saved.';
}
restore_current_blog();

switch_to_blog( $main_site_id );
$main_reloaded = ( new WordPressOptionsRegistryRepository() )->load();
if ( 1 !== $main_reloaded->count() || null === $main_reloaded->find( 'main-runtime-system' ) || null !== $main_reloaded->find( 'secondary-runtime-system' ) ) {
	$failures[] = 'Main-site registry was contaminated by secondary-site data.';
}

$main_export = $builder->build(
	$main_reloaded,
	KAIROSETH_AI_TRANSPARENCY_VERSION,
	array(
		'home_url'     => home_url( '/' ),
		'is_multisite' => is_multisite(),
		'blog_id'      => get_current_blog_id(),
	),
	'2026-09-09T20:30:00Z'
)->to_array();

if ( (int) $main_site_id !== $main_export['site']['blog_id'] ) {
	$failures[] = 'Main-site evidence export recorded the wrong blog id.';
}
if ( 1 !== count( $main_export['registry']['systems'] ) || 'main-runtime-system' !== $main_export['registry']['systems'][0]['id'] ) {
	$failures[] = 'Main-site evidence export did not remain scoped to the main Registry.';
}
restore_current_blog();

switch_to_blog( $secondary_id );
$secondary_reloaded = ( new WordPressOptionsRegistryRepository() )->load();
if ( 1 !== $secondary_reloaded->count() || null === $secondary_reloaded->find( 'secondary-runtime-system' ) || null !== $secondary_reloaded->find( 'main-runtime-system' ) ) {
	$failures[] = 'Secondary-site registry was contaminated by main-site data.';
}

$secondary_export = $builder->build(
	$secondary_reloaded,
	KAIROSETH_AI_TRANSPARENCY_VERSION,
	array(
		'home_url'     => home_url( '/' ),
		'is_multisite' => is_multisite(),
		'blog_id'      => get_current_blog_id(),
	),
	'2026-09-09T20:30:00Z'
)->to_array();

if ( (int) $secondary_id !== $secondary_export['site']['blog_id'] ) {
	$failures[] = 'Secondary-site evidence export recorded the wrong blog id.';
}
if ( 1 !== count( $secondary_export['registry']['systems'] ) || 'secondary-runtime-system' !== $secondary_export['registry']['systems'][0]['id'] ) {
	$failures[] = 'Secondary-site evidence export did not remain scoped to the secondary Registry.';
}
restore_current_blog();

if ( $main_export['snapshot_signature'] === $secondary_export['snapshot_signature'] ) {
	$failures[] = 'Different site-local evidence states unexpectedly produced the same snapshot signature.';
}

if ( $failures ) {
	foreach ( $failures as $failure ) {
		fwrite( STDERR, $failure . "\n" );
	}
	exit( 1 );
}

printf(
	"Multisite Registry and Phase 6 evidence export isolation smoke passed for site %d and site %d.\n",
	(int) $main_site_id,
	(int) $secondary_id
);
