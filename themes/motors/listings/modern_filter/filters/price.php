<?php
/**
*
 * @var $terms
 * @var $modern_filter
 * @var $modern_filter_unit
 *
 */

foreach ($terms as $term) {
    $prices[] = intval($term->name);
}

sort($prices);
?>

<div class="stm-accordion-single-unit stm-modern-price-unit <?php echo esc_attr($modern_filter_unit['slug']); ?>">
    <a class="title" data-toggle="collapse"
       href="#<?php echo esc_attr($modern_filter_unit['slug']) ?>" aria-expanded="true">
        <h5><?php esc_html_e($modern_filter_unit['single_name'], 'motors'); ?></h5>
        <span class="minus"></span>
    </a>
    <div class="stm-accordion-content">
        <div class="collapse in content"
             id="<?php echo esc_attr($modern_filter_unit['slug']); ?>">
            <div class="stm-accordion-content-wrapper stm-modern-filter-price">

                <div class="stm-price-range-unit">
                    <div class="stm-price-range"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-md-wider-right">
                        <input type="text" name="min_price" id="stm_filter_min_price"
                               readonly/>
                    </div>
                    <div class="col-md-6 col-sm-6 col-md-wider-left">
                        <input type="text" name="max_price" id="stm_filter_max_price"
                               readonly/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var stmOptions;
    (function ($) {
        $(document).ready(function () {
            stmOptions = {
                range: true,
                min: <?php echo esc_js($prices[0]); ?>,
                max: <?php echo esc_js($prices[count($prices) - 1]); ?>,
                values: [<?php echo esc_js($prices[0]); ?>, <?php echo esc_js($prices[count($prices) - 1]); ?>],
                step: 100,
                slide: function (event, ui) {
                    $("#stm_filter_min_price").val(ui.values[0]);
                    $("#stm_filter_max_price").val(ui.values[1]);
                }
            }

            $(".stm-price-range").slider(stmOptions);

            $("#stm_filter_min_price").val($(".stm-price-range").slider("values", 0));
            $("#stm_filter_max_price").val($(".stm-price-range").slider("values", 1));
        })
    })(jQuery);
</script>
