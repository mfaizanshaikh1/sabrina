<?php
$data = apply_filters( 'stm_single_car_data', stm_get_single_car_listings() );
$post_id = $__vars['listing_id'];

$show_vin = get_theme_mod('show_vin', true);
$vin_num = get_post_meta($post_id,'vin_number', true);

$show_stock = get_theme_mod('show_stock', true);
$stock_number = get_post_meta($post_id,'stock_number',true);

$show_registered = get_theme_mod('show_registered', true);
$registration_date = get_post_meta($post_id,'registration_date',true);

$show_history = get_theme_mod('show_history', true);
$history = get_post_meta($post_id,'history',true);
$history_link = '';
$history_link = get_post_meta($post_id,'history_link',true);


//Registration
if ( ! empty( $registration_date ) and $show_registered ) {
	$data[] = array(
		'single_name' => esc_html__( 'Registered', 'motors' ),
		'value'       => $registration_date,
		'font'        => 'stm-icon-key',
		'standart'    => false,
	);
}

if(empty($registration_date) and $show_registered) {
	$data[] = array(
		'single_name' => esc_html__( 'Registered', 'motors' ),
		'value'       => esc_html__('N/A', 'motors'),
		'font'        => 'stm-icon-key',
		'standart'    => false,
	);
}

//History
if ( ! empty( $history ) and $show_history ) {
	$data[] = array(
		'single_name' => esc_html__( 'History', 'motors' ),
		'value'       => $history,
		'link'        => $history_link,
		'font'        => 'stm-icon-time',
		'standart'    => false,
	);
}

if ( empty( $history ) and $show_history ) {
	$data[] = array(
		'single_name' => esc_html__( 'History', 'motors' ),
		'value'       => esc_html__('N/A', 'motors'),
		'font'        => 'stm-icon-time',
		'standart'    => false,
	);
}

//Stock
if ( ! empty( $stock_number ) and $show_stock ) {
	$data[] = array(
		'single_name' => esc_html__( 'Stock id', 'motors' ),
		'value'       => $stock_number,
		'font'        => 'stm-service-icon-hashtag',
		'standart'    => false,
	);
}

if ( empty( $stock_number ) and $show_stock ) {
	$data[] = array(
		'single_name' => esc_html__( 'Stock id', 'motors' ),
		'value'       => esc_html__('N/A', 'motors'),
		'font'        => 'stm-service-icon-hashtag',
		'standart'    => false,
	);
}



//VIN
if ( ! empty( $vin_num ) and $show_vin ) {
	$data[] = array(
		'single_name' => esc_html__( 'VIN:', 'motors' ),
		'value'       => $vin_num,
		'font'        => 'stm-service-icon-vin_check',
		'standart'    => false,
		'vin'         => true
	);
}

if ( empty( $vin_num ) and $show_vin ) {
	$data[] = array(
		'single_name' => esc_html__( 'VIN:', 'motors' ),
		'value'       => $vin_num,
		'font'        => 'stm-service-icon-vin_check',
		'standart'    => false,
		'vin'         => true
	);
}
?>

<?php if(!empty($data)): ?>
	<div class="stm-single-car-listing-data">
		<div class="listing-img">
			<?php echo wp_get_attachment_image(get_post_thumbnail_id($post_id), 'full');?>
		</div>
		<div class="listing-data-wrap">
			<h2><?php echo get_the_title($post_id); ?></h2>
			<table class="stm-table-main">
				<tr>
					<?php foreach($data as $data_key => $data_single): ?>

					<?php if($data_key%3 == 0 and $data_key != 0): ?>
				</tr>
				<tr/>
				<?php endif; ?>

				<td>
					<table class="inner-table">
						<?php
						if(!empty($data_single['slug'])) {
							$value = get_post_meta( $post_id, $data_single['slug'], true );
							if($value !== '') {
								if ( ! empty( $data_single['numeric'] ) and $data_single['numeric'] ) {
									if ( ! empty( $data_single['number_field_affix'] ) ) {
										$value .= ' ' . $data_single['number_field_affix'];
									}
								} else {
									$term_slugs = explode( ',', $value );
									$values     = array();

									foreach ( $term_slugs as $term_slug ) {
										$term     = get_term_by( 'slug', $term_slug, $data_single['slug'] );
										if(!empty($term->name)) {
											$values[] = $term->name;
										}
									}

									$value = implode( ', ', $values );
								}
							} else {
								$value = esc_html__('N/A', 'motors');
							}
						} else {
							$value = $data_single['value'];
						}
						?>
						<tr>
							<?php if(!empty($data_single['vin'])): ?>
								<td class="label-td">
									<?php if(!empty($data_single['font'])): ?>
										<i class="<?php echo esc_attr($data_single['font']) ?>"></i>
									<?php endif; ?>
									<?php echo esc_html($data_single['single_name']); ?> <?php echo esc_html($value); ?>
								</td>
								</td>
							<?php else: ?>
								<td class="label-td">
									<?php if(!empty($data_single['font'])): ?>
										<i class="<?php echo esc_attr($data_single['font']) ?>"></i>
									<?php endif; ?>
									<?php echo stm_dynamic_string_translation('Listing Category ' . $data_single['single_name'], $data_single['single_name']); ?>
								</td>
								<td class="heading-font">
									<?php if(!empty($data_single['link'])): ?>
									<a href="<?php echo esc_url($data_single['link']); ?>" target="_blank">
										<?php endif; ?>

										<?php echo stm_dynamic_string_translation('Listing Term ' . $value, $value); ?>

										<?php if(!empty($data_single['link'])): ?>
									</a>
								<?php endif; ?>
								</td>
							<?php endif; ?>
						</tr>
					</table>
				</td>
				<td class="divider-td"></td>

				<?php endforeach; ?>
				</tr>
			</table>
		</div>
	</div>
<?php endif; ?>