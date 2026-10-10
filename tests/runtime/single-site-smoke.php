<?php
/**
 * Single-site WordPress runtime acceptance for Registry migration.
 *
 * Executed through WP-CLI inside wp-env against the production package.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;
use Kairoseth\AITransparency\Registry\RegistrySchema;

if ( is_multisite() ) {
	fwrite( STDERR, "Expected a single-site WordPress runtime.\n" );
	exit( 1 );
}

$legacy_payload = array(
	array(
		'id'                              => 'legacy-runtime-system',
		'name'                            => 'Legacy Runtime Assistant',
		'type'                            => 'assistant',
		'source'                          => 'manual',
		'interaction_disclosure_required' => true,
	),
);

update_option( WordPressOptionsRegistryRepository::OPTION_NAME, $legacy_payload, false );

$repository = new WordPressOptionsRegistryRepository();
$registry   = $repository->load();
$system     = $registry->find( 'legacy-runtime-system' );
$stored     = get_option( WordPressOptionsRegistryRepository::OPTION_NAME );

$failures = array();

if ( 1 !== $registry->count() ) {
	$failures[] = 'Legacy payload did not hydrate exactly one registry record.';
}

if ( null === $system || 'Legacy Runtime Assistant' !== $system->name() ) {
	$failures[] = 'Migrated registry record could not be read through the real repository.';
}

if ( ! is_array( $stored ) || RegistrySchema::VERSION !== (int) ( $stored['schema_version'] ?? 0 ) ) {
	$failures[] = 'Legacy payload was not persisted back using the current Registry schema version.';
}

if ( ! isset( $stored['systems'][0]['status'] ) || 'active' !== $stored['systems'][0]['status'] ) {
	$failures[] = 'Lifecycle defaults were not materialized during migration.';
}

if ( ! isset( $stored['systems'][0]['review_status'] ) || 'pending' !== $stored['systems'][0]['review_status'] ) {
	$failures[] = 'Review defaults were not materialized during migration.';
}

$context_fields = array( 'purpose', 'provider', 'model', 'audience', 'human_oversight', 'data_use_context' );
foreach ( $context_fields as $field ) {
	if ( ! array_key_exists( $field, $stored['systems'][0] ) || '' !== $stored['systems'][0][ $field ] ) {
		$failures[] = 'Administrator context defaults were not materialized safely during migration.';
		break;
	}
}

if ( $failures ) {
	foreach ( $failures as $failure ) {
		fwrite( STDERR, $failure . "\n" );
	}
	exit( 1 );
}

printf( "Single-site runtime migration smoke passed with Registry schema version %d.\n", (int) $stored['schema_version'] );
