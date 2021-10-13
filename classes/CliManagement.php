<?php

/**
 * Class responsable of CLI commands
 */
namespace WPFInterview;

class CliManagement {

	public function register_commands() {
		if( class_exists( 'WP_CLI' ) ) {
			\WP_CLI::add_command('wpfi reset', '\WPFInterview\Reset' );
		}
	}

}
