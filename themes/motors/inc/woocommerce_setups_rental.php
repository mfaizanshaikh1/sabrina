<?php
require_once 'rental/PricePerHour.php';
require_once 'rental/PriceForDatePeriod.php';
if(get_theme_mod('enable_fixed_price_for_days', false)) {
	require_once 'rental/PriceForQuantityDays.php';
} else {
    require_once 'rental/DiscountByDays.php';
}


/**
 * Register the custom product type after init
 */
function stm_register_car_option_product_type()
{

    /**
     * This should be in its own separate file.
     */
    class WC_Product_Car_Option extends WC_Product_Simple
    {

        /**
         * Get internal type.
         * @return string
         */
        public function get_type()
        {
            return 'car_option';
        }

    }

}

add_action( 'init', 'stm_register_car_option_product_type' );

function stm_add_car_option_product( $types )
{

    // Key should be exactly the same as in the class product_type parameter
    $types['car_option'] = __( 'Car Option', 'motors' );

    return $types;

}

add_filter( 'product_type_selector', 'stm_add_car_option_product' );

/**
 * Show pricing fields for simple_rental product.
 */
function stm_car_option_custom_js()
{

    if ( 'product' != get_post_type() ) :
        return;
    endif;

    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('.options_group.pricing, .options_group ._manage_stock_field').addClass('show_if_car_option').show();
            $('.general_options.general_tab, ' +
                '.inventory_options.inventory_tab ').show();
        });
    </script>
    <?php
}

add_action( 'admin_footer', 'stm_car_option_custom_js' );

function stm_get_cart_current_total()
{
    if ( !is_admin() ) {
        return apply_filters( 'stm_rent_current_total', WC()->cart->get_total() );
    }

    return 0;
}

function stm_get_cart_items()
{
    $total_sum = stm_get_cart_current_total();
    $fields = stm_get_rental_order_fields_values();
    $cart = ( WC()->cart != null ) ? WC()->cart->get_cart() : '';
    $cart_items = array(
        'has_car' => false,
        'option_total' => 0,
        'options_list' => array(),
        'car_class' => array(),
        'options' => array(),
        'total' => $total_sum,
        'option_ids' => array(),
        'oldData' => 0
    );

    if ( !empty( $cart ) ) {

        $cartOldData = ( isset( $_GET['order_old_days'] ) && !empty( intval( $_GET["order_old_days"] ) ) ) ? $_GET['order_old_days'] : 0;
        foreach ( $cart as $cart_item ) {
            $id = stm_get_wpml_product_parent_id($cart_item['product_id']);
            $post = $cart_item['data'];

            $buy_type = ( get_class( $cart_item['data'] ) == 'WC_Product_Car_Option' ) ? 'options' : 'car_class';

            if ( $buy_type == 'options' ) {
                $cartItemQuant = $cart_item['quantity'];

                if ( $cartOldData > 0 ) {
                    if ( $cart_item['quantity'] != 1 ) {
                        $cartItemQuant = ( $cart_item['quantity'] / $cartOldData );
                    } else {
                        $cartItemQuant = 1;
                    }
                }

                $priceStr = $cart_item['data']->get_data();

                $total = $cartItemQuant * $priceStr['price'];
                $cart_items['option_total'] += $total;
                $cart_items['option_ids'][] = $id;

                $cart_items[$buy_type][] = array(
                    'id' => $id,
                    'quantity' => $cartItemQuant,
                    'name' => $post->get_title(),
                    'price' => $priceStr['price'],
                    'total' => $total,
                    'opt_days' => $fields['ceil_days'],
                    'subname' => get_post_meta( $id, 'cars_info', true ),
                );

                $cart_items['options_list'][$id] = $post->get_title();
                $cart_items['option_quantity'][$id] = $cartItemQuant;
            } else {

                $variation_id = 0;
                if ( !empty( $cart_item['variation_id'] ) ) {
                    $variation_id = stm_get_wpml_product_parent_id($cart_item['variation_id']);
                }

                if ( isset( $_GET['pickup_location'] ) ) {
                    $pickUpLocationMeta = get_post_meta( $id, 'stm_rental_office' );
                    //if($_GET['pickup_location'] != $pickUpLocationMeta[0]) {
                    if ( !in_array( $_GET['pickup_location'], explode( ',', $pickUpLocationMeta[0] ) ) ) {
                        WC()->cart->empty_cart();
                    }
                }

                $priceStr = $cart_item['data']->get_data();

                $cart_items[$buy_type][] = array(
                    'id' => $id,
                    'variation_id' => $variation_id,
                    'quantity' => $cart_item['quantity'],
                    'name' => $post->get_title(),
                    'price' => $priceStr['price'],
                    'total' => $fields['order_days'] * $priceStr['price'],
                    'subname' => get_post_meta( $id, 'cars_info', true ),
                    'payment_method' => get_post_meta( $variation_id, '_stm_payment_method', true ),
                    'days' => $fields['order_days'],
                    'ceil_days' => $fields['ceil_days'],
                    'oldData' => $cartOldData
                );

                $cart_items['has_car'] = true;
            }
        }

        /*Get only last element*/
        if ( count( $cart_items['car_class'] ) > 1 ) {
            $rent = array_pop( $cart_items['car_class'] );
            $cart_items['delete_items'] = $cart_items['car_class'];
            $cart_items['car_class'] = $rent;
        } else {
            if ( !empty( $cart_items['car_class'] ) ) {
                $cart_items['car_class'] = $cart_items['car_class'][0];
            }
        }
    }

    return apply_filters( 'stm_cart_items_content', $cart_items );
}

/*Remove last car everytime another one added*/
add_action( 'template_redirect', 'stm_rental_remove_car_from_cart' );

function stm_rental_remove_car_from_cart()
{
    /*This code is only for car reservation. Redirect on date reservation if not selected yet. BEGIN*/
    $rental_datepick = get_theme_mod( 'rental_datepick', false );
    if ( !empty( $rental_datepick ) and is_checkout() and !stm_check_rental_date_validation() ) {
        wp_redirect( get_permalink( $rental_datepick ) );
        exit;
    }

    /*This code is only for car reservation. Redirect on date reservation if not selected yet. END*/
    $items = stm_get_cart_items();
    $ids = array();

    if ( !empty( $_GET['remove-from-cart'] ) ) {
        $items['delete_items'][] = array(
            'id' => intval( $_GET['remove-from-cart'] )
        );
    }

    if ( !empty( $items['delete_items'] ) ) {
        foreach ( $items['delete_items'] as $product ) {
            $ids[] = $product['id'];
        }

        $WC = WC();

        foreach ( $WC->cart->get_cart() as $cart_item_key => $cart_item ) {
            // Get the Variation or Product ID
            $prod_id = $cart_item['product_id'];

            // Check to see if IDs match
            if ( in_array( $prod_id, $ids ) ) {
                $WC->cart->set_quantity( $cart_item_key, 0, true );
                break;
            }
        }
    }
}

/*Add quantity equal to days*/
add_action( 'template_redirect', 'stm_rental_add_quantity_to_cart' );

function stm_rental_add_quantity_to_cart()
{
    $items = stm_get_cart_items();
    $items = $items['car_class'];

    if ( !empty( $items ) ) {
        $id = $items['id'];
        $days = 1;
        if ( !empty( $items['ceil_days'] ) ) {
            $days = $items['ceil_days'];
        }

        $WC = WC();

        $cart = $WC->cart->get_cart();

        $keys = array_keys( $cart );
        for ( $q = 0; $q < count( $keys ); $q++ ) {

            $quant = $cart[$keys[$q]]['quantity'];
            if ( ( !empty( $_GET['add-to-cart'] ) && $_GET['add-to-cart'] == $cart[$keys[$q]]['product_id'] || isset( $items['oldData'] ) && $items['oldData'] > 0 ) && $cart[$keys[$q]]['data']->get_type() != 'variation' ) {
                if ( $items['oldData'] > 0 ) {
                    $quant = ( $cart[$keys[$q]]['quantity'] / $items['oldData'] ) * $days;
                } else {
                    $quant = $cart[$keys[$q]]['quantity'] * $days;
                }

                unset( $_GET['order_old_days'] );
            }

            if ( $cart[$keys[$q]]['data']->get_type() == 'car_option' ) {
                $singlePay = get_post_meta( $cart[$keys[$q]]['data']->get_ID(), '_car_option', true );
                if ( !empty( $singlePay ) ) $quant = $cart[$keys[$q]]['quantity'];
            }

            if ( $cart[$keys[$q]]['data']->get_type() == 'variation' || $cart[$keys[$q]]['data']->get_type() == 'simple' ) {
                $quant = 1;
            }

            $WC->cart->set_quantity( $keys[$q], $quant, true );
        }
    }
}


//Remove Car Options from main shop
function stm_remove_car_options_from_query( $query )
{
    if ( !is_admin() and $query->is_main_query() ) {

        $tax_query = array( array( 'taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'car_option', 'operator' => 'NOT IN' ) );
        $query->set( 'tax_query', $tax_query );

		if(stm_is_rental_two()) {
		    if(!empty($_GET)) {
                $tax_query = array(
					'relation' => 'AND',
                    array( 'taxonomy' => 'product_type', 'field' => 'slug', 'terms' => 'car_option', 'operator' => 'NOT IN' )
                );

		        foreach ($_GET as $k => $val) {
		            if(strpos($k, 'filter_') !== false) {
						$tax_query[count( $tax_query )] = array( 'taxonomy' => str_replace('filter_', 'pa_', $k), 'field' => 'slug', 'terms' => $val );
					}
                }

			    $query->set( 'tax_query', $tax_query );
            }
        }
    }

    $pl = "stm_pickup_location_" . get_current_blog_id();

    if ( !is_admin() and $query->is_main_query() && isset( $_GET["pickup_location"] ) && !stm_is_checkout( $query ) || !is_admin() and $query->is_main_query() && !empty( $_COOKIE[$pl] ) && !stm_is_checkout( $query ) && stm_is_shop( $query ) ) {
        $location_id = ( isset( $_GET['pickup_location'] ) ) ? $_GET['pickup_location'] : intval( $_COOKIE[$pl] );

        $meta_query = array(
            array(
                'key' => 'stm_rental_office',
                'value' => stm_get_wpml_office_parent_id($location_id),
                'compare' => 'LIKE'
            )
        );

        $query->set( 'meta_query', $meta_query );
    }

    return $query;
}

add_action( 'pre_get_posts', 'stm_remove_car_options_from_query' );

function stm_is_shop( $query )
{
    $front_page_id = get_option( 'page_on_front' );
    $current_page_id = $query->get( 'page_id' );
    $shop_page_id = apply_filters( 'woocommerce_get_shop_page_id', get_option( 'woocommerce_shop_page_id' ) );
    $is_static_front_page = 'page' == get_option( 'show_on_front' );

    if ( $is_static_front_page && $front_page_id == $current_page_id ) {
        $is_shop_page = ( $current_page_id == $shop_page_id ) ? true : false;
    } else {
        $is_shop_page = is_shop();
    }

    return $is_shop_page;
}

function stm_is_checkout( $query )
{
    $front_page_id = get_option( 'page_on_front' );
    $current_page_id = $query->get( 'page_id' );
    $checkout_page_id = apply_filters( 'woocommerce_checkout_page_id', get_option( 'woocommerce_checkout_page_id' ) );
    $is_static_front_page = 'page' == get_option( 'show_on_front' );

    if ( $is_static_front_page && $front_page_id == $current_page_id ) {
        $is_checkout_page = ( $current_page_id == $checkout_page_id ) ? true : false;
    } else {
        $is_checkout_page = is_checkout();
    }

    return $is_checkout_page;
}

function stm_get_empty_placeholder( $empty = false )
{
    $symbol = '--';
    if ( $empty ) {
        $symbol = '';
    }
    return apply_filters( 'stm_get_empty_placeholder', $symbol );
}

/*Checkout fields styling*/
add_filter( 'woocommerce_checkout_fields', 'stm_custom_override_checkout_fields');

// Our hooked in function - $fields is passed via the filter!
function stm_custom_override_checkout_fields( $fields )
{
    unset( $fields['billing']['billing_address_2'] );

    $billing = $fields['billing'];
    $spliced = array_splice( $billing, 0, 2 );

    $spliced['billing_driver_license'] = array(
        'label' => esc_html__( 'Driver license', 'motors' ),
        'required' => false,
        'class' => array( 'form-row-first' ),
        'autocomplete' => 'driver_license',
        'priority' => '25'
    );

    $fields['billing'] = array_merge( $spliced, $billing );

    $unvalidated_fields = array(
        'first_name',
        'last_name',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone',
        //'billing_company'
    );

    if ( !empty( $fields['billing']['billing_company'] ) ) {
        $fields['billing']['billing_company']['class'] = array(
            'form-row-last'
        );
    }

    unset( $fields['billing']['billing_postcode'] );

    foreach ( $fields['billing'] as $key => $field ) {

        $field['label_class'] = 'heading-font';
        if ( !in_array( $key, $unvalidated_fields ) ) {
            $fields['billing'][$key]['required'] = false;
        } else {
            $fields['billing'][$key]['required'] = true;
        }
    }

    if ( !empty( $fields['billing']['billing_state'] ) ) {
        $fields['billing']['billing_state']['class'] = array(
            'address-field'
        );
    }

    return $fields;
}

add_filter( 'woocommerce_default_address_fields', 'stm_custom_override_default_address_fields' );

function stm_custom_override_default_address_fields( $fields )
{
    $unvalidated_fields = array(
        'first_name',
        'last_name',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        //'billing_company'
    );
    foreach ( $fields as $key => $field ) {
        if ( !in_array( $key, $unvalidated_fields ) ) {
            $fields[$key]['required'] = false;
        } else {
            $fields[$key]['required'] = true;
        }
    }

    return $fields;
}

add_filter( 'woocommerce_form_field_args', 'stm_fields_checkout_args' );

function stm_fields_checkout_args( $args )
{
    $args['label_class'] = 'heading-font';
    return $args;
}

//Add Woocommerce variation payment gateways
/**
 * Create new fields for variations
 *
 */
function stm_variation_settings_fields( $loop, $variation_data, $variation )
{

    $payment_gateways = array();
    $available_methods = WC()->payment_gateways;
    if ( !empty( $available_methods->payment_gateways ) ) {
        foreach ( $available_methods->payment_gateways as $payment_gateway ) {
            $payment_gateways[$payment_gateway->id] = $payment_gateway->title;
        }
    }

    // Select
    woocommerce_wp_select(
        array(
            'wrapper_class' => 'stm-custom-select',
            'id' => '_stm_payment_method[' . $variation->ID . ']',
            'label' => __( 'Availble payment method', 'motors' ),
            'description' => __( 'Choose payment method available only for this variable product. If this product will be in cart, all other payment methods will be disabled on checkout page.', 'motors' ),
            'value' => get_post_meta( $variation->ID, '_stm_payment_method', true ),
            'options' => $payment_gateways
        )
    );
}

add_action( 'woocommerce_product_after_variable_attributes', 'stm_variation_settings_fields', 10, 3 );

//Save Woocommerce variation payment gateway
add_action( 'woocommerce_save_product_variation', 'stm_save_variation_settings_fields', 10, 2 );

function stm_save_variation_settings_fields( $post_id )
{

    $select = sanitize_text_field($_POST['_stm_payment_method'][$post_id]);

    if ( !empty( $select ) ) {
        update_post_meta( $post_id, '_stm_payment_method', esc_attr( $select ) );
    }
}

add_filter( 'woocommerce_available_payment_gateways', 'stm_filter_gateways', 1 );
function stm_filter_gateways( $gateways )
{
    if ( is_admin() ) return $gateways;
    $gateway = array();
    $cart_items = stm_get_cart_items();
    if ( !empty( $cart_items['car_class'] ) and !empty( $cart_items['car_class']['payment_method'] ) ) {
        $payment_method = $cart_items['car_class']['payment_method'];
        if ( !empty( $gateways[$payment_method] ) ) {
            $gateway[$payment_method] = $gateways[$payment_method];
        }
    }

    if ( !empty( $gateway ) ) {
        $gateways = $gateway;
    }

    if ( count( $gateways ) == 1 ): ?>
        <script>
            jQuery(document).ready(function () {
                jQuery('.stm_rental_payment_methods').addClass('stm_single_method_available');
            })
        </script>
    <?php endif;

    return $gateways;
}


function stm_rental_total_order_info()
{
    $fields = stm_get_rental_order_fields_values();
    $items = stm_get_cart_items();
    $billing_fields = stm_rental_billing_info();

    $order_info = array(
        'pickup' => array(
            'title' => esc_html__( 'Pick Up', 'motors' ),
            'content' => ''
        ),
        'dropoff' => array(
            'title' => esc_html__( 'Drop off', 'motors' ),
            'content' => ''
        ),
        'vehicle' => array(
            'title' => esc_html__( 'Vehicle Type', 'motors' ),
            'content' => ''
        ),
        'addons' => array(
            'title' => esc_html__( 'Add-ons', 'motors' ),
            'content' => ''
        ),
        'info' => array(
            'title' => esc_html__( 'Your Information', 'motors' ),
            'content' => ''
        ),
        'payment' => array(
            'title' => esc_html__( 'Payment information', 'motors' ),
            'content' => ''
        )
    );

    if ( !empty( $fields['pickup_location'] ) ) {
        $order_info['pickup']['content'] = $fields['pickup_location'] . ' ';
    }

    if ( !empty( $fields['pickup_date'] ) ) {
        $order_info['pickup']['content'] .= $fields['pickup_date'];
    }

    if ( !empty( $fields['return_location'] ) ) {
        $order_info['dropoff']['content'] = $fields['return_location'] . ' ';
    }

    if ( !empty( $fields['return_date'] ) ) {
        $order_info['dropoff']['content'] .= $fields['return_date'];
    }

    if ( !empty( $items['car_class']['name'] ) ) {
        $order_info['vehicle']['content'] = $items['car_class']['name'] . ' ';
    }

    if ( !empty( $items['car_class']['subname'] ) ) {
        $order_info['vehicle']['content'] .= $items['car_class']['subname'];
    }

    if ( !empty( $items['options_list'] ) ) {
        $order_info['addons']['content'] = implode( ', ', $items['options_list'] );
    }

    if ( !empty( $billing_fields['first_name'] ) and !empty( $billing_fields['last_name'] ) ) {
        $order_info['info']['content'] = $billing_fields['first_name'] . ' ' . $billing_fields['last_name'];
    }

    if ( !empty( $billing_fields['total'] ) ) {
        $order_info['payment']['content'] = sprintf( __( 'Estimated Total - %s', 'motors' ), $billing_fields['total'] );
    }

    return apply_filters( 'stm_rental_order_info', $order_info );
}

function stm_rental_total_order_info_rental_two()
{
    $fields = stm_get_rental_order_fields_values();
    $items = stm_get_cart_items();
    $product = new WC_Product($items['car_class']['id']);
    $prodAtts = stm_mcr_get_product_atts( $product );

    $order_info = array();

    $order_info['pickup_location'] = $fields['pickup_location'];
    $order_info['pickup_date'] = $fields['pickup_date'];
    $order_info['dropoff'] = $fields['return_location'];
    $order_info['dropoff_date'] = $fields['return_date'];
    $order_info['vehicle_name'] = $items['car_class']['name'];
    $order_info['vehicle_img'] = wp_get_attachment_image_url(get_post_thumbnail_id($items['car_class']['id']), 'stm-img-350-181');
    $order_info['vehicle_atts'] = $prodAtts;

    if ( !empty( $items['options_list'] ) ) {
        $order_info['addons'] = implode( ', ', $items['options_list'] );
    }

    return apply_filters( 'stm_rental_total_order_info_rental_two', $order_info );
}

function stm_rental_order_item_info_by_id_rental_two($order_id, $prod_id)
{
    $product = new WC_Product($prod_id);
    $prodAtts = stm_mcr_get_product_atts( $product );

    $order_info = array();

	$date1 = new DateTime( get_post_meta($order_id, 'order_pickup_date', true) );
	$date2 = new DateTime( get_post_meta($order_id, 'order_drop_date', true) );

	$diff = $date2->diff( $date1 )->format( "%a.%h" );

	$hm = explode('.', $diff);
	if ( $hm[0] === '0' ) {
		$diff = 0;
	}

    $order_info['pickup_location'] = get_post_meta($order_id, 'order_pickup_location', true);
    $order_info['pickup_date'] = get_post_meta($order_id, 'order_pickup_date', true);
    $order_info['dropoff'] = get_post_meta($order_id, 'order_drop_location', true);
    $order_info['dropoff_date'] = get_post_meta($order_id, 'order_drop_date', true);
    $order_info['vehicle_img'] = wp_get_attachment_image_url(get_post_thumbnail_id($prod_id), 'stm-img-350-181');
    $order_info['vehicle_name'] = $product->get_name();
    $order_info['vehicle_atts'] = $prodAtts;
    $order_info['order_days'] = $diff;

    if ( !empty( $items['options_list'] ) ) {
        $order_info['addons'] = implode( ', ', $items['options_list'] );
    }

    return apply_filters( 'stm_rental_total_order_info_rental_two', $order_info );
}

function stm_get_order_id()
{
    $order_id = false;

    $orderReceived = get_option( 'woocommerce_checkout_order_received_endpoint', 'order-received' );
    $viewOrder = get_option( 'woocommerce_myaccount_view_order_endpoint', 'view-order' );

    if ( isset( $_GET[$viewOrder] ) ) {
        $order_id = intval($_GET[$viewOrder]);
    } else if ( isset( $_GET[$orderReceived] ) ) {
        $order_id = intval($_GET[$orderReceived]);
    } else {
        $url = apply_filters('stm_get_global_server_val', 'SERVER_NAME') . apply_filters('stm_get_global_server_val', 'REQUEST_URI');

        if ( strpos( $url, '/' . $orderReceived . '/' ) !== false ) {
            $start = strpos( $url, '/' . $orderReceived . '/' );
            $first_part = substr( $url, $start + strlen( '/' . $orderReceived . '/' ) );
            $order_id = substr( $first_part, 0, strpos( $first_part, '/' ) );
        }
    }

    return $order_id;
}

/*Update transient of user after new order*/
function stm_rental_billing_info()
{
    $bill = array(
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'payment' => '',
        'total' => ''
    );

    $order_id = stm_get_order_id();

    if ( !is_user_logged_in() ) {

        if ( $first_name = get_post_meta( $order_id, '_billing_first_name', true ) ) {
            $bill['first_name'] = $first_name;
        }

        if ( $last_name = get_post_meta( $order_id, '_billing_last_name', true ) ) {
            $bill['last_name'] = $last_name;
        }
    } else {
        $id = get_current_user_id();
        $name = get_user_meta( $id, 'billing_first_name', true );
        $last_name = get_user_meta( $id, 'billing_last_name', true );

        if ( !empty( $name ) and !empty( $last_name ) ) {
            $bill['first_name'] = $name;
            $bill['last_name'] = $last_name;
        }
    }

    $order = wc_get_order( $order_id );

    if ( !empty( $order ) ) {
        if ( preg_replace( '/\D/', '', $order ) ) {
            $bill['total'] = wc_price( $order->get_total() );
        }
    }

    if ( $payment = get_post_meta( $order_id, '_payment_method_title', true ) ) {
        $bill['payment'] = $payment;
    }

    return apply_filters( 'stm_billing_rental_info', $bill );
}

//change order info when on order page
add_action( 'stm_cart_items_content', 'stm_order_page_information_rental' );

function stm_order_page_information_rental( $info )
{
    $order_id = stm_get_order_id();
    if ( !empty( $order_id ) ) {
        $info_car = get_post_meta( $order_id, 'order_car', true );
        if ( !empty( $info_car['car_class'] ) ) {
            $info = $info_car;
        }
    }

    return $info;
}

add_action( 'stm_rental_date_values', 'stm_order_page_date_rental' );

function stm_order_page_date_rental( $date )
{
    $order_id = stm_get_order_id();
    if ( !empty( $order_id ) ) {
        $date_car = get_post_meta( $order_id, 'order_car_date', true );
        if ( !empty( $date_car ) ) {
            $date = $date_car;
        }
    }
    return $date;
}

/*Taxes*/
function stm_rental_order_taxes()
{
    $taxes = array();
    $order_id = stm_get_order_id();
    if ( !empty( $order_id ) ) {
        $order = wc_get_order( $order_id );
        $order_taxes = $order->get_taxes();
        foreach ( $order_taxes as $order_tax ) {
            $taxes[$order_tax['label']] = array(
                'label' => $order_tax['label'],
                'value' => wc_price( $order_tax['tax_amount'] ),
            );
        }
    } else {
        $cart = WC()->cart->get_tax_totals();
        foreach ( $cart as $name => $cart_item ) {
            $taxes[$name] = array(
                'label' => $cart_item->label,
                'value' => $cart_item->formatted_amount,
            );
        }
    }

	return apply_filters( 'stm_rental_order_taxes', $taxes );
}

add_action( 'woocommerce_new_order', 'stm_order_fields' );
function stm_order_fields( $order_id )
{

    if ( is_admin() ) {
        return false;
    }
    $cart_items = stm_get_cart_items();
    $date = stm_get_rental_order_fields_values();
    update_post_meta( $order_id, 'order_car', $cart_items );
    update_post_meta( $order_id, 'order_car_date', $date );

    update_post_meta( $order_id, 'order_pickup_date', $date['pickup_date'] );
    update_post_meta( $order_id, 'order_pickup_location', $date['pickup_location'] );
    update_post_meta( $order_id, 'order_drop_date', $date['return_date'] );
    update_post_meta( $order_id, 'order_drop_location', $date['return_location'] );
}

/*Remove notice when adding item to cart*/
add_filter( 'wc_add_to_cart_message_html', '__return_empty_string' );

function stm_get_car_rent_info( $id )
{
    $car_info_points = stm_get_car_listings();
    $car_info = array();
    if ( !empty( $car_info_points ) ) {
        foreach ( $car_info_points as $car_info_point ) {
            $meta = get_post_meta( $id, $car_info_point['slug'], true );
            if ( !empty( $meta ) ) {
                $slug = $car_info_point['slug'];
                $car_info[$slug] = array(
                    'name' => $car_info_point['plural_name'],
                    'value' => $meta,
                    'font' => '',
                    'numeric' => false
                );

                if ( !empty( $car_info_point['numeric'] ) and $car_info_point['numeric'] ) {
                    $car_info[$slug]['numeric'] = true;
                }

                if ( !empty( $car_info_point['font'] ) ) {
                    $car_info[$slug]['font'] = $car_info_point['font'];
                }
            }
        }
    }
    return apply_filters( 'stm_car_rent_info', $car_info );
}

function stm_rental_order_fileds()
{
    $blog_id = get_current_blog_id();
    $rents = array( 'pickup_location', 'pickup_date', 'return_date', 'drop_location', 'return_same', 'calc_pickup_date', 'calc_return_date' );
    foreach ( $rents as $i => $rent ) {
        $rents[$i] = 'stm_' . $rent . '_' . $blog_id;
    }
    return $rents;
}

function stm_check_rental_date_validation()
{
    $r = true;

    $fields = stm_get_rental_order_fields_values();

    if ( $fields['pickup_location'] == stm_get_empty_placeholder() or $fields['pickup_date'] == stm_get_empty_placeholder() or $fields['return_date'] == stm_get_empty_placeholder() ) {
        $r = false;
    }

    return $r;
}

function stm_get_rental_order_fields_values( $empty = false )
{
    $values = array(
        'pickup_location_id' => '',
        'pickup_location' => '',
        'pickup_date' => '',
        'calc_pickup_date' => '',
        'return_date' => '',
        'calc_return_date' => '',
        'return_location_id' => '',
        'return_location' => '',
        'return_same' => '',
        'order_days' => '',
        'order_hours' => '',
        'ceil_days' => '',
    );
    $fields = stm_rental_order_fileds();

    $pickup_location = !empty( $_COOKIE[$fields[0]] ) ? intval( $_COOKIE[$fields[0]] ) : false;
    $pickup_date = !empty( $_COOKIE[$fields[1]] ) ? urldecode( $_COOKIE[$fields[1]] ) : false;
    $return_date = !empty( $_COOKIE[$fields[2]] ) ? urldecode( $_COOKIE[$fields[2]] ) : false;
    $return_location = !empty( $_COOKIE[$fields[3]] ) ? intval( $_COOKIE[$fields[3]] ) : false;
    $return_same = !empty( $_COOKIE[$fields[4]] ) ? sanitize_text_field( $_COOKIE[$fields[4]] ) : 'on';
    $pickup_date_calc = !empty( $_COOKIE[$fields[5]] ) ? urldecode( $_COOKIE[$fields[5]] ) : false;
    $return_date_calc = !empty( $_COOKIE[$fields[6]] ) ? urldecode( $_COOKIE[$fields[6]] ) : false;

    /*Pickup Location*/
    if ( $pickup_location ) {
        $values['pickup_location_id'] = $pickup_location;
        $values['pickup_location'] = get_post_meta( $pickup_location, 'address', true );
    } else {
        $values['pickup_location'] = stm_get_empty_placeholder( $empty );
    }

    /*Pickup date*/
    if ( $pickup_date ) {
        $values['pickup_date'] = $pickup_date;
        $values['calc_pickup_date'] = $pickup_date_calc;
    } else {
        $values['pickup_date'] = stm_get_empty_placeholder( $empty );
        $values['calc_pickup_date'] = stm_get_empty_placeholder( $empty );
    }

    /*Return date*/
    if ( $return_date ) {
        $values['return_date'] = $return_date;
        $values['calc_return_date'] = $return_date_calc;
    } else {
        $values['return_date'] = stm_get_empty_placeholder( $empty );
        $values['calc_return_date'] = stm_get_empty_placeholder( $empty );
    }

    /*Drop Location*/
    if(!stm_is_rental_two()) {
		if ( $return_same == 'on' ) {
			$values['return_location'] = $values['pickup_location'];
		} else {
			if ( !empty( $return_location ) ) {
				$values['return_location_id'] = $return_location;
				$values['return_location'] = get_post_meta( $return_location, 'address', true );
			} else {
				$values['return_location'] = $values['pickup_location'];
			}
		}
	} else {
		if ( $return_same == 'on' ) {
			if ( !empty( $return_location ) ) {
				$values['return_location_id'] = $return_location;
				$values['return_location'] = get_post_meta( $return_location, 'address', true );
			} else {
				$values['return_location'] = $values['pickup_location'];
			}
		} else {
			$values['return_location'] = $values['pickup_location'];
		}
    }

    $values['return_same'] = $return_same;

    if ( $values['calc_return_date'] != stm_get_empty_placeholder() and $values['calc_pickup_date'] != stm_get_empty_placeholder() ) {
        $date1 = new DateTime( $values['calc_pickup_date'] );
        $date2 = new DateTime( $values['calc_return_date'] );

        $diff = $date2->diff( $date1 )->format( "%a.%h" );

        $hm = explode('.', $diff);
        if ( $hm[0] === '0' ) {
            $diff = 0;
        }

        $values['ceil_days'] = ceil( $diff );
        $values['order_days'] = ceil( $diff );
    } else {
        $values['order_days'] = 1;
    }

    $values = apply_filters( 'stm_rental_date_values', $values );

    return $values;
}

/*Rental locations*/
function stm_rental_locations( $full = false )
{
    $args = array(
        'post_type' => 'stm_office',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    //$args = array_merge($args, stm_get_wpml_query_default_attr());

    $offices = new WP_Query( $args );
    $locations = array();
    $i = 0;

    if ( $offices->have_posts() ):
        while ( $offices->have_posts() ): $offices->the_post();
            $id = get_the_ID();
            $lat = get_post_meta( $id, 'latitude', true );
            $lng = get_post_meta( $id, 'longitude', true );
            $phone = get_post_meta( $id, 'phone', true );
            $fax = get_post_meta( $id, 'fax', true );
            $address = get_post_meta( $id, 'address', true );
            $content = '';

            if ( !empty( $lng ) and !empty( $lat ) ) {
                $google_api_key = get_theme_mod( 'google_api_key', '' );
                $class = '';
                if ( !empty( $google_api_key ) ) {
                    $class = 'with-map';
                }

                $content = "<div class='stm_offices_wrapper " . esc_attr( $class ) . "'>";
                $content .= "<div class='location heading-font'>" . get_the_title() . "</div>";
                if ( !empty( $address ) ) {
                    $content .= "<div class='address'><i class='stm-icon-pin'></i>" . $address . "</div>";
                }
                if ( !empty( $phone ) or !empty( $fax ) ) {
                    $content .= "<div class='phone_fax'><i class='stm-icon-phone'></i> ";
                }
                if ( !empty( $phone ) ) {
                    $content .= "<div class='phone'>" . esc_html__( "Phone:", "motors" ) . " " . $phone . "</div>";
                }
                if ( !empty( $fax ) ) {
                    $content .= "<div class='fax'>" . esc_html__( "Fax:", "motors" ) . " " . $fax . "</div>";
                }
                if ( !empty( $phone ) or !empty( $fax ) ) {
                    $content .= "</div>";
                }

                if ( $full ) {
                    $hours = get_post_meta( $id, "work_hours", true );
                    if ( !empty( $hours ) ) {
                        $content .= "<div class='stm_work_hours'><i class='stm-icon-time'></i>";
                        $content .= $hours;
                        $content .= "</div>";
                    }
                    if ( !empty( $google_api_key ) and !empty( $lng ) and !empty( $lat ) ) {
                        $g_map_url = 'https://maps.googleapis.com/maps/api/staticmap?zoom=13&size=253x253&markers=color:red%7Clabel:C%7C' . $lat . ',' . $lng . '&key=' . $google_api_key;
                        $g_map_full_url = 'https://www.google.com/maps/place/' . $lat . ',' . $lng;
                        $content .= "<a href='" . $g_map_full_url . "' target='_blank'><img src='" . $g_map_url . "' /></a>";
                    }
                }

                $content .= "</div>";

                $locations[] = array(
                    $content,
                    $lat,
                    $lng,
                    $i,
                    (!empty(stm_motors_wpml_binding($id, 'product'))) ? get_the_title(stm_motors_wpml_binding($id, 'product')) : get_the_title(),
                    get_the_ID()
                );

                $i++;
            }

        endwhile;

        wp_reset_postdata();
    endif;

    return $locations;

}

function stm_admin_add_offices_to_car( $manager )
{
    /*Offices*/
    $locations = stm_rental_locations( true );

    if ( count( $locations ) > 0 ) {
        $officesArray = array();
        /*Add multiselects*/
        foreach ( $locations as $key => $option ) {
            $officesArray[stm_get_wpml_office_parent_id($option[5])] = $option[4];
        }

        $manager->register_control(
            "stm_rental_office",
            array(
                'type' => 'multiselect',
                'section' => 'stm_info',
                'label' => "Offices",
                'choices' => $officesArray
            )
        );

        $manager->register_setting(
            "stm_rental_office",
            array(
                'sanitize_callback' => 'stm_listings_multiselect',
            )
        );
    }
}

add_action( "stm_add_rental_offices", "stm_admin_add_offices_to_car" );

function stm_remove_get_params()
{
    wp_add_inline_script( 'stm-theme-scripts', '
    jQuery(document).ready(function(){
        window.history.pushState("", "", "' . remove_query_arg( "order_old_days" ) . '");
    });
    ' );
}

if ( isset( $_GET["order_old_days"] ) ) {
    add_action( 'wp_enqueue_scripts', 'stm_remove_get_params' );
}

function stm_createUnavailableCarListForOrder( $carIdList, $startDate, $endDate )
{
    $carsUnavailable = array();
    foreach ( $carIdList as $carId ) {
        $carsQty = get_post_meta( $carId, 'cars_qty', true );
        if ( !empty( $carsQty ) ) {
            if ( count( stm_checkOrderAvailable( $carId, $startDate, $endDate ) ) > 0 ) {
                array_push( $carsUnavailable, $carId );
            }
        }
    }

    return $carsUnavailable;
}

function stm_getDateRange( $date1, $date2 )
{
    $datetime1 = new DateTime( $date1 );
    $datetime2 = new DateTime( $date2 );
    $interval = $datetime1->diff( $datetime2 );

    $days = (int)$interval->format( '%a' );
    $dateRangeArr = array( date( 'Y-m-d', strtotime( 'now', $datetime1->getTimestamp() ) ) );

    $lastGenerateDate = '';
    for ( $q = 1; $q <= $days; $q++ ) {
        $lastGenerateDate = date( 'Y-m-d', strtotime( '+1 day', $datetime1->getTimestamp() ) );
        $datetime1 = new DateTime( $lastGenerateDate );
        $dateRangeArr[] = $lastGenerateDate;
    }

    return $dateRangeArr;
}

function stm_getDateRangeWithTime( $date1, $date2 )
{
    if ( $date1 != '--' && $date2 != '--' ) {
        $datetime1 = new DateTime( $date1 );
        $datetime2 = new DateTime( $date2 );
        $interval = $datetime1->diff( $datetime2 );

        $days = (int)$interval->format( '%a' );
        $dateRangeArr = array( date( 'Y-m-d H:i', strtotime( 'now', $datetime1->getTimestamp() ) ) );
        $lastGenerateDate = '';

        for ( $q = 1; $q <= $days; $q++ ) {
            $lastGenerateDate = date( 'Y-m-d H:i', strtotime( '+1 day', $datetime1->getTimestamp() ) );
            $datetime1 = new DateTime( $lastGenerateDate );
            $dateRangeArr[] = $lastGenerateDate;
        }

        return apply_filters( 'modify_days_range', $dateRangeArr );
    }

    return array();
}

function stm_checkOrderAvailable( $orderCarClassId, $pickupDate, $returnDate )
{
    $unavailableDates = array();

    $orderCarClassId = stm_get_wpml_product_parent_id($orderCarClassId);

    $carsStockAvailable = get_post_meta( $orderCarClassId, 'cars_qty', true );

    $rangeDate = stm_getDateRange( stripslashes($pickupDate), stripslashes($returnDate) );

    for ( $q = 0; $q < count( $rangeDate ); $q++ ) {
        $orderAvailable = get_post_meta( $orderCarClassId, $rangeDate[$q] . '_' . $orderCarClassId, true );

        if ( $carsStockAvailable > $orderAvailable) {

        } else {
            $unavailableDates[] = $rangeDate[$q];
        }
    }

    return $unavailableDates;
}

function stm_add_order_date_info( $order_id, $data )
{

    $order_cookie = stm_get_rental_order_fields_values();
    $orderCarClassId = 0;
    $order = new WC_Order( $order_id );

    foreach ( $order->get_items() as $product ) {
		$prodType = stm_wc_get_product_type($product['product_id']);
        if ( $prodType != 'car_option' ) $orderCarClassId = $product['product_id'];
    }

    $orderCarClassId = stm_get_wpml_product_parent_id($orderCarClassId);

    $carsStockAvailable = get_post_meta( $orderCarClassId, 'cars_qty', true );

    $checkOrderAvailable = stm_checkOrderAvailable( $orderCarClassId, $order_cookie['calc_pickup_date'], $order_cookie['calc_return_date'] );

    if ( $orderCarClassId != 0 && count( $checkOrderAvailable ) == 0 ) {

        //if($carsStockAvailable > 0) update_post_meta($orderCarClassId, 'cars_qty', ($carsStockAvailable - 1));

        $rangeDate = stm_getDateRange( $order_cookie['calc_pickup_date'], $order_cookie['calc_return_date'] );
        $newOrderMetaDates = array();

        for ( $q = 0; $q < count( $rangeDate ); $q++ ) {

            $dateOrderQty = get_post_meta( $orderCarClassId, $rangeDate[$q] . '_' . $orderCarClassId, true );
            if ( $carsStockAvailable > 0 && $carsStockAvailable > $dateOrderQty ) {
                $metaId = update_post_meta( $orderCarClassId, $rangeDate[$q] . '_' . $orderCarClassId, $dateOrderQty + 1 );
                if ( $metaId ) $newOrderMetaDates[] = $rangeDate[$q] . '_' . $orderCarClassId;
            }
        }

        if ( count( $newOrderMetaDates ) > 0 ) {
            update_post_meta( $orderCarClassId, 'order_meta_dates_' . $order_id, implode( ',', $newOrderMetaDates ) );
        }

        return true;
    } else {
        $formatedDates = array();
        foreach ( $checkOrderAvailable as $val ) {
            $formatedDates[] = stm_get_formated_date( $val, 'd M' );
        }
        throw new Exception( esc_html__( 'This Class is already booked in: ', 'motors' ) . "<span class='bold'>" . implode( ', ', $formatedDates ) . "</span>." );
    }
}

add_action( 'woocommerce_checkout_update_order_meta', 'stm_add_order_date_info', 100, 2 );

function stm_add_order_custom_post_meta( $order_id, $status )
{
	if ( $status == 'completed' ) {
	    update_post_meta($order_id, 'stm_order_status', 'in_rent');
    } else if($status = 'cancelled') {
		stm_remove_order_custom_post_meta_hard($order_id);
    }
}

function stm_add_order_complete_post_meta( $order_id )
{
    update_post_meta($order_id, 'stm_order_status', 'in_rent');
}

function stm_remove_order_custom_post_meta_hard( $order_id )
{
    global $wpdb;

    $metaKeys = $wpdb->get_row( $wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s", 'order_meta_dates_' . $order_id) );

    $dates = explode( ',', $metaKeys->meta_value );

    foreach ( $dates as $key => $val ) {
        delete_post_meta( $metaKeys->post_id, $val );
    }

	delete_post_meta( $order_id, 'stm_order_status');
    delete_post_meta( $metaKeys->post_id, 'order_meta_dates_' . $order_id );
}

add_action( 'woocommerce_order_edit_status', 'stm_add_order_custom_post_meta', 100, 2 );
add_action( 'woocommerce_order_status_completed', 'stm_add_order_complete_post_meta', 100, 1 );
add_action( 'woocommerce_order_status_cancelled', 'stm_remove_order_custom_post_meta_hard', 100, 1 );
add_action( 'wp_trash_post', 'stm_remove_order_custom_post_meta_hard', 100, 1 );

function stm_getDefaultVariablePrice( $prodId, $index = 0, $from = false )
{
    $price = get_post_meta( $prodId, '_price' );

    if ( !$from ) rsort( $price );
    return $price[$index];
}

function stm_getInfoWindowPriceManip( $id )
{
    $id = stm_get_wpml_product_parent_id($id);

    $priceDate = PriceForDatePeriod::getVariationPriceView( $id );
    $discount = (class_exists('DiscountByDays')) ? DiscountByDays::get_days_post_meta( $id ) : null;
	$fixedPrice = (class_exists('PriceForQuantityDays')) ? PriceForQuantityDays::get_sorted_fixed_price($id) : null;
    $pricePerHour = get_post_meta( $id, 'rental_price_per_hour_info', true );

    if ( !empty( $priceDate ) || !empty( $discount ) || !empty( $pricePerHour ) || !empty($fixedPrice) ) { ?>
        <span class="price-date-info-icon">
            <i class="fa fa-info-circle"></i>
            <div class="price-date-info-popup">
                <ul>
                    <?php if ( !empty( $priceDate ) ) : ?>
                        <li class="padd-10"><?php echo esc_html__( 'Sessions:', 'motors' ) ?></li>
                        <?php foreach ( $priceDate as $dates ) : $date_format = get_option( 'date_format' ); ?>
                            <li><?php echo esc_html__( 'From ', 'motors' ) . '<b>' . date( $date_format, $dates->starttime ) . '</b>' . esc_html__( ' to ', 'motors' ) . '<b>' . date( $date_format, $dates->endtime ) . '</b>: <b>' . sprintf( __( '%s/Day', 'motors' ), wc_price( $dates->price ) ) . '</b>'; ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ( !empty( $fixedPrice ) ) : ?>
                        <li class="padd-10"><?php echo esc_html__( 'Fixed price:', 'motors' ) ?></li>
                        <?php foreach ( $fixedPrice as $k => $val ) :  ?>
                            <li><?php echo sprintf( esc_html__( '%s days and more: %s', 'motors' ), '<b>' . $k . '</b>', '<b>' . wc_price($val) . '</b>' ); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ( !empty( $discount ) ) : ?>
                        <li class="padd-10"><?php echo esc_html__( 'Discounts:', 'motors' ); ?></li>
                        <?php foreach ( $discount as $k => $val ) : ?>
                            <li><?php echo sprintf( esc_html__( '%s days and more: %s sale', 'motors' ), '<b>' . $val['days'] . '</b>', '<b>' . $val['percent'] . '%</b>' ); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php
                    if ( !empty( $pricePerHour ) ) : ?>
                        <li class="padd-10"><?php echo esc_html__( 'Per Hour:', 'motors' ); ?></li>
                        <li><?php echo sprintf( esc_html__( '%s Per hour', 'motors' ), '<b>' . wc_price( $pricePerHour ) . '</b>' ); ?></li>
                    <?php
                    endif;
                    ?>
                </ul>
            </div>
        </span>
    <?php }
}

function stm_getPopupPromoPrice( $popupId, $id , $price, $fields)
{
    $id = stm_get_wpml_product_parent_id($id);

	$tax_rates = WC_Tax::get_rates();
    $priceDate = PriceForDatePeriod::getDescribeTotalByDays($price, $id);
    $discount = (class_exists('DiscountByDays')) ? DiscountByDays::get_days_post_meta( $id ) : null;
	$fixedPrice = (class_exists('PriceForQuantityDays')) ? PriceForQuantityDays::get_sorted_fixed_price($id) : null;
    $pricePerHour = get_post_meta( $id, 'rental_price_per_hour_info', true );

    if ( !empty( $priceDate ) || !empty( $discount ) || !empty( $pricePerHour ) || !empty($fixedPrice) ) { ?>
        <div id="stm-promo-popup-wrap-<?php echo esc_attr($popupId); ?>" class="stm-promo-popup-wrap">
            <div class="stm-promo-popup">
                <div class="stm-table stm-pp-head">
                    <div class="stm-pp-row stm-pp-qty heading-font"><?php _e('QTY', 'motors');?></div>
                    <div class="stm-pp-row stm-pp-rate heading-font"><?php _e('RATE', 'motors');?></div>
                    <div class="stm-pp-row stm-pp-subtotal heading-font"><?php _e('SUBTOTAL', 'motors');?></div>
                </div>
                <!--PRICES BY DAYS PERIOD-->
				<?php

                    $total = 0;

				    if ( !empty( $priceDate ) && empty($fixedPrice) ) :
				        if(count($priceDate['simple_price']) > 0) :
                            $total = $total + array_sum($priceDate['simple_price']);
                ?>
                            <div class="stm-table stm-pp-by-day">
                                <div class="stm-pp-row stm-pp-qty"><?php echo sprintf(__('%s Days', 'motors'), count($priceDate['simple_price']));?></div>
                                <div class="stm-pp-row stm-pp-rate"><?php echo wc_price( $priceDate['simple_price'][0] ); ?></div>
                                <div class="stm-pp-row stm-pp-subtotal"><?php echo wc_price( array_sum($priceDate['simple_price']) ); ?></div>
                            </div>
                <?php
                        endif;

						if(count($priceDate['promo_price']) > 0) :
							$total = $total + array_sum($priceDate['promo_price']);
							$promoPrices = array_count_values($priceDate['promo_price']);

						    foreach ($promoPrices as $k => $val) :
                ?>
                            <div class="stm-table stm-pp-promo-by-day">
                                <div class="stm-pp-row stm-pp-qty"><?php echo sprintf(__('%s Days', 'motors'), $val);?></div>
                                <div class="stm-pp-row stm-pp-rate"><?php echo wc_price( $k ); ?></div>
                                <div class="stm-pp-row stm-pp-subtotal"><?php echo wc_price( $k * $val ); ?></div>
                            </div>
				<?php
                            endforeach;
                        endif;
				    endif;

                //FIXED PRICE
                if(count($priceDate['promo_price']) == 0 && !empty($fixedPrice)) :
                    $days = $fields['order_days'];
                    $price = 0;

                    foreach ($fixedPrice as $k => $val) {
                        if($days >= $k) {
                            $price = $val;
                        }
                    }

                    $total = $price * $days;
                ?>
                    <div class="stm-table stm-pp-promo-by-day">
                        <div class="stm-pp-row stm-pp-qty"><?php echo sprintf(__('%s Days', 'motors'), $days);?></div>
                        <div class="stm-pp-row stm-pp-rate"><?php echo wc_price( $price ); ?></div>
                        <div class="stm-pp-row stm-pp-subtotal"><?php echo wc_price( $days * $price ); ?></div>
                    </div>
                <?php
                endif;
                ?>

                <!--PRICE PER HOUR-->
				<?php
				if ( !empty( $pricePerHour ) ) :
					$total = $total + $fields['order_hours'] * $pricePerHour;
                ?>
                    <div class="stm-table stm-pp-per-hour">
                        <div class="stm-pp-row stm-pp-qty"><?php echo sprintf(__('%s Hours', 'motors'), $fields['order_hours']);?></div>
                        <div class="stm-pp-row stm-pp-rate"><?php echo wc_price( $pricePerHour ); ?></div>
                        <div class="stm-pp-row stm-pp-subtotal"><?php echo wc_price( $fields['order_hours'] * $pricePerHour ); ?></div>
                    </div>
				<?php
				endif;
				?>
                <!--DISCOUNT-->
				<?php
                $discountPrice = $total;

                if ( !empty( $discount ) ) :
                    $currentDiscount = 0;
					foreach ( $discount as $k => $val ) {
					    if($val['days'] <= $fields['order_days']) {
					        $currentDiscount = $val['percent'];
                        }
                    }

                    $discountPrice = $total - (($total / 100) * $currentDiscount);

                    ?>
                    <div class="stm-table stm-pp-discount">
                        <div class="stm-pp-row stm-pp-qty"><?php _e('Discount', 'motors');?></div>
                        <div class="stm-pp-row stm-pp-rate"><?php echo esc_html($currentDiscount . '%'); ?></div>
                        <div class="stm-pp-row stm-pp-subtotal"><?php echo '- ' . wc_price( ($total / 100) * $currentDiscount ); ?></div>
                    </div>
				<?php endif; ?>
                <!--TAX && FEES RATES-->
                <?php
                if(count($tax_rates) > 0) :
                    $taxFees = 0;
                    echo '<div class="stm-pp-tax-margin"></div>';
                    foreach ($tax_rates as $k => $val) :
                        $taxFees = $taxFees + (( $discountPrice / 100) * $val['rate']);
                ?>
                    <div class="stm-table stm-pp-tax">
                        <div class="stm-pp-row stm-pp-qty heading-font"><?php echo esc_html($val['label']);?></div>
                        <!--<div class="stm-pp-row stm-pp-rate"><?php /*echo esc_html($val['rate'] . '%'); */?></div>-->
                        <div class="stm-pp-row stm-pp-subtotal heading-font"><?php echo wc_price(( $discountPrice / 100) * $val['rate'] ); ?></div>
                    </div>
                <?php
                    endforeach;

                    $discountPrice = $discountPrice + $taxFees;
                endif
                ?>
                <div class="stm-table-total">
                    <div class="stm-pp-total-label heading-font"><?php echo esc_html__('Rental Charges Rate', 'motors');?></div>
                    <div class="stm-pp-total-price heading-font"><?php echo wc_price($discountPrice); ?></div>
                </div>
                <div class="stm-rental-ico-close" data-close-id="stm-promo-popup-wrap-<?php echo esc_attr($popupId); ?>"></div>
            </div>
        </div>
    <?php }
}

add_filter( 'product_type_options', 'stm_wc_hook_stm_product_type_options' );

function stm_wc_hook_stm_product_type_options( $checkboxes )
{
    $checkboxes['car_option'] = array(
        'id' => '_car_option',
        'wrapper_class' => 'show_if_car_option',
        'label' => __( 'Single pay for the option', 'motors' ),
        'description' => __( 'Payment amount doesn`t depend on rental days. Only one time payment for this option.', 'motors' ),
        'default' => 'no'
    );

    return $checkboxes;
}

add_action( 'woocommerce_process_product_meta', 'stm_wc_save_car_option_meta', 10, 2 );

function stm_wc_save_car_option_meta( $post_id, $post )
{
    if ( isset( $_POST['product-type'] ) && $_POST['product-type'] == 'car_option' && isset( $_POST['_car_option'] ) ) {
        update_post_meta( $post_id, '_car_option', 'yes' );
    } else {
        delete_post_meta( $post_id, '_car_option' );
    }
}

function stm_wc_session() {

    $fields = stm_rental_order_fileds();

    if(!empty(WC()->session->get($fields[5], '')) && !empty(WC()->session->get($fields[6], '')) && empty($_COOKIE[$fields[5]]) && empty($_COOKIE[$fields[6]])) {
        $_COOKIE[$fields[0]] = WC()->session->get( $fields[0], '' );
        $_COOKIE[$fields[1]] = WC()->session->get( $fields[1], '' );
        $_COOKIE[$fields[2]] = WC()->session->get( $fields[2], '' );
        $_COOKIE[$fields[3]] = WC()->session->get( $fields[3], '' );
        $_COOKIE[$fields[4]] = WC()->session->get( $fields[4], '' );
        $_COOKIE[$fields[5]] = WC()->session->get( $fields[5], '' );
        $_COOKIE[$fields[6]] = WC()->session->get( $fields[6], '' );
    }

    $pickup_location = !empty( $_COOKIE[$fields[0]] ) ? intval( $_COOKIE[$fields[0]] ) : '';
    $pickup_date = !empty( $_COOKIE[$fields[1]] ) ? sanitize_text_field( $_COOKIE[$fields[1]] ) : '';
    $return_date = !empty( $_COOKIE[$fields[2]] ) ? sanitize_text_field( $_COOKIE[$fields[2]] ) : '';
    $return_location = !empty( $_COOKIE[$fields[3]] ) ? intval( $_COOKIE[$fields[3]] ) : '';
    $return_same = !empty( $_COOKIE[$fields[4]] ) ? sanitize_text_field( $_COOKIE[$fields[4]] ) : 'on';
    $pickup_date_calc = !empty( $_COOKIE[$fields[5]] ) ? sanitize_text_field( $_COOKIE[$fields[5]] ) : '';
    $return_date_calc = !empty( $_COOKIE[$fields[6]] ) ? sanitize_text_field( $_COOKIE[$fields[6]] ) : '';

    WC()->session->set($fields[0], $pickup_location);
    WC()->session->set($fields[1], $pickup_date);
    WC()->session->set($fields[2], $return_date);
    WC()->session->set($fields[3], $return_location);
    WC()->session->set($fields[4], $return_same);
    WC()->session->set($fields[5], $pickup_date_calc);
    WC()->session->set($fields[6], $return_date_calc);
}

add_action('woocommerce_cart_updated', 'stm_wc_session');