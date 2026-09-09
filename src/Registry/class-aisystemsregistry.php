<?php
/**
 * AI systems registry domain service.
 *
 * @package KairosethAITransparency
 */

namespace Kairoseth\AITransparency\Registry;

use Kairoseth\AITransparency\Domain\AiSystem;

/**
 * Maintains a deterministic in-memory set of AI systems by stable id.
 */
final class AiSystemsRegistry {
	/**
	 * Registered systems keyed by stable local id.
	 *
	 * @var array<string, AiSystem>
	 */
	private $systems = array();

	/**
	 * Add or replace a system by stable local id.
	 *
	 * @param AiSystem $system AI system.
	 * @return void
	 */
	public function put( AiSystem $system ): void {
		$this->systems[ $system->id() ] = $system;
	}

	/**
	 * Find a system.
	 *
	 * @param string $id Stable local identifier.
	 * @return AiSystem|null
	 */
	public function find( string $id ) {
		return $this->systems[ $id ] ?? null;
	}

	/**
	 * Return all systems in deterministic id order.
	 *
	 * @return AiSystem[]
	 */
	public function all(): array {
		$systems = $this->systems;
		ksort( $systems, SORT_STRING );

		return array_values( $systems );
	}

	/**
	 * Return the number of registered systems.
	 *
	 * @return int
	 */
	public function count(): int {
		return count( $this->systems );
	}
}
