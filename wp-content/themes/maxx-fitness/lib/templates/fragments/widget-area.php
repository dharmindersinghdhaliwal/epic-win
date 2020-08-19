<?php
/**
 * Echo widget areas.
 *
 * @package Fragments\Widget_Area
 */

maxxfitness_add_smart_action( 'maxxfitness_sidebar_primary', 'maxxfitness_widget_area_sidebar_primary' );

/**
 * Echo primary sidebar widget area.
 *
 * @since 1.0.0
 */
function maxxfitness_widget_area_sidebar_primary() {

	echo maxxfitness_widget_area( 'sidebar-a' );

}


maxxfitness_add_smart_action( 'maxxfitness_sidebar_secondary', 'maxxfitness_widget_area_sidebar_secondary' );

/**
 * Echo secondary sidebar widget area.
 *
 * @since 1.0.0
 */
function maxxfitness_widget_area_sidebar_secondary() {

	echo maxxfitness_widget_area( 'sidebar_secondary' );

}


maxxfitness_add_smart_action( 'maxxfitness_site_after_markup', 'maxxfitness_widget_area_offcanvas_menu' );

/**
 * Echo off-canvas widget area.
 *
 * @since 1.0.0
 */
function maxxfitness_widget_area_offcanvas_menu() {

	if ( !current_theme_supports( 'offcanvas-menu' ) )
		return;

	echo maxxfitness_widget_area( 'offcanvas_menu' );

}