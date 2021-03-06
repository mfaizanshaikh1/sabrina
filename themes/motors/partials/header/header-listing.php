<?php
$logo_main = get_theme_mod('logo', get_template_directory_uri() . '/assets/images/tmp/logo.png');

$fixed_header = get_theme_mod('header_sticky', false);
if(!empty($fixed_header) and $fixed_header) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = 'header-listing-unfixed';
}

$transparent_header = get_post_meta(get_the_id(), 'transparent_header', '');

if(empty($transparent_header)) {
	$transparent_header_class = 'listing-nontransparent-header';
} else {
	$transparent_header_class = '';
}

if(empty($_COOKIE['compare_ids'])) {
	$_COOKIE['compare_ids'] = array();
}

$compare_page = get_theme_mod( 'compare_page', 156 );
$showCompare = get_theme_mod('header_compare_show', true);

$header_bg = get_theme_mod('header_listing_layout_image_bg');

?>

<div class="header-listing <?php echo esc_attr($fixed_header_class.' '.$transparent_header_class); ?>">

	<div class="listing-header-bg" <?php if(!empty($header_bg)): ?>style="background-image: url('<?php echo esc_url($header_bg); ?>')"<?php endif; ?>></div>
	<div class="container header-inner-content">
		<!--Logo-->
		<div class="listing-logo-main" style="margin-top: <?php echo get_theme_mod( 'logo_margin_top', '17' ); ?>px;">
			<?php if(empty($logo_main)): ?>
				<a class="blogname" href="<?php echo esc_url(home_url('/')); ?>" title="<?php esc_attr_e('Home', 'motors'); ?>">
					<h1><?php echo esc_attr(get_bloginfo('name')) ?></h1>
				</a>
			<?php else: ?>
				<a class="bloglogo" href="<?php echo esc_url(home_url('/')); ?>">
					<img
						src="<?php echo esc_url( $logo_main ); ?>"
						style="width: <?php echo get_theme_mod( 'logo_width', '112' ); ?>px;"
						title="<?php esc_attr_e('Home', 'motors'); ?>"
						alt="<?php esc_attr_e('Logo', 'motors'); ?>"
						/>
				</a>
			<?php endif; ?>
		</div>
		<div class="listing-service-right clearfix" style="margin-top: <?php echo (get_theme_mod( 'menu_icon_top_margin', '0' )); ?>px;">
			<div class="listing-right-actions clearfix">
				<?php if(is_listing()): ?>
                    <?php
					    $header_profile = get_theme_mod('header_show_profile', false);

                        $header_listing_btn_link = get_theme_mod('header_listing_btn_link', '/add-car');
                        $header_listing_btn_text = get_theme_mod('header_listing_btn_text', 'Add your item');
                    ?>
                    <?php if(!empty($header_listing_btn_link) and !empty($header_listing_btn_text)): ?>
                        <a href="<?php echo esc_url($header_listing_btn_link); ?>" class="listing_add_cart heading-font">
                            <div>
                                <i class="<?php echo (!is_listing(array('listing_two', 'listing_three'))) ? 'stm-service-icon-listing_car_plus' : 'stm-lt-icon-add_car'; ?>"></i>
                                <?php stm_dynamic_string_translation_e('Add A Car Button label in header', $header_listing_btn_text); ?>
                            </div>
                        </a>
                    <?php endif; ?>
                    <?php if($header_profile) : ?>
                        <div class="pull-right">
                            <div class="lOffer-account-unit">
                                <a href="<?php echo esc_url(stm_get_author_link('register')); ?>" class="lOffer-account">
                                    <?php
                                        if(is_user_logged_in()): $user_fields = stm_get_user_custom_fields('');
                                            if(!empty($user_fields['image'])):
                                                ?>
                                            <div class="stm-dropdown-user-small-avatar">
                                                <img src="<?php echo esc_url($user_fields['image']); ?>" class="im-responsive"/>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <i class="stm-service-icon-user"></i>
                                </a>
                                <?php get_template_part('partials/user/user', 'dropdown'); ?>
                                <?php get_template_part('partials/user/private/mobile/user'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
				<?php if(!empty($compare_page) && $showCompare): ?>
					<div class="pull-right">
						<a class="lOffer-compare"
							href="<?php echo esc_url(get_the_permalink($compare_page)); ?>"
							title="<?php esc_attr_e('Watch compared', 'motors'); ?>"
                            <?php if(!is_listing()) echo 'style="margin-right: 0 !important;"'?>
                        >
							<i class="list-icon stm-service-icon-listing-compare"></i>
							<span class="list-badge"><span class="stm-current-cars-in-compare" data-contains="compare-count"></span></span>
						</a>
					</div>
				<?php endif; ?>

				<div class="listing-menu-mobile-wrapper">
					<div class="stm-menu-trigger">
						<span></span>
						<span></span>
						<span></span>
					</div>
					<div class="stm-opened-menu-listing">
						<ul class="listing-menu-mobile heading-font visible-xs visible-sm clearfix">
							<?php
							$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';
							wp_nav_menu( array(
									'menu'              => $location,
									'theme_location'    => $location,
									'depth'             => 3,
									'container'         => false,
									'menu_class'        => 'service-header-menu clearfix',
									'items_wrap'        => '%3$s',
									'fallback_cb' => false
								)
							);
							?>

							<?php if(!empty($compare_page) && $showCompare): ?>
								<li class="stm_compare_mobile"><a href="<?php echo esc_url(get_the_permalink($compare_page)); ?>"><?php _e('Compare', 'motors'); ?></a></li>
							<?php endif; ?>
						</ul>
						<?php get_template_part('partials/top', 'bar'); ?>
					</div>
				</div>

			</div>

			<ul class="listing-menu clearfix" style="margin-top: <?php echo (get_theme_mod( 'menu_top_margin', '17' )); ?>px;">
				<?php
				$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

				wp_nav_menu( array(
						'menu'              => $location,
						'theme_location'    => $location,
						'depth'             => 3,
						'container'         => false,
						'menu_class'        => 'service-header-menu clearfix',
						'items_wrap'        => '%3$s',
						'fallback_cb' => false
					)
				);
				?>
			</ul>
		</div>
	</div>
</div>