<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

// Load dependencies
require_once 'rp-sub-renewal-order-email.class.php';

// We are including these files so need to check if class has not been defined yet
if (!class_exists('RP_SUB_Email_Customer_Completed_Renewal_Order', false)) {

/**
 * Customer Completed Renewal Order Email
 *
 * @class RP_SUB_Email_Customer_Completed_Renewal_Order
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Email_Customer_Completed_Renewal_Order extends RP_SUB_Renewal_Order_Email
{

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {

        $this->id               = 'customer_completed_renewal_order';
        $this->customer_email   = true;
        $this->title            = __('Completed renewal order', 'subscriptio');
        $this->description      = __('Completed renewal order emails are sent to customers when their subscription renewal orders are marked completed. For tangible products this usually indicates that orders have been shipped.', 'subscriptio');

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

        return __('Your {site_title} renewal order is now complete', 'subscriptio');
    }

    /**
     * Get email heading
     *
     * @access public
     * @return string
     */
    public function get_default_heading()
    {

        return __('Your renewal order is now complete', 'subscriptio');
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

return new RP_SUB_Email_Customer_Completed_Renewal_Order();
