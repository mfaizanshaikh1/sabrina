<?php
$logo_main = get_theme_mod('logo', get_template_directory_uri() . '/assets/images/tmp/logo.png');

$fixed_header = get_theme_mod('header_sticky', false);
if(!empty($fixed_header) and $fixed_header) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = 'header-listing-unfixed';
}

if(is_listing()) {
	$fixed_header_class .= ' is-listing';
}

$transparent_header = get_post_meta(get_the_id(), 'transparent_header', true);
$top_bar_login = get_theme_mod('top_bar_login', true);

$header_style = 'style="background-color:' . get_theme_mod('header_bg_color', '#23393d') . '";';
?>

<div class="header-rental header-main header-listing <?php echo esc_attr($fixed_header_class); ?>" <?php echo sanitize_text_field($header_style); ?>>

	<div class="container header-inner-content" data-bg="<?php echo sanitize_text_field(get_theme_mod('header_bg_color', '#23393d')); ?>">
		<!--Logo-->
		<div class="listing-logo-main" <?php echo sanitize_text_field('style="margin-top:' . get_theme_mod('logo_margin_top', 0) . 'px"') ?>>
			<?php if(empty($logo_main)): ?>
				<a class="blogname" href="<?php echo esc_url(home_url('/')); ?>" title="<?php _e('Home', 'motors'); ?>">
					<h1><?php echo esc_attr(get_bloginfo('name')) ?></h1>
				</a>
			<?php else: ?>
				<a class="bloglogo" href="<?php echo esc_url(home_url('/')); ?>">
					<img
						src="<?php echo esc_url( $logo_main ); ?>"
						style="width: <?php echo get_theme_mod( 'logo_width', '112' ); ?>px;"
						title="<?php _e('Home', 'motors'); ?>"
						alt="<?php esc_attr_e('Logo', 'motors'); ?>"
					/>
				</a>
			<?php endif; ?>
		</div>

		<div class="listing-service-right clearfix" style="margin-top: <?php echo (get_theme_mod( 'menu_icon_top_margin', '0' )); ?>px;">

			<div class="listing-right-actions clearfix">
                <?php $header_listing_btn_text = get_theme_mod('header_rental_header_phone', '709-458-2140'); ?>
				<a href="tel:<?php echo esc_attr($header_listing_btn_text); ?>" class="stm_rental_button heading-font">
					<i class="stm-rental-phone_circle"></i>
					<span><?php echo esc_html($header_listing_btn_text); ?></span>
				</a>

                <?php get_template_part('partials/header/parts/add_a_car'); ?>
                <?php get_template_part('partials/header/parts/profile'); ?>
                <?php get_template_part('partials/header/parts/cart'); ?>
                <?php get_template_part('partials/header/parts/compare'); ?>

                <?php if(!empty($top_bar_login) and $top_bar_login): ?>
				<div class="stm-rent-lOffer-account-unit">
					<a href="<?php echo esc_url(stm_get_author_link('register')); ?>" class="stm-rent-lOffer-account">
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
					<?php get_template_part('partials/user/private/mobile/user'); ?>
				</div>
                <?php endif; ?>

                <div class="listing-menu-mobile-wrapper">
                    <div class="stm-menu-trigger">
                        <span></span>
                        <span></span>
                        <span></span>
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


        <div class="stm-opened-menu-listing">
            <ul class="listing-menu-mobile heading-font clearfix">
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

                get_template_part('partials/header/parts/mobile_menu_items');
                ?>
            </ul>
            <?php get_template_part('partials/top', 'bar'); ?>
        </div>
	</div>
</div>