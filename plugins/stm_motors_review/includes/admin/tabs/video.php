<?php
function register_review_video_metabox($manager, $stmDomain)
{
	/*Register sections*/
	$manager->register_section(
		'stm_video_review',
		array(
			'label' => esc_html__('Video', $stmDomain),
			'icon' => 'fa fa-video-camera'
		)
	);

	/*Register controls*/
	$fields = array(
		'video_preview' =>
        array(
			'type' => 'image',
			'section' => 'stm_video_review',
			'label' => 'Video Preview',
			'description' => esc_html__('Image for video preview. Please note that video will start playing in a pop-up window.', $stmDomain),
			'size' => 'm-r-1015-575',
			'validate' => 'stm_motors_review_validate_image'
		),
		'gallery_video' =>
		array(
			'type' => 'text',
			'section' => 'stm_video_review',
			'label' => 'Gallery Video (Embed video URL)'
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