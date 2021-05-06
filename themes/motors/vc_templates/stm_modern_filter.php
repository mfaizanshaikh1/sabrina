<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

$args = array(
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false,
    'pad_counts' => true,
);

/*Get modern Filter*/
$modern_filter = stm_get_car_modern_filter();

$query_args = array(
    'post_type' => stm_listings_post_type(),
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'paged' => false,
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'car_mark_as_sold',
            'value' => '',
            'compare' => 'NOT EXISTS'
        ),
        array(
            'key' => 'car_mark_as_sold',
            'value' => '',
            'compare' => '='
        )
    )
);

$listings = new WP_Query($query_args);

$listing_filter_position = get_theme_mod('listing_filter_position', 'left');
if (!empty($_GET['filter_position']) and $_GET['filter_position'] == 'right') {
    $listing_filter_position = 'right';
}

$sidebar_pos_classes = '';
$content_pos_classes = '';

if ($listing_filter_position == 'right') {
    $sidebar_pos_classes = 'col-md-push-9 col-sm-push-0';
    $content_pos_classes = 'col-md-pull-3 col-sm-pull-0';
}
?>
<script>
    var stmOptionsObj = new Object();
</script>

<div class="row" id="modern-filter-listing">
    <div class="col-md-3 col-sm-12 sidebar-sm-mg-bt <?php echo esc_attr($sidebar_pos_classes); ?>">
        <?php
        if (!empty($modern_filter)) {
            $counter = 0;
            foreach ($modern_filter as $modern_filter_unit) {
                $counter++;
                $terms = get_terms(array($modern_filter_unit['slug']), $args);

                if (!empty($modern_filter_unit['numeric']) && $modern_filter_unit['slug'] != 'price' && empty($modern_filter_unit['slider'])) {
                    stm_listings_load_template(
                        'modern_filter/filters/numeric',
                        compact('modern_filter', 'modern_filter_unit', 'terms')
                    );
                } else {

                    if (empty($modern_filter_unit['slider']) && $modern_filter_unit['slug'] != 'price') {
                        /*First one if ts not image goes on another view*/
                        if ($counter == 1 and empty($modern_filter_unit['use_on_car_modern_filter_view_images']) and !$modern_filter_unit['use_on_car_modern_filter_view_images']) {
                            if (!empty($terms)) {

                                stm_listings_load_template(
                                    'modern_filter/filters/checkbox',
                                    compact('modern_filter', 'modern_filter_unit', 'terms')
                                );
                                ?>

                            <?php } ?>
                        <?php } else { ?>
                            <!--if its not first one and have images-->
                            <?php if (!empty($modern_filter_unit['use_on_car_modern_filter_view_images'])) { ?>
                                <?php
                                if (!empty($terms)) {

                                    stm_listings_load_template(
                                        'modern_filter/filters/images',
                                        compact('modern_filter', 'modern_filter_unit', 'terms')
                                    );

                                    ?>

                                <?php } ?>
                                <!--All others-->
                            <?php } else { ?>
                                <?php
                                if (!empty($terms)) {

                                    stm_listings_load_template(
                                        'modern_filter/filters/checkbox',
                                        compact('modern_filter', 'modern_filter_unit', 'terms')
                                    );

                                    ?>
                                <?php }
                            }
                        }
                    } else {/*price*/
                        if (!empty($terms)) {
                            if ($modern_filter_unit['slug'] == 'price') {

                                stm_listings_load_template(
                                    'modern_filter/filters/price',
                                    compact('modern_filter', 'modern_filter_unit', 'terms')
                                );

                                ?>
                            <?php
                            } else {

                                stm_listings_load_template(
                                    'modern_filter/filters/slider',
                                    compact('modern_filter', 'modern_filter_unit', 'terms')
                                );
                            ?>

                            <?php }
                        } ?> <!--if terms price not empty-->
                    <?php } ?> <!--price-->
                <?php } ?> <!--price-->
            <?php } ?>
        <?php } ?>
    </div>
    <div class="col-md-9 col-sm-12 <?php echo esc_attr($content_pos_classes); ?>">
        <div class="stm-car-listing-sort-units stm-modern-filter-actions clearfix">
            <div class="stm-modern-filter-found-cars">
                <h4><span class="orange"><?php echo esc_attr($listings->found_posts); ?></span> <?php esc_html_e('Vehicles available', 'motors'); ?>
                </h4>
            </div>
            <?php
            $view_list = '';
            $view_grid = '';
            $view_type = stm_listings_input('view_type', get_theme_mod("listing_view_type", "list"));

            if (!empty($_GET['view_type'])) {
                if ($_GET['view_type'] == 'list') {
                    $view_list = 'active';
                } elseif ($_GET['view_type'] == 'grid') {
                    $view_grid = 'active';
                }
            } else {
                if ($view_type == 'list') {
                    $view_list = 'active';
                } elseif ($view_type == 'grid') {
                    $view_grid = 'active';
                }
            }

            ?>
            <div class="stm-view-by">
                <a href="?view_type=grid"
                   class="stm-modern-view view-grid view-type <?php echo esc_attr($view_grid); ?>">
                    <i class="stm-icon-grid"></i>
                </a>
                <a href="?view_type=list"
                   class="stm-modern-view view-list view-type <?php echo esc_attr($view_list); ?>">
                    <i class="stm-icon-list"></i>
                </a>
            </div>
            <div class="stm-sort-by-options clearfix">
                <span><?php esc_html_e('Sort by:', 'motors'); ?></span>
                <div class="stm-select-sorting">
                    <select>
						<?php echo stm_get_sort_options_html(); ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="modern-filter-badges">
            <ul class="stm-filter-chosen-units-list">

            </ul>
        </div>
        <?php if ($listings->have_posts()): ?>
            <?php if ($view_grid == 'active'): ?>
                <div class="row row-3 car-listing-row <?php if ($view_grid == 'active') echo esc_attr('car-listing-modern-grid'); ?>">
            <?php endif; ?>

            <div class="stm-isotope-sorting">

                <?php
                $template = 'partials/listing-cars/listing-grid-loop';
                if ($view_grid == 'active') {
                    if (stm_is_motorcycle()) {
                        $template = 'partials/listing-cars/motos/grid';
                    } elseif (stm_is_listing()) {
                        $template = 'partials/listing-cars/listing-grid-directory-loop';
                    } else {
                        $template = 'partials/listing-cars/listing-grid-loop';
                    }
                } else {
                    
                    if (stm_is_motorcycle()) {
                        $template = 'partials/listing-cars/motos/list';
                    } elseif (stm_is_listing()) {
                     /*    echo "<pre>";
                    var_dump("2");
                    echo "</pre>";
                    exit();*/
                        $template = 'partials/listing-cars/listing-list-directory-loop';
                    } else {
                        
                        $template = 'partials/listing-cars/listing-list-loop';
                    }
                }

                $modern_filter = true;
                ?>

                <?php while ($listings->have_posts()): $listings->the_post();
                    include(locate_template($template . '.php'));
                endwhile; ?>

                <a class="button stm-show-all-modern-filter stm-hidden-filter"><?php esc_html_e('Show all', 'motors'); ?></a>

            </div>

            <?php if ($view_grid == 'active'): ?>
                </div>
            <?php endif; ?>
        <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>
</div>