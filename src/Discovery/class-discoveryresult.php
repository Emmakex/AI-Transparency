<?php
/**
 * Deterministic AI integration discovery result.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Discovery;

use InvalidArgumentException;
use Kairoseth\AITransparency\Domain\AiSystem;

/**
 * Immutable detector result with explainable evidence and a stable signature.
 */
final class DiscoveryResult {
	/**
	 * Detector identifier.
	 *
	 * @var string
	 */
	private $detector_id;

	/**
	 * Integration slug.
	 *
	 * @var string
	 */
	private $integration_slug;

	/**
	 * Human-readable integration name.
	 *
	 * @var string
	 */
	private $integration_name;

	/**
	 * Observed integration version.
	 *
	 * @var string
	 */
	private $observed_version;

	/**
	 * Whether the observation is inside the validated detector boundary.
	 *
	 * @var bool
	 */
	private $supported;

	/**
	 * Explainable evidence collected by the detector.
	 *
	 * @var array<string, mixed>
	 */
	private $evidence;

	/**
	 * Stable signature for the observed evidence.
	 *
	 * @var string
	 */
	private $source_signature;

	/**
	 * Create one deterministic discovery result.
	 *
	 * @param string               $detector_id Detector identifier.
	 * @param string               $integration_slug Integration slug.
	 * @param string               $integration_name Integration name.
	 * @param string               $observed_version Observed version.
	 * @param bool                 $supported Whether this version is inside the validated boundary.
	 * @param array<string, mixed> $evidence Explainable evidence.
	 * @param string               $source_signature Stable evidence signature.
	 * @throws InvalidArgumentException When required result fields are missing.
	 */
	public function __construct(
		string $detector_id,
		string $integration_slug,
		string $integration_name,
		string $observed_version,
		bool $supported,
		array $evidence,
		string $source_signature
	) {
		$detector_id       = trim( $detector_id );
		$integration_slug  = trim( $integration_slug );
		$integration_name  = trim( $integration_name );
		$observed_version  = trim( $observed_version );
		$source_signature  = trim( $source_signature );

		if ( '' === $detector_id || '' === $integration_slug || '' === $integration_name || '' === $observed_version || '' === $source_signature ) {
			throw new InvalidArgumentException( 'Discovery result identifiers, version and signature must be non-empty.' );
		}

		$this->detector_id      = $detector_id;
		$this->integration_slug = $integration_slug;
		$this->integration_name = $integration_name;
		$this->observed_version = $observed_version;
		$this->supported        = $supported;
		$this->evidence         = $evidence;
		$this->source_signature = $source_signature;
	}

	/**
	 * Get the detector identifier.
	 *
	 * @return string
	 */
	public function detector_id(): string {
		return $this->detector_id;
	}

	/**
	 * Get the integration slug.
	 *
	 * @return string
	 */
	public function integration_slug(): string {
		return $this->integration_slug;
	}

	/**
	 * Get the integration name.
	 *
	 * @return string
	 */
	public function integration_name(): string {
		return $this->integration_name;
	}

	/**
	 * Get the observed version.
	 *
	 * @return string
	 */
	public function observed_version(): string {
		return $this->observed_version;
	}

	/**
	 * Whether the detector has validated this exact observation boundary.
	 *
	 * @return bool
	 */
	public function supported(): bool {
		return $this->supported;
	}

	/**
	 * Get the explainable evidence shape.
	 *
	 * @return array<string, mixed>
	 */
	public function evidence(): array {
		return $this->evidence;
	}

	/**
	 * Get the stable evidence signature.
	 *
	 * @return string
	 */
	public function source_signature(): string {
		return $this->source_signature;
	}

	/**
	 * Build the review-pending registry candidate for a supported observation.
	 *
	 * Discovery never writes this candidate automatically. A privileged server-side
	 * action must explicitly persist it after re-running the detector.
	 *
	 * @param string $timestamp UTC ISO-8601 timestamp used for lifecycle metadata.
	 * @return AiSystem|null
	 */
	public function candidate( string $timestamp ) {
		if ( ! $this->supported ) {
			return null;
		}

		$timestamp = trim( $timestamp );
		if ( '' === $timestamp ) {
			return null;
		}

		return new AiSystem(
			'discovered-' . $this->integration_slug,
			$this->integration_name,
			AiSystem::TYPE_OTHER,
			'detector:' . $this->detector_id . ':' . $this->source_signature,
			false,
			array(
				'source_origin'       => AiSystem::SOURCE_DISCOVERED,
				'status'              => AiSystem::STATUS_ACTIVE,
				'review_status'       => AiSystem::REVIEW_PENDING,
				'interaction_context' => '',
				'created_at'          => $timestamp,
				'updated_at'          => $timestamp,
				'reviewed_at'         => '',
			)
		);
	}
}
