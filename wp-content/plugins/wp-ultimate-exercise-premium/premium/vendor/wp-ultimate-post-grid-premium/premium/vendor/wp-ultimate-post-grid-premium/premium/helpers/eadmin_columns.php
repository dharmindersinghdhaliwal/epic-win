<?php

class WPUPG_Eadmin_Columns {

    public function __construct()
    {
        add_filter( 'manage_edit-' . WPUPG_EPOST_TYPE . '_columns', array( $this, 'columns') );
        add_filter( 'manage_' . WPUPG_EPOST_TYPE . '_posts_custom_column' , array( $this, 'columns_content'), 10, 2 );
    }

    public function columns( $columns ) {
        $columns['grid_actions'] = __( 'Actions', 'wp-eultimate-post-grid' );

        return $columns;
    }

    public function columns_content( $column, $post_ID ) {
        switch( $column ) {
            case 'grid_actions':
                echo '<a href="#" class="clone-grid" data-grid="' . $post_ID . '">' . __( 'Clone Egrid', 'wp-eultimate-post-grid' ) . '</a>';
                break;
        }
    }
}