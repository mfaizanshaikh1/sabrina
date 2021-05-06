<?php
function register_review_metabox($manager, $stmDomain)
{

	$cars = get_posts(array(
		'post_type' => 'listings',
		'post_status' => 'publish',
		'order' => 'ASC',
		'orderby' => 'title',
        'posts_per_page' => -1
	));

	$carsArr = array();

	$carsArr[0] = 'None';
	foreach ($cars as $car) {
		$carsArr[$car->ID] = $car->post_title;
	}

    /*Register sections*/
    $manager->register_section(
        'stm_review_details',
        array(
            'label' => esc_html__('Review details', $stmDomain),
            'icon' => 'fa fa-bookmark'
        )
    );

    /*Register controls*/
    $fields = array(
        'review_title_prefix' => array(
            'label' => esc_html__('Title prefix', $stmDomain),
            'section' => 'stm_title_details',
            'validate' => 'stm_motors_review_no_validate'
        ),
        'sticky_review' => array(
            'label' => esc_html__('Stick this post to the front page', $stmDomain),
            'type' => 'checkbox',
            'section' => 'stm_review_details',
            'values' => 'how_sticky'
        ),
		'review_car' => array(
			'label' => esc_html__('Select car', $stmDomain),
			'type' => 'select',
			'section' => 'stm_review_details',
			'values' => $carsArr
		),
		'review_car_info' => array(
			'label' => esc_html__('Show car info', $stmDomain),
			'type' => 'checkboxes',
			'section' => 'stm_review_details',
			'values' => getCarInfoTitle()
		),
		'show_title_start_at' => array(
			'label' => esc_html__('Show title Starting at (Showing only list reviews page)', $stmDomain),
			'type' => 'checkbox',
			'section' => 'stm_review_details',
			'values' => 'show_start_at'
		),
		'performance' => array(
			'label' => esc_html__('Performance rating', $stmDomain),
			'section' => 'stm_review_details',
			'validate' => 'stm_motors_review_no_validate'
		),
		'comfort' => array(
			'label' => esc_html__('Comfort rating', $stmDomain),
			'section' => 'stm_review_details',
			'validate' => 'stm_motors_review_no_validate'
		),
        'interior' => array(
            'label' => esc_html__('Interior rating', $stmDomain),
			'section' => 'stm_review_details',
			'validate' => 'stm_motors_review_no_validate'
        ),
        'exterior' => array(
            'label' => esc_html__('Exterior rating', $stmDomain),
			'section' => 'stm_review_details',
			'validate' => 'stm_motors_review_no_validate'
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
					'section' => 'stm_review_details',
					'label' => $field_info['label'],
					'choices' => $field_info['values']
				)
			);

			$sanitizeCallback = ($field == 'review_car') ? 'stm_motors_review_car_validate' : 'stm_motors_review_no_validate';

			$manager->register_setting(
				$field,
				array(
					'sanitize_callback' => $sanitizeCallback,
				)
			);
		} else {
			$manager->register_control(
				$field,
				array(
					'type' => $type,
					'section' => 'stm_review_details',
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