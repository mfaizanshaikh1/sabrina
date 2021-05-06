<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function stm_motors_review_admin_enqueue($hook)
{
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    //wp_enqueue_style('stm-listings-datetimepicker', STM_REVIEW_URL . '/assets/css/jquery.stmdatetimepicker.css', null, null, 'all');
    //wp_enqueue_script('stm-listings-datetimepicker', STM_REVIEW_URL . '/assets/js/jquery.stmdatetimepicker.js', array('jquery'), null, true);

	wp_enqueue_script('jquery-ui-datepicker');

    wp_enqueue_style('stm-listings-timepicker', STM_REVIEW_URL . '/includes/admin/butterbean/css/jquery.timepicker.css', null, null, 'all');
    wp_enqueue_script('stm-listings-timepicker', STM_REVIEW_URL . '/includes/admin/butterbean/js/jquery.timepicker.js', array('jquery'), null, true);

    wp_enqueue_media();

	wp_enqueue_script('stm-motors-review-js', STM_REVIEW_URL . '/assets/js/motors-review.js', array('jquery','jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-sortable'));

	wp_enqueue_style('stm_motors-review_awesome_font', STM_REVIEW_URL . '/assets/css/font-awesome.min.css');

	wp_enqueue_style('stm_motors_review_css', STM_REVIEW_URL . '/assets/css/style.css');
}

add_action('admin_enqueue_scripts', 'stm_motors_review_admin_enqueue');