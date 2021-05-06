<?php
if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();
}

$shopping_cart_boats = get_theme_mod('header_cart_show', false);

$compare_page = get_theme_mod( 'compare_page', 156 );
$show_compare_page = get_theme_mod( 'header_compare_show', false );

$header_listing_btn_link = get_theme_mod('header_listing_btn_link', '/add-a-car');
$header_listing_btn_text = get_theme_mod('header_listing_btn_text', esc_html__('Add your item', 'motors'));

$header_profile = get_theme_mod('header_show_profile', false);
?>
<?php if(is_listing() && !empty($header_listing_btn_link) and !empty($header_listing_btn_text)): ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a href="<?php echo esc_url($header_listing_btn_link); ?>">
			<span>
				<?php stm_dynamic_string_translation_e('Listing Button Text', $header_listing_btn_text); ?>
			</span>
		</a>
	</li>
<?php endif; ?>
<?php if(is_listing() && $header_profile): ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a href="<?php echo esc_url( stm_get_author_link( 'register' ) ); ?>">
			<span>
				<?php esc_html_e('Profile', 'motors'); ?>
			</span>
		</a>
	</li>
<?php endif; ?>
<?php if($shopping_cart_boats && !empty($woocommerce_shop_page_id)): ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<?php $items = WC()->cart->cart_contents_count; ?>
		<!--Shop archive-->
		<a href="<?php echo esc_url($woocommerce_shop_page_id); ?>" title="<?php esc_attr_e('Watch shop items', 'motors'); ?>" >
			<span><?php esc_html_e('Cart', 'motors'); ?></span>
			<?php if($items > 0): ?><span class="list-badge"><span class="stm-current-items-in-cart"><?php echo esc_attr($items); ?></span></span><?php endif; ?>
		</a>
	</li>
<?php endif; ?>

<?php if(!empty($compare_page) && $show_compare_page): ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a href="<?php echo esc_url(get_the_permalink($compare_page)); ?>" title="<?php esc_attr_e('Watch compared', 'motors'); ?>">
			<span><?php esc_html_e('Compare', 'motors'); ?></span>
			<?php if(!empty($_COOKIE['compare_ids']) and count($_COOKIE['compare_ids'])): ?><span class="list-badge"><span class="stm-current-cars-in-compare"><?php echo esc_attr(count($_COOKIE['compare_ids']));?></span></span><?php endif; ?>
		</a>
	</li>
<?php endif; ?>