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

	// Additional modules
	public $cli;

	public function __construct( $endpoint ) {

		// Set up the plugin endpoint
		$this->endpoint = $endpoint;

		// Dependencies
		$this->frequency = new Frequency();
		$this->storage = new Storage();

		// Hooks
		$this->actions();

		// Additional modules
		$this->cli = new CliManagement();

	}

	/**
	 * Init hook callback
	 * @return void
	 */
	public function init() {
		$this->cli->register_commands();
		$this->register_shortcode();
	}

	/**
	 * Register or enqueue assets
	 * @return void
	 */
	public function assets() {

		// Main javascript script
		wp_register_script( 'wpfi_interview', WPFI_URL . 'assets/js/core.js', [ 'jquery' ], false, false );
		
		// Localize required data to be used in front-end JS
		wp_add_inline_script(
		'wpfi_interview', 
		'var wpfiRestApi = ' . 
			json_encode(
				array( 
					'root' => esc_url_raw( rest_url() ) . 'wpfi/v1', 
					'nonce' => wp_create_nonce( 'wp_rest' ),
				)
			),
		'before' );

		// Shortcode JS
		wp_add_inline_script(
		'wpfi_interview', 
		'jQuery(document).ready(function($) { wpfi.get_data(); });', 
		'before' );
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

		// Register custom pages
        add_action( 'admin_menu', [ $this, 'custom_pages' ], 101 );

        // Register admin scripts
	    add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

	}

	/**
	 * Register admin scripts
	 * @return void
	 */
	public function admin_scripts() {

        if( \is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php' ) ) {

        	// Load the WP Mail SMTP admin stylesheet
			wp_register_style(
				'wp-mail-smtp-admin',
				wp_mail_smtp()->assets_url . '/css/smtp-admin.min.css',
				false,
				WPMS_PLUGIN_VER
			);

			// Load main assets of the plugin on the admin page
        	$this->assets();
        }

	}

	/**
     * Register custom pages
     * 
     * @hook 'admin_menu'
     * @return void
     */
    public function custom_pages() {

        // Versions page
        add_menu_page(
			__( 'WPF Interview', 'vox'),
			'WPFI Table',
			'manage_options',
			'wpfi_table',
	        [ $this, 'admin_page_callback' ]
        );

    }

    /**
     * Admin page callback function
     * 
     * @return void
     */
    public function admin_page_callback() {
		wp_enqueue_style( 'wp-mail-smtp-admin' );
		wp_enqueue_script( 'wpfi_interview' );   

		include_once WPFI_PATH . 'templates/admin_page.php';
    }

	/**
	 * Register custom made endpoints
	 * 
	 * @hook rest_api_init
	 * @return void
	 */
	public function register_custom_endpoints() {

		// Endpoint responsable of getting data from remote endpoint
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

	/**
	 * Perform the request on the defined endpoint
	 * @return array Array containing the status of the request together with the content received from the endpoint
	 */
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

	/**
	 * Register the plugin shortcodes
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( 'wpfi_table', [ $this, 'wpfi_table_shortcode_callback' ] );
	}

	/**
	 * Shortcode callback
	 * @shortcode wpfi_table
	 * @return string HTML provided by template
	 */
	public function wpfi_table_shortcode_callback() {
		
		// Load shortcode script only when shortcode its used
		wp_enqueue_script( 'wpfi_interview' );

		ob_start();
			$this->get_table_markup();
			$output = ob_get_contents();
	    ob_end_clean();

	    return $output;
	}

	/**
	 * Get the table markup/template
	 * @return string - echo
	 */
	public function get_table_markup() {
		include_once WPFI_PATH . 'templates/shortcode.php';
	}

	/**
	 * Load plugin language files
	 * @return void
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pods-geolocation-field',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
