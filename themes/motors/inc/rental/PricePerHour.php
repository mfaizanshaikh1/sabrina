<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 6/12/2018
 * Time: 5:52 PM
 */

class PricePerHour
{
	const META_KEY_INFO = 'rental_price_per_hour_info';
	private static $varId = 0;
	private static $days, $hours;

	public function __construct()
	{
		do_action( 'stm_rental_meta_box' );
		add_action( 'save_post', array( get_class(), 'add_price_per_day_post_meta' ), 10, 2 );
		add_filter( 'woocommerce_product_type_query', array( get_class(), 'setVarId' ), 10, 2 );
		add_filter( 'woocommerce_product_get_price', array( get_class(), 'setVarPrice' ), 30, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( get_class(), 'setVarPrice' ), 30, 2 );
		add_filter( 'stm_rental_date_values', array( get_class(), 'updateDaysAndHour' ), 10, 1 );
		add_filter( 'stm_cart_items_content', array( get_class(), 'updateCart' ), 40, 1 );
	}

	public static function addPricePerHourMetaBox()
	{

	}

	public static function hasPerHour () {
		$pricePerHour = self::getPricePerHour( self::$varId );
		return (!empty($pricePerHour)) ? true : false;
    }

	public static function add_price_per_day_post_meta( $post_id, $post )
	{
		if ( isset( $_POST['price-per-hour'] ) && !empty( $_POST['price-per-hour'] ) ) {
			update_post_meta( $post->ID, self::META_KEY_INFO, $_POST['price-per-hour'] );
		} else {
			delete_post_meta( $post->ID, self::META_KEY_INFO );
		}
	}

	public static function setVarId( $bool, $productId ) {
		if ( 'product' === get_post_type( $productId ) ) {
			$terms        = get_the_terms( $productId, 'product_type' );

			if($terms && ($terms[0]->slug == 'simple' || $terms[0]->slug == 'variable')) {
				self::$varId = stm_get_wpml_product_parent_id( $productId );
			}
		}
    }

	public static function setVarPrice( $price, $product ) {

		if ( $product->get_type() == 'car_option' ) return $price;

		if ( !empty( $product->get_data() ) ) {

			$pId = $product->get_type() == 'variation' ? $product->get_parent_id() : $product->get_id();

			self::$varId = stm_get_wpml_product_parent_id( $pId );

			$orderCookieData = stm_get_rental_order_fields_values();
			$pricePerHour = self::getPricePerHour( self::$varId );

			if ( isset( $orderCookieData['order_hours'] ) && $orderCookieData['order_hours'] != 0 && !empty( $pricePerHour ) ) {
			    $price = (isset($orderCookieData['ceil_days']) && !empty($orderCookieData['ceil_days'])) ? $price : 0;
				$price = $price + ( $orderCookieData['order_hours'] * $pricePerHour );
			}
		}

		return $price;
	}

	public static function getPricePerHour( $varId )
	{
		return get_post_meta( $varId, self::META_KEY_INFO, true );
	}

	public static function updateDaysAndHour( $data )
	{
		$pickupDate = $data['calc_pickup_date'];
		$returnDate = $data['calc_return_date'];
		if ( $pickupDate != '--' && $returnDate != '--' ) {

			$date1 = new DateTime( $pickupDate );
			$date2 = new DateTime( $returnDate );

			$diff = $date2->diff( $date1 )->format( "%a.%h" );
			$diff = explode( ".", $diff );

			$data['order_days'] = $diff[0];

			$pricePerHour = self::getPricePerHour( self::$varId );

			if ( isset( $diff[1] ) && $diff[1] != 0 && empty( $pricePerHour ) ) {
				$data['order_days'] = $diff[0] + 1;
				$data['ceil_days'] = $diff[0] + 1;
			}

			if ( !empty( self::getPricePerHour( self::$varId ) ) ) {
				$data['order_hours'] = $diff[1];
			}
		}

		return $data;
	}

	public static function updateCart( $cartItems )
	{
		if ( isset( $cartItems['car_class']['total'] ) && isset( $cartItems['car_class']['id'] ) ) {
			$orderCookieData = stm_get_rental_order_fields_values();

			$pId = $cartItems['car_class']['id'];
			$pricePerHour = self::getPricePerHour( $pId );

			if ( isset( $orderCookieData['order_hours'] ) && $orderCookieData['order_hours'] != 0 && !empty( $pricePerHour ) ) {
			    $cartItems['car_class']['total'] = ($cartItems['car_class']['days'] !== '0') ? $cartItems['car_class']['total'] + ( $orderCookieData['order_hours'] * $pricePerHour ) : ( $orderCookieData['order_hours'] * $pricePerHour );
				$cartItems['car_class']['hours'] = $orderCookieData['order_hours'];

				if($orderCookieData['order_days'] == 0) {
					$cartItems['total'] = wc_price($cartItems['car_class']['total']);
                }
			}
		}

		return $cartItems;
	}

	public static function pricePerHourView()
	{
		$price = get_post_meta( stm_get_wpml_product_parent_id( get_the_ID() ), self::META_KEY_INFO, true );

		$disabled = ( get_the_ID() != stm_get_wpml_product_parent_id( get_the_ID() ) ) ? 'disabled="disabled"' : '';

		?>
        <div class="admin-rent-info-wrap">
            <ul class="stm-rent-nav-tabs">
                <li>
                    <a class="stm-nav-link active"
                       data-id="price-per-hour"><?php echo esc_html__( 'Price Per Hour', 'motors' ); ?></a>
                </li>
                <li>
                    <a class="stm-nav-link" data-id="discount-by-days"><?php echo (get_theme_mod('enable_fixed_price_for_days', false)) ? esc_html__( 'Fixed Price By Quantity Days', 'motors' ) : esc_html__( 'Discount By Days', 'motors' ); ?></a>
                </li>
                <li>
                    <a class="stm-nav-link"
                       data-id="price-date-period"><?php echo esc_html__( 'Price For Date Peiod', 'motors' ); ?></a>
                </li>
            </ul>
            <div class="stm-tabs-content">
                <div class="tab-pane show active" id="price-per-hour">
                    <div class="price-per-hour-wrap">
                        <div class="price-per-hour-input">
							<?php echo esc_html__( 'Price', 'motors' ); ?> <input type="text" name="price-per-hour"
                                                                                  value="<?php echo esc_attr( $price ); ?>" <?php echo esc_attr( $disabled ); ?> />
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="discount-by-days">
					<?php
                    if(get_theme_mod('enable_fixed_price_for_days', false)) {
                        do_action( 'stm_fixed_price_for_days' );
                    } else {
                        do_action( 'stm_disc_by_days' );
					}
                    ?>
                </div>
                <div class="tab-pane" id="price-date-period">
					<?php do_action( 'stm_date_period' ); ?>
                </div>
            </div>
        </div>
		<?php
	}
}

new PricePerHour();