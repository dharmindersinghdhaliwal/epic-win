<?php
$data = file_get_contents( 'php://input' );
$objData = json_decode( $data );

$wp_load_dir = isset( $objData->wp_load_dir ) ? $objData->wp_load_dir : false;
if( !$wp_load_dir || !file_exists( $wp_load_dir . 'wp-load.php' ) ) {
    $wp_load_dir = '../../../../../../../';
}
require_once( $wp_load_dir . 'wp-load.php' );

if( !current_user_can( 'manage_options' ) ) die( "You shouldn't be here" );

// Delete template
if( isset( $objData->template ) ) {
    WPUltimateExercise::addon( 'custom-templates' )->delete_template( $objData->template );
}

// Load templates
$mapping = WPUltimateExercise::addon( 'custom-templates' )->get_mapping();

$exercise_default = WPUltimateExercise::option( 'exercise_template_exercise_template', 0 );
$print_default = WPUltimateExercise::option( 'exercise_template_print_template', 1 );
$egrid_default = WPUltimateExercise::option( 'exercise_template_exercisegrid_template', 2 );
$feed_default = WPUltimateExercise::option( 'exercise_template_feed_template', 3 );
$user_menus_default = WPUltimateExercise::option( 'user_menus_exercise_print_template', 1 );

$template_list = array();
foreach( $mapping as $index => $template )
{
    $active = array();

    if( $index == $exercise_default ) $active[] = 'Exercise Default';
    if( $index == $print_default ) $active[] = 'Print Default';
    if( $index == $egrid_default ) $active[] = 'Exercise Egrid Default';
    if( $index == $feed_default ) $active[] = 'RSS Feed Default';
    if( $index == $user_menus_default ) $active[] = 'User Menus Print Default';

    $template_list[] = array(
        'id' => $index,
        'name' => $template,
        'active' => implode( ', ', $active ),
    );
}

echo json_encode( $template_list );