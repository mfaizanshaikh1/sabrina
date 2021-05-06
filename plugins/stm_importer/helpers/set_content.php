<?php
function stm_set_content_options( $chosen_template )
{
    /*Set menus*/
    $locations = get_theme_mod( 'nav_menu_locations' );
    $menus = wp_get_nav_menus();

    if ( !empty( $menus ) ) {
        foreach ( $menus as $menu ) {
            if ( is_object( $menu ) ) {
                switch ( $menu->name ) {
                    case 'Primary menu':
                        $locations['primary'] = $menu->term_id;
                        break;
                    case 'Top bar menu':
                        $locations['top_bar'] = $menu->term_id;
                        break;
                    case 'Bottom menu':
                        $locations['bottom_menu'] = $menu->term_id;
                        break;
                }
            }
        }
    }

    set_theme_mod( 'nav_menu_locations', $locations );
    set_theme_mod( 'listing_sidebar', 'no_sidebar' );

    //Set pages
    update_option( 'show_on_front', 'page' );

    $inventory_page = get_page_by_title( 'Inventory' );
    if ( isset( $inventory_page->ID ) ) {
        set_theme_mod( 'listing_archive', $inventory_page->ID );
    }

    /*Woocomerce set default pages*/
    if ( $chosen_template == 'car_dealer' || $chosen_template == 'car_dealer_two' || $chosen_template == 'boats' || $chosen_template == 'motorcycle' || $chosen_template == 'auto_parts' ) {
        $checkout_page = get_page_by_title( 'Checkout' );
        if ( isset( $checkout_page->ID ) ) {
            update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
        }
        $cart_page = get_page_by_title( 'Cart' );
        if ( isset( $cart_page->ID ) ) {
            update_option( 'woocommerce_cart_page_id', $cart_page->ID );
        }
        $shop_page = get_page_by_title( 'Shop' );
        if ( isset( $shop_page->ID ) ) {
            update_option( 'woocommerce_shop_page_id', $shop_page->ID );
            update_option( 'woocommerce_single_image_width', 327 );
            update_option( 'woocommerce_thumbnail_image_width', 150 );
        }

        $account_page = get_page_by_title( 'My Account' );
        if ( isset( $account_page->ID ) ) {
            update_option( 'woocommerce_myaccount_page_id', $account_page->ID );
        }
    }
    /*Woocomerce set default pages*/

    // Car dealer
    if ( $chosen_template == 'car_dealer' ) {
        stm_update_listing_options_listing_layout();
        $front_page = get_page_by_title( 'Front page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'Newsroom' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }
    }

    // Service
    if ( $chosen_template == 'service' ) {
        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }
    }

    // Listing
    if ( $chosen_template == 'listing' ) {

        stm_update_listing_options_listing_layout();

        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'Blog' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }

        $dealers = get_page_by_title( 'Dealers list' );
        if ( isset( $dealers->ID ) ) {
            set_theme_mod( 'dealer_list_page', $dealers->ID );
        }

        $compare = get_page_by_title( 'Compare' );
        if ( isset( $compare->ID ) ) {
            set_theme_mod( 'compare_page', $compare->ID );
        }

        $optionCat = get_option( 'stm_vehicle_listing_options' );
        $optionCat[5]['listing_taxonomy_parent'] = 'make';
        update_option( 'stm_vehicle_listing_options', $optionCat );

        $termmeta = json_decode( file_get_contents( STM_CONFIGURATIONS_PATH . '/helpers/model_json.json' ) );
        foreach ( $termmeta as $key => $value ) {
            update_term_meta( $value->term_id, $value->meta_key, $value->meta_value );
        }
    }

    if ( $chosen_template == 'listing_four' ) {

        stm_update_listing_options_listing_layout();

        $front_page = get_page_by_title( 'Home' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'Newsroom' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }

        $dealers = get_page_by_title( 'Dealers list' );
        if ( isset( $dealers->ID ) ) {
            set_theme_mod( 'dealer_list_page', $dealers->ID );
        }

        $compare = get_page_by_title( 'Compare' );
        if ( isset( $compare->ID ) ) {
            set_theme_mod( 'compare_page', $compare->ID );
        }

        $optionCat = get_option( 'stm_vehicle_listing_options' );
        $optionCat[5]['listing_taxonomy_parent'] = 'make';
        update_option( 'stm_vehicle_listing_options', $optionCat );

        $termmeta = json_decode( file_get_contents( STM_CONFIGURATIONS_PATH . '/helpers/model_json.json' ) );
        foreach ( $termmeta as $key => $value ) {
            update_term_meta( $value->term_id, $value->meta_key, $value->meta_value );
        }
    }

    if ( $chosen_template == 'listing_five' || $chosen_template == 'listing_six' ) {

        $front_page = get_page_by_title( 'Home Page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$wp_rewrite->flush_rules();

		set_theme_mod( 'site_style', 'site_style_custom' );
		stm_print_styles_color();
    }

    // Boats
    if ( $chosen_template == 'boats' ) {
        stm_update_boats_options_listing_layout();

        $front_page = get_page_by_title( 'Home' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'Newsroom' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }
    }

    // Motorcycle
    if ( $chosen_template == 'motorcycle' ) {
        stm_update_motorcycle_options_listing_layout();

        $front_page = get_page_by_title( 'Home' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'Newsroom' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }
    }

    // Rental
    if ( $chosen_template == 'car_rental' ) {
        stm_update_options_rental_layout();

        $pages = array(
            'woocommerce_shop_page_id' => 'Reservation',
            'woocommerce_cart_page_id' => 'Cart',
            'woocommerce_checkout_page_id' => 'Checkout',
            'woocommerce_myaccount_page_id' => 'Checkout',
            'woocommerce_terms_page_id' => 'Terms',
            'page_on_front' => 'Home page',
            'rental_datepick' => 'Date Reservation',
            'order_received' => 'Policy'
        );

        foreach ( $pages as $key => $page ) {
            $get_page = get_page_by_title( $page );
            if ( isset( $get_page->ID ) ) {
                update_option( $key, $get_page->ID );
            }
        }

		$reservPageId = get_page_by_title( 'Date Reservation' );
		set_theme_mod('rental_datepick', $reservPageId->ID);

        /*Force woocommerce to update shop archive*/
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
        $wp_rewrite->flush_rules();
    }

    // Rental Two
    if ( $chosen_template == 'rental_two' ) {

		update_option('date_format', 'm/d/Y');

        $pages = array(
            'woocommerce_shop_page_id' => 'Explore Vehicles',
            'woocommerce_cart_page_id' => 'Cart',
            'woocommerce_checkout_page_id' => 'Checkout',
            'woocommerce_myaccount_page_id' => 'My account',
            'woocommerce_terms_page_id' => 'Privacy Policy',
            'page_on_front' => 'Home page',
        );

        foreach ( $pages as $key => $page ) {
            $get_page = get_page_by_title( $page );
            if ( isset( $get_page->ID ) ) {
                update_option( $key, $get_page->ID );
            }
        }

		$reservPageId = get_page_by_title( 'Explore Vehicles' );
		$shopSideBarId = get_page_by_title( 'Shop Sidebar' , OBJECT, 'sidebar');
		set_theme_mod('rental_datepick', $reservPageId->ID);
		set_theme_mod('shop_sidebar', $shopSideBarId->ID);
		set_theme_mod('shop_sidebar_position', 'right');

		delete_transient( 'woocommerce_cache_excluded_uris' );

        /*Force woocommerce to update shop archive*/
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
        $wp_rewrite->flush_rules();

        wp_cache_flush();
    }

    // Magazine
    if ( $chosen_template == 'car_magazine' ) {
        stm_update_listing_options_listing_layout();

        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'News' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }


        if ( class_exists( 'RevSlider' ) ) {
            $main_slider = get_template_directory() . '/inc/demo/magazine_home_slider.zip';

            if ( file_exists( $main_slider ) ) {
                $slider = new RevSlider();
                $slider->importSliderFromPost( true, true, $main_slider );
            }
        }

        set_theme_mod( 'site_style', 'site_style_custom' );
        stm_print_styles_color();
    }

    // Dealer Two
    if ( $chosen_template == 'car_dealer_two' ) {

        stm_update_listing_options_listing_layout();

        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'Blog' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }

        $compare = get_page_by_title( 'Compare' );
        if ( isset( $compare->ID ) ) {
            set_theme_mod( 'compare_page', $compare->ID );
        }

        $optionCat = get_option( 'stm_vehicle_listing_options' );
        $optionCat[5]['listing_taxonomy_parent'] = 'make';
        update_option( 'stm_vehicle_listing_options', $optionCat );

        $termmeta = json_decode( file_get_contents( STM_CONFIGURATIONS_PATH . '/helpers/model_json.json' ) );
        foreach ( $termmeta as $key => $value ) {
            update_term_meta( $value->term_id, $value->meta_key, $value->meta_value );
        }

        set_theme_mod( 'listing_filter_position', 'right' );
        set_theme_mod( 'site_style', 'site_style_custom' );
        update_option( 'woocommerce_catalog_columns', 3 );
        stm_print_styles_color();
    }

    // Listing Two or Listing Three
    if ( $chosen_template == 'listing_two' || $chosen_template == 'listing_three' ) {


        stm_update_listing_options_listing_layout();

        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'News' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }

        $compare = get_page_by_title( 'Compare' );
        if ( isset( $compare->ID ) ) {
            set_theme_mod( 'compare_page', $compare->ID );
        }

        $dealers = get_page_by_title( 'Dealers list' );
        if ( isset( $dealers->ID ) ) {
            set_theme_mod( 'dealer_list_page', $dealers->ID );
        }

        $optionCat = get_option( 'stm_vehicle_listing_options' );
        $optionCat[5]['listing_taxonomy_parent'] = 'make';
        update_option( 'stm_vehicle_listing_options', $optionCat );

        $termmeta = json_decode( file_get_contents( STM_CONFIGURATIONS_PATH . '/helpers/model_json.json' ) );
        foreach ( $termmeta as $key => $value ) {
            update_term_meta( $value->term_id, $value->meta_key, $value->meta_value );
        }

        set_theme_mod( 'listing_filter_position', 'right' );
        set_theme_mod( 'site_style', 'site_style_custom' );
        update_option( 'woocommerce_catalog_columns', 3 );
        stm_print_styles_color();
    }

    // Auto Parts
    if ( $chosen_template == 'auto_parts' ) {

        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'News' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }

        $wl = get_page_by_title( 'Wishlist' );
        if ( isset( $wl->ID ) ) {
            update_option( 'yith_wcwl_wishlist_page_id', $wl->ID );
        }

        $multiCurrOpt = array(
            'enable' => 1,
            'price_switcher' => 2,
            'currency_default' => 'USD',
            'currency' => array( 0 => 'USD', 1 => 'EUR' ),
            'currency_pos' => array( 0 => 'left', 1 => 'left' ),
            'currency_rate' => array( 0 => 1, 1 => 1.2 ),
            'currency_decimals' => array( 0 => 2, 1 => 2 ),
            'currency_custom' => array( 0 => '', 1 => '' ),
            'auto_detect' => 0,
            'geo_api' => 0,
            'design_title' => 'Select your currency',
            'design_position' => 1,
            'text_color' => '#ffffff',
            'main_color' => '#f78080',
            'background_color' => '#212121',
            'conditional_tags' => '',
            'flag_custom' => '',
            'custom_css' => '',
            'enable_multi_payment' => 1
        );

        update_option( 'woocommerce_catalog_columns', 4 );
        update_option( 'woocommerce_currency', 'USD' );
        update_option( 'woo-multi-currency_start_use', 1 );
        update_option( 'woo_multi_currency_params', $multiCurrOpt );

        set_theme_mod( 'site_style', 'site_style_custom' );
        stm_print_styles_color();
    }

    if ( $chosen_template == 'aircrafts' ) {
        stm_update_aircrafts_options_listing_layout();

        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $blog_page = get_page_by_title( 'Blog' );
        if ( isset( $blog_page->ID ) ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }

        $compare = get_page_by_title( 'Compare' );
        if ( isset( $compare->ID ) ) {
            set_theme_mod( 'compare_page', $compare->ID );
        }

        $dealers = get_page_by_title( 'Dealers' );
        if ( isset( $dealers->ID ) ) {
            set_theme_mod( 'dealer_list_page', $dealers->ID );
        }

        $termmeta = json_decode( file_get_contents( STM_CONFIGURATIONS_PATH . '/helpers/model_json.json' ) );
        foreach ( $termmeta as $key => $value ) {
            update_term_meta( $value->term_id, $value->meta_key, $value->meta_value );
        }

        set_theme_mod( 'listing_filter_position', 'right' );
        set_theme_mod( 'site_style', 'site_style_custom' );
        update_option( 'woocommerce_catalog_columns', 3 );
        stm_print_styles_color();
    }

    if ( $chosen_template == 'equipment' ) {
        $front_page = get_page_by_title( 'Home page' );
        if ( isset( $front_page->ID ) ) {
            update_option( 'page_on_front', $front_page->ID );
        }

        $compare = get_page_by_title( 'Compare' );
        if ( isset( $compare->ID ) ) {
            set_theme_mod( 'compare_page', $compare->ID );
        }

        set_theme_mod( 'site_style', 'site_style_custom' );
        stm_print_styles_color();
    }

    /*update genuine price*/
    $args = array(
        'post_type' => stm_listings_post_type(),
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    $q = new WP_Query( $args );
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            $id = get_the_ID();
            $price = get_post_meta( $id, 'price', true );
            $sale_price = get_post_meta( $id, 'sale_price', true );

            if ( !empty( $sale_price ) ) {
                $price = $sale_price;
            }

            if ( !empty( $price ) ) {
                update_post_meta( $id, 'stm_genuine_price', $price );
            }
        }
    }

    $a2aUpdOpt = array(
        'display_in_posts_on_front_page' => -1,
        'display_in_posts_on_archive_pages' => -1,
        'display_in_excerpts' => -1,
        'display_in_posts' => -1,
        'display_in_pages' => -1,
        'display_in_attachments' => -1,
        'display_in_feed' => -1,
        'display_in_cpt_stm_office' => -1,
        'display_in_cpt_sidebar' => -1,
        'display_in_cpt_test_drive_request' => -1,
        'display_in_cpt_listings' => -1,
        'display_in_cpt_product' => -1
    );

    $a2aGetOpt = get_option( 'addtoany_options' );

    if ( !empty( $a2aGetOpt ) ) {
        $upd = array_replace( $a2aGetOpt, $a2aUpdOpt );
        update_option( 'addtoany_options', $upd );
    }
}

//Add default taxonomies for the first theme activating
//Only if user dont have them already
function stm_update_options_rental_layout()
{
    $stm_listings_update_options = array(
        0 => array(
            'single_name' => 'Seat',
            'plural_name' => 'Seats',
            'slug' => 'drive',
            'font' => 'stm-rental-seats',
            'numeric' => 1,
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
            'number_field_affix' => '',
            'slider' => '',
            'use_on_tabs' => '',
            'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'listing_taxonomy_parent' => 'fuel-economy',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'show_in_admin_column' => '',
        ),
        1 => array(
            'single_name' => 'Bag',
            'plural_name' => 'Bags',
            'slug' => 'fuel-economy',
            'font' => 'stm-rental-bag',
            'numeric' => 1,
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
            'number_field_affix' => '',
            'slider' => '',
            'use_on_tabs' => '',
            'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'show_in_admin_column' => '',
        ),
        2 => array(
            'single_name' => 'Door',
            'plural_name' => 'Doors',
            'slug' => 'exterior-color',
            'font' => 'stm-rental-door',
            'numeric' => 1,
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
            'number_field_affix' => '',
            'slider' => '',
            'use_on_tabs' => '',
            'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'show_in_admin_column' => '',
        ), 3 => array(
            'single_name' => 'Feature',
            'plural_name' => 'Features',
            'slug' => 'interior-color',
            'font' => 'stm-rental-ac',
            'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
            'number_field_affix' => '',
            'slider' => '',
            'use_on_tabs' => '',
            'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'show_in_admin_column' => '',
        ),
    );
    update_option( 'stm_vehicle_listing_options', $stm_listings_update_options );
}

function stm_update_listing_options_listing_layout()
{
    $stm_listings_update_options = array(
        1 => array(
            'single_name' => 'Condition',
            'plural_name' => 'Conditions',
            'slug' => 'condition',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => true,
        ),
        2 => array(
            'single_name' => 'Body',
            'plural_name' => 'Bodies',
            'slug' => 'body',
            'font' => 'stm-service-icon-body_type',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'listing_rows_numbers' => 'two_cols',
            'enable_checkbox_button' => false,
        ),
        3 => array(
            'single_name' => 'Make',
            'plural_name' => 'Makes',
            'slug' => 'make',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => true,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => true,
        ),
        5 => array(
            'single_name' => 'Model',
            'plural_name' => 'Models',
            'slug' => 'serie',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => true,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => true,
        ),
        6 => array(
            'single_name' => 'Mileage',
            'plural_name' => 'Mileages',
            'slug' => 'mileage',
            'font' => 'stm-icon-road',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'number_field_affix' => 'mi',
            'enable_checkbox_button' => false,
        ),
        7 => array(
            'single_name' => 'Fuel type',
            'plural_name' => 'Fuel types',
            'slug' => 'fuel',
            'font' => 'stm-icon-fuel',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
        ),
        8 => array(
            'single_name' => 'Engine',
            'plural_name' => 'Engines',
            'slug' => 'engine',
            'font' => 'stm-icon-engine_fill',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        9 => array(
            'single_name' => 'Year',
            'plural_name' => 'Years',
            'slug' => 'ca-year',
            'font' => 'stm-icon-road',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
        ),
        10 => array(
            'single_name' => 'Price',
            'plural_name' => 'Prices',
            'slug' => 'price',
            'font' => 'stm-icon-road',
            'numeric' => true,
            'slider' => true,
            'use_on_single_listing_page' => true,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
        ),
        11 => array(
            'single_name' => 'Fuel consumption',
            'plural_name' => 'Fuel consumptions',
            'slug' => 'fuel-consumption',
            'font' => 'stm-icon-fuel',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        12 => array(
            'single_name' => 'Transmission',
            'plural_name' => 'Transmission',
            'slug' => 'transmission',
            'font' => 'stm-icon-transmission_fill',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
        ),
        13 => array(
            'single_name' => 'Drive',
            'plural_name' => 'Drives',
            'slug' => 'drive',
            'font' => 'stm-icon-drive_2',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
        ),
        14 => array(
            'single_name' => 'Fuel economy',
            'plural_name' => 'Fuel economy',
            'slug' => 'fuel-economy',
            'font' => '',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
        ),
        15 => array(
            'single_name' => 'Exterior Color',
            'plural_name' => 'Exterior Colors',
            'slug' => 'exterior-color',
            'font' => 'stm-service-icon-color_type',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
        ),
        16 => array(
            'single_name' => 'Interior Color',
            'plural_name' => 'Interior Colors',
            'slug' => 'interior-color',
            'font' => 'stm-service-icon-color_type',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
        )
    );
    update_option( 'stm_vehicle_listing_options', $stm_listings_update_options );
}

function stm_update_motorcycle_options_listing_layout()
{
    $stm_listings_update_options = array(
        1 => array(
            'single_name' => 'Condition',
            'plural_name' => 'Conditions',
            'slug' => 'condition',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => true,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        2 => array(
            'single_name' => 'Type',
            'plural_name' => 'Types',
            'slug' => 'body',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_tabs' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        3 => array(
            'single_name' => 'Category',
            'plural_name' => 'Categories',
            'slug' => 'category_type',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => true,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        4 => array(
            'single_name' => 'Brand',
            'plural_name' => 'Brands',
            'slug' => 'make',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => true,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        5 => array(
            'single_name' => 'Model',
            'plural_name' => 'Models',
            'slug' => 'serie',
            'font' => 'icomoon-settings',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        6 => array(
            'single_name' => 'Mileage',
            'plural_name' => 'Mileages',
            'slug' => 'mileage',
            'font' => '',
            'numeric' => true,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'number_field_affix' => 'ml',
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        7 => array(
            'single_name' => 'Engine',
            'plural_name' => 'Engines',
            'slug' => 'engine',
            'font' => '',
            'numeric' => true,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        8 => array(
            'single_name' => 'Year',
            'plural_name' => 'Years',
            'slug' => 'ca-year',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        9 => array(
            'single_name' => 'Price',
            'plural_name' => 'Prices',
            'slug' => 'price',
            'font' => '',
            'numeric' => true,
            'slider' => true,
            'use_on_single_listing_page' => true,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        10 => array(
            'single_name' => 'Color',
            'plural_name' => 'Colors',
            'slug' => 'exterior-color',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
    );
    update_option( 'stm_vehicle_listing_options', $stm_listings_update_options );
}

function stm_update_boats_options_listing_layout()
{
    $stm_listings_update_options = array(
        1 => array(
            'single_name' => 'Make',
            'plural_name' => 'Makes',
            'slug' => 'make',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter' => true,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        2 => array(
            'single_name' => 'Model',
            'plural_name' => 'Models',
            'slug' => 'serie',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        3 => array(
            'single_name' => 'Condition',
            'plural_name' => 'Conditions',
            'slug' => 'condition',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),
        4 => array(
            'single_name' => 'Length',
            'plural_name' => 'Length',
            'slug' => 'length_range',
            'font' => 'stm-boats-icon-size',
            'numeric' => true,
            'slider' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'number_field_affix' => '',
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        5 => array(
            'single_name' => 'Year',
            'plural_name' => 'Years',
            'slug' => 'ca-year',
            'font' => 'stm-icon-date',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        6 => array(
            'single_name' => 'Price',
            'plural_name' => 'Prices',
            'slug' => 'price',
            'font' => '',
            'numeric' => true,
            'slider' => true,
            'use_on_single_listing_page' => true,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        7 => array(
            'single_name' => 'Boat type',
            'plural_name' => 'Boat types',
            'slug' => 'boat-type',
            'font' => '',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        8 => array(
            'single_name' => 'Fuel type',
            'plural_name' => 'Fuel types',
            'slug' => 'fuel',
            'font' => 'stm-icon-fuel',
            'numeric' => false,
            'slider' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
        9 => array(
            'single_name' => 'Hull material',
            'plural_name' => 'Hull materials',
            'slug' => 'hull_material',
            'font' => 'stm-boats-icon-sail',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter' => true,
            'use_on_car_filter_links' => false,
            'use_on_directory_filter_title' => false,
            'enable_checkbox_button' => false,
            'use_in_footer_search' => false,
        ),
    );
    update_option( 'stm_vehicle_listing_options', $stm_listings_update_options );
}

function stm_update_aircrafts_options_listing_layout()
{
    $stm_listings_update_options = array(
        0 => array(
            'single_name' => 'Condition',
            'plural_name' => 'Conditions',
            'slug' => 'condition',
            'font' => 'fa fa-outdent',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => true,
            'number_field_affix' => false,
            'slider' => false,
            'slider_step' => 10,
            'use_on_tabs' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => true,
            'listing_rows_numbers' => 'one_col',
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        1 => array(
            'single_name' => 'Type',
            'plural_name' => 'Types',
            'slug' => 'body',
            'font' => 'fa fa-space-shuttle',
            'numeric' => false,
            'number_field_affix' => false,
            'slider' => false,
            'slider_step' => 10,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_car_filter' => true,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => true,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'use_on_single_listing_page' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => true,
            'listing_rows_numbers' => 'two_cols',
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        2 => array
        (
            'single_name' => 'Make',
            'plural_name' => 'Makes',
            'slug' => 'make',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => true,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_tabs' => false,
            'filter_links_default_expanded' => 'close',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => true,
            'listing_rows_numbers' => 'one_col',
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'close',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => true,
        ),

        3 => array
        (
            'single_name' => 'Model',
            'plural_name' => 'Models',
            'slug' => 'serie',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => true,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_tabs' => false,
            'filter_links_default_expanded' => 'close',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => true,
            'listing_rows_numbers' => 'one_col',
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'close',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => true,
        ),

        4 => array
        (
            'single_name' => 'Mileage',
            'plural_name' => 'Mileages',
            'slug' => 'mileage',
            'font' => 'stm-icon-road',
            'numeric' => true,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),

        5 => array
        (
            'single_name' => 'Engine',
            'plural_name' => 'Engines',
            'slug' => 'engine',
            'font' => 'stm-icon-engine_fill',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => true,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_tabs' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        6 => array
        (
            'single_name' => 'Year',
            'plural_name' => 'Years',
            'slug' => 'ca-year',
            'font' => 'stm-icon-road',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),

        7 => array
        (
            'single_name' => 'Price',
            'plural_name' => 'Prices',
            'slug' => 'price',
            'font' => '',
            'numeric' => true,
            'use_on_single_listing_page' => true,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => true,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_tabs' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => true,
            'listing_rows_numbers' => 'one_col',
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => true,
        ),

        8 => array
        (
            'single_name' => 'Drive',
            'plural_name' => 'Drives',
            'slug' => 'drive',
            'font' => 'stm-icon-drive_2',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => true,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => true,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),

        9 => array
        (
            'single_name' => 'Exterior Color',
            'plural_name' => 'Exterior Colors',
            'slug' => 'exterior-color',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => true,
            'use_on_map_page' => false,
            'use_on_car_filter' => true,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
        ),

        10 => array
        (
            'single_name' => 'Interior Color',
            'plural_name' => 'Interior Colors',
            'slug' => 'interior-color',
            'font' => '',
            'numeric' => false,
            'use_on_single_listing_page' => false,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_map_page' => false,
            'use_on_car_filter' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_tabs' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        11 => array
        (
            'single_name' => 'Maximum Range',
            'plural_name' => 'Maximum Range',
            'slug' => 'max_range',
            'font' => 'stm-icon-ac-max-range',
            'numeric' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => true,
            'use_on_car_filter_links' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'use_on_single_listing_page' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        12 => array
        (
            'single_name' => 'Passengers',
            'plural_name' => 'Passengers',
            'slug' => 'passengers',
            'font' => 'stm-icon-ac-max-passenger',
            'numeric' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'use_on_single_listing_page' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        13 => array
        (
            'single_name' => 'Maximum Speed',
            'plural_name' => 'Max Speed',
            'slug' => 'max_speed',
            'font' => 'stm-icon-ac-max-speed',
            'numeric' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'use_on_single_listing_page' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        14 => array
        (
            'single_name' => 'Useful Load',
            'plural_name' => 'Useful Load',
            'slug' => 'useful_load',
            'font' => 'stm-icon-ac-useful-load',
            'numeric' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'use_on_single_listing_page' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        15 => array
        (
            'single_name' => 'Cockpit Automation',
            'plural_name' => 'Cockpit Automation',
            'slug' => 'cockpit-automation',
            'font' => '',
            'numeric' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'use_on_single_listing_page' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        ),

        16 => array(
            'single_name' => 'ACAS Automation',
            'plural_name' => 'ACAS Automation',
            'slug' => 'acas-automation',
            'font' => '',
            'numeric' => false,
            'number_field_affix' => '',
            'slider' => false,
            'slider_step' => 10,
            'use_on_car_listing_page' => false,
            'use_on_car_archive_listing_page' => false,
            'use_on_single_car_page' => false,
            'use_on_car_filter' => false,
            'use_on_tabs' => false,
            'use_on_car_modern_filter' => false,
            'use_on_car_modern_filter_view_images' => false,
            'use_on_car_filter_links' => false,
            'filter_links_default_expanded' => 'open',
            'use_in_footer_search' => false,
            'use_on_directory_filter_title' => false,
            'use_on_single_listing_page' => false,
            'listing_taxonomy_parent' => false,
            'listing_rows_numbers_enable' => false,
            'listing_rows_numbers' => false,
            'enable_checkbox_button' => false,
            'listing_rows_numbers_default_expanded' => 'open',
            'show_in_admin_column' => false,
            'use_on_single_header_search' => false,
        )

    );
    update_option( 'stm_vehicle_listing_options', $stm_listings_update_options );
}

function stm_update_equipment_listings_options () {
	$stm_listings_update_options = array (
		0 => array (
			'single_name' => 'Condition',
			'plural_name' => 'Conditions',
			'slug' => 'condition',
			'font' => '',
            'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_map_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => 1,
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		1 => array (
			'single_name' => 'Body',
			'plural_name' => 'Bodies',
			'slug' => 'body',
			'font' => '',
            'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => 1,
            'use_on_map_page' => '',
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => 1,
            'use_on_car_filter_links' => '',
            'number_field_affix' => '',
            'slider' => '',
            'slider_step' => 10,
            'use_on_tabs' => '',
            'filter_links_default_expanded' => 'open',
			'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'listing_rows_numbers_default_expanded' => 'open',
			'show_in_admin_column' => '',
        ),
		2 => array (
			'single_name' => 'Make',
			'plural_name' => 'Makes',
			'slug' => 'make',
			'font' => '',
            'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_map_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => 1,
            'use_on_car_modern_filter_view_images' => 1,
            'use_on_car_filter_links' => '',
        ),
		3 => array (
			'single_name' => 'Model',
			'plural_name' => 'Models',
			'slug' => 'serie',
			'font' => '',
            'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_map_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		4 => array (
			'single_name' => 'Mileage',
			'plural_name' => 'Mileages',
			'slug' => 'mileage',
			'font' => 'stm-icon-road',
			'numeric' => 1,
			'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => 1,
            'use_on_single_car_page' => 1,
            'use_on_map_page' => 1,
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		5 => array (
			'single_name' => 'Fuel type',
			'plural_name' => 'Fuel types',
			'slug' => 'fuel',
			'font' => 'stm-icon-fuel',
			'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => 1,
            'use_on_single_car_page' => 1,
            'use_on_map_page' => '',
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		6 => array (
			'single_name' => 'Engine',
			'plural_name' => 'Engines',
			'slug' => 'engine',
			'font' => 'stm-icon-engine_fill',
			'numeric' => 1,
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => 1,
            'use_on_single_car_page' => 1,
            'use_on_map_page' => 1,
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		7 => array (
			'single_name' => 'Year',
			'plural_name' => 'Years',
			'slug' => 'ca-year',
			'font' => 'stm-icon-road',
			'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => 1,
            'use_on_map_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		8 => array (
			'single_name' => 'Price',
			'plural_name' => 'Prices',
			'slug' => 'price',
			'font' => '',
            'numeric' => 1,
            'use_on_single_listing_page' => 1,
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => 1,
            'use_on_single_car_page' => 1,
            'use_on_map_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => 1,
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		9 => array (
			'single_name' => 'Fuel consumption',
			'plural_name' => 'Fuel consumptions',
			'slug' => 'fuel-consumption',
			'font' => 'stm-icon-fuel',
			'numeric' => 1,
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => '',
            'use_on_map_page' => '',
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		10 => array (
			'single_name' => 'Transmission',
			'plural_name' => 'Transmission',
			'slug' => 'transmission',
			'font' => 'stm-icon-transmission_fill',
			'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => 1,
            'use_on_single_car_page' => 1,
            'use_on_map_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => 1,
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		11 => array (
			'single_name' => 'Drive',
			'plural_name' => 'Drives',
			'slug' => 'drive',
			'font' => 'stm-icon-drive_2',
			'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => 1,
            'use_on_single_car_page' => 1,
            'use_on_map_page' => '',
            'use_on_car_filter' => '',
            'use_on_car_modern_filter' => 1,
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		12 => array (
			'single_name' => 'Exterior Color',
			'plural_name' => 'Exterior Colors',
			'slug' => 'exterior-color',
			'font' => '',
            'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => 1,
            'use_on_map_page' => '',
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		13 => array (
			'single_name' => 'Interior Color',
			'plural_name' => 'Interior Colors',
			'slug' => 'interior-color',
			'font' => '',
            'numeric' => '',
            'use_on_single_listing_page' => '',
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => 1,
            'use_on_map_page' => '',
            'use_on_car_filter' => 1,
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
        ),
		14 => array (
			'single_name' => 'Industry',
			'plural_name' => 'Industry',
			'slug' => 'industry',
			'font' => '',
            'numeric' => '',
            'number_field_affix' => '',
            'slider' => '',
            'slider_step' => 10,
            'use_on_car_listing_page' => 1,
            'use_on_car_archive_listing_page' => 1,
            'use_on_single_car_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_tabs' => '',
            'use_on_car_modern_filter' => 1,
            'use_on_car_modern_filter_view_images' => 1,
            'use_on_car_filter_links' => '',
            'filter_links_default_expanded' => 'open',
			'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'use_on_single_listing_page' => '',
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'listing_rows_numbers_default_expanded' => 'open',
			'show_in_admin_column' => '',
        ),
		15 => array (
			'single_name' => 'Hours',
			'plural_name' => 'Hours',
			'slug' => 'hours',
			'font' => '',
            'numeric' => 1,
            'number_field_affix' => '',
            'slider' => '',
            'slider_step' => 10,
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_tabs' => '',
            'use_on_car_modern_filter' => 1,
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
            'filter_links_default_expanded' => 'open',
			'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'use_on_single_listing_page' => '',
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'listing_rows_numbers_default_expanded' => 'open',
			'show_in_admin_column' => '',
        ),
		16 => array (
			'single_name' => 'Listing Type',
			'plural_name' => 'Listing Type',
			'slug' => 'listing-type',
			'font' => '',
            'numeric' => '',
            'number_field_affix' => '',
            'slider' => '',
            'slider_step' => 10,
            'use_on_car_listing_page' => '',
            'use_on_car_archive_listing_page' => '',
            'use_on_single_car_page' => 1,
            'use_on_car_filter' => 1,
            'use_on_tabs' => '',
            'use_on_car_modern_filter' => '',
            'use_on_car_modern_filter_view_images' => '',
            'use_on_car_filter_links' => '',
            'filter_links_default_expanded' => 'open',
			'use_in_footer_search' => '',
            'use_on_directory_filter_title' => '',
            'use_on_single_listing_page' => '',
            'listing_taxonomy_parent' => '',
            'listing_rows_numbers_enable' => '',
            'listing_rows_numbers' => '',
            'enable_checkbox_button' => '',
            'listing_rows_numbers_default_expanded' => 'open',
			'show_in_admin_column' => ''
        )

	);

	update_option( 'stm_vehicle_listing_options', $stm_listings_update_options );
}

function stm_importer_create_taxonomy() {

	$prodAtts = array(
		"pa_seats" => array(
			array(
				"tagName" => "14 Persons",
				"slugName" => "Seats"),
			array(
				"tagName" => "2 Persons",
				"slugName" => "Seats"),
			array(
				"tagName" => "4 Persons",
				"slugName" => "Seats"),
			array(
				"tagName" => "5 Persons",
				"slugName" => "Seats"),
			array(
				"tagName" => "7 Persons",
				"slugName" => "Seats")
		),
		"pa_vehicle-class" => array(
			array(
				"tagName" => "7+ Persons",
				"slugName" => "Vehicle class"),
			array(
				"tagName" => "Economic",
				"slugName" => "Vehicle class"),
			array(
				"tagName" => "Luxury",
				"slugName" => "Vehicle class"),
			array(
				"tagName" => "Middle Class",
				"slugName" => "Vehicle class"),
			array(
				"tagName" => "Sport Car",
				"slugName" => "Vehicle class"),
			array(
				"tagName" => "SUV",
				"slugName" => "Vehicle class"),
			array(
				"tagName" => "Top Gradle",
				"slugName" => "Vehicle class")
		),
		"pa_fuel-type" => array(
			array(
				"tagName" => "Autogas",
				"slugName" => "Fuel type"),
			array(
				"tagName" => "Diesel",
				"slugName" => "Fuel type"),
			array(
				"tagName" => "Electrical",
				"slugName" => "Fuel type"),
			array(
				"tagName" => "Gasoline",
				"slugName" => "Fuel type"),
			array(
				"tagName"=>"Hybrid",
				"slugName"=>"Fuel type")
		),
		"pa_gear-type" => array(
			array(
				"tagName"=> "Automatic",
				"slugName"=>"Gear type"),
			array(
				"tagName"=>"Manual",
				"slugName"=>"Gear type")
		),
		"pa_vehicle-type"=>array(
			array(
				"tagName"=>"Hatchback",
				"slugName"=>"Vehicle type"),
			array(
				"tagName"=>"Sedan",
				"slugName"=>"Vehicle type"),
			array(
				"tagName"=>"Stationwagon",
				"slugName"=>"Vehicle type"),
			array(
				"tagName"=>"SUV",
				"slugName"=>"Vehicle type"),
			array(
				"tagName"=>"VAN",
				"slugName"=>"Vehicle type")
		)
	);


	foreach ($prodAtts as $k => $attr) {
		stm_importer_create_product_attribute($attr[0]['slugName']);

		foreach($attr as $key => $val) {
			wp_create_term($val['tagName'], $k);
		}

	}
}

function stm_importer_create_product_attribute( $label_name ){
	global $wpdb;

	$slug = sanitize_title( $label_name );

	if ( strlen( $slug ) >= 28 ) {
		return new WP_Error( 'invalid_product_attribute_slug_too_long', sprintf( __( 'Name "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce' ), $slug ), array( 'status' => 400 ) );
	} elseif ( wc_check_if_attribute_name_is_reserved( $slug ) ) {
		return new WP_Error( 'invalid_product_attribute_slug_reserved_name', sprintf( __( 'Name "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce' ), $slug ), array( 'status' => 400 ) );
	} elseif ( taxonomy_exists( wc_attribute_taxonomy_name( $label_name ) ) ) {
		return new WP_Error( 'invalid_product_attribute_slug_already_exists', sprintf( __( 'Name "%s" is already in use. Change it, please.', 'woocommerce' ), $label_name ), array( 'status' => 400 ) );
	}

	$data = array(
		'attribute_label'   => $label_name,
		'attribute_name'    => $slug,
		'attribute_type'    => 'select',
		'attribute_orderby' => 'menu_order',
		'attribute_public'  => 0, // Enable archives ==> true (or 1)
	);

	$results = $wpdb->insert( "{$wpdb->prefix}woocommerce_attribute_taxonomies", $data );

	if ( is_wp_error( $results ) ) {
		return new WP_Error( 'cannot_create_attribute', $results->get_error_message(), array( 'status' => 400 ) );
	}

	$id = $wpdb->insert_id;


	if($label_name == 'Gear type' || $label_name == 'Fuel Type' || $label_name == 'Seats') {
		update_term_meta($id, 'stm_cr_main_show_on_car', 'yes');
	}

	do_action('woocommerce_attribute_added', $id, $data);

	wp_schedule_single_event( time(), 'woocommerce_flush_rewrite_rules' );

	delete_transient('wc_attribute_taxonomies');
}

add_action('stm_importer_create_taxonomy', 'stm_importer_create_taxonomy');