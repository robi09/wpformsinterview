<?php

/**
 * Admin page template
 */
if ( ! defined( 'ABSPATH' ) )
   exit; // Exit if accessed directly

// Load the page content only if the WP Mail SMTP plugin its active
if ( is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php' ) ) {

	// Load page content template part
	include_once WPFI_PATH . 'templates/admin_page_content.php';
} else {

	// If plugin is not active, add instructions for the user.
	echo '
		</br>
		<div class="notice wp-mail-smtp-notice notice-info notice is-dismissible">
			<p>'. __( 'In order to view this page WP Mail SMTP plugin is required, please activate/install it here', 'wpfi' ) .':
				<a href="'. esc_url( home_url('/wp-admin/plugin-install.php?s=wp%20mail%20smtp&tab=search&type=term' ) ) .'">WP Mail SMTP</a></strong>.
			</p>
		</div>';
}

