<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$breadcrumbs = get_post_meta( get_the_ID(), 'breadcrumbs', true );

get_header();
?>
<?php
if(get_post_format(get_the_ID()) != 'video') {
	stm_motors_review_load_template('header/title_box');
} else {
	if($breadcrumbs) stm_motors_review_load_template('header/breadcrumbs');
}
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="stm-single-post">
			<div class="container">
				<?php if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						stm_motors_review_load_template('content/single/main');
					endwhile;
				endif; ?>
			</div>
		</div>
	</div>
<?php get_footer();?>