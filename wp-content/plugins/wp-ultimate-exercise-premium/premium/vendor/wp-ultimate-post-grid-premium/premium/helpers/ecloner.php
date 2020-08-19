<?php

class WPUPG_Ecloner {

    public function __construct()
    {
        add_action( 'init', array( $this, 'eassets' ) );
        add_action( 'wp_ajax_clone_grid', array( $this, 'ajax_clone_grid' ) );
    }

    public function eassets()
    {	
        WPUltimateEPostGrid::get()->helper( 'eassets' )->add(
            array(
                'file' => '/js/cloner.js',
                'premium' => true,
                'admin' => true,
                'page' => 'grid_posts',
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpupg_cloner',
                    'ajax_url' => WPUltimateEPostGrid::get()->helper('eajax')->url(),
                    'nonce' => wp_create_nonce( 'clone_grid' )
                )
            )
        );
    }

    public function ajax_clone_grid()
    {
        $egrid_id = intval( $_POST['grid'] );

        if( check_ajax_referer( 'clone_grid', 'security', false ) && WPUPG_EPOST_TYPE == get_post_type( $egrid_id ) )
        {
            $egrid = get_post( $egrid_id );

            $post = array(
                'post_title' => $egrid->post_title,
                'post_type'	=> WPUPG_EPOST_TYPE,
                'post_status' => 'draft',
                'post_author' => get_current_user_id(),
            );

            $clone_id = wp_insert_post( $post );

            $custom_fields = get_post_custom( $egrid_id );
            foreach ( $custom_fields as $key => $value ) {
                add_post_meta( $clone_id, $key, maybe_unserialize( $value[0] ) );
            }

            $url = admin_url( 'post.php?post=' . $clone_id . '&action=edit' );
            echo json_encode( array( 'redirect' => $url ) );
        }
        die();
    }
}