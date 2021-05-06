<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Subscription Checkup
 *
 * This class is dedicated to periodically check subscriptions against predefined list of potential issues,
 * recover automatically whenever recovery is possible and warn admin in case of critical and/or frequent issues
 *
 * @class RP_SUB_Subscription_Checkup
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Subscription_Checkup
{

    // Singleton control
    protected static $instance = false; public static function get_instance() { return self::$instance ? self::$instance : (self::$instance = new self()); }

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {

        // TODO: Schedule this to run every hour or so

        // TODO: Scans a number of subscriptions (e.g. 50 or 100 at a time) and creates separate scheduler entries to handle fixes/notifications when it detects a problem (does not handle fixing by itself to avoid fixing something and then running into timeout issues)

        // TODO: Remembers the last checked subscription and starts with the next one or goes back to the start

        // TODO: Does not re-check the same subscription more than once in X hours (how many?)

        // TODO: Recheck every now and then if events that are expected to be scheduled are still scheduled https://github.com/RightPress/subscriptio/issues/234

        // TODO: Protection from failed renewal order creation https://github.com/RightPress/subscriptio/issues/348

        // TODO: Should we check ourselves on admin page loads if cron is running in general (and display warning)? Or does Action Scheduler do something like that?

        // TODO: We also need some a safeguarding method that checks all non-cancelled/expired subscriptions to make sure they have the required actions scheduled and if not re-schedule them. This would protect shops when someone accidentally deletes scheduled event from the database.

        // TODO: We should implement is_scheduled checks and run them periodically to re-schedule events from subscription data that were accidentally deleted from the scheduled tasks list

        // TODO: Make sure there are no scheduled_ properties set on subscription that do not have actual scheduler entries

        // TODO: Make sure there are no scheduler entries that do not have scheduled_ properties set on subscription
    }





}

RP_SUB_Subscription_Checkup::get_instance();
