<?php
function anytrack_for_woocommerce_send_endpoint_data($action, $data, $fixedType, $hookType)
{
	$settings = get_option('waap_options');

	$property_id = isset($settings['property_id']) ? $settings['property_id'] : '';
	$at_cid = isset($data['ID']) ? get_post_meta($data['ID'], '_atcid', true) : '';

	//check if cookie saved in order
	if (!$at_cid || $at_cid == '') {
		$at_cid = isset($_COOKIE['_atcid']) ? $_COOKIE['_atcid'] : '';
	}


	// check if values are set
	if (
		(!$property_id || $property_id == '')
		||
		(!$action || $action == '')
		||
		(!$at_cid || $at_cid == '')
	) {
		return false;
	}

	$ANYTRACK_TRACKER_ENDPOINT = defined('ANYTRACK_TRACKER_ENDPOINT') ? ANYTRACK_TRACKER_ENDPOINT : 'https://t1.anytrack.io/assets/';
	$endpoint_url = $ANYTRACK_TRACKER_ENDPOINT . esc_html($property_id) . '/collect/woocommerce';

	// prepare data archive
	$out_data = [
		'eventName' => esc_html($action),
		'cid' => esc_html($at_cid),
		'data' => $data,
		'timestamp' => microtime(true) * 1000,
		'type' => $fixedType,
		'hookType' => $hookType
	];

	$result = wp_remote_post($endpoint_url, [
		'body' => json_encode($out_data),
		'headers' => array(
			'Content-Type' => 'application/json',
		),
		'timeout' => 45,
		'blocking' => false,
	]);
	if (!is_wp_error($result)) {
		return true;
	} else {
		return false;
	}
}


function anytrack_for_woocommerce_get_single_product_info($product_id, $qty = 0, $variation_id = 0, $total_tax = 0, $subtotal = 0, $total = 0)
{

	$total_variations = '';
	if ($variation_id == 0) {
		// single product
		$product = wc_get_product($product_id);
		$all_cats = wp_get_post_terms($product_id, 'product_cat');
		$all_tags = wp_get_post_terms($product_id, 'product_tag');
		$variation_variations = null;
	} else {
		// variable product



		$product = wc_get_product($variation_id);

		$all_cats = wp_get_post_terms($product->get_parent_id(), 'product_cat');
		$all_tags = wp_get_post_terms($product->get_parent_id(), 'product_tag');
		$variation_variations = $product->get_attributes();
	}

	// prepare cats
	$all_product_cats_output = [];
	foreach ($all_cats as $s_cat) {
		$all_product_cats_output[] = ['name' => $s_cat->name, 'term_id' => $s_cat->term_id, 'slug' => 'slug'];
	}

	// prepare tags
	$all_product_tags_output = [];
	foreach ($all_tags as $s_tag) {
		$all_product_tags_output[] = ['name' => $s_tag->name, 'term_id' => $s_tag->term_id, 'slug' => 'slug'];
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
    $product_info['total_tax'] = $total_tax;
    $product_info['subtotal'] = $subtotal;
    $product_info['total'] = $total;

	return $product_info;
}


function anytrack_for_woocommerce_enrich_with_order_billing_shipping_info($order_info, $order)
{
    //billing
    $order_info['customer_id'] = $order->get_customer_id();
    $order_info['user_id'] = $order->get_user_id();
    $order_info['customer_ip_address'] = $order->get_customer_ip_address();
    $order_info['customer_user_agent'] = $order->get_customer_user_agent();
    $order_info['customer_note'] = $order->get_customer_note();
    //$order_info['address_prop'] = $order->get_address_prop();
    $order_info['billing_first_name'] = $order->get_billing_first_name();
    $order_info['billing_last_name'] = $order->get_billing_last_name();
    $order_info['billing_company'] = $order->get_billing_company();
    $order_info['billing_address_1'] = $order->get_billing_address_1();
    $order_info['billing_address_2'] = $order->get_billing_address_2();
    $order_info['billing_city'] = $order->get_billing_city();
    $order_info['billing_state'] = $order->get_billing_state();
    $order_info['billing_postcode'] = $order->get_billing_postcode();
    $order_info['billing_country'] = $order->get_billing_country();
    $order_info['billing_email'] = $order->get_billing_email();
    $order_info['billing_phone'] = $order->get_billing_phone();

    // shipping
    $order_info['shipping_first_name'] = $order->get_shipping_first_name();
    $order_info['shipping_last_name'] = $order->get_shipping_last_name();
    $order_info['shipping_company'] = $order->get_shipping_company();
    $order_info['shipping_address_1'] = $order->get_shipping_address_1();
    $order_info['shipping_address_2'] = $order->get_shipping_address_2();
    $order_info['shipping_city'] = $order->get_shipping_city();
    $order_info['shipping_state'] = $order->get_shipping_state();
    $order_info['shipping_postcode'] = $order->get_shipping_postcode();
    $order_info['shipping_country'] = $order->get_shipping_country();

    return $order_info;
}

function anytrack_for_woocommerce_enrich_with_order_basics($order_info, $order)
{
    $order_info['ID'] = $order->get_id();
    $order_info['order_key'] = $order->get_order_key();

    $order_info['payment_method'] = $order->get_payment_method();
    $order_info['payment_method_title'] = $order->get_payment_method_title();
    $order_info['transaction_id'] = $order->get_transaction_id();

    $order_info['status'] = $order->get_status();

    return $order_info;
}
