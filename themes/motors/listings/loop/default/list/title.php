<div class="title heading-font">
	<a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
		<?php
        if(stm_is_aircrafts() || stm_is_listing_four()) {
            echo stm_generate_title_from_slugs(get_the_id(), get_theme_mod('show_generated_title_as_label', false));
        } else {
            the_title();
        }
        ?>
	</a>
</div>