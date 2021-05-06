<?php
/**
*
 * @var $terms
 * @var $modern_filter
 * @var $modern_filter_unit
 *
 */
?>

<div class="stm-accordion-single-unit first-if <?php echo esc_attr($modern_filter_unit['slug']); ?>">
    <a class="title" data-toggle="collapse"
       href="#<?php echo esc_attr($modern_filter_unit['slug']) ?>" aria-expanded="true">
        <h5><?php esc_html_e($modern_filter_unit['single_name'], 'motors'); ?></h5>
        <span class="minus"></span>
    </a>
    <div class="stm-accordion-content">
        <div class="collapse in content"
             id="<?php echo esc_attr($modern_filter_unit['slug']); ?>">
            <div class="stm-accordion-content-wrapper">
                <?php foreach ($terms as $term): ?>
                    <?php if (!empty($_GET[$modern_filter_unit['slug']]) and $_GET[$modern_filter_unit['slug']] == $term->slug) { ?>
                    <script>
                        jQuery(window).on('load', function () {
                            var $ = jQuery;
                            $('input[name="<?php echo esc_attr($term->slug . '-' . $term->term_id); ?>"]').trigger('click');
                            $.uniform.update();
                        });
                    </script>
                <?php } ?>
                    <div class="stm-single-unit">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo esc_attr($term->slug . '-' . $term->term_id); ?>"
                                   data-name="<?php echo esc_attr($term->name); ?>"
                            />
                            <?php echo esc_attr($term->name); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>