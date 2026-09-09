<?php
/**
 * Plugin lifecycle coordinator.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency;

use Kairoseth\AITransparency\Admin\AdminPage;

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

		if ( is_admin() ) {
			( new AdminPage() )->register();
		}
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
