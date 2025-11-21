<?php

/**
 * Plugin Name:       Headline Booster – AI Title Suggestions
 * Description:       Generate AI-powered headline variants with scoring to improve clarity, SEO, and click-through rate directly from the block editor.
 * Version:           1.0.0
 * Author:            Aaron Campbell
 * Author URI:        https://campbellaaron.github.io
 * Text Domain:       headline-booster
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'HB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once HB_PLUGIN_DIR . 'includes/class-hb-settings.php';
require_once HB_PLUGIN_DIR . 'includes/class-hb-rest.php';
require_once HB_PLUGIN_DIR . 'includes/class-hb-ai-client.php';

class Headline_Booster {

    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
    }

    public function init() {
        HB_Settings::init();
        HB_REST::init();
    }

    public function enqueue_editor_assets() {
        wp_enqueue_script(
            'headline-booster-sidebar',
            HB_PLUGIN_URL . 'assets/js/editor-sidebar.js',
            array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-api-fetch' ),
            '1.0.0',
            true
        );

        wp_enqueue_style(
            'headline-booster-sidebar',
            HB_PLUGIN_URL . 'assets/css/editor-sidebar.css',
            array(),
            '1.0.0'
        );

        wp_localize_script(
            'headline-booster-sidebar',
            'HeadlineBoosterSettings',
            array(
                // Full REST URL for apiFetch({ url: ... }).
                'apiUrl' => esc_url_raw( rest_url( 'headline-booster/v1/generate' ) ),
                'nonce'  => wp_create_nonce( 'wp_rest' ),
            )
        );
    }

    public function plugin_action_links( $links ) {
        $settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . HB_Settings::PAGE_SLUG ) ) . '">'
            . esc_html__( 'Settings', 'headline-booster' ) . '</a>';

        array_unshift( $links, $settings_link );

        return $links;
    }
}

new Headline_Booster();
