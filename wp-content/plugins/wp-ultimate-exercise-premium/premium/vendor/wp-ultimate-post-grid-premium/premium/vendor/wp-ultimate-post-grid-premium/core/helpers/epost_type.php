<?php

class WPUPG_Epost_Type {

    public function __construct()
    {
        add_action( 'init', array( $this, 'register_post_type' ), 1);
    }

    public function register_post_type()
    {
        $name = __( 'Egrids', 'wp-eultimate-post-grid' );
        $singular = __( 'Egrid', 'wp-eultimate-post-grid' );
        
        $args = apply_filters( 'wpupg_register_post_type',
            array(
                'labels' => array(
                    'name' => $name,
                    'singular_name' => $singular,
                    'add_new' => __( 'Add New', 'wp-eultimate-post-grid' ),
                    'add_new_item' => __( 'Add New', 'wp-eultimate-post-grid' ) . ' ' . $singular,
                    'edit' => __( 'Edit', 'wp-eultimate-post-grid' ),
                    'edit_item' => __( 'Edit', 'wp-eultimate-post-grid' ) . ' ' . $singular,
                    'new_item' => __( 'New', 'wp-eultimate-post-grid' ) . ' ' . $singular,
                    'view' => __( 'View', 'wp-eultimate-post-grid' ),
                    'view_item' => __( 'View', 'wp-eultimate-post-grid' ) . ' ' . $singular,
                    'search_items' => __( 'Search', 'wp-eultimate-post-grid' ) . ' ' . $name,
                    'not_found' => __( 'No', 'wp-eultimate-post-grid' ) . ' ' . $name . ' ' . __( 'found.', 'wp-eultimate-post-grid' ),
                    'not_found_in_trash' => __( 'No', 'wp-eultimate-post-grid' ) . ' ' . $name . ' ' . __( 'found in trash.', 'wp-eultimate-post-grid' ),
                    'parent' => __( 'Parent', 'wp-eultimate-post-grid' ) . ' ' . $singular,
                ),
                'public' => true,
                'exclude_from_search' => true,
                'show_ui' => true,
                'menu_position' => 20,
                'supports' => array( 'title' ),
                'taxonomies' => array(),
                'menu_icon' => 'dashicons-grid-view', //WPUltimateEPostEgrid::get()->coreUrl . '/img/icon_16.png',
                'has_archive' => false,
                'rewrite' => false,
            )
        );

        register_post_type( WPUPG_EPOST_TYPE, $args );
    }
}