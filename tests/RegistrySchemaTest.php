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

		$this->assertSame( 2, RegistrySchema::VERSION );
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
		$this->assertSame( '', $system->purpose() );
		$this->assertSame( '', $system->provider() );
		$this->assertSame( '', $system->model() );
		$this->assertSame( '', $system->audience() );
		$this->assertSame( '', $system->human_oversight() );
		$this->assertSame( '', $system->data_use_context() );
	}

	public function test_schema_migrates_version_1_context_to_empty_declarations(): void {
		$payload = array(
			'schema_version' => 1,
			'systems'        => array(
				array(
					'id'                  => 'v1-system',
					'name'                => 'Version 1 System',
					'type'                => AiSystem::TYPE_ASSISTANT,
					'source'              => 'manual',
					'interaction_context' => 'Internal workflow',
				),
			),
		);

		$migrated = RegistrySchema::migrate( $payload );

		$this->assertSame( RegistrySchema::VERSION, $migrated['schema_version'] );
		$this->assertSame( 'Internal workflow', $migrated['systems'][0]['interaction_context'] );
		$this->assertSame( '', $migrated['systems'][0]['purpose'] );
		$this->assertSame( '', $migrated['systems'][0]['provider'] );
		$this->assertSame( '', $migrated['systems'][0]['model'] );
		$this->assertSame( '', $migrated['systems'][0]['audience'] );
		$this->assertSame( '', $migrated['systems'][0]['human_oversight'] );
		$this->assertSame( '', $migrated['systems'][0]['data_use_context'] );
	}

	public function test_schema_round_trips_v2_administrator_context(): void {
		$registry = new AiSystemsRegistry();
		$registry->put(
			new AiSystem(
				'context-system',
				'Context System',
				AiSystem::TYPE_ASSISTANT,
				'manual',
				false,
				array(
					'purpose'          => 'Assist internal research.',
					'provider'         => 'Example Provider',
					'model'            => 'Example Model',
					'audience'         => 'Research team',
					'human_oversight'  => 'A researcher approves outputs before use.',
					'data_use_context' => 'Internal notes and public references.',
				)
			)
		);

		$decoded = RegistrySchema::decode( RegistrySchema::encode( $registry ) );
		$system  = $decoded->find( 'context-system' );

		$this->assertNotNull( $system );
		$this->assertSame( 'Assist internal research.', $system->purpose() );
		$this->assertSame( 'Example Provider', $system->provider() );
		$this->assertSame( 'Example Model', $system->model() );
		$this->assertSame( 'Research team', $system->audience() );
		$this->assertSame( 'A researcher approves outputs before use.', $system->human_oversight() );
		$this->assertSame( 'Internal notes and public references.', $system->data_use_context() );
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
