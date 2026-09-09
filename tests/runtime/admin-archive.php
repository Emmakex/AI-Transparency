<?php
/**
 * Exercise the real admin archive handler.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Admin\AdminPage;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

require __DIR__ . '/assertions.php';

wp_set_current_user( ai_transparency_runtime_admin_id() );

$registry = ( new WordPressOptionsRegistryRepository() )->load();
$systems  = $registry->all();
ai_transparency_runtime_assert( 1 === count( $systems ), 'Expected exactly one AI system before runtime archive.' );
$system = reset( $systems );

$_POST = array(
	'action'     => 'kairoseth_ai_transparency_archive_system',
	'system_id'  => $system->id(),
	'_kat_nonce' => wp_create_nonce( 'kairoseth_ai_transparency_archive_' . $system->id() ),
);
$_REQUEST = $_POST;

( new AdminPage() )->handle_archive();
