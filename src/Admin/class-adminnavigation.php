<?php
/**
 * Unified WordPress admin navigation for the plugin.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

/**
 * Groups all plugin admin surfaces below the Kairoseth AI top-level menu while
 * preserving the existing page callbacks and request handlers.
 */
final class AdminNavigation {
	/** @var AdminPage */
	private $registry_page;

	/** @var DiscoveryPage */
	private $discovery_page;

	/** @var ReadinessPage */
	private $readiness_page;

	/** @var DisclosurePage */
	private $disclosure_page;

	/** @var EvidenceExportPage */
	private $evidence_export_page;

	/** @var SupportPage */
	private $support_page;

	/**
	 * Create the unified navigation coordinator.
	 */
	public function __construct(
		AdminPage $registry_page,
		DiscoveryPage $discovery_page,
		ReadinessPage $readiness_page,
		DisclosurePage $disclosure_page,
		EvidenceExportPage $evidence_export_page,
		SupportPage $support_page
	) {
		$this->registry_page        = $registry_page;
		$this->discovery_page       = $discovery_page;
		$this->readiness_page       = $readiness_page;
		$this->disclosure_page      = $disclosure_page;
		$this->evidence_export_page = $evidence_export_page;
		$this->support_page         = $support_page;
	}

	/**
	 * Register navigation hooks.
	 *
	 * Existing page classes still register their handlers and legacy Tools page
	 * callbacks. Those menu entries are hidden after we register the new unified
	 * submenu, which keeps backwards-compatible URLs working during v1.1.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_submenus' ), 90 );
		add_action( 'admin_menu', array( $this, 'remove_legacy_tools_menu_entries' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_shared_assets' ), 20 );
	}

	/**
	 * Register the existing plugin screens below the Kairoseth AI menu.
	 *
	 * @return void
	 */
	public function register_submenus(): void {
		$parent = 'kairoseth-ai-transparency';

		add_submenu_page(
			$parent,
			__( 'AI Systems Registry', 'kairoseth-ai-transparency' ),
			__( 'AI Systems Registry', 'kairoseth-ai-transparency' ),
			'manage_options',
			'ai-transparency',
			array( $this->registry_page, 'render' )
		);

		add_submenu_page(
			$parent,
			__( 'AI Discovery', 'kairoseth-ai-transparency' ),
			__( 'AI Discovery', 'kairoseth-ai-transparency' ),
			'manage_options',
			'ai-transparency-discovery',
			array( $this->discovery_page, 'render' )
		);

		add_submenu_page(
			$parent,
			__( 'AI Readiness', 'kairoseth-ai-transparency' ),
			__( 'AI Readiness', 'kairoseth-ai-transparency' ),
			'manage_options',
			'ai-transparency-readiness',
			array( $this->readiness_page, 'render' )
		);

		add_submenu_page(
			$parent,
			__( 'AI Disclosure', 'kairoseth-ai-transparency' ),
			__( 'AI Disclosure', 'kairoseth-ai-transparency' ),
			'manage_options',
			'ai-transparency-disclosure',
			array( $this->disclosure_page, 'render' )
		);

		add_submenu_page(
			$parent,
			__( 'AI Evidence Export', 'kairoseth-ai-transparency' ),
			__( 'AI Evidence Export', 'kairoseth-ai-transparency' ),
			'manage_options',
			'ai-transparency-evidence-export',
			array( $this->evidence_export_page, 'render' )
		);

		add_submenu_page(
			$parent,
			__( 'AI Transparency Support', 'kairoseth-ai-transparency' ),
			__( 'AI Transparency Support', 'kairoseth-ai-transparency' ),
			'manage_options',
			'ai-transparency-support',
			array( $this->support_page, 'render' )
		);
	}

	/**
	 * Hide the duplicate Tools menu links created by the v1.0 page classes.
	 *
	 * The legacy routes stay registered so bookmarks and old dashboard links do
	 * not break during the 1.0.x -> 1.1 transition.
	 *
	 * @return void
	 */
	public function remove_legacy_tools_menu_entries(): void {
		foreach ( self::plugin_page_slugs() as $slug ) {
			remove_submenu_page( 'tools.php', $slug );
		}
	}

	/**
	 * Ensure shared plugin styling loads on the dashboard and submenu screens.
	 *
	 * @param string $hook_suffix Current admin hook suffix.
	 * @return void
	 */
	public function enqueue_shared_assets( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, 'ai-transparency' ) && 'toplevel_page_kairoseth-ai-transparency' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'ai-transparency-admin',
			plugins_url( 'assets/admin.css', KAIROSETH_AI_TRANSPARENCY_FILE ),
			array(),
			KAIROSETH_AI_TRANSPARENCY_VERSION
		);

		wp_enqueue_style(
			'ai-transparency-admin-v11',
			plugins_url( 'assets/admin-v11.css', KAIROSETH_AI_TRANSPARENCY_FILE ),
			array( 'ai-transparency-admin' ),
			KAIROSETH_AI_TRANSPARENCY_VERSION
		);
	}

	/**
	 * Return every non-dashboard plugin page slug.
	 *
	 * @return string[]
	 */
	private static function plugin_page_slugs(): array {
		return array(
			'ai-transparency',
			'ai-transparency-discovery',
			'ai-transparency-readiness',
			'ai-transparency-disclosure',
			'ai-transparency-evidence-export',
			'ai-transparency-support',
		);
	}
}
