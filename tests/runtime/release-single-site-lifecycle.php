<?php
/**
 * Exact release-package lifecycle acceptance for a single WordPress site.
 *
 * Executed through WP-CLI in the release wp-env, which installs ZIP artifacts
 * instead of mounting the development plugin directory.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;

if ( is_multisite() ) {
	fwrite( STDERR, "Expected a single-site WordPress runtime.\n" );
	exit( 1 );
}

$action                   = isset( $args[0] ) ? (string) $args[0] : '';
$expected_release_version = isset( $args[1] ) ? trim( (string) $args[1] ) : '';
$registry_option          = 'kairoseth_ai_transparency_registry';
$expected_hash_option     = 'phase8_release_expected_registry_hash';
$sentinel_option          = 'phase8_unrelated_runtime_sentinel';

$failures = array();

$fail = static function ( string $message ) use ( &$failures ): void {
	$failures[] = $message;
};

$assert_release_version = static function () use ( $expected_release_version, $fail ): void {
	if ( '' === $expected_release_version || 1 !== preg_match( '/^\d+\.\d+\.\d+$/', $expected_release_version ) ) {
		$fail( 'Release assertion requires an expected semantic version argument.' );
		return;
	}

	if ( ! defined( 'KAIROSETH_AI_TRANSPARENCY_VERSION' ) || $expected_release_version !== KAIROSETH_AI_TRANSPARENCY_VERSION ) {
		$fail( 'Release assertion did not run with the expected plugin version active.' );
	}
};

$assert_registry_hash = static function () use ( $registry_option, $expected_hash_option, $fail ): void {
	$stored        = get_option( $registry_option, '__phase8_missing_registry__' );
	$expected_hash = get_option( $expected_hash_option, '' );

	if ( ! is_array( $stored ) ) {
		$fail( 'Expected Registry payload is missing.' );
		return;
	}

	if ( ! is_string( $expected_hash ) || '' === $expected_hash ) {
		$fail( 'Expected Registry fingerprint is missing.' );
		return;
	}

	if ( hash( 'sha256', serialize( $stored ) ) !== $expected_hash ) {
		$fail( 'Registry payload changed across the tested lifecycle transition.' );
	}
};

switch ( $action ) {
	case 'seed-baseline':
		if ( ! defined( 'KAIROSETH_AI_TRANSPARENCY_VERSION' ) || '0.1.0' !== KAIROSETH_AI_TRANSPARENCY_VERSION ) {
			$fail( 'Baseline fixture must run with plugin version 0.1.0 active.' );
			break;
		}

		$registry = new AiSystemsRegistry();
		$registry->put(
			new AiSystem(
				'phase8-reviewed-system',
				'Phase 8 Reviewed Assistant',
				AiSystem::TYPE_ASSISTANT,
				'manual',
				true,
				array(
					'source_origin'       => AiSystem::SOURCE_MANUAL,
					'status'              => AiSystem::STATUS_ACTIVE,
					'review_status'       => AiSystem::REVIEW_REVIEWED,
					'interaction_context' => 'Customer support chat on the storefront.',
					'created_at'          => '2026-09-01T10:00:00Z',
					'updated_at'          => '2026-09-02T10:00:00Z',
					'reviewed_at'         => '2026-09-02T10:00:00Z',
				)
			)
		);
		$registry->put(
			new AiSystem(
				'phase8-archived-system',
				'Phase 8 Archived Generator',
				AiSystem::TYPE_GENERATOR,
				'manual',
				false,
				array(
					'source_origin'       => AiSystem::SOURCE_MANUAL,
					'status'              => AiSystem::STATUS_ARCHIVED,
					'review_status'       => AiSystem::REVIEW_REVIEWED,
					'interaction_context' => 'Historical content workflow.',
					'created_at'          => '2026-08-01T10:00:00Z',
					'updated_at'          => '2026-08-15T10:00:00Z',
					'reviewed_at'         => '2026-08-15T10:00:00Z',
				)
			)
		);

		$repository = new WordPressOptionsRegistryRepository();
		if ( ! $repository->save( $registry ) ) {
			$fail( 'Could not seed the 0.1.0 Registry upgrade fixture.' );
			break;
		}

		$stored = get_option( $registry_option, array() );
		update_option( $expected_hash_option, hash( 'sha256', serialize( $stored ) ), false );
		update_option( $sentinel_option, 'preserve-me', false );
		break;

	case 'assert-upgraded':
		$assert_release_version();
		if ( $failures ) {
			break;
		}

		$assert_registry_hash();

		$repository = new WordPressOptionsRegistryRepository();
		$registry   = $repository->load();
		$reviewed   = $registry->find( 'phase8-reviewed-system' );
		$archived   = $registry->find( 'phase8-archived-system' );

		if ( 2 !== $registry->count() ) {
			$fail( 'Upgrade did not preserve exactly two Registry records.' );
		}
		if ( null === $reviewed || AiSystem::STATUS_ACTIVE !== $reviewed->status() || AiSystem::REVIEW_REVIEWED !== $reviewed->review_status() ) {
			$fail( 'Reviewed active Registry state was not preserved by upgrade.' );
		}
		if ( null === $reviewed || ! $reviewed->interaction_disclosure_required() || 'Customer support chat on the storefront.' !== $reviewed->interaction_context() ) {
			$fail( 'Reviewed disclosure/context state was not preserved by upgrade.' );
		}
		if ( null === $archived || AiSystem::STATUS_ARCHIVED !== $archived->status() || AiSystem::REVIEW_REVIEWED !== $archived->review_status() ) {
			$fail( 'Archived reviewed Registry state was not preserved by upgrade.' );
		}
		if ( 'preserve-me' !== get_option( $sentinel_option, '' ) ) {
			$fail( 'Unrelated sentinel data changed during upgrade.' );
		}
		break;

	case 'assert-deactivated':
		if ( defined( 'KAIROSETH_AI_TRANSPARENCY_VERSION' ) ) {
			$fail( 'Plugin runtime unexpectedly loaded while deactivated.' );
		}
		$assert_registry_hash();
		if ( 'preserve-me' !== get_option( $sentinel_option, '' ) ) {
			$fail( 'Unrelated sentinel data changed during deactivation.' );
		}
		break;

	case 'assert-uninstalled':
		if ( '__phase8_registry_absent__' !== get_option( $registry_option, '__phase8_registry_absent__' ) ) {
			$fail( 'Explicit uninstall did not remove the plugin-owned Registry option.' );
		}
		if ( 'preserve-me' !== get_option( $sentinel_option, '' ) ) {
			$fail( 'Explicit uninstall removed unrelated WordPress data.' );
		}
		if ( is_dir( WP_PLUGIN_DIR . '/ai-transparency' ) ) {
			$fail( 'Plugin directory still exists after WordPress uninstall.' );
		}
		delete_option( $expected_hash_option );
		delete_option( $sentinel_option );
		break;

	case 'assert-fresh':
		$assert_release_version();
		if ( $failures ) {
			break;
		}
		$registry = ( new WordPressOptionsRegistryRepository() )->load();
		if ( 0 !== $registry->count() ) {
			$fail( 'Fresh stable install did not start with an empty Registry.' );
		}
		break;

	default:
		$fail( 'Unknown release single-site lifecycle action.' );
		break;
}

if ( $failures ) {
	foreach ( $failures as $failure ) {
		fwrite( STDERR, $failure . "\n" );
	}
	exit( 1 );
}

printf( "Release single-site lifecycle action passed: %s.\n", $action );
