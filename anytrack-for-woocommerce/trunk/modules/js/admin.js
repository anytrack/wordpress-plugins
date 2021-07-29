jQuery(document).ready(function($){

	if( $('.qtip_picker').length > 0 ){
		$('.qtip_picker').qtip({
			content: {
				text: function(event, api) {
					// Retrieve content from custom attribute of the $('.selector') elements.
					return $(this).attr('qtip-content');
				}
			},
			style: { 
				classes: 'qtip-dark' 
			},
			position: {
				my: 'top center',  
				at: 'bottom center',  
		 
			},
			hide: {
				event: 'unfocus'
			}
		});
	}
	
	
	
});