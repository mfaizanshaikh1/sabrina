<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Scheduler
 *
 * @class RP_SUB_Scheduler
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Scheduler extends RightPress_Scheduler
{

    // TODO: Multiple attemps of failed scheduled events https://github.com/RightPress/subscriptio/issues/235

    // TODO: Good protection from the same event happening more than once https://github.com/RightPress/subscriptio/issues/236 (or is Action Scheduled enough?)

    // TODO: Each subscription should have some kind of a lock to avoid having two processes handling the same action at the same time

    // TODO: fix_stuck_processing_transactions

    // TODO: Protection from instant cancellation if cron has not run for some time https://github.com/RightPress/subscriptio/issues/250

    // TODO: Maybe handle action_scheduler_failed_execution
    // TODO: Maybe handle action_scheduler_failed_action
    // TODO: Maybe handle action_scheduler_unexpected_shutdown

    // TODO: Ensure changes to grace and suspension period settings are accounted for when subscriptions are in grace or suspension period

    // Define group
    protected $group = 'subscriptio';

    // Define prefix
    protected $prefix = RP_SUB_PLUGIN_PRIVATE_PREFIX;

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

        // Set up unscheduling hooks
        // Note: This must remain before set_up_scheduling_hooks() as we don't want to unschedule freshly scheduled actions
        $this->set_up_unscheduling_hooks();

        // Set up scheduling hooks
        $this->set_up_scheduling_hooks();

        // Call parent constructor
        parent::__construct();
    }

    /**
     * Set up unscheduling hooks
     *
     * @access public
     * @return void
     */
    public function set_up_unscheduling_hooks()
    {

        // Unschedule auto resumption action when subscription is resumed (this may be done manually)
        add_action('subscriptio_subscription_status_changed_from_paused_to_trial', array('RP_SUB_Scheduler', 'unschedule_subscription_resume'), 1);
        add_action('subscriptio_subscription_status_changed_from_paused_to_active', array('RP_SUB_Scheduler', 'unschedule_subscription_resume'), 1);
        add_action('subscriptio_subscription_status_changed_from_paused_to_overdue', array('RP_SUB_Scheduler', 'unschedule_subscription_resume'), 1);
        add_action('subscriptio_subscription_status_changed_from_paused_to_suspended', array('RP_SUB_Scheduler', 'unschedule_subscription_resume'), 1);

        // Unschedule scheduled cancellation when subscription is reactivated
        add_action('subscriptio_subscription_status_changed_from_set-to-cancel_to_trial', array('RP_SUB_Scheduler', 'unschedule_subscription_cancel'), 1);
        add_action('subscriptio_subscription_status_changed_from_set-to-cancel_to_active', array('RP_SUB_Scheduler', 'unschedule_subscription_cancel'), 1);

        // Unschedule all events when subscription is paused, set to cancel, cancelled or expired
        add_action('subscriptio_subscription_status_changed_to_paused', array('RP_SUB_Scheduler', 'unschedule_all_actions'), 1);
        add_action('subscriptio_subscription_status_changing_to_set-to-cancel', array('RP_SUB_Scheduler', 'unschedule_all_actions'), 1);
        add_action('subscriptio_subscription_status_changing_to_cancelled', array('RP_SUB_Scheduler', 'unschedule_all_actions'), 1);
        add_action('subscriptio_subscription_status_changing_to_expired', array('RP_SUB_Scheduler', 'unschedule_all_actions'), 1);
    }

    /**
     * Set up scheduling hooks
     *
     * @access public
     * @return void
     */
    public function set_up_scheduling_hooks()
    {

        // Renewal order
        add_action('subscriptio_subscription_payment_applied', array($this, 'renewal_order_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_paused_to_trial', array($this, 'renewal_order_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_paused_to_active', array($this, 'renewal_order_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_set-to-cancel_to_trial', array($this, 'renewal_order_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_set-to-cancel_to_active', array($this, 'renewal_order_scheduler'));

        // Renewal payment
        add_action('subscriptio_subscription_payment_applied', array($this, 'renewal_payment_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_paused_to_trial', array($this, 'renewal_payment_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_paused_to_active', array($this, 'renewal_payment_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_set-to-cancel_to_trial', array($this, 'renewal_payment_scheduler'));
        add_action('subscriptio_subscription_status_changed_from_set-to-cancel_to_active', array($this, 'renewal_payment_scheduler'));

        // Payment retry
        // Currently only scheduled directly from other scheduled event handlers

        // Payment reminder
        // Currently only scheduled directly from other scheduled event handlers

        // Subscription resumption
        add_action('subscriptio_subscription_status_changed_to_paused', array($this, 'subscription_resume_scheduler'));

        // Subscription suspension
        add_action('subscriptio_subscription_status_changed_to_overdue', array($this, 'subscription_suspend_scheduler'));

        // Subscription cancellation
        add_action('subscriptio_subscription_status_changed', array($this, 'subscription_cancel_scheduler'), 10, 3);

        // Subscription expiration
        add_action('subscriptio_subscription_initial_payment_applied', array($this, 'subscription_expire_scheduler'));
        add_action('subscriptio_subscription_set_property_lifespan', array($this, 'subscription_expire_scheduler_set_lifespan'), 10, 2);
    }

    /**
     * Register actions
     *
     * @access public
     * @return array
     */
    public function register_actions()
    {

        /**
         * IMPORTANT:
         * If scheduled action has a corresponding log entry event type, then scheduled action and log entry event type names must match
         * See RP_SUB_Log_Entry_Controller::define_taxonomies_with_terms()
         */

        return array(

            'renewal_order' => array(
                'label' => __('Renewal order', 'subscriptio'),
            ),

            'renewal_payment' => array(
                'label' => __('Renewal payment', 'subscriptio'),
            ),

            'payment_retry' => array(
                'label' => __('Payment retry', 'subscriptio'),
            ),

            'payment_reminder' => array(
                'label' => __('Payment reminder', 'subscriptio'),
            ),

            'subscription_resume' => array(
                'label' => __('Resumption', 'subscriptio'),
            ),

            'subscription_suspend' => array(
                'label' => __('Suspension', 'subscriptio'),
            ),

            'subscription_cancel' => array(
                'label' => __('Cancellation', 'subscriptio'),
            ),

            'subscription_expire' => array(
                'label' => __('Expiration', 'subscriptio'),
            ),
        );
    }


    /**
     * =================================================================================================================
     * SCHEDULERS
     * =================================================================================================================
     */

    /**
     * Renewal order scheduler
     *
     * Renewal order is only explicitly defined if it is set to be created before payment due date,
     * otherwise renewal payment handler will create a renewal order just before processing the payment
     *
     * @access public
     * @param object|int $subscription
     * @return int
     */
    public function renewal_order_scheduler($subscription)
    {

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Make sure we don't have one yet
        if (!$subscription->get_pending_renewal_order()) {

            // Check if order should be created before payment due date
            if (!$subscription->has_automatic_payments() && RP_SUB_Settings::get('renewal_order_offset')) {

                // Calculate next renewal order datetime
                $datetime = $subscription->calculate_next_renewal_order_datetime();

                // Ensure renewal order datetime is at least 30 minutes in the future if RightPress testing mode is not enabled
                if (!defined('RIGHTPRESS_TESTING_MODE_ENABLED')) {
                    RP_SUB_Time::ensure_future_datetime($datetime, '+30 minutes');
                }

                // Schedule renewal order
                RP_SUB_Scheduler::schedule_renewal_order($subscription, $datetime);

                // Add note to log entry
                $subscription->add_log_entry_note(__('Next renewal order scheduled.', 'subscriptio'));
            }
        }
    }

    /**
     * Renewal payment scheduler
     *
     * @access public
     * @param object|int $subscription
     * @return int
     */
    public function renewal_payment_scheduler($subscription)
    {

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Calculate next renewal payment datetime
        $datetime = $subscription->calculate_next_renewal_payment_datetime();

        // Ensure renewal payment datetime is at least 2 hours in the future if RightPress testing mode is not enabled
        if (!defined('RIGHTPRESS_TESTING_MODE_ENABLED')) {
            RP_SUB_Time::ensure_future_datetime($datetime, '+2 hours');
        }

        // Schedule renewal payment
        RP_SUB_Scheduler::schedule_renewal_payment($subscription, $datetime);

        // Add note to log entry
        $subscription->add_log_entry_note(__('Next renewal payment scheduled.', 'subscriptio'));
    }

    /**
     * Payment retry scheduler
     *
     * @access public
     * @param object|int $subscription
     * @return int
     */
    public function payment_retry_scheduler($subscription)
    {

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Get next payment retry datetime
        if ($datetime = RP_SUB_Scheduler::get_next_payment_retry_datetime($subscription)) {

            // Schedule payment retry
            RP_SUB_Scheduler::schedule_payment_retry($subscription, $datetime);

            // Add note to log entry
            $subscription->add_log_entry_note(__('Next automatic payment retry scheduled.', 'subscriptio'));
        }
    }

    /**
     * Payment reminder scheduler
     *
     * @access public
     * @param object|int $subscription
     * @return int
     */
    public function payment_reminder_scheduler($subscription)
    {

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Reminders can only be sent for manual subscriptions or automatic subscriptions that are suspended
        if (!$subscription->has_automatic_payments() || $subscription->has_status('suspended')) {

            // Get next payment reminder datetime
            if ($datetime = RP_SUB_Scheduler::get_next_payment_reminder_datetime($subscription)) {

                // Schedule payment reminder
                RP_SUB_Scheduler::schedule_payment_reminder($subscription, $datetime);

                // Add note to log entry
                $subscription->add_log_entry_note(__('Next payment reminder scheduled.', 'subscriptio'));
            }
        }
    }

    /**
     * Subscription resumption scheduler
     *
     * @access public
     * @param object|int $subscription
     * @return int
     */
    public function subscription_resume_scheduler($subscription)
    {

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Check if pause duration is limited, i.e. auto resuming is enabled
        if ($pause_duration = RP_SUB_Settings::get('customer_pausing_duration_limit')) {

            // Get auto resumption datetime
            $datetime = (new RP_SUB_DateTime())->modify("+{$pause_duration} " . RP_SUB_Time::get_day_name());

            // Schedule automatic resumption
            RP_SUB_Scheduler::schedule_subscription_resume($subscription, $datetime);

            // Add note to log entry
            $subscription->add_log_entry_note(__('Automatic subscription resumption scheduled.', 'subscriptio'));
        }
    }

    /**
     * Subscription suspension scheduler
     *
     * @access public
     * @param object|int $subscription
     * @return int
     */
    public function subscription_suspend_scheduler($subscription)
    {

        $datetime = null;

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Subscription is overdue and does not use automatic payments
        if ($subscription->get_status() === 'overdue' && !$subscription->has_automatic_payments()) {

            // Suspensions are enabled
            if (RP_SUB_Settings::is('suspension_period')) {

                // Subscription will be suspended at the end of the overdue period
                $datetime = $subscription->calculate_next_renewal_payment_datetime();
                RP_SUB_Time::add_period_length_to_datetime($datetime, (RP_SUB_Scheduler::calculate_overdue_period_length($subscription) . ' ' . RP_SUB_Time::get_day_name()));
            }
        }

        // Check if suspension needs to be scheduled
        if ($datetime) {

            // Schedule subscription suspension
            RP_SUB_Scheduler::schedule_subscription_suspend($subscription, $datetime);

            // Add note to log entry
            $subscription->add_log_entry_note(__('Subscription suspension scheduled.', 'subscriptio'));
        }
    }

    /**
     * Subscription cancellation scheduler
     *
     * @access public
     * @param RP_SUB_Subscription|int $subscription
     * @param string $old_status
     * @param string $new_status
     * @return int
     */
    public function subscription_cancel_scheduler($subscription, $old_status, $new_status)
    {

        $datetime = null;

        // Check new status (we are listening to a generic 'status changing' hook so we need to check new status here)
        if (!in_array($new_status, array('overdue', 'suspended', 'set-to-cancel'), true)) {
            return;
        }

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Calculate next renewal payment datetime
        $next_renewal_payment_datetime = $subscription->calculate_next_renewal_payment_datetime();

        // Subscription is overdue and subscription does not use automatic payments
        if ($new_status === 'overdue' && !$subscription->has_automatic_payments()) {

            // Suspensions must not be enabled in order to schedule cancellation
            if (!RP_SUB_Settings::is('suspension_period')) {

                // Subscription will be cancelled at the end of the overdue period
                $datetime = $next_renewal_payment_datetime;
                RP_SUB_Time::add_period_length_to_datetime($datetime, (RP_SUB_Scheduler::calculate_overdue_period_length($subscription) . ' ' . RP_SUB_Time::get_day_name()));
            }
        }
        // Subscription is suspended
        else if ($new_status === 'suspended') {

            // Get a number of days subscription will be suspended
            $days = RP_SUB_Settings::get('suspension_period');

            // Get a number of days subscription was overdue
            if ($old_status === 'overdue') {
                $days += (int) round(((new RightPress_DateTime())->getTimestamp() - $subscription->get_status_since()->getTimestamp()) / RP_SUB_Time::get_day_length_in_seconds());
            }

            // Subscription will be cancelled at the end of the suspension period
            $datetime = $next_renewal_payment_datetime;
            RP_SUB_Time::add_period_length_to_datetime($datetime, ("$days " . RP_SUB_Time::get_day_name()));
        }
        // Subscription is set to cancel
        else if ($new_status === 'set-to-cancel') {

            // Subscription will be cancelled at the end of the current billing cycle
            $datetime = $next_renewal_payment_datetime;
        }

        // Check if cancellation needs to be scheduled
        if ($datetime) {

            // Schedule subscription cancellation
            RP_SUB_Scheduler::schedule_subscription_cancel($subscription, $datetime);

            // Add note to log entry
            $subscription->add_log_entry_note(__('Subscription cancellation scheduled.', 'subscriptio'));
        }
    }

    /**
     * Subscription expiration scheduler
     *
     * @access public
     * @param object|int $subscription
     * @return int
     */
    public function subscription_expire_scheduler($subscription)
    {

        // TODO: Handle case when expiration is changed to be before the next renewal payment day (need to unschedule renewal payment, renewal order, reminders etc)
        // TODO: Handle case when expiration was very close but was cleared or moved further away and now a full billing cycle fits between last payment date and expiration date (need to schedule renewal payment etc)

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Subscription has lifespan set
        if ($subscription->get_lifespan()) {

            // Calculate expiration datetime
            $datetime = $subscription->calculate_expiration_datetime();

            // Ensure expiration is at least 2 hours in the future if RightPress testing mode is not enabled
            if (!defined('RIGHTPRESS_TESTING_MODE_ENABLED')) {
                RP_SUB_Time::ensure_future_datetime($datetime, '+2 hours');
            }

            // Schedule subscription expiration
            RP_SUB_Scheduler::schedule_subscription_expire($subscription, $datetime);

            // Add note to log entry
            $subscription->add_log_entry_note(__('Expiration scheduled.', 'subscriptio'));
        }
        // Subscription does not have lifespan set
        else {

            // Unschedule potentially scheduled expiration
            RP_SUB_Scheduler::unschedule_subscription_expire($subscription);
        }
    }


    /**
     * =================================================================================================================
     * SCHEDULER ALIASES
     * =================================================================================================================
     */

    /**
     * Re-schedule expiration after changes to lifespan property
     *
     * @access public
     * @param string $lifespan
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function subscription_expire_scheduler_set_lifespan($lifespan, $subscription)
    {

        // Get subscription object
        $subscription = $this->get_subscription($subscription);

        // Subscription has not started (initial lifespan was set)
        if (!$subscription->get_first_payment()) {
            return;
        }

        // Call scheduler method
        $this->subscription_expire_scheduler($subscription);
    }


    /**
     * =================================================================================================================
     * SCHEDULING METHODS
     * =================================================================================================================
     */

    /**
     * Schedule renewal order
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_renewal_order($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('renewal_order', $subscription, $datetime);
    }

    /**
     * Schedule renewal payment
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_renewal_payment($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('renewal_payment', $subscription, $datetime);
    }

    /**
     * Schedule payment retry
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_payment_retry($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('payment_retry', $subscription, $datetime);
    }

    /**
     * Schedule payment reminder
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_payment_reminder($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('payment_reminder', $subscription, $datetime);
    }

    /**
     * Schedule resumption of a manually paused subscription
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_subscription_resume($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('subscription_resume', $subscription, $datetime);
    }

    /**
     * Schedule subscription suspension
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_subscription_suspend($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('subscription_suspend', $subscription, $datetime);
    }

    /**
     * Schedule subscription cancellation
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_subscription_cancel($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('subscription_cancel', $subscription, $datetime);
    }

    /**
     * Schedule subscription expiration
     *
     * @access public
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_subscription_expire($subscription, $datetime)
    {

        return RP_SUB_Scheduler::schedule_action('subscription_expire', $subscription, $datetime);
    }

    /**
     * Schedule action
     *
     * @access public
     * @param string $action
     * @param object|int $subscription
     * @param object|int $datetime
     * @return int
     */
    public static function schedule_action($action, $subscription, $datetime)
    {

        $instance = RP_SUB_Scheduler::get_instance();

        // Get subscription object
        $subscription = $instance->get_subscription($subscription);

        // Clear any existing entries, only one entry per action/subscription is allowed
        RP_SUB_Scheduler::{"unschedule_$action"}($subscription);

        // Schedule subscription action
        $result = $instance->schedule_single($datetime, $instance->prefix_hook($action), array('subscription_id' => $subscription->get_id()), $instance->group);

        // Update subscription scheduled action datetime property
        $subscription->{"set_scheduled_$action"}($datetime);
        $subscription->save();

        // Return scheduled action id
        return $result;
    }


    /**
     * =================================================================================================================
     * UNSCHEDULING METHODS
     * =================================================================================================================
     */

    /**
     * Unschedule renewal order
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_renewal_order($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('renewal_order', $subscription);
    }

    /**
     * Unschedule renewal payment
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_renewal_payment($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('renewal_payment', $subscription);
    }

    /**
     * Unschedule payment retry
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_payment_retry($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('payment_retry', $subscription);
    }

    /**
     * Unschedule payment reminder
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_payment_reminder($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('payment_reminder', $subscription);
    }

    /**
     * Unschedule resumption of a manually paused subscription
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_subscription_resume($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('subscription_resume', $subscription);
    }

    /**
     * Unschedule subscription suspension
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_subscription_suspend($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('subscription_suspend', $subscription);
    }

    /**
     * Unschedule subscription cancellation
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_subscription_cancel($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('subscription_cancel', $subscription);
    }

    /**
     * Unschedule subscription expiration
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_subscription_expire($subscription)
    {

        RP_SUB_Scheduler::unschedule_action('subscription_expire', $subscription);
    }

    /**
     * Unschedule action
     *
     * @access public
     * @param string $action
     * @param object|int $subscription
     * @return int
     */
    public static function unschedule_action($action, $subscription)
    {

        $instance = RP_SUB_Scheduler::get_instance();

        // Get subscription object
        $subscription = $instance->get_subscription($subscription);

        // Unschedule subscription action
        $instance->unschedule($instance->prefix_hook($action), array('subscription_id' => $subscription->get_id()), $instance->group);

        // Clear subscription scheduled action datetime property
        $subscription->{"set_scheduled_$action"}(null);
        $subscription->save();
    }

    /**
     * Unschedule all actions
     *
     * @access public
     * @param object|int $subscription
     * @return void
     */
    public static function unschedule_all_actions($subscription)
    {

        $instance = RP_SUB_Scheduler::get_instance();

        // Unschedule all actions
        RP_SUB_Scheduler::unschedule_actions($subscription, array_keys($instance->get_actions()));
    }

    /**
     * Unschedule specific actions
     *
     * @access public
     * @param object|int $subscription
     * @param array $actions
     * @return void
     */
    public static function unschedule_actions($subscription, $actions)
    {

        $instance = RP_SUB_Scheduler::get_instance();

        // Iterate over actions to unschedule
        foreach ((array) $actions as $action) {

            // Unschedule action
            RP_SUB_Scheduler::{"unschedule_$action"}($subscription);
        }
    }


    /**
     * =================================================================================================================
     * SCHEDULED ACTION CALLBACKS
     * =================================================================================================================
     */

    /**
     * Scheduled renewal order
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_renewal_order($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Creating new renewal order according to subscription schedule.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'renewal_order', $start_note);
    }

    /**
     * Scheduled renewal payment
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_renewal_payment($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Processing scheduled renewal payment.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'renewal_payment', $start_note);
    }

    /**
     * Scheduled payment retry
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_payment_retry($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Processing scheduled payment retry.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'payment_retry', $start_note);
    }

    /**
     * Scheduled payment reminder
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_payment_reminder($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Sending scheduled payment reminder.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'payment_reminder', $start_note);
    }

    /**
     * Scheduled subscription resumption of a manually paused subscription
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_subscription_resume($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Resuming paused subscription automatically.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'subscription_resume', $start_note);
    }

    /**
     * Scheduled subscription suspension
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_subscription_suspend($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Processing scheduled subscription suspension.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'subscription_suspend', $start_note);
    }

    /**
     * Scheduled subscription cancellation
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_subscription_cancel($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Processing scheduled subscription cancellation.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'subscription_cancel', $start_note);
    }

    /**
     * Scheduled subscription expiration
     *
     * @access public
     * @param int $subscription_id
     * @return void
     */
    public function scheduled_subscription_expire($subscription_id)
    {

        // Define log entry notes
        $start_note = __('Processing scheduled subscription expiration.', 'subscriptio');

        // Process scheduled subscription action
        $this->process_scheduled_subscription_action($subscription_id, 'subscription_expire', $start_note);
    }


    /**
     * =================================================================================================================
     * SCHEDULED ACTION HANDLERS
     * =================================================================================================================
     */

    /**
     * Process scheduled subscription action
     *
     * @access public
     * @param int $subscription_id
     * @param string $action
     * @param string $start_note
     * @param string $end_note
     * @return void
     */
    protected function process_scheduled_subscription_action($subscription_id, $action, $start_note = null, $end_note = null)
    {

        $subscription = null;

        // Start logging
        $log_entry = RP_SUB_Log_Entry_Controller::create_log_entry(array(
            'event_type'        => $action,
            'subscription_id'   => $subscription_id,
        ));

        // Add start note to log entry
        if ($start_note !== null) {
            $log_entry->add_note($start_note);
        }

        try {

            // Load subscription object
            $subscription = $this->get_subscription($subscription_id);

            // Set log entry to subscription
            $subscription->set_log_entry($log_entry);

            // Action is not supposed to be executed
            if (!$subscription->{"get_scheduled_$action"}()) {
                throw new RightPress_Exception('rp_sub_unexpected_scheduled_action', __('Action is not supposed to be executed, aborting.', 'subscriptio'));
            }

            // Scheduled action is being executed prematurely
            if ((new RightPress_DateTime()) < $subscription->{"get_scheduled_$action"}()) {
                throw new RightPress_Exception('rp_sub_scheduled_action_executed_prematurely', __('Action executed prematurely, aborting.', 'subscriptio'));
            }

            // Clear scheduled datetime
            $subscription->{"set_scheduled_$action"}(null);
            $subscription->save();

            // Call handler
            $this->{"process_scheduled_$action"}($subscription);

            // Add end note to log entry
            if ($end_note !== null) {
                $log_entry->add_note($end_note);
            }
        }
        catch (Exception $e) {

            // Check if action was executed prematurely
            $premature_execution = is_a($e, 'RightPress_Exception') && $e->is_error_code('rp_sub_scheduled_action_executed_prematurely');

            // Handle caught exception
            $log_entry->handle_caught_exception($e, null, ($premature_execution ? 'warning' : 'error'));

            // Reschedule prematurely executed action
            if ($premature_execution) {

                // Reschedule action
                $this->{"schedule_{$action}"}($subscription, $subscription->{"get_scheduled_$action"}());

                // Add log entry note
                $log_entry->add_note(__('Action rescheduled for the correct date.', 'subscriptio'));
            }
        }

        // End logging
        $log_entry->end_logging($subscription);
    }

    /**
     * Process scheduled renewal order
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_renewal_order($subscription)
    {

        // Create renewal order
        $renewal_order = RP_SUB_WC_Order::create_renewal_order($subscription);

        // Unable to create renewal order
        if (!is_a($renewal_order, 'WC_Order')) {
            throw new RightPress_Exception('rp_sub_scheduler_unable_to_create_renewal_order', __('Unable to create renewal order. Reason unknown.', 'subscriptio'));
        }

        // Call payment reminder scheduler if order was not paid right away
        if (!$renewal_order->is_paid()) {
            $this->payment_reminder_scheduler($subscription);
        }
    }

    /**
     * Process scheduled renewal payment
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_renewal_payment($subscription)
    {

        $this->process_scheduled_renewal_payment_or_payment_retry($subscription, false);
    }

    /**
     * Process scheduled payment retry
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_payment_retry($subscription)
    {

        $this->process_scheduled_renewal_payment_or_payment_retry($subscription, true);
    }

    /**
     * Process scheduled renewal payment
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @param bool $is_retry
     * @return void
     */
    public function process_scheduled_renewal_payment_or_payment_retry($subscription, $is_retry = false)
    {

        // Subscription is not pending renewal payment
        if (!$subscription->is_pending_renewal_payment()) {

            // Add note to log entry
            $subscription->add_log_entry_note(__('Subscription is not pending renewal payment, aborting.', 'subscription'));

            // Change log entry status to warning
            $subscription->update_log_entry_status('warning');

            // Ensure there's a correct renewal payment event scheduled
            if ($subscription->has_status('trial', 'active')) {
                $this->renewal_payment_scheduler($subscription);
            }

            // Do not proceed any further
            return;
        }

        // Subscription renewal payment date is still in the future
        if ((new RightPress_DateTime()) < $subscription->calculate_next_renewal_payment_datetime()) {

            // Add note to log entry
            $subscription->add_log_entry_note(__('Subscription payment deadline is still in the future, aborting.', 'subscription'));

            // Change log entry status to warning
            $subscription->update_log_entry_status('warning');

            // Ensure there's a correct renewal payment event scheduled
            if ($subscription->has_status('trial', 'active')) {
                $this->renewal_payment_scheduler($subscription);
            }

            // Do not proceed any further
            return;
        }

        // Get pending renewal order
        $renewal_order = $subscription->get_pending_renewal_order();

        // Renewal order does not exist
        if (!is_a($renewal_order, 'WC_Order')) {

            // Add note to log entry
            $subscription->add_log_entry_note(__('Creating renewal order.', 'subscription'));

            // Create renewal order
            $renewal_order = RP_SUB_WC_Order::create_renewal_order($subscription);

            // Unable to create renewal order
            if (!is_a($renewal_order, 'WC_Order')) {
                throw new RightPress_Exception('rp_sub_scheduler_unable_to_create_renewal_order', __('Unable to create renewal order. Reason unknown.', 'subscriptio'));
            }
        }

        // Add order id to log entry
        $subscription->add_log_entry_property('order_id', $renewal_order->get_id());

        // Renewal order is already paid
        // Note: This is not an expected behaviour since we apply payments to subscriptions as soon as related orders are marked
        // paid, however, it is safe to just apply the payment now since we have protection from duplicate payment application
        if ($renewal_order->is_paid()) {

            // Add note to log entry
            $subscription->add_log_entry_note(__('Order seems to be paid, applying payment to subscription.', 'subscription'));

            // Apply payment to subscription
            $subscription->apply_payment($renewal_order);

            // Do not proceed any further
            return;
        }

        // Subscription has automatic payments
        if ($subscription->has_automatic_payments() && apply_filters('subscriptio_process_automatic_payment', RP_SUB_Main_Site_Controller::is_main_site(), $renewal_order, $subscription)) {

            // Attempt to process automatic payment
            $automatic_payment_processed = RP_SUB_Payment_Controller::process_automatic_payment($renewal_order, $subscription);

            // Automatic payment processed successfully
            if ($automatic_payment_processed) {

                // Add note to log entry
                $subscription->add_log_entry_note(__('Processing automatic payment.', 'subscription'));

                // Do not proceed any further
                return;
            }
            // Automatic payment failed
            else {

                // Add note to log entry
                $subscription->add_log_entry_note(__('Automatic payment failed.', 'subscription'));

                // Trigger action to send email
                do_action('subscriptio_subscription_automatic_payment_failed', $renewal_order, $subscription);
            }
        }

        // Check if subscription is still pending renewal payment
        if ($subscription->is_pending_renewal_payment()) {

            // Add note to log entry
            $subscription->add_log_entry_note(__('Subscription payment not received.', 'subscription'));

            // Subscription uses automatic payments and payment should be retried
            if ($subscription->has_automatic_payments() && RP_SUB_Scheduler::get_next_payment_retry_datetime($subscription)) {

                // Mark subscription overdue
                if (!$subscription->has_status('overdue')) {
                    $subscription->mark_overdue();
                }

                // Schedule payment retry
                $this->payment_retry_scheduler($subscription);
            }
            // Subscription does not use automatic payments and grace period is enabled
            else if (!$subscription->has_automatic_payments() && RP_SUB_Settings::is('overdue_period')) {

                // Mark subscription overdue
                $subscription->mark_overdue();

                // Call payment reminder scheduler
                $this->payment_reminder_scheduler($subscription);
            }
            // Suspension period is enabled
            else if (RP_SUB_Settings::is('suspension_period')) {

                // Suspend subscription
                $subscription->suspend();

                // Call payment reminder scheduler
                $this->payment_reminder_scheduler($subscription);
            }
            // All options depleted, cancel subscription right away
            else {

                // Cancel subscription
                $subscription->cancel();
            }
        }
    }

    /**
     * Process scheduled payment reminder
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_payment_reminder($subscription)
    {

        // Subscription is no longer pending renewal payment
        if (!$subscription->is_pending_renewal_payment()) {
            return;
        }

        // Get pending renewal order
        $renewal_order = $subscription->get_pending_renewal_order();

        // Pending renewal order does not exist
        // TODO: Shouldn't we use this opportunity to create missing renewal order?
        if (!$renewal_order) {
            return;
        }

        // Pending renewal order appears to be paid
        if ($renewal_order->is_paid()) {
            return;
        }

        // Trigger subscription payment reminder
        do_action('subscriptio_send_payment_reminder', $renewal_order);

        // Call payment reminder scheduler
        $this->payment_reminder_scheduler($subscription);
    }

    /**
     * Process scheduled subscription resumption
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_subscription_resume($subscription)
    {

        $subscription->resume();
    }

    /**
     * Process scheduled subscription suspension
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_subscription_suspend($subscription)
    {

        $subscription->suspend();
    }

    /**
     * Process scheduled subscription cancellation
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_subscription_cancel($subscription)
    {

        $subscription->cancel();
    }

    /**
     * Process scheduled subscription expiration
     *
     * Throws RightPress_Exception in case of an error
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return void
     */
    public function process_scheduled_subscription_expire($subscription)
    {

        $subscription->expire();
    }


    /**
     * =================================================================================================================
     * OTHER METHODS
     * =================================================================================================================
     */

    /**
     * Get subscription object
     *
     * Throws exception if subscription object can't be loaded
     *
     * @access public
     * @param RP_SUB_Subscription|int $subscription
     * @return RP_SUB_Subscription
     */
    public function get_subscription($subscription)
    {

        // Get subscription id
        $subscription_id = (is_numeric($subscription) && $subscription) ? $subscription : null;

        // Load subscription object
        $subscription = is_a($subscription, 'RP_SUB_Subscription') ? $subscription : subscriptio_get_subscription($subscription);

        // Unable to load subscription object
        if (!is_a($subscription, 'RP_SUB_Subscription')) {

            $error_message = __('Unable to load subscription object.', 'subscriptio');

            // No such post?
            if ($subscription_id !== null && !RightPress_Help::post_exists($subscription_id)) {
                $error_message .= ' ' . __('Subscription no longer exists.', 'subscriptio');
            }
            // Reason unknown
            else {
                $error_message .= ' ' . __('Reason unknown.', 'subscriptio');
            }

            // Throw exception
            throw new RightPress_Exception('rp_sub_scheduler_unable_to_load_subscription', $error_message);
        }

        // No scheduled actions should be performed on terminated subscriptions
        if ($subscription->is_terminated()) {
            throw new RightPress_Exception('rp_sub_scheduler_subscription_terminated', __('Subscription is cancelled or expired - no further actions allowed.', 'subscriptio'));
        }

        return $subscription;
    }

    /**
     * Get next payment retry datetime
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return RightPress_DateTime|null
     */
    public static function get_next_payment_retry_datetime($subscription)
    {

        return RP_SUB_Scheduler::get_next_or_last_payment_retry_datetime($subscription, false);
    }

    /**
     * Get last payment retry datetime
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return RightPress_DateTime|null
     */
    public static function get_last_payment_retry_datetime($subscription)
    {

        return RP_SUB_Scheduler::get_next_or_last_payment_retry_datetime($subscription, true);
    }

    /**
     * Get next or last payment retry datetime
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @param bool $last
     * @return RightPress_DateTime|null
     */
    public static function get_next_or_last_payment_retry_datetime($subscription, $last = false)
    {

        $datetime = null;

        // Check if subscription uses automatic payments
        if ($subscription->has_automatic_payments()) {

            // Get array of payment retry days
            if ($retries = RP_SUB_Settings::get('payment_retries')) {

                // Get renewal payment datetime
                $renewal_payment = $subscription->calculate_next_renewal_payment_datetime();

                // Sort retry days from smallest to largest
                sort($retries);

                // Getting last payment retry
                if ($last) {

                    // Get last retry day
                    $retry_day = array_pop($retries);

                    // Calculate last retry datetime
                    $current_datetime = clone $renewal_payment;
                    $current_datetime->modify("+{$retry_day} " . RP_SUB_Time::get_day_name());

                    // Check if last retry is in the future
                    if ($current_datetime > (new RightPress_DateTime())) {

                        // Last retry found
                        $datetime = $current_datetime;
                    }
                }
                // Getting next payment retry
                else {

                    // Iterate over retry days
                    foreach ($retries as $retry_day) {

                        // Calculate current retry datetime
                        $current_datetime = clone $renewal_payment;
                        $current_datetime->modify("+{$retry_day} " . RP_SUB_Time::get_day_name());

                        // Check if current retry is in the future
                        if ($current_datetime > (new RightPress_DateTime())) {

                            // Upcoming retry found
                            $datetime = $current_datetime;
                            break;
                        }
                    }
                }
            }
        }

        return $datetime;
    }

    /**
     * Get next payment reminder datetime
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return RightPress_DateTime|null
     */
    public static function get_next_payment_reminder_datetime($subscription)
    {

        $datetime               = null;
        $reminders              = null;
        $next_action_datetime   = null;

        // Subscription is active and does not use automatic payments
        if ($subscription->has_status('active') && !$subscription->has_automatic_payments()) {

            // Get reminders sent before renewal payment date
            $reminders = RP_SUB_Settings::is('renewal_order_offset') ? RP_SUB_Settings::get('payment_reminders') : array();

            // Get next action datetime
            $next_action_datetime = $subscription->calculate_next_renewal_payment_datetime();
        }
        // Subscription is overdue and does not use automatic payments
        else if ($subscription->has_status('overdue') && !$subscription->has_automatic_payments()) {

            // Get reminders sent before subscription suspension or cancellation
            $reminders = RP_SUB_Settings::is('overdue_period') ? RP_SUB_Settings::get('overdue_payment_reminders') : array();

            // Get next action datetime
            $next_action_datetime = $subscription->get_scheduled_subscription_suspend() ? $subscription->get_scheduled_subscription_suspend() : $subscription->get_scheduled_subscription_cancel();
        }
        // Subscription is suspended
        else if ($subscription->has_status('suspended')) {

            // Get reminders sent before subscription cancellation
            $reminders = RP_SUB_Settings::is('suspension_period') ? RP_SUB_Settings::get('suspend_payment_reminders') : array();

            // Get next action datetime
            $next_action_datetime = $subscription->get_scheduled_subscription_cancel();
        }

        // Check if any reminders are configured and next action datetime was determined
        if ($reminders && $next_action_datetime) {

            // Sort reminder days from largest to smallest
            rsort($reminders);

            // Iterate over reminder days
            foreach ($reminders as $reminder_day) {

                // Calculate current reminder datetime
                $current_datetime = clone $next_action_datetime;
                $current_datetime->modify("-{$reminder_day} " . RP_SUB_Time::get_day_name());

                // Check if current reminder is in the future
                if ($current_datetime > (new RightPress_DateTime())) {

                    // Upcoming reminder found
                    $datetime = $current_datetime;
                    break;
                }
            }
        }

        return $datetime;
    }

    /**
     * Calculate overdue period length in days
     *
     * @access public
     * @param RP_SUB_Subscription $subscription
     * @return int|null
     */
    public static function calculate_overdue_period_length($subscription)
    {

        // Automatic subscriptions
        if ($subscription->has_automatic_payments()) {

            // Get array of payment retry days
            if ($retries = RP_SUB_Settings::get('payment_retries')) {

                // Sort retry days from smallest to largest
                sort($retries);

                // Overdue period equals a number of days between renewal payment day and last payment retry day
                $overdue_period = array_pop($retries);
            }
        }
        // Manual subscriptions
        else {

            // Just take value from the setting
            $overdue_period = RP_SUB_Settings::get('overdue_period');
        }

        return (isset($overdue_period) && $overdue_period) ? $overdue_period : null;
    }





}

RP_SUB_Scheduler::get_instance();
