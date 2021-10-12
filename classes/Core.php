<?php

/**
 * Class responsible for the overall functionality of the plugin
 */
namespace WPFInterview;

class Core {

	public $endpoint;

	public function __construct( $endpoint ) {

		// Set up the plugin endpoint
		$this->endpoint = $endpoint;

		$this->actions();

	}

	public function actions() {

		// Load textdomain
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain'] );

		// Register custom endpoints
		add_action( 'rest_api_init', [ $this, 'register_custom_endpoints' ] );

	}

	/**
	 * Register custom made endpointrs
	 * @hook rest_api_init
	 * @return [type] [description]
	 */
	public function register_custom_endpoints() {

		register_rest_route( 'wpfi/v1', '/get_data', array(
			'methods'	=> \WP_REST_Server::READABLE,
			'callback'	=> [ $this, 'rest_get_data_callback' ],
			'args'		=> [],
		) );

	}

	/**
	 * Endpoint Callback
	 * @param  \WP_REST_Request $request
	 * @return array
	 */
	public function rest_get_data_callback( \WP_REST_Request $request ) {
		return $this->do_request();;
	}

	public function do_request() {

		// Check if current website has ssl
		if( defined( 'WP_ENV' ) and WP_ENV == 'dev' ) {
			$args = [
				'sslverify' => false
			];
		} else {
			$args = [];
		}


		// Run the request
		$payload = wp_remote_get( $this->endpoint, $args );

		// Check if request its succesfull
		if( is_wp_error( $payload ) ) {
			return [
				'status' => false,
				'content' => $payload->get_error_message()
			];
		} else {

			// Check if request is in the correct format
			$decoded_payload = json_decode( $payload['body'] );

			if( $this->accept_payload( $decoded_payload ) ) {
				return [
					'status' => true,
					'content' => $decoded_payload
				];
			} else {
				return [
					'status' => false,
					'content' => \WP_Error( 'wpfi_wrong_content', __( 'Incorect content structure received, please check your provider', 'wpfi' ) )
				];
			}
			
		}

	}

	/**
	 * Check if payload has the desired structure
	 * @param  [type] $payload [description] ??
	 * @return [type]          [description] ?? 
	 */
	public function accept_payload( $payload ) {
		return ( isset( $payload['title'] ) and isset( $payload['data'] ) );
	}

	public function register_shortcode() {

	}

	public function register_admin_page() {

	}

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pods-geolocation-field',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
