<?php

class WPUPG_Eactivate {

    public function __construct()
    {
        register_activation_hook( WPUltimateEPostGrid::get()->pluginFile, array( $this, 'activate_plugin' ) );
    }

    public function activate_plugin()
    {
        WPUltimateEPostGrid::addon( 'custom-templates' )->default_templates( true ); // Reset default templates

        // Don't show the activation notice if the new user notice is displayed
        if( get_user_meta( get_current_user_id(), '_wpupg_hide_new_notice', true ) != '' ) {
            $this->activation_notice();
        }
    }

    public function activation_notice() {
        $notice  = '<strong>WP Exercise Ultimate Post Grid</strong><br/>';
        $notice .= '<a href="'.admin_url( 'edit.php?post_type=' . WPUPG_EPOST_TYPE. '&page=wpupg_efaq&sub=whats_new' ).'">Check out our latest changes on the <strong>Grids > FAQ</strong> page</a>';

        WPUltimateEPostGrid::get()->helper( 'notices' )->add_admin_notice( $notice );
    }
}