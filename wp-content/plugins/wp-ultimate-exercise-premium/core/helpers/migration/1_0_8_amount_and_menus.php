<?php
/*
 * -> 1.0.8
 *
 * Store normalized ingredient amounts and migrate user menus
 */

/**
 * Normalize servings and amounts
 */
// Get all exercise posts and loop through them
$exercises = WPUltimateExercise::get()->query()->all();

foreach ( $exercises as $exercise )
{
    // Normalize servings
    $servings = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_servings( $exercise->servings() );
    update_post_meta( $exercise->ID(), 'exercise_servings_normalized', $servings );

    // Normalize ingredient amounts
    $ingredients = array();

    foreach( $exercise->ingredients() as $exercise_ingredient )
    {
        if( isset( $exercise_ingredient['ingredient'] ) && trim( $exercise_ingredient['ingredient'] ) !== '' )
        {
            $exercise_ingredient['amount_normalized'] = WPUltimateExercise::get()->helper( 'exercise_save' )->normalize_amount( $exercise_ingredient['amount'] );
            $ingredients[] = $exercise_ingredient;
        }
    }

    update_post_meta( $exercise->ID(), 'exercise_ingredients', $ingredients );
}

/**
 * User menus migration
 */
$args = array(
    'post_type' => 'menu',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'nopaging' => true,
);

$query = new WP_Query( $args );

if( $query->have_posts() )
{
    while( $query->have_posts() ) {
        $query->the_post();
        global $post;

        $servings = get_post_meta( $post->ID, 'user-menus-global-servings', true );
        $exercises = get_post_meta( $post->ID, 'user-menus-exercise-ids' );
        $exercises = isset( $exercises[0] ) ? $exercises[0] : null;

        if( !is_null( $exercises ) && count( $exercises ) > 0 )
        {
            $migrated_exercises = array();
            $order = array();
            $nbrExercies = 0;
            $unitSystem = 0;

            foreach( $exercises as $exercise_id )
            {
                if( get_post_type($exercise_id) == 'exercise' )
                {
                    $exercise = new WPUEP_Exercise( $exercise_id );

                    $servings_original = $exercise->servings_normalized();
                    if( $servings_original < 1 ) {
                        $servings_original = 1;
                    }

                    $migrated = array(
                        'id' => $exercise_id,
                        'name' => $exercise->title(),
                        'link' => $exercise->link(),
                        'servings_original' => $servings_original,
                        'servings_wanted' => $servings,
                    );

                    $migrated_exercises[] = $migrated;
                    $order[] = strval( $nbrExercies );
                    $nbrExercies++;
                }
            }

            update_post_meta( $post->ID, 'user-menus-exercises', $migrated_exercises );
            update_post_meta( $post->ID, 'user-menus-order', $order );
            update_post_meta( $post->ID, 'user-menus-nbrExercies', $nbrExercies );
            update_post_meta( $post->ID, 'user-menus-unitSystem', $unitSystem );
        }
    }
}

// Successfully migrated to 1.0.8
$migrate_version = '1.0.8';
update_option( 'wpuep_migrate_version', $migrate_version );
if( $notices ) WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> Successfully migrated to 1.0.8+' );