 /**
 * Plugin JavaScript responsible for doing the rest call
 */

var wpfi = {
	get_data: function() {

		// Update loader START
		jQuery('.wpfi_shortcode_table ._loader').show();

		jQuery.ajax({
			url: wpfiRestApi.root + '/get_data',
			method: 'GET',
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', wpfiRestApi.nonce )
			},
			data: {}
		}).done( function ( response ) {
			if( response.status === true ) {

				// Add table title
				jQuery( '.wpfi_shortcode_table .wpfi_shortcode_table_title' ).empty().text( response.content.title );

				// Add table headers
				jQuery( '.wpfi_shortcode_table thead > tr' ).empty();
				jQuery( response.content.data.headers ).each( function( x,y ) {
					jQuery( '.wpfi_shortcode_table thead > tr' ).append( '<th>'+y+'</th>' );
				} );

				// Add table data
				jQuery('.wpfi_shortcode_table tbody').empty();
				jQuery( Object.keys( response.content.data.rows ) ).each( function( x,y ) {
					var row_date = new Date( response.content.data.rows[y].date ).toLocaleString();

					jQuery('.wpfi_shortcode_table tbody').append('<tr>'
						+'<td>'+response.content.data.rows[y].id+'</td>'
						+'<td>'+response.content.data.rows[y].fname+'</td>'
						+'<td>'+response.content.data.rows[y].lname+'</td>'
						+'<td>'+response.content.data.rows[y].email+'</td>'
						+'<td>'+row_date+'</td>'
						+'</tr>');
				} );

				// Update loader END
				setTimeout(function(){
				   jQuery('.wpfi_shortcode_table ._loader').hide();
				}, 700);

			} else {

				// Update loader ERROR
				setTimeout(function(){
				   jQuery('.wpfi_shortcode_table ._loader .message').hide();
					jQuery('.wpfi_shortcode_table ._loader .error').show();
				}, 700);
			}
		} );
	}
}

// Reload table button event
jQuery(document).on('click', '.wpfi_table_button', function() {
	wpfi.get_data();
});
