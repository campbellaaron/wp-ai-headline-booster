<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class HEADLINE_BOOSTER_AITS_Client {

    const API_ENDPOINT  = 'https://api.openai.com/v1/chat/completions';
    const DEFAULT_MODEL = 'gpt-4o-mini';

    /**
     * Generate alternative headlines using OpenAI.
     *
     * @param string $title   Original post title.
     * @param string $excerpt Optional excerpt/content.
     *
     * @return array|WP_Error
     */
    public static function generate_headlines( $title, $excerpt = '' ) {
        $api_key = get_option( HEADLINE_BOOSTER_AITS_Settings::OPTION_API_KEY );

        if ( empty( $api_key ) ) {
            return new WP_Error(
                'hbaits_no_api_key',
                __( 'OpenAI API key is not set. Please add it in Settings → Headline Booster.', 'headline-booster-ai-title-suggestions' ),
                array( 'status' => 500 )
            );
        }

        $tone = get_option( HEADLINE_BOOSTER_AITS_Settings::OPTION_TONE, 'Neutral' );

        $prompt  = "You are a copywriting assistant that writes catchy, clear, SEO-friendly blog post titles.\n\n";
        $prompt .= 'Original title: "' . $title . "\"\n";
        if ( ! empty( $excerpt ) ) {
            $prompt .= 'Post excerpt or summary: "' . $excerpt . "\"\n";
        }
        $prompt .= "Desired tone: {$tone}\n\n";
        $prompt .= "Generate 5 alternative titles that match the tone and improve clarity and click-through rate.\n";
        $prompt .= "Return each title on its own line, with NO numbering, bullets, or extra commentary.";

        $body = array(
            'model'    => self::DEFAULT_MODEL,
            'messages' => array(
                array(
                    'role'    => 'system',
                    'content' => 'You generate alternative titles for blog posts.',
                ),
                array(
                    'role'    => 'user',
                    'content' => $prompt,
                ),
            ),
            'temperature' => 0.8,
        );

        $response = wp_remote_post(
            self::API_ENDPOINT,
            array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type'  => 'application/json',
                ),
                'body'    => wp_json_encode( $body ),
                'timeout' => 20,
            )
        );

        if ( is_wp_error( $response ) ) {
            return new WP_Error(
                'hbaits_openai_http_error',
                __( 'Error communicating with OpenAI API.', 'headline-booster-ai-title-suggestions' ),
                array(
                    'status' => 500,
                    'debug'  => $response->get_error_message(),
                )
            );
        }

        $status_code = wp_remote_retrieve_response_code( $response );
        $raw_body    = wp_remote_retrieve_body( $response );
        $data        = json_decode( $raw_body, true );

        if ( 200 !== $status_code || empty( $data['choices'][0]['message']['content'] ) ) {
            return new WP_Error(
                'hbaits_openai_bad_response',
                __( 'Unexpected response from OpenAI API.', 'headline-booster-ai-title-suggestions' ),
                array(
                    'status' => $status_code,
                    'debug'  => $raw_body,
                )
            );
        }

        $content = $data['choices'][0]['message']['content'];

        $lines    = preg_split( '/\r\n|\r|\n/', $content );
        $variants = array();

        foreach ( $lines as $line ) {
            $line = trim( $line );

            if ( '' === $line ) {
                continue;
            }

            // Remove leading bullets/numbers
            $line = preg_replace( '/^[\-\*\d\.\)\s]+/', '', $line );

            // Remove surrounding quotes (single, double, curly)
            $line = trim( $line, "\"'“”‟„”" );

            if ( '' !== $line ) {
                $variants[] = $line;
            }
        }

        if ( empty( $variants ) ) {
            $variants[] = $content;
        }

        return array_slice( $variants, 0, 5 );
    }
}
