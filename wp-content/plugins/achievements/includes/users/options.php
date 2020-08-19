<?php
/**
 * Achievements user options 
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
function dpa_get_default_user_options() {
	return apply_filters( 'dpa_get_default_user_options', array(
		'_dpa_last_unlocked'  => 0,        // ID of the last achievement this user unlocked (per site or network)
		'_dpa_unlocked_count' => 0,        // How many achievements this user has unlocked (per site or network)
		'_dpa_points'         => 0,        // How many points this user has (per site or network)
		'_dpa_notifications'  => array(),  // User notifications (per site or network)
	) );
}
function dpa_add_user_options( $user_id = 0 ) {	
	if ( empty( $user_id ) && is_user_logged_in() )
		$user_id = get_current_user_id();
	if ( empty( $user_id ) )
		return;
	$store_global = is_multisite() && dpa_is_running_networkwide();
	foreach ( array_keys( dpa_get_default_user_options() ) as $key => $value )
		update_user_option( $user_id, $key, $value, $store_global );
	do_action( 'dpa_add_user_options', $user_id );
}
function dpa_delete_user_options( $user_id = 0 ) {
	if ( empty( $user_id ) && is_user_logged_in() )
		$user_id = get_current_user_id();
	if ( empty( $user_id ) )
		return;
	foreach ( array_keys( dpa_get_default_user_options() ) as $key => $value ) {
		delete_user_option( $user_id, $key, false );
		delete_user_option( $user_id, $key, true );
	}
	do_action( 'dpa_delete_user_options', $user_id );
}
function dpa_setup_user_option_filters() {
	foreach ( array_keys( dpa_get_default_user_options() ) as $key => $value )
		add_filter( 'get_user_option_' . $key, 'dpa_filter_get_user_option', 10, 3 );
	do_action( 'dpa_setup_user_option_filters' );
}
function dpa_filter_get_user_option( $value = false, $option = '', $user = null ) {
	if ( isset( $user->ID ) && isset( achievements()->user_options[$user->ID] ) && ! empty( achievements()->user_options[$user->ID][$option] ) )
		$value = achievements()->user_options[$user->ID][$option];
	return $value;
}
function dpa_update_user_unlocked_count( $user_id = 0, $new_value = 0 ) {
	if ( empty( $user_id ) && is_user_logged_in() )
		$user_id = get_current_user_id();
	if ( empty( $user_id ) )
		return false;	
	$store_global = is_multisite() && dpa_is_running_networkwide();
	$new_value = apply_filters( 'dpa_update_user_unlocked_count', $new_value, $user_id );
	return update_user_option( $user_id, '_dpa_unlocked_count', absint( $new_value ), $store_global );
}
function dpa_user_unlocked_count( $user_id = 0 ) {
	echo number_format_i18n( dpa_get_user_unlocked_count( $user_id ) );
}	
	function dpa_get_user_unlocked_count( $user_id = 0 ) {		
		if ( empty( $user_id ) && is_user_logged_in() )
			$user_id = get_current_user_id();		
		if ( empty( $user_id ) )
			return false;
		$value = get_user_option( '_dpa_unlocked_count', $user_id );
		return absint( apply_filters( 'dpa_get_user_unlocked_count', $value, $user_id ) );
	}
function dpa_update_user_points( $new_value = 0, $user_id = 0 ) {	
	if ( empty( $user_id ) && is_user_logged_in() )
		$user_id = get_current_user_id();	
	if ( empty( $user_id ) )
		return false;	
	$store_global = is_multisite() && dpa_is_running_networkwide();
	$new_value = apply_filters( 'dpa_update_user_points', $new_value, $user_id );
	$retval    = update_user_option( $user_id, '_dpa_points', (int) $new_value, $store_global );	
	wp_cache_set( 'last_changed', microtime(), 'achievements_leaderboard' );
	return $retval;
}
function dpa_user_points( $user_id = 0 ) {
	echo number_format_i18n( dpa_get_user_points( $user_id ) );
}	
	function dpa_get_user_points( $user_id = 0 ) {		
		if ( empty( $user_id ) && is_user_logged_in() )
			$user_id = get_current_user_id();		
		if ( empty( $user_id ) )
			return false;
		$value = get_user_option( '_dpa_points', $user_id );
		return (int) apply_filters( 'dpa_get_user_points', $value, $user_id );
	}
function dpa_update_user_notifications( $notifications = array(), $user_id = 0 ) {	
	if ( empty( $user_id ) && is_user_logged_in() )
		$user_id = get_current_user_id();
	if ( empty( $user_id ) )
		return false;
	$store_global = is_multisite() && dpa_is_running_networkwide();
	$notifications = (array) apply_filters( 'dpa_update_user_notifications', $notifications, $user_id );
	$new_values    = array();
	foreach ( $notifications as $ID => $value )
		$new_values[absint( $ID )] = $value;
	if ( isset( $new_values[0] ) )
		unset( $new_values[0] );
	return update_user_option( $user_id, '_dpa_notifications', $new_values, $store_global );
}
function dpa_get_user_notifications( $user_id = 0 ) {
	if ( empty( $user_id ) && is_user_logged_in() )
		$user_id = get_current_user_id();
	if ( empty( $user_id ) )
		return array();
	$value = get_user_option( '_dpa_notifications', $user_id );
	if ( empty( $value ) )
		return array();
	return (array) apply_filters( 'dpa_get_user_notifications', $value, $user_id );
}
function dpa_update_user_last_unlocked( $user_id = 0, $new_value = 0 ) {
	if ( empty( $user_id ) && is_user_logged_in() )
		$user_id = get_current_user_id();
	if ( empty( $user_id ) )
		return false;	
	$store_global = is_multisite() && dpa_is_running_networkwide();
	$new_value = apply_filters( 'dpa_update_user_last_unlocked', $new_value, $user_id );
	return update_user_option( $user_id, '_dpa_last_unlocked', (int) $new_value, $store_global );
}
function dpa_user_last_unlocked( $user_id = 0 ) {
	echo number_format_i18n( dpa_get_user_last_unlocked( $user_id ) );
}	
	function dpa_get_user_last_unlocked( $user_id = 0 ) {
		if ( empty( $user_id ) && is_user_logged_in() )
			$user_id = get_current_user_id();
		if ( empty( $user_id ) )
			return false;
		$value = get_user_option( '_dpa_last_unlocked', $user_id );
		return (int) apply_filters( 'dpa_get_user_last_unlocked', $value, $user_id );
	}