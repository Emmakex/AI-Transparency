<?php
/**
 * Phase 6 evidence JSON presentation encoder.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Export;

use RuntimeException;

/**
 * Encodes a completed snapshot for direct administrator download.
 */
final class EvidenceJsonEncoder {
	/**
	 * Encode the complete snapshot as readable JSON after signature calculation.
	 *
	 * @param EvidenceSnapshot $snapshot Completed evidence snapshot.
	 * @return string
	 * @throws RuntimeException When JSON encoding fails.
	 */
	public function encode( EvidenceSnapshot $snapshot ): string {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode -- Pure export presentation service intentionally has no WordPress runtime dependency.
		$encoded = json_encode(
			$snapshot->to_array(),
			JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		);

		if ( false === $encoded ) {
			throw new RuntimeException( 'Evidence snapshot download JSON encoding failed.' );
		}

		return $encoded . "\n";
	}
}
