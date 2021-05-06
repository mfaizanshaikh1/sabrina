<?php
/*
Plugin Name: STM Importer
Plugin URI: https://stylemixthemes.com/
Description: STM Importer
Author: Stylemix Themes
Author URI: https://stylemixthemes.com/
Text Domain: stm_importer
Version: 4.8.1
*/

define('STM_CONFIGURATIONS_PATH', dirname(__FILE__));

require_once(STM_CONFIGURATIONS_PATH . '/helpers/set_hb_options.php');
require_once(STM_CONFIGURATIONS_PATH . '/helpers/content.php');
require_once(STM_CONFIGURATIONS_PATH . '/helpers/theme_options.php');
require_once(STM_CONFIGURATIONS_PATH . '/helpers/slider.php');
require_once(STM_CONFIGURATIONS_PATH . '/helpers/widgets.php');
require_once(STM_CONFIGURATIONS_PATH . '/helpers/set_content.php');
require_once(STM_CONFIGURATIONS_PATH . '/helpers/classes/uListingImport.php');

function stm_demo_import_content()
{
    if(current_user_can('administrator') || current_user_can('editor')) {
        $layout = 'car_dealer';

        if ( !empty( $_GET['demo_template'] ) ) {
            $layout = sanitize_title( $_GET['demo_template'] );
        }

        update_option( 'stm_motors_chosen_template', $layout );

        if($layout == 'rental_two') {
			stm_importer_create_taxonomy();
		}

        if($layout == 'listing_five' || $layout == 'listing_six' ) {
			\stmImporter\uListingImport::init();
		}

        stm_set_hb_options( $layout );

        /*Import theme options*/
        stm_get_layout_options( $layout );

        /*Import content*/
        stm_theme_import_content( $layout );

        /*Import sliders*/
        stm_theme_import_sliders( $layout );

        /*Import Widgets*/
        stm_theme_import_widgets( $layout );

        /*Set menu and pages*/
        stm_set_content_options( $layout );

        apply_filters('import_ulisting_data', array());

        do_action( 'stm_importer_done', $layout );

        wp_send_json( array(
            'url' => get_home_url( '/' ),
            'title' => esc_html__( 'View site', 'stm_domain' ),
            'theme_options_title' => esc_html__( 'Theme options', 'stm_domain' ),
            'theme_options' => esc_url_raw( admin_url( 'customize.php' ) )
        ) );
    }

    die();
}

add_action('wp_ajax_stm_demo_import_content', 'stm_demo_import_content');

