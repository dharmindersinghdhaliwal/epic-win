<?php
/*
 * -> 1.0.9
 *
 * Allow free text for exercise times
 */

// Get all exercise posts and loop through them
$exercises = WPUltimateExercise::get()->query()->all();

foreach ( $exercises as $exercise )
{
    if( $exercise->prep_time() ) {
        update_post_meta( $exercise->ID(), 'exercise_prep_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
    }
    if( $exercise->cook_time() ) {
        update_post_meta( $exercise->ID(), 'exercise_cook_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
    }
    if( $exercise->passive_time() ) {
        update_post_meta( $exercise->ID(), 'exercise_passive_time_text', __( 'minutes', 'wp-ultimate-exercise' ) );
    }
}

// Successfully migrated to 1.0.9
$migrate_version = '1.0.9';
update_option( 'wpuep_migrate_version', $migrate_version );
if( $notices ) WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> Successfully migrated to 1.0.9+' );