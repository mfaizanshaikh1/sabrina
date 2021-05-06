<?php
if(is_user_logged_in()):
?>
	<?php
		$user = wp_get_current_user();

		if(!is_wp_error($user) && !empty($user->data->ID)):

        $link = (stm_is_listing_five()) ? stm_c_f_get_page_url('account_page') : stm_get_author_link($user->data->ID);
        $link = (stm_is_listing_six()) ? stm_c_six_get_page_url('account_page') : $link;

		$my_offers = 0;
        $my_fav = 0;

        $user_cars = (function_exists('stm_user_listings_query')) ? stm_user_listings_query($user->data->ID, "publish", -1, false, 0,false, true) : null;
        if($user_cars != null && !empty($user_cars->post_count)) {
            $my_offers = $user_cars->post_count;
        }

        $my_fav = get_the_author_meta('stm_user_favourites', $user->ID);

        if(!empty($my_fav)) {
            $my_fav = count(array_filter(explode(',', $my_fav)));
        } else {
            $my_fav = 0;
        }

        if(stm_is_listing_five() || stm_is_listing_six()) {
			$userObj = new uListing\Classes\StmUser( $user );
		}

        $wishlist_page = (stm_is_listing_five() || stm_is_listing_six()) ? \uListing\Classes\StmListingSettings::getPages("wishlist_page") : null;
	?>

	<div class="lOffer-account-dropdown login">
		<a href="<?php echo esc_url($link . 'edit-profile/'); ?>" class="settings">
			<i class="<?php echo (!stm_is_listing_five() && !stm_is_listing_six()) ? 'stm-settings-icon stm-service-icon-cog' : 'stm-all-icon-cog'; ?>"></i>
		</a>
		<div class="name">
			<a href="<?php echo esc_url($link); ?>"><?php stm_display_user_name($user->ID); ?></a>
		</div>
		<ul class="account-list">
            <?php if(stm_is_listing_five() || stm_is_listing_six()) : ?>
                <li><a href="<?php echo esc_url(\uListing\Classes\StmUser::getUrl('my-listing')); ?>"><?php esc_html_e('My items', 'motors'); ?> (<span><?php echo esc_attr($userObj->getListings(true)); ?></span>)</a></li>
                <?php if(class_exists('UlistingWishlist')): ?>
                <li class="stm-my-favourites">
                    <a href="<?php echo esc_url(get_the_permalink($wishlist_page)); ?>"><?php esc_html_e('Wishlist', 'motors'); ?> (<span><?php echo \uListing\UlistingWishlist\Classes\UlistingWishlist::get_total_count()?></span>)</a>
                </li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="<?php echo esc_url(stm_get_author_link('')); ?>"><?php esc_html_e('My items', 'motors'); ?> (<span><?php echo esc_attr($my_offers); ?></span>)</a></li>
			    <?php if(stm_show_my_plans()): ?>
                <li><a href="<?php echo esc_url(add_query_arg(array('page' => 'my-plans'), stm_get_author_link(''))); ?>"><?php esc_html_e('My plans', 'motors'); ?></a></li>
                <?php endif; ?>
                <li class="stm-my-favourites"><a href="<?php echo esc_url(add_query_arg(array('page' => 'favourite'), stm_get_author_link(''))); ?>"><?php esc_html_e('Favorites', 'motors'); ?> (<span><?php echo esc_attr($my_fav); ?></span>)</a></li>
            <?php endif; ?>
		</ul>
		<a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="logout">
			<i class="fa fa-power-off"></i><?php esc_html_e('Logout', 'motors'); ?>
		</a>
	</div>

	<?php endif; ?>

<?php else : ?>
<?php if(stm_is_listing_five()) wp_enqueue_script('stm-c-f-login', STM_MOTORS_C_F_URL . '/assets/js/c-f-login.js', array('vue'), '1.0', true); ?>
<?php if(stm_is_listing_six()) wp_enqueue_script('stm-c-six-login', STM_MOTORS_C_SIX_URL . '/assets/js/c-six-login.js', array('vue'), '1.0', true); ?>
	<div class="lOffer-account-dropdown stm-login-form-unregistered">
        <?php if(stm_is_listing_five() || stm_is_listing_six()) : ?>
            <div id="stm-c-f-listing-login">
                <div class="form-group" data-v-bind_class="{error: errors['login']}">
                    <h4> <?php echo esc_html__('Login Or E-mail', "motors"); ?></h4>
                    <input type="text"
                           data-v-on_keyup.enter="logIn"
                           data-v-model="login"
                           class="form-control"
                           placeholder="<?php esc_html_e('Enter login', "motors"); ?>"/>
                    <span data-v-if="errors['login']" style="color: red">{{errors['login']}}</span>
                </div>

                <div class="form-group" data-v-bind_class="{error: errors['password']}">
                    <h4> <?php echo esc_html__('Password', "motors"); ?></h4>
                    <input type="password"
                           data-v-on_keyup.enter="logIn"
                           data-v-model="password"
                           class="form-control"
                           placeholder="<?php esc_html_e('Enter password', "motors"); ?>"/>
                    <span data-v-if="errors['password']" style="color: red">{{errors['password']}}</span>
                </div>

                <div class="form-group">
                    <div class="stm-row">
                        <div class="stm-col">
                            <label>
                                <input type="checkbox" value="1" data-v-bind_true-value="1" data-v-bind_false-value="0"
                                       data-v-model="remember"> <?php esc_html_e('Remember me', "motors") ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button data-v-on_click="logInCF" type="button"
                            class="btn btn-primary w-full"><?php echo esc_html__('Login', "motors"); ?></button>
                </div>
                <div data-v-if="loading">Loading...</div>
                <div data-v-if="message" data-v-bind_class="status">{{message}}</div>
            </div>
        <?php else : ?>
            <form method="post">
				<?php do_action( 'stm_before_signin_form' ) ?>
                <div class="form-group">
                    <h4><?php esc_html_e('Login or E-mail', 'motors'); ?></h4>
                    <input type="text" name="stm_user_login" autocomplete="off" placeholder="<?php esc_attr_e('Enter login or E-mail', 'motors') ?>"/>
                </div>

                <div class="form-group">
                    <h4><?php esc_html_e('Password', 'motors'); ?></h4>
                    <input type="password" name="stm_user_password" autocomplete="off" placeholder="<?php esc_attr_e('Enter password', 'motors') ?>"/>
                </div>

                <div class="form-group form-checker">
                    <label>
                        <input type="checkbox" name="stm_remember_me" />
                        <span><?php esc_html_e('Remember me', 'motors'); ?></span>
                    </label>
                </div>
				<?php if(class_exists('SitePress')) : ?><input type="hidden" name="current_lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/><?php endif; ?>
                <input type="submit" value="<?php esc_attr_e('Login', 'motors'); ?>"/>
                <span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
                <a href="<?php echo esc_url(stm_get_author_link('register')); ?>" class="stm_label"><?php esc_html_e('Sign Up', 'motors'); ?></a>
                <div class="stm-validation-message"></div>
				<?php do_action( 'stm_after_signin_form' ) ?>
            </form>
        <?php endif;?>
	</div>
<?php endif; ?>