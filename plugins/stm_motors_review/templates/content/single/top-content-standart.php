<?php

$motorsReviewSCE = new SubContentReviewEditor();
$id = get_the_ID();
$commentCount = wp_count_comments($id)->approved;
?>
<div class="review-top-content">
	<div class="r-t-c-left">
		<div class="r-t-c-author heading-font">
			<div class="author-ava-wrap">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?>
			</div>
			<?php echo esc_html(get_the_author_meta( 'display_name' )); ?>
		</div>
		<div class="r-t-c-comment-num heading-font">
			<i class="rev-icon-ico_mag_reviews"></i>
			<?php echo esc_html($commentCount); ?>
		</div>
		<div class="r-t-c-share">
            <?php if(class_exists( 'SC_Class' )): ?>
                <?php echo do_shortcode('[aps-counter theme="theme-2"]'); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="r-t-c-right">
		<?php echo apply_filters('stm_mr_subcontent_filter', $motorsReviewSCE->get_the_review_subcontent());?>
	</div>
</div>