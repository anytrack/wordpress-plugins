jQuery(document).ready(function($){

// subtitle patch
$('.post-type-custom_redirect .wp-header-end').after( $('#redirect_pagination').html() );


$('.custom_redirect_page_aalmredirects_seetings .form-horizontal').before( $('#redirect_settings').html() );


// prevent title
$('html').on( 'click', '.post-type-custom_redirect a.row-title', function( e ){
	e.preventDefault();
})

// edit data
$('body').on( 'click', '.edit_data', function( e ){
	var id = $(this).attr('data-id');
	var parent = $(this).parents('.params_container');
	$( '.editor_block', parent).slideDown();
	$( '.cancel_data', parent).show();
	$( '.save_data', parent).show();
	$( '.edit_data', parent).hide();
})

// cancell editor
$('body').on( 'click', '.cancel_data', function( e ){
	var id = $(this).attr('data-id');
	var parent = $(this).parents('.params_container');
	$( '.editor_block', parent).slideUp();
	$( '.cancel_data', parent).hide();
	$( '.save_data', parent).hide();
	$( '.edit_data', parent).show();
 
})


// save row data
$('body').on( 'click', '.save_data', function( e ){
		var id = $(this).attr('data-id');
		var parent = $(this).parents('.params_container');
		var parent_big = $(this).parents('tr');

		$( '.editor_block', parent).slideDown();

		
  
		// verify email

		var data = {
			id  : id,
			input_redirect_name  : $('.input_redirect_name', parent).val(),
			input_source_url  : $('.input_source_url', parent).val(),
			input_query_params  : $('.input_query_params', parent).val(),
			input_target_url  : $('.input_target_url', parent).val(),
			security  : aalm_local_data.nonce,
			action : 'save_redirect'
		}
		console.log( data );
		jQuery.ajax({url: aalm_local_data.ajaxurl,
				type: 'POST',
				data: data,            
				beforeSend: function(msg){
						jQuery('body').append('<div class="big_loader"></div>');
					},
					success: function(msg){
						
						
						console.log( msg );
						
						jQuery('.big_loader').replaceWith('');
						
						var obj = jQuery.parseJSON( msg );
						console.log( obj );
						if( obj.result == 'success' ){
					 
							$('.place_source_url', parent).html( $('.input_source_url', parent).val() );
							$('.place_query_params', parent).html( $('.input_query_params', parent).find(':selected').attr('data-text') );
							$('.place_target_url', parent).html( $('.input_target_url', parent).val() );
							$('.row-title', parent_big).html( $('.input_redirect_name', parent).val() );
							
							$( '.editor_block', parent).slideUp();
							$( '.cancel_data', parent).hide();
							$( '.save_data', parent).hide();
							$( '.edit_data', parent).show();
						 
						}else{
							 
						}
						 
					} , 
					error:  function(msg) {
									
					}          
			});
	 
})

// insert row
$('html').on( 'click', '.post-type-custom_redirect a.page-title-action', function( e ){
	e.preventDefault();

 
	// verify email

	var data = {
		security  : aalm_local_data.nonce,
		action : 'get_empty_row'
	}

	jQuery.ajax({url: aalm_local_data.ajaxurl,
			type: 'POST',
			data: data,            
			beforeSend: function(msg){
					jQuery('body').append('<div class="big_loader"></div>');
				},
				success: function(msg){
					
					
					console.log( msg );
					
					jQuery('.big_loader').replaceWith('');
					
					var obj = jQuery.parseJSON( msg );
					console.log( obj );
					if( obj.result == 'success' ){
						$('.post-type-custom_redirect #the-list').prepend( obj.text );
						var pointer = $('.post-type-custom_redirect #the-list tr').first();
						$('.edit_data', pointer).click();
					}else{
						 
					}
					 
				} , 
				error:  function(msg) {
								
				}          
		});
 
}) 
	
});