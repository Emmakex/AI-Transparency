<?php
/**
 * Minimal production autoloader for the plugin namespace.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency;

final class Autoloader {
	/**
	 * Register the namespace autoloader.
	 *
	 * @return void
	 */
	public static function register(): void {
		spl_autoload_register( array( self::class, 'autoload' ) );
	}

	/**
	 * Load a class inside the Kairoseth AI Transparency namespace.
	 *
	 * @param string $class Fully qualified class name.
	 * @return void
	 */
	private static function autoload( string $class ): void {
		$prefix = __NAMESPACE__ . '\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$path     = __DIR__ . '/' . str_replace( '\\', '/', $relative ) . '.php';

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
}
