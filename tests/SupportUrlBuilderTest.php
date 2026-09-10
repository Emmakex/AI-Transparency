<?php
/**
 * Phase 7 contextual support contract tests.
 *
 * @package KairosethAITransparency
 */

use InvalidArgumentException;
use Kairoseth\AITransparency\Support\SupportContext;
use Kairoseth\AITransparency\Support\SupportUrlBuilder;
use PHPUnit\Framework\TestCase;

final class SupportUrlBuilderTest extends TestCase {
	/**
	 * Canonical production destination used by the plugin.
	 */
	private const DESTINATION = 'https://kairoseth.com/custom-requests';

	public function test_context_has_exact_non_sensitive_allow_list(): void {
		$context = new SupportContext( '0.1.0', '6.8.2', 'en_US' );
		$payload = $context->to_array();

		$this->assertSame( SupportContext::CONTEXT_KEYS, array_keys( $payload ) );
		$this->assertSame(
			array(
				'source'              => 'extension',
				'extensionSlug'       => 'ai-transparency',
				'extensionName'       => 'Kairoseth AI Transparency',
				'extensionVersion'    => '0.1.0',
				'hostPlatform'        => 'wordpress',
				'hostPlatformVersion' => '6.8.2',
				'locale'              => 'en',
			),
			$payload
		);

		foreach ( array( 'siteUrl', 'homeUrl', 'registry', 'evidence', 'snapshot_signature', 'userEmail', 'prompt', 'log', 'credential' ) as $forbidden_key ) {
			$this->assertArrayNotHasKey( $forbidden_key, $payload );
		}
	}

	public function test_spanish_wordpress_locale_maps_to_bounded_spanish_context(): void {
		$context = new SupportContext( '0.1.0', '6.8.2', 'es_ES' );

		$this->assertSame( 'es', $context->locale() );
		$this->assertSame( 'es', $context->to_array()['locale'] );
	}

	public function test_non_spanish_locale_uses_english_fallback(): void {
		$context = new SupportContext( '0.1.0', '6.8.2', 'fr_FR' );

		$this->assertSame( 'en', $context->locale() );
	}

	/**
	 * @dataProvider invalid_version_provider
	 */
	public function test_context_rejects_unbounded_or_malformed_versions( string $plugin_version, string $wordpress_version ): void {
		$this->expectException( InvalidArgumentException::class );

		new SupportContext( $plugin_version, $wordpress_version, 'en_US' );
	}

	/**
	 * Invalid version fixtures.
	 *
	 * @return array<string, array{string, string}>
	 */
	public function invalid_version_provider(): array {
		return array(
			'empty plugin version' => array( '', '6.8.2' ),
			'oversized plugin version' => array( str_repeat( 'a', 41 ), '6.8.2' ),
			'plugin query injection' => array( '0.1.0&siteUrl=https://example.test', '6.8.2' ),
			'wordpress query injection' => array( '0.1.0', '6.8.2?admin=1' ),
		);
	}

	public function test_builder_emits_only_canonical_encoded_query_keys(): void {
		$builder = new SupportUrlBuilder( self::DESTINATION );
		$url     = $builder->build( new SupportContext( '0.1.0', '6.8.2', 'en_US' ), 'implementation_support' );
		$parts   = parse_url( $url );

		$this->assertIsArray( $parts );
		$this->assertSame( 'https', $parts['scheme'] );
		$this->assertSame( 'kairoseth.com', $parts['host'] );
		$this->assertSame( '/custom-requests', $parts['path'] );
		$this->assertStringContainsString( 'extensionName=Kairoseth%20AI%20Transparency', $url );

		parse_str( $parts['query'], $query );
		$this->assertSame( SupportUrlBuilder::QUERY_KEYS, array_keys( $query ) );
		$this->assertSame( 'implementation_support', $query['requestType'] );
		$this->assertArrayNotHasKey( 'siteUrl', $query );
		$this->assertArrayNotHasKey( 'registry', $query );
		$this->assertArrayNotHasKey( 'evidence', $query );
	}

	public function test_all_accepted_request_types_build_and_no_other_type_does(): void {
		$builder = new SupportUrlBuilder( self::DESTINATION );
		$context = new SupportContext( '0.1.0', '6.8.2', 'en_US' );

		foreach ( SupportUrlBuilder::REQUEST_TYPES as $request_type ) {
			$url = $builder->build( $context, $request_type );
			$this->assertStringContainsString( 'requestType=' . rawurlencode( $request_type ), $url );
		}

		$this->expectException( InvalidArgumentException::class );
		$builder->build( $context, 'arbitrary_browser_value' );
	}

	/**
	 * @dataProvider unsafe_destination_provider
	 */
	public function test_builder_fails_closed_for_unsafe_destination( string $destination ): void {
		$this->expectException( InvalidArgumentException::class );

		new SupportUrlBuilder( $destination );
	}

	/**
	 * Unsafe production destination fixtures.
	 *
	 * @return array<string, array{string}>
	 */
	public function unsafe_destination_provider(): array {
		return array(
			'http scheme' => array( 'http://kairoseth.com/custom-requests' ),
			'foreign host' => array( 'https://example.com/custom-requests' ),
			'lookalike host' => array( 'https://kairoseth.com.example.com/custom-requests' ),
			'wrong path' => array( 'https://kairoseth.com/support' ),
			'userinfo' => array( 'https://user:pass@kairoseth.com/custom-requests' ),
			'preloaded query' => array( 'https://kairoseth.com/custom-requests?siteUrl=https://example.test' ),
			'fragment payload' => array( 'https://kairoseth.com/custom-requests#secret' ),
			'custom port' => array( 'https://kairoseth.com:8443/custom-requests' ),
		);
	}
}
