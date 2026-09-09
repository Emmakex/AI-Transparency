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

	public function test_ai_system_exports_stable_shape(): void {
		$system = new AiSystem( 'assistant', 'Assistant', 'chatbot', 'manual', true );

		$this->assertSame(
			array(
				'id'                              => 'assistant',
				'name'                            => 'Assistant',
				'type'                            => 'chatbot',
				'source'                          => 'manual',
				'interaction_disclosure_required' => true,
			),
			$system->toArray()
		);
	}
}
