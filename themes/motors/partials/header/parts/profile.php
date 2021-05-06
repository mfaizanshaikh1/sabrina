<?php
$header_profile = get_theme_mod('header_show_profile', false);

if(is_listing() && $header_profile) :
	?>
	<div class="pull-right hdn-767">
		<div class="lOffer-account-unit">
			<a href="<?php echo esc_url( stm_get_author_link( 'register' ) ); ?>"
			   class="lOffer-account">
				<?php
				if ( is_user_logged_in() ): $user_fields = stm_get_user_custom_fields( '' );
					if ( !empty( $user_fields['image'] ) ):
						?>
						<div class="stm-dropdown-user-small-avatar">
							<img src="<?php echo esc_url( $user_fields['image'] ); ?>"
								 class="im-responsive"/>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<i class="stm-service-icon-user"></i>
			</a>
			<?php get_template_part( 'partials/user/user', 'dropdown' ); ?>
			<?php get_template_part( 'partials/user/private/mobile/user' ); ?>
		</div>
	</div>
<?php endif; ?>