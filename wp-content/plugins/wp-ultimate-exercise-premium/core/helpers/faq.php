<?php

class WPUEP_Faq {

    public function __construct()
    {
        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'faq_menu' ), 20 );
    }

    public function eassets()
    {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => '/css/faq.css',
                'admin' => true,
                'page' => 'exercise_page_wpuep_faq',
            )
        );
    }

    public function faq_menu() {
        add_submenu_page( 'edit.php?post_type=exercise', 'WP Ultimate Exercise ' . __( 'FAQ', 'wp-ultimate-exercise' ), __( 'FAQ', 'wp-ultimate-exercise' ), 'edit_posts', 'wpuep_faq', array( $this, 'faq_page' ) );
    }

    public function faq_page() {
        if ( !current_user_can( 'edit_posts' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        // Hide the new user notice
        update_user_meta( get_current_user_id(), '_wpuep_hide_new_notice', get_option( WPUltimateExercise::get()->pluginName . '_version') );
        include( WPUltimateExercise::get()->coreDir . '/static/faq.php' );
    }
}