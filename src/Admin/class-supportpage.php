<?php
/**
 * Phase 7 contextual support administration screen.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Support\SupportContext;
use Kairoseth\AITransparency\Support\SupportUrlBuilder;
use Throwable;

/**
 * Provides an explicit privacy-bounded bridge to Kairoseth Custom Requests.
 */
final class SupportPage {
	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register the support Tools submenu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_management_page(
			__( 'AI Transparency Support', 'ai-transparency' ),
			__( 'AI Transparency Support', 'ai-transparency' ),
			'manage_options',
			'ai-transparency-support',
			array( $this, 'render' )
		);
	}

	/**
	 * Load shared admin styles only on the support page.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'tools_page_ai-transparency-support' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'ai-transparency-admin',
			plugins_url( 'assets/admin.css', KAIROSETH_AI_TRANSPARENCY_FILE ),
			array(),
			KAIROSETH_AI_TRANSPARENCY_VERSION
		);
	}

	/**
	 * Render the read-only local support bridge.
	 *
	 * Rendering this GET page performs no Kairoseth request. External navigation
	 * occurs only when the administrator deliberately activates one of the links.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();

		$urls  = array();
		$error = false;

		try {
			$context = new SupportContext(
				KAIROSETH_AI_TRANSPARENCY_VERSION,
				get_bloginfo( 'version' ),
				determine_locale()
			);
			$builder = new SupportUrlBuilder( KAIROSETH_AI_TRANSPARENCY_CUSTOM_REQUESTS_URL );
			$urls    = array(
				'support' => $builder->build( $context, 'implementation_support' ),
				'custom'  => $builder->build( $context, 'third_party_integration' ),
			);
		} catch ( Throwable $exception ) {
			$error = true;
		}
		?>
		<div class="wrap ai-transparency-admin ai-transparency-support-page">
			<h1><?php echo esc_html__( 'AI Transparency Support', 'ai-transparency' ); ?></h1>
			<p class="description"><?php echo esc_html__( 'Optional Kairoseth support for implementation questions, third-party integrations and custom work.', 'ai-transparency' ); ?></p>

			<div class="notice notice-info inline">
				<p><strong><?php echo esc_html__( 'Local-first:', 'ai-transparency' ); ?></strong> <?php echo esc_html__( 'Registry, Discovery, Readiness, Disclosure and Evidence Export continue to work without contacting Kairoseth.', 'ai-transparency' ); ?></p>
			</div>

			<section class="ai-transparency-support-section" aria-labelledby="ai-transparency-support-privacy">
				<h2 id="ai-transparency-support-privacy"><?php echo esc_html__( 'What happens when you use this page', 'ai-transparency' ); ?></h2>
				<ul class="ai-transparency-support-list">
					<li><?php echo esc_html__( 'Nothing is sent to Kairoseth when this WordPress page loads.', 'ai-transparency' ); ?></li>
					<li><?php echo esc_html__( 'Clicking an action opens Kairoseth in a new browser tab with only bounded product and platform context.', 'ai-transparency' ); ?></li>
					<li><?php echo esc_html__( 'No Registry records, evidence, site URL, administrator identity, prompts, conversations, logs or credentials are attached automatically.', 'ai-transparency' ); ?></li>
					<li><?php echo esc_html__( 'You choose what personal or business information to enter and submit on the Kairoseth form.', 'ai-transparency' ); ?></li>
				</ul>
			</section>

			<?php if ( $error ) : ?>
				<div class="notice notice-error inline">
					<p><?php echo esc_html__( 'The verified Kairoseth support destination is currently unavailable. No data was sent and all local plugin features remain available.', 'ai-transparency' ); ?></p>
				</div>
			<?php else : ?>
				<div class="ai-transparency-support-grid">
					<section class="ai-transparency-support-card" aria-labelledby="ai-transparency-support-help-title">
						<h2 id="ai-transparency-support-help-title"><?php echo esc_html__( 'Get support', 'ai-transparency' ); ?></h2>
						<p><?php echo esc_html__( 'Use this for implementation help, setup questions or guidance applying the plugin in your WordPress environment.', 'ai-transparency' ); ?></p>
						<p>
							<a class="button button-primary" href="<?php echo esc_url( $urls['support'] ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo esc_html__( 'Open Kairoseth support', 'ai-transparency' ); ?>
							</a>
						</p>
					</section>

					<section class="ai-transparency-support-card" aria-labelledby="ai-transparency-support-custom-title">
						<h2 id="ai-transparency-support-custom-title"><?php echo esc_html__( 'Request custom integration', 'ai-transparency' ); ?></h2>
						<p><?php echo esc_html__( 'Use this when you need a third-party integration or a tailored implementation beyond the local plugin workflow.', 'ai-transparency' ); ?></p>
						<p>
							<a class="button button-secondary" href="<?php echo esc_url( $urls['custom'] ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo esc_html__( 'Open custom request', 'ai-transparency' ); ?>
							</a>
						</p>
					</section>
				</div>
			<?php endif; ?>

			<p class="ai-transparency-support-footnote"><?php echo esc_html__( 'Kairoseth availability is not required for any accepted local Free feature.', 'ai-transparency' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Enforce the server-authoritative WordPress capability boundary.
	 *
	 * @return void
	 */
	private function require_permission(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'ai-transparency' ) );
		}
	}
}
