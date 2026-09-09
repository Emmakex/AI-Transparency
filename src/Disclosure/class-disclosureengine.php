<?php
/**
 * Deterministic AI disclosure eligibility engine.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Disclosure;

use Kairoseth\AITransparency\Domain\AiSystem;

/**
 * Converts reviewed registry state into a bounded disclosure model.
 */
final class DisclosureEngine {
	public const REASON_ARCHIVED                    = 'archived';
	public const REASON_PENDING_REVIEW              = 'pending_review';
	public const REASON_MISSING_INTERACTION_CONTEXT = 'missing_interaction_context';
	public const REASON_DISCLOSURE_NOT_CONFIGURED   = 'disclosure_not_configured';

	/**
	 * Return deterministic ineligibility reasons for one system.
	 *
	 * @param AiSystem $system Registry system.
	 * @return string[]
	 */
	public function reason_codes( AiSystem $system ): array {
		$reasons = array();

		if ( AiSystem::STATUS_ACTIVE !== $system->status() ) {
			$reasons[] = self::REASON_ARCHIVED;
		}

		if ( AiSystem::REVIEW_REVIEWED !== $system->review_status() ) {
			$reasons[] = self::REASON_PENDING_REVIEW;
		}

		if ( '' === trim( $system->interaction_context() ) ) {
			$reasons[] = self::REASON_MISSING_INTERACTION_CONTEXT;
		}

		if ( ! $system->interaction_disclosure_required() ) {
			$reasons[] = self::REASON_DISCLOSURE_NOT_CONFIGURED;
		}

		return $reasons;
	}

	/**
	 * Whether one system may produce public disclosure output.
	 *
	 * @param AiSystem $system Registry system.
	 * @return bool
	 */
	public function is_eligible( AiSystem $system ): bool {
		return array() === $this->reason_codes( $system );
	}

	/**
	 * Create a disclosure only from eligible registry state.
	 *
	 * @param AiSystem $system Registry system.
	 * @return Disclosure|null
	 */
	public function create_disclosure( AiSystem $system ) {
		if ( ! $this->is_eligible( $system ) ) {
			return null;
		}

		return new Disclosure(
			$system->id(),
			$system->name(),
			Disclosure::COPY_VERSION_INLINE_V1
		);
	}
}
