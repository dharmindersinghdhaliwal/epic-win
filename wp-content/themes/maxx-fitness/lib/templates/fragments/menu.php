<?php
/**
 * Echo menu fragments.
 *
 * @package Fragments\Menu
 */

maxxfitness_add_smart_action( 'maxxfitness_header', 'maxxfitness_primary_menu', 15 );

/**
 * Echo primary menu.
 *
 * @since 1.0.0
 */
function maxxfitness_primary_menu() {

	$nav_visibility = current_theme_supports( 'offcanvas-menu' ) ? 'uk-visible-large' : '';

	echo maxxfitness_open_markup( 'maxxfitness_primary_menu', 'nav', array(
		'class' => 'tm-primary-menu uk-float-right uk-navbar',
		'role' => 'navigation',
		'itemscope' => 'itemscope',
		'itemtype' => 'http://schema.org/SiteNavigationElement'
	) );

		/**
		 * Filter the primary menu arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Nav menu arguments.
		 */
		$args = apply_filters( 'maxxfitness_primary_menu_args', array(
			'theme_location' => has_nav_menu( 'primary' ) ? 'primary' : '',
			'fallback_cb' => 'maxxfitness_no_menu_notice',
			'container' => '',
			'menu_class' => $nav_visibility, // Automatically escaped.
			'echo' => false,
			'maxxfitness_type' => 'navbar'
		) );

		// Navigation.
		echo maxxfitness_output( 'maxxfitness_primary_menu', wp_nav_menu( $args ) );

	echo maxxfitness_close_markup( 'maxxfitness_primary_menu', 'nav' );

}


maxxfitness_add_smart_action( 'maxxfitness_primary_menu_append_markup', 'maxxfitness_primary_menu_offcanvas_button', 5 );

/**
 * Echo primary menu offcanvas button.
 *
 * @since 1.0.0
 */
function maxxfitness_primary_menu_offcanvas_button() {

	if ( !current_theme_supports( 'offcanvas-menu' ) )
		return;

	echo maxxfitness_open_markup( 'maxxfitness_primary_menu_offcanvas_button', 'a', array(
		'href' => '#offcanvas_menu',
		'class' => 'uk-button uk-hidden-large',
		'data-uk-offcanvas' => ''
	) );

		echo maxxfitness_open_markup( 'maxxfitness_primary_menu_offcanvas_button_icon', 'i', array(
			'class' => 'uk-icon-navicon uk-margin-small-right',
		) );

		echo maxxfitness_close_markup( 'maxxfitness_primary_menu_offcanvas_button_icon', 'i' );

		echo maxxfitness_output( 'maxxfitness_offcanvas_menu_button', esc_html__( 'Menu', 'maxx-fitness' ) );

	echo maxxfitness_close_markup( 'maxxfitness_primary_menu_offcanvas_button', 'a' );

}


maxxfitness_add_smart_action( 'maxxfitness_widget_area_offcanvas_bar_offcanvas_menu_prepend_markup', 'maxxfitness_primary_offcanvas_menu' );

/**
 * Echo off-canvas primary menu.
 *
 * @since 1.0.0
 */
function maxxfitness_primary_offcanvas_menu() {

	if ( !current_theme_supports( 'offcanvas-menu' ) )
		return;

	echo maxxfitness_open_markup( 'maxxfitness_primary_offcanvas_menu', 'nav', array(
		'class' => 'tm-primary-offcanvas-menu uk-margin uk-margin-top',
		'role' => 'navigation',
	) );

		/**
		 * Filter the off-canvas primary menu arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Off-canvas nav menu arguments.
		 */
		$args = apply_filters( 'maxxfitness_primary_offcanvas_menu_args', array(
			'theme_location' => has_nav_menu( 'primary' ) ? 'primary' : '',
			'fallback_cb' => 'maxxfitness_no_menu_notice',
			'container' => '',
			'echo' => false,
			'maxxfitness_type' => 'offcanvas'
		) );

		echo maxxfitness_output( 'maxxfitness_primary_offcanvas_menu', wp_nav_menu( $args ) );

	echo maxxfitness_close_markup( 'maxxfitness_primary_offcanvas_menu', 'nav' );

}


/**
 * Echo no menu notice.
 *
 * @since 1.0.0
 */
function maxxfitness_no_menu_notice() {

	echo maxxfitness_open_markup( 'maxxfitness_no_menu_notice', 'p', array( 'class' => 'uk-alert uk-alert-warning' ) );

		echo maxxfitness_output( 'maxxfitness_no_menu_notice_text', esc_html__( 'Whoops, your site does not have a menu!', 'maxx-fitness' ) );

	echo maxxfitness_close_markup( 'maxxfitness_no_menu_notice', 'p' );

}