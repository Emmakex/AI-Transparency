<?php
/**
 * Exercise the real admin save handler for an existing AI system.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Admin\AdminPage;
use Kairoseth\AITransparency\Persistence\WordPressOptionsRegistryRepository;

require __DIR__ . '/assertions.php';

wp_set_current_user( ai_transparency_runtime_admin_id() );

$registry = ( new WordPressOptionsRegistryRepository() )->load();
$systems  = $registry->all();
ai_transparency_runtime_assert( 1 === count( $systems ), 'Expected exactly one AI system before runtime edit.' );
$system = reset( $systems );

$_POST = array(
	'action'                          => 'kairoseth_ai_transparency_save_system',
	'system_id'                       => $system->id(),
	'system_name'                     => 'Runtime Chatbot Updated',
	'system_type'                     => 'assistant',
	'interaction_context'             => 'Updated customer support workflow',
	'review_status'                   => 'reviewed',
	'interaction_disclosure_required' => '1',
	'_kat_nonce'                      => wp_create_nonce( 'kairoseth_ai_transparency_save_system' ),
);
$_REQUEST = $_POST;

( new AdminPage() )->handle_save();
