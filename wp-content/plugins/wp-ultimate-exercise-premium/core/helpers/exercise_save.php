<?php

class WPUEP_Exercise_Save {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
    }

    /**
     * Handles saving of exercises
     */
    public function save( $id, $post )
    {
        if( $post->post_type == 'exercise' )
        {	
            if ( !isset( $_POST['exercise_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['exercise_meta_box_nonce'], 'exercise' ) )
            {
                return $id;
            }

            $exercise = new WPUEP_Exercise( $post );

            $fields = $exercise->fields();

            // Make sure the exercise_title meta is present
            if( !isset( $_POST['exercise_title'] ) ) {
                $_POST['exercise_title'] = $exercise->title();
            } else if( $_POST['exercise_title'] == '' ) {
                $_POST['exercise_title'] = $post->post_title;
            }

            // TODO Refactor saving of fields
            foreach ( $fields as $field )
            {
                $old = get_post_meta( $exercise->ID(), $field, true );
                $new = isset( $_POST[$field] ) ? $_POST[$field] : null;

                // Field specific adjustments
                if( isset( $new ) && $field == 'exercise_ingredients' )
                {
                    $ingredients = array();
                    $non_empty_ingredients = array();

                    foreach( $new as $ingredient ) {
                        if( trim( $ingredient['ingredient'] ) != '' )
                        {
                            $term = term_exists( $ingredient['ingredient'], 'ingredient' );

                            if ( $term === 0 || $term === null ) {
                                $term = wp_insert_term( $ingredient['ingredient'], 'ingredient' );
                            }

                            if( is_wp_error( $term ) ) {
                                if( isset( $term->error_data['term_exists'] ) ) {
                                    $term_id = intval( $term->error_data['term_exists'] );
                                } else {
                                    var_dump( $term );
                                }
                            } else {
                                $term_id = intval( $term['term_id'] );
                            }

                            $ingredient['ingredient_id'] = $term_id;
                            $ingredients[] = $term_id;

                            $ingredient['amount_normalized'] = $this->normalize_amount( $ingredient['amount'] );

                            $non_empty_ingredients[] = $ingredient;
                        }
                    }

                    wp_set_post_terms( $exercise->ID(), $ingredients, 'ingredient' );
                    $new = $non_empty_ingredients;
                }
                elseif( isset( $new ) && $field == 'exercise_instructions' )
                {
                    $non_empty_instructions = array();

                    foreach( $new as $instruction ) {
                        if( $instruction['description'] != '' || ( isset( $instruction['image'] ) && $instruction['image'] != '' ) )
                        {
                            $non_empty_instructions[] = $instruction;
                        }
                    }

                    $new = $non_empty_instructions;
                }
                elseif( isset( $new ) && $field == 'exercise_servings' )
                {
                    update_post_meta( $exercise->ID(), 'exercise_servings_normalized', $this->normalize_servings( $new ) );
                }
                elseif( isset( $new ) && $field == 'exercise_rating' )
                {
                    $term_name = intval( $new ) == 1 ? $new .' '. __( 'star', 'wp-ultimate-exercise' ) : $new .' '. __( 'stars', 'wp-ultimate-exercise' );
                    wp_set_post_terms( $exercise->ID(), $term_name, 'rating' );
                }

                // Update or delete meta data if changed
                if ( isset( $new ) && $new != $old )
                {
                    update_post_meta( $exercise->ID(), $field, $new );

                    if( $field == 'exercise_ingredients' && WPUltimateExercise::is_addon_active( 'nutritional-information' ) && WPUltimateExercise::option( 'nutritional_information_notice', '1' ) == '1' && current_user_can( WPUltimateExercise::option( 'nutritional_information_capability', 'manage_options' ) ) ) {
                        $notice = '<strong>' . $_POST['exercise_title'] . ':</strong> <a href="'.admin_url( 'edit.php?post_type=exercise&page=wpuep_nutritional_information&limit_by_exercise=' . $exercise->ID() ).'">'. __( 'Update the Nutritional Information', 'wp-ultimate-exercise') .'</a>';
                        WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( $notice );
                    }
                }
                elseif ( $new == '' && $old )
                {
                    delete_post_meta( $exercise->ID(), $field, $old );
                }
            }

            $this->update_exercise_terms( $exercise->ID() );
        }
    }

    /**
     * Save a list of the exercise terms so we can load the Exercise Egrid faster
     */
    public function update_exercise_terms( $exercise_id )
    {
        $taxonomies = WPUltimateExercise::get()->tags();
        $taxonomies['category'] = array( 'labels' => array( 'name' => __( 'Categories', 'wp-ultimate-exercise' ) ) );
        $taxonomies['post_tag'] = array( 'labels' => array( 'name' => __( 'Tags', 'wp-ultimate-exercise' ) ) );

        $exercise_terms = array();
        $exercise_terms_with_parents = array();
        foreach( $taxonomies as $taxonomy => $options ) {
            $terms = wp_get_post_terms( $exercise_id, $taxonomy );

            $exercise_terms[$taxonomy] = array(0);
            $exercise_terms_with_parents[$taxonomy] = array(0);

            $parents = array();

            foreach( $terms as $term ) {
                $exercise_terms[$taxonomy][] = $term->term_id;
                $exercise_terms_with_parents[$taxonomy][] = $term->term_id;

                if( $term->parent != 0 ) {
                    $parents[] = $term->parent;
                }
            }

            // Get term parents as well
            while( count( $parents ) > 0 )
            {
                $children = $parents;
                $parents = array();

                foreach( $children as $child ) {
                    $term = get_term( $child, $taxonomy );

                    $exercise_terms_with_parents[$taxonomy][] = $term->term_id;

                    if( $term->parent != 0 ) {
                        $parents[] = $term->parent;
                    }
                }
            }
        }

        update_post_meta( $exercise_id, 'exercise_terms', $exercise_terms );
        update_post_meta( $exercise_id, 'exercise_terms_with_parents', $exercise_terms_with_parents );
    }

    /**
     * Get normalized servings
     */
    public function normalize_servings( $servings )
    {
        preg_match("/^\d[\d.,]*/", ltrim( $servings ), $out);

        if( isset( $out[0] ) ) {
            $amount = floatval( $out[0] );
            if( $amount == 0 ) {
                $amount = floatval( WPUltimateExercise::option( 'exercise_default_servings', 4 ) );
            }
        } else {
            $amount = floatval( WPUltimateExercise::option( 'exercise_default_servings', 4 ) );
        }

        return $amount;
    }

    /**
     * Get normalized amount. 0 if not a valid amount.
     *
     * @param $amount       Amount to be normalized
     * @return int
     */
    public function normalize_amount( $amount )
    {
        if( is_null($amount) || trim($amount) == '' ) {
            return 0;
        }

        // Treat " to " as a dash for ranges
        $amount = str_ireplace( ' to ', '-', $amount );

        $amount = preg_replace( "/[^\d\.\/\,\s-–—]/", "", $amount ); // Only keep digits, comma, point, forward slash, space and dashes

        // Replace en and em dash with a normal dash
        $amount = str_replace( '–', '-', $amount );
        $amount = str_replace( '—', '-', $amount );

        // Only take first part if we have a dash (e.g. 1-2 cups)
        $parts = explode( '-', $amount );
        $amount = $parts[0];

        // If spaces treat as separate amounts to be added (e.g. 2 1/2 cups = 2 + 1/2)
        $parts = explode( ' ', $amount );

        $float = 0.0;
        foreach( $parts as $amount ) {
            $separator = $this->find_separator( $amount );

            switch ($separator) {
                case '/':
                    $amount = str_replace( '.','', $amount );
                    $amount = str_replace( ',','', $amount );
                    $parts = explode( '/', $amount );

                    $denominator = floatval($parts[1]);
                    if( $denominator == 0 ) {
                        $denominator = 1;
                    }

                    $float += floatval($parts[0]) / $denominator;
                    break;
                case '.':
                    $amount = str_replace( ',','', $amount );
                    $float += floatval($amount);
                    break;
                case ',':
                    $amount = str_replace( '.','', $amount );
                    $amount = str_replace( ',','.', $amount );
                    $float += floatval($amount);
                    break;
                default:
                    $float += floatval($amount);
            }
        }

        return $float;
    }

    /**
     * Pick a separator for the amount
     * Examples:
     * 1/2 => /
     * 1.123,42 => ,
     * 1,123.42 => .
     *
     * @param $string
     * @return string
     */
    private function find_separator( $string )
    {
        $slash = strrpos($string, '/');
        $point = strrpos($string, '.');
        $comma = strrpos($string, ',');

        if( $slash ) {
            return '/';
        }
        else {
            if( !$point && !$comma ) {
                return '';
            } else if( !$point && $comma ) {
                return ',';
            } else if( $point && !$comma ) {
                return '.';
            } else if( $point > $comma ) {
                return '.';
            } else {
                return ',';
            }
        }
    }
}