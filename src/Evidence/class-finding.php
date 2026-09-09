<?php
/**
 * Deterministic readiness finding model.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Evidence;

use InvalidArgumentException;

/**
 * Represents one reproducible technical readiness finding.
 */
final class Finding {
	public const PRIORITY_REVIEW = 'review';
	public const PRIORITY_INFO   = 'info';

	public const FACT_REVIEW_PENDING  = 'review_pending';
	public const FACT_CONTEXT_MISSING = 'interaction_context_missing';
	public const FACT_SYSTEM_ACTIVE   = 'system_active';

	public const DECLARATION_NONE                = '';
	public const DECLARATION_DISCLOSURE_REQUIRED = 'interaction_disclosure_required';

	public const GUIDANCE_COMPLETE_REVIEW   = 'complete_system_review';
	public const GUIDANCE_DOCUMENT_CONTEXT  = 'document_interaction_context';
	public const GUIDANCE_VERIFY_DISCLOSURE = 'verify_disclosure_implementation';

	/**
	 * Stable finding identifier.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Stable rule identifier.
	 *
	 * @var string
	 */
	private $rule_id;

	/**
	 * Finding category.
	 *
	 * @var string
	 */
	private $category;

	/**
	 * Technical review priority.
	 *
	 * @var string
	 */
	private $priority;

	/**
	 * Subject AI system identifier.
	 *
	 * @var string
	 */
	private $subject_system_id;

	/**
	 * Subject AI system display name.
	 *
	 * @var string
	 */
	private $subject_system_name;

	/**
	 * Semantic observed-fact presentation code.
	 *
	 * @var string
	 */
	private $fact_code;

	/**
	 * Semantic administrator-declaration presentation code.
	 *
	 * @var string
	 */
	private $declaration_code;

	/**
	 * Semantic technical-guidance presentation code.
	 *
	 * @var string
	 */
	private $guidance_code;

	/**
	 * Stable signature of rule-relevant evidence.
	 *
	 * @var string
	 */
	private $evidence_signature;

	/**
	 * Finding generation timestamp.
	 *
	 * @var string
	 */
	private $generated_at;

	/**
	 * Create one immutable finding.
	 *
	 * @param string $id Stable finding identifier.
	 * @param string $rule_id Stable rule identifier.
	 * @param string $category Finding category.
	 * @param string $priority Finding priority.
	 * @param string $subject_system_id Subject AI system identifier.
	 * @param string $subject_system_name Subject AI system name.
	 * @param string $fact_code Fact presentation code.
	 * @param string $declaration_code Optional administrator declaration code.
	 * @param string $guidance_code Guidance presentation code.
	 * @param string $evidence_signature Stable evidence signature.
	 * @param string $generated_at Generation timestamp.
	 * @throws InvalidArgumentException When required finding data is invalid.
	 */
	public function __construct(
		string $id,
		string $rule_id,
		string $category,
		string $priority,
		string $subject_system_id,
		string $subject_system_name,
		string $fact_code,
		string $declaration_code,
		string $guidance_code,
		string $evidence_signature,
		string $generated_at
	) {
		$id                  = trim( $id );
		$rule_id             = trim( $rule_id );
		$category            = trim( $category );
		$priority            = trim( $priority );
		$subject_system_id   = trim( $subject_system_id );
		$subject_system_name = trim( $subject_system_name );
		$fact_code           = trim( $fact_code );
		$declaration_code    = trim( $declaration_code );
		$guidance_code       = trim( $guidance_code );
		$evidence_signature  = trim( $evidence_signature );
		$generated_at        = trim( $generated_at );

		if ( '' === $id || '' === $rule_id || '' === $category || '' === $subject_system_id || '' === $subject_system_name || '' === $fact_code || '' === $guidance_code || '' === $evidence_signature || '' === $generated_at ) {
			throw new InvalidArgumentException( 'Finding identifiers, evidence and presentation codes must be non-empty.' );
		}

		if ( ! in_array( $priority, array( self::PRIORITY_REVIEW, self::PRIORITY_INFO ), true ) ) {
			throw new InvalidArgumentException( 'Unsupported finding priority.' );
		}

		$this->id                  = $id;
		$this->rule_id             = $rule_id;
		$this->category            = $category;
		$this->priority            = $priority;
		$this->subject_system_id   = $subject_system_id;
		$this->subject_system_name = $subject_system_name;
		$this->fact_code           = $fact_code;
		$this->declaration_code    = $declaration_code;
		$this->guidance_code       = $guidance_code;
		$this->evidence_signature  = $evidence_signature;
		$this->generated_at        = $generated_at;
	}

	/**
	 * Get the stable finding identifier.
	 *
	 * @return string
	 */
	public function id(): string {
		return $this->id;
	}

	/**
	 * Get the rule identifier.
	 *
	 * @return string
	 */
	public function rule_id(): string {
		return $this->rule_id;
	}

	/**
	 * Get the finding category.
	 *
	 * @return string
	 */
	public function category(): string {
		return $this->category;
	}

	/**
	 * Get the technical priority.
	 *
	 * @return string
	 */
	public function priority(): string {
		return $this->priority;
	}

	/**
	 * Get the subject system identifier.
	 *
	 * @return string
	 */
	public function subject_system_id(): string {
		return $this->subject_system_id;
	}

	/**
	 * Get the subject system display name.
	 *
	 * @return string
	 */
	public function subject_system_name(): string {
		return $this->subject_system_name;
	}

	/**
	 * Get the semantic fact code.
	 *
	 * @return string
	 */
	public function fact_code(): string {
		return $this->fact_code;
	}

	/**
	 * Get the administrator declaration code.
	 *
	 * @return string
	 */
	public function declaration_code(): string {
		return $this->declaration_code;
	}

	/**
	 * Get the technical guidance code.
	 *
	 * @return string
	 */
	public function guidance_code(): string {
		return $this->guidance_code;
	}

	/**
	 * Get the stable evidence signature.
	 *
	 * @return string
	 */
	public function evidence_signature(): string {
		return $this->evidence_signature;
	}

	/**
	 * Get the generation timestamp.
	 *
	 * @return string
	 */
	public function generated_at(): string {
		return $this->generated_at;
	}
}
