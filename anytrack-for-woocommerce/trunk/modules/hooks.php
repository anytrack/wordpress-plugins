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
    $add_to_cart = isset($settings['add_to_cart']) ? $settings['add_to_cart'] : 'AddToCart';

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
    $initiate_checkout = isset($settings['initiate_checkout']) ? $settings['initiate_checkout'] : 'InitiateCheckout';

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
    $viewProduct = isset($settings['ViewContent']) ? $settings['ViewContent'] : 'ViewContent';

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
    $purchase = isset($settings['purchase']) ? $settings['purchase'] : 'Purchase';
    $fixed_type = 'Purchase';

    $is_upstroke = $order->get_created_via() == 'upstroke';
    $does_have_primary_order = get_post_meta($order_id, '_wfocu_primary_order', true);
    $is_upsell_order_created = $does_have_primary_order && $is_upstroke;
    if ($is_upsell_order_created) {
        $purchase = isset($settings['fk_upsell']) ? $settings['fk_upsell'] : '';
        $fixed_type = 'Upsell';
        if (!$purchase) {
            return false;
        }
    }

    $order_info = [];

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

    $all_inner_items = [];
    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
        $variation_id = $item->get_variation_id();
        $quantity = $item->get_quantity();
        $all_inner_items[] = anytrack_for_woocommerce_get_single_product_info($product_id, $quantity, $variation_id = 0);
    }
    $order_info['items'] = $all_inner_items;

    anytrack_for_woocommerce_enrich_with_order_basics($order_info, $order);
    anytrack_for_woocommerce_enrich_with_order_billing_shipping_info($order_info, $order);

    anytrack_for_woocommerce_send_endpoint_data($purchase, $order_info, $fixed_type, 'woocommerce_payment_complete');

    update_post_meta($order_id, '_anytrack_processed', '1');
}

// order update hook
add_action('woocommerce_update_order', 'anytrack_for_woocommerce_woocommerce_order_update', 10, 1);
function anytrack_for_woocommerce_woocommerce_order_update($order_id)
{
    // Only process if the order has been initially tracked
    $is_processed = get_post_meta($order_id, '_anytrack_processed', true);
    if ($is_processed != '1') {
        return false;
    }

    // Check if FunnelKit is used for this order
    $funnelkit_upsell_amount = get_post_meta($order_id, '_wfocu_upsell_amount', true);
    if (empty($funnelkit_upsell_amount)) {
        return false;
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        return false;
    }

    $settings = get_option('waap_options');
    $upsell = isset($settings['fk_upsell']) ? $settings['fk_upsell'] : '';

    if (!$upsell) {
        return false;
    }


    // Get previously tracked upsell item IDs from order meta
    $tracked_upsell_ids = get_post_meta($order_id, '_anytrack_tracked_upsells', true);
    if (!is_array($tracked_upsell_ids)) {
        $tracked_upsell_ids = [];
    }

    // Get current order items and filter for upsells
    $current_items = $order->get_items();
    $current_upsell_ids = [];
    $new_upsell_items = [];

    foreach ($current_items as $item_id => $item) {
        // Check if this item is an upsell by looking for _upstroke_purchase meta
        $is_upsell = $item->get_meta('_upstroke_purchase', true);

        if ($is_upsell === 'yes') {
            $current_upsell_ids[] = $item_id;

            // Check if this upsell item ID is new (not tracked before)
            if (!in_array($item_id, $tracked_upsell_ids)) {
                $product_id = $item->get_product_id();
                $variation_id = $item->get_variation_id();
                $quantity = $item->get_quantity();
                $total = $item->get_total();
                $total_tax = $item->get_total_tax();
                $subtotal = $item->get_subtotal();
                $new_upsell_items[] = anytrack_for_woocommerce_get_single_product_info($product_id, $quantity, $variation_id, $total_tax, $subtotal, $total);
            }
        }
    }

    // If we have new upsell items, track them
    if (empty($new_upsell_items)) {
        return false;
    }

    $order_info = [];

    $total = 0;
    $total_tax = 0;
    $subtotal = 0;

    foreach ($new_upsell_items as $item) {
        if ($item['total_tax']) {
            $total_tax += $item['total_tax'];
        }
        if ($item['total']) {
            $total += $item['total'];
        }
        if ($item['subtotal']) {
            $subtotal += $item['subtotal'];
        }
    }

    $order_info['items'] = $new_upsell_items;

    $order_info['formatted_order_total'] = $order->get_formatted_order_total();
    $order_info['cart_tax'] = $order->get_cart_tax();
    $order_info['currency'] = $order->get_currency();
    $order_info['discount_tax'] = $order->get_discount_tax();
    $order_info['discount_to_display'] = $order->get_discount_to_display();
    $order_info['discount_total'] = $order->get_discount_total();
    $order_info['fees'] = $order->get_fees();
    $order_info['shipping_tax'] = $order->get_shipping_tax();
    $order_info['shipping_total'] = 0;
    $order_info['subtotal'] = $subtotal;
    $order_info['taxes'] = $order->get_taxes();
    $order_info['total'] = $total;
    $order_info['total_discount'] = 0;
    $order_info['total_tax'] = $total_tax;
    $order_info['total_refunded'] = 0;

    anytrack_for_woocommerce_enrich_with_order_basics($order_info, $order);
    anytrack_for_woocommerce_enrich_with_order_billing_shipping_info($order_info, $order);

    anytrack_for_woocommerce_send_endpoint_data($upsell, $order_info, 'Upsell', 'woocommerce_update_order');

    // Update tracked upsell item IDs
    update_post_meta($order_id, '_anytrack_tracked_upsells', $current_upsell_ids);
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
