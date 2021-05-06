<?php

add_action( 'after_setup_theme', 'stm_motors_review_setup' );
function stm_motors_review_setup(){
	add_image_size( 'm-r-100-100', 100, 100, true );
	add_image_size( 'm-r-512-288', 512, 288, true );
	add_image_size( 'm-r-255-160', 255, 160, true );
	add_image_size( 'm-r-350-200', 350, 200, true );
	add_image_size( 'm-r-1110-580', 1110, 580, true );
	add_image_size( 'm-r-1015-575', 1015, 575, true );

	/*register_sidebar( array(
		'name'          => __( 'Event Sidebar', 'motors' ),
		'id'            => 'events_sidebar',
		'description'   => __( 'Event sidebar that appears on the right or left.', 'motors' ),
		'before_widget' => '<aside id="%1$s" class="widget widget-default %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="widget-title"><h4>',
		'after_title'   => '</h4></div>',
	) );*/

	/*register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'motors' ),
		'top_bar'   => __( 'Top bar menu', 'motors' ),
		'bottom_menu'   => __( 'Bottom menu', 'motors' ),
	) );

	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'motors' ),
		'id'            => 'default',
		'description'   => __( 'Main sidebar that appears on the right or left.', 'motors' ),
		'before_widget' => '<aside id="%1$s" class="widget widget-default %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="widget-title"><h4>',
		'after_title'   => '</h4></div>',
	) );*/

}