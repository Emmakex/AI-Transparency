<?php
/**
 * WordPress Options persistence adapter for the AI systems registry.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Persistence;

use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use Kairoseth\AITransparency\Registry\RegistrySchema;

/**
 * Stores one registry per WordPress site/blog using the normal options table.
 */
final class WordPressOptionsRegistryRepository {
	public const OPTION_NAME = 'kairoseth_ai_transparency_registry';

	/**
	 * Load and migrate site-local registry state.
	 *
	 * @return AiSystemsRegistry
	 */
	public function load(): AiSystemsRegistry {
		$stored     = get_option( self::OPTION_NAME, RegistrySchema::empty_payload() );
		$normalized = RegistrySchema::migrate( $stored );

		if ( $stored !== $normalized ) {
			update_option( self::OPTION_NAME, $normalized, false );
		}

		return RegistrySchema::decode( $normalized );
	}

	/**
	 * Persist site-local registry state.
	 *
	 * @param AiSystemsRegistry $registry Registry state.
	 * @return bool True when WordPress reports an update or the stored value already matches.
	 */
	public function save( AiSystemsRegistry $registry ): bool {
		$payload = RegistrySchema::encode( $registry );
		$current = get_option( self::OPTION_NAME, null );

		if ( $current === $payload ) {
			return true;
		}

		return update_option( self::OPTION_NAME, $payload, false );
	}

	/**
	 * Return the deterministic payload used by persistence/export adapters.
	 *
	 * @param AiSystemsRegistry $registry Registry state.
	 * @return array<string, mixed>
	 */
	public function export( AiSystemsRegistry $registry ): array {
		return RegistrySchema::encode( $registry );
	}
}
