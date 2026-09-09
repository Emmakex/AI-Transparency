<?php
/**
 * Verify that a non-administrator cannot mutate the registry.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Admin\AdminPage;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

require __DIR__ . '/assertions.php';

$repository   = new WordPressOptionsRegistryRepository();
$before_count = $repository->load()->count();
$user         = get_user_by( 'login', 'runtime-editor' );
ai_transparency_runtime_assert( false !== $user, 'Runtime editor was not found.' );
wp_set_current_user( (int) $user->ID );

$_POST = array(
	'action'        => 'kairoseth_ai_transparency_save_system',
	'system_id'     => '',
	'system_name'   => 'Unauthorized system',
	'system_type'   => 'chatbot',
	'review_status' => 'pending',
	'_kat_nonce'    => wp_create_nonce( 'kairoseth_ai_transparency_save_system' ),
);
$_REQUEST = $_POST;

add_filter(
	'wp_die_handler',
	static function () {
		return static function ( $message ) {
			throw new RuntimeException( wp_strip_all_tags( (string) $message ) );
		};
	}
);

$rejected = false;
try {
	( new AdminPage() )->handle_save();
} catch ( RuntimeException $exception ) {
	$rejected = false !== strpos( $exception->getMessage(), 'permission' );
}

ai_transparency_runtime_assert( $rejected, 'Unauthorized runtime mutation was not rejected by WordPress capability enforcement.' );
ai_transparency_runtime_assert( $before_count === $repository->load()->count(), 'Unauthorized runtime mutation changed registry state.' );

echo "Unauthorized-role runtime rejection passed.\n";
