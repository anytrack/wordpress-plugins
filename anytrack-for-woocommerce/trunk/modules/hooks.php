<?php

// script insertion
add_filter('wp_head', 'anytrack_for_woocommerce_wp_head');
function anytrack_for_woocommerce_wp_head()
{
	$ANYTRACK_ASSETS_URL = defined('ANYTRACK_ASSETS_URL') ? ANYTRACK_ASSETS_URL : 'https://assets.anytrack.io/';

	$settings = get_option('waap_options');
	$property_id = isset($settings['property_id']) ? $settings['property_id'] : '';

	if ($property_id  && $property_id  != '') {
		$asset_url = $ANYTRACK_ASSETS_URL . $property_id . '.js';

		echo '<!-- AnyTrack Tracking Code -->
		<script>!function(e,t,n,s,a){(a=t.createElement(n)).async=!0,a.src="' . esc_url($asset_url) . '",(t=t.getElementsByTagName(n)[0]).parentNode.insertBefore(a,t),e[s]=e[s]||function(){(e[s].q=e[s].q||[]).push(arguments)}}(window,document,"script","AnyTrack");</script>
		<!-- End AnyTrack Tracking Code -->';
	}
}

// hooks processing
add_action('woocommerce_add_to_cart', 'anytrack_for_woocommerce_woocommerce_add_to_cart', 10, 6);
function anytrack_for_woocommerce_woocommerce_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
{
	$settings = get_option('waap_options');
	$add_to_cart = isset($settings['add_to_cart']) ? $settings['add_to_cart'] : '';

	$product_info = anytrack_for_woocommerce_get_single_product_info($product_id, $quantity, $variation_id);
	$items = [];
	$items['items'][] = $product_info;

	anytrack_for_woocommerce_send_endpoint_data($add_to_cart, $items, 'AddToCart', 'woocommerce_add_to_cart');
}

// go to checkout - return full cart content
add_action('template_redirect', 'anytrack_for_woocommerce_template_redirect', 10);
function anytrack_for_woocommerce_template_redirect()
{
	$settings = get_option('waap_options');
	$initiate_checkout = isset($settings['initiate_checkout']) ? $settings['initiate_checkout'] : '';

	if (is_checkout() && !isset($_GET['key'])) {

		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		$cart_items = [];

		foreach ($items as $item => $values) {
			$variation_id = 0;

			$product_id = $values['data']->get_id();

			$product = wc_get_product($product_id);
			if ($product->is_type('variable')) {
				$variation_id = $product_id;
			}
			$quantity = $values['quantity'];
			$cart_items[] = anytrack_for_woocommerce_get_single_product_info($product_id, $quantity, $variation_id);
		}
		$out_items = [];
		$out_items['items'] = $cart_items;
		$out_items['total'] = $woocommerce->cart->get_cart_total();
		$order_info['currency'] = get_woocommerce_currency();

		anytrack_for_woocommerce_send_endpoint_data($initiate_checkout, $out_items, 'InitiateCheckout', 'is_checkout');
	}
}

/**
 * View single product
 */
add_action('template_redirect', 'anytrack_for_woocommerce_view_product', 10);
function anytrack_for_woocommerce_view_product()
{
	global $post;
	$settings = get_option('waap_options');
	$viewProduct = isset($settings['ViewContent']) ? $settings['ViewContent'] : '';

	if (is_product()) {

		$product_info = anytrack_for_woocommerce_get_single_product_info($post->ID);

		$items = [];
		$items['items'][] = $product_info;


		anytrack_for_woocommerce_send_endpoint_data($viewProduct, $items, 'ViewContent', 'is_product');
	}
}

// order created hooks
add_action('woocommerce_thankyou', 'anytrack_for_woocommerce_woocommerce_new_order', 10, 1);
add_action('woocommerce_payment_complete', 'anytrack_for_woocommerce_woocommerce_new_order', 10, 1);
function anytrack_for_woocommerce_woocommerce_new_order($order_id)
{
	$is_processed = get_post_meta($order_id, '_anytrack_processed', true);
	if ($is_processed == '1') {
		return false;
	}

	//$order_id = 3881;
	$order = wc_get_order($order_id);
	$settings = get_option('waap_options');
	$purchase = isset($settings['purchase']) ? $settings['purchase'] : '';

	$order_info = [];
	$order_info['ID'] = $order->get_id();
	$order_info['order_key'] = $order->get_order_key();

	$order_info['formatted_order_total'] = $order->get_formatted_order_total();
	$order_info['cart_tax'] = $order->get_cart_tax();
	$order_info['currency'] = $order->get_currency();
	$order_info['discount_tax'] = $order->get_discount_tax();
	$order_info['discount_to_display'] = $order->get_discount_to_display();
	$order_info['discount_total'] = $order->get_discount_total();
	$order_info['fees'] = $order->get_fees();
	//$order_info['formatted_line_subtotal'] = $order->get_formatted_line_subtotal();
	$order_info['shipping_tax'] = $order->get_shipping_tax();
	$order_info['shipping_total'] = $order->get_shipping_total();
	$order_info['subtotal'] = $order->get_subtotal();
	$order_info['taxes'] = $order->get_taxes();
	$order_info['total'] = $order->get_total();
	$order_info['total_discount'] = $order->get_total_discount();
	$order_info['total_tax'] = $order->get_total_tax();
	$order_info['total_refunded'] = $order->get_total_refunded();
	$order_info['total_refunded'] = $order->get_total_refunded();

	$all_inner_items = [];
	foreach ($order->get_items() as $item_id => $item) {
		$product_id = $item->get_product_id();
		$variation_id = $item->get_variation_id();
		$quantity = $item->get_quantity();
		$all_inner_items[] = anytrack_for_woocommerce_get_single_product_info($product_id, $quantity, $variation_id = 0);
	}
	$order_info['items'] = $all_inner_items;

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

	$order_info['payment_method'] = $order->get_payment_method();
	$order_info['payment_method_title'] = $order->get_payment_method_title();
	$order_info['transaction_id'] = $order->get_transaction_id();

	$order_info['status'] = $order->get_status();

	anytrack_for_woocommerce_send_endpoint_data($purchase, $order_info, 'Purchase', 'woocommerce_payment_complete');

	update_post_meta($order_id, '_anytrack_processed', '1');
}


/**
 * save cookie as order meta on creation
 */
add_action('woocommerce_new_order', function ($order_id) {
	$at_cid = isset($_COOKIE['_atcid']) ? $_COOKIE['_atcid'] : '';
	if ($at_cid && $at_cid != '') {
		update_post_meta($order_id, '_atcid', $at_cid);
	}
}, 10, 1);
