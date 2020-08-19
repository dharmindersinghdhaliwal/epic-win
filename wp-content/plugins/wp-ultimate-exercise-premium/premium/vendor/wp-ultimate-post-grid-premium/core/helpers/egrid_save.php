<?php

class WPUPG_Egrid_Save {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
    }

    public function save( $post_id, $post )
    {		
        if( $post->post_type == WPUPG_EPOST_TYPE )
        {
            if ( !isset( $_POST['wpupg_nonce'] ) || !wp_verify_nonce( $_POST['wpupg_nonce'], 'grid' ) ) {
                return;
            }

            $egrid = new WPUPG_Egrid( $post_id );

            // Basic metadata
            $fields = $egrid->fields();

            foreach ( $fields as $field )
            {
                $old = get_post_meta( $post_id, $field, true );
                $new = isset( $_POST[$field] ) ? $_POST[$field] : null;

                // Field specific adjustments
                if( isset( $new ) && $field == 'wpupg_epost_types' ) {
                    $new = array( $new );
                }

                // Update or delete meta data if changed
                if( isset( $new ) && $new != $old ) {
                    update_post_meta( $post_id, $field, $new );
                } elseif ( $new == '' && $old ) {
                    delete_post_meta( $post_id, $field, $old );
                }
            }

            // Limit rules

            $limit_rules = array();

            if( isset( $_POST['wpupg_limit_posts_rule'] ) ) {
                foreach( $_POST['wpupg_limit_posts_rule'] as $id => $limit_rule ) {
                    if($id != 0) {
                        $field = $limit_rule['field'];
                        $type = $limit_rule['type'];

                        $field_parts = explode( '|', $field );
                        $values_name = 'values_' . $field_parts[0] . '_' . $field_parts[1];

                        if( isset( $limit_rule[$values_name] ) && $limit_rule[$values_name] ) {
                            $values = $limit_rule[$values_name];

                            if( !is_array( $values ) ) $values = explode( ';', $values );

                            $limit_rules[] = array(
                                'field' => $field,
                                'post_type' => $field_parts[0],
                                'taxonomy' => $field_parts[1],
                                'values' => $values,
                                'type' => $type,
                            );
                        }
                    }
                }
            }

            update_post_meta( $post_id, 'wpupg_limit_rules', $limit_rules );

            // Filter Taxonomies
            $post_type = $_POST['wpupg_epost_types'];

            if( isset( $_POST['wpupg_efilter_taxonomy_' . $post_type] ) ) {
                update_post_meta( $post_id, 'wpupg_efilter_taxonomies', $_POST['wpupg_efilter_taxonomy_' . $post_type] );
            }

            // Filter style metadata
            $styles = $egrid->filter_style_fields();
            $filter_style = array();

            foreach( $styles as $style => $fields ) {
                $filter_style[$style] = array();

                foreach( $fields as $field => $default ) {
                    $field_name  = 'wpupg_' . $style . '_filter_style_' . $field;
                    if( isset( $_POST[$field_name] ) ) {
                        $filter_style[$style][$field] = $_POST[$field_name];
                    }
                }
            }

            update_post_meta( $post_id, 'wpupg_efilter_style', $filter_style );

            // Pagination metadata
            $pagination_fields = $egrid->pagination_fields();
            $pagination = array();

            foreach( $pagination_fields as $type => $fields ) {
                $pagination[$type] = array();

                foreach( $fields as $field => $default ) {
                    $field_name  = 'wpupg_epagination_' . $type . '_' . $field;
                    if( isset( $_POST[$field_name] ) ) {
                        $pagination[$type][$field] = $_POST[$field_name];
                    }
                }
            }

            update_post_meta( $post_id, 'wpupg_epagination', $pagination );

            // Pagination style metadata
            $pagination_style_fields = $egrid->pagination_style_fields();
            $pagination_style = array();

            foreach( $pagination_style_fields as $field => $default ) {
                $field_name  = 'wpupg_epagination_style_' . $field;
                if( isset( $_POST[$field_name] ) ) {
                    $pagination_style[$field] = $_POST[$field_name];
                }
            }

            update_post_meta( $post_id, 'wpupg_epagination_style', $pagination_style );

            // Cache gets automatically generated in WPUPG_Egrid_Cache
        }
    }
}