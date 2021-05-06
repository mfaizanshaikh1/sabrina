<?php
$userId = get_current_user_id();
$plans = (class_exists('Subscriptio_User')) ? Subscriptio_User::find_subscriptions( true, $userId ) : subscriptio_get_customer_subscriptions($userId);
$subscriptionOption = (class_exists('RP_SUB')) ? get_option('rp_sub_settings', '') : '';

$pauseAllow = false;
$cancelAllow = false;
$renewalDay = 1;

if($subscriptionOption) {
    $renewalDay = (!empty($subscriptionOption[1]['renewal_order_offset'])) ? $subscriptionOption[1]['renewal_order_offset'] : $renewalDay;
	$pauseAllow = $subscriptionOption[1]['customer_pausing'] != 'not_allowed';
	$cancelAllow = $subscriptionOption[1]['customer_cancelling'] != 'not_allowed';
}

?>
<div class="stm-plans-grid">
<?php

foreach ( $plans as $plan ) :
	/*
	 * TODO
	 * 'Subscriptio_User' will be removed
	 * */

	if(!$plan) continue;

	$status = (class_exists('Subscriptio_User')) ? $plan->status : $plan->get_status();

	if(class_exists('Subscriptio_User')) {
		$subs_id = $plan->id;
		$plan_name = ( !empty( $plan->products_multiple ) ) ? $plan->products_multiple[0]['product_name'] : $plan->product_name;
		$product_id = $plan->product_id;
		$expires = $plan->payment_due_readable;
		$usedQuota = MultiplePlan::getUsedQuota($subs_id);

		if ( empty( $product_id ) and !empty( $plan->products_multiple ) and is_array( $plan->products_multiple ) ) {
			$products = $plan->products_multiple;
			if ( !empty( $products[0] ) and !empty( $products[0]['product_id'] ) ) {
				$product_id = $products[0]['product_id'];
			}
		}

	} else {
		$initialOrder = $plan->get_initial_order()->get_data();
		$key = key($initialOrder['line_items']);
		$orderData = $initialOrder['line_items'][$key]->get_data();

		$subs_id = $plan->get_id();
		$plan_name = $orderData['name'];
		$product_id = $orderData['product_id'];
		$renewalUrl = $plan->get_initial_order()->get_checkout_payment_url();

        $expires = (!empty($plan->get_scheduled_renewal_payment()) && in_array($status, array('active', 'trial'))) ? $plan->get_scheduled_renewal_payment()->format("m/d/Y H:i") : esc_html__('Expired', 'motors');

        $renew = false;

		$date_expires = strtotime($expires);
		$date_now = time();
		$date_diff = ($date_expires - $date_now) / (60*60*24);

		if($renewalDay != 0 && $date_diff <= 0) {
			$renew = true;
		}

		$usedQuota = MultiplePlan::getUsedQuota($plan->get_id());
	}

	$post_limit = intval( get_post_meta( $product_id, 'stm_price_plan_quota', true ) );

	$planUniqId = 'stm-start-countdown-plan-' . rand(1000, 100000);

?>
	<div class='stm-plan-grid-item-wrap'>
		<div class='stm-pricing-table heading-font'>
			<div class='stm-pricing-table__title'><?php echo esc_html($plan_name); ?></div>
			<ul class='stm-pricing-table__features'>
				<li class='stm-pricing-table__feature'>
					<div class='stm-pricing-table__feature-label'><?php echo esc_html__('Status', 'motors'); ?></div>
					<div class='stm-pricing-table__feature-value'>
						<?php echo esc_html(strtoupper($status)); ?>
					</div>
				</li>
				<li class='stm-pricing-table__feature'>
					<div class='stm-pricing-table__feature-label'><?php echo esc_html__('Used slots', 'motors'); ?></div>
					<div class='stm-pricing-table__feature-value'>
						<?php echo esc_html($usedQuota); ?> / <?php echo esc_html($post_limit); ?>
					</div>
				</li>
				<li class='stm-pricing-table__feature'>
					<div class='stm-pricing-table__feature-label'><?php echo esc_html__('Expire Through', 'motors'); ?></div>
					<div id='<?php echo esc_attr($planUniqId); ?>' class='stm-pricing-table__feature-value'>
						<?php echo stm_do_lmth($expires); ?>
					</div>
				</li>
				<li class='stm-pricing-table__feature btn-wrap'>
					<div class='stm-pricing-table__feature-value'>
                        <?php if($pauseAllow): ?>
                            <?php if(!$renew && $status != 'paused' && in_array($plan->get_previous_status(), array('trial', 'paused', 'pending'))): ?>
					            <button class="stm-btn-plan-pause" data-msgblock="<?php echo esc_attr($planUniqId . '-msg');?>" data-userid="<?php echo esc_attr($userId); ?>" data-subsid="<?php echo esc_attr($subs_id); ?>" data-status="wc-paused"><?php echo esc_html__('Pause', 'motors') ?></button>
                            <?php elseif($status == 'paused'): ?>
                                <button class="stm-btn-plan-trial" data-msgblock="<?php echo esc_attr($planUniqId . '-msg');?>"  data-userid="<?php echo esc_attr($userId); ?>" data-subsid="<?php echo esc_attr($subs_id); ?>" data-status="wc-trial"><?php echo esc_html__('Start', 'motors') ?></button>
                            <?php endif; ?>
                        <?php endif; ?>
						<?php if($cancelAllow): ?>
					        <button class="stm-btn-plan-cancel" data-msgblock="<?php echo esc_attr($planUniqId . '-msg');?>"  data-userid="<?php echo esc_attr($userId); ?>" data-subsid="<?php echo esc_attr($subs_id); ?>" data-status="wc-cancelled"><?php echo esc_html__('Cancel', 'motors') ?></button>
                        <?php endif; ?>
					</div>
				</li>
			</ul>
            <div class="<?php echo esc_attr($planUniqId . '-msg');?> stm-response-msg"></div>
		</div>
		<?php
        if($renew && in_array($status, array('active', 'trial')) && $expires != 'Expired') : ?>
            <script type='text/javascript'>
                jQuery(document).ready(function(){
                    var $ = jQuery;
                    $('#<?php echo esc_attr($planUniqId) ?>')
                        .countdown('<?php echo stm_do_lmth($expires) ?>', function (event) {
                            $(this).text(
                                <?php if($renewalDay > 1): ?>
                                event.strftime('%d day %H:%M:%S')
                                <?php else: ?>
                                event.strftime('%H:%M:%S')
                                <?php endif;?>
                            );
                        });
                })
            </script>
		<?php endif; ?>
	</div>
<?php endforeach; ?>
    <div class='stm-plan-grid-item-wrap'>
        <div class='stm-pricing-table heading-font'>
            <div class='stm-pricing-table__title'><?php echo esc_html__('Get Plan', 'motors'); ?></div>
            <a href="<?php echo esc_url(stm_pricing_link());?>" class="get-new-link">
                <div class="get-new-btn">
                    <i class="fa fa-plus"></i>
                </div>
            </a>
        </div>
    </div>
</div>
