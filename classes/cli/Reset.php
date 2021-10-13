<?php

namespace WPFInterview;

class Reset {
	public function __construct() {}

    public function __invoke( $args ) {

    	// Reset the frequency
    	Frequency::force_reset();

        \WP_CLI::success( __( 'Endpoint time limit has been reseted!', 'wpfi' ) );
    }

}
