<?php
/**
 * Deterministic per-system Action Center engine.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Action;

use Kairoseth\AITransparency\Disclosure\DisclosureEngine;
use Kairoseth\AITransparency\Domain\AiSystem;
use Kairoseth\AITransparency\Evidence\Finding;
use Kairoseth\AITransparency\Evidence\FindingEngine;

/**
 * Derives one highest-priority next action for every active AI system.
 */
final class SystemActionEngine {
	/**
	 * Derive deterministic actions from current authoritative state.
	 *
	 * @param AiSystem[] $systems Current registry systems.
	 * @param Finding[]  $findings Current readiness findings.
	 * @return SystemAction[]
	 */
	public function generate( array $systems, array $findings ): array {
		$findings_by_system = $this->index_findings( $findings );
		$actions            = array();
		$disclosure_engine  = new DisclosureEngine();

		foreach ( $systems as $system ) {
			if ( ! $system instanceof AiSystem || AiSystem::STATUS_ACTIVE !== $system->status() ) {
				continue;
			}

			$system_findings = $findings_by_system[ $system->id() ] ?? array();
			$action          = $this->action_for_system( $system, $system_findings, $disclosure_engine );

			if ( null !== $action ) {
				$actions[] = $action;
			}
		}

		usort(
			$actions,
			static function ( SystemAction $left, SystemAction $right ): int {
				if ( $left->rank() !== $right->rank() ) {
					return $left->rank() <=> $right->rank();
				}

				$name_compare = strcasecmp( $left->system_name(), $right->system_name() );
				return 0 !== $name_compare ? $name_compare : strcmp( $left->system_id(), $right->system_id() );
			}
		);

		return $actions;
	}

	/**
	 * Resolve one highest-priority action for an active system.
	 *
	 * @param AiSystem         $system Current system.
	 * @param Finding[]        $findings Findings for this system.
	 * @param DisclosureEngine $disclosure_engine Disclosure engine.
	 * @return SystemAction|null
	 */
	private function action_for_system( AiSystem $system, array $findings, DisclosureEngine $disclosure_engine ) {
		$review_finding = $this->finding_for_rule( $findings, FindingEngine::RULE_REVIEW_PENDING );
		if ( null !== $review_finding ) {
			return new SystemAction( $system->id(), $system->name(), SystemAction::REVIEW_RECORD, 10, $review_finding->id() );
		}

		$context_finding = $this->finding_for_rule( $findings, FindingEngine::RULE_CONTEXT_MISSING );
		if ( null !== $context_finding ) {
			return new SystemAction( $system->id(), $system->name(), SystemAction::DOCUMENT_INTERACTION_CONTEXT, 20, $context_finding->id() );
		}

		$admin_context_finding = $this->finding_for_rule( $findings, FindingEngine::RULE_ADMIN_CONTEXT_INCOMPLETE );
		if ( null !== $admin_context_finding ) {
			return new SystemAction( $system->id(), $system->name(), SystemAction::COMPLETE_ADMIN_CONTEXT, 30, $admin_context_finding->id() );
		}

		foreach ( $findings as $finding ) {
			if ( ! $finding instanceof Finding || Finding::PRIORITY_REVIEW !== $finding->priority() ) {
				continue;
			}

			if ( FindingEngine::RULE_DISCLOSURE_REVIEW !== $finding->rule_id() ) {
				return new SystemAction( $system->id(), $system->name(), SystemAction::REVIEW_READINESS, 40, $finding->id() );
			}
		}

		$reasons = $disclosure_engine->reason_codes( $system );
		if ( in_array( DisclosureEngine::REASON_DISCLOSURE_NOT_CONFIGURED, $reasons, true ) ) {
			return new SystemAction( $system->id(), $system->name(), SystemAction::REVIEW_DISCLOSURE, 50 );
		}

		if ( empty( $reasons ) ) {
			return new SystemAction( $system->id(), $system->name(), SystemAction::PLACE_DISCLOSURE, 60 );
		}

		return new SystemAction( $system->id(), $system->name(), SystemAction::REVIEW_READINESS, 40 );
	}

	/**
	 * Index valid findings by system identifier.
	 *
	 * @param Finding[] $findings Findings.
	 * @return array<string, Finding[]>
	 */
	private function index_findings( array $findings ): array {
		$indexed = array();

		foreach ( $findings as $finding ) {
			if ( ! $finding instanceof Finding ) {
				continue;
			}

			$indexed[ $finding->subject_system_id() ][] = $finding;
		}

		return $indexed;
	}

	/**
	 * Find one current finding for a stable rule identifier.
	 *
	 * @param Finding[] $findings Findings for one system.
	 * @param string    $rule_id Stable finding rule identifier.
	 * @return Finding|null
	 */
	private function finding_for_rule( array $findings, string $rule_id ) {
		foreach ( $findings as $finding ) {
			if ( $finding instanceof Finding && $rule_id === $finding->rule_id() ) {
				return $finding;
			}
		}

		return null;
	}
}
