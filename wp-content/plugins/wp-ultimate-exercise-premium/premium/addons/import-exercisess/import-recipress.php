<?php

class WPUEP_Import_Recipress extends WPUEP_Premium_Addon {

    public function __construct( $name = 'import-recipress' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'admin_init', array( $this, 'eassets' ) );
        add_action( 'admin_menu', array( $this, 'import_recipress_menu' ) );
        add_action( 'admin_action_import_recipress', array( $this, 'import_recipress' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/css/import_recipress.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_import_recipress',
            )
        );
    }

    public function import_recipress_menu() {
        add_submenu_page( null, __( 'Import ReciPress', 'wp-ultimate-exercise' ), __( 'Import ReciPress', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_import_recipress', array( $this, 'import_recipress_page' ) );
    }

    public function import_recipress_page() {
        if ( !current_user_can('manage_options') ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        require( $this->addonDir. '/templates/import_recipress.php' );
    }

    public function import_recipress() {
        if ( !wp_verify_nonce( $_POST['import_recipress_nonce'], 'import_recipress' ) ) {
            die( 'Invalid nonce.' );
        }

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if( !is_plugin_active( 'recipress/recipress.php' ) ) {
            die( __( 'You should activate this plugin:', 'wp-ultimate-exercise' ) . ' ReciPress' );
        }

        $wpuep_taxonomies = WPUltimateExercise::get()->tags();
        unset( $wpuep_taxonomies['ingredient'] );

        $new_tags = array(
            'cuisine' => $_POST['wpuep_import_cuisine'],
            'course' => $_POST['wpuep_import_course'],
            'skill_level' => $_POST['wpuep_import_skill_level'],
        );

        foreach( $new_tags as $tag ) {
            if ( !array_key_exists( $tag, $wpuep_taxonomies ) ) {
                die( __( 'You should select a new tag for the imported exercises', 'wp-ultimate-exercise' ) );
            }
        }

        $args = array(
            'post_type' => 'post',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'nopaging' => true,
        );

        $post_query = new WP_Query( $args );

        if( $post_query->have_posts() ) {
            while( $post_query->have_posts() ) {
                $post_query->the_post();
                global $post;

                // Check if post has a exercise
                if( get_post_meta( $post->ID, 'hasExercise', true ) == 'Yes' )
                {
                    $this->migrate_recipress_exercise( $post, $new_tags );
                }
            }
        }

        wp_redirect( admin_url( 'edit.php?post_type=exercise' ) );
        exit();
    }

    private function migrate_recipress_exercise( $recipress, $new_tags )
    {
        $meta = get_post_custom( $recipress->ID );

        // Ingredients
        $ingredients = unserialize( $meta['ingredient'][0] );
        $new_ingredients = array();
        $ingredient_terms = array();

        if( $ingredients !== false )
        {
            foreach( $ingredients as $ingredient )
            {
                if( trim( $ingredient['ingredient'] ) !== '' )
                {
                    $ingredient['unit'] = $ingredient['measurement'];
                    unset( $ingredient['measurement'] );

                    $term = term_exists( $ingredient['ingredient'], 'ingredient' );

                    if ( $term === 0 || $term === null ) {
                        $term = wp_insert_term( $ingredient['ingredient'], 'ingredient' );
                    }

                    $ingredient['amount_normalized'] = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_amount( $ingredient['amount'] );

                    if( !is_wp_error( $term ) )
                    {
                        $term_id = intval( $term['term_id'] );

                        $ingredient['ingredient_id'] = $term_id;

                        $new_ingredients[] = $ingredient;
                        $ingredient_terms[] = $term_id;
                    }
                }
            }

            wp_set_post_terms( $recipress->ID, $ingredient_terms, 'ingredient' );

        }

        add_post_meta( $recipress->ID, 'exercise_ingredients', $new_ingredients );


        // Instructions
        $instructions = unserialize( $meta['instruction'][0] );
        add_post_meta( $recipress->ID, 'exercise_instructions', $instructions );


        // Servings
        $recipress_servings = $meta['servings'][0];
        $recipress_yield = $meta['yield'][0];

        if( isset( $recipress_servings ) && trim( $recipress_servings ) != '' )
        {
            $servings = $recipress_servings;
            $servings_type = '';
        }
        else
        {
            $match = preg_match( "/^\s*\d+/", $recipress_yield, $servings_array );
            if( $match === 1 ) {
                $servings = str_replace( ' ','', $servings_array[0] );
            } else {
                $servings = '';
            }

            $servings_type = preg_replace( "/^\s*\d+\s*/", "", $recipress_yield );
        }

        add_post_meta( $recipress->ID, 'exercise_servings', $servings );
        add_post_meta( $recipress->ID, 'exercise_servings_type', $servings_type );
        add_post_meta( $recipress->ID, 'exercise_yield', $meta['yield'][0] ); // For backup purposes

        $normalized_servings = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_servings( $servings );
        add_post_meta( $recipress->ID, 'exercise_servings_normalized', $normalized_servings );

        // Other metadata
        add_post_meta( $recipress->ID, 'exercise_title', $meta['title'][0] );
        add_post_meta( $recipress->ID, 'exercise_description', $meta['summary'][0] );
        add_post_meta( $recipress->ID, 'exercise_prep_time', $meta['prep_time'][0] );
        add_post_meta( $recipress->ID, 'exercise_cook_time', $meta['cook_time'][0] );
        add_post_meta( $recipress->ID, 'exercise_passive_time', $meta['other_time'][0] );
        add_post_meta( $recipress->ID, 'exercise_prep_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
        add_post_meta( $recipress->ID, 'exercise_cook_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
        add_post_meta( $recipress->ID, 'exercise_passive_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
        add_post_meta( $recipress->ID, 'exercise_cost', $meta['cost'][0] );


        // Custom tags
        $tags = array( 'cuisine', 'course', 'skill_level' );

        foreach( $tags as $tag ) {
            $terms = get_the_terms( $recipress->ID, $tag );

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

                wp_set_object_terms( $recipress->ID, $term_ids, $new_tags[$tag] );
            }
        }

        $recipress_options = get_option( 'recipress_options' );

        // Photo
        if( current_theme_supports( 'post-thumbnails' ) && $recipress_options['use_photo'] != 'no' )
            $photo_id = get_post_thumbnail_id( $recipress->ID );
        else {
            $photo_id = $meta['photo'][0];
        }

        if ( $photo_id != '' && $photo_id != false ) {
            set_post_thumbnail( $recipress->ID, $photo_id );
        }

        // Remove ReciPress exercise (but keep rest of data as backup)
        delete_post_meta( $recipress->ID, 'hasExercise' );

        // Switch post type to exercise
        set_post_type( $recipress->ID, 'exercise' );

        // Update exercise terms
        WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $recipress->ID );
    }
}

WPUltimateExercise::loaded_addon( 'import-recipress', new WPUEP_Import_Recipress() );