<?php
/**
 * Exercise the real admin save handler for a new AI system.
 *
 * @package KairosethAITransparency
 */

use Kairoseth\AITransparency\Admin\AdminPage;

require __DIR__ . '/assertions.php';

wp_set_current_user( ai_transparency_runtime_admin_id() );

$_POST = array(
	'action'                          => 'kairoseth_ai_transparency_save_system',
	'system_id'                       => '',
	'system_name'                     => 'Runtime Chatbot',
	'system_type'                     => 'chatbot',
	'interaction_context'             => 'Customer support widget',
	'review_status'                   => 'pending',
	'interaction_disclosure_required' => '1',
	'_kat_nonce'                      => wp_create_nonce( 'kairoseth_ai_transparency_save_system' ),
);
$_REQUEST = $_POST;

( new AdminPage() )->handle_save();
