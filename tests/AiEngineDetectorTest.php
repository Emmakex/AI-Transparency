<?php
/**
 * AI Engine deterministic discovery tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Discovery\AiEngineDetector;
use Kairoseth\AITransparency\Discovery\PluginObservation;
use Kairoseth\AITransparency\Domain\AiSystem;
use PHPUnit\Framework\TestCase;

final class AiEngineDetectorTest extends TestCase {
	public function test_detects_exact_supported_ai_engine_boundary(): void {
		$detector    = new AiEngineDetector();
		$observation = new PluginObservation(
			'ai-engine/ai-engine.php',
			'AI Engine – The Chatbot, AI Framework & MCP for WordPress',
			'3.7.7',
			'ai-engine',
			true
		);
		$result      = $detector->detect( $observation );
		$repeat      = $detector->detect( $observation );

		$this->assertNotNull( $result );
		$this->assertNotNull( $repeat );
		$this->assertTrue( $result->supported() );
		$this->assertSame( 'ai-engine-plugin', $result->detector_id() );
		$this->assertSame( 'ai-engine', $result->integration_slug() );
		$this->assertSame( 'AI Engine', $result->integration_name() );
		$this->assertSame( '3.7.7', $result->observed_version() );
		$this->assertSame( 64, strlen( $result->source_signature() ) );
		$this->assertSame( $result->source_signature(), $repeat->source_signature() );
	}

	public function test_ignores_unrelated_or_inactive_plugins(): void {
		$detector = new AiEngineDetector();

		$this->assertNull( $detector->detect( new PluginObservation( 'other/other.php', 'AI Engine', '3.7.7', 'ai-engine', true ) ) );
		$this->assertNull( $detector->detect( new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.7', 'wrong-domain', true ) ) );
		$this->assertNull( $detector->detect( new PluginObservation( 'ai-engine/ai-engine.php', 'Different Plugin', '3.7.7', 'ai-engine', true ) ) );
		$this->assertNull( $detector->detect( new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.7', 'ai-engine', false ) ) );
	}

	public function test_reports_newer_or_older_versions_without_accepting_them(): void {
		$detector = new AiEngineDetector();
		$result   = $detector->detect( new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.8', 'ai-engine', true ) );

		$this->assertNotNull( $result );
		$this->assertFalse( $result->supported() );
		$this->assertNull( $result->candidate( '2026-09-09T15:30:00+00:00' ) );
	}

	public function test_supported_result_builds_review_pending_discovered_candidate(): void {
		$detector  = new AiEngineDetector();
		$result    = $detector->detect( new PluginObservation( 'ai-engine/ai-engine.php', 'AI Engine', '3.7.7', 'ai-engine', true ) );
		$candidate = $result->candidate( '2026-09-09T15:30:00+00:00' );

		$this->assertNotNull( $candidate );
		$this->assertSame( 'discovered-ai-engine', $candidate->id() );
		$this->assertSame( 'AI Engine', $candidate->name() );
		$this->assertSame( AiSystem::TYPE_OTHER, $candidate->type() );
		$this->assertSame( AiSystem::SOURCE_DISCOVERED, $candidate->source_origin() );
		$this->assertSame( AiSystem::REVIEW_PENDING, $candidate->review_status() );
		$this->assertFalse( $candidate->interaction_disclosure_required() );
		$this->assertStringStartsWith( 'detector:ai-engine-plugin:', $candidate->source() );
	}

	public function test_plugin_observation_normalizes_windows_style_plugin_paths(): void {
		$observation = new PluginObservation( 'ai-engine\\ai-engine.php', 'AI Engine', '3.7.7', 'ai-engine', true );

		$this->assertSame( 'ai-engine/ai-engine.php', $observation->plugin_file() );
		$this->assertSame( 'ai-engine', $observation->plugin_slug() );
	}
}
