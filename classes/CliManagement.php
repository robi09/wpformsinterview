<?php

/**
 * Class responsable of CLI commands
 */
namespace WPFInterview;

class CliManagement {
	public function __construct() {
		$this->register_commands();
	}

	public function register_commands() {
		\WP_CLI::add_command('wpfi reset', '\WPFInterview\Reset' );
	}

}
