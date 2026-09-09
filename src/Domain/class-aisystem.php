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
	public const TYPE_CHATBOT     = 'chatbot';
	public const TYPE_ASSISTANT   = 'assistant';
	public const TYPE_GENERATOR   = 'generator';
	public const TYPE_RECOMMENDER = 'recommender';
	public const TYPE_CLASSIFIER  = 'classifier';
	public const TYPE_OTHER       = 'other';

	public const SOURCE_MANUAL     = 'manual';
	public const SOURCE_DISCOVERED = 'discovered';
	public const SOURCE_IMPORTED   = 'imported';

	public const STATUS_ACTIVE   = 'active';
	public const STATUS_ARCHIVED = 'archived';

	public const REVIEW_PENDING  = 'pending';
	public const REVIEW_REVIEWED = 'reviewed';

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
	 * System taxonomy type.
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
	 * Whether the configured workflow requires interaction disclosure.
	 *
	 * @var bool
	 */
	private $interaction_disclosure_required;

	/**
	 * Origin category for the record.
	 *
	 * @var string
	 */
	private $source_origin;

	/**
	 * Lifecycle status.
	 *
	 * @var string
	 */
	private $status;

	/**
	 * Administrator review status.
	 *
	 * @var string
	 */
	private $review_status;

	/**
	 * Description of where the AI system is used.
	 *
	 * @var string
	 */
	private $interaction_context;

	/**
	 * Creation timestamp.
	 *
	 * @var string
	 */
	private $created_at;

	/**
	 * Last update timestamp.
	 *
	 * @var string
	 */
	private $updated_at;

	/**
	 * Last review timestamp.
	 *
	 * @var string
	 */
	private $reviewed_at;

	/**
	 * Create an AI system record.
	 *
	 * @param string               $id Stable local identifier.
	 * @param string               $name Human-readable name.
	 * @param string               $type System type.
	 * @param string               $source Evidence/source identifier.
	 * @param bool                 $interaction_disclosure_required Whether interaction disclosure is required by the configured workflow.
	 * @param array<string, mixed> $metadata Optional lifecycle and origin metadata.
	 * @throws InvalidArgumentException When the record is invalid.
	 */
	public function __construct(
		string $id,
		string $name,
		string $type,
		string $source,
		bool $interaction_disclosure_required = false,
		array $metadata = array()
	) {
		$id     = trim( $id );
		$name   = trim( $name );
		$type   = trim( $type );
		$source = trim( $source );

		if ( '' === $id || '' === $name || '' === $type || '' === $source ) {
			throw new InvalidArgumentException( 'AI system id, name, type and source must be non-empty.' );
		}

		if ( ! in_array( $type, self::supported_types(), true ) ) {
			throw new InvalidArgumentException( 'Unsupported AI system type.' );
		}

		$source_origin = isset( $metadata['source_origin'] ) ? trim( (string) $metadata['source_origin'] ) : self::SOURCE_MANUAL;
		$status        = isset( $metadata['status'] ) ? trim( (string) $metadata['status'] ) : self::STATUS_ACTIVE;
		$review_status = isset( $metadata['review_status'] ) ? trim( (string) $metadata['review_status'] ) : self::REVIEW_PENDING;

		if ( ! in_array( $source_origin, self::supported_source_origins(), true ) ) {
			throw new InvalidArgumentException( 'Unsupported AI system source origin.' );
		}

		if ( ! in_array( $status, self::supported_statuses(), true ) ) {
			throw new InvalidArgumentException( 'Unsupported AI system status.' );
		}

		if ( ! in_array( $review_status, self::supported_review_statuses(), true ) ) {
			throw new InvalidArgumentException( 'Unsupported AI system review status.' );
		}

		$this->id                              = $id;
		$this->name                            = $name;
		$this->type                            = $type;
		$this->source                          = $source;
		$this->interaction_disclosure_required = $interaction_disclosure_required;
		$this->source_origin                   = $source_origin;
		$this->status                          = $status;
		$this->review_status                   = $review_status;
		$this->interaction_context             = isset( $metadata['interaction_context'] ) ? trim( (string) $metadata['interaction_context'] ) : '';
		$this->created_at                      = isset( $metadata['created_at'] ) ? trim( (string) $metadata['created_at'] ) : '';
		$this->updated_at                      = isset( $metadata['updated_at'] ) ? trim( (string) $metadata['updated_at'] ) : '';
		$this->reviewed_at                     = isset( $metadata['reviewed_at'] ) ? trim( (string) $metadata['reviewed_at'] ) : '';
	}

	/**
	 * Hydrate a system from its stable persistence shape.
	 *
	 * @param array<string, mixed> $data Persisted system data.
	 * @return self
	 */
	public static function from_array( array $data ): self {
		return new self(
			isset( $data['id'] ) ? (string) $data['id'] : '',
			isset( $data['name'] ) ? (string) $data['name'] : '',
			isset( $data['type'] ) ? (string) $data['type'] : '',
			isset( $data['source'] ) ? (string) $data['source'] : '',
			! empty( $data['interaction_disclosure_required'] ),
			array(
				'source_origin'       => $data['source_origin'] ?? self::SOURCE_MANUAL,
				'status'              => $data['status'] ?? self::STATUS_ACTIVE,
				'review_status'       => $data['review_status'] ?? self::REVIEW_PENDING,
				'interaction_context' => $data['interaction_context'] ?? '',
				'created_at'          => $data['created_at'] ?? '',
				'updated_at'          => $data['updated_at'] ?? '',
				'reviewed_at'         => $data['reviewed_at'] ?? '',
			)
		);
	}

	/**
	 * Return a copy with selected persistence fields replaced.
	 *
	 * @param array<string, mixed> $changes Changed values.
	 * @return self
	 */
	public function with_updates( array $changes ): self {
		return self::from_array( array_merge( $this->to_array(), $changes ) );
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
	 * Get the taxonomy type.
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
	 * Get the source origin category.
	 *
	 * @return string
	 */
	public function source_origin(): string {
		return $this->source_origin;
	}

	/**
	 * Get lifecycle status.
	 *
	 * @return string
	 */
	public function status(): string {
		return $this->status;
	}

	/**
	 * Get review status.
	 *
	 * @return string
	 */
	public function review_status(): string {
		return $this->review_status;
	}

	/**
	 * Get interaction context.
	 *
	 * @return string
	 */
	public function interaction_context(): string {
		return $this->interaction_context;
	}

	/**
	 * Get creation timestamp.
	 *
	 * @return string
	 */
	public function created_at(): string {
		return $this->created_at;
	}

	/**
	 * Get last update timestamp.
	 *
	 * @return string
	 */
	public function updated_at(): string {
		return $this->updated_at;
	}

	/**
	 * Get last review timestamp.
	 *
	 * @return string
	 */
	public function reviewed_at(): string {
		return $this->reviewed_at;
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
			'source_origin'                   => $this->source_origin,
			'status'                          => $this->status,
			'review_status'                   => $this->review_status,
			'interaction_context'             => $this->interaction_context,
			'interaction_disclosure_required' => $this->interaction_disclosure_required,
			'created_at'                      => $this->created_at,
			'updated_at'                      => $this->updated_at,
			'reviewed_at'                     => $this->reviewed_at,
		);
	}

	/**
	 * Return supported AI system types.
	 *
	 * @return string[]
	 */
	public static function supported_types(): array {
		return array(
			self::TYPE_CHATBOT,
			self::TYPE_ASSISTANT,
			self::TYPE_GENERATOR,
			self::TYPE_RECOMMENDER,
			self::TYPE_CLASSIFIER,
			self::TYPE_OTHER,
		);
	}

	/**
	 * Return supported record source origins.
	 *
	 * @return string[]
	 */
	public static function supported_source_origins(): array {
		return array( self::SOURCE_MANUAL, self::SOURCE_DISCOVERED, self::SOURCE_IMPORTED );
	}

	/**
	 * Return supported lifecycle statuses.
	 *
	 * @return string[]
	 */
	public static function supported_statuses(): array {
		return array( self::STATUS_ACTIVE, self::STATUS_ARCHIVED );
	}

	/**
	 * Return supported administrator review statuses.
	 *
	 * @return string[]
	 */
	public static function supported_review_statuses(): array {
		return array( self::REVIEW_PENDING, self::REVIEW_REVIEWED );
	}
}
