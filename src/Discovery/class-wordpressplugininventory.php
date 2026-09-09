<?php
/**
 * WordPress plugin inventory observer.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Discovery;

/**
 * Converts the current WordPress plugin inventory into immutable observations.
 */
final class WordPressPluginInventory {
	/**
	 * Observe all installed WordPress plugins in deterministic basename order.
	 *
	 * Malformed/incomplete third-party plugin headers are skipped instead of
	 * breaking the entire discovery screen.
	 *
	 * @return PluginObservation[]
	 */
	public function observations(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins         = get_plugins();
		$active_plugins  = array_map( 'strval', (array) get_option( 'active_plugins', array() ) );
		$network_plugins = array();

		if ( is_multisite() ) {
			$network_plugins = array_keys( (array) get_site_option( 'active_sitewide_plugins', array() ) );
		}

		ksort( $plugins, SORT_STRING );
		$observations = array();

		foreach ( $plugins as $plugin_file => $plugin_data ) {
			$plugin_file = str_replace( '\\', '/', (string) $plugin_file );
			$name        = isset( $plugin_data['Name'] ) ? trim( (string) $plugin_data['Name'] ) : '';
			$version     = isset( $plugin_data['Version'] ) ? trim( (string) $plugin_data['Version'] ) : '';

			if ( '' === $plugin_file || '' === $name || '' === $version ) {
				continue;
			}

			$is_active = in_array( $plugin_file, $active_plugins, true ) || in_array( $plugin_file, $network_plugins, true );

			$observations[] = new PluginObservation(
				$plugin_file,
				$name,
				$version,
				isset( $plugin_data['TextDomain'] ) ? (string) $plugin_data['TextDomain'] : '',
				$is_active
			);
		}

		return $observations;
	}
}
