<?php
/**
 * Public AI disclosure model.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Disclosure;

use InvalidArgumentException;

/**
 * Immutable bounded context for one public disclosure component.
 */
final class Disclosure {
	public const COPY_VERSION_INLINE_V1 = 'inline_v1';

	/**
	 * Internal subject system identifier.
	 *
	 * @var string
	 */
	private $subject_system_id;

	/**
	 * Administrator-reviewed public system name.
	 *
	 * @var string
	 */
	private $subject_system_name;

	/**
	 * Stable public copy contract version.
	 *
	 * @var string
	 */
	private $copy_version;

	/**
	 * Create a disclosure model.
	 *
	 * @param string $subject_system_id Internal registry id.
	 * @param string $subject_system_name Public reviewed name.
	 * @param string $copy_version Stable copy contract version.
	 * @throws InvalidArgumentException When required values are empty or unsupported.
	 */
	public function __construct(
		string $subject_system_id,
		string $subject_system_name,
		string $copy_version = self::COPY_VERSION_INLINE_V1
	) {
		$subject_system_id   = trim( $subject_system_id );
		$subject_system_name = trim( $subject_system_name );
		$copy_version        = trim( $copy_version );

		if ( '' === $subject_system_id || '' === $subject_system_name ) {
			throw new InvalidArgumentException( 'Disclosure subject id and name must be non-empty.' );
		}

		if ( self::COPY_VERSION_INLINE_V1 !== $copy_version ) {
			throw new InvalidArgumentException( 'Unsupported disclosure copy version.' );
		}

		$this->subject_system_id   = $subject_system_id;
		$this->subject_system_name = $subject_system_name;
		$this->copy_version        = $copy_version;
	}

	/**
	 * Get the internal subject system id.
	 *
	 * @return string
	 */
	public function subject_system_id(): string {
		return $this->subject_system_id;
	}

	/**
	 * Get the reviewed public system name.
	 *
	 * @return string
	 */
	public function subject_system_name(): string {
		return $this->subject_system_name;
	}

	/**
	 * Get the stable copy contract version.
	 *
	 * @return string
	 */
	public function copy_version(): string {
		return $this->copy_version;
	}
}
