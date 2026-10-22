<?php
/**
 * Per-system Action Center administration screen.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Action\SystemAction;
use Kairoseth\AITransparency\Action\SystemActionEngine;
use Kairoseth\AITransparency\Evidence\FindingEngine;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

/**
 * Renders deterministic next actions for current local AI systems.
 */
final class ActionCenterPage {
	/**
	 * Site-local registry repository.
	 *
	 * @var WordPressOptionsRegistryRepository
	 */
	private $repository;

	/**
	 * Deterministic readiness engine.
	 *
	 * @var FindingEngine
	 */
	private $finding_engine;

	/**
	 * Deterministic per-system action engine.
	 *
	 * @var SystemActionEngine
	 */
	private $action_engine;

	/**
	 * Shared WordPress admin action presenter.
	 *
	 * @var SystemActionPresenter
	 */
	private $presenter;

	/**
	 * Create the Action Center surface.
	 */
	public function __construct() {
		$this->repository     = new WordPressOptionsRegistryRepository();
		$this->finding_engine = new FindingEngine();
		$this->action_engine  = new SystemActionEngine();
		$this->presenter      = new SystemActionPresenter();
	}

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ), 85 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ), 30 );
	}

	/**
	 * Register the Action Center below the plugin top-level menu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_submenu_page(
			'kairoseth-ai-transparency',
			__( 'Actions', 'kairoseth-ai-transparency' ),
			__( 'Actions', 'kairoseth-ai-transparency' ),
			'manage_options',
			'ai-transparency-actions',
			array( $this, 'render' )
		);
	}

	/**
	 * Load the Action Center layout on its own screen.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, 'ai-transparency-actions' ) ) {
			return;
		}

		$path     = KAIROSETH_AI_TRANSPARENCY_PATH . 'assets/action-center.css';
		$modified = is_file( $path ) ? filemtime( $path ) : false;
		$version  = false !== $modified ? (string) $modified : KAIROSETH_AI_TRANSPARENCY_VERSION;

		wp_enqueue_style(
			'ai-transparency-action-center',
			plugins_url( 'assets/action-center.css', KAIROSETH_AI_TRANSPARENCY_FILE ),
			array( 'ai-transparency-admin-v11' ),
			$version
		);
	}

	/**
	 * Render deterministic next actions from current local state.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();

		$registry = $this->repository->load();
		$findings = $this->finding_engine->generate( $registry, gmdate( 'c' ) );
		$actions  = $this->action_engine->generate( $registry->all(), $findings );
		?>
		<div class="wrap ai-transparency-admin kat-action-center">
			<p class="kat-dashboard-panel-kicker"><?php echo esc_html__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ); ?></p>
			<h1><?php echo esc_html__( 'Actions', 'kairoseth-ai-transparency' ); ?></h1>
			<p><?php echo esc_html__( 'Technical readiness findings are generated from the current local AI Systems Registry.', 'kairoseth-ai-transparency' ); ?></p>
			<p><em><?php echo esc_html__( 'Findings keep observed facts, administrator declarations and guidance separate. They do not determine or certify legal compliance.', 'kairoseth-ai-transparency' ); ?></em></p>

			<?php if ( 0 === $registry->count() ) : ?>
				<div class="kat-dashboard-empty kat-action-center-empty">
					<span class="dashicons dashicons-database" aria-hidden="true"></span>
					<p><?php echo esc_html__( 'No AI systems have been registered yet.', 'kairoseth-ai-transparency' ); ?></p>
					<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency-discovery' ) ); ?>"><?php echo esc_html__( 'AI Discovery', 'kairoseth-ai-transparency' ); ?></a>
				</div>
			<?php elseif ( empty( $actions ) ) : ?>
				<div class="kat-action-center-healthy">
					<span class="dashicons dashicons-yes-alt" aria-hidden="true"></span>
					<strong><?php echo esc_html__( 'Ready', 'kairoseth-ai-transparency' ); ?></strong>
					<p><?php echo esc_html__( 'No readiness findings were generated from the current active registry records.', 'kairoseth-ai-transparency' ); ?></p>
				</div>
			<?php else : ?>
				<div class="kat-action-center-list">
					<?php foreach ( $actions as $action ) : ?>
						<?php $this->render_action( $action ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="kat-action-center-footer">
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency-evidence-export' ) ); ?>"><?php echo esc_html__( 'AI Evidence Export', 'kairoseth-ai-transparency' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency' ) ); ?>"><?php echo esc_html__( 'Open AI Systems Registry', 'kairoseth-ai-transparency' ); ?></a>
			</div>

			<p class="kat-dashboard-boundary">
				<strong><?php echo esc_html__( 'Readiness boundary:', 'kairoseth-ai-transparency' ); ?></strong>
				<?php echo esc_html__( 'This registry records technical information and administrator declarations. It does not certify or guarantee legal compliance.', 'kairoseth-ai-transparency' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Render one action card.
	 *
	 * @param SystemAction $action Deterministic action.
	 * @return void
	 */
	private function render_action( SystemAction $action ): void {
		$presentation = $this->presenter->present( $action );
		?>
		<article class="kat-action-center-item" data-action-code="<?php echo esc_attr( $action->code() ); ?>">
			<div class="kat-action-center-icon" aria-hidden="true"><span class="dashicons <?php echo esc_attr( $presentation['icon'] ); ?>"></span></div>
			<div class="kat-action-center-copy">
				<p class="kat-action-center-system"><?php echo esc_html( $action->system_name() ); ?></p>
				<h2><?php echo esc_html( $presentation['title'] ); ?></h2>
				<p><?php echo esc_html( $presentation['description'] ); ?></p>
			</div>
			<a class="button button-primary" href="<?php echo esc_url( $presentation['url'] ); ?>"><?php echo esc_html( $presentation['label'] ); ?></a>
		</article>
		<?php
	}

	/**
	 * Enforce the server-authoritative WordPress capability boundary.
	 *
	 * @return void
	 */
	private function require_permission(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'kairoseth-ai-transparency' ), 403 );
		}
	}
}
