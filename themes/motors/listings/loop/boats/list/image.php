<?php $show_compare = get_theme_mod('show_listing_compare', false); ?>

<div class="image">
    <a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
        <div class="image-inner">
            <?php if (has_post_thumbnail()): ?>
                <?php
                $plchldr = get_stylesheet_directory_uri() . '/assets/images/boats-placeholders/boats-250.png';
                $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'stm-img-350-205');
				$imgX2 = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'stm-img-350-205-x-2');
                ?>
                <img
                    data-src="<?php echo esc_url((!empty($img[0])) ? $img[0] : $plchldr); ?>"
                    srcset="<?php echo esc_url(!empty($img[0]) ? $img[0] : $plchldr); ?> 1x, <?php echo esc_url(!empty($imgX2[0]) ? $imgX2[0] : $plchldr); ?> 2x"
	                src="<?php echo esc_url($plchldr); ?>"
                    class="lazy img-responsive"
                    alt="<?php echo stm_generate_title_from_slugs(get_the_id()); ?>"
                />

            <?php else : ?>
                <img
                    src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/boats-placeholders/boats-250.png'); ?>"
                    class="img-responsive"
                    alt="<?php esc_attr_e('Placeholder', 'motors'); ?>"
                />
            <?php endif; ?>
			<?php get_template_part('partials/listing-cars/listing-directory', 'badges'); ?>
        </div>
        <?php stm_get_boats_image_hover(get_the_ID()); ?>
        <!--Compare-->
        <?php if (!empty($show_compare) and $show_compare): ?>
            <div
                class="stm-listing-compare stm-compare-directory-new"
                data-id="<?php echo esc_attr(get_the_id()); ?>"
                data-title="<?php echo stm_generate_title_from_slugs(get_the_id(), false); ?>"
                data-toggle="tooltip" data-placement="left" title="<?php esc_attr_e('Add to compare', 'motors'); ?>">
                <i class="stm-boats-icon-add-to-compare"></i>
            </div>
        <?php endif; ?>
    </a>
</div>