<?php
/**
 * Exact release-package uninstall acceptance for WordPress Multisite.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;

if ( ! is_multisite() ) {
	fwrite( STDERR, "Expected a Multisite WordPress runtime.\n" );
	exit( 1 );
}

$action           = isset( $args[0] ) ? (string) $args[0] : '';
$registry_option  = 'kairoseth_ai_transparency_registry';
$sentinel_option  = 'phase8_unrelated_site_sentinel';
$network_sentinel = 'phase8_unrelated_network_sentinel';

$failures = array();

$fail = static function ( string $message ) use ( &$failures ): void {
	$failures[] = $message;
};

switch ( $action ) {
	case 'seed':
		if ( ! defined( 'KAIROSETH_AI_TRANSPARENCY_VERSION' ) || '1.0.0' !== KAIROSETH_AI_TRANSPARENCY_VERSION ) {
			$fail( 'Multisite lifecycle seed must run with plugin version 1.0.0 active.' );
			break;
		}

		$network        = get_network();
		$main_site_id   = get_main_site_id( $network->id );
		$secondary_path = trailingslashit( $network->path ) . 'phase8-release-secondary/';
		$secondary_id   = get_blog_id_from_url( $network->domain, $secondary_path );

		if ( ! $secondary_id ) {
			$secondary_id = wpmu_create_blog(
				$network->domain,
				$secondary_path,
				'Phase 8 Release Secondary',
				1,
				array( 'public' => 0 ),
				$network->id
			);
		}

		if ( is_wp_error( $secondary_id ) || ! $secondary_id ) {
			$fail( 'Could not create the Phase 8 secondary Multisite blog.' );
			break;
		}

		$target_sites = array( (int) $main_site_id, (int) $secondary_id );
		foreach ( $target_sites as $site_id ) {
			switch_to_blog( $site_id );

			$registry = new AiSystemsRegistry();
			$registry->put(
				new AiSystem(
					'phase8-site-' . $site_id,
					'Phase 8 Site ' . $site_id . ' Assistant',
					AiSystem::TYPE_ASSISTANT,
					'manual',
					false,
					array(
						'source_origin' => AiSystem::SOURCE_MANUAL,
						'status'        => AiSystem::STATUS_ACTIVE,
						'review_status' => AiSystem::REVIEW_REVIEWED,
					)
				)
			);

			if ( ! ( new WordPressOptionsRegistryRepository() )->save( $registry ) ) {
				$fail( 'Could not save Registry state on site ' . $site_id . '.' );
			}
			update_option( $sentinel_option, 'preserve-site-' . $site_id, false );
			restore_current_blog();
		}

		update_site_option( $network_sentinel, 'preserve-network' );
		break;

	case 'assert-uninstalled':
		$site_ids = get_sites(
			array(
				'fields' => 'ids',
				'number' => 0,
			)
		);

		if ( count( $site_ids ) < 2 ) {
			$fail( 'Multisite uninstall assertion requires at least two sites.' );
		}

		foreach ( $site_ids as $site_id ) {
			switch_to_blog( (int) $site_id );
			if ( '__phase8_registry_absent__' !== get_option( $registry_option, '__phase8_registry_absent__' ) ) {
				$fail( 'Registry option survived uninstall on site ' . (int) $site_id . '.' );
			}

			$sentinel = get_option( $sentinel_option, '' );
			if ( '' !== $sentinel && 'preserve-site-' . (int) $site_id !== $sentinel ) {
				$fail( 'Unrelated site sentinel changed on site ' . (int) $site_id . '.' );
			}
			delete_option( $sentinel_option );
			restore_current_blog();
		}

		if ( 'preserve-network' !== get_site_option( $network_sentinel, '' ) ) {
			$fail( 'Explicit uninstall removed unrelated network data.' );
		}
		if ( is_dir( WP_PLUGIN_DIR . '/ai-transparency' ) ) {
			$fail( 'Plugin directory still exists after Multisite uninstall.' );
		}
		delete_site_option( $network_sentinel );
		break;

	default:
		$fail( 'Unknown Phase 8 Multisite lifecycle action.' );
		break;
}

if ( $failures ) {
	foreach ( $failures as $failure ) {
		fwrite( STDERR, $failure . "\n" );
	}
	exit( 1 );
}

printf( "Phase 8 Multisite lifecycle action passed: %s.\n", $action );
