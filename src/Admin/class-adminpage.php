<?php
/**
 * AI systems registry administration screen.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

/**
 * Registers and renders the plugin-owned WordPress admin screen.
 */
final class AdminPage {
	/**
	 * Site-local registry repository.
	 *
	 * @var WordPressOptionsRegistryRepository
	 */
	private $repository;

	/**
	 * Create the admin surface.
	 */
	public function __construct() {
		$this->repository = new WordPressOptionsRegistryRepository();
	}

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_kairoseth_ai_transparency_save_system', array( $this, 'handle_save' ) );
		add_action( 'admin_post_kairoseth_ai_transparency_archive_system', array( $this, 'handle_archive' ) );
	}

	/**
	 * Register the plugin Tools submenu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_management_page(
			__( 'Kairoseth AI Transparency', 'ai-transparency' ),
			__( 'AI Transparency', 'ai-transparency' ),
			'manage_options',
			'ai-transparency',
			array( $this, 'render' )
		);
	}

	/**
	 * Enqueue plugin-owned admin styles only on this screen.
	 *
	 * @param string $hook_suffix Current WordPress admin page hook.
	 * @return void
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'tools_page_ai-transparency' !== $hook_suffix ) {
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
	 * Render the registry and editor.
	 *
	 * @return void
	 */
	public function render(): void {
		$this->require_permission();

		$registry = $this->repository->load();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only edit selection.
		$edit_id = isset( $_GET['system'] ) ? sanitize_text_field( wp_unslash( $_GET['system'] ) ) : '';
		$editing = '' !== $edit_id ? $registry->find( $edit_id ) : null;
		?>
		<div class="wrap ai-transparency-admin">
			<h1><?php echo esc_html__( 'Kairoseth AI Transparency', 'ai-transparency' ); ?></h1>
			<p><?php echo esc_html__( 'Maintain a local inventory of AI systems used by this WordPress site.', 'ai-transparency' ); ?></p>
			<p><em><?php echo esc_html__( 'Registry data stays in this WordPress site and is not sent to any external service automatically.', 'ai-transparency' ); ?></em></p>

			<?php $this->render_notice(); ?>

			<h2><?php echo esc_html__( 'AI Systems Registry', 'ai-transparency' ); ?></h2>
			<?php if ( 0 === $registry->count() ) : ?>
				<p><?php echo esc_html__( 'No AI systems have been registered yet.', 'ai-transparency' ); ?></p>
			<?php else : ?>
				<div
					class="ai-transparency-table-wrap"
					role="region"
					aria-label="<?php echo esc_attr__( 'AI Systems Registry', 'ai-transparency' ); ?>"
					tabindex="0"
				>
					<table class="widefat striped">
						<caption class="screen-reader-text"><?php echo esc_html__( 'AI Systems Registry', 'ai-transparency' ); ?></caption>
						<thead>
							<tr>
								<th scope="col"><?php echo esc_html__( 'Name', 'ai-transparency' ); ?></th>
								<th scope="col"><?php echo esc_html__( 'Type', 'ai-transparency' ); ?></th>
								<th scope="col"><?php echo esc_html__( 'Review', 'ai-transparency' ); ?></th>
								<th scope="col"><?php echo esc_html__( 'Status', 'ai-transparency' ); ?></th>
								<th scope="col"><?php echo esc_html__( 'Updated', 'ai-transparency' ); ?></th>
								<th scope="col"><?php echo esc_html__( 'Actions', 'ai-transparency' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $registry->all() as $system ) : ?>
								<?php
								$edit_url = add_query_arg(
									array(
										'page'   => 'ai-transparency',
										'system' => $system->id(),
									),
									admin_url( 'tools.php' )
								);
								?>
								<tr>
									<td><strong><?php echo esc_html( $system->name() ); ?></strong></td>
									<td><?php echo esc_html( $this->type_label( $system->type() ) ); ?></td>
									<td><?php echo esc_html( $this->review_label( $system->review_status() ) ); ?></td>
									<td><?php echo esc_html( $this->status_label( $system->status() ) ); ?></td>
									<td><?php echo esc_html( '' !== $system->updated_at() ? $system->updated_at() : '—' ); ?></td>
									<td>
										<a href="<?php echo esc_url( $edit_url ); ?>">
											<?php echo esc_html__( 'Edit', 'ai-transparency' ); ?>
										</a>
										<?php if ( AiSystem::STATUS_ACTIVE === $system->status() ) : ?>
											<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline">
												<input type="hidden" name="action" value="kairoseth_ai_transparency_archive_system">
												<input type="hidden" name="system_id" value="<?php echo esc_attr( $system->id() ); ?>">
												<?php wp_nonce_field( 'kairoseth_ai_transparency_archive_' . $system->id(), '_kat_nonce' ); ?>
												<button type="submit" class="button-link-delete"><?php echo esc_html__( 'Archive', 'ai-transparency' ); ?></button>
											</form>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>

			<hr>
			<h2><?php echo esc_html( $editing ? __( 'Edit AI system', 'ai-transparency' ) : __( 'Add AI system', 'ai-transparency' ) ); ?></h2>
			<?php $this->render_form( $editing ); ?>

			<p><strong><?php echo esc_html__( 'Readiness boundary:', 'ai-transparency' ); ?></strong> <?php echo esc_html__( 'This registry records technical information and administrator declarations. It does not certify or guarantee legal compliance.', 'ai-transparency' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Save one registry record.
	 *
	 * @return void
	 */
	public function handle_save(): void {
		$this->require_permission();
		check_admin_referer( 'kairoseth_ai_transparency_save_system', '_kat_nonce' );

		$registry = $this->repository->load();
		$id       = isset( $_POST['system_id'] ) ? sanitize_text_field( wp_unslash( $_POST['system_id'] ) ) : '';
		$name     = isset( $_POST['system_name'] ) ? sanitize_text_field( wp_unslash( $_POST['system_name'] ) ) : '';
		$type     = isset( $_POST['system_type'] ) ? sanitize_key( wp_unslash( $_POST['system_type'] ) ) : '';
		$context  = isset( $_POST['interaction_context'] ) ? sanitize_textarea_field( wp_unslash( $_POST['interaction_context'] ) ) : '';
		$review   = isset( $_POST['review_status'] ) ? sanitize_key( wp_unslash( $_POST['review_status'] ) ) : AiSystem::REVIEW_PENDING;
		$required = isset( $_POST['interaction_disclosure_required'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['interaction_disclosure_required'] ) );

		if ( '' === $name || ! in_array( $type, AiSystem::supported_types(), true ) || ! in_array( $review, AiSystem::supported_review_statuses(), true ) ) {
			$this->redirect_with_notice( 'invalid' );
		}

		$existing = '' !== $id ? $registry->find( $id ) : null;
		if ( '' !== $id && null === $existing ) {
			$this->redirect_with_notice( 'missing' );
		}

		$now         = gmdate( 'c' );
		$reviewed_at = AiSystem::REVIEW_REVIEWED === $review ? $now : '';

		if ( $existing ) {
			$system = $existing->with_updates(
				array(
					'name'                            => $name,
					'type'                            => $type,
					'review_status'                   => $review,
					'interaction_context'             => $context,
					'interaction_disclosure_required' => $required,
					'updated_at'                      => $now,
					'reviewed_at'                     => $reviewed_at,
				)
			);
		} else {
			$system = new AiSystem(
				wp_generate_uuid4(),
				$name,
				$type,
				'manual',
				$required,
				array(
					'source_origin'       => AiSystem::SOURCE_MANUAL,
					'status'              => AiSystem::STATUS_ACTIVE,
					'review_status'       => $review,
					'interaction_context' => $context,
					'created_at'          => $now,
					'updated_at'          => $now,
					'reviewed_at'         => $reviewed_at,
				)
			);
		}

		$registry->put( $system );
		if ( ! $this->repository->save( $registry ) ) {
			$this->redirect_with_notice( 'save_failed' );
		}

		$this->redirect_with_notice( 'saved' );
	}

	/**
	 * Archive one registry record without deleting its evidence history.
	 *
	 * @return void
	 */
	public function handle_archive(): void {
		$this->require_permission();

		$id = isset( $_POST['system_id'] ) ? sanitize_text_field( wp_unslash( $_POST['system_id'] ) ) : '';
		check_admin_referer( 'kairoseth_ai_transparency_archive_' . $id, '_kat_nonce' );

		$registry = $this->repository->load();
		$system   = $registry->find( $id );

		if ( null === $system ) {
			$this->redirect_with_notice( 'missing' );
		}

		$registry->put(
			$system->with_updates(
				array(
					'status'     => AiSystem::STATUS_ARCHIVED,
					'updated_at' => gmdate( 'c' ),
				)
			)
		);

		if ( ! $this->repository->save( $registry ) ) {
			$this->redirect_with_notice( 'save_failed' );
		}

		$this->redirect_with_notice( 'archived' );
	}

	/**
	 * Render the add/edit form.
	 *
	 * @param AiSystem|null $system Existing system when editing.
	 * @return void
	 */
	private function render_form( $system ): void {
		$name     = $system ? $system->name() : '';
		$type     = $system ? $system->type() : AiSystem::TYPE_CHATBOT;
		$context  = $system ? $system->interaction_context() : '';
		$review   = $system ? $system->review_status() : AiSystem::REVIEW_PENDING;
		$required = $system ? $system->interaction_disclosure_required() : false;
		?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="kairoseth_ai_transparency_save_system">
			<input type="hidden" name="system_id" value="<?php echo esc_attr( $system ? $system->id() : '' ); ?>">
			<?php wp_nonce_field( 'kairoseth_ai_transparency_save_system', '_kat_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="kat-system-name"><?php echo esc_html__( 'System name', 'ai-transparency' ); ?></label></th>
					<td><input name="system_name" id="kat-system-name" type="text" class="regular-text" required value="<?php echo esc_attr( $name ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="kat-system-type"><?php echo esc_html__( 'System type', 'ai-transparency' ); ?></label></th>
					<td>
						<select name="system_type" id="kat-system-type">
							<?php foreach ( AiSystem::supported_types() as $type_value ) : ?>
								<option value="<?php echo esc_attr( $type_value ); ?>" <?php selected( $type, $type_value ); ?>><?php echo esc_html( $this->type_label( $type_value ) ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="kat-interaction-context"><?php echo esc_html__( 'Interaction context', 'ai-transparency' ); ?></label></th>
					<td>
						<textarea name="interaction_context" id="kat-interaction-context" class="large-text" rows="3" aria-describedby="kat-interaction-context-description"><?php echo esc_textarea( $context ); ?></textarea>
						<p class="description" id="kat-interaction-context-description"><?php echo esc_html__( 'Describe where or how visitors, staff or customers interact with this AI system.', 'ai-transparency' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="kat-review-status"><?php echo esc_html__( 'Review status', 'ai-transparency' ); ?></label></th>
					<td>
						<select name="review_status" id="kat-review-status">
							<option value="pending" <?php selected( $review, AiSystem::REVIEW_PENDING ); ?>><?php echo esc_html__( 'Pending review', 'ai-transparency' ); ?></option>
							<option value="reviewed" <?php selected( $review, AiSystem::REVIEW_REVIEWED ); ?>><?php echo esc_html__( 'Reviewed', 'ai-transparency' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php echo esc_html__( 'Interaction disclosure', 'ai-transparency' ); ?></th>
					<td>
						<label>
							<input name="interaction_disclosure_required" type="checkbox" value="1" <?php checked( $required ); ?>>
							<?php echo esc_html__( 'This configured workflow requires an AI interaction disclosure.', 'ai-transparency' ); ?>
						</label>
					</td>
				</tr>
			</table>

			<?php submit_button( $system ? __( 'Update AI system', 'ai-transparency' ) : __( 'Add AI system', 'ai-transparency' ) ); ?>
		</form>
		<?php
	}

	/**
	 * Show state-change feedback.
	 *
	 * @return void
	 */
	private function render_notice(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only notice selection after a protected redirect.
		$notice = isset( $_GET['kat_notice'] ) ? sanitize_key( wp_unslash( $_GET['kat_notice'] ) ) : '';
		$map    = array(
			'saved'       => array( 'success', __( 'AI system saved.', 'ai-transparency' ) ),
			'archived'    => array( 'success', __( 'AI system archived.', 'ai-transparency' ) ),
			'invalid'     => array( 'error', __( 'Please provide a valid name, system type and review status.', 'ai-transparency' ) ),
			'missing'     => array( 'error', __( 'The requested AI system could not be found.', 'ai-transparency' ) ),
			'save_failed' => array( 'error', __( 'The AI systems registry could not be saved.', 'ai-transparency' ) ),
		);

		if ( ! isset( $map[ $notice ] ) ) {
			return;
		}

		$role = 'error' === $map[ $notice ][0] ? 'alert' : 'status';
		?>
		<div class="notice notice-<?php echo esc_attr( $map[ $notice ][0] ); ?> is-dismissible" role="<?php echo esc_attr( $role ); ?>"><p><?php echo esc_html( $map[ $notice ][1] ); ?></p></div>
		<?php
	}

	/**
	 * Require the privileged WordPress capability.
	 *
	 * @return void
	 */
	private function require_permission(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'ai-transparency' ) );
		}
	}

	/**
	 * Redirect back to the registry with a bounded notice code.
	 *
	 * @param string $notice Notice code.
	 * @return void
	 */
	private function redirect_with_notice( string $notice ): void {
		wp_safe_redirect(
			add_query_arg(
				array(
					'page'       => 'ai-transparency',
					'kat_notice' => $notice,
				),
				admin_url( 'tools.php' )
			)
		);
		exit;
	}

	/**
	 * Convert a system type key to its localized label.
	 *
	 * @param string $type Type key.
	 * @return string
	 */
	private function type_label( string $type ): string {
		$labels = array(
			AiSystem::TYPE_CHATBOT     => __( 'Chatbot', 'ai-transparency' ),
			AiSystem::TYPE_ASSISTANT   => __( 'Assistant', 'ai-transparency' ),
			AiSystem::TYPE_GENERATOR   => __( 'Content generator', 'ai-transparency' ),
			AiSystem::TYPE_RECOMMENDER => __( 'Recommender', 'ai-transparency' ),
			AiSystem::TYPE_CLASSIFIER  => __( 'Classifier', 'ai-transparency' ),
			AiSystem::TYPE_OTHER       => __( 'Other', 'ai-transparency' ),
		);

		return $labels[ $type ] ?? $type;
	}

	/**
	 * Convert a review key to its localized label.
	 *
	 * @param string $review Review key.
	 * @return string
	 */
	private function review_label( string $review ): string {
		return AiSystem::REVIEW_REVIEWED === $review ? __( 'Reviewed', 'ai-transparency' ) : __( 'Pending review', 'ai-transparency' );
	}

	/**
	 * Convert a lifecycle status key to its localized label.
	 *
	 * @param string $status Status key.
	 * @return string
	 */
	private function status_label( string $status ): string {
		return AiSystem::STATUS_ARCHIVED === $status ? __( 'Archived', 'ai-transparency' ) : __( 'Active', 'ai-transparency' );
	}
}
