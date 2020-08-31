<?php
add_action( 'init', 'register_epic_manage_tracking');
add_action( 'init', 'register_epic_epctrack_levels');

# Register Custom Taxonomy For Tracking Level
function register_epic_manage_tracking(){
  $labels = array(
    'name'                  => _x( 'Epic Trackings', 'Post Type General Name', 'epic' ),
    'singular_name'         => _x( 'EpicTracking', 'Post Type Singular Name', 'epic' ),
    'menu_name'             => __( 'Epic Tracking', 'epic' ),
    'name_admin_bar'        => __( 'Epic Tracking', 'epic' ),
    'archives'              => __( 'Item Archives', 'epic' ),
    'parent_item_colon'     => __( 'Parent Item:', 'epic' ),
    'all_items'             => __( 'All Items', 'epic' ),
    'add_new_item'          => __( 'Add New Item', 'epic' ),
    'add_new'               => __( 'Add New', 'epic' ),
    'new_item'              => __( 'New Item', 'epic' ),
    'edit_item'             => __( 'Edit Item', 'epic' ),
    'update_item'           => __( 'Update Item', 'epic' ),
    'view_item'             => __( 'View Item', 'epic' ),
    'search_items'          => __( 'Search Item', 'epic' ),
    'not_found'             => __( 'Not found', 'epic' ),
    'not_found_in_trash'    => __( 'Not found in Trash', 'epic' ),
    'featured_image'        => __( 'Featured Image', 'epic' ),
    'set_featured_image'    => __( 'Set featured image', 'epic' ),
    'remove_featured_image' => __( 'Remove featured image', 'epic' ),
    'use_featured_image'    => __( 'Use as featured image', 'epic' ),
    'insert_into_item'      => __( 'Insert into item', 'epic' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'epic' ),
    'items_list'            => __( 'Items list', 'epic' ),
    'items_list_navigation' => __( 'Items list navigation', 'epic' ),
    'filter_items_list'     => __( 'Filter items list', 'epic' ),
  );
  $args = array(
    'label'                 => __( 'EpicTracking', 'epic' ),
    'description'           => __( 'Epic Tracking', 'epic' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'excerpt', 'custom-fields', ),
    'taxonomies'            => array( 'epctrack' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => plugins_url('/img/i.png',__FILE__),
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => true,
    'publicly_queryable'    => true,
    'capability_type'       => 'post',
  );
  register_post_type('epic_tracking',$args );
}

function register_epic_epctrack_levels(){
		$labels = array(
			'name'                       => _x( 'Tracking Levels', 'Taxonomy General Name', 'epic' ),
			'singular_name'              => _x( 'Tracking Level', 'Taxonomy Singular Name', 'epic' ),
			'menu_name'                  => __( 'Tracking Level', 'epic' ),
			'all_items'                  => __( 'All Items', 'epic' ),
			'parent_item'                => __( 'Parent Item', 'epic' ),
			'parent_item_colon'          => __( 'Parent Item:', 'epic' ),
			'new_item_name'              => __( 'New Item Name', 'epic' ),
			'add_new_item'               => __( 'Add New Item', 'epic' ),
			'edit_item'                  => __( 'Edit Item', 'epic' ),
			'update_item'                => __( 'Update Item', 'epic' ),
			'view_item'                  => __( 'View Item', 'epic' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'epic' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'epic' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'epic' ),
			'popular_items'              => __( 'Popular Items', 'epic' ),
			'search_items'               => __( 'Search Items', 'epic' ),
			'not_found'                  => __( 'Not Found', 'epic' ),
			'no_terms'                   => __( 'No items', 'epic' ),
			'items_list'                 => __( 'Items list', 'epic' ),
			'items_list_navigation'      => __( 'Items list navigation', 'epic' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => true,
		);
		register_taxonomy('epctrack', array('epic_tracking'),$args);
	}
