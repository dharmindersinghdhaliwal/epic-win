<?php

class WPUPG_Evafpress_Menu {

    public function __construct()
    {
        add_action( 'after_setup_theme', array( $this, 'vafpress_menu_init' ), 11 );
    }

    public function vafpress_menu_init()
    {
        require_once( WPUltimateEPostGrid::get()->coreDir . '/helpers/vafpress/vafpress_menu_whitelist.php');
        require_once( WPUltimateEPostGrid::get()->coreDir . '/helpers/vafpress/vafpress_menu_options.php');

        new VP_Option(array(
            'is_dev_mode'           => false,
            'option_key'            => 'wpupg_option',
            'page_slug'             => 'wpupg_eadmin',
            'template'              => $admin_menu,
            'menu_page'             => 'edit.php?post_type=' . WPUPG_EPOST_TYPE,
            'use_auto_group_naming' => true,
            'use_exim_menu'         => true,
            'minimum_role'          => 'manage_options',
            'layout'                => 'fluid',
            'page_title'            => __( 'Settings', 'wp-eultimate-post-grid' ),
            'menu_label'            => __( 'Settings', 'wp-eultimate-post-grid' ),
        ));
    }
}