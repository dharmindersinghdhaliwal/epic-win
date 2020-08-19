<?php
/*
 * -> 2.1.4
 *
 * Fix normalized servings problem
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
}

// Successfully migrated to 2.1.4
$migrate_version = '2.1.4';
update_option( 'wpuep_migrate_version', $migrate_version );
if( $notices ) WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> Successfully migrated to 2.1.4+' );