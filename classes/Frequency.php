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
	 * 
	 * @return void
	 */
	public static function update() {
		update_option( 'wpfi_frequency_latest_update', time(), false );
	}

	/**
	 * Get the frequency value
	 * @return string The frequency value
	 */
	public static function get() {
		return get_option( 'wpfi_frequency_latest_update' );
	}

	/**
	 * Force reset the frequency
	 * @return void 
	 */
	public static function force_reset() {
		update_option( 'wpfi_frequency_latest_update', null, false );
	}

	/**
	 * Check if a new request can be performed
	 * @return boolean
	 */
	public static function is_passed() {
		$latest_frequency = get_option( 'wpfi_frequency_latest_update' );

		return ( ( time() - intval( $latest_frequency ) ) >= ( 60 * 60 ) );
	}

}
