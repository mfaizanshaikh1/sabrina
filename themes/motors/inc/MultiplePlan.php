<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 11/22/19
 * Time: 11:55
 */

class MultiplePlan
{
	public static $currentUser;
	private static $wpdbObj;
	private static $plansMeta;
	private static $isMultiple = false;

	public function __construct() {
		global $wpdb;

		/*
		 * TODO
		 * 'Subscriptio' will be removed
		 * */
		$subscriptionOption = (class_exists('Subscriptio')) ? get_option('subscriptio_options', '') : get_option('rp_sub_settings', '');

		if((class_exists('Subscriptio') && !empty($subscriptionOption['subscriptio_multiproduct_subscription']) && $subscriptionOption['subscriptio_multiproduct_subscription']) || (!empty($subscriptionOption[1]) && $subscriptionOption[1]['multiple_product_checkout'] == 'multiple_subscriptions')) {
			self::$isMultiple = true;
		}

		self::$currentUser = get_current_user_id();
		self::$wpdbObj = $wpdb;
		self::createTable();
		add_action( 'init', array( $this, 'buildPlansMeta' ) );
		add_action( 'init', array( $this, 'disableExpiredListings' ) );
		add_filter('stm_user_restrictions', array( $this, 'user_restrictions' ), 10, 2);
	}

	private function createTable(  )
	{
		$charset_collate = self::$wpdbObj->get_charset_collate();
		$table_name = self::$wpdbObj->prefix . "multiple_plans_meta";

		if ( self::$wpdbObj->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

			$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            starttime TIMESTAMP,
            endtime TIMESTAMP,
            user_id INT NOT NULL,
            plan_id TEXT NOT NULL,
            listing_id INT NOT NULL,
            listing_status TEXT NOT NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	public function buildPlansMeta() {
		$defaultUserQuota = intval( get_theme_mod( 'user_post_limit', '3' ) );
		$defaultUserImgQuota = intval( get_theme_mod( 'user_post_images_limit', '5' ) );
		$dealer = stm_get_user_role( self::$currentUser );

		if ( $dealer ) {
			$defaultUserQuota = intval( get_theme_mod( 'dealer_post_limit', '50' ) );
			$defaultUserImgQuota = intval( get_theme_mod( 'dealer_post_images_limit', '10' ) );
		}

		$totalQuota = $defaultUserQuota;

		self::$plansMeta = array('plans' => array(
				array(
					'plan_id' => 'free',
					'label' => esc_html__('Free', 'motors'),
					'total_quota' => $defaultUserQuota,
					'used_quota' => self::getUsedQuotaByPlanId( 'free' ),
					'img_quota' => $defaultUserImgQuota
				)
			)
		);

		if(self::$currentUser && (class_exists('Subscriptio_User') || class_exists('RP_SUB'))) {

			/*
		     * TODO
		     * 'Subscriptio_User' will be removed
		     * */
			$user_subscriptions = (class_exists("Subscriptio_User")) ? Subscriptio_User::find_subscriptions( true, self::$currentUser ) : subscriptio_get_customer_subscriptions(self::$currentUser);

			if($user_subscriptions) {

				$statuses = array( 'active', 'trial' );

				foreach ( $user_subscriptions as $user_subscription) {

					if(!$user_subscription) continue;

					$status = (class_exists('Subscriptio_User')) ? $user_subscription->status : $user_subscription->get_status();

					if ( !in_array( $status, $statuses )) continue;

					if(class_exists('Subscriptio_User')) {
						$plan_name = ( !empty( $user_subscription->products_multiple ) ) ? $user_subscription->products_multiple[0]['product_name'] : $user_subscription->product_name;
						$subs_id = $user_subscription->id;
						$product_id = $user_subscription->product_id;

						if ( empty( $product_id ) and !empty( $user_subscription->products_multiple ) and is_array( $user_subscription->products_multiple ) ) {
							$products = $user_subscription->products_multiple;
							if ( !empty( $products[0] ) and !empty( $products[0]['product_id'] ) ) {
								$product_id = $products[0]['product_id'];
							}
						}

					} else {
						$initialOrder = $user_subscription->get_initial_order()->get_data();
						$key = key($initialOrder['line_items']);
						$orderData = $initialOrder['line_items'][$key]->get_data();

						$plan_name = $orderData['name'];
						$subs_id = $user_subscription->get_id();
						$product_id = $orderData['product_id'];
					}

					$post_limit = intval( get_post_meta( $product_id, 'stm_price_plan_quota', true ) );
					$image_limit = intval( get_post_meta( $product_id, 'stm_price_plan_media_quota', true ) );

					$totalQuota = $totalQuota + $post_limit;

					self::$plansMeta['plans'][] = array(
						'plan_id' => $subs_id,
						'label' => $plan_name,
						'total_quota' => $post_limit,
						'used_quota' => self::getUsedQuotaByPlanId($subs_id),
						'img_quota' => $image_limit
					);
				}
			}
		}

		self::$plansMeta['total_quota'] = $totalQuota;
	}

	public function disableExpiredListings () {
		if(self::$currentUser && class_exists('Subscriptio_User')) {

		}
	}

	private function getUsedQuotaByPlanId( $planId )
	{
		$result = self::$wpdbObj->get_results( "SELECT count(id) as usedQuota FROM " . self::$wpdbObj->prefix . "multiple_plans_meta WHERE `plan_id` = '{$planId}' AND `listing_status` = 'active' " );

		return $result[0]->usedQuota;
	}

	public static function getListingIdsByPlanId ($planId) {
		$result = self::$wpdbObj->get_results( "SELECT listing_id FROM " . self::$wpdbObj->prefix . "multiple_plans_meta WHERE `plan_id` = '{$planId}' AND `listing_status` != 'trash' " );

		return $result;
	}

	public static function getCurrentPlan($listingId) {
		$result = self::$wpdbObj->get_var( self::$wpdbObj->prepare( "SELECT plan_id FROM " . self::$wpdbObj->prefix . "multiple_plans_meta WHERE `user_id` = %d AND `listing_id` = %d", array( self::$currentUser, $listingId ) ) );

		if ( !empty( $result ) ) {
			return $result;
		}

		return null;
	}

	public static function deletePlanMeta( $ids )
	{
		$table_name = self::$wpdbObj->prefix . "multiple_plans_meta";
		foreach ( explode( ',', $ids ) as $item ) {
			self::$wpdbObj->delete( $table_name, array( 'id' => $item ) );
		}
	}

	public static function checkPlanMeta( $userId, $listingId )
	{
		$result = self::$wpdbObj->get_var( self::$wpdbObj->prepare( "SELECT id FROM " . self::$wpdbObj->prefix . "multiple_plans_meta WHERE `user_id` = %d AND `listing_id` = %d", array( $userId, $listingId ) ) );

		if ( !empty( $result ) ) {
			return $result;
		}

		return null;
	}

	public static function addPlanMeta ($planId, $listingId, $listingStatus) {
		$table_name = self::$wpdbObj->prefix . "multiple_plans_meta";

		self::$wpdbObj->insert(
			$table_name,
			array(
				'starttime' => strtotime( new DateTime() ),
				'endtime' => strtotime( new DateTime() ),
				'user_id' => self::$currentUser,
				'plan_id' => $planId,
				'listing_id' => $listingId,
				'listing_status' => $listingStatus
			),
			array( '%d', '%d', '%d', '%s', '%d', '%s' ) );
	}

	public static function updatePlanMeta ($planId, $listingId, $listingStatus) {
		$table_name = self::$wpdbObj->prefix . "multiple_plans_meta";

		if(self::checkPlanMeta(self::$currentUser, $listingId)) {
			self::$wpdbObj->update(
				$table_name,
				array(
					'plan_id' => $planId,
					'listing_status' => $listingStatus
				),
				array(
					'user_id' => self::$currentUser,
					'listing_id' => $listingId,
				),
				array( '%s', '%s' ),
				array( '%d', '%d' ) );
		} else {
			self::addPlanMeta($planId, $listingId, $listingStatus);
		}
	}

	public static function updateListingStatus ($listingId, $listingStatus) {
		$table_name = self::$wpdbObj->prefix . "multiple_plans_meta";

		if(self::checkPlanMeta(self::$currentUser, $listingId)) {
			self::$wpdbObj->update(
				$table_name,
				array(
					'listing_status' => $listingStatus
				),
				array(
					'user_id' => self::$currentUser,
					'listing_id' => $listingId,
				),
				array( '%s', '%s' ),
				array( '%d', '%d' ) );
		}
	}

	public function user_restrictions($restriction, $userId) {

		if(self::$isMultiple) {
			$restriction['posts_allowed'] = self::$plansMeta['total_quota'];
		}

		return $restriction;
	}

	public static function getUsedQuota( $planId )
	{
		$result = self::$wpdbObj->get_results( "SELECT count(id) as usedQuota FROM " . self::$wpdbObj->prefix . "multiple_plans_meta WHERE `plan_id` = '{$planId}' AND `listing_status` = 'active' " );

		return $result[0]->usedQuota;
	}

	public static function isMultiplePlans() {
		return self::$isMultiple;
	}

	public static function getPlans () {
		return self::$plansMeta;
	}
}

new MultiplePlan();