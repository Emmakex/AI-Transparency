<?php
/**
 * AI systems registry tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use PHPUnit\Framework\TestCase;

final class AiSystemsRegistryTest extends TestCase {
	public function test_registry_replaces_existing_system_by_stable_id(): void {
		$registry = new AiSystemsRegistry();

		$registry->put( new AiSystem( 'chatbot-main', 'Chatbot', 'chatbot', 'manual', true ) );
		$registry->put( new AiSystem( 'chatbot-main', 'Support Chatbot', 'chatbot', 'detector:example', true ) );

		$this->assertSame( 1, $registry->count() );
		$this->assertSame( 'Support Chatbot', $registry->find( 'chatbot-main' )->name() );
		$this->assertSame( 'detector:example', $registry->find( 'chatbot-main' )->source() );
	}

	public function test_registry_returns_systems_in_deterministic_id_order(): void {
		$registry = new AiSystemsRegistry();

		$registry->put( new AiSystem( 'z-system', 'Z', 'other', 'manual' ) );
		$registry->put( new AiSystem( 'a-system', 'A', 'chatbot', 'manual' ) );

		$systems = $registry->all();

		$this->assertSame( 'a-system', $systems[0]->id() );
		$this->assertSame( 'z-system', $systems[1]->id() );
	}

	public function test_ai_system_rejects_empty_required_fields(): void {
		$this->expectException( InvalidArgumentException::class );

		new AiSystem( '', 'Chatbot', 'chatbot', 'manual' );
	}

	public function test_ai_system_rejects_unknown_taxonomy_values(): void {
		$this->expectException( InvalidArgumentException::class );

		new AiSystem( 'x', 'Unknown', 'magic-ai', 'manual' );
	}

	public function test_ai_system_exports_stable_phase_2_shape(): void {
		$system = new AiSystem(
			'assistant',
			'Assistant',
			AiSystem::TYPE_ASSISTANT,
			'manual',
			true,
			array(
				'source_origin'       => AiSystem::SOURCE_MANUAL,
				'status'              => AiSystem::STATUS_ACTIVE,
				'review_status'       => AiSystem::REVIEW_REVIEWED,
				'interaction_context' => 'Support widget',
				'created_at'          => '2026-09-09T10:00:00+00:00',
				'updated_at'          => '2026-09-09T11:00:00+00:00',
				'reviewed_at'         => '2026-09-09T11:00:00+00:00',
			)
		);

		$this->assertSame(
			array(
				'id'                              => 'assistant',
				'name'                            => 'Assistant',
				'type'                            => AiSystem::TYPE_ASSISTANT,
				'source'                          => 'manual',
				'source_origin'                   => AiSystem::SOURCE_MANUAL,
				'status'                          => AiSystem::STATUS_ACTIVE,
				'review_status'                   => AiSystem::REVIEW_REVIEWED,
				'interaction_context'             => 'Support widget',
				'interaction_disclosure_required' => true,
				'created_at'                      => '2026-09-09T10:00:00+00:00',
				'updated_at'                      => '2026-09-09T11:00:00+00:00',
				'reviewed_at'                     => '2026-09-09T11:00:00+00:00',
			),
			$system->to_array()
		);
	}

	public function test_ai_system_can_be_archived_without_losing_existing_metadata(): void {
		$system   = new AiSystem( 'assistant', 'Assistant', 'assistant', 'manual' );
		$archived = $system->with_updates(
			array(
				'status'     => AiSystem::STATUS_ARCHIVED,
				'updated_at' => '2026-09-09T12:00:00+00:00',
			)
		);

		$this->assertSame( AiSystem::STATUS_ARCHIVED, $archived->status() );
		$this->assertSame( 'Assistant', $archived->name() );
		$this->assertSame( 'manual', $archived->source() );
	}
}
