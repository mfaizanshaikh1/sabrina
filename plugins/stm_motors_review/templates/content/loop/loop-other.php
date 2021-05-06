<?php
$id = get_the_ID();
$commentCount = wp_count_comments($id)->approved;
$thumb = '';
$selectedCar = get_post_meta($id, 'review_car', true);
$thumb = get_the_post_thumbnail_url($selectedCar, 'm-r-350-200');

if(get_the_post_thumbnail_url($id, 'full') != null) {
	$thumb = get_the_post_thumbnail_url($id, 'm-r-350-200');
}

?>
<div class="other-review-item">
	<div class="img-block">
		<img src="<?php echo esc_url($thumb); ?>" />
		<?php if(get_post_format($id)): ?>
			<span><?php echo sprintf(esc_html__('%s Review', 'stm_motors_review'), get_post_format($id)); ?></span>
		<?php endif; ?>
	</div>
	<div class="review-data">
		<h6><a href="<?php echo get_the_permalink($id); ?>"><?php the_title(); ?></a></h6>
		<div class="comment-num heading-font">
			<i class="rev-icon-ico_mag_reviews"></i>
			<?php echo esc_html($commentCount); ?>
		</div>
	</div>
</div>