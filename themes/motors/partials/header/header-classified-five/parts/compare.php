<?php
$showCompare = get_theme_mod('header_compare_show', true);
$compare_page = (stm_is_listing_five() || stm_is_listing_six()) ? \uListing\Classes\StmListingSettings::getPages("compare_page") : get_theme_mod( 'compare_page', 156 );
$compareIcon = (stm_is_listing_five() || stm_is_listing_six()) ? 'stm-all-icon-listing-compare' : 'list-icon stm-boats-icon-compare-boats';

if ($compare_page):
    $compareCookie = (!empty($_COOKIE['ulisting_compare'])) ? (array) $_COOKIE['ulisting_compare'] : array();
    $compareCount = (!empty($compareCookie)) ? count((array) json_decode(stripslashes($compareCookie[0]))) : 0;

    if($showCompare) :
?>
<div class="stm-compare">
    <a class="lOffer-compare" href="<?php echo esc_url(get_the_permalink($compare_page)); ?>"
       title="<?php esc_attr_e('Watch compared', 'motors'); ?>">
        <i class="<?php echo esc_attr($compareIcon); ?>"></i>
        <span class="list-badge">
            <span class="stm-current-cars-in-compare">
                <?php if ($compareCount != 0) {
                    echo esc_html($compareCount);
                } ?>
            </span>
        </span>
    </a>
</div>
<?php
    endif;
endif;
?>