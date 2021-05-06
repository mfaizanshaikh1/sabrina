<?php
$logo_main = get_theme_mod('logo', get_template_directory_uri() . '/assets/images/tmp/logo-boats.png');
$compare_page = get_theme_mod( 'compare_page', 156 );
$shopping_cart_boats = get_theme_mod('header_cart_show', false);
//Get archive shop page id
if( function_exists('WC')) {
    $woocommerce_shop_page_id = wc_get_cart_url();
}
?>

<div class="stm-boats-mobile-header">
	<?php if(empty($logo_main)): ?>
		<a class="blogname" href="<?php echo esc_url(home_url('/')); ?>" title="<?php _e('Home', 'motors'); ?>">
			<h1><?php echo esc_attr(get_bloginfo('name')) ?></h1>
		</a>
	<?php else: ?>
		<a class="bloglogo" href="<?php echo esc_url(home_url('/')); ?>">
			<img
				src="<?php echo esc_url( $logo_main ); ?>"
				style="width: <?php echo get_theme_mod( 'logo_width', '160' ); ?>px;"
				title="<?php esc_attr_e('Home', 'motors'); ?>"
				alt="<?php esc_attr_e('Logo', 'motors'); ?>"
				/>
		</a>
	<?php endif; ?>

	<div class="stm-menu-boats-trigger">
		<span></span>
		<span></span>
		<span></span>
	</div>
</div>

<div class="stm-boats-mobile-menu">
	<div class="inner">
		<div class="inner-content">
			<ul class="listing-menu heading-font clearfix">
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
			<?php get_template_part('partials/top-bar-boats', 'mobile'); ?>
		</div>
	</div>
</div>