<?php

class WPUEP_Import_Exercisecard extends WPUEP_Premium_Addon {

    public function __construct( $name = 'import-exercisecard' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'import_menu' ) );
        add_action( 'admin_menu', array( $this, 'import_manual_menu' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/css/import_exercisecard.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_import_exercisecard',
            )
        );
    }

    public function import_menu() {
        add_submenu_page( null, __( 'Import ExerciseCard', 'wp-ultimate-exercise' ), __( 'Import ExerciseCard', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_exercisecard', array( $this, 'import_page' ) );
    }

    public function import_page() {
        if ( !current_user_can('manage_options') ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        require( $this->addonDir. '/templates/before_importing.php' );
    }

    public function import_manual_menu() {
        add_submenu_page( null, __( 'Import ExerciseCard', 'wp-ultimate-exercise' ), __( 'Import ExerciseCard', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_exercisecard_manual', array( $this, 'import_manual_page' ) );
    }

    public function import_manual_page() {
        if ( !wp_verify_nonce( $_POST['import_exercisecard_manual'], 'import_exercisecard_manual' ) ) {
            die( 'Invalid nonce.' );
        }

        // Actually import exercise
        if( isset( $_POST['import_exercisecard_id'] ) && isset( $_POST['import_post_id'] )) {

            $post_id = intval( $_POST['import_post_id'] );
            $exercisecard_id = intval( $_POST['import_exercisecard_id'] );

            $this->import_exercisecard_exercise( $post_id, $exercisecard_id );
        }

        $this->custom_fields();
        require( $this->addonDir. '/templates/manual_import.php' );
    }

    private function import_exercisecard_exercise( $post_id, $exercisecard_id )
    {
        $exercisecard = $this->get_exercisecard_exercise( $exercisecard_id );

        // Exercise image
        $exercise_image_url = $exercisecard->image;

        if( $exercise_image_url ) {
            $exercise_image_id = $this->get_attachment_id_from_url( $exercise_image_url );

            if( $exercise_image_id ) {
                set_post_thumbnail( $post_id, $exercise_image_id );
            }
        }

        // Ingredient groups
        $ingredient_groups = array();
        foreach( $exercisecard->ingredients as $ingredient_group ) {
            foreach( $ingredient_group->lines as $ingredient_line ) {
                $ingredient_groups[] = $ingredient_group->title;
            }
        }

        // Ingredients
        $ingredients = $_POST['exercise_ingredients'];
        $new_ingredients = array();
        $ingredient_terms = array();

        if( $ingredients )
        {
            $i = 0;
            foreach( $ingredients as $ingredient )
            {
                if( trim( $ingredient['ingredient'] ) !== '' )
                {
                    $term = term_exists( $ingredient['ingredient'], 'ingredient' );

                    if ( $term === 0 || $term === null ) {
                        $term = wp_insert_term( $ingredient['ingredient'], 'ingredient' );
                    }

                    $ingredient['amount_normalized'] = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_amount( $ingredient['amount'] );

                    if( !is_wp_error( $term ) )
                    {
                        $term_id = intval( $term['term_id'] );

                        $ingredient['ingredient_id'] = $term_id;
                        $ingredient['group'] = $ingredient_groups[$i];
                        $i++;

                        $new_ingredients[] = $ingredient;
                        $ingredient_terms[] = $term_id;
                    }
                }
            }
            wp_set_post_terms( $post_id, $ingredient_terms, 'ingredient' );
        }
        update_post_meta( $post_id, 'exercise_ingredients', $new_ingredients );

        // Instructions
        if( isset( $exercisecard->directions ) && is_array( $exercisecard->directions ) ) {
            $instructions = array();
            foreach( $exercisecard->directions as $instruction_group ) {
                foreach( $instruction_group->lines as $instruction_line ) {
                    $instructions[] = array(
                        'description' => $instruction_line,
                        'group' => $instruction_group->title,
                        'image' => '',
                    );
                }
            }
            update_post_meta( $post_id, 'exercise_instructions', $instructions );
        }

        // Cooking Times
        $prep_time = intval( $exercisecard->prepTime );
        $cook_time = intval( $exercisecard->cookTime );
        $total_time = intval( $exercisecard->totalTime );

        if( $prep_time != 0 ) {
            update_post_meta( $post_id, 'exercise_prep_time', $prep_time );
            update_post_meta( $post_id, 'exercise_prep_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
        }

        if( $cook_time != 0 ) {
            update_post_meta( $post_id, 'exercise_cook_time', $cook_time );
            update_post_meta( $post_id, 'exercise_cook_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
        }

        if( $total_time != 0 ) {
            $passive_time = $total_time - ( $prep_time + $cook_time );

            if( $passive_time > 0 ) {
                update_post_meta( $post_id, 'exercise_passive_time', $passive_time );
                update_post_meta( $post_id, 'exercise_passive_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
            }
        }

        // Exercise Notes
        if( isset( $exercisecard->notes ) && is_array( $exercisecard->notes ) ) {
            $notes = '';

            foreach( $exercisecard->notes as $notes_group ) {
                if( $notes_group->title ) {
                    if( $notes ) {
                        $notes .= '<br/>';
                    }
                    $notes .= $notes_group->title . ':<br/>';
                }

                foreach( $notes_group->lines as $notes_line ) {
                    $notes .= $notes_line . '<br/>';
                }
            }
            update_post_meta( $post_id, 'exercise_notes', $notes );
        }


        // Servings
        update_post_meta( $post_id, 'exercise_servings', $exercisecard->servings );
        update_post_meta( $post_id, 'exercise_servings_type', '' );

        $normalized_servings = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_servings( $exercisecard->servings );
        update_post_meta( $post_id, 'exercise_servings_normalized', $normalized_servings );

        // Other metadata
        update_post_meta( $post_id, 'exercise_title', $exercisecard->title );
        update_post_meta( $post_id, 'exercise_description', $exercisecard->summary );

        update_post_meta( $post_id, 'exercisecard_author', $exercisecard->author );
        update_post_meta( $post_id, 'exercisecard_adapted', $exercisecard->adapted );
        update_post_meta( $post_id, 'exercisecard_adapted_link', $exercisecard->adaptedLink );
        update_post_meta( $post_id, 'exercisecard_yield', $exercisecard->yields );

        // Backup to remember which exercisecard exercise this originated from
        update_post_meta( $post_id, 'exercise_exercisecard_id', $exercisecard_id );


        // Switch post type to exercise
        set_post_type( $post_id, 'exercise' );

        // Add [exercise] shortcode instead of exercisecard one
        $post = get_post( $post_id );

        $update_content = array(
            'ID' => $post_id,
            'post_content' => preg_replace("/\[yumprint-exercise\s+id=\'(\d+)\'\s*]/i", "[exercise]", $post->post_content),
        );
        wp_update_post( $update_content );

        // Update exercise terms
        WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $post_id );
    }

    private function get_exercisecard_exercises()
    {
        $import_exercisecard = array(
            'total' => 0,
            'import' => array(

            ),
            'problem' => array(

            ),
        );

        // Loop through all posts
        $limit = 100;
        $offset = 0;
        $total = 0;

        while(true) {
            $args = array(
                'post_type' => array( 'post', 'page'),
                'post_status' => 'any',
                'orderby' => 'ID',
                'order' => WPUltimateExercise::option( 'import_exercises_order', 'ASC' ),
                'posts_per_page' => $limit,
                'offset' => $offset,
            );

            $query = new WP_Query( $args );

            if ( !$query->have_posts() ) break;

            $posts = $query->posts;

            foreach( $posts as $post ) {
                $exercises = $this->get_exercises_from_content( $post->post_content );

                if( count( $exercises ) == 1 && $post->post_type == 'post' ) {
                    $total++;
                    $import_exercisecard['import'][$post->ID] = $exercises[0];
                } else if( count( $exercises ) != 0 ) {
                    $import_exercisecard['problem'][$post->ID] = $exercises;
                }

                wp_cache_delete( $post->ID, 'posts' );
                wp_cache_delete( $post->ID, 'post_meta' );
            }

            $offset += $limit;
            wp_cache_flush();
        }

        $import_exercisecard['total'] = $total;

        return $import_exercisecard;
    }

    private function get_exercisecard_exercise( $exerciseId )
    {
        global $wpdb;

        $exercise_table_name = $wpdb->prefix . "yumprint_exercise_exercise";
        $exercise_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $exercise_table_name WHERE id=%d", intval( $exerciseId ) ) );
        if( empty( $exercise_row ) ) {
            return false;
        }

        $exercise = json_decode( $exercise_row->exercise );

        return $exercise;
    }

    private function custom_fields()
    {
        $fields = array(
            'exercisecard_author' => __( 'Author', 'wp-ultimate-exercise' ),
            'exercisecard_adapted' => __( 'Adapted', 'wp-ultimate-exercise' ),
            'exercisecard_adapted_link' => __( 'Adapted Link', 'wp-ultimate-exercise' ),
            'exercisecard_yield' => __( 'Yield', 'wp-ultimate-exercise' ),
        );

        $custom_fields = WPUltimateExercise::addon( 'custom-fields' )->get_custom_fields();

        foreach( $fields as $key => $name ) {
            if ( !array_key_exists( $key, $custom_fields ) ) {
                $custom_fields[$key] = array(
                    'key' => $key,
                    'name' => $name,
                );
            }
        }

        WPUltimateExercise::addon( 'custom-fields' )->update_custom_fields( $custom_fields );
    }

    /*
     * Source: https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
     */
    function get_attachment_id_from_url( $attachment_url = '' ) {

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

    /*
     * Helper functions
     */

    private function get_exercises_from_content( $content )
    {
        preg_match_all("/\[yumprint-exercise\s+id=\'(\d+)\'\s*]/i", $content, $output);

        return array_unique( $output[1] );
    }
}

WPUltimateExercise::loaded_addon( 'import-exercisecard', new WPUEP_Import_Exercisecard() );