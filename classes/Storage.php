<?php

/**
 * Class responsable of storing data
 */
namespace WPFInterview;

class Storage {

	public static function save( $payload ) {
		update_option( 'wpfi_payload', $payload, false );
	}

	public static function get() {
		return get_option( 'wpfi_payload' );
	}

}
