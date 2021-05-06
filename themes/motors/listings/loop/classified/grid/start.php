<?php
$taxonomies = stm_get_taxonomies();

$categories = wp_get_post_terms(get_the_ID(), array_values($taxonomies));

$classes = array();

if(!empty($categories)) {
    foreach($categories as $category) {
        $classes[] = $category->slug.'-'.$category->term_id;
    }
}

if(empty($class)) $class = array();

$asSold = get_post_meta(get_the_ID(), 'car_mark_as_sold', true);

$col = (!empty(get_post_meta(stm_get_listing_archive_page_id(), 'quant_grid_items', true))) ? 12 / get_post_meta(stm_get_listing_archive_page_id(), 'quant_grid_items', true) : 4;

if(stm_is_dealer_two() && empty(get_post_meta(stm_get_listing_archive_page_id(), 'quant_grid_items', true))) {
    $col = 6;
}

$colClass = (stm_is_dealer_two()) ? 'col-md-' . $col . ' col-sm-' . $col . ' col-xs-12 col-xxs-12 ' : 'col-md-' . $col . ' col-sm-' . $col . ' col-xs-12 col-xxs-12 ';

if(!empty($class) && strpos($class[0], 'col-') !== false) {
	$colClass = '';
}

?>
<div
    class="<?php echo esc_attr($colClass); ?> stm-directory-grid-loop stm-isotope-listing-item all <?php if(!empty($asSold)) echo esc_attr('car-as-sold');?> <?php print_r(implode(' ', $classes)); ?> <?php print_r(implode(' ', $class)); ?>"
    data-price="<?php echo esc_attr($data_price) ?>"
    data-date="<?php echo get_the_date('Ymdhi') ?>"
    data-mileage="<?php echo esc_attr($data_mileage); ?>"
>
    <a href="<?php echo esc_url(get_the_permalink()); ?>" class="rmv_txt_drctn">