<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$subscrNonce = wp_create_nonce( 'stm_admin_remove_subscription' );

if ( !empty( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'stm_admin_remove_subscription' ) ) {
	if ( isset( $_GET['remove_old_subscriptio'] ) && delete_option( "subscriptio_options" ) ) {
		$subTransac= get_posts( array('post_type'=>'sub_transaction','numberposts'=>-1) );
		$subscr= get_posts( array('post_type'=>'subscription','numberposts'=>-1) );

		if(count($subTransac) > 0) {
			foreach ( $subTransac as $eachpost ) {
				wp_delete_post( $eachpost->ID, true );
			}
		}

		if(count($subscr) > 0) {
			foreach ( $subscr as $eachpost ) {
				wp_delete_post( $eachpost->ID, true );
			}
		}

		wp_safe_redirect( 'edit.php?post_type=rp_sub_subscription&page=rp_sub_settings&tab=general' );
	}
}

?>
<div>
	<div>
		<h2>Update to the new version of Subscriptio </h2>
		<p>
            You can update your Subsriptio plugin by removing your current version.
            Please note all your settings will be reset. Proceed with updating?
            <br>
			<br>
			<a href="<?php echo esc_url(get_admin_url())?>admin.php?page=stm-admin-remove-subscriptio&remove_old_subscriptio=remove&nonce=<?php echo esc_attr($subscrNonce);?>" class="button-secondary">Remove</a>
		</p>
	</div>
</div>
