<?php


add_action('wp_ajax_checkout_action', 'wpafw_checkout_action');
add_action('wp_ajax_nopriv_checkout_action', 'wpafw_checkout_action');

function wpafw_checkout_action()
{
	global $current_user, $wpdb;
	if (check_ajax_referer('ajax_call_nonce', 'security')) {

		$settings = get_option('waap_options');
		$add_payment_info = isset($settings['add_payment_info']) ? $settings['add_payment_info'] : '';



		$serialized = $_POST['serialized_data'];
		parse_str($serialized, $output);


		$order_info = [];

		$order_info['cart_contents_count'] = WC()->cart->get_cart_contents_count();

		$order_info['cart_subtotal'] = WC()->cart->get_cart_subtotal();

		//$order_info['subtotal_ex_tax'] = WC()->cart->get_subtotal_ex_tax();
		$order_info['subtotal'] = WC()->cart->get_subtotal();
		$order_info['displayed_subtotal'] = WC()->cart->get_displayed_subtotal();
		$order_info['total'] = WC()->cart->get_cart_total();
		$order_info['taxes_total'] = WC()->cart->get_taxes_total();
		$order_info['shipping_total'] = WC()->cart->get_shipping_total();
		$order_info['coupons'] = WC()->cart->get_coupons();
		$order_info['coupon_discount_amount'] = WC()->cart->get_coupon_discount_amount('coupon_code');
		$order_info['fees'] = WC()->cart->get_fees();
		$order_info['discount_total'] = WC()->cart->get_discount_total();

		$order_info['total'] = WC()->cart->get_total();
		$order_info['tax_totals'] = WC()->cart->get_tax_totals();
		$order_info['cart_contents_tax'] = WC()->cart->get_cart_contents_tax();
		$order_info['fee_tax'] = WC()->cart->get_fee_tax();
		$order_info['discount_tax'] = WC()->cart->get_discount_tax();
		$order_info['shipping_total'] = WC()->cart->get_shipping_total();
		$order_info['shipping_taxes'] = WC()->cart->get_shipping_taxes();


		$all_inner_items = [];
		foreach (WC()->cart->get_cart() as $item_id => $item) {
			$product_id = $item['product_id'];
			$variation_id = $item['variation_id'];
			$quantity = $item['quantity'];
			$all_inner_items[] = anytrack_for_woocommerce_get_single_product_info($product_id, $quantity, $variation_id = 0);
		}
		$order_info['items'] = $all_inner_items;

		//billing

		$order_info['customer_ip_address'] = $_SERVER["REMOTE_ADDR"];
		$order_info['customer_user_agent'] = $output['wc_order_attribution_user_agent'];
		$order_info['customer_note'] = $output['order_comments'];
		//$order_info['address_prop'] = $order->get_address_prop();
		$order_info['billing_first_name'] = $output['billing_first_name'];
		$order_info['billing_last_name'] = $output['billing_last_name'];
		$order_info['billing_company'] = $output['billing_company'];
		$order_info['billing_address_1'] = $output['billing_address_1'];
		$order_info['billing_address_2'] = $output['billing_address_2'];
		$order_info['billing_city'] = $output['billing_city'];
		$order_info['billing_state'] = $output['billing_state'];
		$order_info['billing_postcode'] = $output['billing_postcode'];
		$order_info['billing_country'] = $output['billing_country'];
		$order_info['billing_email'] = $output['billing_email'];
		$order_info['billing_phone'] = $output['billing_phone'];

		// shipping
		$order_info['shipping_first_name'] = $output['shipping_first_name'];
		$order_info['shipping_last_name'] = $output['shipping_last_name'];
		$order_info['shipping_company'] = $output['shipping_company'];
		$order_info['shipping_address_1'] = $output['shipping_address_1'];
		$order_info['shipping_address_2'] = $output['shipping_address_2'];
		$order_info['shipping_city'] = $output['shipping_city'];
		$order_info['shipping_state'] = $output['shipping_state'];
		$order_info['shipping_postcode'] = $output['shipping_postcode'];
		$order_info['shipping_country'] = $output['shipping_country'];

		$order_info['payment_method'] = $output['payment_method'];

		$isSent = anytrack_for_woocommerce_send_endpoint_data($add_payment_info, $order_info, 'AddPaymentInfo', 'woocommerce_payment_complete');
		echo json_encode(['result' => $isSent ? 'success' : 'error']);
	}
	die();
}
