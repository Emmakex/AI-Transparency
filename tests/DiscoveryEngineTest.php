<?php
/**
 * Generic deterministic discovery orchestration tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Discovery\AiEngineDetector;
use Kairoseth\AITransparency\Discovery\DetectorInterface;
use Kairoseth\AITransparency\Discovery\DiscoveryEngine;
use Kairoseth\AITransparency\Discovery\DiscoveryResult;
use Kairoseth\AITransparency\Discovery\PluginObservation;
use PHPUnit\Framework\TestCase;

final class DiscoveryEngineTest extends TestCase {
	public function test_discovers_registered_detectors_in_deterministic_order(): void {
		$second_detector = new class() implements DetectorInterface {
			public function detect( PluginObservation $observation ) {
				if ( 'second/second.php' !== $observation->plugin_file() ) {
					return null;
				}

				return new DiscoveryResult(
					'second-plugin',
					'second',
					'Second AI',
					$observation->version(),
					true,
					array( 'plugin' => $observation->to_array() ),
					str_repeat( 'a', 64 )
				);
			}
		};

		$engine = new DiscoveryEngine(
			array(
				new AiEngineDetector(),
				$second_detector,
			)
		);

		$results = $engine->discover(
			array(
				new PluginObservation( 'second/second.php', 'Second AI', '1.0.0', 'second', true ),
				new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.7', 'ai-engine', true ),
			)
		);

		$this->assertCount( 2, $results );
		$this->assertSame( 'ai-engine-plugin', $results[0]->detector_id() );
		$this->assertSame( 'second-plugin', $results[1]->detector_id() );
	}

	public function test_preserves_supported_and_outside_boundary_results(): void {
		$engine = new DiscoveryEngine( array( new AiEngineDetector() ) );
		$results = $engine->discover(
			array(
				new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.7', 'ai-engine', true ),
				new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.8', 'ai-engine', true ),
			)
		);

		$this->assertCount( 2, $results );
		$this->assertTrue( $results[0]->supported() );
		$this->assertFalse( $results[1]->supported() );
	}

	public function test_finds_only_a_fresh_server_generated_result_matching_both_selectors(): void {
		$engine       = new DiscoveryEngine( array( new AiEngineDetector() ) );
		$observations = array(
			new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.7', 'ai-engine', true ),
		);
		$generated    = $engine->discover( $observations );
		$expected     = $generated[0];

		$matched = $engine->find(
			$observations,
			$expected->detector_id(),
			$expected->source_signature()
		);

		$this->assertNotNull( $matched );
		$this->assertSame( $expected->source_signature(), $matched->source_signature() );
		$this->assertNull( $engine->find( $observations, 'other-detector', $expected->source_signature() ) );
		$this->assertNull( $engine->find( $observations, $expected->detector_id(), str_repeat( '0', 64 ) ) );
		$this->assertNull( $engine->find( $observations, '', $expected->source_signature() ) );
		$this->assertNull( $engine->find( $observations, $expected->detector_id(), '' ) );
	}

	public function test_rejects_invalid_detector_registration(): void {
		$this->expectException( InvalidArgumentException::class );

		new DiscoveryEngine( array( new stdClass() ) );
	}

	public function test_rejects_invalid_observation_input(): void {
		$engine = new DiscoveryEngine( array( new AiEngineDetector() ) );

		$this->expectException( InvalidArgumentException::class );
		$engine->discover( array( new stdClass() ) );
	}

	public function test_find_rejects_invalid_observation_input(): void {
		$engine = new DiscoveryEngine( array( new AiEngineDetector() ) );

		$this->expectException( InvalidArgumentException::class );
		$engine->find( array( new stdClass() ), 'ai-engine-plugin', str_repeat( 'a', 64 ) );
	}
}
