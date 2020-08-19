<?php

class WPUPG_Efaq {

    public function __construct()
    {
        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'faq_menu' ), 20 );
    }

    public function eassets()
    {
        WPUltimateEPostGrid::get()->helper( 'eassets' )->add(
            array(
                'file' => '/css/faq.css',
                'admin' => true,
                'page' => WPUPG_EPOST_TYPE . '_page_wpupg_efaq',
            )
        );
    }

    public function faq_menu() {
        add_submenu_page( 'edit.php?post_type=' . WPUPG_EPOST_TYPE, 'WP Ultimate Post Egrid ' . __( 'FAQ', 'wp-eultimate-post-grid' ), __( 'FAQ', 'wp-eultimate-post-grid' ), 'edit_posts', 'wpupg_efaq', array( $this, 'faq_page' ) );
    }

    public function faq_page() {
        if ( !current_user_can( 'edit_posts' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        // Hide the new user notice
        update_user_meta( get_current_user_id(), '_wpupg_hide_new_notice', get_option( WPUltimateEPostGrid::get()->pluginName . '_version') );
        include( WPUltimateEPostGrid::get()->coreDir . '/static/faq.php' );
    }
}