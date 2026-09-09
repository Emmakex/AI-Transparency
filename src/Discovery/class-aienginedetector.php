<?php
/**
 * Deterministic detector for the AI Engine WordPress plugin.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Discovery;

/**
 * Detects a validated AI Engine installation from WordPress plugin inventory evidence.
 */
final class AiEngineDetector {
	public const DETECTOR_ID       = 'ai-engine-plugin';
	public const INTEGRATION_SLUG  = 'ai-engine';
	public const INTEGRATION_NAME  = 'AI Engine';
	public const PLUGIN_FILE       = 'ai-engine/ai-engine.php';
	public const TEXT_DOMAIN       = 'ai-engine';
	public const SUPPORTED_VERSION = '3.7.7';
	public const CONTRACT_VERSION  = 1;

	/**
	 * Detect AI Engine from one immutable WordPress plugin observation.
	 *
	 * The detector proves only that the exact AI Engine plugin identity is active
	 * and records its observed version. It does not inspect provider settings,
	 * API keys, models, chatbots, generated content or legal obligations.
	 *
	 * @param PluginObservation $observation WordPress plugin observation.
	 * @return DiscoveryResult|null
	 */
	public function detect( PluginObservation $observation ) {
		if ( self::PLUGIN_FILE !== $observation->plugin_file() ) {
			return null;
		}

		if ( self::TEXT_DOMAIN !== $observation->text_domain() ) {
			return null;
		}

		if ( 0 !== strpos( $observation->name(), self::INTEGRATION_NAME ) ) {
			return null;
		}

		if ( ! $observation->active() ) {
			return null;
		}

		$evidence = array(
			'detector_contract_version' => self::CONTRACT_VERSION,
			'observation_type'           => 'active_wordpress_plugin_inventory',
			'supported_version'          => self::SUPPORTED_VERSION,
			'plugin'                     => $observation->to_array(),
		);

		$encoded_evidence = wp_json_encode( $evidence, JSON_UNESCAPED_SLASHES );
		if ( false === $encoded_evidence ) {
			return null;
		}

		return new DiscoveryResult(
			self::DETECTOR_ID,
			self::INTEGRATION_SLUG,
			self::INTEGRATION_NAME,
			$observation->version(),
			self::SUPPORTED_VERSION === $observation->version(),
			$evidence,
			hash( 'sha256', $encoded_evidence )
		);
	}
}
