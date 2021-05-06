<?php
$link = (stm_is_listing_five()) ? stm_c_f_get_page_url('account_page') : stm_get_author_link('register');
$link = (stm_is_listing_six()) ? stm_c_six_get_page_url('account_page') : $link;
?>

<div class="profile-wrap">
    <div class="lOffer-account-unit">
        <a href="<?php echo esc_url($link); ?>" class="lOffer-account">
            <?php
            if(is_user_logged_in()) {
                $user_fields = stm_get_user_custom_fields('');
                $ava = $user_fields['image'];

                if(class_exists('StmUser')) {
					$user = wp_get_current_user();
					$user = new uListing\Classes\StmUser( $user );
					$ava = $user->getAvatarUrl();
				}


                if(!empty($ava)):
                    ?>
                    <div class="stm-dropdown-user-small-avatar">
                        <img src="<?php echo esc_url($ava); ?>" class="im-responsive"/>
                    </div>
                <?php else: ?>
                    <i class="stm-service-icon-user"></i>
                <?php endif; ?>
            <?php } else { ?>
                <i class="stm-service-icon-user"></i>
            <?php } ?>
        </a>
        <?php get_template_part( 'partials/header/header-classified-five/parts/account-dropdown') ; ?>
    </div>
</div>

