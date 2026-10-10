<?php
/**
 * AI Puffer deterministic discovery tests.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Discovery\AiPufferDetector;
use Kairoseth\AITransparency\Discovery\PluginObservation;
use Kairoseth\AITransparency\Domain\AiSystem;
use PHPUnit\Framework\TestCase;

final class AiPufferDetectorTest extends TestCase {
	public function test_detects_exact_supported_ai_puffer_boundary(): void {
		$detector    = new AiPufferDetector();
		$observation = new PluginObservation(
			'gpt3-ai-content-generator/gpt3-ai-content-generator.php',
			'AI Puffer – AI Chatbot, AI Writer & Automation',
			'2.4.97',
			'gpt3-ai-content-generator',
			true
		);
		$result      = $detector->detect( $observation );
		$repeat      = $detector->detect( $observation );

		$this->assertNotNull( $result );
		$this->assertNotNull( $repeat );
		$this->assertTrue( $result->supported() );
		$this->assertSame( 'ai-puffer-plugin', $result->detector_id() );
		$this->assertSame( 'ai-puffer', $result->integration_slug() );
		$this->assertSame( 'AI Puffer', $result->integration_name() );
		$this->assertSame( '2.4.97', $result->observed_version() );
		$this->assertSame( 64, strlen( $result->source_signature() ) );
		$this->assertSame( $result->source_signature(), $repeat->source_signature() );
	}

	public function test_ignores_unrelated_or_inactive_plugins(): void {
		$detector = new AiPufferDetector();

		$this->assertNull( $detector->detect( new PluginObservation( 'other/other.php', 'AI Puffer', '2.4.97', 'gpt3-ai-content-generator', true ) ) );
		$this->assertNull( $detector->detect( new PluginObservation( 'gpt3-ai-content-generator/gpt3-ai-content-generator.php', 'AI Puffer', '2.4.97', 'wrong-domain', true ) ) );
		$this->assertNull( $detector->detect( new PluginObservation( 'gpt3-ai-content-generator/gpt3-ai-content-generator.php', 'Different Plugin', '2.4.97', 'gpt3-ai-content-generator', true ) ) );
		$this->assertNull( $detector->detect( new PluginObservation( 'gpt3-ai-content-generator/gpt3-ai-content-generator.php', 'AI Puffer', '2.4.97', 'gpt3-ai-content-generator', false ) ) );
	}

	public function test_reports_other_versions_without_accepting_them(): void {
		$detector = new AiPufferDetector();
		$result   = $detector->detect(
			new PluginObservation(
				'gpt3-ai-content-generator/gpt3-ai-content-generator.php',
				'AI Puffer – AI Chatbot, AI Writer & Automation',
				'2.4.98',
				'gpt3-ai-content-generator',
				true
			)
		);

		$this->assertNotNull( $result );
		$this->assertFalse( $result->supported() );
		$this->assertNull( $result->candidate( '2026-10-10T10:00:00+00:00' ) );
	}

	public function test_supported_result_builds_review_pending_discovered_candidate(): void {
		$detector = new AiPufferDetector();
		$result   = $detector->detect(
			new PluginObservation(
				'gpt3-ai-content-generator/gpt3-ai-content-generator.php',
				'AI Puffer – AI Chatbot, AI Writer & Automation',
				'2.4.97',
				'gpt3-ai-content-generator',
				true
			)
		);
		$candidate = $result->candidate( '2026-10-10T10:00:00+00:00' );

		$this->assertNotNull( $candidate );
		$this->assertSame( 'discovered-ai-puffer', $candidate->id() );
		$this->assertSame( 'AI Puffer', $candidate->name() );
		$this->assertSame( AiSystem::TYPE_OTHER, $candidate->type() );
		$this->assertSame( AiSystem::SOURCE_DISCOVERED, $candidate->source_origin() );
		$this->assertSame( AiSystem::REVIEW_PENDING, $candidate->review_status() );
		$this->assertFalse( $candidate->interaction_disclosure_required() );
		$this->assertStringStartsWith( 'detector:ai-puffer-plugin:', $candidate->source() );
	}
}
