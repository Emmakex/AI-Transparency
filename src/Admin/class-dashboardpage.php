<?php
/**
 * Plugin overview dashboard.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Disclosure\DisclosureEngine;
use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Evidence\Finding;
use Kairoseth\AITransparency\Evidence\FindingEngine;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

/**
 * Renders the plugin-owned WordPress admin overview.
 */
final class DashboardPage {
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
	 * Disclosure eligibility engine.
	 *
	 * @var DisclosureEngine
	 */
	private $disclosure_engine;

	/**
	 * Create the dashboard surface.
	 */
	public function __construct() {
		$this->repository        = new WordPressOptionsRegistryRepository();
		$this->finding_engine    = new FindingEngine();
		$this->disclosure_engine = new DisclosureEngine();
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
	 * Register a dedicated top-level plugin dashboard.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_menu_page(
			__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ),
			__( 'Kairoseth AI', 'kairoseth-ai-transparency' ),
			'manage_options',
			'kairoseth-ai-transparency',
			array( $this, 'render' ),
			'dashicons-shield-alt',
			58
		);
	}

	/**
	 * Load dashboard styling only on the plugin overview.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'toplevel_page_kairoseth-ai-transparency' !== $hook_suffix ) {
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
	 * Render the overview dashboard from current local state.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();

		$registry         = $this->repository->load();
		$systems          = $registry->all();
		$findings         = $this->finding_engine->generate( $registry, gmdate( 'c' ) );
		$active_count     = 0;
		$pending_count    = 0;
		$disclosure_ready = 0;
		$review_findings  = 0;

		foreach ( $systems as $system ) {
			if ( AiSystem::STATUS_ACTIVE !== $system->status() ) {
				continue;
			}

			++$active_count;

			if ( AiSystem::REVIEW_PENDING === $system->review_status() ) {
				++$pending_count;
			}

			if ( $this->disclosure_engine->is_eligible( $system ) ) {
				++$disclosure_ready;
			}
		}

		foreach ( $findings as $finding ) {
			if ( Finding::PRIORITY_REVIEW === $finding->priority() ) {
				++$review_findings;
			}
		}

		$actions = array(
			array(
				'title'       => __( 'AI Systems Registry', 'kairoseth-ai-transparency' ),
				'description' => __( 'Catalog, review and maintain every AI system used on this WordPress site.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Manage systems', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'tools.php?page=ai-transparency' ),
				'icon'        => 'dashicons-list-view',
			),
			array(
				'title'       => __( 'AI Discovery', 'kairoseth-ai-transparency' ),
				'description' => __( 'Review explainable technical evidence from supported AI integrations.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Run discovery', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'tools.php?page=ai-transparency-discovery' ),
				'icon'        => 'dashicons-search',
			),
			array(
				'title'       => __( 'AI Readiness', 'kairoseth-ai-transparency' ),
				'description' => __( 'Review deterministic technical findings and the actions that need attention.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'View readiness', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'tools.php?page=ai-transparency-readiness' ),
				'icon'        => 'dashicons-shield-alt',
			),
			array(
				'title'       => __( 'AI Disclosure', 'kairoseth-ai-transparency' ),
				'description' => __( 'Prepare public AI interaction disclosures from reviewed registry state.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Manage disclosures', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'tools.php?page=ai-transparency-disclosure' ),
				'icon'        => 'dashicons-media-document',
			),
			array(
				'title'       => __( 'Evidence Export', 'kairoseth-ai-transparency' ),
				'description' => __( 'Generate a deterministic JSON evidence snapshot for review or audit workflows.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Export evidence', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'tools.php?page=ai-transparency-evidence-export' ),
				'icon'        => 'dashicons-download',
			),
			array(
				'title'       => __( 'Support', 'kairoseth-ai-transparency' ),
				'description' => __( 'Open support guidance and the optional privacy-bounded Kairoseth handoff.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Open support', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'tools.php?page=ai-transparency-support' ),
				'icon'        => 'dashicons-sos',
			),
		);
		?>
		<div class="wrap ai-transparency-admin kat-dashboard">
			<section class="kat-dashboard-hero" aria-labelledby="kat-dashboard-title">
				<div>
					<p class="kat-dashboard-kicker"><?php echo esc_html__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ); ?></p>
					<h1 id="kat-dashboard-title"><?php echo esc_html__( 'AI transparency overview', 'kairoseth-ai-transparency' ); ?></h1>
					<p><?php echo esc_html__( 'Discover, document, review and disclose AI use from one technical evidence workflow.', 'kairoseth-ai-transparency' ); ?></p>
				</div>
				<div class="kat-dashboard-version">
					<span><?php echo esc_html__( 'Plugin version', 'kairoseth-ai-transparency' ); ?></span>
					<strong><?php echo esc_html( KAIROSETH_AI_TRANSPARENCY_VERSION ); ?></strong>
				</div>
			</section>

			<section class="kat-dashboard-metrics" aria-label="<?php echo esc_attr__( 'AI transparency summary', 'kairoseth-ai-transparency' ); ?>">
				<?php $this->render_metric( __( 'Active AI systems', 'kairoseth-ai-transparency' ), $active_count, 'dashicons-database' ); ?>
				<?php $this->render_metric( __( 'Pending reviews', 'kairoseth-ai-transparency' ), $pending_count, 'dashicons-visibility' ); ?>
				<?php $this->render_metric( __( 'Readiness items', 'kairoseth-ai-transparency' ), $review_findings, 'dashicons-warning' ); ?>
				<?php $this->render_metric( __( 'Disclosure-ready', 'kairoseth-ai-transparency' ), $disclosure_ready, 'dashicons-yes-alt' ); ?>
			</section>

			<section class="kat-dashboard-status" aria-labelledby="kat-dashboard-status-title">
				<div>
					<h2 id="kat-dashboard-status-title"><?php echo esc_html__( 'What needs attention', 'kairoseth-ai-transparency' ); ?></h2>
					<?php $this->render_status_summary( $active_count, $pending_count, $review_findings, $disclosure_ready ); ?>
				</div>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'tools.php?page=ai-transparency-readiness' ) ); ?>">
					<?php echo esc_html__( 'Review action items', 'kairoseth-ai-transparency' ); ?>
				</a>
			</section>

			<section aria-labelledby="kat-dashboard-tools-title">
				<h2 id="kat-dashboard-tools-title" class="kat-dashboard-section-title"><?php echo esc_html__( 'Transparency workflow', 'kairoseth-ai-transparency' ); ?></h2>
				<div class="kat-dashboard-grid">
					<?php foreach ( $actions as $action ) : ?>
						<article class="kat-dashboard-card">
							<div class="kat-dashboard-card-icon" aria-hidden="true"><span class="dashicons <?php echo esc_attr( $action['icon'] ); ?>"></span></div>
							<h3><?php echo esc_html( $action['title'] ); ?></h3>
							<p><?php echo esc_html( $action['description'] ); ?></p>
							<a class="kat-dashboard-card-link" href="<?php echo esc_url( $action['url'] ); ?>">
								<?php echo esc_html( $action['label'] ); ?> <span aria-hidden="true">→</span>
							</a>
						</article>
					<?php endforeach; ?>
				</div>
			</section>

			<p class="kat-dashboard-boundary">
				<strong><?php echo esc_html__( 'Technical boundary:', 'kairoseth-ai-transparency' ); ?></strong>
				<?php echo esc_html__( 'The dashboard summarizes technical evidence and administrator declarations. It does not determine or certify legal compliance.', 'kairoseth-ai-transparency' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Render one summary metric.
	 *
	 * @param string $label Metric label.
	 * @param int    $value Metric value.
	 * @param string $icon Dashicon class.
	 * @return void
	 */
	private function render_metric( string $label, int $value, string $icon ): void {
		?>
		<div class="kat-dashboard-metric">
			<span class="dashicons <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>
			<div>
				<strong><?php echo esc_html( (string) $value ); ?></strong>
				<span><?php echo esc_html( $label ); ?></span>
			</div>
		</div>
		<?php
	}

	/**
	 * Render a concise state-dependent summary.
	 *
	 * @param int $active Active systems.
	 * @param int $pending Pending reviews.
	 * @param int $review_findings Review-priority findings.
	 * @param int $disclosure_ready Disclosure-ready systems.
	 * @return void
	 */
	private function render_status_summary( int $active, int $pending, int $review_findings, int $disclosure_ready ): void {
		$items = array();

		if ( 0 === $active ) {
			$items[] = __( 'No active AI systems are registered yet. Add one manually or review supported discovery evidence.', 'kairoseth-ai-transparency' );
		} else {
			if ( $pending > 0 ) {
				$items[] = sprintf(
					/* translators: %d: number of AI systems pending review. */
					_n( '%d AI system still needs administrator review.', '%d AI systems still need administrator review.', $pending, 'kairoseth-ai-transparency' ),
					$pending
				);
			}

			if ( $review_findings > 0 ) {
				$items[] = sprintf(
					/* translators: %d: number of readiness findings requiring review. */
					_n( '%d readiness item needs attention.', '%d readiness items need attention.', $review_findings, 'kairoseth-ai-transparency' ),
					$review_findings
				);
			}

			if ( $disclosure_ready > 0 ) {
				$items[] = sprintf(
					/* translators: %d: number of disclosure-ready AI systems. */
					_n( '%d AI system is ready to render a disclosure.', '%d AI systems are ready to render disclosures.', $disclosure_ready, 'kairoseth-ai-transparency' ),
					$disclosure_ready
				);
			}
		}

		if ( empty( $items ) ) {
			$items[] = __( 'No review-priority technical issues are currently reported from the active registry.', 'kairoseth-ai-transparency' );
		}
		?>
		<ul class="kat-dashboard-status-list">
			<?php foreach ( $items as $item ) : ?>
				<li><?php echo esc_html( $item ); ?></li>
			<?php endforeach; ?>
		</ul>
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
