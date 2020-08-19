<?php

class WPUEP_Template_Editor extends WPUEP_Premium_Addon {

    public function __construct( $name = 'template-editor' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'image_manager_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts_for_image_upload' ), -10 );

        // Filters
        add_filter( 'wpuep_output_exercise', array( $this, 'template_editor_preview' ) );
    }

    public function eassets() {

        if( isset( $_GET['wpuep_template_editor_preview'] ) ) {
            WPUltimateExercise::get()->helper( 'eassets' )->add(
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
                        'name' => 'wpuep_template_preview',
                        'ajax_url' => WPUltimateExercise::get()->helper('eajax')->url(),
                        'nonce' => wp_create_nonce( 'template_editor_preview' ),
                        'exercise' => intval($_GET['wpuep_template_editor_preview']),
                    )
                )
            );

            $template = get_option( 'wpuep_custom_template_preview' );
            if( isset( $template->fonts ) && count( $template->fonts ) > 0 ) {

                WPUltimateExercise::get()->helper( 'eassets' )->add(
                    array(
                        'type' => 'css',
                        'file' => 'https://fonts.googleapis.com/css?family=' . implode( '|', $template->fonts ),
                        'direct' => true,
                        'public' => true,
                    )
                );
            }
        }

        WPUltimateExercise::get()->helper( 'eassets'  )->add(
            array(
                'file' => $this->addonPath . '/js/images.js',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_image_manager',
                'deps' => array(
                    'jquery',
                )
            )
        );
    }

    public function template_editor_preview( $output )
    {
        if( isset( $_GET['wpuep_template_editor_preview'] ) && 'exercise' == get_post_type( $_GET['wpuep_template_editor_preview'] ) )
        {
            $template = get_option( 'wpuep_custom_template_preview' );
            $output = $template->output_string( new WPUEP_Exercise( $_GET['wpuep_template_editor_preview'] ) );
        }

        return $output;
    }

    public function editor_url()
    {
        return $this->addonUrl . '/templates/editor.php';
    }

    public function image_manager_menu()
    {
        add_submenu_page( null, 'WP Ultimate Exercise ' . __( 'Image Manager', 'wp-ultimate-exercise' ), __( 'Image Manager', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_image_manager', array( $this, 'image_manager_menu_page' ) );
    }

    public function image_manager_menu_page()
    {
        include( $this->addonDir . '/templates/images.php' );
    }

    public function scripts_for_image_upload()
    {
        $screen = get_current_screen();
        if( $screen->id == 'exercise_page_wpuep_image_manager' && function_exists( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
    }
}

WPUltimateExercise::loaded_addon( 'template-editor', new WPUEP_Template_Editor() );