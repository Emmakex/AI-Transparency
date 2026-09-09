<?php
/**
 * Runtime acceptance helpers executed inside a real WordPress installation.
 *
 * @package KairosethAITransparency
 */

if ( ! function_exists( 'ai_transparency_runtime_assert' ) ) {
	/**
	 * Fail the runtime smoke with an actionable message.
	 *
	 * @param bool   $condition Assertion result.
	 * @param string $message Failure message.
	 * @return void
	 */
	function ai_transparency_runtime_assert( bool $condition, string $message ): void {
		if ( ! $condition ) {
			throw new RuntimeException( $message );
		}
	}
}

if ( ! function_exists( 'ai_transparency_runtime_admin_id' ) ) {
	/**
	 * Resolve the acceptance administrator.
	 *
	 * @return int
	 */
	function ai_transparency_runtime_admin_id(): int {
		$user = get_user_by( 'login', 'runtime-admin' );
		ai_transparency_runtime_assert( false !== $user, 'Runtime administrator was not found.' );

		return (int) $user->ID;
	}
}
