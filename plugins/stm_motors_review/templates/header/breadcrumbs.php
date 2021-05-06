<?php
if ( function_exists( 'bcn_display' ) ) { ?>
	<div class="stm_breadcrumbs_unit heading-font <?php echo esc_attr($blog_margin); ?>">
		<div class="container">
			<div class="navxtBreads">
				<?php bcn_display(); ?>
			</div>
		</div>
	</div>
<?php }
?>