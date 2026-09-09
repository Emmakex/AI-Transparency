<?php
/**
 * Phase 6 deterministic evidence snapshot tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Export\EvidenceSnapshot;
use Kairoseth\AITransparency\Export\EvidenceSnapshotBuilder;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class EvidenceSnapshotBuilderTest extends TestCase {
	public function test_empty_registry_produces_valid_signed_snapshot(): void {
		$builder  = new EvidenceSnapshotBuilder();
		$snapshot = $builder->build( new AiSystemsRegistry(), '0.1.0', $this->site(), '2026-09-09T20:00:00Z' );
		$payload  = $snapshot->to_array();

		$this->assertSame( EvidenceSnapshot::SCHEMA_VERSION, $payload['export_schema_version'] );
		$this->assertSame( '2026-09-09T20:00:00Z', $payload['generated_at'] );
		$this->assertSame( 1, preg_match( '/^[a-f0-9]{64}$/', $payload['snapshot_signature'] ) );
		$this->assertSame( array(), $payload['registry']['systems'] );
		$this->assertSame( array(), $payload['discovery_evidence'] );
		$this->assertSame( array(), $payload['findings'] );
		$this->assertSame( array(), $payload['disclosure_readiness'] );
	}

	public function test_generation_time_is_not_part_of_snapshot_identity(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'system-a' ) );
		$builder = new EvidenceSnapshotBuilder();

		$first  = $builder->build( $registry, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' );
		$second = $builder->build( $registry, '0.1.0', $this->site(), '2026-09-09T21:00:00Z' );

		$this->assertNotSame( $first->generated_at(), $second->generated_at() );
		$this->assertSame( $first->snapshot_signature(), $second->snapshot_signature() );
		$this->assertSame( $first->stable_payload(), $second->stable_payload() );
	}

	public function test_meaningful_registry_change_changes_snapshot_identity(): void {
		$builder = new EvidenceSnapshotBuilder();

		$before = new AiSystemsRegistry();
		$before->put( $this->system( 'system-a', AiSystem::REVIEW_PENDING ) );

		$after = new AiSystemsRegistry();
		$after->put( $this->system( 'system-a', AiSystem::REVIEW_REVIEWED ) );

		$before_snapshot = $builder->build( $before, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' );
		$after_snapshot  = $builder->build( $after, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' );

		$this->assertNotSame( $before_snapshot->snapshot_signature(), $after_snapshot->snapshot_signature() );
	}

	public function test_registry_insertion_order_does_not_change_snapshot_identity(): void {
		$first = new AiSystemsRegistry();
		$first->put( $this->system( 'system-b' ) );
		$first->put( $this->system( 'system-a' ) );

		$second = new AiSystemsRegistry();
		$second->put( $this->system( 'system-a' ) );
		$second->put( $this->system( 'system-b' ) );

		$builder         = new EvidenceSnapshotBuilder();
		$first_snapshot  = $builder->build( $first, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' );
		$second_snapshot = $builder->build( $second, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' );

		$this->assertSame( $first_snapshot->snapshot_signature(), $second_snapshot->snapshot_signature() );
		$this->assertSame( 'system-a', $first_snapshot->to_array()['registry']['systems'][0]['id'] );
		$this->assertSame( 'system-b', $first_snapshot->to_array()['registry']['systems'][1]['id'] );
	}

	public function test_discovery_reference_is_normalized_only_for_valid_discovered_source(): void {
		$signature = str_repeat( 'a', 64 );
		$registry  = new AiSystemsRegistry();
		$registry->put(
			$this->system(
				'discovered-a',
				AiSystem::REVIEW_PENDING,
				AiSystem::STATUS_ACTIVE,
				'detector:ai-engine-wordpress-plugin-v1:' . $signature,
				AiSystem::SOURCE_DISCOVERED
			)
		);
		$registry->put(
			$this->system(
				'discovered-malformed',
				AiSystem::REVIEW_PENDING,
				AiSystem::STATUS_ACTIVE,
				'detector:bad:not-a-signature',
				AiSystem::SOURCE_DISCOVERED
			)
		);
		$registry->put(
			$this->system(
				'manual-mimic',
				AiSystem::REVIEW_PENDING,
				AiSystem::STATUS_ACTIVE,
				'detector:ai-engine-wordpress-plugin-v1:' . $signature,
				AiSystem::SOURCE_MANUAL
			)
		);

		$payload = ( new EvidenceSnapshotBuilder() )
			->build( $registry, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' )
			->to_array();

		$this->assertSame(
			array(
				array(
					'system_id'        => 'discovered-a',
					'detector_id'      => 'ai-engine-wordpress-plugin-v1',
					'source_signature' => $signature,
				),
			),
			$payload['discovery_evidence']
		);
	}

	public function test_archived_system_remains_in_registry_and_disclosure_readiness_but_not_findings(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'archived-a', AiSystem::REVIEW_REVIEWED, AiSystem::STATUS_ARCHIVED ) );

		$payload = ( new EvidenceSnapshotBuilder() )
			->build( $registry, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' )
			->to_array();

		$this->assertSame( 'archived-a', $payload['registry']['systems'][0]['id'] );
		$this->assertSame( array(), $payload['findings'] );
		$this->assertSame(
			array(
				array(
					'system_id'    => 'archived-a',
					'eligible'     => false,
					'reason_codes' => array( 'archived' ),
				),
			),
			$payload['disclosure_readiness']
		);
	}

	public function test_findings_omit_volatile_finding_generation_timestamp(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'pending-a', AiSystem::REVIEW_PENDING ) );

		$payload = ( new EvidenceSnapshotBuilder() )
			->build( $registry, '0.1.0', $this->site(), '2026-09-09T20:00:00Z' )
			->to_array();

		$this->assertNotEmpty( $payload['findings'] );
		$this->assertArrayNotHasKey( 'generated_at', $payload['findings'][0] );
		$this->assertArrayHasKey( 'evidence_signature', $payload['findings'][0] );
	}

	public function test_canonical_json_failure_is_fatal(): void {
		$this->expectException( RuntimeException::class );

		EvidenceSnapshotBuilder::canonical_json(
			array(
				'bad_utf8' => "\xB1\x31",
			)
		);
	}

	/**
	 * Build a deterministic test system.
	 */
	private function system(
		string $id,
		string $review_status = AiSystem::REVIEW_PENDING,
		string $status = AiSystem::STATUS_ACTIVE,
		string $source = 'manual:test',
		string $source_origin = AiSystem::SOURCE_MANUAL
	): AiSystem {
		return new AiSystem(
			$id,
			'Test ' . $id,
			AiSystem::TYPE_ASSISTANT,
			$source,
			true,
			array(
				'source_origin'       => $source_origin,
				'status'              => $status,
				'review_status'       => $review_status,
				'interaction_context' => 'Confidential operational context for ' . $id . '.',
				'created_at'          => '2026-09-09T18:00:00Z',
				'updated_at'          => '2026-09-09T18:10:00Z',
				'reviewed_at'         => AiSystem::REVIEW_REVIEWED === $review_status ? '2026-09-09T18:10:00Z' : '',
			)
		);
	}

	/**
	 * Return authoritative site metadata fixture.
	 *
	 * @return array<string, mixed>
	 */
	private function site(): array {
		return array(
			'home_url'     => 'https://example.test/',
			'is_multisite' => false,
			'blog_id'      => 1,
		);
	}
}
