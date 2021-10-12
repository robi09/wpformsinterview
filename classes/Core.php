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

		$payload = $this->do_request();

		return $payload;

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

		$payload = wp_remote_get( $this->endpoint, $args );

		if( is_wp_error( $payload ) ) {
			return $payload;
		} else {
			return json_decode($payload['body']);
		}
	}

	/**
	 * Check if payload has the desired structure
	 * @param  [type] $payload [description] ??
	 * @return [type]          [description] ?? 
	 */
	public function accept_payload( $payload ) {
		// if( isset( $payload['title']))
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
