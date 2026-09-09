<?php
/**
 * Deterministic AI discovery administration screen.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Discovery\AiEngineDetector;
use Kairoseth\AITransparency\Discovery\DiscoveryResult;
use Kairoseth\AITransparency\Discovery\WordPressPluginInventory;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

/**
 * Renders explainable discovery evidence and accepts reviewed candidates.
 */
final class DiscoveryPage {
	/**
	 * Site-local registry repository.
	 *
	 * @var WordPressOptionsRegistryRepository
	 */
	private $repository;

	/**
	 * WordPress plugin inventory observer.
	 *
	 * @var WordPressPluginInventory
	 */
	private $inventory;

	/**
	 * First validated deterministic detector.
	 *
	 * @var AiEngineDetector
	 */
	private $ai_engine_detector;

	/**
	 * Create the discovery surface.
	 */
	public function __construct() {
		$this->repository         = new WordPressOptionsRegistryRepository();
		$this->inventory          = new WordPressPluginInventory();
		$this->ai_engine_detector = new AiEngineDetector();
	}

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_ai_transparency_accept_discovery', array( $this, 'handle_accept' ) );
	}

	/**
	 * Register the discovery Tools submenu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_management_page(
			__( 'AI Discovery', 'ai-transparency' ),
			__( 'AI Discovery', 'ai-transparency' ),
			'manage_options',
			'ai-transparency-discovery',
			array( $this, 'render' )
		);
	}

	/**
	 * Load the shared plugin admin stylesheet only on the discovery page.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'tools_page_ai-transparency-discovery' !== $hook_suffix ) {
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
	 * Render deterministic discovery evidence.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();

		$result    = $this->detect_ai_engine();
		$registry  = $this->repository->load();
		$candidate = $result && $result->supported() ? $result->candidate( gmdate( 'c' ) ) : null;
		$existing  = $candidate ? $registry->find( $candidate->id() ) : null;
		?>
		<div class="wrap ai-transparency-admin">
			<h1><?php echo esc_html__( 'AI Discovery', 'ai-transparency' ); ?></h1>
			<p><?php echo esc_html__( 'Discovery reports only explainable technical evidence from supported WordPress integrations.', 'ai-transparency' ); ?></p>
			<p><em><?php echo esc_html__( 'Discovery reads plugin identity, version and activation state only. It does not read AI provider credentials or infer legal compliance.', 'ai-transparency' ); ?></em></p>

			<?php $this->render_notice(); ?>

			<h2><?php echo esc_html__( 'Deterministic discovery', 'ai-transparency' ); ?></h2>

			<?php if ( null === $result ) : ?>
				<p><?php echo esc_html__( 'AI Engine was not detected as active in the WordPress plugin inventory.', 'ai-transparency' ); ?></p>
			<?php else : ?>
				<?php $this->render_result( $result, null !== $existing ); ?>
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
	 * Persist a supported candidate only after re-running discovery server-side.
	 *
	 * @return void
	 */
	public function handle_accept(): void {
		$this->require_permission();
		check_admin_referer( 'ai_transparency_accept_ai_engine', '_kat_discovery_nonce' );

		$result = $this->detect_ai_engine();
		if ( null === $result ) {
			$this->redirect_with_notice( 'not_found' );
		}

		if ( ! $result->supported() ) {
			$this->redirect_with_notice( 'unsupported' );
		}

		$candidate = $result->candidate( gmdate( 'c' ) );
		if ( null === $candidate ) {
			$this->redirect_with_notice( 'unsupported' );
		}

		$registry = $this->repository->load();
		if ( null !== $registry->find( $candidate->id() ) ) {
			$this->redirect_with_notice( 'exists' );
		}

		$registry->put( $candidate );
		if ( ! $this->repository->save( $registry ) ) {
			$this->redirect_with_notice( 'save_failed' );
		}

		$this->redirect_with_notice( 'added' );
	}

	/**
	 * Render one detector result.
	 *
	 * @param DiscoveryResult $result Detector result.
	 * @param bool            $already_registered Whether its stable candidate already exists.
	 * @return void
	 */
	private function render_result( DiscoveryResult $result, bool $already_registered ): void {
		$evidence = $result->evidence();
		$plugin   = isset( $evidence['plugin'] ) && is_array( $evidence['plugin'] ) ? $evidence['plugin'] : array();
		$status   = $result->supported() ? __( 'Supported', 'ai-transparency' ) : __( 'Outside validated boundary', 'ai-transparency' );
		?>
		<table class="widefat striped ai-transparency-discovery-evidence">
			<caption class="screen-reader-text"><?php echo esc_html__( 'Detected integration evidence', 'ai-transparency' ); ?></caption>
			<tbody>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Detected integration', 'ai-transparency' ); ?></th>
					<td><?php echo esc_html( $result->integration_name() ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Observed version', 'ai-transparency' ); ?></th>
					<td><?php echo esc_html( $result->observed_version() ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Supported detector boundary', 'ai-transparency' ); ?></th>
					<td><?php echo esc_html( AiEngineDetector::SUPPORTED_VERSION ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Discovery status', 'ai-transparency' ); ?></th>
					<td><?php echo esc_html( $status ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Plugin file', 'ai-transparency' ); ?></th>
					<td><code><?php echo esc_html( isset( $plugin['plugin_file'] ) ? (string) $plugin['plugin_file'] : '' ); ?></code></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Text domain', 'ai-transparency' ); ?></th>
					<td><code><?php echo esc_html( isset( $plugin['text_domain'] ) ? (string) $plugin['text_domain'] : '' ); ?></code></td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Evidence signature', 'ai-transparency' ); ?></th>
					<td><code><?php echo esc_html( $result->source_signature() ); ?></code></td>
				</tr>
			</tbody>
		</table>

		<?php if ( ! $result->supported() ) : ?>
			<p><?php echo esc_html__( 'This AI Engine version is detected but is outside the currently validated detector boundary.', 'ai-transparency' ); ?></p>
			<p><?php echo esc_html__( 'Review the installed version manually or wait for a validated detector update before adding it through discovery.', 'ai-transparency' ); ?></p>
		<?php elseif ( $already_registered ) : ?>
			<p><strong><?php echo esc_html__( 'Already in registry', 'ai-transparency' ); ?></strong></p>
			<p><?php echo esc_html__( 'This detected integration has already been added to the local registry.', 'ai-transparency' ); ?></p>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="ai_transparency_accept_discovery">
				<?php wp_nonce_field( 'ai_transparency_accept_ai_engine', '_kat_discovery_nonce' ); ?>
				<?php submit_button( __( 'Add to registry', 'ai-transparency' ), 'primary', 'submit', false ); ?>
			</form>
			<p><?php echo esc_html__( 'Adding the detected integration creates a local record with pending review status. You must still review its actual use and interaction context.', 'ai-transparency' ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Re-run the AI Engine detector from the current WordPress inventory.
	 *
	 * @return DiscoveryResult|null
	 */
	private function detect_ai_engine() {
		foreach ( $this->inventory->observations() as $observation ) {
			$result = $this->ai_engine_detector->detect( $observation );
			if ( null !== $result ) {
				return $result;
			}
		}

		return null;
	}

	/**
	 * Render a bounded discovery notice from a server-generated status code.
	 *
	 * @return void
	 */
	private function render_notice(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only redirect notice.
		$notice   = isset( $_GET['kat_discovery_notice'] ) ? sanitize_key( wp_unslash( $_GET['kat_discovery_notice'] ) ) : '';
		$messages = array(
			'added'       => __( 'Detected AI integration added to the registry for administrator review.', 'ai-transparency' ),
			'exists'      => __( 'The detected integration is already present in the registry.', 'ai-transparency' ),
			'unsupported' => __( 'The AI Engine observation is outside the currently validated detector boundary.', 'ai-transparency' ),
			'not_found'   => __( 'AI Engine could not be detected as an active plugin.', 'ai-transparency' ),
			'save_failed' => __( 'The AI systems registry could not be saved.', 'ai-transparency' ),
		);

		if ( ! isset( $messages[ $notice ] ) ) {
			return;
		}

		$class = 'save_failed' === $notice ? 'notice notice-error' : 'notice notice-success';
		?>
		<div class="<?php echo esc_attr( $class ); ?>" role="<?php echo esc_attr( 'save_failed' === $notice ? 'alert' : 'status' ); ?>">
			<p><?php echo esc_html( $messages[ $notice ] ); ?></p>
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
			wp_die( esc_html__( 'You do not have permission to access this page.', 'ai-transparency' ), 403 );
		}
	}

	/**
	 * Redirect to the discovery page with a bounded notice code.
	 *
	 * @param string $notice Notice code.
	 * @return void
	 */
	private function redirect_with_notice( string $notice ): void {
		$url = add_query_arg(
			array(
				'page'                 => 'ai-transparency-discovery',
				'kat_discovery_notice' => sanitize_key( $notice ),
			),
			admin_url( 'tools.php' )
		);

		wp_safe_redirect( $url );
		exit;
	}
}
