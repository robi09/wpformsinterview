/**
 * Plugin JavaScript responsible for doing the rest calls and attach the call to certain events
 */

var wpfi = {
	get_data: function() {
		
	}
}

jQuery(document).ready(function($) {

	$.ajax({
		url: wpfiRestApi.root + '/get_data',
		method: 'GET',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', wpfiRestApi.nonce)
		},
		data: {}
	}).done(function (response) {
		if( response.status === true ) {

			// Add table title
			$('.wpfi_shortcode_table .wpfi_shortcode_table_title').text( response.content.title );

			// Add table headers
			$( response.content.data.headers ).each( function( x,y ) {
				$('.wpfi_shortcode_table thead > tr').append('<th>'+y+'</th>');
			} );
			// Add table data
			$(Object.keys(response.content.data.rows)).each( function( x,y ) {
				var row_date = new Date(response.content.data.rows[y].date).toLocaleString()

				$('.wpfi_shortcode_table tbody').append('<tr>'
					+'<td>'+response.content.data.rows[y].id+'</td>'
					+'<td>'+response.content.data.rows[y].fname+'</td>'
					+'<td>'+response.content.data.rows[y].lname+'</td>'
					+'<td>'+response.content.data.rows[y].email+'</td>'
					+'<td>'+row_date+'</td>'
					+'</tr>');
			} );
		}
	});

});