<?php
/**
 * Loads Beans's template parts.
 *
 * The templates parts contain the structural markup and hooks to which the fragments are attached.
 *
 * @package Render\Template_Parts
 */

maxxfitness_add_smart_action( 'maxxfitness_load_document', 'maxxfitness_header_template', 5 );

/**
 * Echo header template part.
 *
 * @since 1.0.0
 */
function maxxfitness_header_template() {

	get_header();

}


maxxfitness_add_smart_action( 'maxxfitness_site_prepend_markup', 'maxxfitness_header_partial_template' );

/**
 * Echo header partial template part.
 *
 * @since 1.3.0
 */
function maxxfitness_header_partial_template() {

	// Allow overwrite.
	if ( locate_template( 'header-partial.php', true, false ) != '' )
		return;

	require( maxxfitness_STRUCTURE_PATH . 'header-partial.php' );

}


maxxfitness_add_smart_action( 'maxxfitness_load_document', 'maxxfitness_content_template' );

/**
 * Echo main content template part.
 *
 * @since 1.0.0
 */
function maxxfitness_content_template() {

	// Allow overwrite.
	if ( locate_template( 'content.php', true ) != '' )
		return;

	require_once( maxxfitness_STRUCTURE_PATH . 'content.php' );

}


maxxfitness_add_smart_action( 'maxxfitness_content', 'maxxfitness_loop_template' );

/**
 * Echo loop template part.
 *
 * @since 1.0.0
 *
 * @param string $id Optional. The loop ID is used to filter the loop WP_Query arguments.
 */
function maxxfitness_loop_template( $id = false ) {

	// Set default loop id.
	if ( !$id )
		$id = 'main';

	// Only run new query if a filter is set.
	if ( $_has_filter = maxxfitness_has_filters( "maxxfitness_loop_query_args[_{$id}]" ) ) {

		global $wp_query;

		/**
		 * Filter the beans loop query. This can be used for custom queries.
		 *
		 * @since 1.0.0
		 */
		$args = maxxfitness_apply_filters( "maxxfitness_loop_query_args[_{$id}]", false );
		$wp_query = new WP_Query( $args );

	}

	// Allow overwrite. Require the default loop.php if not overwrite is found.
	if ( locate_template( 'loop.php', true, false ) == '' )
		require( maxxfitness_STRUCTURE_PATH . 'loop.php' );

	// Only reset the query if a filter is set.
	if ( $_has_filter )
		wp_reset_query();

}


maxxfitness_add_smart_action( 'maxxfitness_post_after_markup', 'maxxfitness_comments_template', 15 );

/**
 * Echo comments template part.
 *
 * The comments template part only loads if comments are active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 */
function maxxfitness_comments_template() {

	global $post;

	if ( !( comments_open() || get_comments_number() ) || !post_type_supports( maxxfitness_get( 'post_type', $post ), 'comments' ) )
		return;

	comments_template();

}


maxxfitness_add_smart_action( 'maxxfitness_comment', 'maxxfitness_comment_template' );

/**
 * Echo comment template part.
 *
 * @since 1.0.0
 */
function maxxfitness_comment_template() {

	// Allow overwrite.
	if ( locate_template( 'comment.php', true, false ) != '' )
		return;

	require( maxxfitness_STRUCTURE_PATH . 'comment.php' );

}


maxxfitness_add_smart_action( 'maxxfitness_widget_area', 'maxxfitness_widget_area_template' );

/**
 * Echo widget area template part.
 *
 * @since 1.0.0
 */
function maxxfitness_widget_area_template() {

	// Allow overwrite.
	if ( locate_template( 'widget-area.php', true, false ) != '' )
		return;

	require( maxxfitness_STRUCTURE_PATH . 'widget-area.php' );

}


maxxfitness_add_smart_action( 'maxxfitness_primary_after_markup', 'maxxfitness_sidebar_primary_template' );

/**
 * Echo primary sidebar template part.
 *
 * The primary sidebar template part only loads if the layout set includes it, thus prevent unnecessary memory usage.
 *
 * @since 1.0.0
 */
function maxxfitness_sidebar_primary_template() {

	if ( stripos( maxxfitness_get_layout(), 'sp' ) === false || !maxxfitness_has_widget_area( 'sidebar-a' ) )
		return;

	get_sidebar( 'a' );

}


maxxfitness_add_smart_action( 'maxxfitness_primary_after_markup', 'maxxfitness_sidebar_secondary_template' );

/**
 * Echo secondary sidebar template part.
 *
 * The secondary sidebar template part only loads if the layout set includes it, thus prevent unnecessary memory usage.
 *
 * @since 1.0.0
 */
function maxxfitness_sidebar_secondary_template() {

	if ( stripos( maxxfitness_get_layout(), 'ss' ) === false || !maxxfitness_has_widget_area( 'sidebar_secondary' ) )
		return;

	get_sidebar( 'secondary' );

}


maxxfitness_add_smart_action( 'maxxfitness_site_append_markup', 'maxxfitness_footer_partial_template' );

/**
 * Echo footer partial template part.
 *
 * @since 1.3.0
 */
function maxxfitness_footer_partial_template() {

	// Allow overwrite.
	if ( locate_template( 'footer-partial.php', true, false ) != '' )
		return;

	require( maxxfitness_STRUCTURE_PATH . 'footer-partial.php' );

}


maxxfitness_add_smart_action( 'maxxfitness_load_document', 'maxxfitness_footer_template' );

/**
 * Echo footer template part.
 *
 * @since 1.0.0
 */
function maxxfitness_footer_template() {

	get_footer();

}


/**
 * Set the content width based on Beans default layout.
 *
 * This is mainly added to align to WordPress.org requirements.
 *
 * @since 1.2.0
 *
 * @ignore
 */
if ( !isset( $content_width ) )
	$content_width = 800;