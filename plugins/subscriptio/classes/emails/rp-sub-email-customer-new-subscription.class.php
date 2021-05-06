<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

// Load dependencies
require_once 'rp-sub-subscription-email.class.php';

// We are including these files so need to check if class has not been defined yet
if (!class_exists('RP_SUB_Email_Customer_New_Subscription', false)) {

/**
 * Customer New Subscription Email
 *
 * @class RP_SUB_Email_Customer_New_Subscription
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Email_Customer_New_Subscription extends RP_SUB_Subscription_Email
{

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {

        $this->id               = 'customer_new_subscription';
        $this->customer_email   = true;
        $this->title            = __('New subscription', 'subscriptio');
        $this->description      = __('New subscription emails are sent to customers when a new subscription is created.', 'subscriptio');

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

        return __('Your {site_title} subscription has been created', 'subscriptio');
    }

    /**
     * Get email heading
     *
     * @access public
     * @return string
     */
    public function get_default_heading()
    {

        return __('Your subscription has been created', 'subscriptio');
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





}
}

return new RP_SUB_Email_Customer_New_Subscription();
