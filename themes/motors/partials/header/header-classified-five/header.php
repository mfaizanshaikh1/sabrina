<?php
$top_bar = get_theme_mod( 'top_bar_enable', false );
$logo_main = get_theme_mod( 'logo', get_template_directory_uri() . '/assets/images/tmp/logo_c_five.svg' );


$fixed_header = get_theme_mod( 'header_sticky', false );
if ( !empty( $fixed_header ) and $fixed_header ) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = 'header-listing-unfixed';
}

$transparent_header = get_post_meta(get_the_ID(), 'transparent_header', true);

$transparent_header_class = ($transparent_header) ? 'transparent-header' : '';

if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();
}

$langs = apply_filters( 'wpml_active_languages', null, null );

$header_listing_btn_text = get_theme_mod('header_listing_btn_text', esc_html__('Add your item', 'motors'));

$header_listing_btn_link = (is_listing(array('listing_five'))) ? stm_c_f_get_page_url( 'add_listing' ) : get_theme_mod('header_listing_btn_link', '/add-car');
$header_listing_btn_link = (is_listing(array('listing_six'))) ? stm_c_six_get_page_url( 'add_listing' ) : $header_listing_btn_link;
$header_profile = get_theme_mod('header_show_profile', false);

$phoneLabel = get_theme_mod( 'header_main_phone_label', 'Call Free' );
$phone = get_theme_mod( 'header_main_phone', '+1 212-226-3126' );
?>
<div id="header" class="<?php echo esc_attr($transparent_header_class)?>"><!--HEADER-->
	<?php if ( $top_bar ) get_template_part( 'partials/header/header-classified-five/top-bar' ); ?>

    <div class="header-main header-main-listing-five <?php echo esc_attr( $fixed_header_class ); ?>">
        <div class="container">
            <div class="row header-row">
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <div class="stm-header-left">
                        <div class="logo-main">
							<?php if ( empty( $logo_main ) ): ?>
                                <a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                   title="<?php _e( 'Home', 'stm_motors_classified_five' ); ?>">
                                    <h1><?php echo esc_attr( get_bloginfo( 'name' ) ) ?></h1>
                                </a>
							<?php else: ?>
                                <a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                    <img src="<?php echo esc_url( $logo_main ); ?>"
                                         style="width: <?php echo get_theme_mod( 'logo_width', '138' ); ?>px;"
                                         title="<?php esc_attr_e( 'Home', 'stm_motors_classified_five' ); ?>"
                                         alt="<?php esc_attr_e( 'Logo', 'stm_motors_classified_five' ); ?>"
                                    />
                                </a>
							<?php endif; ?>
                            <div class="mobile-menu-trigger visible-sm visible-xs">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <?php
						$compare_page = (is_listing(array('listing_five', 'listing_six'))) ? \uListing\Classes\StmListingSettings::getPages("compare_page") : get_theme_mod( 'compare_page', 156 );
					    $showCompare = get_theme_mod('header_compare_show', false);
					    $wishlist_page = (is_listing(array('listing_five', 'listing_six'))) ? \uListing\Classes\StmListingSettings::getPages("wishlist_page") : null;
						$link = (is_listing(array('listing_five'))) ? stm_c_f_get_page_url('account_page') : stm_get_author_link('register');
						$link = (is_listing(array('listing_six'))) ? stm_c_six_get_page_url('account_page') : $link;
                        ?>
                    <div class="mobile-menu-holder">
                        <div class="account-lang-wrap">
							<?php get_template_part( 'partials/header/header-classified-five/parts/lang-switcher' ); ?>
                        </div>
                        <?php stm_getCurrencySelectorHtml(); ?>
                        <div class="mobile-menu-wrap">
                            <ul class="header-menu clearfix">
								<?php
								$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

								wp_nav_menu( array( 'theme_location' => $location, 'depth' => 5, 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false ) );
								?>
                                <?php if(is_listing() && $header_profile): ?>
                                    <li>
                                        <a href="<?php echo esc_url($link); ?>" class="lOffer-account">
                                            <?php echo esc_html__('Account', 'motors');?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if($showCompare): ?>
                                <li>
                                    <a class="lOffer-compare" href="<?php echo esc_url(get_the_permalink($compare_page)); ?>">
                                        <?php echo esc_html__('Compare', 'motors');?>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if(!empty($wishlist_page)) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_the_permalink($wishlist_page)); ?>"><?php esc_html_e('Wishlist', 'motors'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(is_listing()): ?>
                                    <li>
                                        <a class="add-listing-btn stm-button heading-font" href="<?php echo esc_html($header_listing_btn_link); ?>">
                                            <?php echo esc_html($header_listing_btn_text); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 hidden-sm hidden-xs">
                    <div class="stm-header-right">
                        <div class="main-menu">
                            <ul class="header-menu clearfix">
								<?php
								$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

								wp_nav_menu( array( 'menu' => $location, 'theme_location' => $location, 'depth' => 5, 'container' => false, 'menu_class' => 'header-menu clearfix', 'items_wrap' => '%3$s', 'fallback_cb' => false ) ); ?>
                            </ul>
                        </div>

                        <?php if(stm_is_listing_six()): ?>
                            <div class="head-phone-wrap">
                                <div class="ph-title heading-font">
									<?php echo stm_dynamic_string_translation("Header Equipment call free", $phoneLabel);?>
                                </div>
                                <div class="phone heading-font">
									<?php echo esc_html($phone);?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php get_template_part( 'partials/header/header-classified-five/parts/compare' ); ?>

                        <?php if(is_listing()) : ?>

                            <?php if($header_profile) get_template_part( 'partials/header/header-classified-five/parts/account' ) ; ?>

                            <div class="stm-c-f-add-btn-wrap">
                                <a class="add-listing-btn stm-button heading-font"
                                   href="<?php echo esc_html($header_listing_btn_link); ?>">
                                    <i class="stm-all-icon-listing_car_plus"></i>
                                    <?php echo esc_html($header_listing_btn_text); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div> <!--container-->
    </div> <!--header-main-->
</div><!--HEADER-->