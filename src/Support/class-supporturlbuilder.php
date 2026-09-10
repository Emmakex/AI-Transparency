<?php
/**
 * Phase 7 contextual Kairoseth support URL builder.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Support;

use InvalidArgumentException;

/**
 * Builds one bounded HTTPS navigation URL to the verified Kairoseth intake.
 */
final class SupportUrlBuilder {
	/**
	 * Verified production path for Kairoseth Custom Requests.
	 */
	public const CANONICAL_PATH = '/custom-requests';

	/**
	 * Accepted Phase 7 request types.
	 */
	public const REQUEST_TYPES = array(
		'implementation_support',
		'third_party_integration',
		'business_customization',
		'automation',
		'additional_feature',
		'other',
	);

	/**
	 * Exact ordered query key allow-list.
	 */
	public const QUERY_KEYS = array(
		'source',
		'extensionSlug',
		'extensionName',
		'extensionVersion',
		'hostPlatform',
		'hostPlatformVersion',
		'locale',
		'requestType',
	);

	/**
	 * Verified Kairoseth destination.
	 *
	 * @var string
	 */
	private $destination;

	/**
	 * Create a builder for one plugin-owned Kairoseth destination.
	 *
	 * @param string $destination Verified production destination.
	 * @throws InvalidArgumentException When the destination is unsafe.
	 */
	public function __construct( string $destination ) {
		$this->destination = $this->validate_destination( $destination );
	}

	/**
	 * Build the explicit browser-navigation URL.
	 *
	 * @param SupportContext $context      Server-authoritative context.
	 * @param string         $request_type Accepted request type.
	 * @return string
	 * @throws InvalidArgumentException When request type is not accepted.
	 */
	public function build( SupportContext $context, string $request_type ): string {
		if ( ! in_array( $request_type, self::REQUEST_TYPES, true ) ) {
			throw new InvalidArgumentException( 'Unsupported Kairoseth support request type.' );
		}

		$query                = $context->to_array();
		$query['requestType'] = $request_type;

		if ( self::QUERY_KEYS !== array_keys( $query ) ) {
			throw new InvalidArgumentException( 'Support query keys do not match the Phase 7 allow-list.' );
		}

		return $this->destination . '?' . http_build_query( $query, '', '&', PHP_QUERY_RFC3986 );
	}

	/**
	 * Validate the plugin-owned destination and fail closed.
	 *
	 * This class intentionally uses PHP's native URL parser so domain unit tests
	 * remain independent of a running WordPress bootstrap.
	 *
	 * @param string $destination Candidate destination.
	 * @return string
	 * @throws InvalidArgumentException When the destination violates the contract.
	 */
	private function validate_destination( string $destination ): string {
		$destination = trim( $destination );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url -- Pure domain validation must run without a WordPress bootstrap.
		$parts = parse_url( $destination );

		if ( false === $parts || ! is_array( $parts ) ) {
			throw new InvalidArgumentException( 'Kairoseth support destination is invalid.' );
		}

		$scheme = isset( $parts['scheme'] ) ? strtolower( (string) $parts['scheme'] ) : '';
		$host   = isset( $parts['host'] ) ? strtolower( (string) $parts['host'] ) : '';
		$path   = isset( $parts['path'] ) ? (string) $parts['path'] : '';

		if ( 'https' !== $scheme || 'kairoseth.com' !== $host || self::CANONICAL_PATH !== $path ) {
			throw new InvalidArgumentException( 'Kairoseth support destination is not canonical.' );
		}

		foreach ( array( 'user', 'pass', 'query', 'fragment', 'port' ) as $forbidden_part ) {
			if ( isset( $parts[ $forbidden_part ] ) && '' !== (string) $parts[ $forbidden_part ] ) {
				throw new InvalidArgumentException( 'Kairoseth support destination contains an unsafe URL component.' );
			}
		}

		return 'https://kairoseth.com' . self::CANONICAL_PATH;
	}
}
