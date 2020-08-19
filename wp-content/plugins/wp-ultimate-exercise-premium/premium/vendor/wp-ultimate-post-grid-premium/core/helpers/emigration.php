<?php

class WPUPG_Emigration {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'migrate_if_needed' ) );
    }

    public function migrate_if_needed()
    {
        // Get current migrated to version
        $migrate_version = get_option( 'wpupg_migrate_version', false );

        if( !$migrate_version ) {
            $notices = false;
            $migrate_version = '0.0.1';
        } else {
            $notices = true;
        }

        $migrate_special = '';
        if( isset( $_GET['wpupg_migrate'] ) ) {
            $migrate_special = $_GET['wpupg_migrate'];
        }

        if( $migrate_version < '1.2' ) require_once( WPUltimateEPostGrid::get()->coreDir . '/helpers/migration/1_2_active_colors.php');

        // Each version update once
        if( $migrate_version < WPUPG_EVERSION ) {
            WPUltimateEPostGrid::addon( 'custom-templates' )->default_templates( true ); // Reset default templates

            // Update all grid caches
            $args = array(
                'post_type' => WPUPG_EPOST_TYPE,
                'post_status' => 'any',
                'posts_per_page' => -1,
                'nopaging' => true,
                'fields' => 'ids',
            );

            $query = new WP_Query( $args );
            $posts = $query->have_posts() ? $query->posts : array();
            $egrid_ids = array_map( 'intval', $posts );

            if( count( $egrid_ids ) > 0 ) {
                update_option( 'wpupg_regenerate_grids_check', $egrid_ids );
            }

            update_option( 'wpupg_migrate_version', WPUPG_EVERSION );
        }
    }
}