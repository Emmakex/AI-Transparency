<?php
/**
 * Per-system Action Center engine tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Action\SystemAction;
use Kairoseth\AITransparency\Action\SystemActionEngine;
use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Evidence\FindingEngine;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use PHPUnit\Framework\TestCase;

final class SystemActionEngineTest extends TestCase {
	public function test_pending_review_is_the_highest_priority_action(): void {
		$system  = $this->system( 'pending', 'Pending system', AiSystem::REVIEW_PENDING, '', false, false );
		$actions = $this->actions_for( array( $system ) );

		$this->assertCount( 1, $actions );
		$this->assertSame( SystemAction::REVIEW_RECORD, $actions[0]->code() );
		$this->assertSame( 10, $actions[0]->rank() );
		$this->assertNotSame( '', $actions[0]->finding_id() );
	}

	public function test_reviewed_system_without_interaction_context_is_routed_to_context_editor(): void {
		$system  = $this->system( 'context', 'Context system', AiSystem::REVIEW_REVIEWED, '', false, true );
		$actions = $this->actions_for( array( $system ) );

		$this->assertSame( SystemAction::DOCUMENT_INTERACTION_CONTEXT, $actions[0]->code() );
		$this->assertSame( 20, $actions[0]->rank() );
	}

	public function test_reviewed_system_with_incomplete_administrator_context_is_routed_to_admin_context(): void {
		$system  = $this->system( 'admin-context', 'Admin context system', AiSystem::REVIEW_REVIEWED, 'Public support flow.', false, false );
		$actions = $this->actions_for( array( $system ) );

		$this->assertSame( SystemAction::COMPLETE_ADMIN_CONTEXT, $actions[0]->code() );
		$this->assertSame( 30, $actions[0]->rank() );
	}

	public function test_complete_system_without_disclosure_configuration_is_routed_to_disclosure_review(): void {
		$system  = $this->system( 'disclosure-review', 'Disclosure review system', AiSystem::REVIEW_REVIEWED, 'Public support flow.', false, true );
		$actions = $this->actions_for( array( $system ) );

		$this->assertSame( SystemAction::REVIEW_DISCLOSURE, $actions[0]->code() );
		$this->assertSame( 50, $actions[0]->rank() );
	}

	public function test_eligible_system_is_routed_to_disclosure_placement(): void {
		$system  = $this->system( 'ready', 'Ready system', AiSystem::REVIEW_REVIEWED, 'Public support flow.', true, true );
		$actions = $this->actions_for( array( $system ) );

		$this->assertSame( SystemAction::PLACE_DISCLOSURE, $actions[0]->code() );
		$this->assertSame( 60, $actions[0]->rank() );
	}

	public function test_archived_system_does_not_generate_an_action(): void {
		$system = $this->system( 'archived', 'Archived system', AiSystem::REVIEW_PENDING, '', false, false )->with_updates(
			array( 'status' => AiSystem::STATUS_ARCHIVED )
		);

		$this->assertSame( array(), $this->actions_for( array( $system ) ) );
	}

	public function test_actions_are_sorted_by_urgency_then_system_name(): void {
		$systems = array(
			$this->system( 'ready', 'Zulu', AiSystem::REVIEW_REVIEWED, 'Public support flow.', true, true ),
			$this->system( 'pending-b', 'Beta', AiSystem::REVIEW_PENDING, '', false, false ),
			$this->system( 'pending-a', 'Alpha', AiSystem::REVIEW_PENDING, '', false, false ),
			$this->system( 'context', 'Context', AiSystem::REVIEW_REVIEWED, '', false, true ),
		);

		$actions = $this->actions_for( $systems );

		$this->assertSame(
			array( 'pending-a', 'pending-b', 'context', 'ready' ),
			array_map(
				static function ( SystemAction $action ): string {
					return $action->system_id();
				},
				$actions
			)
		);
	}

	/**
	 * @param AiSystem[] $systems Systems.
	 * @return SystemAction[]
	 */
	private function actions_for( array $systems ): array {
		$registry = new AiSystemsRegistry();
		foreach ( $systems as $system ) {
			$registry->put( $system );
		}

		$findings = ( new FindingEngine() )->generate( $registry, '2026-10-22T19:00:00+00:00' );
		return ( new SystemActionEngine() )->generate( $registry->all(), $findings );
	}

	private function system(
		string $id,
		string $name,
		string $review_status,
		string $context,
		bool $disclosure_required,
		bool $complete_admin_context
	): AiSystem {
		$metadata = array(
			'source_origin'       => AiSystem::SOURCE_MANUAL,
			'status'              => AiSystem::STATUS_ACTIVE,
			'review_status'       => $review_status,
			'interaction_context' => $context,
			'created_at'          => '2026-10-22T18:00:00+00:00',
			'updated_at'          => '2026-10-22T18:00:00+00:00',
			'reviewed_at'         => AiSystem::REVIEW_REVIEWED === $review_status ? '2026-10-22T18:30:00+00:00' : '',
		);

		if ( $complete_admin_context ) {
			$metadata = array_merge(
				$metadata,
				array(
					'purpose'          => 'Support the documented workflow.',
					'provider'         => 'Example Provider',
					'model'            => 'Example Model',
					'audience'         => 'Documented users',
					'human_oversight'  => 'A responsible person reviews escalations.',
					'data_use_context' => 'Documented operational input categories.',
				)
			);
		}

		return new AiSystem(
			$id,
			$name,
			AiSystem::TYPE_ASSISTANT,
			'manual:test',
			$disclosure_required,
			$metadata
		);
	}
}
