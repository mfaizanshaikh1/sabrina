<?php
if ( is_admin() ) {
	require_once get_template_directory() . '/admin/admin.php';
	/* Phone Number Patch */
	require_once( get_template_directory() . '/inc/autoload.php' );
}

define( 'STM_TEMPLATE_URI', get_template_directory_uri() );
define( 'STM_TEMPLATE_DIR', get_template_directory() );
define( 'STM_THEME_SLUG', 'stm' );
define( 'STM_INC_PATH', get_template_directory() . '/inc' );
define( 'STM_CUSTOMIZER_PATH', get_template_directory() . '/inc/customizer' );
define( 'STM_CUSTOMIZER_URI', get_template_directory_uri() . '/inc/customizer' );
if(!defined('MOTORS_DEMO_SITE')) define('MOTORS_DEMO_SITE', false);

register_sidebar( array(
       'name'          => __( 'Header Top Right', 'Motors' ),
       'id'            => 'hdr-cstm',
       'description'   => __( 'Appears in the header section of the site.', 'Motors' ),
       'before_widget' => '<div id="custom-hdr">',
       'after_widget'  => '</div>',
       'before_title'  => '',
       'after_title'   => '',
) );
//	Include path
$inc_path = get_template_directory() . '/inc';

//	Widgets path
$widgets_path = get_template_directory() . '/inc/widgets';

// Theme setups
require_once STM_CUSTOMIZER_PATH . '/customizer.class.php';

// Custom code and theme main setups
require_once( $inc_path . '/setup.php' );

// Cron Settings
require_once( $inc_path . '/cron.php' );

// Enqueue scripts and styles for theme
require_once( $inc_path . '/scripts_styles.php' );

// Multiple Currency
require_once( $inc_path . '/multiple_currencies.php' );

// Custom code for any outputs modifying
require_once( $inc_path . '/custom.php' );

// Required plugins for theme
require_once( $inc_path . '/tgm/tgm-plugin-registration.php' );

// Visual composer custom modules
if ( defined( 'WPB_VC_VERSION' ) && !defined('STM_MOTORS_CLASSIFIED_FIVE') ) {
	require_once( $inc_path . '/visual_composer.php' );
}

// Custom code for any outputs modifying with ajax relation
require_once( $inc_path . '/stm-ajax.php' );

// Custom code for filter output
//require_once( $inc_path . '/listing-filter.php' );
require_once( $inc_path . '/user-filter.php' );

//User
if ( is_listing() || stm_is_aircrafts() ) {
	require_once( $inc_path . '/user-extra.php' );
}

require_once( $inc_path . '/stm_single_dealer.php' );

//email template manager
require_once( $inc_path . '/email_template_manager/email_template_manager.php' );

//value my car
if ( is_listing( array( 'listing_two', 'listing_three' ) ) ) require_once( $inc_path . '/value_my_car/value_my_car.php' );

// Custom code for woocommerce modifying
if ( class_exists( 'WooCommerce' ) ) {

	if( class_exists('Subscriptio') || class_exists('RP_SUB')) {
		require_once 'inc/MultiplePlan.php';
	}

	require_once( $inc_path . '/woocommerce_setups.php' );
	if ( stm_is_rental() ) {
		require_once( $inc_path . '/woocommerce_setups_rental.php' );
	}

	if ( ( get_theme_mod( 'dealer_pay_per_listing', false ) ||
			get_theme_mod( 'dealer_payments_for_featured_listing', false ) ||
			get_theme_mod( 'enable_woo_online', false ) ) &&
			(is_listing() || stm_is_dealer_two() ) ) {
		require_once $inc_path . '/perpay.php';
	}
}

if(stm_is_dealer_two()) {
	require_once ($inc_path . '/dealer-two-helper.php');
}

if ( class_exists( '\\STM_GDPR\\STM_GDPR' ) ) {
	require_once( $inc_path . '/motors-gdpr.php' );
}

function stm_motors_get_plugin_path($plugin_slug, $wp_repository = false)
{
	$is_dev_mode = defined('STM_DEV_MODE') && STM_DEV_MODE === true;

	/*DEV mode is off and we have WP Repository*/
	if (!$is_dev_mode && $wp_repository) return null;

	/*DEV mode is off and is not a WP Repository*/
	if (!$is_dev_mode && !$wp_repository) return get_package($plugin_slug, 'zip');

	/*Only dev mode now*/
	$plugins_path = get_template_directory() . '/inc/tgm/plugins';
	$plugins_path = "{$plugins_path}/{$plugin_slug}.zip";

	/*DEV mode is on but no plugin uploaded locally */
	if (defined('STM_DEV_MODE') && !file_exists($plugins_path)) {
		return !$wp_repository ? get_package($plugin_slug, 'zip') : null;
	}

	/*So we have this plugin locally*/
	return $plugins_path;
}