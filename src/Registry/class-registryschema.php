<?php
/**
 * Versioned AI systems registry persistence schema.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Registry;

use Kairoseth\AITransparency\Domain\AiSystem;
use Throwable;

/**
 * Converts registry state to and from a stable versioned persistence shape.
 */
final class RegistrySchema {
	public const VERSION = 1;

	/**
	 * Encode registry state for persistence and deterministic export.
	 *
	 * @param AiSystemsRegistry $registry Registry state.
	 * @return array<string, mixed>
	 */
	public static function encode( AiSystemsRegistry $registry ): array {
		$systems = array();

		foreach ( $registry->all() as $system ) {
			$systems[] = $system->to_array();
		}

		return array(
			'schema_version' => self::VERSION,
			'systems'        => $systems,
		);
	}

	/**
	 * Decode any supported persisted payload.
	 *
	 * Invalid individual records are skipped instead of breaking the whole registry.
	 *
	 * @param mixed $payload Persisted value.
	 * @return AiSystemsRegistry
	 */
	public static function decode( $payload ): AiSystemsRegistry {
		$normalized = self::migrate( $payload );
		$registry   = new AiSystemsRegistry();

		foreach ( $normalized['systems'] as $system_data ) {
			if ( ! is_array( $system_data ) ) {
				continue;
			}

			try {
				$registry->put( AiSystem::from_array( $system_data ) );
			} catch ( Throwable $error ) {
				continue;
			}
		}

		return $registry;
	}

	/**
	 * Normalize supported older payloads to the current schema.
	 *
	 * Version 0 accepted either a raw list of records or an array with a systems key.
	 * Missing Phase 2 metadata is filled by AiSystem defaults during hydration.
	 *
	 * @param mixed $payload Persisted value.
	 * @return array<string, mixed>
	 */
	public static function migrate( $payload ): array {
		if ( ! is_array( $payload ) ) {
			return self::empty_payload();
		}

		$version = isset( $payload['schema_version'] ) ? (int) $payload['schema_version'] : 0;
		$systems = array();

		if ( isset( $payload['systems'] ) && is_array( $payload['systems'] ) ) {
			$systems = $payload['systems'];
		} elseif ( 0 === $version && self::is_list( $payload ) ) {
			$systems = $payload;
		}

		if ( $version > self::VERSION ) {
			return self::empty_payload();
		}

		$normalized = array();
		foreach ( $systems as $system_data ) {
			if ( ! is_array( $system_data ) ) {
				continue;
			}

			$normalized[] = array(
				'id'                              => isset( $system_data['id'] ) ? (string) $system_data['id'] : '',
				'name'                            => isset( $system_data['name'] ) ? (string) $system_data['name'] : '',
				'type'                            => isset( $system_data['type'] ) ? (string) $system_data['type'] : '',
				'source'                          => isset( $system_data['source'] ) ? (string) $system_data['source'] : 'manual',
				'source_origin'                   => isset( $system_data['source_origin'] ) ? (string) $system_data['source_origin'] : AiSystem::SOURCE_MANUAL,
				'status'                          => isset( $system_data['status'] ) ? (string) $system_data['status'] : AiSystem::STATUS_ACTIVE,
				'review_status'                   => isset( $system_data['review_status'] ) ? (string) $system_data['review_status'] : AiSystem::REVIEW_PENDING,
				'interaction_context'             => isset( $system_data['interaction_context'] ) ? (string) $system_data['interaction_context'] : '',
				'interaction_disclosure_required' => ! empty( $system_data['interaction_disclosure_required'] ),
				'created_at'                      => isset( $system_data['created_at'] ) ? (string) $system_data['created_at'] : '',
				'updated_at'                      => isset( $system_data['updated_at'] ) ? (string) $system_data['updated_at'] : '',
				'reviewed_at'                     => isset( $system_data['reviewed_at'] ) ? (string) $system_data['reviewed_at'] : '',
			);
		}

		return array(
			'schema_version' => self::VERSION,
			'systems'        => $normalized,
		);
	}

	/**
	 * Empty current-version payload.
	 *
	 * @return array<string, mixed>
	 */
	public static function empty_payload(): array {
		return array(
			'schema_version' => self::VERSION,
			'systems'        => array(),
		);
	}

	/**
	 * PHP 7.4-compatible list detection.
	 *
	 * @param array<mixed> $value Candidate list.
	 * @return bool
	 */
	private static function is_list( array $value ): bool {
		$expected = 0;
		foreach ( array_keys( $value ) as $key ) {
			if ( $key !== $expected ) {
				return false;
			}
			++$expected;
		}

		return true;
	}
}
