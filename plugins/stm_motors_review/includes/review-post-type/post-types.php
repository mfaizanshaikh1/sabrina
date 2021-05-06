<?php
add_action('init', 'stm_motors_review_init', 10, 1);

function stm_motors_review_init()
{

	$options = get_option('stm_post_types_options');


	$stm_review_options = wp_parse_args($options, array(
		'stm_review' => array(
			'title' => (!empty($options['stm_review']['title'])) ? $options['stm_review']['title'] : __('Car Review', 'stm_motors_review'),
			'plural_title' => (!empty($options['stm_review']['plural_title'])) ? $options['stm_review']['plural_title'] : __('Car Review', 'stm_motors_review'),
			'rewrite' => (!empty($options['stm_review']['rewrite'])) ? $options['stm_review']['rewrite'] : 'stm_review',
		),
	));

	register_post_type('stm_review', array(
		'labels' => array(
			'name' => $stm_review_options['stm_review']['plural_title'],
			'singular_name' => $stm_review_options['stm_review']['title'],
			'add_new' => __('Add New', 'stm_motors_review'),
			'add_new_item' => __('Add New Item', 'stm_motors_review'),
			'edit_item' => __('Edit Item', 'stm_motors_review'),
			'new_item' => __('New Item', 'stm_motors_review'),
			'all_items' => __('All Items', 'stm_motors_review'),
			'view_item' => __('View Item', 'stm_motors_review'),
			'search_items' => __('Search Items', 'stm_motors_review'),
			'not_found' => __('No items found', 'stm_motors_review'),
			'not_found_in_trash' => __('No items found in Trash', 'stm_motors_review'),
			'parent_item_colon' => '',
			'menu_name' => __($stm_review_options['stm_review']['plural_title'], 'stm_motors_review'),
		),
		'menu_icon' => 'dashicons-location-alt',
		'show_in_nav_menus' => true,
		'supports' => array('title', 'editor', 'post-formats', 'thumbnail', 'comments', 'excerpt', 'author'),
		'rewrite' => array('slug' => $stm_review_options['stm_review']['rewrite']),
		'has_archive' => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'taxonomies' => array('review_tag'),
	));

	if(isset($stm_review_options['stm_review']['sub_types'])) {

        foreach ($stm_review_options['stm_review']['sub_types'] as $args) {
            $sub_type = $args;
            $sub_labels = stm_motors_review_post_type_labels($sub_type['name'], $sub_type['plural']);

            $sub_args = array(
                'labels' => $sub_labels,
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=stm_review',
                'query_var' => false,
                'rewrite' => array('slug' => $sub_type['slug']),
                'capability_type' => 'post',
                'has_archive' => false,
                'hierarchical' => false,
                'supports' => $sub_type['supports']
            );

            register_post_type($sub_type['slug'], $sub_args);
        }
    }

	stm_review_register_taxonomies('review_category', 'Car Review Category', 'stm_review');

	stm_review_tag_taxonomies();
}

function stm_review_register_taxonomies ($slug, $taxonomyName, $post_type) {
	$pluralName = $taxonomyName . 's';

	$labels     = array(
		'name'              => _x( $taxonomyName, 'taxonomy general name', 'stm_theme' ),
		'singular_name'     => _x( $taxonomyName, 'taxonomy singular name', 'stm_theme' ),
		'search_items'      => __( 'Search ' . $pluralName, 'stm_theme' ),
		'all_items'         => __( 'All ' . $pluralName, 'stm_theme' ),
		'parent_item'       => __( 'Parent ' . $taxonomyName, 'stm_theme' ),
		'parent_item_colon' => __( 'Parent ' . $taxonomyName . ':', 'stm_theme' ),
		'edit_item'         => __( 'Edit ' . $taxonomyName, 'stm_theme' ),
		'update_item'       => __( 'Update ' . $taxonomyName, 'stm_theme' ),
		'add_new_item'      => __( 'Add New ' . $taxonomyName, 'stm_theme' ),
		'new_item_name'     => __( 'New ' . $taxonomyName . 'Name', 'stm_theme' ),
		'menu_name'         => __( $taxonomyName, 'stm_theme' )
	);

	$defaults = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_in_nav_menus' => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => $slug )
	);

	register_taxonomy( $slug, $post_type, $defaults );
}

function stm_review_tag_taxonomies()
{
	$labels = array(
		'name' => _x( 'Review Tags', 'taxonomy general name' ),
		'singular_name' => _x( 'Review Tag', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Tags' ),
		'popular_items' => __( 'Popular Tags' ),
		'all_items' => __( 'All Tags' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Tag' ),
		'update_item' => __( 'Update Tag' ),
		'add_new_item' => __( 'Add New Tag' ),
		'new_item_name' => __( 'New Tag Name' ),
		'separate_items_with_commas' => __( 'Separate tags with commas' ),
		'add_or_remove_items' => __( 'Add or remove tags' ),
		'choose_from_most_used' => __( 'Choose from the most used tags' ),
		'menu_name' => __( 'Tags' ),
	);

	register_taxonomy('review_tag','stm_review',array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'review_tag' ),
	));
}

function stm_motors_review_post_type_labels($name, $plural)
{
	$name = sanitize_text_field($name);
	$plural = sanitize_text_field($plural);
	$labels = array(
		'name'               => sprintf(__('%s', 'stm_motors_review'), $plural),
		'singular_name'      => sprintf(__('%s', 'stm_motors_review'), $name),
		'menu_name'          => sprintf(__('%s', 'stm_motors_review'), $plural),
		'name_admin_bar'     => sprintf(__('%s', 'stm_motors_review'), $name),
		'add_new'            => __('Add New', 'stm_motors_review'),
		'add_new_item'       => sprintf(__('Add new %s', 'stm_motors_review'), $name),
		'new_item'           => sprintf(__('New %s', 'stm_motors_review'), $name),
		'edit_item'          => sprintf(__('Edit %s', 'stm_motors_review'), $name),
		'view_item'          => sprintf(__('View %s', 'stm_motors_review'), $name),
		'all_items'          => sprintf(__('All %s', 'stm_motors_review'), $plural),
		'search_items'       => sprintf(__('Search %s', 'stm_motors_review'), $plural),
		'parent_item_colon'  => sprintf(__('Parent %s', 'stm_motors_review'), $plural),
		'not_found'          => sprintf(__('No %s found', 'stm_motors_review'), $plural),
		'not_found_in_trash' => sprintf(__('No %s found in Trash.', 'stm_motors_review'), $plural)
	);

	return apply_filters('stm_motors_review_post_type_labels', $labels);
}

