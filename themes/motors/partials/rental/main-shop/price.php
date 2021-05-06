<?php
$id = stm_get_wpml_product_parent_id(get_the_ID());

$product = wc_get_product($id);

$product_type = 'default';

if(!empty($product)):
    if( $product->is_type( 'variable' ) ):
        $variations = $product->get_available_variations();
        $prices = array();

		$fields = stm_get_rental_order_fields_values();

        if(!empty($variations)) {
            $max_price = 0;
            $i = 0;
            foreach($variations as $variation) {

                if((!empty($variation['display_price']) || !empty($variation['display_regular_price'])) and !empty($variation['variation_description'])) {

                    $gets = array(
                        'add-to-cart' => $id,
                        'product_id' => $id,
                        'variation_id' => $variation['variation_id'],
                    );

                    foreach($variation['attributes'] as $key => $val) {
                        $gets[$key] = $val;
                    }

                    $url = add_query_arg($gets, get_permalink($id));

                    $total_price = false;
                    if(!empty($fields['order_days'])) {
                        $total_price = (!empty($variation['display_price'])) ? $variation['display_price'] : $variation['display_regular_price'];//$variation['display_price'] * $fields['order_days'];
                    }

                    if(!empty($total_price)) {
                        if($max_price < $total_price) {
                            $max_price = $total_price;
                        }
                    }

                    $prices[] = array(
                        'price' => stm_getDefaultVariablePrice($id, $i),
                        'text' => $variation['variation_description'],
                        'total' => $total_price,
                        'url' => $url,
                        'var_id' => $variation['variation_id']
                    );
                }

                $i++;
            }
        }

        if(!empty($prices)): ?>
            <div class="stm_rent_prices">
                <?php foreach($prices as $price): ?>
                    <div class="stm_rent_price">
                        <div class="total heading-font">
                            <?php
                                if(!empty($price['total'])) {
                                    echo sprintf( __('%s/Total', 'motors'), wc_price($price['total']) );
                                }
                            ?>
                        </div>
                        <div class="period">
                            <?php
                                $popupId = $price['var_id'] . '-' . $id;

                                if(!empty($price['price'])) {
                                    if(PricePerHour::hasPerHour() || PriceForDatePeriod::hasDatePeriod() || (class_exists('DiscountByDays') && DiscountByDays::hasDiscount($id, $fields['order_days'])) || (class_exists('PriceForQuantityDays') && PriceForQuantityDays::hasFixedPrice($id, $fields['order_days']))) {
                                        echo '<div class="stm-show-rent-promo-info" data-popup-id="stm-promo-popup-wrap-' . $popupId . '">';
										$orderHours = ( empty( $fields['order_hours'] ) ) ? 0 : $fields['order_hours'];
                                        echo sprintf( __( '%s Days / %s Hours', 'motors' ), $fields['order_days'], $orderHours );
										echo '</div>';
										stm_getPopupPromoPrice( $popupId, $id, $price['price'], $fields);
									} else {
										echo sprintf( __( '%s/Day', 'motors' ), wc_price( $price['price'] ) );
                                    }
                                }
                            ?>
                        </div>
                        <div class="pay">
                            <a class="heading-font" href="<?php echo esc_url($price['url']); ?>"><?php echo stm_dynamic_string_translation(wp_strip_all_tags($price['text']) . ' button title', wp_strip_all_tags($price['text'])); ?></a>
                        </div>
                        <?php if(!empty($max_price) and $price['total'] < $max_price ) : ?>
                            <div class="stm_discount"><?php echo sprintf( __('Saves you %s', 'motors'), wc_price($max_price - $price['total']) ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else:
        $prod = $product->get_data();
        $price = (empty($prod['sale_price'])) ? $prod['price'] : $prod['sale_price'];

        $fields = stm_get_rental_order_fields_values();

        $gets = array(
            'add-to-cart' => $id,
            'product_id' => $id
        );

        $total_price = false;
        if(!empty($fields['order_days'])) {
            $total_price = $product->get_price();
        }

        $url = add_query_arg($gets, get_permalink($id));
        if(!empty($price) and $url): ?>
            <div class="stm_rent_prices">
                <div class="stm_rent_price">
                    <div class="total heading-font">
                        <?php
                        if(!empty($total_price)) {
                            echo sprintf( __('%s/Total', 'motors'), wc_price($total_price) );
                        }
                        ?>
                    </div>
                    <div class="period">
                        <?php

						$popupId = $price . '-' . $id;

						if(!empty($price)) {
							if(PricePerHour::hasPerHour() || PriceForDatePeriod::hasDatePeriod() || (class_exists('DiscountByDays') && DiscountByDays::hasDiscount($id, $fields['order_days'])) || (class_exists('PriceForQuantityDays') && PriceForQuantityDays::hasFixedPrice($id, $fields['order_days']))) {
								echo '<div class="stm-show-rent-promo-info" data-popup-id="stm-promo-popup-wrap-' . $popupId . '">';
								$orderHours = ( empty( $fields['order_hours'] ) ) ? 0 : $fields['order_hours'];
								echo sprintf( __( '%s Days / %s Hours', 'motors' ), $fields['order_days'], $orderHours );
								echo '</div>';

								stm_getPopupPromoPrice( $popupId, $id, $price, $fields);
							} else {
								echo sprintf( __( '%s/Day', 'motors' ), wc_price( $price ) );
							}
						}
                        ?>
                    </div>
                    <div class="pay">
                        <a class="heading-font" href="<?php echo esc_url($url); ?>"><?php esc_html_e('Pay now', 'motors'); ?></a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>