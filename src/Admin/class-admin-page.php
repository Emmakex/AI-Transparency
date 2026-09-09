<?php
/**
 * Initial admin screen for Kairoseth AI Transparency.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

/**
 * Registers and renders the plugin-owned WordPress admin screen.
 */
final class AdminPage {
	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	/**
	 * Register a Tools submenu while the product is in bootstrap.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_management_page(
			__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ),
			__( 'AI Transparency', 'kairoseth-ai-transparency' ),
			'manage_options',
			'kairoseth-ai-transparency',
			array( $this, 'render' )
		);
	}

	/**
	 * Render the initial, truthful bootstrap screen.
	 *
	 * @return void
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'kairoseth-ai-transparency' ) );
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ); ?></h1>
			<p><strong><?php echo esc_html__( 'EU AI Act Readiness for WordPress', 'kairoseth-ai-transparency' ); ?></strong></p>
			<div class="notice notice-info inline">
				<p>
					<?php
					echo esc_html__(
						'This development build establishes the plugin foundation. AI discovery, registry, disclosure and evidence workflows will be enabled only after their acceptance gates are implemented.',
						'kairoseth-ai-transparency'
					);
					?>
				</p>
			</div>
			<p>
				<?php
				echo esc_html__(
					'This plugin provides technical readiness and evidence tooling. It does not certify or guarantee legal compliance.',
					'kairoseth-ai-transparency'
				);
				?>
			</p>
		</div>
		<?php
	}
}
