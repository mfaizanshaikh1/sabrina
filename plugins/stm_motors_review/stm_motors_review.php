<?php
/**
Plugin Name: Motors - Review
Plugin URI: http://stylemixthemes.com/
Description: Manage motors review
Author: StylemixThemes
Author URI: http://stylemixthemes.com/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: stm_motors_review
Version: 1.3.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'STM_REVIEW_PATH', dirname( __FILE__ ) );
define( 'STM_REVIEW_URL', plugins_url( '', __FILE__ ) );
define( 'STM_REVIEW', 'stm_motors_review' );

define( 'STM_REVIEW_IMAGES', STM_REVIEW_URL . '/includes/admin/butterbean/images/' );

if ( ! is_textdomain_loaded( 'stm_motors_review' ) ) {
	load_plugin_textdomain( 'stm_motors_review', false, 'stm_motors_review/languages' );
}

require_once __DIR__ . '/includes/review-post-type/post-types.php';
require_once __DIR__ . '/includes/ajax-actions.php';
require_once __DIR__ . '/includes/setup.php';
require_once __DIR__ . '/includes/query.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/templates.php';
require_once __DIR__ . '/includes/enqueue.php';
require_once __DIR__ . '/includes/visual_composer.php';
require_once __DIR__ . '/includes/admin/SubContentReviewEditor.php';

if ( is_admin() ) {
	require_once __DIR__ . '/includes/admin/enqueue.php';
	require_once __DIR__ . '/includes/admin/butterbean_metaboxes.php';
}