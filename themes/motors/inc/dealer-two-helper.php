<?php
if(!function_exists('stm_ajax_buy_car_online')) {
	function stm_ajax_buy_car_online() {
		check_ajax_referer( 'stm_ajax_sell_online_nonce', 'security' );

		$response = array('status' => 'Error');

		$carId = intval($_POST['car_id']);
		$price = intval($_POST['price']);

		if ( !empty($carId) && !empty($price) ) {
			if ( class_exists( "WooCommerce" ) && get_theme_mod( 'enable_woo_online', false ) ) {

				update_post_meta( $carId, '_price', $price );
				update_post_meta( $carId, 'is_sell_online_status', 'in_cart' );

				$checkoutUrl = wc_get_checkout_url() . '?add-to-cart=' . $carId;

				$response = array(
					'status' => 'success',
					'redirect_url' => $checkoutUrl
				);

				wp_send_json( $response );
			}
		}

		wp_send_json($response);
	}
}
add_action( 'wp_ajax_stm_ajax_buy_car_online', 'stm_ajax_buy_car_online' );
add_action( 'wp_ajax_nopriv_stm_ajax_buy_car_online', 'stm_ajax_buy_car_online' );

function stm_dt_before_create_order ($order_id, $data) {
	$cart = WC()->cart->get_cart();

	foreach ($cart as $cart_item) {
		$id = $cart_item['product_id'];
		$post_object = get_post($cart_item['product_id']);

		if('product' === $post_object->post_type || 'car_option' === $post_object->post_type) continue;

		if(!empty(get_post_meta($id, 'is_sell_online_on_checkout', true))) {
			update_post_meta($order_id, 'order_sell_online_car_id', $id);
		}
	}

	return true;
}
add_action('woocommerce_checkout_update_order_meta', 'stm_dt_before_create_order', 200, 2);