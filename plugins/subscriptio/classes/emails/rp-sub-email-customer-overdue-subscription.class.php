<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

// Load dependencies
require_once 'rp-sub-subscription-email.class.php';

// We are including these files so need to check if class has not been defined yet
if (!class_exists('RP_SUB_Email_Customer_Overdue_Subscription', false)) {

/**
 * Customer Overdue Subscription Email
 *
 * @class RP_SUB_Email_Customer_Overdue_Subscription
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Email_Customer_Overdue_Subscription extends RP_SUB_Subscription_Email
{

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {

        $this->id               = 'customer_overdue_subscription';
        $this->customer_email   = true;
        $this->title            = __('Subscription overdue', 'subscriptio');
        $this->description      = __('Subscription overdue emails are sent to customers when subscription payments are not received by the payment due date and grace period is allowed.', 'subscriptio');

        // Call parent constructor
        parent::__construct();
    }

    /**
     * Get email subject
     *
     * @access public
     * @return string
     */
    public function get_default_subject()
    {

        return __('Your {site_title} subscription is overdue', 'subscriptio');
    }

    /**
     * Get email heading
     *
     * @access public
     * @return string
     */
    public function get_default_heading()
    {

        return __('Your subscription is overdue', 'subscriptio');
    }

    /**
     * Default content to show below main email content
     *
     * @access public
     * @return string
     */
    public function get_default_additional_content()
    {

        return __('Thank you for choosing us.', 'subscriptio');
    }

    /**
     * Get template variables
     *
     * @access public
     * @return array
     */
    public function get_template_variables()
    {

        // Reference subscription
        $subscription = $this->object;

        // Get next action
        $next_action = $subscription->get_scheduled_subscription_suspend() ? 'suspend' : 'cancel';

        // Get next action date
        if ($next_action === 'suspend') {
            $next_action_date = $subscription->get_scheduled_subscription_suspend() ? $subscription->get_scheduled_subscription_suspend()->format_date() : '?';
        }
        else {
            $next_action_date = $subscription->get_scheduled_subscription_cancel() ? $subscription->get_scheduled_subscription_cancel()->format_date() : '?';
        }

        // Merge with default variables and return
        return array_merge(parent::get_template_variables(), array(
            'next_action'       => $next_action,
            'next_action_date'  => $next_action_date,
        ));
    }





}
}

return new RP_SUB_Email_Customer_Overdue_Subscription();
