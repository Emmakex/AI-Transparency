<?php
/**
 * Plugin Name:       Kairoseth AI Transparency
 * Plugin URI:        https://kairoseth.com/
 * Description:       EU AI Act transparency readiness tooling for WordPress, with local AI system inventory, evidence and disclosure workflows.
 * Version:           0.1.0
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Author:            Kairoseth
 * Author URI:        https://kairoseth.com/
 * License:           MIT
 * License URI:       https://opensource.org/license/mit/
 * Text Domain:       kairoseth-ai-transparency
 * Domain Path:       /languages
 *
 * @package KairosethAITransparency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KAIROSETH_AI_TRANSPARENCY_VERSION', '0.1.0' );
define( 'KAIROSETH_AI_TRANSPARENCY_FILE', __FILE__ );
define( 'KAIROSETH_AI_TRANSPARENCY_PATH', plugin_dir_path( __FILE__ ) );

require_once KAIROSETH_AI_TRANSPARENCY_PATH . 'src/Autoloader.php';

\Kairoseth\AITransparency\Autoloader::register();

add_action(
	'plugins_loaded',
	static function () {
		\Kairoseth\AITransparency\Plugin::instance()->boot();
	}
);
