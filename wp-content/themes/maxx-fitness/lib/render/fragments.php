<?php
/**
 * Loads Beans fragments.
 *
 * @package Render\Fragments
 */

// Filter.
maxxfitness_add_smart_action( 'template_redirect', 'maxxfitness_load_global_fragments', 1 );

/**
 * Load global fragments and dynamic views.
 *
 * @since 1.0.0
 *
 * @param string $template The template filename.
 *
 * @return string The template filename.
 */
function maxxfitness_load_global_fragments() {

	maxxfitness_load_fragment_file( 'breadcrumb' );
	maxxfitness_load_fragment_file( 'footer' );
	maxxfitness_load_fragment_file( 'header' );
	maxxfitness_load_fragment_file( 'menu' );
	maxxfitness_load_fragment_file( 'post-shortcodes' );
	maxxfitness_load_fragment_file( 'post' );
	maxxfitness_load_fragment_file( 'widget-area' );
	maxxfitness_load_fragment_file( 'embed' );
	maxxfitness_load_fragment_file( 'deprecated' );

}


// Filter.
maxxfitness_add_smart_action( 'comments_template', 'maxxfitness_load_comments_fragment' );

/**
 * Load comments fragments.
 *
 * The comments fragments only loads if comments are active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @param string $template The template filename.
 *
 * @return string The template filename.
 */
function maxxfitness_load_comments_fragment( $template ) {

	if ( empty( $template ) )
		return;

	maxxfitness_load_fragment_file( 'comments' );

	return $template;

}


maxxfitness_add_smart_action( 'dynamic_sidebar_before', 'maxxfitness_load_widget_fragment', -1 );

/**
 * Load widget fragments.
 *
 * The widget fragments only loads if a sidebar is active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function maxxfitness_load_widget_fragment() {

	return maxxfitness_load_fragment_file( 'widget' );

}


maxxfitness_add_smart_action( 'pre_get_search_form', 'maxxfitness_load_search_form_fragment' );

/**
 * Load search form fragments.
 *
 * The search form fragments only loads if search is active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function maxxfitness_load_search_form_fragment() {

	return maxxfitness_load_fragment_file( 'searchform' );

}