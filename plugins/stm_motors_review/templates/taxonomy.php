<?php
$reviewPagination = get_theme_mod('review_archive_paginatin_style', 'pagination');
$reviewPerPage = get_theme_mod('review_per_page', 4);
$post_type = 'stm_review';

$paged = (get_query_var('page')) ? get_query_var('page') : 1;

$tax = get_term_by('id', get_queried_object()->term_id, 'review_category');
if($tax) {
    $taxQuery = array(
        'taxonomy' => $tax->taxonomy,
        'field'    => 'slug',
        'terms'    => $tax->slug,
    );
} else {
    $tax = get_term_by('id', get_queried_object()->term_id, 'review_tag');
    $taxQuery = array(
        'taxonomy' => $tax->taxonomy,
        'field'    => 'slug',
        'terms'    => $tax->slug,
    );
}

$args = array(
    'post_type'      => 'stm_review',
    'posts_per_page' => $reviewPerPage,
    'paged'          => $paged,
    'post_status'    => array('publish', 'future'),
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
    'meta_query'     => array(
        'relation' => 'OR',
    ),
    'tax_query' => array(
        $taxQuery
    ),
);

$q = new WP_Query($args);

add_filter('body_class', 'stm_review_body_class_list');
get_header();
stm_motors_review_load_template('header/breadcrumbs');
?>

<div class="container">
    <?php stm_motors_review_load_template('header/title_box_archive_list'); ?>
    <div class="archive-review-list">
        <div class="row">
            <?php
            if($q->have_posts()) {
                while ($q->have_posts()) {
                    $q->the_post();
                    stm_motors_review_load_template('content/loop/loop-review');
                }
                ?>
                <div class="stm-review-load-block"></div>
                <?php
            } else {
                ?>
                <h3 class="col-md-12"><?php esc_html_e('Sorry, No results', 'stm_motors_review'); ?></h3>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    if ($q->found_posts > $reviewPerPage):
        if ($reviewPagination === 'pagination') {
            echo review_pagination(
                array(
                    'type'    => 'list',
                    'format'  => '?page=%#%',
                    'current' => $paged,
                    'total'   => $q->max_num_pages,
                )
            );
        } else {
            ?>

            <div class="container review-load-more-btn-wrap">
                <a href="#"
                   data-page="1"
                   data-per_page="<?php echo esc_js($reviewPerPage); ?>"
                   data-post_type="stm_review"
                   class="btn review-btn-bg btn_loading stm_load_posts stm_load_review">
                    <span><?php esc_html_e('Load more review', 'stm_motors_review'); ?></span>
                    <span class="preloader"></span>
                </a>
            </div>
            <?php
        }
    endif;
    wp_reset_postdata();
    ?>
</div>
<?php get_footer(); ?>
