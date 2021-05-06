<?php
function checkPayPerListings()
{
	global $wpdb;

	$ppPeriod = get_theme_mod( 'pay_per_listing_period', '30' );

	if($ppPeriod == 0) return;

	$toDay = new DateTime();
	$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'pay_per_create_date'" );

	if ( $results ) {
		foreach ( $results as $val ) {
			$datetime1 = new DateTime( date( 'Y-m-d', $val->meta_value ) );
			$datetime2 = new DateTime( date( 'Y-m-d', $toDay->getTimestamp() ) );

			$diff = (array)$datetime2->diff( $datetime1 );

			if ( $ppPeriod < $diff['days'] ) {
				$listing = array( 'ID' => $val->post_id, 'post_status' => 'pending', );
				delete_post_meta( $val->post_id, 'pay_per_create_date' );
				wp_update_post( $listing );
			}
		}
	}
}

function checkPayFeaturedListings()
{
	global $wpdb;

	$ppPeriod = get_theme_mod( 'featured_listing_period', '30' );

	if($ppPeriod == 0) return;

	$toDay = new DateTime();
	$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'pay_featured_create_date'" );

	if ( $results ) {
		foreach ( $results as $val ) {
			$listingId = $val->post_id;
			$datetime1 = new DateTime( date( 'Y-m-d', $val->meta_value ) );
			$datetime2 = new DateTime( date( 'Y-m-d', $toDay->getTimestamp() ) );

			$diff = (array)$datetime2->diff( $datetime1 );

			if ( $ppPeriod < $diff['days'] ) {
				delete_post_meta( $listingId, 'car_make_featured_status', '' );
				delete_post_meta( $listingId, 'pay_featured_create_date', '' );
				delete_post_meta( $listingId, 'special_car', '' );
				delete_post_meta( $listingId, 'badge_text', '' );
			}
		}
	}
}

if(!function_exists('stm_checkPayPerFeatured')) {
	function stm_checkPayPerFeatured() {
		checkPayPerListings();
		checkPayFeaturedListings();
	}
}

function stm_start_listings_cron () {
	if ( is_listing() ) {
		if ( !wp_next_scheduled( 'checkPayPerFeatured' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'checkPayPerFeatured' );
		}
	}
}

add_action('checkPayPerFeatured', 'stm_checkPayPerFeatured');
add_action( 'init', 'stm_start_listings_cron' );


function stm_check_is_past_date_and_del_meta() {

	global $wpdb;
	$orders = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = 'in_rent'" );

	if($orders) {
		foreach ($orders as $order ) {
			$orderPId = $order->post_id;

			$orderCarData = get_post_meta($orderPId, 'order_car_date', true);

			if(is_array($orderCarData)) {

				$currentDT = current_datetime();
				$checkDate = $orderCarData['calc_return_date'];

				if((strtotime($checkDate) - strtotime($currentDT->format('Y-m-d H:i:s'))) < 0) {
					$metaKeys = $wpdb->get_row( $wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s", 'order_meta_dates_' . $orderPId) );

					$dates = explode( ',', $metaKeys->meta_value );

					foreach ( $dates as $key => $val ) {
						delete_post_meta( $metaKeys->post_id, $val );
					}
					delete_post_meta( $metaKeys->post_id, 'order_meta_dates_' . $orderPId );
					delete_post_meta($orderPId, 'stm_order_status');
				}
			}
		}
	}
}

if(!function_exists('stm_rentalCheckPastOrders')) {
	function stm_rentalCheckPastOrders() {
		stm_check_is_past_date_and_del_meta();
	}
}

function stm_check_past_orders_cron () {
	$listing = get_option('stm_motors_chosen_template');
	if ( !empty($listing) && ($listing == 'car_rental' || $listing == 'rental_two') ) {
		if ( !wp_next_scheduled( 'rentalCheckPastOrders' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'rentalCheckPastOrders' );
		}
	}
}

add_action('rentalCheckPastOrders', 'stm_rentalCheckPastOrders');
add_action( 'init', 'stm_check_past_orders_cron' );