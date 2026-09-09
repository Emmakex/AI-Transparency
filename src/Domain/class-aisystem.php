<?php
/**
 * AI system domain model.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Domain;

use InvalidArgumentException;

/**
 * Represents one locally known AI system and its evidence source.
 */
final class AiSystem {
	/**
	 * Stable local identifier.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Human-readable system name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * System type.
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Evidence/source identifier.
	 *
	 * @var string
	 */
	private $source;

	/**
	 * Whether the configured workflow requires an interaction disclosure.
	 *
	 * @var bool
	 */
	private $interaction_disclosure_required;

	/**
	 * Create an AI system record.
	 *
	 * @param string $id Stable local identifier.
	 * @param string $name Human-readable name.
	 * @param string $type System type, for example chatbot or generator.
	 * @param string $source Evidence/source identifier.
	 * @param bool   $interaction_disclosure_required Whether interaction disclosure is required by the configured workflow.
	 * @throws InvalidArgumentException When a required field is empty.
	 */
	public function __construct(
		string $id,
		string $name,
		string $type,
		string $source,
		bool $interaction_disclosure_required = false
	) {
		$id     = trim( $id );
		$name   = trim( $name );
		$type   = trim( $type );
		$source = trim( $source );

		if ( '' === $id || '' === $name || '' === $type || '' === $source ) {
			throw new InvalidArgumentException( 'AI system id, name, type and source must be non-empty.' );
		}

		$this->id                              = $id;
		$this->name                            = $name;
		$this->type                            = $type;
		$this->source                          = $source;
		$this->interaction_disclosure_required = $interaction_disclosure_required;
	}

	/**
	 * Get the stable local identifier.
	 *
	 * @return string
	 */
	public function id(): string {
		return $this->id;
	}

	/**
	 * Get the human-readable name.
	 *
	 * @return string
	 */
	public function name(): string {
		return $this->name;
	}

	/**
	 * Get the system type.
	 *
	 * @return string
	 */
	public function type(): string {
		return $this->type;
	}

	/**
	 * Get the evidence/source identifier.
	 *
	 * @return string
	 */
	public function source(): string {
		return $this->source;
	}

	/**
	 * Whether interaction disclosure is configured as required.
	 *
	 * @return bool
	 */
	public function interaction_disclosure_required(): bool {
		return $this->interaction_disclosure_required;
	}

	/**
	 * Return a stable array representation for persistence/export adapters.
	 *
	 * @return array<string, mixed>
	 */
	public function to_array(): array {
		return array(
			'id'                              => $this->id,
			'name'                            => $this->name,
			'type'                            => $this->type,
			'source'                          => $this->source,
			'interaction_disclosure_required' => $this->interaction_disclosure_required,
		);
	}
}
