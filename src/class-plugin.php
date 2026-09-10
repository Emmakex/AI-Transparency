<?php
/**
 * Plugin lifecycle coordinator.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency;

use Kairoseth\AITransparency\Admin\AdminPage;
use Kairoseth\AITransparency\Admin\DisclosurePage;
use Kairoseth\AITransparency\Admin\DiscoveryPage;
use Kairoseth\AITransparency\Admin\EvidenceExportPage;
use Kairoseth\AITransparency\Admin\ReadinessPage;
use Kairoseth\AITransparency\Admin\SupportPage;
use Kairoseth\AITransparency\Disclosure\DisclosureShortcode;

/**
 * Coordinates plugin bootstrapping and WordPress hooks.
 */
final class Plugin {
	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Whether boot hooks have already been registered.
	 *
	 * @var bool
	 */
	private $booted = false;

	/**
	 * Get the plugin instance.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register WordPress hooks once.
	 *
	 * @return void
	 */
	public function boot(): void {
		if ( $this->booted ) {
			return;
		}

		$this->booted = true;

		add_action( 'init', array( $this, 'load_textdomain' ) );
		( new DisclosureShortcode() )->register();

		if ( is_admin() ) {
			( new AdminPage() )->register();
			( new DiscoveryPage() )->register();
			( new ReadinessPage() )->register();
			( new DisclosurePage() )->register();
			( new EvidenceExportPage() )->register();
			( new SupportPage() )->register();
		}
	}

	/**
	 * Load bundled translations after WordPress initialization begins.
	 *
	 * @return void
	 */
	public function load_textdomain(): void {
		load_plugin_textdomain(
			'ai-transparency',
			false,
			dirname( plugin_basename( KAIROSETH_AI_TRANSPARENCY_FILE ) ) . '/languages'
		);
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
