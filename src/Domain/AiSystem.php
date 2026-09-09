<?php
/**
 * AI system domain model.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Domain;

use InvalidArgumentException;

final class AiSystem {
	/** @var string */
	private $id;

	/** @var string */
	private $name;

	/** @var string */
	private $type;

	/** @var string */
	private $source;

	/** @var bool */
	private $interactionDisclosureRequired;

	/**
	 * @param string $id Stable local identifier.
	 * @param string $name Human-readable name.
	 * @param string $type System type, for example chatbot or generator.
	 * @param string $source Evidence/source identifier.
	 * @param bool   $interaction_disclosure_required Whether interaction disclosure is required by the configured workflow.
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

		$this->id                            = $id;
		$this->name                          = $name;
		$this->type                          = $type;
		$this->source                        = $source;
		$this->interactionDisclosureRequired = $interaction_disclosure_required;
	}

	public function id(): string {
		return $this->id;
	}

	public function name(): string {
		return $this->name;
	}

	public function type(): string {
		return $this->type;
	}

	public function source(): string {
		return $this->source;
	}

	public function interactionDisclosureRequired(): bool {
		return $this->interactionDisclosureRequired;
	}

	/**
	 * Stable array representation for persistence/export adapters.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(): array {
		return array(
			'id'                              => $this->id,
			'name'                            => $this->name,
			'type'                            => $this->type,
			'source'                          => $this->source,
			'interaction_disclosure_required' => $this->interactionDisclosureRequired,
		);
	}
}
