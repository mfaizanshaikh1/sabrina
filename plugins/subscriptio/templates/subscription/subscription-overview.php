<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Subscription overview
 *
 * This template can be overridden by copying it to yourtheme/subscriptio/subscription/subscription-overview.php
 *
 * Formatting and styles based on WooCommerce 3.7 order details templates for uniform appearance
 *
 * @package Subscriptio
 * @version 3.0
 * @var string $overview_text
 */

?>

<?php do_action('subscriptio_account_before_subscription_overview', $subscription); ?>

<p class="subscriptio-account-subscription-overview">
    <?php echo $overview_text; ?>
</p>

<?php do_action('subscriptio_account_after_subscription_overview', $subscription); ?>
