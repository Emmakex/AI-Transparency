<?php
/**
 * Deterministic Phase 6 evidence snapshot builder.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Export;

use InvalidArgumentException;
use Kairoseth\AITransparency\Disclosure\DisclosureEngine;
use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Evidence\Finding;
use Kairoseth\AITransparency\Evidence\FindingEngine;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use Kairoseth\AITransparency\Registry\RegistrySchema;
use RuntimeException;

/**
 * Builds one canonical allow-list evidence snapshot from authoritative server state.
 */
final class EvidenceSnapshotBuilder {
	private const PLUGIN_SLUG = 'ai-transparency';
	private const PLUGIN_NAME = 'Kairoseth AI Transparency';

	/**
	 * Phase 4 finding engine.
	 *
	 * @var FindingEngine
	 */
	private $finding_engine;

	/**
	 * Phase 5 disclosure eligibility engine.
	 *
	 * @var DisclosureEngine
	 */
	private $disclosure_engine;

	/**
	 * Create the deterministic builder.
	 *
	 * @param FindingEngine|null    $finding_engine Optional finding engine for testing.
	 * @param DisclosureEngine|null $disclosure_engine Optional disclosure engine for testing.
	 */
	public function __construct( FindingEngine $finding_engine = null, DisclosureEngine $disclosure_engine = null ) {
		$this->finding_engine    = null !== $finding_engine ? $finding_engine : new FindingEngine();
		$this->disclosure_engine = null !== $disclosure_engine ? $disclosure_engine : new DisclosureEngine();
	}

	/**
	 * Build one immutable evidence snapshot.
	 *
	 * @param AiSystemsRegistry    $registry Current authoritative site-local Registry.
	 * @param string               $plugin_version Real runtime plugin version.
	 * @param array<string, mixed> $site Authoritative server-resolved site identity.
	 * @param string               $generated_at UTC ISO-8601 generation timestamp.
	 * @return EvidenceSnapshot
	 * @throws InvalidArgumentException When required generation metadata is invalid.
	 */
	public function build( AiSystemsRegistry $registry, string $plugin_version, array $site, string $generated_at ): EvidenceSnapshot {
		$plugin_version = trim( $plugin_version );
		$generated_at   = trim( $generated_at );

		if ( '' === $plugin_version ) {
			throw new InvalidArgumentException( 'Evidence export plugin version must be non-empty.' );
		}

		if ( '' === $generated_at ) {
			throw new InvalidArgumentException( 'Evidence export generation time must be non-empty.' );
		}

		$site_payload = $this->normalize_site( $site );
		$systems      = $registry->all();
		$findings     = $this->finding_engine->generate( $registry, $generated_at );

		$stable_payload = array(
			'export_schema_version' => EvidenceSnapshot::SCHEMA_VERSION,
			'generator'             => array(
				'plugin_slug'    => self::PLUGIN_SLUG,
				'plugin_name'    => self::PLUGIN_NAME,
				'plugin_version' => $plugin_version,
			),
			'site'                  => $site_payload,
			'registry'              => RegistrySchema::encode( $registry ),
			'discovery_evidence'    => $this->discovery_evidence( $systems ),
			'findings'              => $this->findings_payload( $findings ),
			'disclosure_readiness'  => $this->disclosure_readiness( $systems ),
		);

		$canonical_json = self::canonical_json( $stable_payload );
		$signature      = hash( 'sha256', $canonical_json );

		return new EvidenceSnapshot( $generated_at, $signature, $stable_payload );
	}

	/**
	 * Encode a canonical payload into stable UTF-8 JSON bytes.
	 *
	 * The caller must construct allow-listed keys and deterministically ordered arrays first.
	 *
	 * @param array<string, mixed> $payload Canonical payload.
	 * @return string
	 * @throws RuntimeException When JSON encoding fails.
	 */
	public static function canonical_json( array $payload ): string {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode -- Pure deterministic domain/export service intentionally has no WordPress runtime dependency.
		$encoded = json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

		if ( false === $encoded ) {
			throw new RuntimeException( 'Evidence snapshot canonical JSON encoding failed.' );
		}

		return $encoded;
	}

	/**
	 * Normalize authoritative site identity to the schema-v1 allow-list.
	 *
	 * @param array<string, mixed> $site Site metadata.
	 * @return array<string, mixed>
	 * @throws InvalidArgumentException When site identity is invalid.
	 */
	private function normalize_site( array $site ): array {
		$home_url     = isset( $site['home_url'] ) ? trim( (string) $site['home_url'] ) : '';
		$is_multisite = isset( $site['is_multisite'] ) ? (bool) $site['is_multisite'] : false;
		$blog_id      = isset( $site['blog_id'] ) ? (int) $site['blog_id'] : 0;

		if ( '' === $home_url || $blog_id < 1 ) {
			throw new InvalidArgumentException( 'Evidence export site identity is invalid.' );
		}

		return array(
			'home_url'     => $home_url,
			'is_multisite' => $is_multisite,
			'blog_id'      => $blog_id,
		);
	}

	/**
	 * Normalize persisted Discovery source references without claiming fresh observation.
	 *
	 * @param AiSystem[] $systems Registry systems in deterministic id order.
	 * @return array<int, array<string, string>>
	 */
	private function discovery_evidence( array $systems ): array {
		$evidence = array();

		foreach ( $systems as $system ) {
			if ( AiSystem::SOURCE_DISCOVERED !== $system->source_origin() ) {
				continue;
			}

			if ( 1 !== preg_match( '/^detector:([^:]+):([a-f0-9]{64})$/', $system->source(), $matches ) ) {
				continue;
			}

			$evidence[] = array(
				'system_id'        => $system->id(),
				'detector_id'      => $matches[1],
				'source_signature' => $matches[2],
			);
		}

		usort(
			$evidence,
			static function ( array $left, array $right ): int {
				$by_system = strcmp( $left['system_id'], $right['system_id'] );
				return 0 !== $by_system ? $by_system : strcmp( $left['detector_id'], $right['detector_id'] );
			}
		);

		return $evidence;
	}

	/**
	 * Convert Phase 4 findings to the bounded schema-v1 allow-list.
	 *
	 * @param Finding[] $findings Deterministically ordered findings.
	 * @return array<int, array<string, string>>
	 */
	private function findings_payload( array $findings ): array {
		$payload = array();

		foreach ( $findings as $finding ) {
			$payload[] = array(
				'id'                  => $finding->id(),
				'rule_id'             => $finding->rule_id(),
				'category'            => $finding->category(),
				'priority'            => $finding->priority(),
				'subject_system_id'   => $finding->subject_system_id(),
				'subject_system_name' => $finding->subject_system_name(),
				'fact_code'           => $finding->fact_code(),
				'declaration_code'    => $finding->declaration_code(),
				'guidance_code'       => $finding->guidance_code(),
				'evidence_signature'  => $finding->evidence_signature(),
			);
		}

		usort(
			$payload,
			static function ( array $left, array $right ): int {
				return strcmp( $left['id'], $right['id'] );
			}
		);

		return $payload;
	}

	/**
	 * Build one Phase 5 readiness entry for every exported Registry system.
	 *
	 * @param AiSystem[] $systems Registry systems in deterministic id order.
	 * @return array<int, array<string, mixed>>
	 */
	private function disclosure_readiness( array $systems ): array {
		$payload = array();

		foreach ( $systems as $system ) {
			$reasons   = $this->disclosure_engine->reason_codes( $system );
			$payload[] = array(
				'system_id'    => $system->id(),
				'eligible'     => array() === $reasons,
				'reason_codes' => $reasons,
			);
		}

		usort(
			$payload,
			static function ( array $left, array $right ): int {
				return strcmp( $left['system_id'], $right['system_id'] );
			}
		);

		return $payload;
	}
}
