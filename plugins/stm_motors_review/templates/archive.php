<?php
global $wp_query;
$reviewPagination = get_theme_mod('review_archive_paginatin_style', 'pagination');
$reviewPerPage = get_theme_mod('review_per_page', 4);
$paged = (get_query_var('page')) ? get_query_var('page') : 1;

add_filter('body_class', 'stm_review_body_class_list');
get_header();
stm_motors_review_load_template('header/breadcrumbs');
?>

<div class="container">
    <?php stm_motors_review_load_template('header/title_box_archive_list'); ?>
    <div class="archive-review-list">
        <div class="row">
            <?php
                if(have_posts()) {
                    while (have_posts()) {
                        the_post();
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
        if ($wp_query->found_posts > $reviewPerPage):
            if ($reviewPagination === 'pagination') {
                echo paginate_links( array(
                    'type'      => 'list',
                    'prev_text' => '<i class="fa fa-angle-left"></i>',
                    'next_text' => '<i class="fa fa-angle-right"></i>',
                ) );
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
    ?>
</div>
<?php get_footer(); ?>
