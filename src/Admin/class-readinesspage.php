<?php
/**
 * Readiness findings administration screen.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Evidence\Finding;
use Kairoseth\AITransparency\Evidence\FindingEngine;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

/**
 * Renders deterministic readiness findings from current local registry state.
 */
final class ReadinessPage {
	/**
	 * Site-local registry repository.
	 *
	 * @var WordPressOptionsRegistryRepository
	 */
	private $repository;

	/**
	 * Deterministic readiness finding engine.
	 *
	 * @var FindingEngine
	 */
	private $engine;

	/**
	 * Create the readiness surface.
	 */
	public function __construct() {
		$this->repository = new WordPressOptionsRegistryRepository();
		$this->engine     = new FindingEngine();
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
	 * Register the readiness Tools submenu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_management_page(
			__( 'AI Readiness', 'ai-transparency' ),
			__( 'AI Readiness', 'ai-transparency' ),
			'manage_options',
			'ai-transparency-readiness',
			array( $this, 'render' )
		);
	}

	/**
	 * Load shared admin styles only on the readiness page.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'tools_page_ai-transparency-readiness' !== $hook_suffix ) {
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
	 * Render current deterministic readiness findings.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();

		$registry = $this->repository->load();
		$findings = $this->engine->generate( $registry, gmdate( 'c' ) );
		?>
		<div class="wrap ai-transparency-admin">
			<h1><?php echo esc_html__( 'AI Readiness', 'ai-transparency' ); ?></h1>
			<p><?php echo esc_html__( 'Technical readiness findings are generated from the current local AI Systems Registry.', 'ai-transparency' ); ?></p>
			<p><em><?php echo esc_html__( 'Findings keep observed facts, administrator declarations and guidance separate. They do not determine or certify legal compliance.', 'ai-transparency' ); ?></em></p>

			<?php if ( empty( $findings ) ) : ?>
				<p><?php echo esc_html__( 'No readiness findings were generated from the current active registry records.', 'ai-transparency' ); ?></p>
			<?php else : ?>
				<div class="ai-transparency-findings">
					<?php foreach ( $findings as $finding ) : ?>
						<?php $this->render_finding( $finding ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<p>
				<a href="<?php echo esc_url( admin_url( 'tools.php?page=ai-transparency' ) ); ?>">
					<?php echo esc_html__( 'Open AI Systems Registry', 'ai-transparency' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Render one finding with evidence classes kept visually distinct.
	 *
	 * @param Finding $finding Readiness finding.
	 * @return void
	 */
	private function render_finding( Finding $finding ): void {
		?>
		<section class="ai-transparency-finding" aria-labelledby="<?php echo esc_attr( $finding->id() . '-title' ); ?>">
			<h2 id="<?php echo esc_attr( $finding->id() . '-title' ); ?>">
				<?php echo esc_html( $finding->subject_system_name() ); ?>
			</h2>
			<p><strong><?php echo esc_html__( 'Priority:', 'ai-transparency' ); ?></strong> <?php echo esc_html( $this->priority_label( $finding ) ); ?></p>

			<h3><?php echo esc_html__( 'Fact', 'ai-transparency' ); ?></h3>
			<p><?php echo esc_html( $this->fact_text( $finding ) ); ?></p>

			<h3><?php echo esc_html__( 'Administrator declaration', 'ai-transparency' ); ?></h3>
			<p><?php echo esc_html( $this->declaration_text( $finding ) ); ?></p>

			<h3><?php echo esc_html__( 'Guidance', 'ai-transparency' ); ?></h3>
			<p><?php echo esc_html( $this->guidance_text( $finding ) ); ?></p>

			<p class="description">
				<strong><?php echo esc_html__( 'Evidence signature:', 'ai-transparency' ); ?></strong>
				<code class="ai-transparency-signature"><?php echo esc_html( $finding->evidence_signature() ); ?></code>
			</p>
		</section>
		<?php
	}

	/**
	 * Return localized priority label.
	 *
	 * @param Finding $finding Finding.
	 * @return string
	 */
	private function priority_label( Finding $finding ): string {
		return Finding::PRIORITY_REVIEW === $finding->priority()
			? __( 'Review', 'ai-transparency' )
			: __( 'Information', 'ai-transparency' );
	}

	/**
	 * Resolve localized fact text from the semantic fact code.
	 *
	 * @param Finding $finding Finding.
	 * @return string
	 */
	private function fact_text( Finding $finding ): string {
		switch ( $finding->fact_code() ) {
			case Finding::FACT_REVIEW_PENDING:
				return __( 'The active registry record is still pending administrator review.', 'ai-transparency' );
			case Finding::FACT_CONTEXT_MISSING:
				return __( 'No interaction context is recorded for this active AI system.', 'ai-transparency' );
			case Finding::FACT_SYSTEM_ACTIVE:
				return __( 'This AI system is active in the local AI Systems Registry.', 'ai-transparency' );
			default:
				return __( 'A technical registry condition requires review.', 'ai-transparency' );
		}
	}

	/**
	 * Resolve localized administrator declaration text.
	 *
	 * @param Finding $finding Finding.
	 * @return string
	 */
	private function declaration_text( Finding $finding ): string {
		if ( Finding::DECLARATION_DISCLOSURE_REQUIRED === $finding->declaration_code() ) {
			return __( 'The administrator has configured this workflow as requiring an AI interaction disclosure.', 'ai-transparency' );
		}

		return __( 'No additional administrator declaration is attached to this finding.', 'ai-transparency' );
	}

	/**
	 * Resolve localized technical guidance.
	 *
	 * @param Finding $finding Finding.
	 * @return string
	 */
	private function guidance_text( Finding $finding ): string {
		switch ( $finding->guidance_code() ) {
			case Finding::GUIDANCE_COMPLETE_REVIEW:
				return __( 'Review the system actual use, type, interaction context and transparency configuration.', 'ai-transparency' );
			case Finding::GUIDANCE_DOCUMENT_CONTEXT:
				return __( 'Document where or how visitors, staff or customers interact with this AI system.', 'ai-transparency' );
			case Finding::GUIDANCE_VERIFY_DISCLOSURE:
				return __( 'Verify that the configured disclosure has an appropriate implementation and placement for the actual workflow.', 'ai-transparency' );
			default:
				return __( 'Review the technical evidence and update the registry when appropriate.', 'ai-transparency' );
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
