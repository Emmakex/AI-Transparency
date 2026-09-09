<?php
/**
 * Phase 6 Evidence Export administration screen and protected download action.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Export\EvidenceJsonEncoder;
use Kairoseth\AITransparency\Export\EvidenceSnapshotBuilder;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;
use Throwable;

/**
 * Provides the explicit administrator-generated local JSON evidence export.
 */
final class EvidenceExportPage {
	/**
	 * Site-local Registry repository.
	 *
	 * @var WordPressOptionsRegistryRepository
	 */
	private $repository;

	/**
	 * Deterministic snapshot builder.
	 *
	 * @var EvidenceSnapshotBuilder
	 */
	private $builder;

	/**
	 * JSON download encoder.
	 *
	 * @var EvidenceJsonEncoder
	 */
	private $encoder;

	/**
	 * Create the Evidence Export surface.
	 */
	public function __construct() {
		$this->repository = new WordPressOptionsRegistryRepository();
		$this->builder    = new EvidenceSnapshotBuilder();
		$this->encoder    = new EvidenceJsonEncoder();
	}

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_ai_transparency_export_evidence', array( $this, 'handle_export' ) );
	}

	/**
	 * Register the Evidence Export Tools submenu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_management_page(
			__( 'AI Evidence Export', 'ai-transparency' ),
			__( 'AI Evidence Export', 'ai-transparency' ),
			'manage_options',
			'ai-transparency-evidence-export',
			array( $this, 'render' )
		);
	}

	/**
	 * Load shared admin styles only on the Evidence Export page.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'tools_page_ai-transparency-evidence-export' !== $hook_suffix ) {
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
	 * Render the read-only export explanation and explicit POST action.
	 *
	 * A GET request never generates or downloads evidence.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();
		?>
		<div class="wrap ai-transparency-admin">
			<h1><?php echo esc_html__( 'AI Evidence Export', 'ai-transparency' ); ?></h1>
			<p><?php echo esc_html__( 'Create a local JSON snapshot of the current site AI Systems Registry, technical findings and disclosure readiness.', 'ai-transparency' ); ?></p>
			<p><em><?php echo esc_html__( 'This export is technical evidence for review. It is not legal certification, regulatory approval, a trusted timestamp or a digital signature.', 'ai-transparency' ); ?></em></p>

			<div class="notice notice-warning inline">
				<p>
					<strong><?php echo esc_html__( 'Confidentiality:', 'ai-transparency' ); ?></strong>
					<?php echo esc_html__( 'The JSON file may contain administrator-authored operational context from the Registry. Review and store the downloaded file appropriately.', 'ai-transparency' ); ?>
				</p>
			</div>

			<p><?php echo esc_html__( 'Generation is user-initiated and local to this WordPress request. The plugin does not upload, email or persist the generated evidence file.', 'ai-transparency' ); ?></p>
			<p><?php echo esc_html__( 'The export always uses the complete current site-local Registry. Browser-selected system ids or evidence values are not accepted.', 'ai-transparency' ); ?></p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="ai_transparency_export_evidence">
				<?php wp_nonce_field( 'ai_transparency_export_evidence', '_kat_evidence_export_nonce' ); ?>
				<?php submit_button( __( 'Download JSON evidence', 'ai-transparency' ), 'primary', 'submit', false ); ?>
			</form>

			<p>
				<a href="<?php echo esc_url( admin_url( 'tools.php?page=ai-transparency' ) ); ?>">
					<?php echo esc_html__( 'Open AI Systems Registry', 'ai-transparency' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Generate and stream one bounded evidence snapshot for the current site.
	 *
	 * @return void
	 */
	public function handle_export(): void {
		$this->require_permission();
		check_admin_referer( 'ai_transparency_export_evidence', '_kat_evidence_export_nonce' );

		$timestamp    = time();
		$generated_at = gmdate( 'Y-m-d\TH:i:s\Z', $timestamp );

		try {
			$registry = $this->repository->load();
			$snapshot = $this->builder->build(
				$registry,
				KAIROSETH_AI_TRANSPARENCY_VERSION,
				array(
					'home_url'     => home_url( '/' ),
					'is_multisite' => is_multisite(),
					'blog_id'      => get_current_blog_id(),
				),
				$generated_at
			);
			$json = $this->encoder->encode( $snapshot );
		} catch ( Throwable $error ) {
			wp_die(
				esc_html__( 'The evidence export could not be generated. No file was created.', 'ai-transparency' ),
				esc_html__( 'Evidence export failed', 'ai-transparency' ),
				array( 'response' => 500 )
			);
		}

		$filename = 'kairoseth-ai-transparency-evidence-' . gmdate( 'Ymd\THis\Z', $timestamp ) . '.json';

		header( 'Content-Type: application/json; charset=UTF-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'X-Content-Type-Options: nosniff' );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is a completed JSON document encoded from the strict Phase 6 allow-list.
		echo $json;
		exit;
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
