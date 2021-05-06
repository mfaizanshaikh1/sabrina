<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

// Load dependencies
require_once 'rp-sub-subscription-email.class.php';

// We are including these files so need to check if class has not been defined yet
if (!class_exists('RP_SUB_Email_Customer_Suspended_Subscription', false)) {

/**
 * Customer Suspended Subscription Email
 *
 * @class RP_SUB_Email_Customer_Suspended_Subscription
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Email_Customer_Suspended_Subscription extends RP_SUB_Subscription_Email
{

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {

        $this->id               = 'customer_suspended_subscription';
        $this->customer_email   = true;
        $this->title            = __('Subscription suspended', 'subscriptio');
        $this->description      = __('Subscription suspended emails are sent to customers when subscriptions are suspended due to non-payment and suspensions are enabled.', 'subscriptio');

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

        return __('Your {site_title} subscription has been suspended', 'subscriptio');
    }

    /**
     * Get email heading
     *
     * @access public
     * @return string
     */
    public function get_default_heading()
    {

        return __('Your subscription has been suspended', 'subscriptio');
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

        // Merge with default variables and return
        return array_merge(parent::get_template_variables(), array(
            'next_action_date' => ($this->object->get_scheduled_subscription_cancel() ? $this->object->get_scheduled_subscription_cancel()->format_date() : '?'),
        ));
    }





}
}

return new RP_SUB_Email_Customer_Suspended_Subscription();
