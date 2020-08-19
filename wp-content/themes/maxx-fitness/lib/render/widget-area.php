<?php
/**
 * Registers Beans's default widget areas.
 *
 * @package Render\Widgets
 */

maxxfitness_add_smart_action( 'widgets_init', 'maxxfitness_do_register_widget_areas', 5 );

/**
 * Register Beans's default widget areas.
 *
 * @since 1.0.0
 */
function maxxfitness_do_register_widget_areas() {

	// Keep primary sidebar first for default widget asignment.
	maxxfitness_register_widget_area( array(
		'name' => esc_html__( 'sidebar-a', 'maxx-fitness' ),
		'id' => 'sidebar-a'
	) );

	maxxfitness_register_widget_area( array(
		'name' => esc_html__( 'Sidebar Secondary', 'maxx-fitness' ),
		'id' => 'sidebar_secondary'
	) );

	if ( current_theme_supports( 'offcanvas-menu' ) )
		maxxfitness_register_widget_area( array(
			'name' => esc_html__( 'Off-Canvas Menu', 'maxx-fitness' ),
			'id' => 'offcanvas_menu',
			'maxxfitness_type' => 'offcanvas',
		) );

}


/**
 * Call register sidebar.
 *
 * Because WordPress.org checker don't understand that we are using register_sidebar properly,
 * we have to add this useless call which only has to be declared once.
 *
 * @since 1.0.0
 *
 * @ignore
 */
add_action( 'widgets_init', 'maxxfitness_register_widget_area' );