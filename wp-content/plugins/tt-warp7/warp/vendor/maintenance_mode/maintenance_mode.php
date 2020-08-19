<?php
/*
* @package   Esta
* @encoding   UTF-8
* @version    2.0.1
* @author    Aleksandr Glovatskyy (aleksandr1278@gmail.com)
* @copyright  Copyright ( C ) 2015 torbar (http://torbara.com/). All rights reserved.
* @license  Copyrighted Commercial Software
* @support    support@torbara.com
*/


/*******************************************************************************
                     Maintenance mode / start
*******************************************************************************/

/*
 *  Activate WordPress Maintenance Mode
 */
    function ang_maintenance_mode(){
        global $warp;
        
        if(!isset($_POST['notify-mail'])){ // For notify widget in maintance mode
            
            if ( ! (stristr($_SERVER['REQUEST_URI'], '/wp-login') || stristr($_SERVER['REQUEST_URI'], '/wp-admin') || (stristr($_SERVER['REQUEST_URI'],'jquery-t-countdown-widget'))   ) ) {
            //if ( ! (stristr($_SERVER['REQUEST_URI'], '/wp-login') || stristr($_SERVER['REQUEST_URI'], '/wp-admin') ) ) {
                if( (!current_user_can('edit_themes') || !is_user_logged_in()) && ($warp['config']->get('maintenance_mode')=="0") ){
                    echo $warp['template']->render('offline');         
                    die();
                }
            }
        }
    }
    add_action('init', 'ang_maintenance_mode');

/*
 *  Register Custom Post Type
 */
    
if ( ! function_exists('custom_post_type_maintenance_mode') ) {
    
function custom_post_type_maintenance_mode() {

	$labels = array(
		'name'                  => _x( 'Maintenance Mode', 'Post Type General Name', 'esta' ),
		'singular_name'         => _x( 'Offline Page', 'Post Type Singular Name', 'esta' ),
		'menu_name'             => __( 'Maintenance Mode', 'esta' ),
		'name_admin_bar'        => __( 'Maintenance Mode', 'esta' ),
		'parent_item_colon'     => __( 'Parent Item:', 'esta' ),
		'all_items'             => __( 'All Offline Pages', 'esta' ),
		'add_new_item'          => __( 'Add New Offline Page', 'esta' ),
		'add_new'               => __( 'Add New', 'esta' ),
		'new_item'              => __( 'Offline Page', 'esta' ),
		'edit_item'             => __( 'Edit Offline Page', 'esta' ),
		'update_item'           => __( 'Update Offline Page', 'esta' ),
		'view_item'             => __( 'View Offline Page', 'esta' ),
		'search_items'          => __( 'Search Page', 'esta' ),
		'not_found'             => __( 'Page Not found', 'esta' ),
		'not_found_in_trash'    => __( 'Page Not found in Trash', 'esta' ),
		'items_list'            => __( 'Pages list', 'esta' ),
		'items_list_navigation' => __( 'Pages list navigation', 'esta' ),
		'filter_items_list'     => __( 'Filter Pages list', 'esta' ),
	);
	$args = array(
		'label'                 => __( 'Maintenance Mode', 'esta' ),
		'description'           => __( 'Maintenance mode and offline pages', 'esta' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 81,
                'menu_icon'             => 'dashicons-hammer',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
	);
	register_post_type( 'maintenance_mode', $args );

}
add_action( 'init', 'custom_post_type_maintenance_mode', 0 );
}

/*
 * add theme support post thumbnails in maintenance_mode listing for admin
 */
add_filter('manage_edit-maintenance_mode_columns', 'maintenance_mode_listing', 5);
function maintenance_mode_listing($default1) {
    $default1['post_thumbnails'] = 'Image';
    return $default1;
}
 
/*
 *  maintenance_mode admin listing image size
 */
add_action('manage_maintenance_mode_posts_custom_column', 'maintenance_mode_custom_columns', 5, 2);
function maintenance_mode_custom_columns($row_label, $id) {
    if ($row_label === 'post_thumbnails') :
        print the_post_thumbnail(array(100,100));
    endif;
}

/*******************************************************************************
                          Maintenance mode / end
*******************************************************************************/