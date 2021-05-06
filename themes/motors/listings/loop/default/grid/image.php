<?php
$size = 'stm-img-255-135';
$sizeRetina = 'stm-img-255-135-x-2';
$placeholder_path = 'plchldr255.png';

if(wp_is_mobile()){
	$size = 'stm-img-796-466';
    $placeholder_path = 'plchldr350.png';
}

if (stm_is_boats()) {
    $show_compare = get_theme_mod('show_listing_compare', false);
	$size = 'stm-img-350-205';
	$sizeRetina = 'stm-img-350-205-x-2';
	$placeholder_path = 'boats-placeholders/boats-250.png';
}

$col = (!empty(get_post_meta(stm_get_listing_archive_page_id(), 'quant_grid_items', true))) ? 12 / get_post_meta(stm_get_listing_archive_page_id(), 'quant_grid_items', true) : 4;

if($col == '6') {
	$size = 'stm-img-398-223';
	$sizeRetina = 'stm-img-398-223-x-2';
}

$placeholder_path = (stm_is_aircrafts()) ? 'ac_plchldr.jpg' : $placeholder_path;
?>

<div class="image">
    <?php if (has_post_thumbnail()): ?>
    <?php
        $img_placeholder = $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size);
		$imgX2 = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $sizeRetina);
    ?>
        <img
            data-src="<?php echo esc_url($img[0]); ?>"
            srcset="<?php echo esc_url($img[0]); ?> 1x, <?php echo esc_url($imgX2[0]); ?> 2x"
            src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path); ?>"
            class="lazy img-responsive"
            alt="<?php echo stm_get_img_alt(get_post_thumbnail_id(get_the_ID())); ?>"
        />
    <?php else: ?>
        <img
            src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path); ?>"
            class="img-responsive"
            alt="<?php esc_attr_e('Placeholder', 'motors'); ?>"
        />
    <?php endif; ?>
	<?php get_template_part('partials/listing-cars/listing-directory', 'badges'); ?>
    <?php if (stm_is_boats()) {
        stm_get_boats_image_hover(get_the_ID()); ?>
        <!--Compare-->
        <?php if (!empty($show_compare) and $show_compare): ?>
            <div
                class="stm-listing-compare stm-compare-directory-new"
                data-id="<?php echo esc_attr(get_the_id()); ?>"
                data-title="<?php echo stm_generate_title_from_slugs(get_the_id(), false); ?>"
                data-toggle="tooltip" data-placement="bottom" title="<?php esc_attr_e('Add to compare', 'motors'); ?>">
				<i class="stm-boats-icon-add-to-compare"></i>
            </div>
        <?php endif;
    } ?>
</div>