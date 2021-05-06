<?php
function register_review_author_metabox($manager, $stmDomain)
{
	/*Register sections*/
	$manager->register_section(
		'stm_author_details',
		array(
			'label' => esc_html__('Author', $stmDomain),
			'icon' => 'fa fa-bookmark'
		)
	);

	/*Register controls*/
	$fields = array(
		'review_author_name' => array(
			'label' => esc_html__('Author name', $stmDomain),
			'section' => 'stm_author_details',
			'validate' => 'stm_motors_review_no_validate'
		),
		'review_author_desc' => array(
			'label' => esc_html__('Author excerpt', $stmDomain),
			'section' => 'stm_author_details',
			'validate' => 'stm_motors_review_no_validate'
		),
		'review_author_avatar' => array(
			'type' => 'image',
			'label' => esc_html__('Author photo', $stmDomain),
			'section' => 'stm_author_details',
			'size' => 'm-r-100-100',
			'validate' => 'stm_motors_review_validate_image'
		)
	);

	$fields = apply_filters('stm_review_title_fields', $fields);

	foreach($fields as $field => $field_info) {
		/*Register control*/
		$type = (!empty($field_info['type'])) ? $field_info['type'] : 'text';
		$validate = (!empty($field_info['validate'])) ? $field_info['validate'] : 'stm_motors_review_no_validate';
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

