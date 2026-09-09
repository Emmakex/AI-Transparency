<?php
/**
 * Immutable Phase 6 evidence snapshot model.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Export;

use InvalidArgumentException;

/**
 * Represents one bounded administrator-generated technical evidence snapshot.
 */
final class EvidenceSnapshot {
	public const SCHEMA_VERSION = 1;

	/**
	 * UTC ISO-8601 generation timestamp.
	 *
	 * @var string
	 */
	private $generated_at;

	/**
	 * Stable SHA-256 identity of the technical snapshot payload.
	 *
	 * @var string
	 */
	private $snapshot_signature;

	/**
	 * Stable allow-list payload used to calculate snapshot identity.
	 *
	 * @var array<string, mixed>
	 */
	private $stable_payload;

	/**
	 * Create one immutable snapshot.
	 *
	 * @param string               $generated_at UTC ISO-8601 generation timestamp.
	 * @param string               $snapshot_signature Stable SHA-256 snapshot identity.
	 * @param array<string, mixed> $stable_payload Canonical stable export payload.
	 * @throws InvalidArgumentException When required snapshot metadata is invalid.
	 */
	public function __construct( string $generated_at, string $snapshot_signature, array $stable_payload ) {
		$generated_at       = trim( $generated_at );
		$snapshot_signature = trim( $snapshot_signature );

		if ( '' === $generated_at ) {
			throw new InvalidArgumentException( 'Evidence snapshot generation time must be non-empty.' );
		}

		if ( 1 !== preg_match( '/^[a-f0-9]{64}$/', $snapshot_signature ) ) {
			throw new InvalidArgumentException( 'Evidence snapshot signature must be lowercase SHA-256 hexadecimal.' );
		}

		if ( self::SCHEMA_VERSION !== ( $stable_payload['export_schema_version'] ?? null ) ) {
			throw new InvalidArgumentException( 'Evidence snapshot export schema version is invalid.' );
		}

		$this->generated_at       = $generated_at;
		$this->snapshot_signature = $snapshot_signature;
		$this->stable_payload     = $stable_payload;
	}

	/**
	 * Get the generation timestamp.
	 *
	 * @return string
	 */
	public function generated_at(): string {
		return $this->generated_at;
	}

	/**
	 * Get the stable snapshot signature.
	 *
	 * @return string
	 */
	public function snapshot_signature(): string {
		return $this->snapshot_signature;
	}

	/**
	 * Return the canonical stable payload used for hashing.
	 *
	 * @return array<string, mixed>
	 */
	public function stable_payload(): array {
		return $this->stable_payload;
	}

	/**
	 * Return the complete Phase 6 JSON document shape.
	 *
	 * @return array<string, mixed>
	 */
	public function to_array(): array {
		return array(
			'export_schema_version' => self::SCHEMA_VERSION,
			'generated_at'          => $this->generated_at,
			'snapshot_signature'    => $this->snapshot_signature,
			'generator'             => $this->stable_payload['generator'],
			'site'                  => $this->stable_payload['site'],
			'registry'              => $this->stable_payload['registry'],
			'discovery_evidence'    => $this->stable_payload['discovery_evidence'],
			'findings'              => $this->stable_payload['findings'],
			'disclosure_readiness'  => $this->stable_payload['disclosure_readiness'],
		);
	}
}
