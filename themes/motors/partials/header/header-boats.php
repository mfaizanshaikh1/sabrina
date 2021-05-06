<?php
$logo_main = get_theme_mod('logo', get_template_directory_uri() . '/assets/images/tmp/logo-boats.png');

$fixed_header = get_theme_mod('header_sticky', false);
if(!empty($fixed_header) and $fixed_header) {
	$fixed_header_class = 'header-listing-fixed';
} else {
	$fixed_header_class = '';
}

if(is_listing()) {
	$fixed_header_class .= ' is-listing';
}

$transparent_header = get_post_meta(get_the_id(), 'transparent_header', true);

if(empty($transparent_header)) {
	$transparent_header_class = 'listing-nontransparent-header';
} else {
	$transparent_header_class = '';
}

?>

<div class="header-listing header-listing-boats <?php echo esc_attr($fixed_header_class.' '.$transparent_header_class); ?>">

	<div class="container header-inner-content">
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
						style="width: <?php echo get_theme_mod( 'logo_width', '160' ); ?>px;"
						title="<?php esc_attr_e('Home', 'motors'); ?>"
						alt="<?php esc_attr_e('Logo', 'motors'); ?>"
						/>
				</a>
			<?php endif; ?>
		</div>

		<div class="listing-service-right clearfix" style="margin-top: <?php echo (get_theme_mod( 'menu_icon_top_margin', '0' )); ?>px;">
			<div class="listing-right-actions">
				<?php get_template_part('partials/header/parts/add_a_car'); ?>

				<?php get_template_part('partials/header/parts/profile'); ?>

				<?php get_template_part('partials/header/parts/cart') ?>

				<?php get_template_part('partials/header/parts/compare') ?>
			</div>

			<ul class="listing-menu boats-menu-<?php echo (wp_is_mobile()) ? 'ipad' : 'desktop'; ?> clearfix" style="margin-top: <?php echo (get_theme_mod( 'menu_top_margin', '0' )); ?>px;">
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