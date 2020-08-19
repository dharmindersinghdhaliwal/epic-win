<?php
/**
* Achievement options
*
* @package Achievements
* @subpackage CoreOptions
*/
if ( ! defined( 'ABSPATH' ) ) exit;
function dpa_add_options() {
	$options = dpa_get_default_options();
	foreach ( $options as $key => $value )
		add_option( $key, $value );
	do_action( 'dpa_add_options' );
}
function dpa_delete_options() {
	foreach ( array_keys( dpa_get_default_options() ) as $key )
		delete_option( $key );
	do_action( 'dpa_delete_options' );
}
function dpa_setup_option_filters() {
	foreach ( array_keys( dpa_get_default_options() ) as $key )
		add_filter( 'pre_option_' . $key, 'dpa_pre_get_option' );
	do_action( 'dpa_setup_option_filters' );
}
function dpa_pre_get_option( $value = '' ) {
	$option = str_replace( 'pre_option_', '', current_filter() );
	if ( isset( achievements()->options[$option] ) )
		$value = achievements()->options[$option];
	return $value;
}
function dpa_get_theme_package_id( $default = 'default' ) {
	return apply_filters( 'dpa_get_theme_package_id', get_option( '_dpa_theme_package_id', $default ) );
}
function dpa_get_achievements_per_page() {
	$default = 15;
	$per = $retval = (int) get_option( '_dpa_achievements_per_page', $default );
	if ( empty( $retval ) )
		$retval = $default;
	return (int) apply_filters( 'dpa_get_achievements_per_page', $retval, $per );
}
function dpa_get_achievements_per_rss_page() {
	$default = 25;
	$per = $retval = (int) get_option( '_dpa_achievements_per_rss_page', $default );
	if ( empty( $retval ) )
		$retval = $default;
	return (int) apply_filters( 'dpa_get_achievements_per_rss_page', $retval, $per );
}
function dpa_get_progresses_per_page() {
	$default = 15;
	$per = $retval = (int) get_option( '_dpa_progresses_per_page', $default );
	if ( empty( $retval ) )
		$retval = $default;
	return (int) apply_filters( 'dpa_get_progresses_per_page', $retval, $per );
}
function dpa_get_progresses_per_rss_page() {
	$default = 25;
	$per = $retval = (int) get_option( '_dpa_progresses_per_rss_page', $default );
	if ( empty( $retval ) )
		$retval = $default;
	return (int) apply_filters( 'dpa_get_progresses_per_rss_page', $retval, $per );
}
function dpa_get_leaderboard_items_per_page() {
	$default = 1000;
	$per     = $retval = (int) get_option( '_dpa_leaderboard_per_page', $default );
	if ( empty( $retval ) )
		$retval = $default;
	return (int) apply_filters( 'dpa_get_leaderboard_items_per_page', $retval, $per );
}
function dpa_get_root_slug( $default = 'achievements' ) {
	return apply_filters( 'dpa_get_root_slug', get_option( '_dpa_root_slug', $default ) );
}
function dpa_get_singular_root_slug( $default = 'achievement' ) {
	return apply_filters( 'dpa_get_singular_root_slug', get_option( '_dpa_singular_root_slug', $default ) );
}
function dpa_get_achievement_slug() {
	return apply_filters( 'dpa_get_achievement_slug', dpa_get_root_slug() );
}
function dpa_get_extension_versions() {
	if ( is_multisite() && dpa_is_running_networkwide() )
		$retval = get_site_option( '_dpa_extension_versions', array() );
	else
		$retval = get_option( '_dpa_extension_versions', array() );

	return apply_filters( 'dpa_get_extension_versions', $retval );
}
function dpa_update_extension_versions( $new_value ) {
	if ( is_multisite() && dpa_is_running_networkwide() )
		update_site_option( '_dpa_extension_versions', $new_value );
	else
		update_option( '_dpa_extension_versions', $new_value );
}
function dpa_stats_get_last_achievement_id() {
	$id = get_option( '_dpa_stats_last_achievement_id', 0 );

	return (int) apply_filters( 'dpa_stats_get_last_achievement_id', $id );
}
function dpa_stats_get_last_achievement_user_id() {
	$user_id = get_option( '_dpa_stats_last_achievement_user_id', 0 );

	return (int) apply_filters( 'dpa_stats_get_last_achievement_user_id', $user_id );
}
function dpa_stats_update_last_achievement_id( $achievement_id ) {
	$achievement_id = apply_filters( 'dpa_stats_update_last_achievement_id', $achievement_id );

	update_option( '_dpa_stats_last_achievement_id', $achievement_id );
}
function dpa_stats_update_last_achievement_user_id( $user_id ) {
	$user_id = apply_filters( 'dpa_stats_update_last_achievement_user_id', $user_id );

	update_option( '_dpa_stats_last_achievement_user_id', $user_id );
}