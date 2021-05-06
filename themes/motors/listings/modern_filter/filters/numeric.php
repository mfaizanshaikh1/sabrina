<?php
/**
 *
 * @var $terms
 * @var $modern_filter
 * @var $modern_filter_unit
 *
 */

?>

<div class="stm-accordion-single-unit <?php echo esc_attr($modern_filter_unit['slug']); ?>">
    <a class="title collapsed"
       data-toggle="collapse"
       href="#<?php echo esc_attr($modern_filter_unit['slug']) ?>"
       aria-expanded="false">
        <h5><?php esc_html_e($modern_filter_unit['single_name'], 'motors'); ?></h5>
        <span class="minus"></span>
    </a>
    <div class="stm-accordion-content">
        <div class="collapse content"
             id="<?php echo esc_attr($modern_filter_unit['slug']); ?>">
            <div class="stm-accordion-content-wrapper">

            </div>
        </div>
    </div>
</div>
