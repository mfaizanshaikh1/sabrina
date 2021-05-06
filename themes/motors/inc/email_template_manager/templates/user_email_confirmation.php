<?php
$val = (get_option('user_email_confirmation_template', '') != '') ? stripslashes(get_option('user_email_confirmation_template', '')) :
	'<table>
        <tr>
            <td>
            Howdy [username],

			Your new account is set up.
			
			Please confirm your account:
			
			<a href="[confirmation_link]">Confirmation</a>
			
			Thanks!
			</td>
        </tr>
    </table>';

$subject = (get_option('user_email_confirmation_subject', '') != '') ? get_option('user_email_confirmation_subject', '') : 'Confirmation Email';
?>
<div class="etm-single-form">
	<h3>New User Email Confirmation Template</h3>
	<input type="text" name="user_email_confirmation_subject" value="<?php echo esc_html($subject);?>" class="full_width" />
	<div class="lr-wrap">
		<div class="left">
			<?php
			$sc_arg = array(
				'textarea_rows' => apply_filters( 'etm-aac-sce-row', 10 ),
				'wpautop' => true,
				'media_buttons' => apply_filters( 'etm-aac-sce-media_buttons', false ),
				'tinymce' => apply_filters( 'etm-aac-sce-tinymce', true ),
			);

			wp_editor( $val, 'user_email_confirmation_template', $sc_arg );
			?>
		</div>
		<div class="right">
			<h4>Shortcodes</h4>
			<ul>
				<?php
				foreach (getTemplateShortcodes('userConfirmationEmail') as $k => $val) {
					echo "<li id='{$k}'><input type='text' value='{$val}' class='auto_select' /></li>";
				}
				?>
			</ul>
		</div>
	</div>
</div>
