<?php
/**
 * Deterministic discovery orchestration.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Discovery;

use InvalidArgumentException;

/**
 * Runs registered deterministic detectors against immutable observations.
 */
final class DiscoveryEngine {
	/**
	 * Registered deterministic detectors.
	 *
	 * @var DetectorInterface[]
	 */
	private $detectors;

	/**
	 * Create the discovery engine.
	 *
	 * @param DetectorInterface[] $detectors Registered deterministic detectors.
	 * @throws InvalidArgumentException When a registered detector does not implement DetectorInterface.
	 */
	public function __construct( array $detectors ) {
		foreach ( $detectors as $detector ) {
			if ( ! $detector instanceof DetectorInterface ) {
				throw new InvalidArgumentException( 'Discovery detectors must implement DetectorInterface.' );
			}
		}

		$this->detectors = array_values( $detectors );
	}

	/**
	 * Run every detector over every immutable observation.
	 *
	 * A detector contributes at most one result per observation. Results preserve
	 * detector registration order and observation order so repeated discovery is
	 * deterministic for the same technical inventory.
	 *
	 * @param PluginObservation[] $observations Immutable plugin observations.
	 * @return DiscoveryResult[]
	 * @throws InvalidArgumentException When an observation is not a PluginObservation instance.
	 */
	public function discover( array $observations ): array {
		$results = array();

		foreach ( $this->detectors as $detector ) {
			foreach ( $observations as $observation ) {
				if ( ! $observation instanceof PluginObservation ) {
					throw new InvalidArgumentException( 'Discovery observations must be PluginObservation instances.' );
				}

				$result = $detector->detect( $observation );
				if ( null !== $result ) {
					$results[] = $result;
				}
			}
		}

		return $results;
	}

	/**
	 * Match a result selector only against freshly regenerated detector output.
	 *
	 * Detector id and source signature are selectors, never trusted evidence. The
	 * returned object always comes from the current immutable observation set.
	 *
	 * @param PluginObservation[] $observations Current immutable observations.
	 * @param string              $detector_id Detector identifier selector.
	 * @param string              $source_signature Evidence signature selector.
	 * @return DiscoveryResult|null
	 * @throws InvalidArgumentException When an observation is not a PluginObservation instance.
	 */
	public function find( array $observations, string $detector_id, string $source_signature ) {
		$detector_id      = trim( $detector_id );
		$source_signature = trim( $source_signature );

		if ( '' === $detector_id || '' === $source_signature ) {
			return null;
		}

		foreach ( $this->discover( $observations ) as $result ) {
			if ( $detector_id === $result->detector_id() && $source_signature === $result->source_signature() ) {
				return $result;
			}
		}

		return null;
	}
}
