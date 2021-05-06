<?php

add_action('init', 'motors_review_update_existing_shortcodes');

function motors_review_update_existing_shortcodes()
{

	if (function_exists('vc_add_params')) {

		vc_map( array(
			'html_template' => STM_REVIEW_PATH . '/vc_templates/stm_pros_cons.php',
			"name" => esc_html__('Stm Pros and Cons', 'stm_motors_review'),
			"base" => "stm_pros_cons",
			"content_element" => true,
            'category' => __('STM Magazine', 'motors'),
			"params" => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Select Pros or Cons', 'stm_motors_review'),
					'param_name' => 'stm_pros_cons_type',
					'value' => array(
						'Pros' => 'pros',
						'Cons' => 'cons'
					)
				),
				array(
					'type' => 'textarea_html',
					'heading' => esc_html__( 'Pros & Cons', 'motors' ),
					'param_name' => 'content'
				)
			)
		) );
	}
}


if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_Stm_Pros_Cons extends WPBakeryShortCode {
	}
}