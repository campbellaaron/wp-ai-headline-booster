<?php
/**
 * Uninstall routine for AI Headline Booster.
 *
 * This file is executed when the plugin is deleted via the WordPress admin.
 * It must not be directly accessed.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// List all options used by the plugin.
$option_keys = array(
    'hb_api_key',
    'hb_default_tone',
);

// Delete each option.
foreach ( $option_keys as $key ) {
    delete_option( $key );
    delete_site_option( $key ); // multisite support
}
