<?php
/**
 * Disclosure readiness administration screen.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Disclosure\DisclosureEngine;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

/**
 * Renders deterministic disclosure readiness from current registry state.
 */
final class DisclosurePage {
	/**
	 * Site-local registry repository.
	 *
	 * @var WordPressOptionsRegistryRepository
	 */
	private $repository;

	/**
	 * Deterministic disclosure engine.
	 *
	 * @var DisclosureEngine
	 */
	private $engine;

	/**
	 * Create the disclosure readiness surface.
	 */
	public function __construct() {
		$this->repository = new WordPressOptionsRegistryRepository();
		$this->engine     = new DisclosureEngine();
	}

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
	 * Register the disclosure Tools submenu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_management_page(
			__( 'AI Disclosure', 'ai-transparency' ),
			__( 'AI Disclosure', 'ai-transparency' ),
			'manage_options',
			'ai-transparency-disclosure',
			array( $this, 'render' )
		);
	}

	/**
	 * Load shared admin styles only on the disclosure page.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'tools_page_ai-transparency-disclosure' !== $hook_suffix ) {
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
	 * Render deterministic disclosure readiness for registry systems.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();
		$registry = $this->repository->load();
		?>
		<div class="wrap ai-transparency-admin">
			<h1><?php echo esc_html__( 'AI Disclosure', 'ai-transparency' ); ?></h1>
			<p><?php echo esc_html__( 'Review which registered AI systems are ready for explicit frontend disclosure placement.', 'ai-transparency' ); ?></p>
			<p><em><?php echo esc_html__( 'Disclosure readiness is derived from current reviewed registry state. This screen does not decide legal obligations.', 'ai-transparency' ); ?></em></p>

			<h2><?php echo esc_html__( 'Disclosure readiness', 'ai-transparency' ); ?></h2>
			<?php if ( 0 === $registry->count() ) : ?>
				<p><?php echo esc_html__( 'No AI systems have been registered yet.', 'ai-transparency' ); ?></p>
			<?php else : ?>
				<div class="ai-transparency-table-wrap" role="region" aria-label="<?php echo esc_attr__( 'Disclosure readiness', 'ai-transparency' ); ?>" tabindex="0">
					<table class="widefat striped">
						<caption class="screen-reader-text"><?php echo esc_html__( 'Disclosure readiness', 'ai-transparency' ); ?></caption>
						<thead>
							<tr>
								<th scope="col"><?php echo esc_html__( 'System', 'ai-transparency' ); ?></th>
								<th scope="col"><?php echo esc_html__( 'Readiness', 'ai-transparency' ); ?></th>
								<th scope="col"><?php echo esc_html__( 'Placement', 'ai-transparency' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $registry->all() as $system ) : ?>
								<?php
								$reasons  = $this->engine->reason_codes( $system );
								$is_ready = empty( $reasons );
								$edit_url = add_query_arg(
									array(
										'page'   => 'ai-transparency',
										'system' => $system->id(),
									),
									admin_url( 'tools.php' )
								);
								$shortcode = sprintf( '[kairoseth_ai_disclosure system="%s"]', $system->id() );
								?>
								<tr>
									<td><strong><?php echo esc_html( $system->name() ); ?></strong></td>
									<td>
										<?php if ( $is_ready ) : ?>
											<strong><?php echo esc_html__( 'Ready', 'ai-transparency' ); ?></strong>
										<?php else : ?>
											<strong><?php echo esc_html__( 'Not ready', 'ai-transparency' ); ?></strong>
											<ul>
												<?php foreach ( $reasons as $reason ) : ?>
													<li><?php echo esc_html( $this->reason_label( $reason ) ); ?></li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</td>
									<td>
										<?php if ( $is_ready ) : ?>
											<code class="ai-transparency-shortcode"><?php echo esc_html( $shortcode ); ?></code>
											<p class="description"><?php echo esc_html__( 'Place this shortcode manually in the supported WordPress post or page interaction context.', 'ai-transparency' ); ?></p>
										<?php else : ?>
											<a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html__( 'Edit registry record', 'ai-transparency' ); ?></a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Resolve a localized bounded ineligibility reason.
	 *
	 * @param string $reason Reason code from DisclosureEngine.
	 * @return string
	 */
	private function reason_label( string $reason ): string {
		switch ( $reason ) {
			case DisclosureEngine::REASON_ARCHIVED:
				return __( 'The system is archived.', 'ai-transparency' );
			case DisclosureEngine::REASON_PENDING_REVIEW:
				return __( 'Administrator review is still pending.', 'ai-transparency' );
			case DisclosureEngine::REASON_MISSING_INTERACTION_CONTEXT:
				return __( 'Interaction context is missing.', 'ai-transparency' );
			case DisclosureEngine::REASON_DISCLOSURE_NOT_CONFIGURED:
				return __( 'Interaction disclosure is not configured.', 'ai-transparency' );
			default:
				return __( 'The registry record is not ready for disclosure placement.', 'ai-transparency' );
		}
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
