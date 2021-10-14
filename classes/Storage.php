<?php

/**
 * Class responsable of storing data
 */
namespace WPFInterview;

class Storage {

	/**
	 * Save the payload
	 * @param  mixed $payload
	 * @return void
	 */
	public static function save( $payload ) {
		update_option( 'wpfi_payload', $payload, false );
	}

	/**
	 * Get the storage
	 * @return string
	 */
	public static function get() {
		return get_option( 'wpfi_payload' );
	}

}
