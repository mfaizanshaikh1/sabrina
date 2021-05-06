<?php
if(get_post_format(get_the_ID()) == 'video') {
	$tpl = 'content/single/top-content-video';
} else {
	$tpl = 'content/single/top-content-standart';
}

stm_motors_review_load_template($tpl);
the_content();
stm_motors_review_load_template('content/single/bottom-tags');
stm_motors_review_load_template('content/single/author-info');
stm_motors_review_load_template('content/single/other-review');
stm_motors_review_load_template('content/single/comment-form');