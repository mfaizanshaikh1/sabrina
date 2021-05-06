<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);


?>

<div class="stm_pros_cons_wrapp <?php echo esc_attr($stm_pros_cons_type); ?> heading-font">
	<?php echo wpb_js_remove_wpautop($content); ?>
</div>
