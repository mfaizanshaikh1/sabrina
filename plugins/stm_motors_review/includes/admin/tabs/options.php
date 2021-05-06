<?php
function register_review_options_metabox($manager, $stmDomain)
{

	$recentTypes = array(
		'all' => 'All',
		'standard' => 'Standard',
		'video' => 'Video'
	);

	/*Register sections*/
	$manager->register_section(
		'stm_review_post_options',
		array(
			'label' => esc_html__('Post options', $stmDomain),
			'icon' => 'fa fa-bookmark'
		)
	);

	/*Register controls*/
	$fields = array(
		'show_other_review' => array(
			'label' => esc_html__('Show Other Review', $stmDomain),
			'type' => 'checkbox',
			'section' => 'stm_review_post_options',
			'values' => 'show_other_reviews'
		),
		'other_review_title' => array(
			'label' => esc_html__('Other Review Title', $stmDomain),
			'section' => 'stm_review_post_options',
			'validate' => 'stm_motors_review_no_validate'
		),
		'other_review_posts_type' => array(
			'label' => esc_html__('Other Review Type', $stmDomain),
			'type' => 'select',
			'section' => 'stm_review_post_options',
			'values' => $recentTypes
		)
	);

	$fields = apply_filters('stm_projects_fields', $fields);

	foreach($fields as $field => $field_info) {
		/*Register control*/
		$type = (!empty($field_info['type'])) ? $field_info['type'] : 'text';
		$validate = (!empty($field_info['validate'])) ? $field_info['validate'] : 'stm_listings_no_validate';

		if ($type == 'checkboxes' || $type == 'select') {
			$manager->register_control(
				$field,
				array(
					'type' => $type,
					'section' => $field_info['section'],
					'label' => $field_info['label'],
					'choices' => $field_info['values']
				)
			);

			$manager->register_setting(
				$field,
				array(
					'sanitize_callback' => 'stm_motors_review_no_validate',
				)
			);
		} else {
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
}