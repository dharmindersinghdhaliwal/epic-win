<?php

class WPUEP_Import_Ziplist extends WPUEP_Premium_Addon {

    public function __construct( $name = 'import-ziplist' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'import_menu' ) );
        add_action( 'admin_menu', array( $this, 'import_manual_menu' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/css/import_ziplist.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_import_ziplist',
            )
        );
    }

    public function import_menu() {
        add_submenu_page( null, __( 'Import Ziplist', 'wp-ultimate-exercise' ), __( 'Import Ziplist', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_ziplist', array( $this, 'import_page' ) );
    }

    public function import_page() {
        if ( !current_user_can('manage_options') ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        require( $this->addonDir. '/templates/before_importing.php' );
    }

    public function import_manual_menu() {
        add_submenu_page( null, __( 'Import Ziplist', 'wp-ultimate-exercise' ), __( 'Import Ziplist', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_ziplist_manual', array( $this, 'import_manual_page' ) );
    }

    public function import_manual_page() {
        if ( !wp_verify_nonce( $_POST['import_ziplist_manual'], 'import_ziplist_manual' ) ) {
            die( 'Invalid nonce.' );
        }

        // Actually import exercise
        if( isset( $_POST['import_ziplist_id'] ) && isset( $_POST['import_post_id'] )) {

            $post_id = intval( $_POST['import_post_id'] );
            $ziplist_id = intval( $_POST['import_ziplist_id'] );

            $this->import_ziplist_exercise( $post_id, $ziplist_id );
        }

        $this->custom_fields();
        require( $this->addonDir. '/templates/manual_import.php' );
    }

    private function import_ziplist_exercise( $post_id, $ziplist_id )
    {
        $ziplist = $this->ziplist_get_exercise( $ziplist_id );

        // Exercise image
        $exercise_image_url = $ziplist->exercise_image;

        if( $exercise_image_url ) {
            $exercise_image_id = $this->get_or_upload_attachment( $post_id, $exercise_image_url );

            if( $exercise_image_id ) {
                set_post_thumbnail( $post_id, $exercise_image_id );
            }
        }


        // Ingredient groups
        $ziplist_ingredients = explode( "\n", $ziplist->ingredients );

        $ingredient_groups = array();
        $group = '';
        foreach( $ziplist_ingredients as $item ) {
            if( preg_match( "/^%(\S*)/", $item, $matches ) ) {
                // Ignore images
            } else if( preg_match( "/^!(.*)/", $item, $matches ) ) {
                // The next ingredients belong to this group
                $group = $matches[1];
            } else {
                // Ingredient line that belongs to this group
                $ingredient_groups[] = $group;
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
        $ziplist_instructions = explode("\n", $ziplist->instructions);

        $instructions = array();
        $index = 0;
        $group = '';
        $image = '';
        foreach( $ziplist_instructions as $item ) {
            if( strlen( $item ) > 1 ) {
                if( preg_match( "/^%(\S*)/", $item, $matches ) ) {

                    $image_id = $this->get_or_upload_attachment( $post_id, $matches[1] );

                    if( $image_id ) {
                        if( $index == 0 ) {
                            $image = strval( $image_id );
                        } else {
                            if( $instructions[$index-1]['image'] == '' ) {
                                $instructions[$index-1]['image'] = strval( $image_id );
                            } else {
                                // Previous instruction already has an image, so add a new step
                                $instructions[] = array(
                                    'description' => '',
                                    'group' => $group,
                                    'image' => strval( $image_id ),
                                );
                                $index++;
                            }
                        }
                    }
                } else if( preg_match( "/^!(.*)/", $item, $matches ) ) {
                    // The next instructions belong to this group
                    $group = $matches[1];
                } else {
                    $description = $this->ziplist_richify( $item );

                    $instructions[] = array(
                        'description' => $description,
                        'group' => $group,
                        'image' => $image,
                    );
                    $image = '';
                    $index++;
                }
            }
        }
        update_post_meta( $post_id, 'exercise_instructions', $instructions );


        // Servings
        $ziplist_servings = $ziplist->serving_size;

        $match = preg_match( "/^\s*\d+/", $ziplist_servings, $servings_array );
        if( $match === 1 ) {
            $servings = str_replace( ' ','', $servings_array[0] );
        } else {
            $servings = '';
        }

        $servings_type = preg_replace( "/^\s*\d+\s*/", "", $ziplist_servings );

        update_post_meta( $post_id, 'exercise_servings', $servings );
        update_post_meta( $post_id, 'exercise_servings_type', $servings_type );

        $normalized_servings = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_servings( $servings );
        update_post_meta( $post_id, 'exercise_servings_normalized', $normalized_servings );


        // Cooking Times
        $prep_time = $this->ziplist_time_to_minutes( $ziplist->prep_time );
        $cook_time = $this->ziplist_time_to_minutes( $ziplist->cook_time );
        $total_time = $this->ziplist_time_to_minutes( $ziplist->total_time );

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


        // Nutritional information
        $fat = floatval( $ziplist->fat ) > 0 ? strval( floatval( $ziplist->fat ) ) : '';
        $calories = floatval( $ziplist->calories ) > 0 ? strval( floatval( $ziplist->calories ) ) : '';

        $nutritional = array(
            'fat' => $fat,
            'calories' => $calories,
        );

        add_post_meta( $post_id, 'exercise_nutritional', $nutritional );


        // Other metadata
        update_post_meta( $post_id, 'exercise_title', $ziplist->exercise_title );
        update_post_meta( $post_id, 'exercise_description', $this->ziplist_richify( $ziplist->summary ) );
        update_post_meta( $post_id, 'exercise_rating', $this->ziplist_richify( $ziplist->rating ) );
        update_post_meta( $post_id, 'exercise_notes', $this->ziplist_richify( $ziplist->notes ) );
        update_post_meta( $post_id, 'ziplist_yield', $ziplist->yield );

        // Backup to remember which ziplist exercise this originated from
        update_post_meta( $post_id, 'exercise_ziplist_id', $ziplist_id );


        // Switch post type to exercise
        set_post_type( $post_id, 'exercise' );

        // Add [exercise] shortcode instead of ziplist one
        $post = get_post( $post_id );

        $update_content = array(
            'ID' => $post_id,
            'post_content' => $this->ziplist_replace_shortcode( $post->post_content, '[exercise]' ),
        );
        wp_update_post( $update_content );

        // Update exercise terms
        WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $post_id );
    }

    private function get_ziplist_exercises()
    {
        $import_ziplist = array(
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
                $exercises = $this->ziplist_get_exercises_from_content( $post->post_content );

                if( count( $exercises ) == 1 && $post->post_type == 'post' ) {
                    $total++;
                    $import_ziplist['import'][$post->ID] = $exercises[0];
                } else if( count( $exercises ) != 0 ) {
                    $import_ziplist['problem'][$post->ID] = $exercises;
                }

                wp_cache_delete( $post->ID, 'posts' );
                wp_cache_delete( $post->ID, 'post_meta' );
            }

            $offset += $limit;
            wp_cache_flush();
        }

        $import_ziplist['total'] = $total;

        return $import_ziplist;
    }

    private function custom_fields()
    {
        $key = 'ziplist_yield';
        $name = __( 'Yield', 'wp-ultimate-exercise' );

        $custom_fields = WPUltimateExercise::addon( 'custom-fields' )->get_custom_fields();

        if( !array_key_exists( $key, $custom_fields ) ) {
            $custom_fields[$key] = array(
                'key' => $key,
                'name' => $name,
            );

            WPUltimateExercise::addon( 'custom-fields' )->update_custom_fields( $custom_fields );
        }
    }

    function get_or_upload_attachment( $post_id, $url ) {
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
     * Helper functions from Ziplist plugin itself
     */

    private function ziplist_get_exercises_from_content( $post_text )
    {
        $exercises = array();
        $needle_old = 'id="amd-zlexercise-exercise-';
        $preg_needle_old = '/(id)=("(amd-zlexercise-exercise-)[0-9^"]*")/i';
        $needle = '[amd-zlexercise-exercise:';
        $preg_needle = '/\[amd-zlexercise-exercise:([0-9]+)\]/i';

        if( strpos( $post_text, $needle_old ) !== false ) {
            preg_match_all( $preg_needle_old, $post_text, $matches );
            foreach ( $matches[0] as $match ) {
                $exercise_id = str_replace( 'id="amd-zlexercise-exercise-', '', $match );
                $exercise_id = str_replace( '"', '', $exercise_id );
                if( !in_array( $exercise_id, $exercises ) ) {
                    $exercises[] = intval( $exercise_id );
                }
            }
        }

        if( strpos( $post_text, $needle ) !== false ) {
            preg_match_all( $preg_needle, $post_text, $matches );
            foreach( $matches[0] as $match ) {
                $exercise_id = str_replace( '[amd-zlexercise-exercise:', '', $match );
                $exercise_id = str_replace( ']', '', $exercise_id );
                if( !in_array( $exercise_id, $exercises ) ) {
                    $exercises[] = intval( $exercise_id );
                }
            }
        }

        return $exercises;
    }

    private function ziplist_replace_shortcode( $post_text, $replacement )
    {
        $output = $post_text;

        $needle_old = 'id="amd-zlexercise-exercise-';
        $preg_needle_old = '/(id)=("(amd-zlexercise-exercise-)[0-9^"]*")/i';
        $needle = '[amd-zlexercise-exercise:';
        $preg_needle = '/\[amd-zlexercise-exercise:([0-9]+)\]/i';

        if( strpos( $post_text, $needle_old ) !== false ) {
            preg_match_all( $preg_needle_old, $post_text, $matches );
            foreach( $matches[0] as $match ) {
                $exercise_id = str_replace( 'id="amd-zlexercise-exercise-', '', $match );
                $exercise_id = str_replace( '"', '', $exercise_id );
                $output = preg_replace( "/<img id=\"amd-zlexercise-exercise-".$exercise_id."\" class=\"amd-zlexercise-exercise\" src=\"[^\"]*\" alt=\"\" \/>/", $replacement, $output );
            }
        }

        if( strpos( $post_text, $needle ) !== false ) {
            preg_match_all( $preg_needle, $post_text, $matches );
            foreach ( $matches[0] as $match ) {
                $exercise_id = str_replace( '[amd-zlexercise-exercise:', '', $match );
                $exercise_id = str_replace( ']', '', $exercise_id );
                $output = str_replace( '[amd-zlexercise-exercise:' . $exercise_id . ']', $replacement, $output );
            }
        }

        return $output;
    }

    private function ziplist_get_exercise( $exercise_id )
    {
        global $wpdb;

        $exercise = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "amd_zlexercise_exercises WHERE exercise_id=" . $exercise_id);

        return $exercise;
    }

    private function ziplist_richify( $input )
    {
        $output = $this->ziplist_bold( $input, '<strong>', '</strong>' );
        $output = $this->ziplist_italic( $output, '<em>', '</em>' );
        $output = $this->ziplist_link( $output, false );

        return $output;
    }

    private function ziplist_derichify( $input )
    {
        $output = $this->ziplist_bold( $input );
        $output = $this->ziplist_italic( $output );
        $output = $this->ziplist_link( $output );

        return $output;
    }

    private function ziplist_bold( $input, $before = '', $after = '' )
    {
        return preg_replace( '/(^|\s)\*([^\s\*][^\*]*[^\s\*]|[^\s\*])\*(\W|$)/', '\\1'.$before.'\\2'.$after.'\\3', $input );
    }

    private function ziplist_italic( $input, $before = '', $after = '' )
    {
        return preg_replace( '/(^|\s)_([^\s_][^_]*[^\s_]|[^\s_])_(\W|$)/', '\\1'.$before.'\\2'.$after.'\\3', $input );
    }

    private function ziplist_link( $input, $remove = true )
    {
        if( $remove ) {
            $output = preg_replace( '/\[([^\]\|\[]*)\|([^\]\|\[]*)\]/', '\\1', $input );
        } else {
            $output = preg_replace( '/\[([^\]\|\[]*)\|([^\]\|\[]*)\]/', '<a href="\\2" target="_blank">\\1</a>', $input );
        }
        return $output;
    }

    private function ziplist_time_to_minutes( $duration = 'PT' )
    {
        $date_abbr = array(
            'd' => 60*24,
            'h' => 60,
            'i' => 1
        );
        $result = 0;

        $arr = explode( 'T', $duration );
        if( isset( $arr[1] ) ) {
            $arr[1] = str_replace( 'M', 'I', $arr[1] );
        }
        $duration = implode( 'T', $arr );

        foreach( $date_abbr as $abbr => $time ) {
            if( preg_match( '/(\d+)' . $abbr . '/i', $duration, $val ) ) {
                $result += intval( $val[1] ) * $time;
            }
        }

        return $result;
    }
}

WPUltimateExercise::loaded_addon( 'import-ziplist', new WPUEP_Import_Ziplist() );