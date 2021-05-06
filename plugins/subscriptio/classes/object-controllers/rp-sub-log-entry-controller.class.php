<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

// Load dependencies
require_once 'abstract/rp-sub-wp-log-entry-controller.class.php';

/**
 * Log Entry Controller
 *
 * @class RP_SUB_Log_Entry_Controller
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Log_Entry_Controller extends RP_SUB_WP_Log_Entry_Controller
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

        // Call parent constructor
        parent::__construct();
    }

    /**
     * Get object name
     *
     * @access public
     * @return string
     */
    public function get_object_name()
    {

        return 'log_entry';
    }

    /**
     * Get object class
     *
     * @access public
     * @return string
     */
    public function get_object_class()
    {

        return 'RP_SUB_Log_Entry';
    }

    /**
     * Get data store class
     *
     * @access public
     * @return string
     */
    public function get_data_store_class()
    {

        return 'RP_SUB_Log_Entry_Data_Store';
    }

    /**
     * Define custom taxonomies with terms
     *
     * @access public
     * @return array
     */
    public function define_taxonomies_with_terms()
    {

        /**
         * IMPORTANT:
         * If event type is related to scheduled action, then event type and scheduled action names must match
         * See RP_SUB_Scheduler::register_actions()
         */

        return array(

            // Event type
            'event_type' => array(

                // Taxonomy settings
                'singular'  => __('Event type', 'subscriptio'),
                'plural'    => __('Event types', 'subscriptio'),
                'all'       => __('All event types', 'subscriptio'),

                // Grouped terms
                'grouped_terms' => array(

                    // TODO: Make sure logging of all events below is implemented, all of them are used and there are no other events in plugin without representation here

                    'subscription' => array(
                        'label' => __('Subscription', 'subscriptio'),
                        'terms' => array(

                            // Other subscription changes
                            'new_subscription' => array(
                                'label' => __('New subscription', 'subscriptio'),
                            ),
                            'subscription_edit' => array(
                                'label' => __('Subscription edited', 'subscriptio'),
                            ),
                            'subscription_delete' => array(
                                'label' => __('Subscription deleted', 'subscriptio'),
                            ),

                            // Subscription status changes
                            'subscription_pause' => array(
                                'label' => __('Subscription pausing', 'subscriptio'),
                            ),
                            'subscription_resume' => array(
                                'label' => __('Subscription resuming', 'subscriptio'),
                            ),
                            'subscription_suspend' => array(
                                'label' => __('Subscription suspending', 'subscriptio'),
                            ),
                            'subscription_set_to_cancel' => array(
                                'label' => __('Set to cancel', 'subscriptio'),
                            ),
                            'subscription_cancel' => array(
                                'label' => __('Subscription cancelling', 'subscriptio'),
                            ),
                            'subscription_reactivate' => array(
                                'label' => __('Subscription reactivating', 'subscriptio'),
                            ),
                            'subscription_expire' => array(
                                'label' => __('Subscription expiring', 'subscriptio'),
                            ),
                        ),
                    ),

                    'payment' => array(
                        'label' => __('Payment', 'subscriptio'),
                        'terms' => array(

                            'renewal_payment' => array(
                                'label' => __('Payment due', 'subscriptio'),
                            ),
                            'payment_retry' => array(
                                'label' => __('Payment retry', 'subscriptio'),
                            ),
                            'payment_received' => array(
                                'label' => __('Payment received', 'subscriptio'),
                            ),
                        ),
                    ),

                    'order' => array(
                        'label' => __('Order', 'subscriptio'),
                        'terms' => array(

                            'initial_order' => array(
                                'label' => __('New initial order', 'subscriptio'),
                            ),
                            'renewal_order' => array(
                                'label' => __('New renewal order', 'subscriptio'),
                            ),
                            'order_cancel' => array(
                                'label' => __('Order cancelled', 'subscriptio'),
                            ),
                            'order_refund' => array(
                                'label' => __('Order refunded', 'subscriptio'),
                            ),
                            'order_delete' => array(
                                'label' => __('Order deleted', 'subscriptio'),
                            ),
                        ),
                    ),

                    'notification' => array(
                        'label' => __('Notifications', 'subscriptio'),
                        'terms' => array(

                            'payment_reminder' => array(
                                'label' => __('Payment reminder', 'subscriptio'),
                            ),
                        ),
                    ),

                    'errors' => array(
                        'label' => __('Other errors', 'subscriptio'),
                        'terms' => array(

                            'unexpected_error' => array(
                                'label' => __('Unexpected error', 'subscriptio'),
                            ),
                        ),
                    ),

                    'other' => array(
                        'label' => __('Other', 'subscriptio'),
                        'terms' => array(

                            'settings_update' => array(
                                'label' => __('Settings updated', 'subscriptio'),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }





}

RP_SUB_Log_Entry_Controller::get_instance();
