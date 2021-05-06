<?php

// Do not allow directly accessing this file.
if ( !defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

if ( !stm_check_hacked_categories() ) {
    exit( 'Direct script access denied.' );
}

if ( !empty( $_POST['listing_category_patch_field'] ) ) {

    if(!wp_verify_nonce( $_POST['listing_category_patch_field'], 'listing_category_patch_action' ) && !current_user_can('administrator')) {
        exit( 'Direct script access denied.' );
    } else {
        $optArr = array();
        foreach ($_POST['listing_category'] as $k => $opt) {
            $optArr[] = array(
                'single_name' => ucfirst(str_replace(array('-', '_'), ' ', $opt)),
                'plural_name' => ucfirst(str_replace(array('-', '_'), ' ', $opt)),
                'slug' => $opt,
                'font' => '',
                'numeric' => false,
                'use_on_single_listing_page' => false,
                'use_on_car_listing_page' => false,
                'use_on_car_archive_listing_page' => false,
                'use_on_single_car_page' => false,
                'use_on_map_page' => false,
                'use_on_car_filter' => false,
                'use_on_car_modern_filter' => false,
                'use_on_car_modern_filter_view_images' => false,
                'use_on_car_filter_links' => false,
                'use_on_directory_filter_title' => false,
                'number_field_affix' => '',
                'enable_checkbox_button' => false,
            );
        }

        update_option('stm_vehicle_listing_options', $optArr);
        wp_safe_redirect(admin_url( "edit.php?post_type=listings&page=listing_categories&after_patch=listing_categories" ));
    }
}

global $wpdb;
$options = $wpdb->get_results( "SELECT taxonomy FROM $wpdb->term_taxonomy GROUP BY taxonomy");

?>
<div class="wrap about-wrap stm-admin-wrap  stm-admin-demos-screen">
    <h2>Listing categories patch</h2>
    <div class="stm-admin-message" style="padding: 20px; margin-bottom: 20px; clear: both;">
        <p style="font-size: 20px;">WARNING!!!</p>
        This patch created for issue with Listing Categories renaming like:<br />
        <code style="background: #777;"><?php echo esc_html("<script type=text/javascript src='....'></script>酒店星级. ") ?></code><br />
        Patch deletes all affected Listing Categories, but Terms won’t be lost.<br />
    </div>
    <h4>Please select ONLY Listing Categories and patch re-creates them for you with generated “Singular Name” and “Plural Name”:</h4>
    <form action="<?php echo esc_url( admin_url( "admin.php?page=stm-admin-category-patch" ) ) ?>" method="post">
        <?php wp_nonce_field( 'listing_category_patch_action', 'listing_category_patch_field' ); ?>
        <ul style="display: flex; flex-direction: row; flex-wrap: wrap;">
            <?php for ($i=0;$i<count($options);$i++) :?>
                <li style="width: 33.33%;">
                    <label>
                        <input type="checkbox" name="listing_category[]" value="<?php echo esc_attr( $options[$i]->taxonomy ); ?>"/>
                        <?php echo esc_html( $options[$i]->taxonomy ); ?>
                    </label>
                </li>
            <?php endfor; ?>
        </ul>
        <input type="submit" class="button button-primary button-large" value="Run patch">
    </form>
</div>
