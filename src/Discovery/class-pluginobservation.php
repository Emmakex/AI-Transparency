<?php
/**
 * Deterministic WordPress plugin observation.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Discovery;

use InvalidArgumentException;

/**
 * Immutable evidence observed from the WordPress plugin inventory.
 */
final class PluginObservation {
	/**
	 * WordPress plugin basename.
	 *
	 * @var string
	 */
	private $plugin_file;

	/**
	 * Plugin header name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Plugin header version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Plugin header text domain.
	 *
	 * @var string
	 */
	private $text_domain;

	/**
	 * Whether WordPress reports the plugin as active.
	 *
	 * @var bool
	 */
	private $active;

	/**
	 * Create one plugin observation.
	 *
	 * @param string $plugin_file Plugin basename reported by WordPress.
	 * @param string $name Plugin header name.
	 * @param string $version Plugin header version.
	 * @param string $text_domain Plugin header text domain.
	 * @param bool   $active Whether WordPress reports the plugin as active.
	 * @throws InvalidArgumentException When required evidence is missing.
	 */
	public function __construct( string $plugin_file, string $name, string $version, string $text_domain, bool $active ) {
		$plugin_file = str_replace( '\\', '/', trim( $plugin_file ) );
		$name        = trim( $name );
		$version     = trim( $version );
		$text_domain = trim( $text_domain );

		if ( '' === $plugin_file || '' === $name || '' === $version ) {
			throw new InvalidArgumentException( 'Plugin file, name and version must be non-empty.' );
		}

		$this->plugin_file = $plugin_file;
		$this->name        = $name;
		$this->version     = $version;
		$this->text_domain = $text_domain;
		$this->active      = $active;
	}

	/**
	 * Get the WordPress plugin basename.
	 *
	 * @return string
	 */
	public function plugin_file(): string {
		return $this->plugin_file;
	}

	/**
	 * Get the top-level plugin directory slug.
	 *
	 * @return string
	 */
	public function plugin_slug(): string {
		$segments = explode( '/', $this->plugin_file );

		return $segments[0];
	}

	/**
	 * Get the plugin header name.
	 *
	 * @return string
	 */
	public function name(): string {
		return $this->name;
	}

	/**
	 * Get the plugin header version.
	 *
	 * @return string
	 */
	public function version(): string {
		return $this->version;
	}

	/**
	 * Get the plugin header text domain.
	 *
	 * @return string
	 */
	public function text_domain(): string {
		return $this->text_domain;
	}

	/**
	 * Whether the plugin is active in the current WordPress context.
	 *
	 * @return bool
	 */
	public function active(): bool {
		return $this->active;
	}

	/**
	 * Return the stable evidence shape used by detector results.
	 *
	 * @return array<string, mixed>
	 */
	public function to_array(): array {
		return array(
			'plugin_file' => $this->plugin_file,
			'plugin_slug' => $this->plugin_slug(),
			'name'        => $this->name,
			'version'     => $this->version,
			'text_domain' => $this->text_domain,
			'active'      => $this->active,
		);
	}
}
