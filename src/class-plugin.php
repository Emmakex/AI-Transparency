<?php
/**
 * Plugin lifecycle coordinator.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency;

use Kairoseth\AITransparency\Admin\ActionCenterPage;
use Kairoseth\AITransparency\Admin\AdminNavigation;
use Kairoseth\AITransparency\Admin\AdminPage;
use Kairoseth\AITransparency\Admin\DashboardPage;
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
	 * WordPress.org language packs remain authoritative when installed. The
	 * bundled Spanish catalog is registered as a local fallback so an es_ES
	 * WordPress site does not remain in English while a directory translation
	 * is still waiting for validation or download.
	 *
	 * @return void
	 */
	public function boot(): void {
		if ( $this->booted ) {
			return;
		}

		$this->booted = true;

		add_action( 'init', array( $this, 'register_translation_path' ), 0 );

		( new DisclosureShortcode() )->register();

		if ( is_admin() ) {
			$dashboard_page       = new DashboardPage();
			$action_center_page   = new ActionCenterPage();
			$registry_page        = new AdminPage();
			$discovery_page       = new DiscoveryPage();
			$readiness_page       = new ReadinessPage();
			$disclosure_page      = new DisclosurePage();
			$evidence_export_page = new EvidenceExportPage();
			$support_page         = new SupportPage();

			$dashboard_page->register();
			$action_center_page->register();
			$registry_page->register();
			$discovery_page->register();
			$readiness_page->register();
			$disclosure_page->register();
			$evidence_export_page->register();
			$support_page->register();

			( new AdminNavigation(
				$registry_page,
				$discovery_page,
				$readiness_page,
				$disclosure_page,
				$evidence_export_page,
				$support_page
			) )->register();
		}
	}

	/**
	 * Register the bundled translation directory for WordPress just-in-time i18n.
	 *
	 * WordPress checks WP_LANG_DIR first, so an installed WordPress.org language
	 * pack takes precedence over this bundled fallback automatically.
	 *
	 * @return void
	 */
	public function register_translation_path(): void {
		load_plugin_textdomain(
			'kairoseth-ai-transparency',
			false,
			dirname( plugin_basename( KAIROSETH_AI_TRANSPARENCY_FILE ) ) . '/languages'
		);
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
