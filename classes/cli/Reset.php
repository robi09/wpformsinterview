<?php

namespace WPFInterview;

class Reset {
	public function __construct() {}
    public function __invoke( $args ) {
        \WP_CLI::success( 'test' );
    }

}