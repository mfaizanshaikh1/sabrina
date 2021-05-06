<?php

$eventsTopBg = get_theme_mod('events_block_title_bg', '');
$eventsTopSubTitle = get_theme_mod('events_subtitle');


?>
<div class="stm-motors-event-archive-header entry-header" style="background-image: url('<?php echo esc_url($eventsTopBg)?>'); ">
	<div class="container">
		<h1><?php echo esc_html__('Events', 'stm_motors_events')?></h1>
		<h5><?php echo esc_html($eventsTopSubTitle); ?></h5>
	</div>
</div>

