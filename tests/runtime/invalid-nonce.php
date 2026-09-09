<?php
/**
 * Verify that an administrator mutation with an invalid nonce is rejected.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Admin\AdminPage;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

require __DIR__ . '/assertions.php';

$repository   = new WordPressOptionsRegistryRepository();
$before_count = $repository->load()->count();
wp_set_current_user( ai_transparency_runtime_admin_id() );

$_POST = array(
	'action'        => 'kairoseth_ai_transparency_save_system',
	'system_id'     => '',
	'system_name'   => 'Invalid nonce system',
	'system_type'   => 'chatbot',
	'review_status' => 'pending',
	'_kat_nonce'    => 'invalid-runtime-nonce',
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
	$rejected = true;
}

ai_transparency_runtime_assert( $rejected, 'Invalid nonce was not rejected in real WordPress runtime.' );
ai_transparency_runtime_assert( $before_count === $repository->load()->count(), 'Invalid nonce mutation changed registry state.' );

echo "Invalid-nonce runtime rejection passed.\n";
