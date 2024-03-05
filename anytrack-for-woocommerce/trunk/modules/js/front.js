jQuery(document).ready(function($){
	/**
	* trace checkout data
	*/
	$('body').on('change', '.woocommerce-billing-fields input, .woocommerce-billing-fields select, .woocommerce-billing-fields radio', function(){    
		var data = {
			serialized_data  : $('form.woocommerce-checkout').serialize(),
			security  : wpafw_local_data.nonce,
			action : 'checkout_action'
		}
		jQuery.ajax({url: wpafw_local_data.ajaxurl,
				type: 'POST',
				data: data,            
				success: function(msg){
								 
				}, 
				error:  function(msg) {	
				} 				         
			});
	})

});