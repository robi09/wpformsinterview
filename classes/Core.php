<?php

/**
 * Class responsible for the overall functionality of the plugin
 */
namespace WPFInterview;

class Core {

	// Params
	public $endpoint;

	// Dependencies
	public $frequency;
	public $storage;
	public $cli;

	public function __construct( $endpoint ) {

		// Set up the plugin endpoint
		$this->endpoint = $endpoint;

		// Dependencies
		$this->frequency = new Frequency();
		$this->storage = new Storage();

		// Hooks
		$this->actions();

		$this->cli = new CliManagement();

	}

	public function init() {
		$this->cli->register_commands();
		$this->register_shortcode();
	}

	/**
	 * Register or enqueue assets
	 * @return void
	 */
	public function assets() {

		wp_register_script( 'wpfi_interview', WPFI_URL . 'assets/js/shortcode.js', [ 'jquery' ], false, false 	);
		
		// Localize required data to be used in front-end JS
		wp_add_inline_script(
		'wpfi_interview', 
		'var wpfiRestApi = ' . json_encode( array( 
			'root' => esc_url_raw( rest_url() ) . 'wpfi/v1', 
			'nonce' => wp_create_nonce( 'wp_rest' ),
		) ), 'before' );
	}

	/**
	 * Define action hooks
	 * @return void
	 */
	public function actions() {

		// Init hooks
		add_action( 'init', [ $this, 'init'] );

		// Load assets
		add_action( 'wp_enqueue_scripts', 	[ $this, 'assets' ] );			

		// Load textdomain
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain'] );

		// Register custom endpoints
		add_action( 'rest_api_init', [ $this, 'register_custom_endpoints' ] );

	}

	/**
	 * Register custom made endpointrs
	 * @hook rest_api_init
	 * @return void
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
		$return = '';

		if( Frequency::is_passed() ) {
			
			// Do the request
			$payload = $this->do_request();

			// Save payload to db
			Storage::Save( $payload );

			// Update frequency time
			Frequency::update();

			// Return the request
			$return = $payload;
		} else {
			$return = Storage::Get();
		}

		return $return;
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
	 * @param  obj $payload
	 * @return boolean
	 */
	public function accept_payload( $payload ) {
		return ( isset( $payload->title ) and isset( $payload->data ) );
	}

	public function register_shortcode() {
		add_shortcode( 'wpfi_table', [ $this, 'wpfi_table_shortcode_callback' ] );
	}

	public function wpfi_table_shortcode_callback() {
		
		// Load shortcode script only when shortcode its used
		wp_enqueue_script( 'wpfi_interview' );

		ob_start();
			include_once WPFI_PATH . 'templates/shortcode.php';
			$output = ob_get_contents();
	    ob_end_clean();

	    return $output;
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
