<?php
/*
 * -> 2.2.1
 *
 * Better way of saving custom templates
 */

$custom_templates = get_option( 'wpuep_custom_templates', array() );

$mapping = array();

foreach( $custom_templates as $id => $custom_template ) {
    $mapping[$id] = $custom_template['name'];
    add_option( 'wpuep_custom_template_' . intval( $id ), $custom_template['template'] );
}

WPUltimateExercise::addon( 'custom-templates' )->update_mapping( $mapping );

// Successfully migrated to 2.1.4
$migrate_version = '2.2.1';
update_option( 'wpuep_migrate_version', $migrate_version );
if( $notices ) WPUltimateExercise::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Exercise</strong> Successfully migrated to 2.2.1+' );