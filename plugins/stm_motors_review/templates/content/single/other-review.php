<?php
$id = get_the_ID();

$showOther = get_post_meta($id, 'show_other_review', true);
$otherTitle = get_post_meta($id, 'other_review_title', true);
$postFormat = get_post_meta($id, 'other_review_posts_type', true);

$taxArr = '';

if($postFormat == 'video') {
	$taxArr = array(
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => array( 'post-format-' . $postFormat ),
	);
} elseif ($postFormat == 'standard') {
	$taxArr = array(
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => array( 'post-format-video' ),
		'operator' => 'NOT IN'
	);
}

$otherReview = new WP_Query(array(
	'post_type' => 'stm_review',
	'tax_query' => array(
		$taxArr
	),
	'post_status' => 'publish',
	'posts_per_page' => 3,
	'orderby' => 'rand',
	'order' => 'DESC',
	'post__not_in' => array($id)
));

if(!empty($showOther)){
?>

<div class="other-review-block">
	<h3><?php echo esc_html($otherTitle); ?></h3>
	<div class="other-review-list">
		<?php
		if ( $otherReview->have_posts() ) :
			while ( $otherReview->have_posts() ) :
				$otherReview->the_post();
				stm_motors_review_load_template('content/loop/loop-other');
			endwhile;
		endif;
		wp_reset_postdata();
		?>
	</div>
</div>
<?php } ?>