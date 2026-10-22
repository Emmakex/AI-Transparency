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
		$priority       = $this->priority_state( $active_count, $pending_count, $review_findings, $disclosure_ready );

		$workflow = array(
			array(
				'title'       => __( 'AI Systems Registry', 'kairoseth-ai-transparency' ),
				'description' => __( 'Maintain a local inventory of AI systems used by this WordPress site.', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency' ),
				'icon'        => 'dashicons-list-view',
			),
			array(
				'title'       => __( 'AI Discovery', 'kairoseth-ai-transparency' ),
				'description' => __( 'Discovery reports only explainable technical evidence from supported WordPress integrations.', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-discovery' ),
				'icon'        => 'dashicons-search',
			),
			array(
				'title'       => __( 'AI Readiness', 'kairoseth-ai-transparency' ),
				'description' => __( 'Technical readiness findings are generated from the current local AI Systems Registry.', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-readiness' ),
				'icon'        => 'dashicons-shield-alt',
			),
			array(
				'title'       => __( 'AI Disclosure', 'kairoseth-ai-transparency' ),
				'description' => __( 'Review which registered AI systems are ready for explicit frontend disclosure placement.', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-disclosure' ),
				'icon'        => 'dashicons-media-document',
			),
			array(
				'title'       => __( 'AI Evidence Export', 'kairoseth-ai-transparency' ),
				'description' => __( 'Create a local JSON snapshot of the current site AI Systems Registry, technical findings and disclosure readiness.', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-evidence-export' ),
				'icon'        => 'dashicons-download',
			),
			array(
				'title'       => __( 'AI Transparency Support', 'kairoseth-ai-transparency' ),
				'description' => __( 'Optional Kairoseth support for implementation questions, third-party integrations and custom work.', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-support' ),
				'icon'        => 'dashicons-sos',
			),
		);
		?>
		<div class="wrap ai-transparency-admin kat-dashboard">
			<section class="kat-dashboard-hero" aria-labelledby="kat-dashboard-title">
				<div class="kat-dashboard-hero-copy">
					<p class="kat-dashboard-kicker"><?php echo esc_html__( 'Local-first:', 'kairoseth-ai-transparency' ); ?></p>
					<h1 id="kat-dashboard-title"><?php echo esc_html__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ); ?></h1>
					<p><?php echo esc_html__( 'Maintain a local inventory of AI systems used by this WordPress site.', 'kairoseth-ai-transparency' ); ?></p>
					<div class="kat-dashboard-hero-actions">
						<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency' ) ); ?>">
							<?php echo esc_html__( 'Open AI Systems Registry', 'kairoseth-ai-transparency' ); ?>
						</a>
						<a class="button kat-dashboard-hero-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency-discovery' ) ); ?>">
							<?php echo esc_html__( 'AI Discovery', 'kairoseth-ai-transparency' ); ?>
						</a>
					</div>
				</div>
				<div class="kat-dashboard-version" aria-label="<?php echo esc_attr( 'v' . KAIROSETH_AI_TRANSPARENCY_VERSION ); ?>">
					<span class="dashicons dashicons-shield-alt" aria-hidden="true"></span>
					<strong><?php echo esc_html( 'v' . KAIROSETH_AI_TRANSPARENCY_VERSION ); ?></strong>
				</div>
			</section>

			<section class="kat-dashboard-metrics" aria-label="<?php echo esc_attr__( 'AI Transparency', 'kairoseth-ai-transparency' ); ?>">
				<?php $this->render_metric( __( 'Active', 'kairoseth-ai-transparency' ), $active_count, 'dashicons-database', 'active' ); ?>
				<?php $this->render_metric( __( 'Pending review', 'kairoseth-ai-transparency' ), $pending_count, 'dashicons-visibility', 'pending' ); ?>
				<?php $this->render_metric( __( 'Review', 'kairoseth-ai-transparency' ), $review_findings, 'dashicons-warning', 'review' ); ?>
				<?php $this->render_metric( __( 'Ready', 'kairoseth-ai-transparency' ), $disclosure_ready, 'dashicons-yes-alt', 'ready' ); ?>
			</section>

			<section class="kat-dashboard-priority kat-dashboard-priority--<?php echo esc_attr( $priority['tone'] ); ?>" aria-labelledby="kat-dashboard-priority-title">
				<div class="kat-dashboard-priority-icon" aria-hidden="true"><span class="dashicons <?php echo esc_attr( $priority['icon'] ); ?>"></span></div>
				<div class="kat-dashboard-priority-copy">
					<p class="kat-dashboard-priority-label"><?php echo esc_html__( 'Guidance', 'kairoseth-ai-transparency' ); ?></p>
					<h2 id="kat-dashboard-priority-title"><?php echo esc_html( $priority['title'] ); ?></h2>
					<p><?php echo esc_html( $priority['description'] ); ?></p>
				</div>
				<a class="button button-primary" href="<?php echo esc_url( $priority['url'] ); ?>"><?php echo esc_html( $priority['label'] ); ?></a>
			</section>

			<div class="kat-dashboard-main-grid">
				<section class="kat-dashboard-panel" aria-labelledby="kat-dashboard-guidance-title">
					<div class="kat-dashboard-panel-header">
						<div>
							<p class="kat-dashboard-panel-kicker"><?php echo esc_html__( 'Guidance', 'kairoseth-ai-transparency' ); ?></p>
							<h2 id="kat-dashboard-guidance-title"><?php echo esc_html__( 'AI Readiness', 'kairoseth-ai-transparency' ); ?></h2>
						</div>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency-readiness' ) ); ?>"><?php echo esc_html__( 'AI Readiness', 'kairoseth-ai-transparency' ); ?> <span aria-hidden="true">→</span></a>
					</div>
					<p class="kat-dashboard-panel-intro"><?php echo esc_html__( 'Findings keep observed facts, administrator declarations and guidance separate. They do not determine or certify legal compliance.', 'kairoseth-ai-transparency' ); ?></p>
					<div class="kat-dashboard-actions">
						<?php
						$this->render_action(
							__( 'Pending review', 'kairoseth-ai-transparency' ),
							$pending_count,
							__( 'The active registry record is still pending administrator review.', 'kairoseth-ai-transparency' ),
							admin_url( 'admin.php?page=ai-transparency' ),
							'dashicons-visibility'
						);
						$this->render_action(
							__( 'Review', 'kairoseth-ai-transparency' ),
							$review_findings,
							__( 'A technical registry condition requires review.', 'kairoseth-ai-transparency' ),
							admin_url( 'admin.php?page=ai-transparency-readiness' ),
							'dashicons-warning'
						);
						$this->render_action(
							__( 'Ready', 'kairoseth-ai-transparency' ),
							$disclosure_ready,
							__( 'Review which registered AI systems are ready for explicit frontend disclosure placement.', 'kairoseth-ai-transparency' ),
							admin_url( 'admin.php?page=ai-transparency-disclosure' ),
							'dashicons-yes-alt'
						);
						?>
					</div>
				</section>

				<section class="kat-dashboard-panel" aria-labelledby="kat-dashboard-workflow-title">
					<div class="kat-dashboard-panel-header">
						<div>
							<p class="kat-dashboard-panel-kicker"><?php echo esc_html__( 'AI Transparency', 'kairoseth-ai-transparency' ); ?></p>
							<h2 id="kat-dashboard-workflow-title"><?php echo esc_html__( 'Kairoseth AI Transparency', 'kairoseth-ai-transparency' ); ?></h2>
						</div>
					</div>
					<div class="kat-dashboard-workflow">
						<?php foreach ( $workflow as $step ) : ?>
							<a class="kat-dashboard-workflow-link" href="<?php echo esc_url( $step['url'] ); ?>">
								<span class="kat-dashboard-workflow-icon" aria-hidden="true"><span class="dashicons <?php echo esc_attr( $step['icon'] ); ?>"></span></span>
								<span class="kat-dashboard-workflow-copy">
									<strong><?php echo esc_html( $step['title'] ); ?></strong>
									<span><?php echo esc_html( $step['description'] ); ?></span>
								</span>
								<span class="kat-dashboard-workflow-arrow" aria-hidden="true">→</span>
							</a>
						<?php endforeach; ?>
					</div>
				</section>
			</div>

			<section class="kat-dashboard-registry" aria-labelledby="kat-dashboard-registry-title">
				<div class="kat-dashboard-registry-header">
					<div>
						<p class="kat-dashboard-panel-kicker"><?php echo esc_html__( 'Active', 'kairoseth-ai-transparency' ); ?></p>
						<h2 id="kat-dashboard-registry-title"><?php echo esc_html__( 'AI Systems Registry', 'kairoseth-ai-transparency' ); ?></h2>
					</div>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency' ) ); ?>">
						<?php echo esc_html__( 'Open AI Systems Registry', 'kairoseth-ai-transparency' ); ?>
					</a>
				</div>

				<?php if ( empty( $recent_systems ) ) : ?>
					<div class="kat-dashboard-empty">
						<span class="dashicons dashicons-database" aria-hidden="true"></span>
						<p><?php echo esc_html__( 'No AI systems have been registered yet.', 'kairoseth-ai-transparency' ); ?></p>
						<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=ai-transparency-discovery' ) ); ?>"><?php echo esc_html__( 'AI Discovery', 'kairoseth-ai-transparency' ); ?></a>
					</div>
				<?php else : ?>
					<div class="ai-transparency-table-wrap kat-dashboard-table-wrap" role="region" aria-label="<?php echo esc_attr__( 'AI Systems Registry', 'kairoseth-ai-transparency' ); ?>" tabindex="0">
						<table class="widefat">
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
									<?php
									$edit_url = add_query_arg(
										array(
											'page'   => 'ai-transparency',
											'system' => $system->id(),
										),
										admin_url( 'admin.php' )
									);
									?>
									<tr>
										<td><strong><?php echo esc_html( $system->name() ); ?></strong></td>
										<td><span class="kat-dashboard-badge kat-dashboard-badge--<?php echo esc_attr( $this->review_class( $system ) ); ?>"><?php echo esc_html( $this->review_label( $system ) ); ?></span></td>
										<td><span class="kat-dashboard-badge kat-dashboard-badge--<?php echo esc_attr( $this->status_class( $system ) ); ?>"><?php echo esc_html( $this->status_label( $system ) ); ?></span></td>
										<td><?php echo esc_html( $this->format_updated_at( $system->updated_at() ) ); ?></td>
										<td><a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html__( 'Edit', 'kairoseth-ai-transparency' ); ?></a></td>
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
	 * @param string $tone Visual tone identifier.
	 * @return void
	 */
	private function render_metric( string $label, int $value, string $icon, string $tone ): void {
		?>
		<div class="kat-dashboard-metric kat-dashboard-metric--<?php echo esc_attr( $tone ); ?>">
			<span class="kat-dashboard-metric-icon dashicons <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>
			<div>
				<strong><?php echo esc_html( (string) $value ); ?></strong>
				<span><?php echo esc_html( $label ); ?></span>
			</div>
		</div>
		<?php
	}

	/**
	 * Render one operational action row.
	 *
	 * @param string $label Action label.
	 * @param int    $value Current count.
	 * @param string $description Supporting explanation.
	 * @param string $url Destination URL.
	 * @param string $icon Dashicon class.
	 * @return void
	 */
	private function render_action( string $label, int $value, string $description, string $url, string $icon ): void {
		?>
		<a class="kat-dashboard-action" href="<?php echo esc_url( $url ); ?>">
			<span class="kat-dashboard-action-icon dashicons <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>
			<span class="kat-dashboard-action-copy">
				<span class="kat-dashboard-action-heading"><strong><?php echo esc_html( $label ); ?></strong><span class="kat-dashboard-action-count"><?php echo esc_html( (string) $value ); ?></span></span>
				<span><?php echo esc_html( $description ); ?></span>
			</span>
			<span class="kat-dashboard-action-arrow" aria-hidden="true">→</span>
		</a>
		<?php
	}

	/**
	 * Build the highest-priority dashboard state from current local evidence.
	 *
	 * @param int $active_count Number of active registry systems.
	 * @param int $pending_count Number of active systems pending review.
	 * @param int $review_findings Number of review-priority findings.
	 * @param int $disclosure_ready Number of systems ready for disclosure placement.
	 * @return array<string,string>
	 */
	private function priority_state( int $active_count, int $pending_count, int $review_findings, int $disclosure_ready ): array {
		if ( 0 === $active_count ) {
			return array(
				'title'       => __( 'AI Discovery', 'kairoseth-ai-transparency' ),
				'description' => __( 'Discovery reports only explainable technical evidence from supported WordPress integrations.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'AI Discovery', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-discovery' ),
				'icon'        => 'dashicons-search',
				'tone'        => 'neutral',
			);
		}

		if ( $pending_count > 0 ) {
			return array(
				'title'       => __( 'Pending review', 'kairoseth-ai-transparency' ),
				'description' => __( 'The active registry record is still pending administrator review.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'Open AI Systems Registry', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency' ),
				'icon'        => 'dashicons-visibility',
				'tone'        => 'attention',
			);
		}

		if ( $review_findings > 0 ) {
			return array(
				'title'       => __( 'Review', 'kairoseth-ai-transparency' ),
				'description' => __( 'A technical registry condition requires review.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'AI Readiness', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-readiness' ),
				'icon'        => 'dashicons-warning',
				'tone'        => 'attention',
			);
		}

		if ( $disclosure_ready > 0 ) {
			return array(
				'title'       => __( 'Ready', 'kairoseth-ai-transparency' ),
				'description' => __( 'Review which registered AI systems are ready for explicit frontend disclosure placement.', 'kairoseth-ai-transparency' ),
				'label'       => __( 'AI Disclosure', 'kairoseth-ai-transparency' ),
				'url'         => admin_url( 'admin.php?page=ai-transparency-disclosure' ),
				'icon'        => 'dashicons-yes-alt',
				'tone'        => 'ready',
			);
		}

		return array(
			'title'       => __( 'Guidance', 'kairoseth-ai-transparency' ),
			'description' => __( 'Findings keep observed facts, administrator declarations and guidance separate. They do not determine or certify legal compliance.', 'kairoseth-ai-transparency' ),
			'label'       => __( 'AI Readiness', 'kairoseth-ai-transparency' ),
			'url'         => admin_url( 'admin.php?page=ai-transparency-readiness' ),
			'icon'        => 'dashicons-shield-alt',
			'tone'        => 'neutral',
		);
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
	 * Return a safe visual class for the system review state.
	 *
	 * @param AiSystem $system Registry system.
	 * @return string
	 */
	private function review_class( AiSystem $system ): string {
		return AiSystem::REVIEW_REVIEWED === $system->review_status() ? 'ready' : 'pending';
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
	 * Return a safe visual class for the system lifecycle state.
	 *
	 * @param AiSystem $system Registry system.
	 * @return string
	 */
	private function status_class( AiSystem $system ): string {
		return AiSystem::STATUS_ACTIVE === $system->status() ? 'active' : 'archived';
	}

	/**
	 * Format a stored UTC timestamp using the WordPress site display settings.
	 *
	 * @param string $updated_at ISO timestamp.
	 * @return string
	 */
	private function format_updated_at( string $updated_at ): string {
		if ( '' === $updated_at ) {
			return '—';
		}

		$timestamp = strtotime( $updated_at );
		if ( false === $timestamp ) {
			return $updated_at;
		}

		return wp_date( get_option( 'date_format' ), $timestamp );
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
