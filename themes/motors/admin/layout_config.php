<?php

function motors_layout_plugins($layout, $get_layouts = false)
{
    $required = array(
        'stm-motors-extends',
        'custom_icons_by_stylemixthemes',
        'stm_importer',
        'js_composer',
        'revslider',
        'breadcrumb-navxt',
        'contact-form-7',
    );

    $plugins = array(
        'car_magazine' => array(
			'stm-post-type',
            'stm_vehicles_listing',
            'stm-megamenu',
            'instagram-feed',
            'accesspress-social-counter',
            'stm_motors_events',
			'add-to-any',
			'mailchimp-for-wp',
            'stm_motors_review'
        ),
        'service' => array(
			'stm-post-type',
            'stm_vehicles_listing',
            'instagram-feed',
            'bookly-responsive-appointment-booking-tool',
			'add-to-any',
			'mailchimp-for-wp',
        ),
        'listing' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
			'add-to-any',
			'mailchimp-for-wp',
            'woocommerce'
        ),
        'listing_two' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
            'woocommerce',
			'add-to-any',
			'mailchimp-for-wp',
            'stm_motors_review'
        ),
        'listing_three' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
            'woocommerce',
			'add-to-any',
			'mailchimp-for-wp',
            'stm_motors_review'
        ),
        'listing_four' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
			'add-to-any',
			'mailchimp-for-wp',
            'woocommerce'
        ),
        'listing_five' => array(
			'stm-post-type',
        	'stm-motors-classified-five',
			'ulisting',
			'ulisting-wishlist',
			'ulisting-compare',
        ),
        'listing_six' => array(
        	'stm-motors-classified-six',
			'ulisting',
			'ulisting-wishlist',
			'ulisting-compare',
        ),
        'car_dealer' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
			'add-to-any',
            'woocommerce',
			'mailchimp-for-wp',
        ),
        'car_dealer_two' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
			'add-to-any',
            'woocommerce',
			'mailchimp-for-wp',
        ),
        'motorcycle' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
            'woocommerce',
			'add-to-any',
			'mailchimp-for-wp',
        ),
        'boats' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
            'instagram-feed',
            'woocommerce',
			'add-to-any',
			'mailchimp-for-wp',
        ),
        'car_rental' => array(
			'stm-post-type',
            'stm_vehicles_listing',
            'instagram-feed',
            'woocommerce',
			'add-to-any',
			'mailchimp-for-wp',
        ),
        'auto_parts' => array(
			'stm-post-type',
            'stm-woocommerce-motors-auto-parts',
            'pearl-header-builder',
            'woo-multi-currency',
            'yith-woocommerce-compare',
            'yith-woocommerce-wishlist',
            'woocommerce',
			'add-to-any',
			'mailchimp-for-wp',
        ),
        'aircrafts' => array(
			'stm-post-type',
            'stm_vehicles_listing',
			'stm-megamenu',
			'add-to-any',
			'mailchimp-for-wp',
            'woocommerce'
        ),
        'rental_two' => array(
			'stm-post-type',
            'stm-motors-car-rental',
			'mailchimp-for-wp',
            'woocommerce'
        ),
		'equipment' => array(
			'stm-post-type',
			'stm_vehicles_listing',
			'mailchimp-for-wp',
			'stm-motors-equipment'
		),
    );

    if ($get_layouts) return $plugins;

    return array_merge($required, $plugins[$layout]);
}