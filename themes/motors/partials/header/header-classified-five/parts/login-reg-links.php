<?php
$link = (stm_is_listing_five()) ? stm_c_f_get_page_url('account_page') : stm_get_author_link('register');
$link = (stm_is_listing_six()) ? stm_c_six_get_page_url('account_page') : $link;
?>

<ul class="login-reg-urls">
	<li><i class="stm-all-icon-lnr-user"></i></li>
	<li><a href="<?php echo esc_url($link);?>"><?php _e('Login', 'stm_motors_classified_five');?></a></li>
	<li><a href="<?php echo esc_url($link);?>"><?php _e('Register', 'stm_motors_classified_five');?></a></li>
</ul>