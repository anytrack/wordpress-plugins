<?php 
function anytrack_for_woocommerce_send_endpoint_data( $action, $data, $fixedType, $hookType ){
	$settings = get_option('waap_options');	 
	$endpoint_url = 'https://t1.anytrack.io/assets/'.esc_html( $settings['property_id'] ).'/collect/woocommerce';
	//$endpoint_url = 'https://en7vb91n5n7uynz.m.pipedream.net';


	// prepare data archive
	$out_data = [
		'eventName' => esc_html( $action ),
		'cid' => esc_html( $_COOKIE['_atcid'] ),
		'data' => $data,
		'timestamp' => microtime( true )*1000,
		'type' => $fixedType,
		'hookType' => $hookType
	];


	// check if values are set
	if( 
		(	!$settings['property_id'] || $settings['property_id'] == '' )
		||
		( !$action || $action == '' )
		||
		( !$_COOKIE['_atcid'] || $_COOKIE['_atcid'] == '' )
	){
		return false;
	}

	$result = wp_remote_post( $endpoint_url, [
		'body' => json_encode( $out_data ),
		'headers' => array(
			'Content-Type' => 'application/json',
		),
		'timeout' => 45,
		'blocking' => false,
	]);
	if( !is_wp_error( $result ) ){
		return true;
	}else{
		return false;
	}
}


function anytrack_for_woocommerce_get_single_product_info( $product_id, $qty, $variation_id = 0 ){

	$total_variations = '';
	if( $variation_id == 0 ){
		// single product
		$product = wc_get_product( $product_id );
		$all_cats = wp_get_post_terms( $product_id, 'product_cat' );
		$all_tags = wp_get_post_terms( $product_id, 'product_tag' );
	}else{
		// variable product
	 
		

		$product = wc_get_product( $variation_id );
 
		$all_cats = wp_get_post_terms( $product->get_parent_id(), 'product_cat' );
		$all_tags = wp_get_post_terms( $product->get_parent_id(), 'product_tag' );
        $variation_variations = $product->get_attributes();  
 
	}

	// prepare cats
	$all_product_cats_output = [];
	foreach( $all_cats as $s_cat ){
		$all_product_cats_output[] = [ 'name' => $s_cat->name, 'term_id' => $s_cat->term_id, 'slug' => 'slug' ];
	}

	// prepare tags
	$all_product_tags_output = [];
	foreach( $all_tags as $s_tag ){
		$all_product_tags_output[] = [ 'name' => $s_tag->name, 'term_id' => $s_tag->term_id, 'slug' => 'slug' ];
	}


	$product_info = [];

	$product_info['ID'] = $product->get_ID();
	$product_info['title'] = $product->get_name();
	$product_info['quantity'] = $qty;
	$product_info['price'] = $product->get_price();
	$product_info['sku'] = $product->get_sku();
	$product_info['description'] = $product->get_description();
	$product_info['categories'] = $all_product_cats_output;
	$product_info['tags'] = $all_product_tags_output;
	$product_info['attributes'] = $variation_variations;
	$product_info['currency'] = get_woocommerce_currency();



	return $product_info;
}


 

?>