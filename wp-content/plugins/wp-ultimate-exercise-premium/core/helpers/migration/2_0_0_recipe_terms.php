<?php
/*
 * -> 2.0.0
 *
 * Save exercise terms as metadata to speed up Exercise Egrid generation
 */

// Get all exercise posts and loop through them
$exercises = WPUltimateExercise::get()->query()->all();

foreach ( $exercises as $exercise )
{
    WPUltimateExercise::get()->helper( 'exercise_save' )->update_exercise_terms( $exercise->ID() );
}

// Successfully migrated to 2.0.0
$migrate_version = '2.0.0';
update_option( 'wpuep_migrate_version', $migrate_version );
if( $notices ) WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> Successfully migrated to 2.0.0+' );