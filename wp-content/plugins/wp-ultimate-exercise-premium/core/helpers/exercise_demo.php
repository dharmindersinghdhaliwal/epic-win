<?php

class WPUEP_Exercise_Demo {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'check_exercise_demo' ) );
    }

    public function check_exercise_demo()
    {
        if( isset( $_GET['wpuep_reset_demo_exercise'] ) ) {
            update_option( 'wpuep_demo_exercise', false );
            WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> The Exercise Demo has been reset' );
        }

        if( !get_option( 'wpuep_demo_exercise', false ) ) {
            // Demo Exercise content
            $_POST = array(
                'exercise_meta_box_nonce' => wp_create_nonce( 'exercise' ),
                'exercise_description' => __( 'This must be the best demo exercise I have ever seen. I could eat this every single day.', 'wp-ultimate-exercise' ),
                'exercise_rating' => '4',
                'exercise_servings' => '2',
                'exercise_servings_type' => __( 'people', 'wp-ultimate-exercise' ),
                'exercise_prep_time' => '10',
                'exercise_prep_time_text' => __( 'minutes', 'wp-ultimate-exercise' ),
                'exercise_cook_time' => '20',
                'exercise_cook_time_text' => __( 'minutes', 'wp-ultimate-exercise' ),
                'exercise_passive_time' => '1',
                'exercise_passive_time_text' => __( 'hour', 'wp-ultimate-exercise' ),
                'exercise_ingredients' => array(
                    array(
                        'group' => '',
                        'amount' => '175',
                        'unit' => 'g',
                        'ingredient' => 'tagliatelle',
                        'notes' => '',
                    ),
                    array(
                        'group' => '',
                        'amount' => '200',
                        'unit' => 'g',
                        'ingredient' => 'bacon',
                        'notes' => 'tiny strips',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '1',
                        'unit' => 'clove',
                        'ingredient' => 'garlic',
                        'notes' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '12.5',
                        'unit' => 'g',
                        'ingredient' => 'pine kernels',
                        'notes' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '50',
                        'unit' => 'g',
                        'ingredient' => 'basil leaves',
                        'notes' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '6.25',
                        'unit' => 'cl',
                        'ingredient' => 'olive oil',
                        'notes' => 'extra virgin',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '27.5',
                        'unit' => 'g',
                        'ingredient' => 'Parmesan cheese',
                        'notes' => 'freshly grated',
                    ),
                ),
                'exercise_instructions' => array(
                    array(
                        'group' => 'Fresh Pesto (you can make this in advance)',
                        'description' => 'We\'ll be using a food processor to make the pesto. Put the garlic, pine kernels and some salt in there and process briefly.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto (you can make this in advance)',
                        'description' => 'Add the basil leaves (but keep some for the presentation) and blend to a green paste.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto (you can make this in advance)',
                        'description' => 'While processing, gradually add the olive oil and finally add the Parmesan cheese.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'Bring a pot of salted water to the boil and cook your tagliatelle al dente.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'Use the cooking time of the pasta to sauté your bacon strips.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'After about 8 to 10 minutes, the pasta should be done. Drain it and put it back in the pot to mix it with the pesto.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'Present the dish with some fresh basil leaves on top.',
                        'image' => '',
                    ),

                ),
                'exercise_notes' => __( 'Use this section for whatever you like.', 'wp-ultimate-exercise' ),
            );

            $post_content = '<p>' . __( 'Use this like normal post content. The exercise will automatically be included at the end of the post, or wherever you place the shortcode:', 'wp-ultimate-exercise' ) . '</p>[exercise]<br/><p>' . __( 'This text will appear below your exercise.', 'wp-ultimate-exercise');

            if( WPUltimateExercise::is_addon_active('nutritional-information') ) {
                $post_content .= ' ' . __( 'Followed by the nutrition label:', 'wp-ultimate-exercise' ) . '</p>[nutrition-label]<br/>';
            } else {
                $post_content .= '</p>';
            }

            // Insert post
            $post = array(
                'post_title' => __( 'Demo Exercise', 'wp-ultimate-exercise' ),
                'post_content' => $post_content,
                'post_type'	=> 'exercise',
                'post_status' => 'private',
                'post_author' => get_current_user_id(),
            );

            $post_id = wp_insert_post($post);
            update_option( 'wpuep_demo_exercise', $post_id );

            // Update post taxonomies
            $tags = array(
                'cuisine' => array(
                    'Italian',
                ),
                'course' => array(
                    'Main Dish',
                ),
            );

            foreach( $tags as $tag => $terms ) {
                $term_ids = array();
				
                foreach( $terms as $term )
                {
                    $existing_term = term_exists( $term, $tag );

                    if ( $existing_term == 0 || $existing_term == null ) {
                        $new_term = wp_insert_term( $term, $tag );						
                        $term_ids[] = (int)$new_term['term_id'];
                    } else {
                        $term_ids[] = (int)$existing_term['term_id'];
                    }
                }

                wp_set_object_terms( $post_id, $term_ids, $tag );
            }

            // Exercise image
            $url = WPUltimateExercise::get()->coreUrl . '/img/demo-exercise.jpg';
            media_sideload_image( $url, $post_id );

            $attachments = get_posts( array(
                'numberposts' => '1',
                'post_parent' => $post_id,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => 'ASC')
            );

            if( sizeof( $attachments ) > 0 ) {
                set_post_thumbnail( $post_id, $attachments[0]->ID );
            }

            // Nutritional Information
            $nutritional = array(
                'calories' => '1276',
                'carbohydrate' => '71',
                'protein' => '57',
                'fat' => '85',
                'saturated_fat' => '22',
                'polyunsaturated_fat' => '10',
                'monounsaturated_fat' => '44',
                'trans_fat' => '',
                'cholesterol' => '238',
                'sodium' => '2548',
                'potassium' => '620',
                'fiber' => '4',
                'sugar' => '4',
                'vitamin_a' => '2',
                'vitamin_c' => '0.1',
                'calcium' => '16',
                'iron' => '12'
            );

            update_post_meta( $post_id, 'exercise_nutritional', $nutritional );

            // Update exercise content
            WPUltimateExercise::get()->helper( 'exercise_save' )->save( $post_id, get_post( $post_id ) );
        }
    }
}