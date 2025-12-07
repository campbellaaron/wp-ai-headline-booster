<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class HEADLINE_BOOSTER_AITS_REST {

    public static function init() {
        add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
    }

    public static function register_routes() {
        register_rest_route(
            'headline-booster/v1',
            '/generate',
            array(
                'methods'             => 'POST',
                'callback'            => array( __CLASS__, 'handle_generate' ),
                'permission_callback' => function () {
                    return current_user_can( 'edit_posts' );
                },
                'args'                => array(
                    'title'   => array(
                        'required' => true,
                        'type'     => 'string',
                    ),
                    'excerpt' => array(
                        'required' => false,
                        'type'     => 'string',
                    ),
                ),
            )
        );
    }

    public static function handle_generate( WP_REST_Request $request ) {
        $title   = sanitize_text_field( $request->get_param( 'title' ) );
        $excerpt = sanitize_textarea_field( $request->get_param( 'excerpt' ) );

        if ( empty( $title ) ) {
            return new WP_Error(
                'hbaits_no_title',
                __( 'No title provided.', 'headline-booster-ai-title-suggestions' ),
                array( 'status' => 400 )
            );
        }

        $variants = HEADLINE_BOOSTER_AITS_Client::generate_headlines( $title, $excerpt );

        if ( is_wp_error( $variants ) ) {
            return $variants;
        }

        return rest_ensure_response(
            array(
                'original' => $title,
                'variants' => $variants,
            )
        );
    }
}
