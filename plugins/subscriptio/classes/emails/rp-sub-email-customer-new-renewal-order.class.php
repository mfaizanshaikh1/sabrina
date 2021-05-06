<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

// Load dependencies
require_once 'rp-sub-renewal-order-email.class.php';

// We are including these files so need to check if class has not been defined yet
if (!class_exists('RP_SUB_Email_Customer_New_Renewal_Order', false)) {

/**
 * Customer New Renewal Order Email
 *
 * @class RP_SUB_Email_Customer_New_Renewal_Order
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Email_Customer_New_Renewal_Order extends RP_SUB_Renewal_Order_Email
{

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {

        $this->id               = 'customer_new_renewal_order';
        $this->customer_email   = true;
        $this->title            = __('New renewal order', 'subscriptio');
        $this->description      = __('New renewal order emails are sent to customers when a new renewal order is generated for subscription.', 'subscriptio');

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

        return __('New subscription renewal order {order_number}', 'subscriptio');
    }

    /**
     * Get email heading
     *
     * @access public
     * @return string
     */
    public function get_default_heading()
    {

        return __('New subscription renewal order {order_number}', 'subscriptio');
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

return new RP_SUB_Email_Customer_New_Renewal_Order();
