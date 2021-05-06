<?php
if ( !function_exists( 'stm_is_use_plugin' ) ) {
    function stm_is_use_plugin( $plug )
    {

        if ( !function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        return in_array( $plug, (array)get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $plug );
    }
}


if(!function_exists('stm_get_header_layout')) {
	function stm_get_header_layout() {

		$selLayout = get_option('stm_motors_chosen_template');

		if(empty($selLayout)) return 'car_dealer';

		$arrHeader = array(
			'service' => 'car_dealer',
			'listing_two' => 'listing',
			'listing_three' => 'listing',
			'listing_four' => 'car_dealer',
		);

		$defaultHeader = (!empty($arrHeader[$selLayout])) ? $arrHeader[$selLayout] : $selLayout;

		/*
		 * aircrafts
		 * boats
		 * car_dealer
		 * car_dealer_two
		 * equipment
		 * listing
		 * listing_five
		 * magazine
		 * motorcycle
		 * car_rental
		 * */

		if(stm_is_listing_six()) return 'listing_five';

		return apply_filters('stm_selected_header', get_theme_mod( 'header_layout', $defaultHeader ));
	}
}

if ( !function_exists( 'stm_is_not_use_plugin' ) ) {
    function stm_is_not_use_plugin( $plug )
    {
        return !stm_is_use_plugin( $plug );
    }
}

if (!function_exists('stm_is_car_dealer')) {
    function stm_is_car_dealer()
    {
        $listing = get_option('stm_motors_chosen_template', 'car_dealer');
        if($listing) {
            if ($listing == 'car_dealer') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_nccurrent_blog_id() == 1) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_listing')) {
    function stm_is_listing()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'listing') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 2) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_listing_two')) {
    function stm_is_listing_two()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'listing_two') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 10) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_listing_three')) {
    function stm_is_listing_three()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'listing_three') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 11) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_listing_four')) {
    function stm_is_listing_four()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'listing_four') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 13) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_listing_five')) {
    function stm_is_listing_five()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'listing_five') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 17) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_listing_six')) {
    function stm_is_listing_six()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'listing_six') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 18) return true;
            else return false;
        }

        return $listing;
    }
}

if(!function_exists('is_listing')) {
    function is_listing($only = array()) {
        if(count($only) > 0) {
            $listing = get_option('stm_motors_chosen_template');

            foreach ($only as $layout) {
                if($layout == $listing) return true;
            }
        } else {
            if(stm_is_listing() || stm_is_listing_two() || stm_is_listing_three() || stm_is_listing_four() || stm_is_listing_five() || stm_is_listing_six()) return true;
        }

        return false;
    }
}

if(!function_exists('is_dealer')) {
    function is_dealer($only = array()) {
        if(count($only) > 0) {
            $listing = get_option('stm_motors_chosen_template');

            foreach ($only as $layout) {
                if($layout == $listing) return true;
            }
        } else {
            if(stm_is_car_dealer() || stm_is_dealer_two()) return true;
        }

        return false;
    }
}

if (!function_exists('stm_is_boats')) {
    function stm_is_boats()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'boats') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 4) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_motorcycle')) {
    function stm_is_motorcycle()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'motorcycle') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 5) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_service')) {
    function stm_is_service()
    {
        $listing = get_option('stm_motors_chosen_template');

        if($listing) {
            if ($listing == 'service') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 3) return true;
            else return false;
        }

        return $listing;
    }
}

if (!function_exists('stm_is_rental')) {
	function stm_is_rental() {
		$listing = get_option('stm_motors_chosen_template');

		if($listing) {
            if ($listing == 'car_rental' || $listing == 'rental_two') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 7) return true;
            else return false;
        }

		return $listing;
	}
}

if (!function_exists('stm_is_rental_two')) {
	function stm_is_rental_two() {
		$listing = get_option('stm_motors_chosen_template');

		return ($listing == 'rental_two') ? true : false;
	}
}

if (!function_exists('stm_is_magazine')) {
	function stm_is_magazine() {
		$listing = get_option('stm_motors_chosen_template');


		if($listing) {
            if ($listing == 'car_magazine') {
                $listing = true;
            } else {
                $listing = false;
            }
        } else {
            if(get_current_blog_id() == 8) return true;
            else return false;
        }

		return $listing;
	}
}

if (!function_exists('stm_is_dealer_two')) {
	function stm_is_dealer_two() {
		$dealer = get_option('stm_motors_chosen_template');


		if($dealer) {
            if ($dealer == 'car_dealer_two') {
                $dealer = true;
            } else {
                $dealer = false;
            }
        } else {
            if(get_current_blog_id() == 9) return true;
            else return false;
        }

		return $dealer;
	}
}

if (!function_exists('stm_is_aircrafts')) {
	function stm_is_aircrafts() {
		$dealer = get_option('stm_motors_chosen_template');


		if($dealer) {
            if ($dealer == 'aircrafts') {
                $dealer = true;
            } else {
                $dealer = false;
            }
        } else {
            if(get_current_blog_id() == 14) return true;
            else return false;
        }

		return $dealer;
	}
}

if (!function_exists('stm_is_auto_parts')) {
	function stm_is_auto_parts() {
		$dealer = get_option('stm_motors_chosen_template');


		if($dealer) {
            if ($dealer == 'auto_parts') {
                $dealer = true;
            } else {
                $dealer = false;
            }
        } else {
            if(get_current_blog_id() == 12) return true;
            else return false;
        }

		return $dealer;
	}
}

if (!function_exists('stm_is_equipment')) {
	function stm_is_equipment() {
		$dealer = get_option('stm_motors_chosen_template');

	    if ($dealer == 'equipment') {
			$dealer = true;
		} else {
			$dealer = false;
		}

        return $dealer;
	}
}


if (!function_exists('stm_get_current_layout')) {
    function stm_get_current_layout()
    {
        $layout = get_option('stm_motors_chosen_template');

        if (empty($layout)) {
            $layout = 'car_dealer';
        }

        return $layout;
    }
}

if(!function_exists('stm_get_headers_list')) {
	function stm_get_headers_list() {
		$headers = array(
			'car_dealer' => esc_html__('Dealer', 'motors'),
			'car_dealer_two' => esc_html__('Dealer Two', 'motors'),
			'listing' => esc_html__('Classified', 'motors'),
			'listing_five' => esc_html__('Classified Five', 'motors'),
			'boats' => esc_html__('Boats', 'motors'),
			'motorcycle' => esc_html__('Motorcycle', 'motors'),
			'car_rental' => esc_html__('Rental', 'motors'),
			'car_magazine' => esc_html__('Magazine', 'motors'),
			'aircrafts' => esc_html__('Aircrafts', 'motors'),
			'equipment' => esc_html__('Equipment', 'motors'),
		);

		return $headers;
	}
}

if(!function_exists('stm_get_default_header')) {
	function stm_get_default_header() {
		$header = 'car_dealer';

		if ( is_listing( array( 'listing', 'listing_two', 'listing_three' ) ) ) {
			$header = 'listing';
		} elseif ( stm_is_listing_five() || stm_is_listing_six() ) {
			$header = 'listing_five';
		} elseif ( stm_get_current_layout() == 'boats') {
			$header = 'boats';
		} elseif ( stm_is_motorcycle()) {
			$header = 'motorcycle';
		} elseif ( stm_is_rental() ) {
			$header = 'car_rental';
		} elseif ( stm_is_magazine() ) {
			$header = 'car_magazine';
		} elseif ( stm_is_dealer_two() ) {
			$header = 'car_dealer_two';
		} elseif ( stm_is_aircrafts() ) {
			$header = 'aircrafts';
		} elseif ( stm_is_equipment() ) {
			$header = 'equipment';
		}

		return $header;
	}
}

$show_on_listing = false;
$show_on_dealer = true;

if (is_listing()) {
    $margin_top = 17;
    $show_on_listing = true;
    $show_on_dealer = false;
    $footer_bg = '#153e4d';
} else {
    $margin_top = 0;
    $footer_bg = '#232628';
}

$socials = array(
    'facebook' => esc_html__('Facebook', 'motors'),
    'twitter' => esc_html__('Twitter', 'motors'),
    'vk' => esc_html__('VK', 'motors'),
    'instagram' => esc_html__('Instagram', 'motors'),
    'behance' => esc_html__('Behance', 'motors'),
    'dribbble' => esc_html__('Dribbble', 'motors'),
    'flickr' => esc_html__('Flickr', 'motors'),
    'git' => esc_html__('Git', 'motors'),
    'linkedin' => esc_html__('Linkedin', 'motors'),
    'pinterest' => esc_html__('Pinterest', 'motors'),
    'yahoo' => esc_html__('Yahoo', 'motors'),
    'delicious' => esc_html__('Delicious', 'motors'),
    'dropbox' => esc_html__('Dropbox', 'motors'),
    'reddit' => esc_html__('Reddit', 'motors'),
    'soundcloud' => esc_html__('Soundcloud', 'motors'),
    'google' => esc_html__('Google', 'motors'),
    'google-plus' => esc_html__('Google +', 'motors'),
    'skype' => esc_html__('Skype', 'motors'),
    'youtube' => esc_html__('Youtube', 'motors'),
    'youtube-play' => esc_html__('Youtube Play', 'motors'),
    'tumblr' => esc_html__('Tumblr', 'motors'),
    'whatsapp' => esc_html__('Whatsapp', 'motors'),
);

$positions = array(
    'left' => esc_html__('Left', 'motors'),
    'right' => esc_html__('Right', 'motors'),
);

$sortBy = array(
    'date_high' => esc_html__('Date: newest first', 'motors'),
    'date_low' => esc_html__('Date: oldest first', 'motors'),
    'price_low' => esc_html__('Price: lower first', 'motors'),
    'price_high' => esc_html__('Price: highest first', 'motors'),
    'mileage_low' => esc_html__('Mileage: lowest first', 'motors'),
    'mileage_high' => esc_html__('Mileage: highest first', 'motors'),
);

$currencyTo = array();

if(get_theme_mod("currency_list")) {
    $currList = json_decode(get_theme_mod("currency_list"));
    $currencyTo = array("currency" => explode(",", $currList->currency), "symbol" => explode(",", $currList->symbol), "to" => explode(",", $currList->to));
}

// Get sidebar posts
$sidebars = array(
    'no_sidebar' => esc_html__('Without sidebar', 'motors'),
    'default' => esc_html__('Primary sidebar', 'motors'),
);

$query = get_posts(array('post_type' => 'sidebar', 'posts_per_page' => -1));

if ($query) {
    foreach ($query as $post) {
        $sidebars[$post->ID] = get_the_title($post->ID);
    }
}

$customizerSettings = array(
    'site_settings' => array(
        'title' => esc_html__('Site Settings', 'motors'),
        'priority' => 10
    ),
	'header' => array(
		'title' => esc_html__('Header', 'motors'),
		'priority' => 20
	),
    'footer' => array(
        'title' => esc_html__('Footer', 'motors'),
        'priority' => 50
    )
);

if(!stm_is_auto_parts() && !stm_is_rental()) {
    $customizerSettings = array(
        'site_settings' => array(
            'title' => esc_html__('Site Settings', 'motors'),
            'priority' => 10
        ),
        'header' => array(
            'title' => esc_html__('Header', 'motors'),
            'priority' => 20
        ),
        'listing' => array(
            'title' => esc_html__('Listing', 'motors'),
            'priority' => 30
        ),
        'footer' => array(
            'title' => esc_html__('Footer', 'motors'),
            'priority' => 50
        )
    );
}

if(defined('STM_MOTORS_CLASSIFIED_FIVE')) {
	unset($customizerSettings['listing']);
}



STM_Customizer::setPanels($customizerSettings);

if(!stm_is_auto_parts()) {
    STM_Customizer::setSection('title_tagline', array(
        'title' => esc_html__('Logo &amp; Title', 'motors'),
        'panel' => 'site_settings',
        'priority' => 200,
        'fields' => array(
            'logo' => array(
                'label' => esc_html__('Logo', 'motors'),
                'type' => 'image',
                'default' => get_template_directory_uri() . '/assets/images/tmp/logo.png'
            ),
            'logo_width' => array(
                'label' => esc_html__('Logo Width (px)', 'motors'),
                'type' => 'text'
            ),
			'logo_margin_top' => array(
				'label' => esc_html__('Logo margin top', 'motors'),
				'type' => 'text',
			),
            'menu_icon_top_margin' => array(
                'label' => esc_html__('Menu & Icons area margin top (px)', 'motors'),
                'type' => 'text',
                'default' => '0'
            ),
            'menu_top_margin' => array(
                'label' => esc_html__('Menu margin top (px)', 'motors'),
                'type' => 'text',
                'default' => $margin_top
            ),
            'logo_break_2' => array(
                'type' => 'stm-separator',
            ),
            'logo_font_family' => array(
                'label' => esc_html__('Text Logo Font Family', 'motors'),
                'type' => 'stm-font-family',
                'description' => esc_html__('If you dont have logo, you can customize your brand name', 'motors'),
                'output' => '#header .blogname h1'
            ),
            'logo_font_size' => array(
                'label' => esc_html__('Text Logo Font Size', 'motors'),
                'type' => 'stm-attr',
                'mode' => 'font-size',
                'units' => 'px',
                'min' => '0',
                'max' => '30',
                'output' => '#header .blogname h1'
            ),
            'logo_color' => array(
                'label' => esc_html__('Text Logo Color', 'motors'),
                'type' => 'color',
                'output' => array('color' => '#header .blogname h1'),
                'transport' => 'postMessage',
                'default' => '#fff'
            ),
        )
    ));

    STM_Customizer::setSection('google_api_settings', array(
        'title' => esc_html__('Google Api Settings', 'motors'),
        'panel' => 'site_settings',
        'priority' => 300,
        'fields' => array(
            'google_api_key' => array(
                'label' => esc_html__('Google API Key', 'motors'),
                'type' => 'text',
                'description' => esc_html__('Enter here the secret api key you have created on Google APIs. You can enable MAP API in Google APIs > Google Maps APIs > Google Maps JavaScript API.', 'motors')
            ),
        )
    ));
}

$site_styles = array();

if (!stm_is_boats()) {
    if (stm_is_dealer_two() or stm_is_motorcycle() or stm_is_rental() or stm_is_rental_two() || stm_is_magazine() || is_listing(array('listing_two', 'listing_three', 'listing_four', 'listing_five', 'listing_six')) || stm_is_equipment()) {
        $site_styles['site_style'] = array(
            'label' => esc_html__('Style', 'motors'),
            'type' => 'stm-select',
            'choices' => array(
                'site_style_default' => esc_html__('Default', 'motors'),
                'site_style_custom' => esc_html__('Custom Colors', 'motors'),
            ),
            'default' => 'site_style_default'
        );
    } else {
        $site_styles['site_style'] = array(
            'label' => esc_html__('Style', 'motors'),
            'type' => 'stm-select',
            'choices' => array(
                'site_style_default' => esc_html__('Default', 'motors'),
                'site_style_blue' => esc_html__('Blue', 'motors'),
                'site_style_light_blue' => esc_html__('Light Blue', 'motors'),
                'site_style_orange' => esc_html__('Green', 'motors'),
                'site_style_red' => esc_html__('Red', 'motors'),
                'site_style_yellow' => esc_html__('Yellow', 'motors'),
                'site_style_custom' => esc_html__('Custom Colors', 'motors'),
            ),
            'default' => 'site_style_default'
        );
    }
} else {
    $site_styles['site_style'] = array(
        'label' => esc_html__('Style', 'motors'),
        'type' => 'stm-select',
        'choices' => array(
            'site_style_default' => esc_html__('Default', 'motors'),
            'site_style_blue' => esc_html__('Corall', 'motors'),
            'site_style_light_blue' => esc_html__('Turquoise', 'motors'),
            'site_style_orange' => esc_html__('Green', 'motors'),
            'site_style_red' => esc_html__('Red', 'motors'),
            'site_style_custom' => esc_html__('Custom Colors', 'motors'),
        ),
        'default' => 'site_style_default'
    );
}

if (stm_is_motorcycle() || stm_is_equipment()) {
    $site_styles['site_style_base_color'] = array(
        'label' => esc_html__('Custom Base Color', 'motors'),
        'type' => 'color',
        'default' => '#df1d1d'
    );

    $site_styles['site_style_secondary_color'] = array(
        'label' => esc_html__('Custom Secondary Color', 'motors'),
        'type' => 'color',
        'default' => '#2f3c40'
    );
} else {
    if (stm_is_boats()) {
        $site_styles['site_style_base_color'] = array(
            'label' => esc_html__('Custom Base Color', 'motors'),
            'type' => 'color',
            'default' => '#31a3c6'
        );

        $site_styles['site_style_secondary_color'] = array(
            'label' => esc_html__('Custom Secondary Color', 'motors'),
            'type' => 'color',
            'default' => '#ceac61'
        );

        $site_styles['site_style_base_color_listing'] = array(
            'label' => esc_html__('Custom Third Color', 'motors'),
            'type' => 'color',
            'default' => '#002568'
        );

    } else {
        $site_styles['site_style_base_color'] = array(
            'label' => esc_html__('Custom Base Car Dealer Color', 'motors'),
            'type' => 'color',
            'default' => '#cc6119'
        );

        $site_styles['site_style_secondary_color'] = array(
            'label' => esc_html__('Custom Secondary Car Dealer Color', 'motors'),
            'type' => 'color',
            'default' => '#6c98e1'
        );

        $site_styles['site_style_base_color_listing'] = array(
            'label' => esc_html__('Custom Base Listing Color', 'motors'),
            'type' => 'color',
            'default' => '#1bc744'
        );

        $site_styles['site_style_secondary_color_listing'] = array(
            'label' => esc_html__('Custom Secondary Listing Color', 'motors'),
            'type' => 'color',
            'default' => '#153e4d'
        );
    }
}

$site_styles_default = array(
    'site_boxed' => array(
        'label' => esc_html__('Enable Boxed Layout', 'motors'),
        'type' => 'stm-checkbox',
        'default' => false
    ),
    'bg_image' => array(
        'label' => esc_html__('Background Image', 'motors'),
        'type' => 'stm-bg',
        'choices' => array(
            'stm-background-customizer-box_img_5' => 'box_img_5_preview.png',
            'stm-background-customizer-box_img_1' => 'box_img_1_preview.png',
            'stm-background-customizer-box_img_2' => 'box_img_2_preview.png',
            'stm-background-customizer-box_img_3' => 'box_img_3_preview.jpg',
            'stm-background-customizer-box_img_4' => 'box_img_4_preview.jpg',
        )
    ),
    'custom_bg_image' => array(
        'label' => esc_html__('Custom Bg Image', 'motors'),
        'type' => 'image'
    ),

    'frontend_customizer' => array(
        'label' => esc_html__('Frontend Customizer', 'motors'),
        'type' => 'stm-checkbox',
        'default' => false
    ),
    'enable_preloader' => array(
        'label' => esc_html__('Enable Preloader', 'motors'),
        'type' => 'stm-checkbox',
        'default' => false
    ),
    'smooth_scroll' => array(
        'label' => esc_html__('Site smooth scroll', 'motors'),
        'type' => 'stm-checkbox',
        'default' => false
    ),
);

if (stm_is_motorcycle() or stm_is_rental() || stm_is_equipment()) {
    $default_site_bg = '#0e1315';
    if(stm_is_rental()) {
        $default_site_bg = 'eeeeee';
    }
    $site_styles['site_bg_color'] = array(
        'label' => esc_html__('Site Background Color', 'motors'),
        'type' => 'color',
        'default' => $default_site_bg
    );
}

if(stm_is_auto_parts()) {
    $site_styles = array();

    $site_styles['site_style'] = array(
        'label' => esc_html__('Style', 'motors'),
        'type' => 'stm-select',
        'choices' => array(
            'site_style_default' => esc_html__('Default', 'motors'),
            'site_style_custom' => esc_html__('Custom Colors', 'motors'),
        ),
        'default' => 'site_style_default'
    );
    $site_styles['site_style_base_color'] = array(
        'label' => esc_html__('Custom Base Color', 'motors'),
        'type' => 'color',
        'default' => '#ffcc12'
    );

    $site_styles['site_style_secondary_color'] = array(
        'label' => esc_html__('Custom Secondary Color', 'motors'),
        'type' => 'color',
        'default' => '#6f9ae2'
    );
}

$site_styles = array_merge($site_styles, $site_styles_default);


STM_Customizer::setSection('site_style', array(
    'title' => esc_html__('Style', 'motors'),
    'panel' => 'site_settings',
    'priority' => 220,
    'fields' => $site_styles
));

//Typography
STM_Customizer::setSection('typography', array(
    'title' => esc_html__('Typography', 'motors'),
    'panel' => 'site_settings',
    'priority' => 230,
    'fields' => array(
        'typography_body_font_family' => array(
            'label' => esc_html__('Body Font Family', 'motors'),
            'type' => 'stm-font-family',
            'output' => 'body, .normal_font, #top-bar, #top-bar a,.icon-box .icon-text .content',
        ),
        'typography_body_font_size' => array(
            'label' => esc_html__('Body Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '30',
            'output' => 'body, .normal_font',
            'default' => '14'
        ),
        'typography_body_line_height' => array(
            'label' => esc_html__('Body Line Height', 'motors'),
            'type' => 'stm-attr',
            'units' => 'px',
            'mode' => 'line-height',
            'output' => 'body, .normal_font',
            'default' => '22'
        ),
        'typography_body_color' => array(
            'label' => esc_html__('Body Font Color', 'motors'),
            'type' => 'color',
            'output' => array('color' => 'body, .normal_font'),
            'transport' => 'postMessage',
            'default' => '#232628'
        ),
        'typography_break_1' => array(
            'type' => 'stm-separator',
        ),
        'typography_menu_font_family' => array(
            'label' => esc_html__('Menu Text Font Family', 'motors'),
            'type' => 'stm-font-family',
            'output' => '.header-menu li a, 
            .listing-menu li a,
             	.header-listing .listing-menu li a,
              	.stm-navigation ul li a,
               	.widget_nav_menu li a,
               	.stm-layout-header-listing_five .header-menu li a
               ',
        ),
        'typography_menu_font_size' => array(
            'label' => esc_html__('Menu Text Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '30',
            'output' => '.header-menu li a, .listing-menu li a, .header-listing .listing-menu li a',
            'default' => '13'
        ),
        'typography_menu_color' => array(
            'label' => esc_html__('Menu Text Color', 'motors'),
            'type' => 'color',
            'output' => array('color' => '
            	#header .header-menu > li > a, 
            	#header .listing-menu > li > a, 
            	#header .header-listing .listing-menu > li > a,
            	#wrapper #header .header-inner-content .listing-service-right .listing-menu > li > a,
            	#wrapper #stm-boats-header #header .header-inner-content .listing-service-right .listing-menu > li > a,
            	#wrapper #header .header-magazine .container .magazine-service-right ul.magazine-menu > li > a
            	'),
            'transport' => 'postMessage',
            'default' => '#232628'
        ),
        'typography_break_2' => array(
            'type' => 'stm-separator',
        ),
        'typography_heading_font_family' => array(
            'label' => esc_html__('Headings Font Family', 'motors'),
            'type' => 'stm-font-family',
            'output' => 'h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6,.heading-font,.button,.event-head,
			.load-more-btn,.vc_tta-panel-title,.page-numbers li > a,.page-numbers li > span,
			.vc_tta-tabs .vc_tta-tabs-container .vc_tta-tabs-list .vc_tta-tab a span,.stm_auto_loan_calculator input,
			.post-content blockquote,.contact-us-label,.stm-shop-sidebar-area .widget.widget_product_categories > ul,
			#main .stm-shop-sidebar-area .widget .product_list_widget li .product-title,
			#main .stm-shop-sidebar-area .widget .product_list_widget li a,
			.woocommerce ul.products li.product .onsale,
			.woocommerce div.product p.price, .woocommerce div.product span.price,
			.woocommerce div.product .woocommerce-tabs ul.tabs li a,
			.woocommerce table.shop_attributes td,
			.woocommerce table.shop_table td.product-name > a,
			.woocommerce-cart table.cart td.product-price,
			.woocommerce-cart table.cart td.product-subtotal,
			.stm-list-style-counter li:before,
			.ab-booking-form .ab-nav-steps .ab-btn,
			body.stm-template-motorcycle .stm_motorcycle-header .stm_mc-main.header-main .stm_top-menu li .sub-menu a,
			.wpb_tour_tabs_wrapper.ui-tabs ul.wpb_tabs_nav > li > a'
        ),
        'typography_heading_color' => array(
            'label' => esc_html__('Headings Color', 'motors'),
            'type' => 'color',
            'output' => array('color' => 'h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6,.heading-font,.button,.load-more-btn,.vc_tta-panel-title,.page-numbers li > a,.page-numbers li > span,.vc_tta-tabs .vc_tta-tabs-container .vc_tta-tabs-list .vc_tta-tab a span,.stm_auto_loan_calculator input'),
            'transport' => 'postMessage',
            'default' => '#232628'
        ),
        'typography_h1_font_size' => array(
            'label' => esc_html__('H1 Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '50',
            'output' => 'h1, .h1',
            'default' => '50'
        ),
        'typography_h2_font_size' => array(
            'label' => esc_html__('H2 Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '50',
            'output' => 'h2, .h2',
            'default' => '36'
        ),
        'typography_h3_font_size' => array(
            'label' => esc_html__('H3 Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '50',
            'output' => 'h3, .h3',
            'default' => '26'
        ),
        'typography_h4_font_size' => array(
            'label' => esc_html__('H4 Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '50',
            'output' => 'h4, .h4',
            'default' => '16'
        ),
        'typography_h5_font_size' => array(
            'label' => esc_html__('H5 Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '50',
            'output' => 'h5, .h5',
            'default' => '14'
        ),
        'typography_h6_font_size' => array(
            'label' => esc_html__('H6 Font Size', 'motors'),
            'type' => 'stm-attr',
            'mode' => 'font-size',
            'units' => 'px',
            'min' => '0',
            'max' => '50',
            'output' => 'h6, .h6',
            'default' => '12'
        ),
    )
));

if(!stm_is_auto_parts()) {
    STM_Customizer::setSection('static_front_page', array(
        'title' => esc_html__('Static Front Page', 'motors'),
        'panel' => 'site_settings',
        'priority' => 190,
    ));
}

$default_color = '#232628';
if (stm_is_boats()) {
    $default_color = '#002568';
}

$header_layout_settings = array(
    'title' => esc_html__('Main settings', 'motors'),
    'panel' => 'header',
    'fields' => array(
		'header_layout' => array(
			'label' => esc_html__('Header Layout', 'motors'),
			'type' => 'stm-select',
			'default' => stm_get_default_header(),
			'choices' => stm_get_headers_list()
		),
        'header_bg_color' => array(
            'label' => esc_html__('Header Background Color', 'motors'),
            'type' => 'color',
            'output' => array('background-color' => '
            					#header .header-main, 
            					#header .stm_motorcycle-header .stm_mc-main.header-main, 
            					.home #header .header-main-listing-five.stm-fixed, 
            					.stm-template-listing #header .header-listing.listing-nontransparent-header, 
            					.stm-layout-header-listing .header-listing.listing-nontransparent-header,
            					#header .header-listing:after, 
            					#header .header-listing.stm-fixed,
            					.header-service.header-service-sticky, 
            					.stm-template-boats .header-listing.stm-fixed,
            					#wrapper #stm-boats-header #header .header-listing-boats.stm-fixed,
            					#wrapper #stm-boats-header #header:after,
            					.stm-template-car_dealer_two.no_margin #wrapper #stm-boats-header #header:after,
            					.stm-template-aircrafts:not(.home):not(.stm-inventory-page):not(.single-listings) #wrapper #header,
            					.stm-layout-header-aircrafts #header .header-listing,
            					.stm-layout-header-equipment #header .header-listing,
            					.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #header:after,
            					.stm-template-rental_two #wrapper .header-main'

				),
            'transport' => 'postMessage',
            'default' => $default_color
        ),
        'header_text_color' => array(
            'label' => esc_html__('Header Text Color', 'motors'),
            'type' => 'color',
            'output' => array('color' => '
            	#wrapper #header .header-main .heading-font,
            	#wrapper #header .header-main .heading-font a,
            	#wrapper #stm-boats-header #header .header-inner-content .listing-right-actions .heading-font,
            	#wrapper #header .header-inner-content .listing-right-actions .head-phone-wrap .heading-font,
            	#wrapper #header .header-magazine .container .magazine-service-right .magazine-right-actions .pull-right a.lOffer-compare,
            	#wrapper #header .stm_motorcycle-header .stm_mc-main.header-main .header-main-phone a,
            	.stm-layout-header-listing_five #wrapper .lOffer-compare,
            	.stm-layout-header-listing_five #wrapper .header-main .stm-header-right .head-phone-wrap .ph-title,
            	.stm-layout-header-listing_five #wrapper .header-main .stm-header-right .head-phone-wrap .phone
            	'),
            'transport' => 'postMessage',
            'default' => '#ffffff'
        ),
        'header_icon_color' => array(
            'label' => esc_html__('Header Icon Color', 'motors'),
            'type' => 'color',
            'output' => array('color' => '
            	#wrapper #header .pull-right i,
            	#header .stm_motorcycle-header .stm_mc-main.header-main .header-main-socs ul li a i, 
            	#wrapper #header .header-inner-content .listing-service-right .listing-right-actions i,
            	#wrapper #header .header-main .stm-header-right i
            	'),
            'transport' => 'postMessage',
            'default' => '#ffffff'
        ),
        //Main phone
        'header_main_phone_label' => array(
            'label' => esc_html__('Main phone label', 'motors'),
            'type' => 'text',
            'default' => 'Sales'
        ),
        'header_main_phone' => array(
            'label' => esc_html__('Main phone', 'motors'),
            'type' => 'text',
            'default' => '878-9671-4455'
        ),
		'header_rental_header_phone' => array(
			'label' => esc_html__('Header Phone', 'motors'),
			'type' => 'text',
			'default' => '709-458-2140'
		),
        'header_layout_break_1' => array(
            'type' => 'stm-separator',
        ),
        //Secondary phone 1
        'header_secondary_phone_label_1' => array(
            'label' => esc_html__('Secondary phone label 1', 'motors'),
            'type' => 'text',
            'default' => 'Service'
        ),
        'header_secondary_phone_1' => array(
            'label' => esc_html__('Secondary phone 1', 'motors'),
            'type' => 'text',
            'default' => '878-3971-3223'
        ),
        //Secondary phone 2
        'header_secondary_phone_label_2' => array(
            'label' => esc_html__('Secondary phone label 2', 'motors'),
            'type' => 'text',
            'default' => 'Parts'
        ),
        'header_secondary_phone_2' => array(
            'label' => esc_html__('Secondary phone 2', 'motors'),
            'type' => 'text',
            'default' => '878-0910-0770'
        ),
        'header_layout_break_2' => array(
            'type' => 'stm-separator',
        ),
        //Address
        'header_address' => array(
            'label' => esc_html__('Address', 'motors'),
            'type' => 'text',
            'default' => '1840 E Garvey Ave South West Covina, CA 91791'
        ),
        'header_address_url' => array(
            'label' => esc_html__('Google Map Address URL', 'motors'),
            'type' => 'text',
        ),
        'header_break_1' => array(
            'type' => 'stm-separator',
        ),
        'header_sticky' => array(
            'label' => esc_html__('Sticky', 'motors'),
            'type' => 'stm-checkbox',
            'sanitize_callback' => 'sanitize_checkbox',
            'default' => false
        ),
        'header_compare_show' => array(
            'label' => esc_html__('Show compare', 'motors'),
            'type' => 'stm-checkbox',
            'sanitize_callback' => 'sanitize_checkbox',
            'default' => false
        ),
		'header_cart_show' => array(//!stm_is_dealer_two() && !is_listing()
			'label' => esc_html__('Show cart (Woocommerce needed)', 'motors'),
			'type' => 'stm-checkbox',
			'sanitize_callback' => 'sanitize_checkbox',
			'default' => false
		),
		'header_show_profile' => array(
			'label' => esc_html__('Show Profile', 'motors'),
			'type' => 'stm-checkbox',
			'sanitize_callback' => 'sanitize_checkbox',
			'default' => false
		),
		'header_layout_break_listing' => array(//is_listing
			'type' => 'stm-separator',
		),
		'header_listing_layout_image_bg' => array(
			'label' => esc_html__('Listing layout header image for non-transparent option', 'motors'),
			'type' => 'image'
		),
		'header_listing_btn_text' => array(
			'label' => esc_html__('Button label in header', 'motors'),
			'type' => 'text',
			'default' => esc_html__('Add your item', 'motors')
		),
		'header_listing_btn_link' => array(
			'label' => esc_html__('Button link in header', 'motors'),
			'type' => 'text',
			'default' => esc_attr('/add-car')
		),
    )
);

if(get_theme_mod( 'header_layout', 'car_dealer' ) == 'car_dealer') {
	unset($header_layout_settings['fields']['header_icon_color']);
}

if(get_theme_mod( 'header_layout', 'car_dealer' ) != 'car_dealer'
	&& get_theme_mod( 'header_layout', 'car_dealer' ) != 'equipment'
	&& get_theme_mod( 'header_layout', 'car_dealer' ) != 'listing_five'
	&& get_theme_mod( 'header_layout', 'car_dealer' ) != 'motorcycle' ) {

	unset($header_layout_settings['fields']['header_main_phone_label']);
	unset($header_layout_settings['fields']['header_main_phone']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_2']);
	unset($header_layout_settings['fields']['header_secondary_phone_2']);
	unset($header_layout_settings['fields']['header_address']);
	unset($header_layout_settings['fields']['header_address_url']);
	unset($header_layout_settings['fields']['header_layout_break_listing']);
	unset($header_layout_settings['fields']['header_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_2']);
}

if(stm_is_listing_five()) {
	unset($header_layout_settings['fields']['header_main_phone_label']);
	unset($header_layout_settings['fields']['header_main_phone']);
}
if(get_theme_mod( 'header_layout', 'car_dealer' ) == 'equipment' || get_theme_mod( 'header_layout', 'car_dealer' ) == 'listing_five') {
	unset($header_layout_settings['fields']['header_secondary_phone_label_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_2']);
	unset($header_layout_settings['fields']['header_secondary_phone_2']);
	unset($header_layout_settings['fields']['header_address']);
	unset($header_layout_settings['fields']['header_address_url']);
	unset($header_layout_settings['fields']['header_layout_break_listing']);
	unset($header_layout_settings['fields']['header_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_2']);
}

if(get_theme_mod( 'header_layout', 'car_dealer' ) == 'motorcycle' ) {
	unset($header_layout_settings['fields']['header_main_phone_label']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_2']);
	unset($header_layout_settings['fields']['header_secondary_phone_2']);
	unset($header_layout_settings['fields']['header_address']);
	unset($header_layout_settings['fields']['header_address_url']);
	unset($header_layout_settings['fields']['header_layout_break_listing']);
	unset($header_layout_settings['fields']['header_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_2']);
}

if(get_theme_mod( 'header_layout', 'car_dealer' ) != 'car_rental') {
	unset($header_layout_settings['fields']['header_rental_header_phone']);
}

if(get_theme_mod( 'header_layout', 'car_dealer' ) == 'car_rental') {
	unset($header_layout_settings['fields']['header_cart_show']);
	unset($header_layout_settings['fields']['header_compare_show']);
}

if(!is_listing()) {
	unset($header_layout_settings['fields']['header_show_profile']);
	unset($header_layout_settings['fields']['header_listing_btn_text']);
	unset($header_layout_settings['fields']['header_listing_btn_link']);
}

if(stm_is_service()) {
	unset($header_layout_settings['fields']['header_layout']);
	unset($header_layout_settings['fields']['header_main_phone_label']);
	unset($header_layout_settings['fields']['header_main_phone']);
	unset($header_layout_settings['fields']['header_rental_header_phone']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_2']);
	unset($header_layout_settings['fields']['header_secondary_phone_2']);
	unset($header_layout_settings['fields']['header_address']);
	unset($header_layout_settings['fields']['header_address_url']);
	unset($header_layout_settings['fields']['header_compare_show']);
	unset($header_layout_settings['fields']['header_cart_show']);
	unset($header_layout_settings['fields']['header_show_profile']);
	unset($header_layout_settings['fields']['header_layout_break_listing']);
	unset($header_layout_settings['fields']['header_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_1']);
	unset($header_layout_settings['fields']['header_layout_break_2']);
	unset($header_layout_settings['fields']['header_listing_layout_image_bg']);
	unset($header_layout_settings['fields']['header_listing_btn_text']);
	unset($header_layout_settings['fields']['header_listing_btn_link']);
}

if(defined('STM_MOTORS_CLASSIFIED_FIVE')) {
	unset($header_layout_settings['fields']['header_address']);
	//unset($header_layout_settings['fields']['header_address_url']);
	unset($header_layout_settings['fields']['header_main_phone_label']);
	unset($header_layout_settings['fields']['header_main_phone']);
	unset($header_layout_settings['fields']['header_layout_break_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_1']);
	unset($header_layout_settings['fields']['header_secondary_phone_1']);
	unset($header_layout_settings['fields']['header_layout_break_2']);
	unset($header_layout_settings['fields']['header_secondary_phone_label_2']);
	unset($header_layout_settings['fields']['header_secondary_phone_2']);
	unset($header_layout_settings['fields']['header_cart_show']);
}

if(defined('STM_MOTORS_CLASSIFIED_SIX')) {
	unset($header_layout_settings['fields']['header_layout']);
}

if(stm_is_rental_two()){
	$header_layout_settings = array(
		'title' => esc_html__('Main settings', 'motors'),
		'panel' => 'header',
		'fields' => array(
			'header_bg_color' => array(
				'label' => esc_html__('Header Background Color', 'motors'),
				'type' => 'color',
				'output' => array('background-color' => '.header-main, .stm-template-rental_two #wrapper .header-main'),
				'transport' => 'postMessage',
				'default' => $default_color
			),
			//Main phone
			'header_listing_btn_text' => array(
				'label' => esc_html__('Header Phone', 'motors'),
				'type' => 'text',
				'default' => '709-458-2140'
			),
			'header_sticky' => array(
				'label' => esc_html__('Sticky', 'motors'),
				'type' => 'stm-checkbox',
				'sanitize_callback' => 'sanitize_checkbox',
				'default' => true
			),
		)
	);
}

if(!stm_is_auto_parts()) {
    STM_Customizer::setSection('header_layout', $header_layout_settings);
}

$topBarSettings = array(
    'title' => esc_html__('Top bar', 'motors'),
    'panel' => 'header',
    'fields' => array(
        'top_bar_enable' => array(
            'label' => esc_html__('Top bar Enabled', 'motors'),
            'type' => 'stm-checkbox',
            'sanitize_callback' => 'sanitize_checkbox',
            'default' => true
        ),
		'top_bar_bg_color' => array(
			'label' => esc_html__('Top bar background color', 'motors'),
			'type' => 'color',
			'output' => array('background-color' => '#wrapper #top-bar, .top-bar-wrap, .stm-template-listing #top-bar, body.page-template-home-service-layout #top-bar,
													.stm-template-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar:after,
													.stm-template-aircrafts #wrapper #top-bar, 
													.stm-template-listing_three #top-bar, 
													#wrapper #stm-boats-header #top-bar:after,
													.stm-template-listing_five .top-bar-wrap '
			),
			'transport' => 'postMessage',
			'default' => $default_color
		),
		'top_bar_text_color' => array(
			'label' => esc_html__('Top bar Text color', 'motors'),
			'type' => 'color',
			'output' => array('color' => '#wrapper #top-bar, 
				#wrapper #top-bar a,
				#wrapper .top-bar-wrap,
			 	#wrapper #top-bar .language-switcher-unit .stm_current_language,
			 	.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .top-bar-wrapper .language-switcher-unit .stm_current_language, 
				#wrapper #top-bar .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
				.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered,
				#wrapper #stm-boats-header #top-bar .top-bar-info li,
				#wrapper #top-bar .top_bar_menu ul li a,
				#wrapper #top-bar .header-login-url a,
				#wrapper #top-bar .select2-container--default .select2-selection--single .select2-selection__arrow b, 
				.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .top-bar-wrapper .pull-left .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__arrow b,
				.stm-layout-header-car_dealer_two.no_margin #wrapper #stm-boats-header #top-bar .header-login-url a,
				#wrapper #header .top-bar-wrap .stm-c-f-top-bar .stm-top-address-wrap,
				#wrapper #header .top-bar-wrap .stm-c-f-top-bar .language-switcher-unit .stm_current_language, 
				#wrapper #header .top-bar-wrap .stm-c-f-top-bar .stm-multiple-currency-wrap .select2-container--default .select2-selection--single .select2-selection__rendered, 
				#wrapper #header .top-bar-wrap .stm-c-f-top-bar .select2-container--default .select2-selection--single .select2-selection__arrow b
				'
			),
			'transport' => 'postMessage',
			'default' => '#ffffff'
		),
		'top_bar_icon_color' => array(
			'label' => esc_html__('Top bar Icon color', 'motors'),
			'type' => 'color',
			'output' => array('color' => ' 
				#wrapper #top-bar .pull-right i, 
				#wrapper #top-bar .stm-boats-top-bar-centered i,
				#wrapper #header .stm-c-f-top-bar i
				 '
			),
			'transport' => 'postMessage',
			'default' => '#ffffff'
		),
        'top_bar_login' => array(
            'label' => esc_html__('Top bar Login Enabled (Woocommerce needed or listing Layout chosen)', 'motors'),
            'type' => 'stm-checkbox',
            'sanitize_callback' => 'sanitize_checkbox',
            'default' => true
        ),
        'top_bar_wpml_switcher' => array(
            'label' => esc_html__('Top bar language switcher (WPML needed)', 'motors'),
            'type' => 'stm-checkbox',
            'sanitize_callback' => 'sanitize_checkbox',
            'default' => true
        ),
        //Address
        'top_bar_address' => array(
            'label' => esc_html__('Address', 'motors'),
            'type' => 'text',
            'default' => '1010 Moon ave, New York, NY US'
        ),
        'top_bar_address_mobile' => array(
            'label' => esc_html__('Show address on mobile', 'motors'),
            'type' => 'stm-checkbox',
            'sanitize_callback' => 'sanitize_checkbox',
            'default' => true
        ),
		'header_address_url' => array(
			'label' => esc_html__('Google Map Address URL', 'motors'),
			'type' => 'text',
		),
		//Working Hours
		'top_bar_working_hours' => array(
			'label' => esc_html__('Working Hours', 'motors'),
			'type' => 'text',
			'default' => 'Mon - Sat 8.00 - 18.00'
		),
		'top_bar_working_hours_mobile' => array(
			'label' => esc_html__('Show Working hours on mobile', 'motors'),
			'type' => 'stm-checkbox',
			'sanitize_callback' => 'sanitize_checkbox',
			'default' => true
		),
		//Phone number
		'top_bar_phone' => array(
			'label' => esc_html__('Phone number', 'motors'),
			'type' => 'text',
			'default' => '+1 212-226-3126'
		),
		'top_bar_phone_mobile' => array(
			'label' => esc_html__('Show phone on mobile', 'motors'),
			'type' => 'stm-checkbox',
			'sanitize_callback' => 'sanitize_checkbox',
			'default' => true
		),
		// Top bar menu
		'top_bar_menu' => array(
			'label' => esc_html__('Top bar menu Enabled', 'motors'),
			'type' => 'stm-checkbox',
			'sanitize_callback' => 'sanitize_checkbox',
			'default' => false
		),
		'top_bar_socials_enable' => array(
			'label' => esc_html__('Top bar Socials', 'motors'),
			'type' => 'stm-multiple-checkbox',
			'choices' => $socials
		),
    )
);


if(stm_is_rental_two()) {
	$topBarSettings = array();
}

if(defined('STM_MOTORS_CLASSIFIED_FIVE')) {
	//unset($topBarSettings['fields']['top_bar_address']);
	unset($topBarSettings['fields']['top_bar_address_mobile']);
	unset($topBarSettings['fields']['top_bar_login']);
	unset($topBarSettings['fields']['top_bar_working_hours']);
	unset($topBarSettings['fields']['top_bar_working_hours_mobile']);
	unset($topBarSettings['fields']['top_bar_phone']);
	unset($topBarSettings['fields']['top_bar_phone_mobile']);
	unset($topBarSettings['fields']['top_bar_menu']);
}

if(!stm_is_auto_parts()) {
	if(!empty($topBarSettings)) {
		STM_Customizer::setSection( 'header_top_bar', $topBarSettings );
	}

	if(get_theme_mod( 'header_layout', 'car_dealer' ) == 'car_dealer' || get_theme_mod( 'header_layout', 'car_dealer' ) == 'motorcycle'){

		$hmaSettings = array(
			'hma_background_color' => array(
				'label' => esc_html__('Background color', 'motors'),
				'type' => 'color',
				'output' => array('background-color' => '
						#header-nav-holder .header-nav.header-nav-default,
						#header .stm_motorcycle-header .stm_mc-nav .main-menu .inner .header-menu,
						#header .stm_motorcycle-header .stm_mc-nav .main-menu .inner:before,
						#header .stm_motorcycle-header .stm_mc-nav .main-menu .inner:after 
						'),
				'transport' => 'postMessage',
				'default' => '#eaedf0'
			),
			'hma_text_color' => array(
				'label' => esc_html__('Text color', 'motors'),
				'type' => 'color',
				'output' => array('color' => '
						#header-nav-holder .header-help-bar > ul li a .list-label, 
						#header-nav-holder .header-help-bar > ul li a .list-icon, 
						#header-nav-holder .header-help-bar > ul li a i, 
						#header-nav-holder .header-help-bar > ul li.nav-search > a 
						'),
				'transport' => 'postMessage',
				'default' => '#232628'
			),
		);

		if( get_theme_mod( 'header_layout', 'car_dealer' ) == 'car_dealer' ) {
			$hmaSettings['hma_search_button'] = array(
				'label' => esc_html__('Show Search Button', 'motors'),
				'type' => 'checkbox',
				'default' => true
			);
		}

		STM_Customizer::setSection( 'header_menu_area', array(
			'title' => esc_html__( 'Menu area', 'motors' ),
			'panel' => 'header',
			'fields' => $hmaSettings
		) );

	}

	if(!defined('STM_MOTORS_CLASSIFIED_FIVE')) {
		if( !stm_is_rental() ) {
			STM_Customizer::setSection( 'service_layout', array(
				'title' => esc_html__( 'Service Layout', 'motors' ),
				'fields' => array(
					'service_header_label' => array(
						'label' => esc_html__( 'Header button label', 'motors' ),
						'type' => 'text',
						'default' => esc_html__( 'Make an Appointment', 'motors' )
					),
					'service_header_link' => array(
						'label' => esc_html__( 'Header button link', 'motors' ),
						'type' => 'text',
						'default' => '#appointment-form',
					),
				)
			) );
		}

		STM_Customizer::setSection('rental_layout', array(
			'title' => esc_html__('Rental Layout', 'motors'),
			'fields' => array(
				'rental_datepick' => array(
					'label' => esc_html__('Reservation Date Page', 'motors'),
					'type' => 'stm-post-type',
					'post_type' => 'page',
					'description' => esc_html__('Choose page for reservation date', 'motors'),
					'default' => ''
				),
				'order_received' => array(
					'label' => esc_html__('Checkout "order received" endpoint page', 'motors'),
					'type' => 'stm-post-type',
					'post_type' => 'page',
					'description' => esc_html__('Choose a page to display content from, on order received endpoint.', 'motors'),
					'default' => ''
				),
				'discount_program_desc' => array(
					'label' => esc_html__('Popup Discount Program Description', 'motors'),
					'type' => 'textarea',
				),
				'enable_fixed_price_for_days' => array(
					'label' => esc_html__('Enable Fixed Price for Quantity Days', 'motors'),
					'type' => 'checkbox',
					'default' => false
				),
			)
		));

		STM_Customizer::setSection( 'header_socials', array(
			'title' => esc_html__( 'Header Socials', 'motors' ),
			'panel' => 'header',
			'fields' => array(
				'header_socials_enable' => array(
					'label' => esc_html__( 'Header Socials', 'motors' ),
					'type' => 'stm-multiple-checkbox',
					'choices' => $socials ),
				)
		) );
	}
}

STM_Customizer::setSection('footer_layout', array(
    'title' => esc_html__('Main settings', 'motors'),
    'panel' => 'footer',
    'fields' => array(
        'footer_bg_color' => array(
            'label' => esc_html__('Footer background color', 'motors'),
            'type' => 'color',
            'output' => array('background-color' => '#footer-main'),
            'transport' => 'postMessage',
            'default' => $footer_bg
        ),
        'footer_sidebar_count' => array(
            'label' => esc_html__('Widget Areas', 'motors'),
            'type' => 'stm-select',
            'default' => 4,
            'choices' => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4
            )
        ),
        'footer_break_2' => array(
            'type' => 'stm-separator',
        ),
        'footer_copyright' => array(
            'label' => esc_html__('Footer copyright Enabled', 'motors'),
            'type' => 'stm-checkbox',
            'sanitize_callback' => 'sanitize_checkbox',
            'default' => true
        ),
        'footer_copyright_color' => array(
            'label' => esc_html__('Footer Copyright background color', 'motors'),
            'type' => 'color',
            'output' => array('background-color' => '#footer-copyright'),
            'transport' => 'postMessage',
            'default' => '#232628'
        ),
        'footer_copyright_text' => array(
            'label' => esc_html__('Copyright', 'motors'),
            'default' => esc_html__('&copy; 2015 <a target="_blank" href="http://www.stylemixthemes.com/">Stylemix Themes</a><span class="divider"></span>Trademarks and brands are the property of their respective owners.', 'motors'),
            'type' => 'text'
        ),
        'footer_socials_enable' => array(
            'label' => esc_html__('Copyright Socials', 'motors'),
            'type' => 'stm-multiple-checkbox',
            'choices' => $socials
        ),
    )
));

STM_Customizer::setSection('footer_scripts', array(
    'title' => esc_html__('Additional Scripts', 'motors'),
    'panel' => 'footer',
    'fields' => array(
        'footer_custom_scripts' => array(
            'label' => '',
            'type' => 'stm-code',
            'placeholder' => 'alert("hello");',
            'description' => esc_html__("Enter in any custom script to include in your site's footer. Be sure to use double quotes for strings.", 'motors')
        )
    )
));

if (!stm_is_motorcycle()) {

    $listing_features['listing_archive'] = array(
        'label' => esc_html__('Listing archive', 'motors'),
        'type' => 'stm-post-type',
        'post_type' => 'page',
        'description' => esc_html__('Choose listing archive page', 'motors'),
        'default' => ''
    );

    if(stm_is_dealer_two()) {
        $listing_features['inventory_layout'] = array(
            'label' => esc_html__('Inventory Layout', 'motors'),
            'type' => 'radio',
            'choices' => array(
                'light' => __('Light', 'motors'),
                'dark' => __('Dark', 'motors')
            ),
            'default' => 'dark'
        );
    }

    $listing_features_2 = array(
        'listing_sidebar' => array(
            'label' => esc_html__('Choose inventory sidebar', 'motors'),
            'type' => 'stm-select',
            'choices' => $sidebars,
            'default' => 'no_sidebar'
        ),
        'listing_view_type' => array(
	        'label' => esc_html__('Listing view type', 'motors'),
	        'type' => 'radio',
	        'choices' => array(
		        'grid' => __('Grid', 'motors'),
		        'list' => __('List', 'motors')
	        ),
	        'default' => 'list'
        ),
		'grid_title_max_length' => array(
			'label' => esc_html__('Grid item title max length', 'motors'),
			'type' => 'text',
			'default' => '44'
		),
        'enable_search' => array(
            'label' => esc_html__('Bind WP Search form with Inventory', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),
        /*'hide_empty_category' => array(
            'label' => esc_html__('Hide empty Categories from Inventory filter', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),*/
        'enable_features_search' => array(
            'label' => esc_html__('Display Additional Features on Inventory Filter', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'default_sort_by' => array(
            'label' => esc_html__('Default Sort option', 'motors'),
            'type' => 'stm-select',
            'default' => 'date_high',
            'choices' => $sortBy
        ),
        'listing_filter_position' => array(
            'label' => esc_html__('Filter Position', 'motors'),
            'type' => 'stm-select',
            'default' => 'left',
            'choices' => $positions
        ),
        'classic_listing_title_bg' => array(
            'label' => esc_html__('Title background', 'motors'),
            'type' => 'image'
        ),
        'classic_listing_title' => array(
            'label' => esc_html__('Listing archive "Title box" title', 'motors'),
            'type' => 'text',
            'default' => esc_html__('Inventory', 'motors'),
        ),
        'price_currency_name' => array(
            'label' => esc_html__('Price currency name', 'motors'),
            'type' => 'text',
            'default' => 'USD'
        ),
        'price_currency' => array(
            'label' => esc_html__('Price currency', 'motors'),
            'type' => 'text',
            'default' => '$'
        ),
        'price_currency_position' => array(
            'label' => esc_html__('Price currency position', 'motors'),
            'type' => 'stm-select',
            'choices' => $positions,
            'default' => 'left'
        ),
        'currency_list'     => array(
            'label'     => esc_html__('Multiple currencies', 'motors'),
            'description' => esc_html__('Conversion Rate should be delimited by dot (example: 1.2)', 'motors'),
            'type'      => 'stm-text-repeater',
            'choices'   => $currencyTo
        ),
        'price_delimeter' => array(
            'label' => esc_html__('Price delimeter', 'motors'),
            'type' => 'text',
            'default' => ' '
        ),
        'enable_location' => array(
            'label' => esc_html__('Show location/include location in filter', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'distance_measure_unit' => array(
            'label' => esc_html__('Unit measurement', 'motors'),
            'type' => 'stm-select',
            'choices' => array(
                'miles' => esc_html__('Miles', 'motors'),
                'kilometers' => esc_html__('Kilometers', 'motors'),
            ),
            'default' => 'miles'
        ),
        'distance_search' => array(
            'label' => esc_html__('Set max search radius', 'motors'),
            'type' => 'text',
        ),
        'show_listing_stock' => array(
            'label' => esc_html__('Show stock', 'motors'),
            'type' => 'checkbox',
            'default' => $show_on_dealer
        ),
        'show_listing_test_drive' => array(
            'label' => esc_html__('Show test drive schedule', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),
        'show_listing_compare' => array(
            'label' => esc_html__('Show compare', 'motors'),
            'type' => 'checkbox',
            'default' => $show_on_dealer
        ),
        'show_listing_share' => array(
            'label' => esc_html__('Show share block', 'motors'),
            'type' => 'checkbox',
        ),
        'show_listing_pdf' => array(
            'label' => esc_html__('Show PDF brochure', 'motors'),
            'type' => 'checkbox',
        ),
        'show_listing_certified_logo_1' => array(
            'label' => esc_html__('Show certified logo 1', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'show_listing_certified_logo_2' => array(
            'label' => esc_html__('Show certified logo 2', 'motors'),
            'type' => 'checkbox',
            'default' => $show_on_dealer
        ),
        'typography_break_1' => array(
            'type' => 'stm-separator',
        ),
        'listing_directory_title_default' => array(
            'label' => esc_html__('Default Title', 'motors'),
            'type' => 'text',
            'default' => esc_html__('Cars for sale', 'motors')
        ),
        'listing_directory_title_generated_affix' => array(
            'label' => esc_html__('Generated title affix', 'motors'),
            'type' => 'text',
            'default' => esc_html__(' for sale', 'motors')
        ),
        'listing_directory_title_frontend' => array(
            'label' => esc_html__('Display generated car title as:', 'motors'),
            'type' => 'text',
            'default' => esc_html__('{make} {serie} {ca-year}', 'motors'),
            'description' => esc_html__('"Put in curly brackets slug of taxonomy. For Example - {make} {serie} {ca-year}. Leave empty if you want to display default car title."', 'motors'),
        ),
        'show_generated_title_as_label' => array(
            'label' => esc_html__('Show two first parameters as a badge (only on archive page)', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'enable_favorite_items' => array(
            'label' => esc_html__('Enable favorites', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'listing_directory_enable_dealer_info' => array(
            'label' => esc_html__('Enable dealer info on listing', 'motors'),
            'type' => 'checkbox',
            'default' => $show_on_listing
        ),
        'hide_price_labels' => array(
            'label' => esc_html__('Hide price labels on listing archive', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'sidebar_filter_bg' => array(
            'label' => esc_html__('Listing sidebar filter background', 'motors'),
            'type' => 'image',
            'default' => get_template_directory_uri() . '/assets/images/listing-directory-filter-bg.jpg'
        ),
    );

	if(stm_is_dealer_two()) {
		unset($listing_features_2['enable_favorite_items']);
	}

    $listing_features = array_merge($listing_features, $listing_features_2);

} else {
    $listing_features = array(
        'listing_archive' => array(
            'label' => esc_html__('Listing archive', 'motors'),
            'type' => 'stm-post-type',
            'post_type' => 'page',
            'description' => esc_html__('Choose listing archive page', 'motors'),
            'default' => ''
        ),
        'listing_sidebar' => array(
            'label' => esc_html__('Choose inventory sidebar', 'motors'),
            'type' => 'stm-select',
            'choices' => $sidebars,
            'default' => 'no_sidebar'
        ),
        'listing_view_type' => array(
	        'label' => esc_html__('Listing view type', 'motors'),
	        'type' => 'radio',
	        'choices' => array(
		        'grid' => __('Grid', 'motors'),
		        'list' => __('List', 'motors')
	        ),
	        'default' => 'list'
        ),
		'grid_title_max_length' => array(
			'label' => esc_html__('Grid item title max length', 'motors'),
			'type' => 'text',
			'default' => '44'
		),
        'enable_search' => array(
            'label' => esc_html__('Bind WP Search form with Inventory', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),
        /*'hide_empty_category' => array(
            'label' => esc_html__('Hide empty Categories from Inventory filter', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),*/
        'enable_features_search' => array(
            'label' => esc_html__('Display Additional Features on Inventory Filter', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
		'default_sort_by' => array(
			'label' => esc_html__('Default Sort option', 'motors'),
			'type' => 'stm-select',
			'default' => 'date_high',
			'choices' => $sortBy
		),
        'listing_filter_position' => array(
            'label' => esc_html__('Filter Position', 'motors'),
            'type' => 'stm-select',
            'default' => 'left',
            'choices' => $positions
        ),
        'classic_listing_title_bg' => array(
            'label' => esc_html__('Title background', 'motors'),
            'type' => 'image'
        ),
        'classic_listing_title' => array(
            'label' => esc_html__('Listing archive "Title box" title', 'motors'),
            'type' => 'text',
            'default' => esc_html__('Inventory', 'motors'),
        ),
        'price_currency_name' => array(
            'label' => esc_html__('Price currency name', 'motors'),
            'type' => 'text',
            'default' => 'USD'
        ),
        'price_currency' => array(
            'label' => esc_html__('Price currency', 'motors'),
            'type' => 'text',
            'default' => '$'
        ),
        'price_currency_position' => array(
            'label' => esc_html__('Price currency position', 'motors'),
            'type' => 'stm-select',
            'choices' => $positions,
            'default' => 'left'
        ),
        'currency_list'     => array(
            'label'     => esc_html__('Multiple currencies', 'motors'),
            'description' => esc_html__('Conversion Rate should be delimited by dot (example: 1.2)', 'motors'),
            'type'      => 'stm-text-repeater',
            'choices'   => $currencyTo
        ),
        'price_delimeter' => array(
            'label' => esc_html__('Price delimeter', 'motors'),
            'type' => 'text',
            'default' => ' '
        ),
        'show_listing_stock' => array(
            'label' => esc_html__('Show stock', 'motors'),
            'type' => 'checkbox',
            'default' => $show_on_dealer
        ),
        'show_listing_compare' => array(
            'label' => esc_html__('Show compare', 'motors'),
            'type' => 'checkbox',
            'default' => $show_on_dealer
        ),
        'show_listing_test_drive' => array(
            'label' => esc_html__('Show "Test drive" popup', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'show_listing_quote' => array(
            'label' => esc_html__('Show "Quote by phone"', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),
        'show_listing_trade' => array(
            'label' => esc_html__('Show "Trade value" popup', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),
        'show_listing_calculate' => array(
            'label' => esc_html__('Show "Calculate Payment" popup', 'motors'),
            'type' => 'checkbox',
            'default' => false
        ),
        'show_listing_vin' => array(
            'label' => esc_html__('Show "VIN" link', 'motors'),
            'type' => 'checkbox',
            'default' => true
        ),
        'typography_break_1' => array(
            'type' => 'stm-separator',
        ),
        'listing_directory_title_default' => array(
            'label' => esc_html__('Default Title', 'motors'),
            'type' => 'text',
            'default' => esc_html__('Cars for sale', 'motors')
        ),
        'listing_directory_title_generated_affix' => array(
            'label' => esc_html__('Generated title affix', 'motors'),
            'type' => 'text',
            'default' => esc_html__(' for sale', 'motors')
        ),
        'listing_directory_title_frontend' => array(
            'label' => esc_html__('Display generated car title as:', 'motors'),
            'type' => 'text',
            'default' => esc_html__('{make} {serie} {ca-year}', 'motors'),
            'description' => esc_html__('"Put in curly brackets slug of taxonomy. For Example - {make} {serie} {ca-year}. Leave empty if you want to display default car title."', 'motors'),
        ),
        'show_generated_title_as_label' => array(
            'label' => esc_html__('Show first parametre as a badge (only on archive page)', 'motors'),
            'type' => 'checkbox',
            'default' => true
        )
    );

    $listing_features['listing_grid_choices'] = array(
        'label' => esc_html__('Items per page choices(Grid version. Ex: 9,12,18,27).', 'motors'),
        'type' => 'text',
        'default' => '9,12,18,27'
    );
}

if (stm_is_boats()) {
    $listing_features['listing_boat_filter'] = array(
        'label' => esc_html__('Use Boats filter style', 'motors'),
        'type' => 'checkbox',
        'default' => true
    );
    $listing_features['listing_list_sort_slug'] = array(
        'label' => esc_html__('List version sort parameter (Type slug)', 'motors'),
        'type' => 'text',
        'default' => 'make'
    );
    $listing_features['listing_grid_choices'] = array(
        'label' => esc_html__('Items per page choices(Grid version. Ex: 9,12,18,27).', 'motors'),
        'type' => 'text',
        'default' => '9,12,18,27'
    );
}

if(!stm_is_auto_parts() && !stm_is_rental()) {
    STM_Customizer::setSection('listing_features', array(
        'title' => esc_html__('Inventory settings', 'motors'),
        'panel' => 'listing',
        'fields' => $listing_features
    ));
}

$single_car_settings = array(
    'show_stock' => array(
        'label' => esc_html__('Show stock', 'motors'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_compare' => array(
        'label' => esc_html__('Show compare', 'motors'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_share' => array(
        'label' => esc_html__('Show share block', 'motors'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_pdf' => array(
        'label' => esc_html__('Show PDF brochure', 'motors'),
        'type' => 'checkbox',
        'default' => true
    ),
    'show_certified_logo_1' => array(
        'label' => esc_html__('Show certified logo 1', 'motors'),
        'type' => 'checkbox',
    ),
    'show_certified_logo_2' => array(
        'label' => esc_html__('Show certified logo 2', 'motors'),
        'type' => 'checkbox',
    ),
    'show_featured_btn' => array(
        'label' => esc_html__('Show featured button', 'motors'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
    'single_car_break' => array(
        'type' => 'stm-separator',
    ),
    'show_vin' => array(
        'label' => esc_html__('Show VIN', 'motors'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
    'show_registered' => array(
        'label' => esc_html__('Show Registered date', 'motors'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
    'show_history' => array(
        'label' => esc_html__('Show History', 'motors'),
        'type' => 'checkbox',
        'default' => $show_on_listing
    ),
	'single_car_break_2' => array(
		'type' => 'stm-separator',
	),
    'stm_show_number' => array(
        'label' => esc_html__('Show Number', 'motors'),
        'type' => 'checkbox',
        'default' => false
    ),
	'single_car_break_3' => array(
		'type' => 'stm-separator',
	),
	'default_interest_rate' => array(
		'label' => esc_html__('Default interest rate', 'motors'),
		'type' => 'text',
		'default' => ''
	),
	'default_month_period' => array(
		'label' => esc_html__('Default Month Period', 'motors'),
		'type' => 'text',
		'default' => ''
	),

    'default_down_payment_type' => array(
	    'label' => esc_html__('Default down payment type', 'motors'),
	    'type' => 'radio',
	    'choices' => array(
		    'static' => __('Static', 'motors'),
		    'percent' => __('Percent', 'motors')
	    ),
	    'default' => 'static'
    ),

	'default_down_payment' => array(
		'label' => esc_html__('Down payment amount', 'motors'),
		'type' => 'number',
		'default' => '',
	),

    'default_down_payment_percent' => array(
	    'label' => esc_html__('Down payment percent (%)', 'motors'),
	    'type' => 'number',
	    'default' => '0'
    )
);

if(is_dealer() || stm_is_listing_four()) {
	unset($single_car_settings['show_featured_btn']);
}

if(is_dealer() && stm_is_use_plugin('vin-decoder/motors-vin-decoder.php')) {
    $element['show_vin_history_btn'] = array(
        'label' => esc_html__('Show Vin History button', 'motors'),
        'type' => 'checkbox',
        'default' => false
    );
    $single_car_settings = array_merge($element, $single_car_settings);
}

if(is_listing()) {
    $element['show_added_date'] = array(
        'label' => esc_html__('Show Added Date', 'motors'),
        'type' => 'checkbox',
        'default' => false
    );
    $single_car_settings = array_merge($element, $single_car_settings);
}

if(is_dealer() || is_listing()) {
    $element['show_print_btn'] = array(
        'label' => esc_html__('Show Print button', 'motors'),
        'type' => 'checkbox',
        'default' => false
    );
    $single_car_settings = array_merge($element, $single_car_settings);
}

if(!stm_is_magazine()) {
    $element['show_test_drive'] = array(
        'label' => esc_html__('Show test drive schedule', 'motors'),
        'type' => 'checkbox',
        'default' => true
    );
    $single_car_settings = array_merge($element, $single_car_settings);
}

if(!is_listing() and !stm_is_boats() and !stm_is_motorcycle()) {
	$element['show_trade_in'] = array(
		'label' => esc_html__('Show Trade In button', 'motors'),
		'type' => 'checkbox',
		'default' => false
	);

	$element['show_offer_price'] = array(
		'label' => esc_html__('Show Offer Price button', 'motors'),
		'type' => 'checkbox',
		'default' => false
	);

	$element['show_calculator'] = array(
		'label' => esc_html__('Show Calculator', 'motors'),
		'type' => 'checkbox',
		'default' => true
	);

	$single_car_settings = array_merge($element, $single_car_settings);
}

if (stm_is_motorcycle() || stm_is_dealer_two()) {
    $single_car_settings['show_quote_phone'] = array(
        'label' => esc_html__('Show quote by phone', 'motors'),
        'type' => 'checkbox',
        'default' => true
    );
    $single_car_settings['show_trade_in'] = array(
        'label' => esc_html__('Show trade in form', 'motors'),
        'type' => 'checkbox',
        'default' => true
    );
    $single_car_settings['show_calculator'] = array(
        'label' => esc_html__('Show calculator button', 'motors'),
        'type' => 'checkbox',
        'default' => true
    );
    $single_car_settings['show_report'] = array(
        'label' => esc_html__('Show history report', 'motors'),
        'type' => 'checkbox',
        'default' => true
    );
    $single_car_settings['stm_single_car_page'] = array(
        'label' => esc_html__('Single car page background', 'motors'),
        'type' => 'image',
    );
    $single_car_settings['stm_car_link_quote'] = array(
        'label' => esc_html__('Request a quote link', 'motors'),
        'type' => 'text',
    );
}

if(stm_is_equipment()) {
	$single_car_settings['stm_single_car_page'] = array(
		'label' => esc_html__('Single car page background', 'motors'),
		'type' => 'image',
	);
	$single_car_settings['stm_car_link_quote'] = array(
		'label' => esc_html__('Request a quote link', 'motors'),
		'type' => 'text',
	);
}

$single_car_settings['stm_similar_query'] = array(
    'label' => esc_html__('Show similar cars by param (slug of listing category, separated by comma, without spaces. Ex: make,condition)', 'motors'),
    'type' => 'text',
    'default' => ''
);

//CARGurus Added only for dealer layout
if(is_dealer()) {
    /*$single_car_settings['carguru'] = array(
        'label' => esc_html__('CarGuru Javascript code', 'motors'),
        'type' => 'textarea',
        'description' => esc_html__('You must have an active feed to CarGurus in order for the badge to appear', 'motors')
    );*/


	$single_car_settings['carguru_style'] = array(
		'label' => esc_html__('CarGuru Style', 'motors'),
		'type' => 'stm-select',
		'choices' => array(
			'STYLE1' => esc_html__('Style 1', 'motors'),
			'STYLE2' => esc_html__('Style 2', 'motors'),
			'BANNER1' => esc_html__('Banner 1 - 900 x 60 pixels', 'motors'),
			'BANNER2' => esc_html__('Banner 2 - 900 x 42 pixels', 'motors'),
			'BANNER3' => esc_html__('Banner 3 - 748 x 42 pixels', 'motors'),
			'BANNER4' => esc_html__('Banner 4 - 550 x 42 pixels', 'motors'),
			'BANNER5' => esc_html__('Banner 5 - 374 x 42 pixels', 'motors'),
		),
		'default' => 'STYLE1'
	);

	$single_car_settings['carguru_min_rating'] = array(
		'label' => esc_html__('CarGuru Minimum Rating to display', 'motors'),
		'type' => 'stm-select',
		'choices' => array(
			'GREAT_PRICE' => esc_html__('Great Price', 'motors'),
			'GOOD_PRICE' => esc_html__('Good Price', 'motors'),
			'FAIR_PRICE' => esc_html__('Fair Price', 'motors')
		),
		'default' => 'GREAT_PRICE'
	);

	$single_car_settings['carguru_default_height'] = array(
		'label' => esc_html__('CarGuru Enter Height (in pixels)', 'motors'),
		'type' => 'text',
		'default' => esc_html__('42', 'motors'),
	);
}

if(!stm_is_auto_parts() && !stm_is_rental()) {
    STM_Customizer::setSection( 'car_settings', array(
        'title' => esc_html__( 'Single Car Settings', 'motors' ),
        'panel' => 'listing',
        'fields' => $single_car_settings
    ) );

    STM_Customizer::setSection( 'compare', array(
        'title' => esc_html__( 'Compare', 'motors' ),
        'panel' => 'listing',
        'fields' => array(
            'compare_page' => array(
                'label' => esc_html__( 'Compare page', 'motors' ),
                'type' => 'stm-post-type',
                'post_type' => 'page',
                'description' => esc_html__( 'Choose landing page for compare', 'motors' ),
                'default' => '156'
            ),
        )
    ) );

    STM_Customizer::setSection( 'user_dealer', array(
        'title' => esc_html__( 'User/Dealer options', 'motors' ),
        'panel' => 'listing',
        'fields' => array(
            'login_page' => array(
                'label' => esc_html__( 'Login/Registration page', 'motors' ),
                'type' => 'stm-post-type',
                'post_type' => 'page',
                'description' => esc_html__( 'Choose page for login User/Dealer', 'motors' ),
                'default' => '1718'
            )
        )
    ) );

    if(stm_is_dealer_two()) {
		STM_Customizer::setSection( 'user_dealer',
			array(
				'title' => esc_html__( 'User/Dealer options', 'motors' ),
				'panel' => 'listing',
				'fields' => array(
					'login_page' => array(
						'label' => esc_html__( 'Login/Registration page', 'motors' ),
						'type' => 'stm-post-type',
						'post_type' => 'page',
						'description' => esc_html__( 'Choose page for login User/Dealer', 'motors' ),
						'default' => '1718' ),
					'enable_woo_online_sep' => array(
						'type' => 'stm-separator',
					),
					'enable_woo_online' => array(
						'label' => esc_html__( 'Enable Sell a Car online (Woocommerce)', 'motors' ),
						'type' => 'stm-checkbox',
						'default' => false
					),
					'contact_us_page' => array(
						'label' => esc_html__( 'Contact Us page', 'motors' ),
						'type' => 'stm-post-type',
						'post_type' => 'page',
						'description' => esc_html__( 'Choose page for contact us after order vehicle', 'motors' ),
						'default' => '2080' ),
				)
			)
		);
	}

    if ( is_listing() ) {
        STM_Customizer::setSection( 'user_dealer', array(
            'title' => esc_html__( 'User/Dealer options', 'motors' ),
            'panel' => 'listing',
            'fields' => array(
                'dealer_list_page' => array(
                    'label' => esc_html__( 'Dealer list page', 'motors' ),
                    'type' => 'stm-post-type',
                    'post_type' => 'page',
                    'description' => esc_html__( 'Choose page for Dealer list page', 'motors' ),
                    'default' => '2119'
                ),
                'login_page' => array(
                    'label' => esc_html__( 'Login/Registration page', 'motors' ),
                    'type' => 'stm-post-type',
                    'post_type' => 'page',
                    'description' => esc_html__( 'Choose page for login User/Dealer', 'motors' ),
                    'default' => '1718'
                ),
				'enable_email_confirmation' => array(
					'label' => esc_html__( 'Enable Email Confirmation', 'motors' ),
					'type' => 'stm-checkbox',
					'default' => false
				),
                'user_sidebar' => array(
                    'label' => esc_html__( 'Default user sidebar', 'motors' ),
                    'type' => 'stm-post-type',
                    'post_type' => 'sidebar',
                    'description' => esc_html__( 'Choose page for Default user sidebar', 'motors' ),
                    'default' => '1725'
                ),
                'user_sidebar_position' => array(
                    'label' => esc_html__( 'User Sidebar Position', 'motors' ),
                    'type' => 'radio',
                    'choices' => array(
                        'left' => __( 'Left', 'motors' ),
                        'right' => __( 'Right', 'motors' )
                    ),
                    'default' => 'right'
                ),
                'dealer_sidebar' => array(
                    'label' => esc_html__( 'Default dealer sidebar', 'motors' ),
                    'type' => 'stm-post-type',
                    'post_type' => 'sidebar',
                    'description' => esc_html__( 'Choose page for Default user sidebar', 'motors' ),
                    'default' => '1864'
                ),
                'dealer_sidebar_position' => array(
                    'label' => esc_html__( 'Dealer Sidebar Position', 'motors' ),
                    'type' => 'radio',
                    'choices' => array(
                        'left' => __( 'Left', 'motors' ),
                        'right' => __( 'Right', 'motors' )
                    ),
                    'default' => 'right'
                ),
                'user_add_car_page' => array(
                    'label' => esc_html__( 'Add a car page', 'motors' ),
                    'type' => 'stm-post-type',
                    'post_type' => 'page',
                    'description' => esc_html__( 'Choose page for Add to car Page (Also, this page will be used for editing items)', 'motors' ),
                    'default' => '1755'
                ),
                'dealer_rate_1' => array(
                    'label' => esc_html__( 'Rate 1 label:', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( 'Customer Service', 'motors' ),
                ),
                'dealer_rate_2' => array(
                    'label' => esc_html__( 'Rate 2 label:', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( 'Buying Process', 'motors' ),
                ),
                'dealer_rate_3' => array(
                    'label' => esc_html__( 'Rate 3 label:', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( 'Overall Experience', 'motors' ),
                ),
                'user_post_limit' => array(
                    'label' => esc_html__( 'User Slots Limit:', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( '3', 'motors' ),
                ),
                'user_post_images_limit' => array(
                    'label' => esc_html__( 'User Slot Images Upload Limit:', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( '5', 'motors' ),
                ),
                'dealer_post_limit' => array(
                    'label' => esc_html__( 'Dealer Slots Limit:', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( '50', 'motors' ),
                ),
                'dealer_post_images_limit' => array(
                    'label' => esc_html__( 'Dealer Slot Images Upload Limit:', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( '10', 'motors' ),
                ),
                'user_image_size_limit' => array(
                    'label' => esc_html__( 'Image size limit (Kb)', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( '4000', 'motors' ),
                ),
                'send_email_to_user' => array(
                    'label' => esc_html__( 'Send email to Dealer/Private Seller (Ad to be waiting approve or ad has been approved)', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
                'user_premoderation' => array(
                    'label' => esc_html__( 'Enable User ads moderation', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => true
                ),
                'dealer_premoderation' => array(
                    'label' => esc_html__( 'Enable Dealer ads moderation', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
                'dealer_pay_per_listing' => array(
                    'label' => esc_html__( 'Enable Pay Per Listing', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
                'dealer_payments_for_featured_listing' => array(
                    'label' => esc_html__( 'Enable Paid Featured Listing', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
                'allow_dealer_add_new_category' => array(
                    'label' => esc_html__( 'Allow Dealer Add New Category', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
                'dealer_review_moderation' => array(
                    'label' => esc_html__( 'Enable moderation for dealer review', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
                'pay_per_listing_price' => array(
                    'label' => esc_html__( 'Pay Per Listing Price', 'motors' ),
                    'type' => 'text',
                    'default' => 0,
                ),
                'pay_per_listing_period' => array(
                    'label' => esc_html__( 'Pay Per Listing Period (days)', 'motors' ),
                    'type' => 'text',
                    'default' => 30,
                ),
                'featured_listing_price' => array(
                    'label' => esc_html__( 'Featured Listing Price', 'motors' ),
                    'type' => 'text',
                    'default' => 0,
                ),
                'featured_listing_period' => array(
                    'label' => esc_html__( 'Featured Listing Period (days)', 'motors' ),
                    'type' => 'text',
                    'default' => 30,
                ),
                'enable_plans_sep' => array(
                    'type' => 'stm-separator',
                ),
				'enable_plans' => array(
                    'label' => esc_html__( 'Enable Pricing Plans (Woocommerce)', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
                'pricing_link' => array(
                    'label' => esc_html__( 'Pricing Link', 'motors' ),
                    'type' => 'stm-post-type',
                    'post_type' => 'page',
                ),
                'enable_demo_sep' => array(
                    'type' => 'stm-separator',
                ),
                'site_demo_mode' => array(
                    'label' => esc_html__( 'Site demo mode', 'motors' ),
                    'type' => 'stm-checkbox',
                    'default' => false
                ),
            )
        ) );

        STM_Customizer::setSection( 'stm_paypal_options', array(
            'title' => esc_html__( 'Paypal options', 'motors' ),
            'panel' => 'listing',
            'fields' => array(
                'paypal_currency' => array(
                    'label' => esc_html__( 'Currency', 'motors' ),
                    'type' => 'text',
                    'default' => esc_html__( 'USD', 'motors' ),
                    'description' => esc_html__( 'Ex.: USD', 'motors' ),
                ),
                'paypal_email' => array(
                    'label' => esc_html__( 'Paypal Email', 'motors' ),
                    'type' => 'text',
                    'default' => '',
                ),
                'paypal_mode' => array(
                    'label' => esc_html__( 'Paypal mode', 'motors' ),
                    'type' => 'stm-select',
                    'choices' => array(
                        'sandbox' => esc_html__( 'Sandbox', 'motors' ),
                        'live' => esc_html__( 'Live', 'motors' ),
                    ),
                    'default' => 'sandbox'
                ),
                'membership_cost' => array(
                    'label' => esc_html__( 'Membership price', 'motors' ),
                    'type' => 'text',
                    'default' => '',
                    'description' => esc_html__( 'Membership submission price', 'motors' ),
                ),
            )
        ) );
    }
}

if(!defined('STM_MOTORS_CLASSIFIED_FIVE')) {
	STM_Customizer::setSection( 'shop', array( 'title' => esc_html__( 'Shop', 'motors' ), 'priority' => 45, 'fields' => array( 'shop_sidebar' => array( 'label' => esc_html__( 'Choose Shop Sidebar', 'motors' ), 'type' => 'stm-post-type', 'post_type' => 'sidebar', 'default' => '768' ), 'shop_sidebar_position' => array( 'label' => esc_html__( 'Shop Sidebar Position', 'motors' ), 'type' => 'radio', 'choices' => array( 'left' => __( 'Left', 'motors' ), 'right' => __( 'Right', 'motors' ) ), 'default' => 'left' ), ) ) );
}


    STM_Customizer::setSection( 'blog', array(
        'title' => esc_html__( 'Blog', 'motors' ),
        'priority' => 40,
        'fields' => array(
            'view_type' => array(
                'label' => esc_html__( 'View type', 'motors' ),
                'type' => 'radio',
                'choices' => array(
                    'grid' => __( 'Grid', 'motors' ),
                    'list' => __( 'List', 'motors' )
                ),
                'default' => 'grid'
            ),
            'sidebar' => array(
                'label' => esc_html__( 'Choose archive sidebar', 'motors' ),
                'type' => 'stm-select',
                'choices' => $sidebars,
                'default' => 'default'
            ),
            'sidebar_blog' => array(
                'label' => esc_html__( 'Choose default sidebar for single blog post', 'motors' ),
                'type' => 'stm-select',
                'choices' => $sidebars,
                'default' => 'default'
            ),
            'sidebar_position' => array(
                'label' => esc_html__( 'Sidebar position', 'motors' ),
                'type' => 'radio',
                'choices' => array(
                    'left' => __( 'Left', 'motors' ),
                    'right' => __( 'Right', 'motors' )
                ),
                'default' => 'right'
            ),
            'blog_show_excerpt' => array(
                'label' => esc_html__( 'Show excerpt', 'motors' ),
                'type' => 'checkbox',
            ),
        )
    ) );

    STM_Customizer::setSection('socials_widget', array(
        'title' => esc_html__('Socials Widget', 'motors'),
        'priority' => 70,
        'fields' => array(
            'socials_widget_enable' => array(
                'label' => esc_html__('"Social Widget" socials', 'motors'),
                'type' => 'stm-multiple-checkbox',
                'choices' => $socials
            )
        )
    ));

if(!stm_is_auto_parts()) {
    $allowed_tags = array(
        'a' => array(
            'href' => array(),
            'title' => array()
        )
    );

    $html = 'You can get a Google reCAPTCHA API from <a href="http://www.google.com/recaptcha/intro/" target="_blank">here</a>';

	if(!defined('STM_MOTORS_CLASSIFIED_FIVE')) {
		STM_Customizer::setSection(
			'recaptcha',
			array(
				'title' => esc_html__( 'Recaptcha', 'motors' ),
				'priority' => 80,
				'fields' =>
					array(
						'enable_recaptcha' =>
							array(
								'label' => esc_html__( 'Recaptcha', 'motors' ),
								'type' => 'checkbox',
								'description' => wp_kses( $html, $allowed_tags ) ),
						'recaptcha_public_key' =>
							array(
								'label' => esc_html__( 'Public key', 'motors' ),
								'type' => 'text', ), 'recaptcha_secret_key' => array( 'label' => esc_html__( 'Secret key', 'motors' ), 'type' => 'text', ), ) ) );
	}

    STM_Customizer::setSection('css', array(
        'title' => esc_html__('CSS', 'motors'),
        'fields' => array(
            'custom_css' => array(
                'label' => '',
                'type' => 'stm-code',
                'placeholder' => ".classname {\n\tbackground: #000;\n}"
            )
        )
    ));
}

STM_Customizer::setSection('socials', array(
    'title' => esc_html__('Socials', 'motors'),
    'priority' => 60,
    'fields' => array(
        'socials_link' => array(
            'label' => esc_html__('Socials Links', 'motors'),
            'type' => 'stm-socials',
            'choices' => $socials
        )
    )
));