<?php
/*
 * -> 1.0.4
 *
 * Store ingredient IDs
 */

// Get all exercise posts and loop through them
$exercises = WPUltimateExercise::get()->query()->all();

foreach ( $exercises as $exercise )
{
    $ingredients = array();
    $terms = array();

    foreach( $exercise->ingredients() as $exercise_ingredient )
    {
        if( isset( $exercise_ingredient['ingredient'] ) && trim( $exercise_ingredient['ingredient'] ) !== '' )
        {
            $term = term_exists( $exercise_ingredient['ingredient'], 'ingredient' );

            if ( $term === 0 || $term === null ) {
                $term = wp_insert_term( $exercise_ingredient['ingredient'], 'ingredient' );
            }

            if( !is_wp_error( $term ) )
            {
                $term_id = intval( $term['term_id'] );

                $exercise_ingredient['ingredient_id'] = $term_id;

                $ingredients[] = $exercise_ingredient;
                $terms[] = $term_id;
            }
        }
    }

    wp_set_post_terms( $exercise->ID(), $terms, 'ingredient' );
    update_post_meta( $exercise->ID(), 'exercise_ingredients', $ingredients );
}

// Successfully migrated to 1.0.4
$migrate_version = '1.0.4';
update_option( 'wpuep_migrate_version', $migrate_version );
if( $notices ) WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> Successfully migrated to 1.0.4+' );