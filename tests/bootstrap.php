<?php
/**
 * PHPUnit bootstrap for pure PHP domain tests.
 *
 * @package KairosethAITransparency
 */

if ( ! function_exists( 'wp_json_encode' ) ) {
	/**
	 * Minimal WordPress JSON helper shim for pure PHP detector tests.
	 *
	 * @param mixed $value Value to encode.
	 * @param int   $flags JSON encoding flags.
	 * @return string|false
	 */
	function wp_json_encode( $value, int $flags = 0 ) {
		return json_encode( $value, $flags );
	}
}

require_once dirname( __DIR__ ) . '/src/class-autoloader.php';

\Kairoseth\AITransparency\Autoloader::register();
