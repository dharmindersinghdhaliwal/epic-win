<?php

class WPUPG_Emeta_Box {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'add_meta_box' ), 10 );
        add_action( 'admin_init', array( $this, 'add_meta_box_data_source' ), 12 );
        add_action( 'admin_init', array( $this, 'add_meta_box_filter' ), 14 );
        add_action( 'admin_init', array( $this, 'add_meta_box_grid' ), 16 );
        add_action( 'admin_init', array( $this, 'add_meta_box_pagination' ), 18 );
    }

    public function add_meta_box()
    {
        add_meta_box(
            'wpupg_meta_box_shortcode',
            __( 'Shortcode', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_shortcode' ),
            WPUPG_EPOST_TYPE,
            'side',
            'high'
        );

        if( !WPUltimateEPostGrid::is_premium_active() ) {
            add_meta_box(
                'wpupg_meta_box_premium_only',
                __( 'Premium Only', 'wp-eultimate-post-grid' ),
                array( $this, 'meta_box_premium_only' ),
                WPUPG_EPOST_TYPE,
                'side',
                'default'
            );
        }
    }

    public function add_meta_box_data_source()
    {
        add_meta_box(
            'wpupg_meta_box_data_source',
            __( 'Data Source', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_data_source' ),
            WPUPG_EPOST_TYPE,
            'normal',
            'high'
        );

        add_meta_box(
            'wpupg_meta_box_limit_posts',
            __( 'Limit Posts', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_limit_posts' ),
            WPUPG_EPOST_TYPE,
            'normal',
            'high'
        );
    }

    public function add_meta_box_filter()
    {
        add_meta_box(
            'wpupg_meta_box_filter',
            __( 'Filter', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_filter' ),
            WPUPG_EPOST_TYPE,
            'normal',
            'high'
        );

        add_meta_box(
            'wpupg_meta_box_isotope_filter',
            __( 'Isotope Filter', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_isotope_filter' ),
            WPUPG_EPOST_TYPE,
            'normal',
            'high'
        );
    }

    public function add_meta_box_grid()
    {
        add_meta_box(
            'wpupg_meta_box_grid',
            __( 'Egrid', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_grid' ),
            WPUPG_EPOST_TYPE,
            'normal',
            'high'
        );
    }

    public function add_meta_box_pagination()
    {
        add_meta_box(
            'wpupg_meta_box_pagination',
            __( 'Pagination', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_pagination' ),
            WPUPG_EPOST_TYPE,
            'normal',
            'high'
        );

        add_meta_box(
            'wpupg_meta_box_pagination_style',
            __( 'Pagination Style', 'wp-eultimate-post-grid' ),
            array( $this, 'meta_box_pagination_style' ),
            WPUPG_EPOST_TYPE,
            'normal',
            'high'
        );
    }

    public function meta_box_shortcode( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_shortcode.php' );
    }

    public function meta_box_premium_only( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_premium_only.php' );
    }

    public function meta_box_data_source( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_data_source.php' );
    }

    public function meta_box_limit_posts( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_limit_posts.php' );
    }

    public function meta_box_filter( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_filter.php' );
    }

    public function meta_box_isotope_filter( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_isotope_filter.php' );
    }

    public function meta_box_grid( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_grid.php' );
    }

    public function meta_box_pagination( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_pagination.php' );
    }

    public function meta_box_pagination_style( $post )
    {
        $egrid = new WPUPG_Egrid( $post );
        include( WPUltimateEPostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_pagination_style.php' );
    }
}