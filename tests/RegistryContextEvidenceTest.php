<?php
/**
 * Registry administrator context evidence tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Export\EvidenceSnapshotBuilder;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use PHPUnit\Framework\TestCase;

final class RegistryContextEvidenceTest extends TestCase {
	public function test_administrator_context_is_preserved_in_local_evidence_snapshot(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'Document support use' ) );

		$payload = ( new EvidenceSnapshotBuilder() )
			->build( $registry, '1.1.0', $this->site(), '2026-10-10T11:20:00Z' )
			->to_array();
		$record  = $payload['registry']['systems'][0];

		$this->assertSame( 'Document support use', $record['purpose'] );
		$this->assertSame( 'Example Provider', $record['provider'] );
		$this->assertSame( 'Example Model Family', $record['model'] );
		$this->assertSame( 'Website customers', $record['audience'] );
		$this->assertSame( 'Support staff review escalations.', $record['human_oversight'] );
		$this->assertSame( 'Customer questions and approved support content.', $record['data_use_context'] );
	}

	public function test_context_change_changes_snapshot_identity(): void {
		$builder = new EvidenceSnapshotBuilder();

		$before = new AiSystemsRegistry();
		$before->put( $this->system( 'Document support use' ) );

		$after = new AiSystemsRegistry();
		$after->put( $this->system( 'Document support use and triage' ) );

		$before_snapshot = $builder->build( $before, '1.1.0', $this->site(), '2026-10-10T11:20:00Z' );
		$after_snapshot  = $builder->build( $after, '1.1.0', $this->site(), '2026-10-10T11:20:00Z' );

		$this->assertNotSame( $before_snapshot->snapshot_signature(), $after_snapshot->snapshot_signature() );
	}

	private function system( string $purpose ): AiSystem {
		return new AiSystem(
			'context-evidence-system',
			'Context Evidence System',
			AiSystem::TYPE_ASSISTANT,
			'manual',
			false,
			array(
				'source_origin'    => AiSystem::SOURCE_MANUAL,
				'status'           => AiSystem::STATUS_ACTIVE,
				'review_status'    => AiSystem::REVIEW_REVIEWED,
				'purpose'          => $purpose,
				'provider'         => 'Example Provider',
				'model'            => 'Example Model Family',
				'audience'         => 'Website customers',
				'human_oversight'  => 'Support staff review escalations.',
				'data_use_context' => 'Customer questions and approved support content.',
				'created_at'       => '2026-10-10T10:00:00Z',
				'updated_at'       => '2026-10-10T10:10:00Z',
				'reviewed_at'      => '2026-10-10T10:10:00Z',
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
