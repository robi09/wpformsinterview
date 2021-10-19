<?php

/**
 * Table shortcode template
 */
if ( ! defined( 'ABSPATH' ) )
   exit; // Exit if accessed directly

?>

<div class="wpfi_shortcode_table">
	
	<table>
		<h2 class="wpfi_shortcode_table_title"></h2>
		<thead>
			<tr>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<div class="_loader" style="display: none;">
		<span class="message"><?php _e( 'Loading', 'wpfi' ); ?></span>
		<span class="error" style="display: none;">
			<?php _e( 'There has been an error, refresh the page and try again.', 'wpfi' ); ?>
		</span>
	</div>
</div>
