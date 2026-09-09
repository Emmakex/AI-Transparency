<?php
/**
 * Readiness finding engine tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Evidence\Finding;
use Kairoseth\AITransparency\Evidence\FindingEngine;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use PHPUnit\Framework\TestCase;

final class FindingEngineTest extends TestCase {
	public function test_pending_system_without_context_produces_two_review_findings(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'assistant-1', AiSystem::REVIEW_PENDING, '', false ) );

		$findings = ( new FindingEngine() )->generate( $registry, '2026-09-09T17:00:00+00:00' );

		$this->assertCount( 2, $findings );
		$this->assertSame( Finding::FACT_CONTEXT_MISSING, $findings[0]->fact_code() );
		$this->assertSame( Finding::FACT_REVIEW_PENDING, $findings[1]->fact_code() );
		$this->assertSame( Finding::PRIORITY_REVIEW, $findings[0]->priority() );
		$this->assertSame( Finding::PRIORITY_REVIEW, $findings[1]->priority() );
	}

	public function test_reviewed_system_with_context_has_no_completeness_findings(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'assistant-2', AiSystem::REVIEW_REVIEWED, 'Public support flow.', false ) );

		$this->assertSame( array(), ( new FindingEngine() )->generate( $registry, '2026-09-09T17:00:00+00:00' ) );
	}

	public function test_disclosure_declaration_creates_separate_declaration_finding(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'assistant-3', AiSystem::REVIEW_REVIEWED, 'Public assistant.', true ) );

		$findings = ( new FindingEngine() )->generate( $registry, '2026-09-09T17:00:00+00:00' );

		$this->assertCount( 1, $findings );
		$this->assertSame( Finding::FACT_SYSTEM_ACTIVE, $findings[0]->fact_code() );
		$this->assertSame( Finding::DECLARATION_DISCLOSURE_REQUIRED, $findings[0]->declaration_code() );
		$this->assertSame( Finding::GUIDANCE_VERIFY_DISCLOSURE, $findings[0]->guidance_code() );
	}

	public function test_archived_systems_do_not_generate_current_findings(): void {
		$registry = new AiSystemsRegistry();
		$system   = $this->system( 'assistant-4', AiSystem::REVIEW_PENDING, '', true )->with_updates(
			array( 'status' => AiSystem::STATUS_ARCHIVED )
		);
		$registry->put( $system );

		$this->assertSame( array(), ( new FindingEngine() )->generate( $registry, '2026-09-09T17:00:00+00:00' ) );
	}

	public function test_finding_id_and_signature_are_stable_across_generation_times(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( $this->system( 'assistant-5', AiSystem::REVIEW_PENDING, 'Internal assistant.', false ) );
		$engine = new FindingEngine();

		$first  = $engine->generate( $registry, '2026-09-09T17:00:00+00:00' );
		$second = $engine->generate( $registry, '2026-09-09T18:00:00+00:00' );

		$this->assertCount( 1, $first );
		$this->assertSame( $first[0]->id(), $second[0]->id() );
		$this->assertSame( $first[0]->evidence_signature(), $second[0]->evidence_signature() );
		$this->assertNotSame( $first[0]->generated_at(), $second[0]->generated_at() );
	}

	private function system( string $id, string $review_status, string $context, bool $disclosure_required ): AiSystem {
		return new AiSystem(
			$id,
			'Test AI System',
			AiSystem::TYPE_ASSISTANT,
			'manual:test',
			$disclosure_required,
			array(
				'source_origin'       => AiSystem::SOURCE_MANUAL,
				'status'              => AiSystem::STATUS_ACTIVE,
				'review_status'       => $review_status,
				'interaction_context' => $context,
				'created_at'          => '2026-09-09T15:00:00+00:00',
				'updated_at'          => '2026-09-09T15:00:00+00:00',
				'reviewed_at'         => AiSystem::REVIEW_REVIEWED === $review_status ? '2026-09-09T15:30:00+00:00' : '',
			)
		);
	}
}
