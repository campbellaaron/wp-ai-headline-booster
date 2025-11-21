<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class HB_Settings {

    const OPTION_API_KEY = 'hb_api_key';
    const OPTION_TONE    = 'hb_default_tone';

    // Slug used in the URL (?page=...).
    const PAGE_SLUG    = 'headline-booster-settings';

    // Option group for register_setting() / settings_fields().
    const OPTION_GROUP = 'headline_booster_settings';

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    public static function add_menu() {
        add_options_page(
            __( 'Headline Booster Settings', 'headline-booster' ),
            __( 'Headline Booster', 'headline-booster' ),
            'manage_options',
            self::PAGE_SLUG,
            array( __CLASS__, 'render_settings_page' )
        );
    }

    public static function register_settings() {
        // Register options.
        register_setting( self::OPTION_GROUP, self::OPTION_API_KEY );
        register_setting(
            self::OPTION_GROUP,
            self::OPTION_TONE,
            array(
                'type'              => 'string',
                'sanitize_callback' => array( __CLASS__, 'sanitize_tone' ),
                'default'           => 'Neutral',
            )
        );

        // Main section.
        add_settings_section(
            'hb_main_section',
            __( 'API Settings', 'headline-booster' ),
            '__return_false',
            self::PAGE_SLUG
        );

        // API key field.
        add_settings_field(
            self::OPTION_API_KEY,
            __( 'OpenAI API Key', 'headline-booster' ),
            array( __CLASS__, 'render_api_key_field' ),
            self::PAGE_SLUG,
            'hb_main_section'
        );

        // Tone field.
        add_settings_field(
            self::OPTION_TONE,
            __( 'Default Tone', 'headline-booster' ),
            array( __CLASS__, 'render_tone_field' ),
            self::PAGE_SLUG,
            'hb_main_section'
        );
    }

    public static function render_api_key_field() {
        $value = esc_attr( get_option( self::OPTION_API_KEY, '' ) );
        echo '<input type="password" name="' . esc_attr( self::OPTION_API_KEY ) . '" value="' . $value . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__( 'Create a secret key in your OpenAI dashboard and paste it here.', 'headline-booster' ) . '</p>';
    }

    public static function render_tone_field() {
        $value = esc_attr( get_option( self::OPTION_TONE, 'Neutral' ) );
        $tones = array( 'Neutral', 'Casual', 'Formal', 'Excited', 'Professional', 'Humorous', 'Clickbait', 'Dramatic' );

        echo '<select name="' . esc_attr( self::OPTION_TONE ) . '">';
        foreach ( $tones as $tone ) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr( $tone ),
                selected( $value, $tone, false ),
                esc_html( $tone )
            );
        }
        echo '</select>';
    }

    public static function sanitize_tone( $value ) {
        $valid = array(
            'Neutral', 'Casual', 'Formal', 'Excited',
            'Professional', 'Humorous', 'Clickbait', 'Dramatic'
        );
        return in_array( $value, $valid, true ) ? $value : 'Neutral';
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Headline Booster Settings', 'headline-booster' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( self::OPTION_GROUP );
                do_settings_sections( self::PAGE_SLUG );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
