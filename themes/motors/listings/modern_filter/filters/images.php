<?php
/**
 *
 * @var $terms
 * @var $modern_filter
 * @var $modern_filter_unit
 *
 */

$listing_rows_numbers_default_expanded = 'false';
if (isset($modern_filter_unit['listing_rows_numbers_default_expanded']) AND $modern_filter_unit['listing_rows_numbers_default_expanded'] == 'open') {
    $listing_rows_numbers_default_expanded = 'true';
}
?>


<div class="stm-accordion-single-unit stm-modern-filter-unit-images <?php echo esc_attr($modern_filter_unit['slug']); ?>">
    <a class="title <?php echo (esc_attr($listing_rows_numbers_default_expanded) == 'false') ? 'collapsed' : '' ?>"
       data-toggle="collapse"
       href="#<?php echo esc_attr($modern_filter_unit['slug']) ?>"
       aria-expanded="<?php echo esc_attr($listing_rows_numbers_default_expanded); ?>">
        <h5><?php esc_html_e($modern_filter_unit['single_name'], 'motors'); ?></h5>
        <span class="minus"></span>
    </a>
    <div class="stm-accordion-content">
        <div class="collapse content <?php echo (esc_attr($listing_rows_numbers_default_expanded) == 'true') ? 'in' : '' ?>"
             id="<?php echo esc_attr($modern_filter_unit['slug']); ?>">
            <div class="stm-accordion-content-wrapper">
                <div class="stm-single-unit-wrapper">
                    <?php $number_of_images = 0; ?>
                    <?php $images = 0;
                    foreach ($terms as $term) {
                        $images++; ?>
                        <?php if (!empty($_GET[$modern_filter_unit['slug']]) and $_GET[$modern_filter_unit['slug']] == $term->slug) { ?>
                        <script>
                            jQuery(window).on('load', function () {
                                var $ = jQuery;
                                $('input[name="<?php echo esc_attr($term->slug . '-' . $term->term_id); ?>"]').trigger('click');
                                $.uniform.update();
                            });
                        </script>
                    <?php }

                    $category_image = '';
                    $image = get_term_meta($term->term_id, 'stm_image', true);
                    if (!empty($image)) {
                        $image = wp_get_attachment_image_src($image, 'stm-img-190-132');
                        $category_image = $image[0];
                    }

                    if (!empty($image)){
                    $number_of_images++; ?>
                        <div class="stm-single-unit-image">
                            <label>
                                <?php if (!empty($category_image)) { ?>
                                    <span class="image">
                                        <img class="img-reponsive"
                                             src="<?php echo esc_url( $category_image ); ?>"
                                             alt="<?php esc_attr_e( 'Brand', 'motors' ); ?>"/>
                                    </span>
                                <?php } ?>
                                <input type="checkbox"
                                       name="<?php echo esc_attr($term->slug . '-' . $term->term_id); ?>"
                                       data-name="<?php echo esc_attr($term->name); ?>"
                                />
                                <?php echo esc_attr($term->name); ?>
                            </label>
                        </div>
                    <?php } ?>
                    <?php }
                    if ($number_of_images < count($terms)) { ?>
                        <div class="stm-modern-view-others">
                            <a href=""><?php echo esc_html_e('View all', 'motors'); ?></a>
                        </div>
                        <div class="stm-modern-filter-others">
                            <?php $non_images = 0;
                            foreach ($terms as $term) {
                                $non_images++; ?>

                                <?php
                                $category_image = '';
                                $image = get_term_meta($term->term_id, 'stm_image', true);
                                if (!empty($image)) {
                                    $image = wp_get_attachment_image_src($image, 'stm-img-190-132');
                                    $category_image = $image[0];
                                }

                                if (empty($image)) {
                                    ?>
                                    <div class="stm-single-unit-image stm-no-image">
                                        <label>
                                            <input type="checkbox"
                                                   name="<?php echo esc_attr($term->slug . '-' . $term->term_id); ?>"
                                                   data-name="<?php echo esc_attr($term->name); ?>"
                                            />
                                            <?php echo esc_attr($term->name); ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>