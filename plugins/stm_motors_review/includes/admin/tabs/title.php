<?php
function register_review_title_metabox($manager, $stmDomain)
{
	/*Register sections*/
	$manager->register_section(
		'stm_title_details',
		array(
			'label' => esc_html__('Title details', $stmDomain),
			'icon' => 'fa fa-bookmark'
		)
	);

	/*Register controls*/
	$fields = array(
		'review_title_prefix' => array(
			'label' => esc_html__('Title prefix', $stmDomain),
			'section' => 'stm_title_details',
			'validate' => 'stm_motors_review_no_validate'
		)
	);

	$fields = apply_filters('stm_review_title_fields', $fields);

	foreach($fields as $field => $field_info) {
		/*Register control*/
		$type = (!empty($field_info['type'])) ? $field_info['type'] : 'text';
		$validate = (!empty($field_info['validate'])) ? $field_info['validate'] : 'stm_listings_no_validate';
		$manager->register_control(
			$field,
			array(
				'type' => $type,
				'section' => $field_info['section'],
				'label' => $field_info['label'],
				'attr' => array(
					'class' => 'widefat',
				)
			)
		);

		/*Register setting*/
		$manager->register_setting(
			$field,
			array(
				'sanitize_callback' => $validate,
			)
		);
	}

}