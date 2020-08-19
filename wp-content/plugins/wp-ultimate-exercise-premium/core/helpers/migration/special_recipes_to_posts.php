<?php
/*
 * -> Exercies to Posts
 *
 * Convert posts that include 1 exercise to actual exercises
 */

$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'nopaging' => true
);

$query = new WP_Query( $args );

$migrate_result = array();

if( $query->have_posts() )
{
    $posts_exercises = array();

    while( $query->have_posts() ) {
        $query->the_post();
        global $post;

        if($post->post_type == 'post')
        {
            $included_ids = array();

            if( stripos($post->post_content, '[ultimate-exercise') !== false ) //contains shortcode
            {
                // Get all the exercise shortcodes
                preg_match_all("/\[ultimate-exercise\s[^]]+/i", $post->post_content, $out);
                $shortcodes = $out[0];

                foreach($shortcodes as $shortcode)
                {
                    // Get id part of shortcode
                    preg_match("/\sid=[\"']?\d+/i", $shortcode, $out);

                    if( !empty( $out ) )
                    {
                        // Get actual ID
                        preg_match("/\d+$/i", $out[0], $out);

                        $included_ids[] = $out[0];
                    }
                    else // Random exercise
                    {
                        $included_ids[] = 'rand';
                    }
                }
            }

            // Only 1 exercise included in this post
            if( count( $included_ids ) === 1  && $included_ids[0] !== 'rand' )
            {
                $posts_exercises[$post->ID] = intval( $included_ids[0] );
            }
        }
    }

    $exercise_count = array_count_values( $posts_exercises );

    foreach( $posts_exercises as $post_id => $exercise_id )
    {

        // If exercise is included in multiple posts we can't know which one should be used
        if( $exercise_count[$exercise_id] !== 1 )
        {
            $migrate_result[] = array(
                'exercise' => $exercise_id,
                'migrated' => false,
                'reason' => 'Exercise is included as the only exercise in multiple posts'
            );
        }
        else
        {
            $exercise = get_post( $exercise_id );

            // Exercise post content should be empty because otherwise we'll lose the current content
            if( $exercise->post_content !== '' )
            {
                $migrate_result[] = array(
                    'exercise' => $exercise_id,
                    'migrated' => false,
                    'reason' => 'Exercise already has post content'
                );
            }
            else
            {
                $post = get_post( $post_id );

                $meta = get_post_custom( $exercise->ID );

                // Ingredients
                $ingredients = unserialize( $meta['exercise_ingredients'][0] );
                $new_ingredients = array();
                $ingredient_terms = array();

                if( $ingredients !== false )
                {
                    foreach( $ingredients as $ingredient )
                    {
                        if( $ingredient['ingredient'] !== '' )
                        {
                            $term = term_exists($ingredient['ingredient'], 'ingredient');

                            if ( $term === 0 || $term === null) {
                                $term = wp_insert_term($ingredient['ingredient'], 'ingredient');
                            }

                            $term_id = intval($term['term_id']);

                            $ingredient['ingredient_id'] = $term_id;

                            $ingredient['amount_normalized'] = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_amount( $ingredient['amount'] );

                            $new_ingredients[] = $ingredient;
                            $ingredient_terms[] = $term_id;
                        }
                    }

                    wp_set_post_terms( $post->ID, $ingredient_terms, 'ingredient' );

                }

                add_post_meta( $post->ID, 'exercise_ingredients', $new_ingredients );

                // Instructions
                $instructions = unserialize( $meta['exercise_instructions'][0] );

                if($instructions !== false)
                {
                    foreach($instructions as $instruction)
                    {
                        if($instruction['image'] != '')
                        {
                            $update_image = array(
                                'ID' => $instruction['image'],
                                'post_parent' => $post->ID,
                            );
                            wp_update_post( $update_image );
                        }
                    }
                }

                add_post_meta( $post->ID, 'exercise_instructions', $instructions );

                $exercise_object = new WPUEP_Exercise( $exercise_id );
                // Exercise Title
                add_post_meta( $post->ID, 'exercise_title', $exercise_object->title() );

                // Servings
                add_post_meta( $post->ID, 'exercise_servings', $meta['exercise_servings'][0] );
                $servings = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_servings( $meta['exercise_servings'][0] );
                add_post_meta( $post->ID, 'exercise_servings_normalized', $servings );

                // Other metadata
                add_post_meta( $post->ID, 'exercise_servings_type', $meta['exercise_servings_type'][0] );
                add_post_meta( $post->ID, 'exercise_description', $meta['exercise_description'][0] );
                add_post_meta( $post->ID, 'exercise_prep_time', $meta['exercise_prep_time'][0] );
                add_post_meta( $post->ID, 'exercise_cook_time', $meta['exercise_cook_time'][0] );
                add_post_meta( $post->ID, 'exercise_passive_time', $meta['exercise_passive_time'][0] );
                add_post_meta( $post->ID, 'exercise_prep_time_text', $meta['exercise_prep_time_text'][0] );
                add_post_meta( $post->ID, 'exercise_cook_time_text', $meta['exercise_cook_time_text'][0] );
                add_post_meta( $post->ID, 'exercise_passive_time_text', $meta['exercise_passive_time_text'][0] );
                add_post_meta( $post->ID, 'exercise_cost', $meta['exercise_cost'][0] );
                add_post_meta( $post->ID, 'exercise_user_ratings', $meta['exercise_user_ratings'][0] );
                add_post_meta( $post->ID, 'exercise_migrated_from', $exercise->ID );


                // Custom tags
                $tags = WPUltimateExercise::get()->tags();
                unset( $tags['ingredient'] );

                foreach( $tags as $tag ) {
                    $terms = get_the_terms( $exercise->ID, $tag );

                    if( $terms !== false && !is_wp_error( $terms ) )
                    {
                        $term_ids = array();
                        foreach( $terms as $term )
                        {
                            $existing_term = term_exists( $term->name, $tag );
                            $term_ids[] = (int) $existing_term['term_id'];
                        }

                        wp_set_object_terms( $post->ID, $term_ids, $tag );
                    }
                }

                // Photo
                $photo_id = get_post_thumbnail_id($exercise->ID);

                if ($photo_id != '' && $photo_id != false) {
                    set_post_thumbnail( $post->ID, $photo_id );
                }

                // Change the slug of the exercise so we don't have any collisions
                $update_slug = array(
                    'ID' => $exercise_id,
                    'post_name' => $exercise->post_name . '-exercise'
                );
                wp_update_post( $update_slug );

                // Change post content shortcode
                $update_content = array(
                    'ID' => $post_id,
                    'post_content' => preg_replace("/\[ultimate-exercise\s[^]]+]/", "[exercise]", $post->post_content)
                );
                wp_update_post( $update_content );

                // Move exercise to trash
                wp_trash_post( $exercise->ID );

                // Switch post type to exercise
                set_post_type( $post->ID, 'exercise' );

                $migrate_result[] = array(
                    'exercise' => $exercise_id,
                    'migrated' => true,
                );
            }
        }
    }
}

var_dump( $migrate_result );