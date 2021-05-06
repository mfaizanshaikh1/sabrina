<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function stm_motors_review_enqueue_scripts_styles()
{
    wp_enqueue_style('stm-motors-review-font-style', STM_REVIEW_URL . '/assets/css/review-font-style.css', array());
    wp_enqueue_style('stm-motors-review-style', STM_REVIEW_URL . '/assets/css/style.css', array(), '1.1');

    wp_enqueue_script('jquery_cookie', STM_REVIEW_URL . '/assets/js/frontend/jquery.cookie.js', array('jquery'), null, true);
    wp_enqueue_script('motors_review', STM_REVIEW_URL . '/assets/js/motors-review.js', array('jquery'), null, false);
}

add_action('wp_enqueue_scripts', 'stm_motors_review_enqueue_scripts_styles');

