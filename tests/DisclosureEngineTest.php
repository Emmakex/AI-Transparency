<?php
/**
 * Disclosure eligibility engine tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Disclosure\Disclosure;
use Kairoseth\AITransparency\Disclosure\DisclosureEngine;
use Kairoseth\AITransparency\Domain\AiSystem;
use PHPUnit\Framework\TestCase;

final class DisclosureEngineTest extends TestCase {
	public function test_reviewed_active_configured_system_with_context_is_eligible(): void {
		$system = $this->system( AiSystem::STATUS_ACTIVE, AiSystem::REVIEW_REVIEWED, 'Public support assistant.', true );
		$engine = new DisclosureEngine();

		$this->assertSame( array(), $engine->reason_codes( $system ) );
		$this->assertTrue( $engine->is_eligible( $system ) );

		$disclosure = $engine->create_disclosure( $system );
		$this->assertInstanceOf( Disclosure::class, $disclosure );
		$this->assertSame( 'phase5-system', $disclosure->subject_system_id() );
		$this->assertSame( 'Phase 5 Test Assistant', $disclosure->subject_system_name() );
		$this->assertSame( Disclosure::COPY_VERSION_INLINE_V1, $disclosure->copy_version() );
	}

	public function test_pending_review_blocks_disclosure(): void {
		$engine = new DisclosureEngine();
		$system = $this->system( AiSystem::STATUS_ACTIVE, AiSystem::REVIEW_PENDING, 'Public assistant.', true );

		$this->assertSame( array( DisclosureEngine::REASON_PENDING_REVIEW ), $engine->reason_codes( $system ) );
		$this->assertFalse( $engine->is_eligible( $system ) );
		$this->assertNull( $engine->create_disclosure( $system ) );
	}

	public function test_missing_context_blocks_disclosure(): void {
		$engine = new DisclosureEngine();
		$system = $this->system( AiSystem::STATUS_ACTIVE, AiSystem::REVIEW_REVIEWED, '   ', true );

		$this->assertSame( array( DisclosureEngine::REASON_MISSING_INTERACTION_CONTEXT ), $engine->reason_codes( $system ) );
		$this->assertNull( $engine->create_disclosure( $system ) );
	}

	public function test_unconfigured_disclosure_blocks_output(): void {
		$engine = new DisclosureEngine();
		$system = $this->system( AiSystem::STATUS_ACTIVE, AiSystem::REVIEW_REVIEWED, 'Public assistant.', false );

		$this->assertSame( array( DisclosureEngine::REASON_DISCLOSURE_NOT_CONFIGURED ), $engine->reason_codes( $system ) );
		$this->assertNull( $engine->create_disclosure( $system ) );
	}

	public function test_archived_system_blocks_output(): void {
		$engine = new DisclosureEngine();
		$system = $this->system( AiSystem::STATUS_ARCHIVED, AiSystem::REVIEW_REVIEWED, 'Public assistant.', true );

		$this->assertSame( array( DisclosureEngine::REASON_ARCHIVED ), $engine->reason_codes( $system ) );
		$this->assertNull( $engine->create_disclosure( $system ) );
	}

	public function test_multiple_reasons_have_stable_order(): void {
		$engine = new DisclosureEngine();
		$system = $this->system( AiSystem::STATUS_ARCHIVED, AiSystem::REVIEW_PENDING, '', false );

		$this->assertSame(
			array(
				DisclosureEngine::REASON_ARCHIVED,
				DisclosureEngine::REASON_PENDING_REVIEW,
				DisclosureEngine::REASON_MISSING_INTERACTION_CONTEXT,
				DisclosureEngine::REASON_DISCLOSURE_NOT_CONFIGURED,
			),
			$engine->reason_codes( $system )
		);
	}

	private function system( string $status, string $review_status, string $context, bool $disclosure_required ): AiSystem {
		return new AiSystem(
			'phase5-system',
			'Phase 5 Test Assistant',
			AiSystem::TYPE_ASSISTANT,
			'manual:test',
			$disclosure_required,
			array(
				'source_origin'       => AiSystem::SOURCE_MANUAL,
				'status'              => $status,
				'review_status'       => $review_status,
				'interaction_context' => $context,
				'created_at'          => '2026-09-09T18:00:00+00:00',
				'updated_at'          => '2026-09-09T18:00:00+00:00',
				'reviewed_at'         => AiSystem::REVIEW_REVIEWED === $review_status ? '2026-09-09T18:10:00+00:00' : '',
			)
		);
	}
}
