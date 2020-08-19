<?php

class WPUPG_Etemplate_Editor extends WPUPG_Epremium_Addon {

    public function __construct( $name = 'template-editor' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'image_manager_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts_for_image_upload' ), -10 );

        // Filters
        add_filter( 'wpupg_eoutput_grid_template', array( $this, 'template_editor_preview' ) );
    }

    public function eassets() {

        if( isset( $_GET['wpupg_etemplate_editor_preview'] ) ) {
            WPUltimateEPostGrid::get()->helper( 'eassets' )->add(
                array(
                    'file' => $this->addonUrl . '/css/preview.css',
                    'direct' => true,
                    'public' => true,
                ),
                array(
                    'file' => $this->addonUrl . '/js/preview.js',
                    'direct' => true,
                    'public' => true,
                    'deps' => array(
                        'jquery',
                    ),
                    'data' => array(
                        'name' => 'wpupg_etemplate_preview',
                        'ajax_url' => WPUltimateEPostGrid::get()->helper('eajax')->url(),
                        'nonce' => wp_create_nonce( 'template_editor_preview' ),
                        'exercise' => intval($_GET['wpupg_etemplate_editor_preview']),
                    )
                )
            );

            $template = get_option( 'wpupg_custom_template_preview' );
            if( isset( $template->fonts ) && count( $template->fonts ) > 0 ) {

                WPUltimateEPostGrid::get()->helper( 'eassets' )->add(
                    array(
                        'type' => 'css',
                        'file' => 'https://fonts.googleapis.com/css?family=' . implode( '|', $template->fonts ),
                        'direct' => true,
                        'public' => true,
                    )
                );
            }
        }

        WPUltimateEPostGrid::get()->helper( 'eassets'  )->add(
            array(
                'file' => $this->addonPath . '/js/images.js',
                'premium' => true,
                'admin' => true,
                'page' => 'wpupg_egrid_page_wpupg_image_manager',
                'deps' => array(
                    'jquery',
                )
            )
        );
    }

    public function template_editor_preview( $template )
    {
        if( isset( $_GET['wpupg_etemplate_editor_preview'] ) && WPUPG_EPOST_TYPE == get_post_type( $_GET['wpupg_etemplate_editor_preview'] ) )
        {
            $template = get_option( 'wpupg_custom_template_preview' );
        }

        return $template;
    }

    public function editor_url()
    {
        return $this->addonUrl . '/templates/editor.php';
    }

    public function image_manager_menu()
    {
        add_submenu_page( null, 'WP Ultimate Post Egrid ' . __( 'Image Manager', 'wp-eultimate-post-grid' ), __( 'Image Manager', 'wp-eultimate-post-grid' ), 'manage_options', 'wpupg_image_manager', array( $this, 'image_manager_menu_page' ) );
    }

    public function image_manager_menu_page()
    {
        include( $this->addonDir . '/templates/images.php' );
    }

    public function scripts_for_image_upload()
    {
        $screen = get_current_screen();
        if( $screen->id == 'wpupg_egrid_page_wpupg_image_manager' && function_exists( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
    }
}

WPUltimateEPostGrid::loaded_addon( 'template-editor', new WPUPG_Etemplate_Editor() );