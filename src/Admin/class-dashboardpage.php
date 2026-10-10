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
	/** @var WordPressOptionsRegistryRepository */
	private $repository;

	/** @var FindingEngine */
	private $finding_engine;

	/** @var DisclosureEngine */
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
			__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ),
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

		usort(
			$systems,
			static function ( AiSystem $left, AiSystem $right ): int {
				return strcmp( $right->updated_at(), $left->updated_at() );
			}
		);
		$recent_systems = array_slice( $systems, 0, 5 );

		$actions = array(
			array(
				'title'       => __( 'AI Systems Registry', 'kairoseth-ai-transparency' ),
				'description' => __( 'Maintain a local inventory of AI systems used by this WordPress site.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Open AI Systems Registry', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency' ),
				'icon'        => 'dashicons-list-view',
			),
			array(
				'title'       => __( 'AI Discovery', 'kairoseth-ai-transparency' ),
				'description' => __( 'Discovery reports only explainable technical evidence from supported WordPress integrations.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'AI Discovery', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-discovery' ),
				'icon'        => 'dashicons-search',
			),
			array(
				'title'       => __( 'AI Readiness', 'kairoseth-ai-transparency' ),
				'description' => __( 'Technical readiness findings are generated from the current local AI Systems Registry.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'AI Readiness', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-readiness' ),
				'icon'        => 'dashicons-shield-alt',
			),
			array(
				'title'       => __( 'AI Disclosure', 'kairoseth-ai-transparency' ),
				'description' => __( 'Review which registered AI systems are ready for explicit frontend disclosure placement.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'AI Disclosure', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-disclosure' ),
				'icon'        => 'dashicons-media-document',
			),
			array(
				'title'       => __( 'AI Evidence Export', 'kairoseth-ai-transparency' ),
				'description' => __( 'Create a local JSON snapshot of the current site AI Systems Registry, technical findings and disclosure readiness.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'AI Evidence Export', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-evidence-export' ),
				'icon'        => 'dashicons-download',
			),
			array(
				'title'       => __( 'AI Transparency Support', 'kairoseth-ai-transparency' ),
				'description' => __( 'Optional Kairoseth support for implementation questions, third-party integrations and custom work.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Get support', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-support' ),
				'icon'        => 'dashicons-sos',
			),
		);
		?>
		<div class="wrap ai-transparency-admin kat-dashboard">
			<section class="kat-dashboard-hero" aria-labelledby="kat-dashboard-title">
				<div>
					<p class="kat-dashboard-kicker"><?php echo esc_html__( 'Local-first:', 'kairoseth-ai-transparency' ); ?></p>
					<h1 id="kat-dashboard-title"><?php echo esc_html__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ); ?></h1>
					<p><?php echo esc_html__( 'Maintain a local inventory of AI systems used by this WordPress site.', 'kairoseth-ai-transparency' ); ?></p>
				</div>
			</section>

			<section class="kat-dashboard-metrics" aria-label="<?php echo esc_attr__( 'AI Transparency', 'kairoseth-ai-transparency' ); ?>">
				<?php $this->render_metric( __( 'Active', 'kairoseth-ai-transparency' ), $active_count, 'dashicons-database' ); ?>
				<?php $this->render_metric( __( 'Pending review', 'kairoseth-ai-transparency' ), $pending_count, 'dashicons-visibility' ); ?>
				<?php $this->render_metric( __( 'Review', 'kairoseth-ai-transparency' ), $review_findings, 'dashicons-warning' ); ?>
				<?php $this->render_metric( __( 'Ready', 'kairoseth-ai-transparency' ), $disclosure_ready, 'dashicons-yes-alt' ); ?>
			</section>

			<section class="kat-dashboard-status" aria-labelledby="kat-dashboard-status-title">
				<div>
					<h2 id="kat-dashboard-status-title"><?php echo esc_html__( 'Guidance', 'kairoseth-ai-transparency' ); ?></h2>
					<p><?php echo esc_html__( 'Findings keep observed facts, administrator declarations and guidance separate. They do not determine or certify legal compliance.', 'kairoseth-ai-transparency' ); ?></p>
				</div>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency-readiness' ) ); ?>">
					<?php echo esc_html__( 'AI Readiness', 'kairoseth-ai-transparency' ); ?>
				</a>
			</section>

			<section aria-labelledby="kat-dashboard-tools-title">
				<h2 id="kat-dashboard-tools-title" class="kat-dashboard-section-title"><?php echo esc_html__( 'AI Transparency', 'kairoseth-ai-transparency' ); ?></h2>
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

			<section class="kat-dashboard-registry" aria-labelledby="kat-dashboard-registry-title">
				<div class="kat-dashboard-registry-header">
					<h2 id="kat-dashboard-registry-title"><?php echo esc_html__( 'AI Systems Registry', 'kairoseth-ai-transparency' ); ?></h2>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency' ) ); ?>">
						<?php echo esc_html__( 'Open AI Systems Registry', 'kairoseth-ai-transparency' ); ?>
					</a>
				</div>

				<?php if ( empty( $recent_systems ) ) : ?>
					<p><?php echo esc_html__( 'No AI systems have been registered yet.', 'kairoseth-ai-transparency' ); ?></p>
				<?php else : ?>
					<div class="ai-transparency-table-wrap" role="region" aria-label="<?php echo esc_attr__( 'AI Systems Registry', 'kairoseth-ai-transparency' ); ?>" tabindex="0">
						<table class="widefat striped">
							<thead>
								<tr>
									<th scope="col"><?php echo esc_html__( 'Name', 'kairoseth-ai-transparency' ); ?></th>
									<th scope="col"><?php echo esc_html__( 'Review', 'kairoseth-ai-transparency' ); ?></th>
									<th scope="col"><?php echo esc_html__( 'Status', 'kairoseth-ai-transparency' ); ?></th>
									<th scope="col"><?php echo esc_html__( 'Updated', 'kairoseth-ai-transparency' ); ?></th>
									<th scope="col"><?php echo esc_html__( 'Actions', 'kairoseth-ai-transparency' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $recent_systems as $system ) : ?>
									<tr>
										<td><strong><?php echo esc_html( $system->name() ); ?></strong></td>
										<td><?php echo esc_html( $this->review_label( $system ) ); ?></td>
										<td><?php echo esc_html( $this->status_label( $system ) ); ?></td>
										<td><?php echo esc_html( '' !== $system->updated_at() ? $system->updated_at() : '—' ); ?></td>
										<td>
											<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'ai-transparency', 'system' => $system->id() ), admin_url( 'admin.php' ) ) ); ?>">
												<?php echo esc_html__( 'Edit', 'kairoseth-ai-transparency' ); ?>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</section>

			<p class="kat-dashboard-boundary">
				<strong><?php echo esc_html__( 'Readiness boundary:', 'kairoseth-ai-transparency' ); ?></strong>
				<?php echo esc_html__( 'This registry records technical information and administrator declarations. It does not certify or guarantee legal compliance.', 'kairoseth-ai-transparency' ); ?>
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
	 * Return the localized review label for a registry system.
	 *
	 * @param AiSystem $system Registry system.
	 * @return string
	 */
	private function review_label( AiSystem $system ): string {
		return AiSystem::REVIEW_REVIEWED === $system->review_status()
			? __( 'Reviewed', 'kairoseth-ai-transparency' )
			: __( 'Pending review', 'kairoseth-ai-transparency' );
	}

	/**
	 * Return the localized lifecycle label for a registry system.
	 *
	 * @param AiSystem $system Registry system.
	 * @return string
	 */
	private function status_label( AiSystem $system ): string {
		return AiSystem::STATUS_ACTIVE === $system->status()
			? __( 'Active', 'kairoseth-ai-transparency' )
			: __( 'Archived', 'kairoseth-ai-transparency' );
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
