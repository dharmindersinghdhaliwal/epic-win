<?php

class WPUEP_Notices {

    public function __construct()
    {
        add_action( 'admin_init',       array( $this, 'wpuep_hide_notice' ) );
        add_action( 'admin_notices',    array( $this, 'wpuep_admin_notices' ) );
    }

    public function wpuep_admin_notices()
    {
        // Drip Form in settings page
        if( get_current_screen()->id == 'exercise_page_wpuep_admin' && get_user_meta( get_current_user_id(), '_wpuep_hide_notice', true ) == '' ) {
            include(WPUltimateExercise::get()->coreDir . '/static/drip_form.php');
        }

        // New to WP Ultimate Exercise
        if( current_user_can( 'edit_posts' ) && get_user_meta( get_current_user_id(), '_wpuep_hide_new_notice', true ) == '' ) {
            include(WPUltimateExercise::get()->coreDir . '/static/getting_started_notice.php');
        }

        if( $notices = get_option( 'wpuep_deferred_admin_notices' ) ) {
            $i = 0;
            foreach( $notices as $notice ) {
                echo '<div class="updated"><p>'.$notice.'</p></div>';
                $i++;

                if( $i > 3 ) {
                    echo '<div class="updated"><p>' . (count( $notices ) - 4) . ' ' .  __( 'other notices ignored', 'wp-ultimate-exercise' ) .'</p></div>';
                    break;
                }
            }

            delete_option('wpuep_deferred_admin_notices');
        }
    }

    public function add_admin_notice( $notice )
    {
        $notices = get_option( 'wpuep_deferred_admin_notices', array() );
        $notices[] = $notice;
        update_option( 'wpuep_deferred_admin_notices', $notices );
    }

    function wpuep_hide_notice()
    {
        if ( isset( $_GET['wpuep_hide_notice'] ) ) {
            check_admin_referer( 'wpuep_hide_notice', 'wpuep_hide_notice' );
            update_user_meta( get_current_user_id(), '_wpuep_hide_notice', get_option( WPUltimateExercise::get()->pluginName . '_version') );
        }

        if ( isset( $_GET['wpuep_hide_new_notice'] ) ) {
            check_admin_referer( 'wpuep_hide_new_notice', 'wpuep_hide_new_notice' );
            update_user_meta( get_current_user_id(), '_wpuep_hide_new_notice', get_option( WPUltimateExercise::get()->pluginName . '_version') );
        }
    }
}