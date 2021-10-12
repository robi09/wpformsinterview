<?php

/**
 * Class responsable of frequency updates
 */
namespace WPFInterview;

class Frequency {

	public static $frequency = '';

	/**
	 * Update the frequency time
	 * Save the timestamp in seconds
	 */
	public static function update() {
		update_option( 'wpfi_frequency_latest_update', time(), false );
	}

	public static function get() {
		get_option( 'wpfi_frequency_latest_update' );
	}

	public static function is_passed( $last_hour = null) {
		$latest_frequency = get_option( 'wpfi_frequency_latest_update' );

		if ( $latest_frequency == null ) {
			$latest_frequency = time();
		}

		return ( ( time() - intval( $latest_frequency ) ) >= ( 60 * 60 ) );
	}

}
