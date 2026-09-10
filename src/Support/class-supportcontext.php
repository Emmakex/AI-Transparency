<?php
/**
 * Phase 7 bounded support context.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Support;

use InvalidArgumentException;

/**
 * Immutable non-sensitive context sent only after an explicit support CTA click.
 */
final class SupportContext {
	/**
	 * Extension slug sent to Kairoseth.
	 */
	public const EXTENSION_SLUG = 'ai-transparency';

	/**
	 * Customer-facing extension name sent to Kairoseth.
	 */
	public const EXTENSION_NAME = 'Kairoseth AI Transparency';

	/**
	 * Host platform identifier.
	 */
	public const HOST_PLATFORM = 'wordpress';

	/**
	 * Canonical ordered context keys.
	 */
	public const CONTEXT_KEYS = array(
		'source',
		'extensionSlug',
		'extensionName',
		'extensionVersion',
		'hostPlatform',
		'hostPlatformVersion',
		'locale',
	);

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $extension_version;

	/**
	 * WordPress version.
	 *
	 * @var string
	 */
	private $host_platform_version;

	/**
	 * Bounded Kairoseth locale.
	 *
	 * @var string
	 */
	private $locale;

	/**
	 * Create server-authoritative support context.
	 *
	 * @param string $extension_version     Real plugin version.
	 * @param string $host_platform_version Real WordPress version.
	 * @param string $locale                Current WordPress locale.
	 */
	public function __construct( string $extension_version, string $host_platform_version, string $locale ) {
		$this->extension_version     = self::bounded_version( $extension_version );
		$this->host_platform_version = self::bounded_version( $host_platform_version );
		$this->locale                = self::normalize_locale( $locale );
	}

	/**
	 * Return the exact non-sensitive query context allow-list.
	 *
	 * @return array<string, string>
	 */
	public function to_array(): array {
		return array(
			'source'              => 'extension',
			'extensionSlug'       => self::EXTENSION_SLUG,
			'extensionName'       => self::EXTENSION_NAME,
			'extensionVersion'    => $this->extension_version,
			'hostPlatform'        => self::HOST_PLATFORM,
			'hostPlatformVersion' => $this->host_platform_version,
			'locale'              => $this->locale,
		);
	}

	/**
	 * Return the bounded locale understood by the Kairoseth intake.
	 *
	 * @return string
	 */
	public function locale(): string {
		return $this->locale;
	}

	/**
	 * Normalize a WordPress locale to the first supported Kairoseth locale set.
	 *
	 * Spanish locales map to `es`; all other locales use the English fallback.
	 *
	 * @param string $locale WordPress locale.
	 * @return string
	 */
	public static function normalize_locale( string $locale ): string {
		$normalized = strtolower( trim( $locale ) );

		return 0 === strpos( $normalized, 'es' ) ? 'es' : 'en';
	}

	/**
	 * Enforce a compact version-token boundary.
	 *
	 * @param string $value Version value.
	 * @return string
	 * @throws InvalidArgumentException When the version is empty, oversized or malformed.
	 */
	private static function bounded_version( string $value ): string {
		$value = trim( $value );

		if ( '' === $value || strlen( $value ) > 40 || 1 !== preg_match( '/^[A-Za-z0-9][A-Za-z0-9._+\-]*$/', $value ) ) {
			throw new InvalidArgumentException( 'Support context contains an invalid bounded version token.' );
		}

		return $value;
	}
}
