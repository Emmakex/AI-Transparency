<?php
/**
 * Uninstall cleanup for Kairoseth AI Transparency.
 *
 * Deactivation and upgrades deliberately preserve plugin data. This file runs
 * only during an explicit WordPress uninstall and removes only site-local data
 * owned by this plugin.
 *
 * @package KairosethAITransparency
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$kairoseth_ai_transparency_option = 'kairoseth_ai_transparency_registry';

if ( is_multisite() ) {
	$kairoseth_ai_transparency_site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);

	foreach ( $kairoseth_ai_transparency_site_ids as $kairoseth_ai_transparency_site_id ) {
		switch_to_blog( (int) $kairoseth_ai_transparency_site_id );
		delete_option( $kairoseth_ai_transparency_option );
		restore_current_blog();
	}

	return;
}

delete_option( $kairoseth_ai_transparency_option );
