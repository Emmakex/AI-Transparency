<?php
/**
 * Deterministic per-system dashboard action value object.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Action;

use InvalidArgumentException;

/**
 * Represents one bounded next action derived from authoritative local state.
 */
final class SystemAction {
	public const REVIEW_RECORD                = 'review_record';
	public const DOCUMENT_INTERACTION_CONTEXT = 'document_interaction_context';
	public const COMPLETE_ADMIN_CONTEXT       = 'complete_administrator_context';
	public const REVIEW_READINESS             = 'review_readiness';
	public const REVIEW_DISCLOSURE             = 'review_disclosure';
	public const PLACE_DISCLOSURE              = 'place_disclosure';

	/** @var string */
	private $system_id;

	/** @var string */
	private $system_name;

	/** @var string */
	private $code;

	/** @var int */
	private $rank;

	/** @var string */
	private $finding_id;

	/**
	 * Create one deterministic system action.
	 *
	 * @param string $system_id System identifier.
	 * @param string $system_name System display name.
	 * @param string $code Action code.
	 * @param int    $rank Stable lower-is-higher ordering rank.
	 * @param string $finding_id Optional source finding identifier.
	 * @throws InvalidArgumentException When required values are invalid.
	 */
	public function __construct( string $system_id, string $system_name, string $code, int $rank, string $finding_id = '' ) {
		$system_id   = trim( $system_id );
		$system_name = trim( $system_name );
		$code        = trim( $code );
		$finding_id  = trim( $finding_id );

		if ( '' === $system_id || '' === $system_name || ! in_array( $code, self::supported_codes(), true ) || $rank < 1 ) {
			throw new InvalidArgumentException( 'System action id, name, code and rank must be valid.' );
		}

		$this->system_id   = $system_id;
		$this->system_name = $system_name;
		$this->code        = $code;
		$this->rank        = $rank;
		$this->finding_id  = $finding_id;
	}

	public function system_id(): string {
		return $this->system_id;
	}

	public function system_name(): string {
		return $this->system_name;
	}

	public function code(): string {
		return $this->code;
	}

	public function rank(): int {
		return $this->rank;
	}

	public function finding_id(): string {
		return $this->finding_id;
	}

	/**
	 * Return supported action codes.
	 *
	 * @return string[]
	 */
	public static function supported_codes(): array {
		return array(
			self::REVIEW_RECORD,
			self::DOCUMENT_INTERACTION_CONTEXT,
			self::COMPLETE_ADMIN_CONTEXT,
			self::REVIEW_READINESS,
			self::REVIEW_DISCLOSURE,
			self::PLACE_DISCLOSURE,
		);
	}
}
