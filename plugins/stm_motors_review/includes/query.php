<?php
add_filter('pre_get_posts', 'stm_review_query_vars', 50, 1);

function stm_review_query_vars ($query) {
    $is_reviews = isset($query->query_vars['post_type']) && 'stm_review' == $query->query_vars['post_type'];

    if ( !is_admin() && $query->is_main_query() && $is_reviews ) {

        $meta_query = array('relation' => 'AND');

        if(isset($_GET)) {
            foreach ($_GET as $k => $val) {
                if($val != '' && $k != 'listing_type' && $k != 'unapproved' && $k != 'moderation-hash') {
                    $meta_query = array_merge($meta_query, array(array('key' => $k, 'value' => $val, 'compare' => '=')));
                }
            }
        }

        $reviewPerPage = get_theme_mod('review_per_page', 4);
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $query->set('posts_per_page', $reviewPerPage);
        $query->set('paged', $paged);
        $query->set('post_status', array('publish', 'future'));
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', $meta_query);
    }


    return $query;
}

function getAllReview() {
	$args = array(
		'post_type' => 'stm_review',
		'post_per_page' => -1,
		'post_status'	=> 'publish'
	);

	return new WP_Query( $args );
}

function getReviews($args) {
	$query_params = array(
		'post_type' => 'stm_review',
		'post_per_page' => -1,
		'post_status'	=> 'publish'
	);

	if($args != null) array_merge($query_params, $args);

	return new WP_Query( $query_params );
}