<?php
$logo_main = get_theme_mod('logo', get_template_directory_uri() . '/assets/images/tmp/logo_dealer_two.svg');

$fixed_header = get_theme_mod('header_sticky', false);
if (!empty($fixed_header) and $fixed_header) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = '';
}

if(is_listing()) {
	$fixed_header_class .= ' is-listing';
}

$phoneLabel = get_theme_mod( 'header_main_phone_label', 'Call Free' );
$phone = get_theme_mod( 'header_main_phone', '+1 212-226-3126' );
$top_bar_phone_mobile = get_theme_mod( 'top_bar_phone_mobile', true );

?>

<div class="header-listing <?php echo esc_attr($fixed_header_class ); ?>">

	<div class="container header-inner-content">
		<!--Logo-->
		<div class="listing-logo-main" style="margin-top: <?php echo get_theme_mod('logo_margin_top', '4'); ?>px;">
			<?php if (empty($logo_main)): ?>
				<a class="blogname" href="<?php echo esc_url(home_url('/')); ?>" title="<?php _e('Home', 'motors'); ?>">
					<h1><?php echo esc_attr(get_bloginfo('name')) ?></h1>
				</a>
			<?php else: ?>
				<a class="bloglogo" href="<?php echo esc_url(home_url('/')); ?>">
					<img
						src="<?php echo esc_url($logo_main); ?>"
						style="width: <?php echo get_theme_mod('logo_width', '160'); ?>px;"
						title="<?php esc_attr_e('Home', 'motors'); ?>"
						alt="<?php esc_attr_e('Logo', 'motors'); ?>"
					/>
				</a>
			<?php endif; ?>
		</div>

		<div class="listing-service-right clearfix" style="margin-top: <?php echo (get_theme_mod( 'menu_icon_top_margin', '0' )); ?>px;">
			<div class="listing-right-actions">
                <div class="head-phone-wrap">
                    <div class="ph-title heading-font">
                        <i class="fa fa-phone"></i>
                        <?php echo stm_dynamic_string_translation("Header Equipment call free", $phoneLabel);?>
                    </div>
                    <div class="phone heading-font">
                        <?php echo esc_html($phone);?>
                    </div>
                </div>

                <?php
                if(is_listing()) :
                    get_template_part('partials/header/parts/add_a_car');
                    get_template_part('partials/header/parts/profile');
                endif;
                ?>

				<?php get_template_part('partials/header/parts/cart') ?>

				<?php get_template_part('partials/header/parts/compare') ?>
			</div>

			<ul class="listing-menu clearfix" style="margin-top: <?php echo get_theme_mod('menu_top_margin', '0'); ?>px;">
				<?php
				$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';
				wp_nav_menu(array(
						'menu' => $location,
						'theme_location' => $location,
						'depth' => 3,
						'container' => false,
						'menu_class' => 'service-header-menu clearfix',
						'items_wrap' => '%3$s',
						'fallback_cb' => false
					)
				);
				?>
			</ul>
            <div class="mobile-menu-trigger visible-sm visible-xs">
                <span></span>
                <span></span>
                <span></span>
            </div>
		</div>

        <div class="mobile-menu-holder">
            <ul class="header-menu clearfix">
                <?php
				$location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';
                wp_nav_menu( array(
                        'menu' => $location,
                        'theme_location' => $location,
                        'depth' => 3,
                        'container' => false,
                        'items_wrap' => '%3$s',
                        'fallback_cb' => false
                    )
                );

				get_template_part('partials/header/parts/mobile_menu_items');
                ?>
            </ul>
        </div>
	</div>
</div>