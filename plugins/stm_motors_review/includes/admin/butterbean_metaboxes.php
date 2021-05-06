<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once STM_REVIEW_PATH . '/includes/admin/butterbean_helpers.php';

require_once 'tabs/title.php';
require_once 'tabs/review.php';
//require_once 'tabs/author.php';
require_once 'tabs/video.php';
require_once 'tabs/options.php';

add_action('butterbean_review_register', 'stm_magazine_review_register_manager_order', 10, 2);

function stm_magazine_review_register_manager_order($butterbean, $post_type) {

	if($post_type == 'stm_review') {
		$butterbean->register_manager(
			'stm_review_details',
			array(
				'label' => esc_html__('STM View settings', 'stm_motors_review'),
				'post_type' => 'stm_review',
				'context' => 'normal',
				'priority' => 'high'
			)
		);

		$manager = $butterbean->get_manager('stm_review_details');
		//register_review_title_metabox($manager, 'stm_motors_review');
		register_review_metabox($manager, 'stm_motors_review');
		//register_review_author_metabox($manager, 'stm_motors_review');
		register_review_video_metabox($manager, 'stm_motors_review');
		register_review_options_metabox($manager, 'stm_motors_review');
	}
}