<?php
//Get archive shop page id
if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();
}

$shopping_cart_boats = get_theme_mod('header_cart_show', false);

if($shopping_cart_boats): ?>
	<div class="pull-right hdn-767">
		<?php if(!empty($woocommerce_shop_page_id)): ?>
			<?php $items = (!empty(WC()->cart->cart_contents_count)) ? WC()->cart->cart_contents_count : 0; ?>
			<!--Shop archive-->
			<div class="help-bar-shop">
				<a
					href="<?php echo esc_url($woocommerce_shop_page_id); ?>"
					title="<?php esc_attr_e('Watch shop items', 'motors'); ?>"
				>
					<i class="stm-boats-icon-cart"></i>
					<span class="list-badge"><span class="stm-current-items-in-cart"><?php echo esc_html($items); ?></span></span>
				</a>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>