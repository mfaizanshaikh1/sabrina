<?php
$id = get_the_ID();
?>
<div class="author-info-wrap">
	<div class="author-ava-wrap">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 256 ); ?>
	</div>
	<div class="author-info">
		<div class="author-label heading-font"><?php echo esc_html__('Author', 'stm_motors_review'); ?></div>
		<div class="author-name heading-font"><?php echo esc_html(get_the_author()); ?></div>
		<div class="author-desc normal-font"><?php echo esc_html(get_the_author_meta( 'user_description' )); ?></div>
	</div>
</div>