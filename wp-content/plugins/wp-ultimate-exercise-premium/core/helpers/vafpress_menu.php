<?php

class WPUEP_Vafpress_Menu {

    private $defaults;

    public function __construct()
    {
        add_action( 'after_setup_theme', array( $this, 'vafpress_menu_init' ), 11 );
        add_action( 'admin_init', array( $this, 'eassets' ) );

        $this->defaults = array(
            'import_exercises_generic_units' => implode( ';', array(
                __( 'clove', 'wp-ultimate-exercise' ),
                __( 'cloves', 'wp-ultimate-exercise' ),
                __( 'leave', 'wp-ultimate-exercise' ),
                __( 'leaves', 'wp-ultimate-exercise' ),
                __( 'slice', 'wp-ultimate-exercise' ),
                __( 'slices', 'wp-ultimate-exercise' ),
                __( 'piece', 'wp-ultimate-exercise' ),
                __( 'pieces', 'wp-ultimate-exercise' ),
                __( 'pinch', 'wp-ultimate-exercise' ),
                __( 'pinches', 'wp-ultimate-exercise' ),
            ) ),
        );
    }

    public function eassets()
    {
        WPUltimateExercise::get()->helper('eassets')->add(
            array(
                'file' => '/css/admin_settings.css',
                'admin' => true,
            )
        );
    }

    public function vafpress_menu_init()
    {
        $defaults = $this->defaults;

        require_once( WPUltimateExercise::get()->coreDir . '/helpers/vafpress/vafpress_menu_whitelist.php');
        require_once( WPUltimateExercise::get()->coreDir . '/helpers/vafpress/vafpress_menu_options.php');

        new VP_Option(array(
            'is_dev_mode'           => false,
            'option_key'            => 'wpuep_option',
            'page_slug'             => 'wpuep_admin',
            'template'              => $admin_menu,
            'menu_page'             => 'edit.php?post_type=exercise',
            'use_auto_group_naming' => true,
            'use_exim_menu'         => true,
            'minimum_role'          => 'manage_options',
            'layout'                => 'fluid',
            'page_title'            => __( 'Settings', 'wp-ultimate-exercise' ),
            'menu_label'            => __( 'Settings', 'wp-ultimate-exercise' ),
        ));
    }

    public function defaults( $option ) {
        return isset( $this->defaults[$option] ) ? $this->defaults[$option] : null;
    }
}