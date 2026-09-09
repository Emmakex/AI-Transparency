<?php
/**
 * Registry schema tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;
use Kairoseth\AITransparency\Registry\RegistrySchema;
use PHPUnit\Framework\TestCase;

final class RegistrySchemaTest extends TestCase {
	public function test_schema_encodes_deterministic_versioned_payload(): void {
		$registry = new AiSystemsRegistry();
		$registry->put( new AiSystem( 'b', 'B', 'other', 'manual' ) );
		$registry->put( new AiSystem( 'a', 'A', 'chatbot', 'manual' ) );

		$payload = RegistrySchema::encode( $registry );

		$this->assertSame( RegistrySchema::VERSION, $payload['schema_version'] );
		$this->assertSame( 'a', $payload['systems'][0]['id'] );
		$this->assertSame( 'b', $payload['systems'][1]['id'] );
	}

	public function test_schema_migrates_bootstrap_record_without_losing_it(): void {
		$legacy = array(
			array(
				'id'                              => 'legacy-chat',
				'name'                            => 'Legacy Chat',
				'type'                            => 'chatbot',
				'source'                          => 'manual',
				'interaction_disclosure_required' => true,
			),
		);

		$registry = RegistrySchema::decode( $legacy );
		$system   = $registry->find( 'legacy-chat' );

		$this->assertNotNull( $system );
		$this->assertSame( AiSystem::STATUS_ACTIVE, $system->status() );
		$this->assertSame( AiSystem::REVIEW_PENDING, $system->review_status() );
		$this->assertSame( AiSystem::SOURCE_MANUAL, $system->source_origin() );
		$this->assertTrue( $system->interaction_disclosure_required() );
	}

	public function test_invalid_individual_record_does_not_destroy_valid_registry_records(): void {
		$payload = array(
			'schema_version' => 1,
			'systems'        => array(
				array(
					'id'     => 'valid',
					'name'   => 'Valid',
					'type'   => 'assistant',
					'source' => 'manual',
				),
				array(
					'id'     => '',
					'name'   => 'Broken',
					'type'   => 'assistant',
					'source' => 'manual',
				),
			),
		);

		$registry = RegistrySchema::decode( $payload );

		$this->assertSame( 1, $registry->count() );
		$this->assertNotNull( $registry->find( 'valid' ) );
	}

	public function test_future_unknown_schema_fails_safe_to_empty_registry(): void {
		$registry = RegistrySchema::decode(
			array(
				'schema_version' => 999,
				'systems'        => array(
					array(
						'id'     => 'future',
						'name'   => 'Future',
						'type'   => 'other',
						'source' => 'manual',
					),
				),
			)
		);

		$this->assertSame( 0, $registry->count() );
	}
}
