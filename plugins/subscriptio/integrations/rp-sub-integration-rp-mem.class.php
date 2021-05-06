<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * RP_SUB integration with RP_MEM
 *
 * @class RP_SUB_Integration_RP_MEM
 * @package Subscriptio
 * @author RightPress
 */
class RP_SUB_Integration_RP_MEM
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

        // RP_MEM version 3.0+ present
        if (defined('RP_MEM_VERSION')) {

            // TODO
        }
        // Old WooCommerce Membership present, print notice
        else if (defined('RPWCM_VERSION')) {
            add_action('admin_notices', array($this, 'print_incompatible_version_notice'));
        }
    }

    /**
     * Old WooCommerce Membership active on site, print notice
     *
     * @access public
     * @return void
     */
    public function print_incompatible_version_notice()
    {

        // Notice dismissed earlier
        if (get_option('rp_sub_rp_mem_incompatible_version_notice_dismissed', false)) {
            return;
        }

        // Notice dismissed now
        if (!empty($_REQUEST['rp_sub_rp_mem_incompatible_version_notice_dismissed'])) {
            update_option('rp_sub_rp_mem_incompatible_version_notice_dismissed', '1', false);
            return;
        }

        // Notice Format notice
        $notice = '<p>';
        $notice .= __('<strong>Subscriptio</strong> version 3.0 and up is only compatible with <strong>WooCommerce Membership</strong> version 3.0 and up.', 'subscriptio');
        $notice .= '</p><p>';
        $notice .= __('Plugins will work but they will not communicate with each other, e.g. membership will not be cancelled when subscription is cancelled.', 'subscriptio');
        $notice .= '</p><p>';
        $notice .= sprintf(__('Please read our <a href="%s">upgrade guide</a> for more information.', 'subscriptio'), 'http://url.rightpress.net/subscriptio-3-0-rp-mem-integration');
        $notice .= '</p><p><small>';
        $notice .= '<a href="' . add_query_arg(array('rp_sub_rp_mem_incompatible_version_notice_dismissed' => '1')) . '">' . __('Hide this notice', 'subscriptio') . '</a>';
        $notice .= '</small></p>';

        // Print notice
        echo '<div id="rp_sub_rp_mem_incompatible_version_notice" class="error" style="padding-bottom: 7px;"><h3>' . __('Warning!', 'subscriptio') . '</h3>' . $notice . '</div>';
    }





}

RP_SUB_Integration_RP_MEM::get_instance();
