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
$headline_booster_option_keys = array(
    'hb_api_key',
    'hb_default_tone',
);

foreach ( $headline_booster_option_keys as $headline_booster_key ) {
    delete_option( $headline_booster_key );
    delete_site_option( $headline_booster_key ); // multisite support
}
