<?php
/**
 * WordPress admin presentation for deterministic system actions.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Admin;

use Kairoseth\AITransparency\Action\SystemAction;

/**
 * Maps bounded system action codes to localized admin copy and destinations.
 */
final class SystemActionPresenter {
	/**
	 * Present one deterministic system action for WordPress admin UI.
	 *
	 * @param SystemAction $action Deterministic system action.
	 * @return array<string,string>
	 */
	public function present( SystemAction $action ): array {
		$edit_url = add_query_arg(
			array(
				'page'   => 'ai-transparency',
				'system' => $action->system_id(),
			),
			admin_url( 'admin.php' )
		);

		switch ( $action->code() ) {
			case SystemAction::REVIEW_RECORD:
				return array(
					'title'       => __( 'Pending review', 'kairoseth-ai-transparency' ),
					'description' => __( 'The active registry record is still pending administrator review.', 'kairoseth-ai-transparency' ),
					'label'       => __( 'Edit', 'kairoseth-ai-transparency' ),
					'url'         => $edit_url,
					'icon'        => 'dashicons-visibility',
				);

			case SystemAction::DOCUMENT_INTERACTION_CONTEXT:
				return array(
					'title'       => __( 'Review', 'kairoseth-ai-transparency' ),
					'description' => __( 'No interaction context is recorded for this active AI system.', 'kairoseth-ai-transparency' ),
					'label'       => __( 'Edit registry record', 'kairoseth-ai-transparency' ),
					'url'         => $edit_url,
					'icon'        => 'dashicons-edit',
				);

			case SystemAction::COMPLETE_ADMIN_CONTEXT:
				return array(
					'title'       => __( 'Administrator context', 'kairoseth-ai-transparency' ),
					'description' => __( 'One or more administrator context fields are not documented for this active AI system.', 'kairoseth-ai-transparency' ),
					'label'       => __( 'Edit registry record', 'kairoseth-ai-transparency' ),
					'url'         => $edit_url,
					'icon'        => 'dashicons-forms',
				);

			case SystemAction::REVIEW_DISCLOSURE:
				return array(
					'title'       => __( 'AI Disclosure', 'kairoseth-ai-transparency' ),
					'description' => __( 'Interaction disclosure is not configured.', 'kairoseth-ai-transparency' ),
					'label'       => __( 'Edit registry record', 'kairoseth-ai-transparency' ),
					'url'         => $edit_url,
					'icon'        => 'dashicons-media-document',
				);

			case SystemAction::PLACE_DISCLOSURE:
				return array(
					'title'       => __( 'Ready', 'kairoseth-ai-transparency' ),
					'description' => __( 'Review which registered AI systems are ready for explicit frontend disclosure placement.', 'kairoseth-ai-transparency' ),
					'label'       => __( 'AI Disclosure', 'kairoseth-ai-transparency' ),
					'url'         => admin_url( 'admin.php?page=ai-transparency-disclosure' ),
					'icon'        => 'dashicons-yes-alt',
				);

			case SystemAction::REVIEW_READINESS:
			default:
				return array(
					'title'       => __( 'Review', 'kairoseth-ai-transparency' ),
					'description' => __( 'A technical registry condition requires review.', 'kairoseth-ai-transparency' ),
					'label'       => __( 'AI Readiness', 'kairoseth-ai-transparency' ),
					'url'         => admin_url( 'admin.php?page=ai-transparency-readiness' ),
					'icon'        => 'dashicons-warning',
				);
		}
	}
}
