<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

stm_motors_enqueue_scripts_styles( 'stm_mm_top_makes_tab' );

$query = new WP_Query(array(
	'post_type' => stm_listings_post_type(),
	'ignore_sticky_posts' => 1,
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'meta_query' => array(
		array(
			'key' => 'stm_car_views',
			'value' => '0',
			'compare' => '!=',
		)),
	'orderby' => 'meta_value',
	'order' => 'DESC'
));

$explMakes = explode(',', $top_makes);
$rand = rand(100, 100000);
?>
<div class="stm-mm-top-makes-wrap">
    <ul class="nav nav-tabs" id="mmTab<?php echo esc_attr($rand); ?>" role="tablist">
        <?php foreach ($explMakes as $k => $make): ?>

        <li class="nav-item <?php if($k == 0) echo 'active'?>">
            <a class="nav-link" id="<?php echo esc_attr($make . $rand); ?>-tab" data-toggle="tab" href="#<?php echo esc_attr($make . $rand); ?>" role="tab" aria-controls="<?php echo esc_attr($make . $rand); ?>" aria-selected="<?php echo (esc_attr($k) == 0) ? 'true' : 'false'; ?>"><?php echo ucfirst(str_replace('_', ' ', esc_html($make))); ?></a>
        </li>

        <?php endforeach;?>
    </ul>
    <div class="tab-content" id="mmTabContent<?php echo esc_attr($rand); ?>">
        <?php foreach ($explMakes as $k => $make): ?>
            <div class="tab-pane fade <?php if($k == 0) echo 'in active'?>" id="<?php echo esc_attr($make . $rand); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($make . $rand); ?>-tab">
                <div class="stm-mm-vehicles-wrap">
                <?php
                if($query->have_posts()) :
                    $q=0;

                    while($query->have_posts()):
                        $query->the_post();

                        if($q > 3 && $make == 'all_makes') break;
                        elseif($q > 3) break;

                        $makeOpt = get_post_meta( get_the_ID(), 'make', true );

						if($make == $makeOpt || $make == 'all_makes') {

							$img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'stm-img-380-240' );
							$price = get_post_meta( get_the_ID(), 'stm_genuine_price', true );
							?>
                            <div class="stm-mm-vehicle">
                                <div class="vehicle-img">
                                    <img src="<?php echo esc_url( $img[0] ) ?>" class="lazy img-responsive"
                                         alt="<?php echo get_the_title(); ?>"/>
                                    <div class="heading-font price"><?php echo stm_listing_price_view( $price ); ?></div>
                                </div>
                                <div class="title heading-font"><a href="<?php echo get_the_permalink(get_the_ID())?>"><?php the_title(); ?></a></div>
                            </div>
							<?php
						}
                    if($make == 'all_makes') $q++;
                    elseif($make == $makeOpt) $q++;

                    endwhile;
                endif;
                ?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>