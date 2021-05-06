<?php

$categories = get_terms(array(
	'taxonomy' => 'review_category',
	'hide_empty' => true,
));

$get = '';
if(isset($_GET)) {
	unset($_GET['page']);
	$get = '?' . http_build_query($_GET, '', '&amp;');
}

$current_term = get_queried_object();
?>
<div class="stm-motors-review-archive-header review-list-archive">
    <h1><?php echo esc_html__('Review', 'stm_motors_review')?></h1>
    <div class="review-category-filter">
        <ul>
            <li <?php if(!isset($current_term->term_id)) echo 'class="active"'; ?>><a href="<?php echo esc_url(get_post_type_archive_link( 'stm_review' ) . $get); ?>" class="heading-font"><?php echo esc_html__('All Review', 'stm_motors_review'); ?></a></li>
            <?php foreach ($categories as $val) : ?>
                <li <?php if(isset($current_term->term_id) && $current_term->term_id == $val->term_id) echo 'class="active"'; ?>><a href="<?php echo esc_url(get_term_link($val->term_id) . $get); ?>" class="heading-font"><?php echo esc_attr($val->name); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php ?>
</div>