<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Methods related to Settings
 *
 * @class RP_SUB_Settings
 * @package Subscriptio
 * @author RightPress
 */

class RP_SUB_Settings extends RightPress_Plugin_Settings
{

    // Singleton control
    protected static $instance = false; public static function get_instance() { return self::$instance ? self::$instance : (self::$instance = new self()); }

    // Define settings structure version
    protected $version = '1';

    // Define parent menu key
    protected $parent_menu_key = 'edit.php?post_type=rp_sub_subscription';

    // Tab key for import and export section
    protected $import_export_tab_key = 'advanced';

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
     * Define structure
     *
     * @access public
     * @return array
     */
    public function define_structure()
    {

        return array(

            /**
             * GENERAL
             */
            'general' => array(
                'title'     => __('General', 'subscriptio'),
                'children'  => array(

                    /**
                     * GENERAL SETTINGS
                     */
                    'general_settings' => array(
                        'title'     => __('General', 'subscriptio'),
                        'children'  => array(

                            'add_to_cart_label' => array(
                                'type'          => 'text',
                                'title'         => __("\"Add to cart\" button text", 'subscriptio'),
                                'placeholder'   => __('Do not change', 'subscriptio'),
                                'hint'          => __("Changes \"Add to cart\" button text for subscription products.", 'subscriptio'),
                            ),

                            'multiple_product_checkout' => array(
                                'type'          => 'select',
                                'title'         => __('If multiple subscription products are in cart', 'subscriptio'),
                                'hint'          => __('For multiple subscription products to end up in one subscription, they must have identical billing cycle, trial and lifespan settings.', 'subscriptio'),
                                'options'       => array(
                                    'multiple_subscriptions'    => __('Create multiple single-product subscriptions', 'subscriptio'),
                                    'single_subscription'       => __('Create single multi-product subscription', 'subscriptio'),
                                ),
                                'default'       => 'multiple_subscriptions',
                                'validation'    => array('is_required' => true),
                            ),
                        ),
                    ),

                    /**
                     * MY ACCOUNT
                     */
                    'my_account' => array(
                        'title'     => __('My Account', 'subscriptio'),
                        'children'  => array(

                            'display_empty_subscription_list' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Display empty subscriptions list', 'subscriptio'),
                                'default'   => '1',
                            ),
                        ),
                    ),

                    /**
                     * PRICING
                     */
                    'pricing' => array(
                        'title'     => __('Pricing, Discounts & Fees', 'subscriptio'),
                        'children'  => array(

                            'sale_price_is_recurring' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Sale price is recurring', 'subscriptio'),
                                'default'   => '1',
                            ),

                            'cart_discounts_are_recurring' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Cart discounts are recurring', 'subscriptio'),
                                'default'   => '0',
                            ),

                            'checkout_fees_are_recurring' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Checkout fees are recurring', 'subscriptio'),
                                'default'   => '0',
                            ),

                            'shipping_is_recurring' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Shipping fees are recurring', 'subscriptio'),
                                'default'   => '1',
                            ),

                            'signup_fees_per_item' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Sign-up fees are multiplied by quantity', 'subscriptio'),
                                'default'   => '0',
                            ),
                        ),
                    ),
                ),
            ),

            /**
             * LIMITS
             */
            'limits' => array(
                'title'     => __('Limits', 'subscriptio'),
                'children'  => array(

                    /**
                     * SUBSCRIPTION LIMITS
                     */
                    'subscription_limits' => array(
                        'title' => __('Subscription Limits', 'subscriptio'),
                        'children' => array(

                            'subscription_limit' => array(
                                'type'          => 'select',
                                'title'         => __('Active subscription limit', 'subscriptio'),
                                'options'       => array(
                                    'no_limit'          => __('No limit', 'subscriptio'),
                                    'one_per_product'   => __('One active subscription per product per customer', 'subscriptio'),
                                    'one_per_customer'  => __('One active subscription per customer', 'subscriptio'),
                                ),
                                'default'       => 'no_limit',
                                'validation'    => array('is_required' => true),
                            ),

                            'trial_limit' => array(
                                'type'          => 'select',
                                'title'         => __('Free trial limit', 'subscriptio'),
                                'options'       => array(
                                    'no_limit'          => __('No limit', 'subscriptio'),
                                    'one_per_product'   => __('One free trial per product per customer', 'subscriptio'),
                                    'one_per_customer'  => __('One free trial per customer', 'subscriptio'),
                                ),
                                'default'       => 'no_limit',
                                'validation'    => array('is_required' => true),
                            ),
                        ),
                    ),

                    /**
                     * CUSTOMER CAPABILITIES
                     */
                    'customer_capabilities' => array(
                        'title'     => __('Customer Capabilities', 'subscriptio'),
                        'children'  => array(

                            'customer_pausing' => array(
                                'type'          => 'select',
                                'title'         => __('Pausing by customers', 'subscriptio'),
                                'options'       => array(
                                    'not_allowed'           => __('Not allowed', 'subscriptio'),
                                    'allowed_no_limits'     => __('Allowed', 'subscriptio'),
                                    'allowed_with_limits'   => __('Allowed with limits', 'subscriptio'),
                                ),
                                'default'       => 'not_allowed',
                                'validation'    => array('is_required' => true),
                            ),

                            'customer_pausing_number_limit' => array(
                                'type'          => 'number',
                                'title'         => __('Pause limit', 'subscriptio'),
                                'after'         => __('pauses per subscription', 'subscriptio'),
                                'validation'    => array(
                                    'is_whole'  => true,
                                    'min'       => 1,
                                ),
                                'placeholder'   => __('unlimited', 'subscriptio'),
                                'conditions'    => array(
                                    'customer_pausing' => array(
                                        'value' => 'allowed_with_limits',
                                    ),
                                ),
                            ),

                            'customer_pausing_duration_limit' => array(
                                'type'          => 'number',
                                'title'         => __('Resume automatically after', 'subscriptio'),
                                'after'         => __('days', 'subscriptio'),
                                'validation'    => array(
                                    'is_whole'  => true,
                                    'min'       => 1,
                                ),
                                'placeholder'   => __('unlimited', 'subscriptio'),
                                'conditions'    => array(
                                    'customer_pausing' => array(
                                        'value' => 'allowed_with_limits',
                                    ),
                                ),
                            ),

                            'customer_cancelling' => array(
                                'type'          => 'select',
                                'title'         => __('Cancelling by customers', 'subscriptio'),
                                'options'       => array(
                                    'not_allowed'   => __('Not allowed', 'subscriptio'),
                                    'delayed'       => __('Allowed, effective at the end of the prepaid term', 'subscriptio'),
                                    'immediate'     => __('Allowed, effective immediately', 'subscriptio'),
                                ),
                                'default'       => 'not_allowed',
                                'validation'    => array('is_required' => true),
                            ),
                        ),
                    ),
                ),
            ),

            /**
             * SCHEDULE
             */
            'schedule' => array(
                'title'     => __('Schedule', 'subscriptio'),
                'children'  => array(

                    /**
                     * AUTOMATIC PAYMENTS
                     */
                    'automatic_payment_schedule' => array(
                        'title'     => __('Automatic Payments', 'subscriptio'),
                        'info'      => __('Failed payments can be retried a number of times before subscription is suspended or cancelled. If payment is to be retried, subscription enters a grace period and its status is set to overdue.', 'subscriptio'),
                        'children'  => array(

                            'payment_retries' => array(
                                'type'          => 'tags',
                                'title'         => __('Retry failed payments', 'subscriptio'),
                                'placeholder'   => __('none', 'subscriptio'),
                                'after'         => __('days after first payment fails', 'subscriptio'),
                                'default'       => '',
                            ),
                        ),
                    ),

                    /**
                     * MANUAL PAYMENTS
                     */
                    'manual_payment_schedule' => array(
                        'title'     => __('Manual Payments', 'subscriptio'),
                        'info'      => __('Renewal orders are created in advance allowing customers to make renewal payments before they are due. Optional grace period can give customers extra time before subscription is suspended or cancelled.', 'subscriptio'),
                        'children'  => array(

                            'renewal_order_offset' => array(
                                'type'          => 'number',
                                'title'         => __('Generate renewal orders', 'subscriptio'),
                                'placeholder'   => __('same day', 'subscriptio'),
                                'after'         => __('days before renewal payment date', 'subscriptio'),
                                'default'       => 1,
                                'validation'    => array(
                                    'is_required'   => true,
                                    'is_whole'      => true,
                                    'min'           => 1,
                                ),
                            ),

                            'payment_reminders' => array(
                                'type'          => 'tags',
                                'title'         => __('Send payment reminders', 'subscriptio'),
                                'placeholder'   => __('none', 'subscriptio'),
                                'after'         => __('days before renewal payment date', 'subscriptio'),
                                'default'       => '',
                                'conditions'    => array(
                                    'renewal_order_offset' => array(
                                        'not_empty' => true,
                                    ),
                                ),
                            ),

                            'overdue_period' => array(
                                'type'          => 'number',
                                'title'         => __('Grace period', 'subscriptio'),
                                'placeholder'   => __('none', 'subscriptio'),
                                'after'         => __('days', 'subscriptio'),
                                'default'       => '',
                                'validation'    => array(
                                    'is_whole'  => true,
                                    'min'       => 1,
                                ),
                            ),

                            'overdue_payment_reminders' => array(
                                'type'          => 'tags',
                                'title'         => __('Send payment reminders', 'subscriptio'),
                                'placeholder'   => __('none', 'subscriptio'),
                                'after'         => __('days before suspension or cancellation date', 'subscriptio'),
                                'default'       => '',
                                'conditions'    => array(
                                    'overdue_period' => array(
                                        'not_empty' => true,
                                    ),
                                ),
                            ),
                        ),
                    ),

                    /**
                     * Suspensions
                     */
                    'suspensions' => array(
                        'title'     => __('Suspensions', 'subscriptio'),
                        'info'      => __('Subscriptions can be suspended for a period of time before they are permanently cancelled. This gives customers the last chance to keep their subscriptions.', 'subscriptio'),
                        'children'  => array(

                            'suspension_period' => array(
                                'type'          => 'number',
                                'title'         => __('Suspension period', 'subscriptio'),
                                'placeholder'   => __('none', 'subscriptio'),
                                'after'         => __('days', 'subscriptio'),
                                'default'       => '',
                                'validation'    => array(
                                    'is_whole'  => true,
                                    'min'       => 1,
                                ),
                            ),

                            'suspend_payment_reminders' => array(
                                'type'          => 'tags',
                                'title'         => __('Send cancellation notices', 'subscriptio'),
                                'placeholder'   => __('none', 'subscriptio'),
                                'after'         => __('days before cancellation date', 'subscriptio'),
                                'default'       => '',
                                'conditions'    => array(
                                    'suspension_period' => array(
                                        'not_empty' => true,
                                    ),
                                ),
                            ),
                        ),
                    ),

                    /**
                     * PREPAID TERM EXTENSION
                     */
                    'prepaid_term_extension' => array(
                        'title'     => __('Prepaid Term Extension', 'subscriptio'),
                        'info'      => __('Choose whether to extend the prepaid subscription term by the amount of days subscription was paused or suspended.', 'subscriptio'),
                        'children'  => array(

                            'add_paused_days' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Account for days subscription was paused', 'subscriptio'),
                                'default'   => '0',
                            ),

                            'add_suspended_days' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Account for days subscription was suspended', 'subscriptio'),
                                'default'   => '0',
                            ),
                        ),
                    ),
                ),
            ),

            /**
             * GATEWAYS
             */
            'gateways' => array(
                'title'     => __('Gateways', 'subscriptio'),
                'children'  => array(

                    /**
                     * STRIPE
                     */
                    'stripe' => array(
                        'title'         => __('Stripe', 'subscriptio'),
                        'print_empty'   => true,
                        'children'      => array(

                        ),
                    ),

                    /**
                     * PAYPAL EXPRESS CHECKOUT
                     */
                    'paypal_ec' => array(
                        'title'         => __('PayPal Express Checkout', 'subscriptio'),
                        'print_empty'   => true,
                        'children'      => array(

                            'paypal_ec_enabled' => array(
                                'type'      => 'checkbox',
                                'title'     => __('Enable', 'subscriptio'),
                                'default'   => '0',
                            ),
                        ),
                    ),

                    /**
                     * MANUAL PAYMENTS
                     */
                    'manual_payments' => array(
                        'title'         => __('Manual Payments', 'subscriptio'),
                        'print_empty'   => true,
                        'children'      => array(

                        ),
                    ),
                ),
            ),

            /**
             * ADVANCED
             */
            'advanced' => array(
                'title'     => __('Advanced', 'subscriptio'),
                'children'  => array(

                ),
            ),
        );
    }

    /**
     * Migrate settings
     *
     * @access public
     * @param array $stored
     * @return array
     */
    public function migrate_settings($stored)
    {

        return RP_SUB_Data_Updater::migrate_settings($stored, $this->version);
    }

    /**
     * Get plugin private prefix
     *
     * @access public
     * @return string
     */
    public function get_plugin_private_prefix()
    {

        return RP_SUB_PLUGIN_PRIVATE_PREFIX;
    }

    /**
     * Get plugin path
     *
     * @access public
     * @return string
     */
    public function get_plugin_path()
    {

        return RP_SUB_PLUGIN_PATH;
    }

    /**
     * Get settings capability
     *
     * @access public
     * @return string
     */
    public function get_capability()
    {

        return RP_SUB::get_admin_capability();
    }

    /**
     * Custom sanitization handler
     *
     * Boolean false return value is reserved to indicate that value was not sanitized
     *
     * @access protected
     * @param array $input
     * @param string $field_key
     * @param string $prefixed_key
     * @param mixed $current_value
     * @param array $output
     * @param array $field
     * @return mixed
     */
    protected function sanitize_custom($input, $field_key, $prefixed_key, $current_value, $output, $field)
    {

        // Sanitize payment retries and reminders
        if (in_array($field_key, array('payment_retries', 'payment_reminders', 'overdue_payment_reminders', 'suspend_payment_reminders'), true)) {

            // Call main tags sanitizer
            $output = $this->sanitize_field_tags($input, $prefixed_key, $current_value, $field);

            // Leave only positive whole integers as ints
            foreach ($output as $key => $value) {

                // TODO: We should prevent entering wrong values in UI in the first place

                // Value must be positive whole number
                if (!RightPress_Help::is_whole_number($value) || $value < 1) {
                    unset($output[$key]);
                }
                // Cast value to int
                else {
                    $output[$key] = (int) $value;
                }
            }

            // Sort numeric values
            if ($field_key === 'payment_retries') {
                asort($output);
            }
            else {
                arsort($output);
            }

            // Return sanitized value
            return $output;
        }

        // Value was not sanitized
        return false;
    }

    /**
     * Print section info
     *
     * @access public
     * @param array $section
     * @return void
     */
    public function print_section_info($section)
    {

        // TODO: Add link tu gateway's settings page

        // Gateways - Stripe
        if ($section['id'] === 'stripe') {

            echo '<p>' . sprintf(__('Subscriptio integrates with the official <a href="%s">WooCommerce Stripe Payment Gateway</a> extension. You can <a href="%s">download</a> and install this payment gateway extension for free.', 'subscriptio'), 'http://url.rightpress.net/woocommerce-gateway-stripe-download-page', 'http://url.rightpress.net/woocommerce-gateway-stripe-download-page') . '</p>';
            echo '<p style="padding-top: 0;">' . __('If customer uses this payment method during checkout, subsequent subscription payments will be processed automatically.', 'subscriptio') . '</p>';
            echo '<p style="padding-top: 0;">' . sprintf(__('To learn more about this payment gateway extension, <a href="%s">click here</a>.', 'subscriptio'), 'http://url.rightpress.net/subscriptio-gateways-stripe') . '</p>';

            echo '<table class="form-table" role="presentation"><tbody><tr><th scope="row">' . __('Installed and enabled?', 'subscriptio') . '</th><td><span style="text-decoration: underline; text-decoration-style: dotted;">' . (RP_SUB_WooCommerce_Gateway_Stripe::get_payment_gateway() ? __('Yes', 'subscriptio') : __('No', 'subscriptio')) . '</span></td></tr></tbody></table>';
        }
        // Gateways - PayPal Express Checkout
        else if ($section['id'] === 'paypal_ec') {

            echo '<p>' . __('PayPal Express Checkout payment gateway extension comes bundled with Subscriptio. Reference Transactions must be enabled on your PayPal account for automatic payments to work.', 'subscriptio') . '</p>';
            echo '<p style="padding-top: 0;">' . sprintf(__('To learn more about this payment gateway extension, <a href="%s">click here</a>.', 'subscriptio'), 'http://url.rightpress.net/subscriptio-gateways-paypal-express-checkout') . '</p>';
        }
        // Gateways - Manual Payments
        else if ($section['id'] === 'manual_payments') {

            echo '<p>' . __('If automatic payments are not set up for a subscription, it will require manual payment at the beginning of each billing cycle. In this case customer will receive a payment due notification with a link to the renewal order payment page.', 'subscriptio') . '</p>';
            echo '<p style="padding-top: 0;">' . __('Any WooCommerce payment gateway extension can be used for manual subscription payments, including offline payment methods.', 'subscriptio') . '</p>';
            echo '<p style="padding-top: 0; margin-bottom: 25px;">' . sprintf(__('To learn more about manual subscription payments, <a href="%s">click here</a>.', 'subscriptio'), 'http://url.rightpress.net/subscriptio-gateways-manual-payments') . '</p>';
        }

        // Call parent
        parent::print_section_info($section);
    }





}

RP_SUB_Settings::get_instance();
