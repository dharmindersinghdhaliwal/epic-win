<?php
/*
 * -> 2.0.8
 *
 * Make sure the Exercise Title field is filled
 */

// Get all exercise posts and loop through them
$exercises = WPUltimateExercise::get()->query()->all();

foreach ( $exercises as $exercise )
{
    // Check if the exercise_title field is set
    if( !$exercise->meta( 'exercise_title' ) ) {
        $title = $exercise->title();
        update_post_meta( $exercise->ID(), 'exercise_title', $title );
    }
}

// Successfully migrated to 2.0.8
$migrate_version = '2.0.8';
update_option( 'wpuep_migrate_version', $migrate_version );
if( $notices ) WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> Successfully migrated to 2.0.8+' );