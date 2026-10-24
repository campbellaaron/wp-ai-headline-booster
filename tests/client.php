<?php
// Offline request/response regression tests. No API calls or API key required.
define( 'ABSPATH', __DIR__ );
class WP_Error {
    public $code;
    public $data;
    public $message;
    public function __construct( $code, $message, $data = array() ) { $this->code = $code; $this->data = $data; $this->message = $message; }
    public function get_error_message() { return $this->message; }
}
function __( $text, $domain ) { return $text; }
function get_option( $key, $default = '' ) { return $GLOBALS['options'][$key] ?? $default; }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function wp_json_encode( $value ) { return json_encode( $value ); }
function wp_remote_post( $url, $args ) {
    $GLOBALS['calls'] = ( $GLOBALS['calls'] ?? 0 ) + 1;
    $GLOBALS['sent'] = json_decode( $args['body'], true );
    return empty( $GLOBALS['queue'] ) ? $GLOBALS['response'] : array_shift( $GLOBALS['queue'] );
}
function wp_remote_retrieve_response_code( $response ) { return $response['status']; }
function wp_remote_retrieve_body( $response ) { return $response['body']; }
require __DIR__ . '/../includes/class-hb-settings.php';
require __DIR__ . '/../includes/class-hb-ai-client.php';
function check( $condition, $message ) { if ( ! $condition ) { throw new Exception( $message ); } }
function response( $content, $finish = 'stop' ) {
    $GLOBALS['response'] = array( 'status' => 200, 'body' => json_encode( array( 'choices' => array( array( 'message' => array( 'content' => $content ), 'finish_reason' => $finish ) ) ) ) );
}
$GLOBALS['options'] = array();
check( HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title' )->code === 'hbaits_no_api_key', 'Missing key' );
$GLOBALS['options'][HEADLINE_BOOSTER_AITS_Settings::OPTION_API_KEY] = 'test-placeholder';
response( "10 Ways to Improve Your Blog\n1. A numbered suggestion\n- A bullet suggestion\nFourth\nFifth\nSixth" );
$titles = HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title', 'Excerpt' );
check( count( $titles ) === 5 && $titles[0] === '10 Ways to Improve Your Blog' && $titles[1] === 'A numbered suggestion', 'Headline parsing' );
check( $GLOBALS['sent']['model'] === 'gpt-6-luna' && $GLOBALS['sent']['reasoning_effort'] === 'none' && $GLOBALS['sent']['max_completion_tokens'] === 512 && ! isset( $GLOBALS['sent']['temperature'] ), 'Model request contract' );
foreach ( array( array( '', 'stop' ), array( 'Partial title', 'length' ), array( null, 'stop' ), array( array( 'unexpected' ), 'stop' ) ) as $case ) {
    response( $case[0], $case[1] );
    check( HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title' )->data['status'] === 502, 'Invalid success response must be an error' );
}
$GLOBALS['response'] = array( 'status' => 429, 'body' => '{"error":{"message":"Rate limited"}}' );
check( HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title' )->data['status'] === 429, 'Rate limit status' );
$GLOBALS['response'] = new WP_Error( 'transport', 'Failed' );
check( HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title' )->code === 'hbaits_openai_http_error', 'Transport failure' );
$reset = new WP_Error( 'http_request_failed', 'cURL error 35: Recv failure: Connection was reset' );
response( 'Recovered headline' );
$GLOBALS['calls'] = 0;
$GLOBALS['queue'] = array( $reset, $GLOBALS['response'] );
check( HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title' ) === array( 'Recovered headline' ) && $GLOBALS['calls'] === 2, 'Reset recovers on one retry' );
$GLOBALS['calls'] = 0;
$GLOBALS['queue'] = array( $reset, $reset );
check( is_wp_error( HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title' ) ) && $GLOBALS['calls'] === 2, 'Retry is bounded' );
foreach ( array( new WP_Error( 'http_request_failed', 'cURL error 60: SSL certificate problem' ), new WP_Error( 'http_request_failed', 'cURL error 28: Operation timed out' ), new WP_Error( 'http_request_failed', 'cURL error 35: Unsupported SSL protocol' ), array( 'status' => 401, 'body' => '{}' ), array( 'status' => 429, 'body' => '{}' ) ) as $failure ) {
    $GLOBALS['calls'] = 0;
    $GLOBALS['response'] = $failure;
    check( is_wp_error( HEADLINE_BOOSTER_AITS_Client::generate_headlines( 'Title' ) ) && $GLOBALS['calls'] === 1, 'Non-transient failure is not retried' );
}
echo "Client regression checks passed.\n";
