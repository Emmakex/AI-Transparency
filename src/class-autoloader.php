<?php
/**
 * Plugin namespace autoloader.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency;

/**
 * Loads namespaced plugin classes from WordPress-style class filenames.
 */
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
	 * @param string $fully_qualified_class_name Fully qualified class name.
	 * @return void
	 */
	private static function autoload( string $fully_qualified_class_name ): void {
		$prefix = __NAMESPACE__ . '\\';

		if ( 0 !== strpos( $fully_qualified_class_name, $prefix ) ) {
			return;
		}

		$relative_class = substr( $fully_qualified_class_name, strlen( $prefix ) );
		$segments       = explode( '\\', $relative_class );
		$class_name     = array_pop( $segments );
		$file_name      = 'class-' . strtolower( $class_name ) . '.php';
		$directory      = __DIR__;

		if ( ! empty( $segments ) ) {
			$directory .= '/' . implode( '/', $segments );
		}

		$path = $directory . '/' . $file_name;

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
}
