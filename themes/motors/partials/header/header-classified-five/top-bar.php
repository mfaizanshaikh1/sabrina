<?php
$bgColor = get_theme_mod('top_bar_bg_color', '');
$top_bar_address = get_theme_mod('top_bar_address', '');
$top_bar_working_hours = get_theme_mod( 'top_bar_working_hours', '' );
$header_address_url = get_theme_mod('header_address_url', '');
$top_bar_phone = get_theme_mod( 'top_bar_phone', '' );
?>

<div class="top-bar-wrap">
	<div class="container">
		<div class="stm-c-f-top-bar">
			
			<?php
            get_template_part( 'partials/header/header-classified-five/parts/lang-switcher');
            stm_getCurrencySelectorHtml();
            ?>
            <?php if(!empty($top_bar_address)) : ?>
                <div class="stm-top-address-wrap">
                    <span id="top-bar-address" class="<?php if( !empty($header_address_url) ) echo 'fancy-iframe'; ?>" data-iframe="true" data-src="<?php echo esc_url($header_address_url); ?>">
                        <i class="fa fa-map-marker"></i> <?php stm_dynamic_string_translation_e('Top Bar Address', $top_bar_address ); ?>
                    </span>
                </div>
            <?php endif; ?>
            <?php if(!empty($top_bar_working_hours)) : ?>
                <div class="stm-top-address-wrap">
                    <span id="top-bar-info">
                        <i class="fa fa-clock-o"></i><?php stm_dynamic_string_translation_e('Top Bar Working Hours', $top_bar_working_hours ); ?>
                    </span>
                </div>
            <?php endif; ?>
            <?php if(!empty($top_bar_phone)) : ?>
                <div class="stm-top-address-wrap">
                    <span id="top-bar-phone">
                        <i class="fa fa-phone"></i> <a href="tel:<?php echo esc_attr($top_bar_phone); ?>"><?php stm_dynamic_string_translation_e('Top Bar Phone', $top_bar_phone ); ?></a>
                    </span>
                </div>
            <?php endif; ?>
			
            <div class="pull-right">
                <?php get_template_part( 'partials/header/header-classified-five/parts/socials'); ?>
                <?php if(!is_user_logged_in()) get_template_part( 'partials/header/header-classified-five/parts/login-reg-links'); ?>
            </div>
			
		</div>
	</div>
</div>