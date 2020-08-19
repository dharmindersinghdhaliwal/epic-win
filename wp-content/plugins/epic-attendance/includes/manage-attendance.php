<?php
class Epic_Attendance_Tracking{
	public static function init(){
		$class = __CLASS__;
        new $class;
    }
	
	public function __construct(){
		add_action( 'init', array($this,'manage_attendance'), 0 );
		add_action( 'init', array($this,'classes_category'), 0 );
	}

	// Register Custom Post Type
	function manage_attendance() {
		$labels = array(
		'name'                  => _x( 'Manage Attendances', 'Post Type General Name', 'cf' ),
		'singular_name'         => _x( 'Manage Attendance', 'Post Type Singular Name', 'cf' ),
		'menu_name'             => __( 'Classes', 'cf' ),
		'name_admin_bar'        => __( 'Post Type', 'cf' ),
		'archives'              => __( 'Item Archives', 'cf' ),
		'attributes'            => __( 'Item Attributes', 'cf' ),
		'parent_item_colon'     => __( 'Parent Item:', 'cf' ),
		'all_items'             => __( 'All Items', 'cf' ),
		'add_new_item'          => __( 'Add New Item', 'cf' ),
		'add_new'               => __( 'Add New', 'cf' ),
		'new_item'              => __( 'New Item', 'cf' ),
		'edit_item'             => __( 'Edit Item', 'cf' ),
		'update_item'           => __( 'Update Item', 'cf' ),
		'view_item'             => __( 'View Item', 'cf' ),
		'view_items'            => __( 'View Items', 'cf' ),
		'search_items'          => __( 'Search Item', 'cf' ),
		'not_found'             => __( 'Not found', 'cf' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'cf' ),
		'featured_image'        => __( 'Featured Image', 'cf' ),
		'set_featured_image'    => __( 'Set featured image', 'cf' ),
		'remove_featured_image' => __( 'Remove featured image', 'cf' ),
		'use_featured_image'    => __( 'Use as featured image', 'cf' ),
		'insert_into_item'      => __( 'Insert into item', 'cf' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'cf' ),
		'items_list'            => __( 'Items list', 'cf' ),
		'items_list_navigation' => __( 'Items list navigation', 'cf' ),
		'filter_items_list'     => __( 'Filter items list', 'cf' ),
		);
		$args = array(
			'label'                 => __( 'Manage Attendance', 'cf' ),
			'description'           => __( 'Post Type Description', 'cf' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', ),
			'taxonomies'            => array( 'mrd_classes_cats' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => plugins_url('/img/class.png',__FILE__),
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
		);
		register_post_type( 'mrd_classes', $args );
	}
	
	// Register Custom Taxonomy
	function classes_category() {
		$labels = array(
		'name'                       => _x( ' Categories', 'Taxonomy General Name', 'cf' ),
		'singular_name'              => _x( ' Category', 'Taxonomy Singular Name', 'cf' ),
		'menu_name'                  => __( ' Category', 'cf' ),
		'all_items'                  => __( 'All Items', 'cf' ),
		'parent_item'                => __( 'Parent Item', 'cf' ),
		'parent_item_colon'          => __( 'Parent Item:', 'cf' ),
		'new_item_name'              => __( 'New Item Name', 'cf' ),
		'add_new_item'               => __( 'Add New Item', 'cf' ),
		'edit_item'                  => __( 'Edit Item', 'cf' ),
		'update_item'                => __( 'Update Item', 'cf' ),
		'view_item'                  => __( 'View Item', 'cf' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'cf' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'cf' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'cf' ),
		'popular_items'              => __( 'Popular Items', 'cf' ),
		'search_items'               => __( 'Search Items', 'cf' ),
		'not_found'                  => __( 'Not Found', 'cf' ),
		'no_terms'                   => __( 'No items', 'cf' ),
		'items_list'                 => __( 'Items list', 'cf' ),
		'items_list_navigation'      => __( 'Items list navigation', 'cf' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);		
		register_taxonomy( 'mrd_classes_cats', array( 'mrd_classes' ), $args );
	}
}
add_action('plugins_loaded',array('Epic_Attendance_Tracking','init'));