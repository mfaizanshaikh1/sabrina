<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$review_pagination = array(
    'pagination' => esc_html__('Pagination', 'stm_motors_review'),
    'load_more' => esc_html__('Load More Button', 'stm_motors_review'),
);

STM_Customizer_Review::setPanels(array(
    'review' => array(
        'title' => esc_html__('Review', 'stm_motors_review'),
        'priority' => 40
    ),
));

$review_features = array(
	'review_archive_paginatin_style' => array(
        'label' => esc_html__('Review pagination type', 'stm_motors_review'),
		'type' => 'stm-select',
		'choices' => $review_pagination,
		'default' => 'pagination',
    ),
    'review_per_page' => array(
        'label' => esc_html__('Review per page', 'stm_motors_review'),
        'type' => 'text',
        'default' => 6
    )
);

STM_Customizer_Review::setSection('review_features', array(
	'title' => esc_html__('Review settings', 'stm_motors_review'),
	'panel' => 'review',
	'fields' => $review_features
));