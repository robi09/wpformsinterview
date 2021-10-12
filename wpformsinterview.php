<?php

namespace WPFInterview;

/**
 * Plugin Name:       WPForms Interview
 * Plugin URI:        http://www.wpforms.com
 * Description:       Get data from endpoint and display it in a shortcode.
 * Version:           1.0.0
 * Author:            Hapiuc Robert
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpfi
 * Domain Path:       /languages
 */

define( 'WPFI_VERSION', '1.0.0' );

define( 'WPFI_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPFI_SLUG', plugin_basename( __FILE__ ) );
define( 'WPFI_URL', plugin_dir_url( __FILE__ ) );

$wpf_interview = '';
if( file_exists( WPFI_PATH . '/vendor/autoload.php') ) {

	// Include autoload file
	include_once( WPFI_PATH . '/vendor/autoload.php' );

	$wpf_interview = new Core( 'asdas' );

}

