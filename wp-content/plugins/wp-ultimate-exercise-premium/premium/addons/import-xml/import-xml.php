<?php

class WPUEP_Import_Xml extends WPUEP_Premium_Addon {

    public function __construct( $name = 'import-xml' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'import_menu' ) );
        add_action( 'admin_menu', array( $this, 'import_manual_menu' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/css/import_xml.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_import_xml',
            )
        );
    }

    public function import_menu() {
        add_submenu_page( null, __( 'Import XML', 'wp-ultimate-exercise' ), __( 'Import XML', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_xml', array( $this, 'import_page' ) );
    }

    public function import_page() {
        if ( !current_user_can('manage_options') ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        require( $this->addonDir. '/templates/before_importing.php' );
    }

    public function import_manual_menu() {
        add_submenu_page( null, __( 'Import XML', 'wp-ultimate-exercise' ), __( 'Import XML', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_xml_manual', array( $this, 'import_manual_page' ) );
    }

    public function import_manual_page() {
        if ( !wp_verify_nonce( $_POST['submitexercise'], 'exercise_submit' ) ) {
            die( 'Invalid nonce.' );
        }

        $exercises = simplexml_load_file( $_FILES['xml']['tmp_name'] );

        echo '<h2>Exercies Imported</h2>';

        $i = 1;
        foreach( $exercises as $exercise ) {
            $this->import_xml_exercise( $exercise, $i );
            $i++;
        }

        if( $i == 1 ) {
            echo 'No exercises found';
        }
    }

    public function import_xml_exercise( $xml_exercise, $exercise_number ) {
        $title = isset ( $xml_exercise->title ) ? (string) $xml_exercise->title : '';
        if( $title == '' ) {
            $title = __( 'Untitled', 'wp-ultimate-exercise' );
        }

        $post = array(
            'post_title' => $title,
            'post_type'	=> 'exercise',
            'post_status' => 'draft',
            'post_author' => get_current_user_id(),
        );

        $_POST['exercise_title']              = $title;
        $_POST['exercise_description']        = (string) $xml_exercise->description;
        $_POST['exercise_rating']             = isset( $xml_exercise->rating ) ? (string) $xml_exercise->rating->attributes()->stars : '';
        $_POST['exercise_servings']           = isset( $xml_exercise->servings ) ? (string) $xml_exercise->servings->attributes()->quantity : '';
        $_POST['exercise_servings_type']      = isset( $xml_exercise->servings ) ? (string) $xml_exercise->servings->attributes()->unit : '';
        $_POST['exercise_prep_time']          = isset( $xml_exercise->prepTime ) ? (string) $xml_exercise->prepTime->attributes()->quantity : '';
        $_POST['exercise_prep_time_text']     = isset( $xml_exercise->prepTime ) ? (string) $xml_exercise->prepTime->attributes()->unit : '';
        $_POST['exercise_cook_time']          = isset( $xml_exercise->cookTime ) ? (string) $xml_exercise->cookTime->attributes()->quantity : '';
        $_POST['exercise_cook_time_text']     = isset( $xml_exercise->cookTime ) ? (string) $xml_exercise->cookTime->attributes()->unit : '';
        $_POST['exercise_passive_time']       = isset( $xml_exercise->passiveTime ) ? (string) $xml_exercise->passiveTime->attributes()->quantity : '';
        $_POST['exercise_passive_time_text']  = isset( $xml_exercise->passiveTime ) ? (string) $xml_exercise->passiveTime->attributes()->unit : '';
        $_POST['exercise_notes']              = (string) $xml_exercise->notes;

        $ingredients = array();
        foreach( $xml_exercise->ingredients as $ingredient_group ) {
            $group = (string) $ingredient_group->attributes()->group;

            foreach( $ingredient_group->ingredient as $ingredient ) {
                $ingredients[] = array(
                    'ingredient' => (string) $ingredient->attributes()->name,
                    'amount' => (string) $ingredient->attributes()->quantity,
                    'unit' => (string) $ingredient->attributes()->unit,
                    'notes' => (string) $ingredient->attributes()->notes,
                    'group' => $group,
                );
            }
        }
        $_POST['exercise_ingredients'] = $ingredients;

        $instructions = array();
        foreach( $xml_exercise->instructions as $instruction_group ) {
            $group = (string) $instruction_group->attributes()->group;

            foreach( $instruction_group->instruction as $instruction ) {
                $instructions[] = array(
                    'description' => (string) $instruction,
                    'image' => '',
                    'group' => $group,
                );
            }
        }
        $_POST['exercise_instructions'] = $instructions;

        $post_id = wp_insert_post($post);

        // Exercise image
        $exercise_image_url = (string) $xml_exercise->imageUrl;

        if( $exercise_image_url ) {
            $exercise_image_id = $this->get_or_upload_attachment( $post_id, $exercise_image_url );

            if( $exercise_image_id ) {
                set_post_thumbnail( $post_id, $exercise_image_id );
            }
        }

        // Taxonomies
        foreach( $xml_exercise->taxonomy as $xml_taxonomy ) {
            $taxonomy = (string) $xml_taxonomy->attributes()->name;

            if( taxonomy_exists( $taxonomy ) ) {
                $terms = array();
                foreach( $xml_taxonomy->term as $xml_term ) {
                    $term_string = (string) $xml_term;

                    if( $taxonomy !== 'post_tag' ) {
                        $term = term_exists( $term_string, $taxonomy );

                        if ( $term === 0 || $term === null ) {
                            $term = wp_insert_term( $term_string, $taxonomy );
                        }

                        $term_id = intval( $term['term_id'] );

                        $terms[] = $term_id;
                    } else {
                        $terms[] = $term_string;
                    }
                }

                wp_set_post_terms( $post_id, $terms, $taxonomy );
            }
        }

        // Custom Fields
        $fields = array();
        $custom_fields_addon = WPUltimateExercise::addon( 'custom-fields' );
        if( $custom_fields_addon ) {
            $custom_fields = $custom_fields_addon->get_custom_fields();

            foreach( $custom_fields as $key => $custom_field ) {
                $fields[] = $key;
            }
        }

        foreach( $xml_exercise->customField as $custom_field ) {
            $key = (string) $custom_field->attributes()->key;

            if( in_array( $key, $fields ) ) {
                update_post_meta( $post_id, $key, (string) $custom_field->attributes()->value );
            }
        }

        //Nutrition
        $nutrition_addon = WPUltimateExercise::addon( 'nutritional-information' );
        if( $nutrition_addon && isset( $xml_exercise->nutrition ) ) {
            $nutrition_fields = $nutrition_addon->fields;

            $exercise_nutritional = array();

            foreach( $nutrition_fields as $field => $unit ) {
                if( isset( $xml_exercise->nutrition->attributes()->$field ) ) {
                    $exercise_nutritional[$field] = (string) $xml_exercise->nutrition->attributes()->$field;
                }
            }

            update_post_meta( $post_id, 'exercise_nutritional', $exercise_nutritional );
        }

        echo $exercise_number . '. <a href="' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . '">' . $title . '</a><br/>';
    }

    /**
     * Helper functions
     */
    private function get_or_upload_attachment( $post_id, $url ) {
        $image_id = $this->get_attachment_id_from_url( $url );

        if( $image_id ) {
            return $image_id;
        } else {
            $media = media_sideload_image( $url, $post_id );

            $attachments = get_posts( array(
                    'numberposts' => '1',
                    'post_parent' => $post_id,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                )
            );

            if( sizeof( $attachments ) > 0 ) {
                return $attachments[0]->ID;
            }
        }

        return null;
    }

    /*
     * Source: https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
     */
    private function get_attachment_id_from_url( $attachment_url = '' ) {

        global $wpdb;
        $attachment_id = false;

        // If there is no url, return.
        if ( '' == $attachment_url )
            return;

        // Get the upload directory paths
        $upload_dir_paths = wp_upload_dir();

        // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
        if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

            // If this is the URL of an auto-generated thumbnail, get the URL of the original image
            $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

            // Remove the upload path base directory from the attachment URL
            $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

            // Finally, run a custom database query to get the attachment ID from the modified attachment URL
            $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

        }

        return $attachment_id;
    }
}

WPUltimateExercise::loaded_addon( 'import-xml', new WPUEP_Import_Xml() );