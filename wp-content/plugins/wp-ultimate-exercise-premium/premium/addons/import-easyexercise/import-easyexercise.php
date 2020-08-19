<?php

class WPUEP_Import_Easyexercise extends WPUEP_Premium_Addon {

    public function __construct( $name = 'import-easyexercise' ) {
        parent::__construct( $name );

        if( !class_exists( 'simple_html_dom' ) && !class_exists( 'simple_html_dom_node' ) ) {
            require_once( $this->addonDir . '/vendor/simple_html_dom.php' );
            libxml_use_internal_errors(true);
        }

        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'import_menu' ) );
        add_action( 'admin_menu', array( $this, 'import_manual_menu' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/css/import_easyexercise.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_import_easyexercise',
            ),
            array(
                'file' => $this->addonPath . '/css/import_easyexercise_manual.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_import_easyexercise_manual',
            ),
            array(
                'file' => $this->addonPath . '/vendor/jquery.radioImageSelect.min.js',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_import_easyexercise_manual',
                'deps' => array(
                    'jquery',
                )
            )
        );
    }

    public function import_menu() {
        add_submenu_page( null, __( 'Import EasyExercise', 'wp-ultimate-exercise' ), __( 'Import EasyExercise', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_easyexercise', array( $this, 'import_page' ) );
    }

    public function import_page() {
        if ( !current_user_can('manage_options') ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        require( $this->addonDir. '/templates/before_importing.php' );
    }

    public function import_manual_menu() {
        add_submenu_page( null, __( 'Import EasyExercise', 'wp-ultimate-exercise' ), __( 'Import EasyExercise', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_easyexercise_manual', array( $this, 'import_manual_page' ) );
    }

    public function import_manual_page() {
        if ( !wp_verify_nonce( $_POST['import_easyexercise_manual'], 'import_easyexercise_manual' ) ) {
            die( 'Invalid nonce.' );
        }

        // Actually import exercise
        if( isset( $_POST['import_post_id'] )) {

            $post_id = intval( $_POST['import_post_id'] );
            $this->import_easyexercise_exercise( $post_id );
        }

        $this->custom_fields();
        require( $this->addonDir. '/templates/manual_import.php' );
    }

    private function import_easyexercise_exercise( $post_id )
    {
        $post = get_post( $post_id );

        // Backup post_content in case something goes wrong
        update_post_meta( $post_id, 'exercise_easyexercise_before', $post->post_content );

        $easyexercise_html = $this->get_easyexercise( $post_id );


        // Exercise image
        $featured_image = intval( $_POST['featured-image'] );

        if( $featured_image != 0 ) {
            set_post_thumbnail( $post_id, $featured_image );
        }


        // Ingredient groups
        $ingredient_list = $easyexercise_html->find( 'ul[class=ingredients]' );
        $ingredient_elements = isset( $ingredient_list[0] ) && is_object( $ingredient_list[0] ) ? $ingredient_list[0]->children() : array();

        $ingredient_groups = array();
        $group = '';
        foreach( $ingredient_elements as $ingredient_element ) {
            if( strpos( $ingredient_element->class, 'ERSeparator' ) !== false ) {
                // Ingredient group
                $group = $this->strip_easyexercise_tags( $ingredient_element->plaintext );
            } else {
                // Ingredient
                if( strlen( $this->strip_easyexercise_tags( $ingredient_element->plaintext ) ) > 0 ) {
                    $ingredient_groups[] = $group;
                }
            }
        }

        // Ingredients
        $ingredients = isset( $_POST['exercise_ingredients'] ) ? $_POST['exercise_ingredients'] : array();
        $new_ingredients = array();
        $ingredient_terms = array();

        if( $ingredients )
        {
            foreach( $ingredients as $index => $ingredient )
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
                        $ingredient['group'] = $ingredient_groups[$index];

                        $new_ingredients[] = $ingredient;
                        $ingredient_terms[] = $term_id;
                    }
                }
            }
            wp_set_post_terms( $post_id, $ingredient_terms, 'ingredient' );
        }
        update_post_meta( $post_id, 'exercise_ingredients', $new_ingredients );


        // Instructions
        $instructions = array();
        $instruction_div = $easyexercise_html->find( 'div[class=instructions]' );
        $instruction_children = isset( $instruction_div[0] ) && is_object( $instruction_div[0] ) ? $instruction_div[0]->children() : array();

        $group = '';
        foreach( $instruction_children as $instruction_child ) {
            if( $instruction_child->tag == 'div' && strpos( $instruction_child->class, 'ERSeparator' ) !== false ) {
                // Instruction Group
                $group = $this->strip_easyexercise_tags( $instruction_child->plaintext );
            } else if( $instruction_child->tag == 'ol' ) {
                $instruction_steps = $instruction_child->children();

                foreach( $instruction_steps as $instruction_step ) {
                    if( strpos( $instruction_step->class, 'instruction' ) !== false ) {
                        $description = $this->replace_easyexercise_tags( $instruction_step->plaintext );
                        $images = $this->get_easyexercise_images( $instruction_step->plaintext );

                        if( count( $images ) == 0 ) {
                            // Create an instruction step without image
                            $instructions[] = array(
                                'description' => $description,
                                'group' => $group,
                                'image' => '',
                            );
                        } else {
                            // We have at least 1 image, create an instruction step for each image
                            foreach( $images as $image ) {
                                $instructions[] = array(
                                    'description' => $description,
                                    'group' => $group,
                                    'image' => strval( $image['id'] ),
                                );
                                $description = ''; // Only use description for first step.
                            }
                        }
                    }
                }
            }
        }
        update_post_meta( $post_id, 'exercise_instructions', $instructions );


        // Servings
        $servings = $easyexercise_html->find( 'span[class=yield]', 0 );
        $easyexercise_servings = is_object( $servings ) ? trim( $servings->plaintext ) : '';

        $match = preg_match( "/^\s*\d+/", $easyexercise_servings, $servings_array );
        if( $match === 1 ) {
            $servings = str_replace( ' ','', $servings_array[0] );
        } else {
            $servings = '';
        }

        $servings_type = preg_replace( "/^\s*\d+\s*/", "", $easyexercise_servings );

        update_post_meta( $post_id, 'exercise_servings', $servings );
        update_post_meta( $post_id, 'exercise_servings_type', $servings_type );

        $normalized_servings = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_servings( $servings );
        update_post_meta( $post_id, 'exercise_servings_normalized', $normalized_servings );


        // Cooking Times
        $easyexercise_times = array();
        $times = $easyexercise_html->find( 'time' );
        foreach( $times as $time ) {
            $easyexercise_times[$time->itemprop] = $this->easyexercise_time_to_minutes( $time->datetime );
        }

        $prep_time = isset( $easyexercise_times['prepTime'] ) ? $easyexercise_times['prepTime'] : 0;
        $cook_time = isset( $easyexercise_times['cookTime'] ) ? $easyexercise_times['cookTime'] : 0;
        $total_time = isset( $easyexercise_times['totalTime'] ) ? $easyexercise_times['totalTime'] : 0;

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
        $nutritional_mapping = array(
            'calories'              => 'calories',
            'carbohydrates'         => 'carbohydrate',
            'protein'               => 'protein',
            'fat'                   => 'fat',
            'saturatedFat'          => 'saturated_fat',
            'unsaturatedFat'        => 'polyunsaturated_fat',
            'transFat'              => 'trans_fat',
            'cholesterol'           => 'cholesterol',
            'sodium'                => 'sodium',
            'fiber'                 => 'fiber',
            'sugar'                 => 'sugar',
        );

        $nutritional = array();
        foreach( $nutritional_mapping as $easyexercise_field => $wpuep_field ) {
            $nutritional_data = $easyexercise_html->find( 'span[class=' . $easyexercise_field . ']', 0 );

            if( is_object( $nutritional_data ) ) {
                $value = trim( $nutritional_data->plaintext );
                $nutritional[$wpuep_field] = floatval( $value ) > 0 ? strval( floatval( $value ) ) : '';
            }
        }
        add_post_meta( $post_id, 'exercise_nutritional', $nutritional );


        // Rating
        $rating = $easyexercise_html->getAttribute( 'data-rating' );

        if( $rating && intval( $rating ) > 0 ) {
            if( intval( $rating ) >= 5 ) {
                update_post_meta( $post_id, 'exercise_rating', 5 );
            } else {
                update_post_meta( $post_id, 'exercise_rating', intval( $rating ) );
            }
        } else {
            update_post_meta( $post_id, 'exercise_rating', 0 );
        }


        // User Ratings
        global $wpdb;

        $query = "SELECT comment_author_IP as ip, meta_value as rating FROM $wpdb->comments JOIN $wpdb->commentmeta ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID ";
        $query .= "WHERE comment_approved = 1 AND meta_key = 'ERRating' AND comment_post_ID = $post_id AND meta_value > 0";
        $comments = $wpdb->get_results( $query );

        if( count( $comments ) > 0 ) {
            delete_post_meta( $post_id, 'exercise_user_ratings' );

            foreach( $comments as $comment ) {
                $user_rating = array(
                    'user' => 0,
                    'ip' => $comment->ip,
                    'rating' => intval( $comment->rating ),
                );

                add_post_meta( $post_id, 'exercise_user_ratings', $user_rating );
            }
        }


        // Custom tags
        $tags = array( 'course', 'cuisine' );
        $new_tags = array(
            'course' => $_POST['wpuep_import_course'],
            'cuisine' => $_POST['wpuep_import_cuisine'],
        );

        foreach( $tags as $tag ) {
            $terms = get_the_terms( $post_id, $tag );

            if( $terms !== false && !is_wp_error( $terms ) )
            {
                $term_ids = array();
                foreach( $terms as $term )
                {
                    $existing_term = term_exists( $term->name, $new_tags[$tag] );

                    if ( $existing_term == 0 || $existing_term == null ) {
                        $new_term = wp_insert_term(
                            $term->name,
                            $new_tags[$tag],
                            array(
                                'description' => $term->description,
                                'slug' => $term->slug,
                                'parent' => $term->parent,
                            )
                        );

                        $term_ids[] = (int)$new_term['term_id'];
                    } else {
                        $term_ids[] = (int)$existing_term['term_id'];
                    }
                }

                wp_set_object_terms( $post_id, $term_ids, $new_tags[$tag] );
            }
        }


        // Other metadata
        $easyexercise_field = $easyexercise_html->find( 'div[class=ERName]', 0 );
        $wpuep_field = is_object( $easyexercise_field ) ? $this->strip_easyexercise_tags( $easyexercise_field->plaintext ) : '';
        update_post_meta( $post_id, 'exercise_title', $wpuep_field );

        $easyexercise_field = $easyexercise_html->find( 'div[class=ERSummary]', 0 );
        $wpuep_field = is_object( $easyexercise_field ) ? $this->replace_easyexercise_tags( $easyexercise_field->plaintext ) : '';
        update_post_meta( $post_id, 'exercise_description', $wpuep_field );

        $easyexercise_field = $easyexercise_html->find( 'div[class=ERNotes]', 0 );
        $wpuep_field = is_object( $easyexercise_field ) ? $this->replace_easyexercise_tags( $easyexercise_field->plaintext, true, true ) : '';
        update_post_meta( $post_id, 'exercise_notes', $wpuep_field );

        $easyexercise_field = $easyexercise_html->find( 'span[class=servingSize]', 0 );
        $wpuep_field = is_object( $easyexercise_field ) ? trim( $easyexercise_field->plaintext ) : '';
        update_post_meta( $post_id, 'easyexercise_nutritional_serving_size', $wpuep_field );


        // Switch post type to exercise
        set_post_type( $post_id, 'exercise' );

        // Add [exercise] shortcode instead of EasyExercise html
        $html = $this->get_html( $post_id );
        $wrapper = $html->find( 'div[class=easyexerciseWrapper]', 0 );
        $wrapper = is_object( $wrapper ) ? $wrapper : $html->find( 'div[class=easyexercise]', 0 );
        $wrapper->outertext = '[exercise]';

        $body = $html->find( 'body', 0 );
        $content = $body->innertext;

        $update_content = array(
            'ID' => $post_id,
            'post_content' => $content,
        );
        wp_update_post( $update_content );

        // Update exercise terms
        WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $post_id );
    }

    private function get_easyexercise_exercises()
    {
        $import_exercises = array(
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
                $nbr_exercises = $this->get_easyexercise_exercises_from_post( $post->ID );

                if( $nbr_exercises == 1 && $post->post_type == 'post' ) {
                    $total++;
                    $import_exercises['import'][] = $post->ID;
                } else if( $nbr_exercises != 0 ) {
                    $import_exercises['problem'][] = $post->ID;
                }

                wp_cache_delete( $post->ID, 'posts' );
                wp_cache_delete( $post->ID, 'post_meta' );
            }

            $offset += $limit;
            wp_cache_flush();
        }

        $import_exercises['total'] = $total;

        return $import_exercises;
    }

    private function custom_fields()
    {
        $key = 'easyexercise_author';
        $name = __( 'Author', 'wp-ultimate-exercise' );

        $custom_fields = WPUltimateExercise::addon( 'custom-fields' )->get_custom_fields();

        if( !array_key_exists( $key, $custom_fields ) ) {
            $custom_fields[$key] = array(
                'key' => $key,
                'name' => $name,
            );

            WPUltimateExercise::addon( 'custom-fields' )->update_custom_fields( $custom_fields );
        }

        $key = 'easyexercise_nutritional_serving_size';
        $name = __( 'Nutritional Serving Size', 'wp-ultimate-exercise' );

        $custom_fields = WPUltimateExercise::addon( 'custom-fields' )->get_custom_fields();

        if( !array_key_exists( $key, $custom_fields ) ) {
            $custom_fields[$key] = array(
                'key' => $key,
                'name' => $name,
            );

            WPUltimateExercise::addon( 'custom-fields' )->update_custom_fields( $custom_fields );
        }
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
     * Helper Functions
     */

    private function get_easyexercise_exercises_from_post( $post_id )
    {
        $html = $this->get_html( $post_id );
        $exercises = $html->find( 'div[class=easyexercise]' );
        return count( $exercises );
    }

    private function get_html( $post_id )
    {
        $post = get_post( $post_id );
        return str_get_html( '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>' . $post->post_content . '</body>');
    }

    private function get_easyexercise( $post_id )
    {
        $html = $this->get_html( $post_id );
        $exercises = $html->find( 'div[class=easyexercise]' );
        return $exercises[0];
    }

    private function get_easyexercise_images( $text )
    {
        $images = array();

        preg_match_all( "/\[img[^\]]*]/i", $text, $easyexercise_images );

        if( isset( $easyexercise_images[0] ) ) {
            foreach( $easyexercise_images[0] as $easyexercise_image ) {
                preg_match( "/src=\"([^\"]*)\"/i", $easyexercise_image, $image );

                if( isset( $image[1] ) ) {
                    $id = $this->get_attachment_id_from_url( $image[1] );
                    $image = wp_get_attachment_image_src( $id, array( 9999, 150 ) );

                    $images[] = array(
                        'id' => $id,
                        'img' => $image[0],
                    );
                }
            }
        }

        return $images;
    }

    private function strip_easyexercise_tags( $string )
    {
        $string = str_ireplace( '[b]', '', $string );
        $string = str_ireplace( '[/b]', '', $string );
        $string = str_ireplace( '[i]', '', $string );
        $string = str_ireplace( '[/i]', '', $string );
        $string = str_ireplace( '[u]', '', $string );
        $string = str_ireplace( '[/u]', '', $string );
        $string = str_ireplace( '[br]', '', $string );

        $string = preg_replace( "/\[img[^\]]*]/i", "", $string );

        $string = preg_replace( "/\[url[^\]]*]/i", "", $string );
        $string = str_ireplace( '[/url]', '', $string );

        return trim( $string );
    }

    private function replace_easyexercise_tags( $string, $line_breaks = false, $images = false )
    {
        $string = str_ireplace( '[b]', '<strong>', $string );
        $string = str_ireplace( '[/b]', '</strong>', $string );
        $string = str_ireplace( '[i]', '<em>', $string );
        $string = str_ireplace( '[/i]', '</em>', $string );
        $string = str_ireplace( '[u]', '<span style="text-decoration: underline;">', $string );
        $string = str_ireplace( '[/u]', '</span>', $string );

        if( $line_breaks ) {
            $string = str_ireplace( '[br]', '<br/>', $string );
        } else {
            $string = str_ireplace( '[br]', '', $string );
        }

        if( $images ) {
            $string = preg_replace("/\[img([^\]]*)]/i", "<img$1 />", $string);
        } else {
            $string = preg_replace( "/\[img[^\]]*]/i", "", $string );
        }

        $string = preg_replace("/\[url([^\]]*)]/i", "<a$1>", $string);
        $string = str_ireplace( '[/url]', '</a>', $string );

        return trim( $string );
    }

    private function easyexercise_time_to_minutes( $duration = 'PT' )
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

WPUltimateExercise::loaded_addon( 'import-easyexercise', new WPUEP_Import_Easyexercise() );