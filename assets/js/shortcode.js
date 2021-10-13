/**
 * Record Recombee data
 */
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

		// const keys = Object.keys(response);

		// var products = '';

		// Append products if we have a response from our API
		// if( response ) {

		// 	if( keys.length > 0 ) {

		// 		// Build products markup
		// 		for (const key of keys) {
		// 			products += 
		// 			'<li style="width: 18%; float: left; margin: 1%; list-style: none;"><a style="text-decoration: none;" href="'+response[key].permalink+'"><img src="'+response[key].image+'" />'+
		// 			response[key].name +
		// 			'</br><b style="font-size: 14px;"> '+response[key].html_price+'</b></a></li>';
		// 		}

		// 		// Append here
		// 		wcr_recommendations.append( products );
		// 	} else {
		// 		wcr_recommendations.append( '<p>The recommended products are currently unavailable.</p>' );
		// 	}
			
		// } else {
		// 	wcr_recommendations.append( '<p>The recommended products are currently unavailable.</p>' );
		// }
	});

});