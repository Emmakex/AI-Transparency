<?php
/**
 * Contract for deterministic discovery detectors.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Discovery;

/**
 * Converts one immutable technical observation into bounded discovery evidence.
 */
interface DetectorInterface {
	/**
	 * Detect a supported integration from one WordPress plugin observation.
	 *
	 * Returning null means the observation does not belong to this detector.
	 * A non-null result may still be outside the detector's validated support
	 * boundary; that state is represented by DiscoveryResult::supported().
	 *
	 * @param PluginObservation $observation Immutable plugin observation.
	 * @return DiscoveryResult|null
	 */
	public function detect( PluginObservation $observation );
}
