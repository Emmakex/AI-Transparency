<?php
/**
 * Deterministic detector for the AI Puffer WordPress plugin.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Discovery;

/**
 * Detects a validated AI Puffer installation from WordPress plugin inventory evidence.
 */
final class AiPufferDetector implements DetectorInterface {
	public const DETECTOR_ID       = 'ai-puffer-plugin';
	public const INTEGRATION_SLUG  = 'ai-puffer';
	public const INTEGRATION_NAME  = 'AI Puffer';
	public const PLUGIN_FILE       = 'gpt3-ai-content-generator/gpt3-ai-content-generator.php';
	public const TEXT_DOMAIN       = 'gpt3-ai-content-generator';
	public const SUPPORTED_VERSION = '2.4.97';
	public const CONTRACT_VERSION  = 1;

	/**
	 * Detect AI Puffer from one immutable WordPress plugin observation.
	 *
	 * The detector proves only that the exact AI Puffer plugin identity is active
	 * and records its observed version. It does not inspect provider settings,
	 * API keys, models, chatbots, generated content, automations or legal duties.
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
			'observation_type'          => 'active_wordpress_plugin_inventory',
			'supported_version'         => self::SUPPORTED_VERSION,
			'plugin'                    => $observation->to_array(),
		);

		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode -- Pure detector intentionally has no WordPress runtime dependency.
		$encoded_evidence = json_encode( $evidence, JSON_UNESCAPED_SLASHES );
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
