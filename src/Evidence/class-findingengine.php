<?php
/**
 * Deterministic readiness finding engine.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Evidence;

use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Registry\AiSystemsRegistry;

/**
 * Generates technical readiness findings from current registry state.
 */
final class FindingEngine {
	public const RULE_REVIEW_PENDING    = 'registry_review_pending_v1';
	public const RULE_CONTEXT_MISSING   = 'interaction_context_missing_v1';
	public const RULE_DISCLOSURE_REVIEW = 'configured_disclosure_review_v1';

	/**
	 * Generate all current findings in deterministic id order.
	 *
	 * @param AiSystemsRegistry $registry Current registry.
	 * @param string            $generated_at UTC ISO-8601 generation timestamp.
	 * @return Finding[]
	 */
	public function generate( AiSystemsRegistry $registry, string $generated_at ): array {
		$findings = array();

		foreach ( $registry->all() as $system ) {
			if ( AiSystem::STATUS_ACTIVE !== $system->status() ) {
				continue;
			}

			if ( AiSystem::REVIEW_PENDING === $system->review_status() ) {
				$findings[] = $this->build_finding(
					self::RULE_REVIEW_PENDING,
					'registry_review',
					Finding::PRIORITY_REVIEW,
					$system,
					Finding::FACT_REVIEW_PENDING,
					Finding::DECLARATION_NONE,
					Finding::GUIDANCE_COMPLETE_REVIEW,
					array(
						'review_status' => $system->review_status(),
						'source_origin' => $system->source_origin(),
					),
					$generated_at
				);
			}

			if ( '' === $system->interaction_context() ) {
				$findings[] = $this->build_finding(
					self::RULE_CONTEXT_MISSING,
					'registry_completeness',
					Finding::PRIORITY_REVIEW,
					$system,
					Finding::FACT_CONTEXT_MISSING,
					Finding::DECLARATION_NONE,
					Finding::GUIDANCE_DOCUMENT_CONTEXT,
					array(
						'interaction_context' => '',
						'status'              => $system->status(),
					),
					$generated_at
				);
			}

			if ( $system->interaction_disclosure_required() ) {
				$findings[] = $this->build_finding(
					self::RULE_DISCLOSURE_REVIEW,
					'disclosure_review',
					Finding::PRIORITY_REVIEW,
					$system,
					Finding::FACT_SYSTEM_ACTIVE,
					Finding::DECLARATION_DISCLOSURE_REQUIRED,
					Finding::GUIDANCE_VERIFY_DISCLOSURE,
					array(
						'interaction_disclosure_required' => true,
						'status'                          => $system->status(),
					),
					$generated_at
				);
			}
		}

		usort(
			$findings,
			static function ( Finding $left, Finding $right ): int {
				return strcmp( $left->id(), $right->id() );
			}
		);

		return $findings;
	}

	/**
	 * Build one finding with a signature derived only from rule evidence.
	 *
	 * @param string               $rule_id Rule identifier.
	 * @param string               $category Finding category.
	 * @param string               $priority Finding priority.
	 * @param AiSystem             $system Subject AI system.
	 * @param string               $fact_code Fact presentation code.
	 * @param string               $declaration_code Optional declaration presentation code.
	 * @param string               $guidance_code Guidance presentation code.
	 * @param array<string, mixed> $evidence Relevant deterministic evidence.
	 * @param string               $generated_at Generation timestamp.
	 * @return Finding
	 */
	private function build_finding(
		string $rule_id,
		string $category,
		string $priority,
		AiSystem $system,
		string $fact_code,
		string $declaration_code,
		string $guidance_code,
		array $evidence,
		string $generated_at
	): Finding {
		$signature_payload = array(
			'rule_id'   => $rule_id,
			'system_id' => $system->id(),
			'evidence'  => $evidence,
		);

		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode -- Pure domain service intentionally has no WordPress runtime dependency.
		$encoded = json_encode( $signature_payload, JSON_UNESCAPED_SLASHES );
		$signature = false === $encoded ? hash( 'sha256', $rule_id . '|' . $system->id() ) : hash( 'sha256', $encoded );

		return new Finding(
			'finding-' . $rule_id . '-' . $system->id(),
			$rule_id,
			$category,
			$priority,
			$system->id(),
			$system->name(),
			$fact_code,
			$declaration_code,
			$guidance_code,
			$signature,
			$generated_at
		);
	}
}
