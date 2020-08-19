<?php

class WPUPG_Epagination {

    public function __construct()
    {
        add_action( 'wp_ajax_wpupg_get_page', array( $this, 'ajax_get_page' ) );
        add_action( 'wp_ajax_nopriv_wpupg_get_page', array( $this, 'ajax_get_page' ) );
    }

    public function ajax_get_page()
    {
        if( check_ajax_referer( 'wpupg_egrid', 'security', false ) )
        {
            $egrid = $_POST['grid'];
            $page = intval( $_POST['page'] );

            $post = get_page_by_path( $egrid, OBJECT, WPUPG_EPOST_TYPE );

            if( !is_null( $post ) ) {
                $egrid = new WPUPG_Egrid($post);

                echo $egrid->draw_posts( $page );
            }
        }

        die();
    }
}