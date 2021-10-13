/**
 * Record Recombee data
 */
jQuery(document).ready(function($) {

	// // Check if we have the API details
	// if( restApi.database && restApi.public_key ) {

	// 	// Init Recombee client
	// 	var recombeeClient = new recombee.ApiClient( restApi.database, restApi.public_key );

	// 	// Record view only on product single page
	// 	if( $('body').hasClass( 'single-product' ) ) {
	// 		recombeeClient.send(new recombee.AddDetailView( restApi.customer_id, restApi.product_id ),
	// 		    (err, response) => {
	// 		    	if( err != null ) {
	// 		    		console.log('Record view. Recombee API error:');
	// 		    		console.log(err);
	// 		    	}
	// 		    }
	// 		);
	// 	}

	// 	// Record a purchase when add to cart button is clicked on multiple places
	// 	$( ".single_add_to_cart_button, .add_to_cart_button" ).on( 'click', function() {

	// 		var final_product_id = '';
	// 		var button_product_id = $(this).attr('data-product_id');
			
	// 		if(restApi.product_id) {
	// 			final_product_id = restApi.product_id;
	// 		} else {
	// 			final_product_id = button_product_id;
	// 		}

	// 		recombeeClient.send(new recombee.AddCartAddition( restApi.customer_id, final_product_id ),
	// 		    ( err, response ) => {
	// 		    	if( err != null ) {
	// 		    		console.log('Add to cart. Recombee API error:');
	// 		    		console.log(err);
	// 		    	}
	// 		    }
	// 		);
	// 	} );

	// } else {
	// 	console.log('Recombee API error: API Details are missing');
	// }
	

});