<?php

class WPUEP_Activate {

    public function __construct()
    {
        register_activation_hook( WPUltimateExercise::get()->pluginFile, array( $this, 'activate_plugin' ) );
    }

    public function activate_plugin()
    {	
        WPUltimateExercise::get()->helper( 'exercise_post_type' )->register_exercise_post_type();
        WPUltimateExercise::get()->helper( 'taxonomies' )->check_exercise_taxonomies();

        WPUltimateExercise::get()->helper( 'permalinks_flusher' )->set_flush_needed();
        WPUltimateExercise::addon( 'custom-templates' )->default_templates( true ); // Reset default templates

        // Don't show the activation notice if the new user notice is displayed
        if( get_user_meta( get_current_user_id(), '_wpuep_hide_new_notice', true ) != '' ) {
            $this->activation_notice();
        }
    }

    public function activation_notice() {
        $notice  = '<strong>WP Ultimate Exercise</strong><br/>';
        $notice .= '<a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_faq&sub=whats_new' ).'">Check out our latest changes on the <strong>Exercies > FAQ</strong> page</a>';

        WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( $notice );
    }
}