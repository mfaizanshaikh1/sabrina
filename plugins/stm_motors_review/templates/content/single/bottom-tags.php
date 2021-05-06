<?php

$terms = get_terms( array(
	'taxonomy' => 'review_tag',
	'hide_empty' => false,
) );



?>
<div class="review-tags-wrap">
	<ul>
		<li class="heading-font"><?php echo esc_html__('Tags', 'stm_motors_review'); ?></li>
		<?php foreach ($terms as $tag) : ?>
			<li class="normal-font">
				<a href="<?php echo esc_attr(get_tag_link($tag->term_id)); ?>">
					<?php echo esc_html($tag->name);?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
