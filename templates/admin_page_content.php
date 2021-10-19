<?php

/**
 * Admin page template - CONTENT
 */


if ( ! defined( 'ABSPATH' ) )
   exit; // Exit if accessed directly

global $wpf_interview;

?>

<div class="wrap" id="wp-mail-smtp">
	<div class="wp-mail-smtp-page wp-mail-smtp-page-general wp-mail-smtp-tab-settings">
		<div class="wp-mail-smtp-page-title" id="wp-mail-smtp-header">
			<a class="tab active">
				<?php _e( 'My Table', 'wpfi' ); ?>
			</a>
		</div> 
		<div class="wp-mail-smtp-page-content">
			<div id="wp-mail-smtp-pro-banner">
				<div class="wp-list-table widefat fixed striped table-view-list pages">
					<?php $wpf_interview->get_table_markup(); ?>
				</div>

				<button type="submit" class="wpfi_table_button wp-mail-smtp-btn wp-mail-smtp-btn-md wp-mail-smtp-btn-orange">
					<span><?php _e( 'Refresh table', 'wpfi' ); ?></span>
				</button>
			</div>
		</div>
	</div>
</div>
