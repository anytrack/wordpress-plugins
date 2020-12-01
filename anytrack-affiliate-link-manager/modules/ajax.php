<?php 

// save redirect
add_action('wp_ajax_save_redirect', 'wcr_save_redirect');
add_action('wp_ajax_nopriv_save_redirect', 'wcr_save_redirect');

function wcr_save_redirect(){
	global $current_user, $wpdb;
	if( check_ajax_referer( 'ajax_call_nonce', 'security') ){
		
		$id = (int)$_POST['id'];


		wp_update_post( [
			'ID' => $id,
			'post_title' => sanitize_text_field( stripslashes( $_POST['input_redirect_name']  ) ) 
		] );

		update_post_meta( $id, 'source_url', sanitize_text_field( $_POST['input_source_url'] ) );
		update_post_meta( $id, 'query_params', sanitize_text_field( $_POST['input_query_params'] ) );
		update_post_meta( $id, 'target_url', sanitize_text_field( $_POST['input_target_url'] ) );

 
 
		echo json_encode( ['result' => 'success'] );
		
	}
	die();
}

// save redirect
add_action('wp_ajax_get_empty_row', 'wcr_get_empty_row');
add_action('wp_ajax_nopriv_get_empty_row', 'wcr_get_empty_row');

function wcr_get_empty_row(){
	global $current_user, $wpdb;
	if( check_ajax_referer( 'ajax_call_nonce', 'security') ){
		echo json_encode( ['result' => 'success', 'text' => wcr_gnerate_palceholder() ] );
	}
	die();
}
?>