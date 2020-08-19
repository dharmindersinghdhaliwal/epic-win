<?php
/**
 * Echo widget fragments.
 *
 * @package Fragments\Widget
 */

maxxfitness_add_smart_action( 'maxxfitness_widget', 'maxxfitness_widget_badge', 5 );

/**
 * Echo widget badge.
 *
 * @since 1.0.0
 */
function maxxfitness_widget_badge() {

	if ( !maxxfitness_get_widget( 'badge' ) )
		return;

	echo maxxfitness_open_markup( 'maxxfitness_widget_badge' . maxxfitness_tt_widget_subfilters(), 'div', 'class=uk-panel-badge uk-badge' );

		echo maxxfitness_widget_shortcodes( maxxfitness_get_widget( 'badge_content' ) );

	echo maxxfitness_close_markup( 'maxxfitness_widget_badge' . maxxfitness_tt_widget_subfilters(), 'div' );

}


maxxfitness_add_smart_action( 'maxxfitness_widget', 'maxxfitness_widget_title' );

/**
 * Echo widget title.
 *
 * @since 1.0.0
 */
function maxxfitness_widget_title() {

	if ( !( $title = maxxfitness_get_widget( 'title' ) ) || !maxxfitness_get_widget( 'show_title' ) )
		return;

	echo maxxfitness_open_markup( 'maxxfitness_widget_title' . maxxfitness_tt_widget_subfilters(), 'h3', 'class=uk-panel-title' );

		echo maxxfitness_output( 'maxxfitness_widget_title_text', $title );

	echo maxxfitness_close_markup( 'maxxfitness_widget_title' . maxxfitness_tt_widget_subfilters(), 'h3' );

}


maxxfitness_add_smart_action( 'maxxfitness_widget', 'maxxfitness_widget_content', 15 );

/**
 * Echo widget content.
 *
 * @since 1.0.0
 */
function maxxfitness_widget_content() {

	echo maxxfitness_open_markup( 'maxxfitness_widget_content' . maxxfitness_tt_widget_subfilters(), 'div' );

		echo maxxfitness_output( 'maxxfitness_widget_content' . maxxfitness_tt_widget_subfilters(), maxxfitness_get_widget( 'content' ) );

	echo maxxfitness_close_markup( 'maxxfitness_widget_content' . maxxfitness_tt_widget_subfilters(), 'div' );

}


maxxfitness_add_smart_action( 'maxxfitness_no_widget', 'maxxfitness_no_widget' );

/**
 * Echo no widget content.
 *
 * @since 1.0.0
 */
function maxxfitness_no_widget() {

	// Only apply this notice to sidebar-a and sidebar_secondary.
	if ( !in_array( maxxfitness_get_widget_area( 'id' ), array( 'sidebar-a', 'sidebar_secondary' ) ) )
		return;

	echo maxxfitness_open_markup( 'maxxfitness_no_widget_notice', 'p', array( 'class' => 'uk-alert uk-alert-warning' ) );

		echo maxxfitness_output( 'maxxfitness_no_widget_notice_text', sprintf( esc_html__( '%s does not have any widget assigned!', 'maxx-fitness' ), maxxfitness_get_widget_area( 'name' ) ) );

	echo maxxfitness_close_markup( 'maxxfitness_no_widget_notice', 'p' );

}


maxxfitness_add_filter( 'maxxfitness_widget_content_rss_output', 'maxxfitness_widget_rss_content' );

/**
 * Modify RSS widget content.
 *
 * @since 1.0.0
 *
 * @return The RSS widget content.
 */
function maxxfitness_widget_rss_content() {

	$options = maxxfitness_get_widget( 'options' );

	return '<p><a class="uk-button" href="' . maxxfitness_get( 'url', $options ) . '" target="_blank">' . esc_html__( 'Read feed', 'maxx-fitness' ) . '</a><p>';

}


maxxfitness_add_filter( 'maxxfitness_widget_content_attributes', 'maxxfitness_modify_widget_content_attributes' );

/**
 * Modify core widgets content attributes, so they use the default UIKit styling.
 *
 * @since 1.0.0
 *
 * @param array $attributes The current widget attributes.
 *
 * @return array The modified widget attributes.
 */
function maxxfitness_modify_widget_content_attributes( $attributes ) {

	$type = maxxfitness_get_widget( 'type' );

	$target = array(
		'archives',
		'categories',
		'links',
		'meta',
		'pages',
		'recent-posts',
		'recent-comments'
	);

	$current_class = isset( $attributes['class'] ) ? $attributes['class'] . ' ' : '';

	if ( in_array( maxxfitness_get_widget( 'type' ), $target ) )
		$attributes['class'] = $current_class . 'uk-list'; // Automatically escaped.

	if ( $type == 'calendar' )
		$attributes['class'] = $current_class . 'uk-table uk-table-condensed'; // Automatically escaped.

	return $attributes;

}


maxxfitness_add_filter( 'maxxfitness_widget_content_categories_output', 'maxxfitness_modify_widget_count' );
maxxfitness_add_filter( 'maxxfitness_widget_content_archives_output', 'maxxfitness_modify_widget_count' );

/**
 * Modify widget count.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function maxxfitness_modify_widget_count( $content ) {

	$count = maxxfitness_output( 'maxxfitness_widget_count', '$1' );

	if ( maxxfitness_get( 'dropdown', maxxfitness_get_widget( 'options' ) ) == true ) {

		$output = $count;

	} else {

		$output = maxxfitness_open_markup( 'maxxfitness_widget_count', 'span', 'class=tm-count' );

			$output .= $count;

		$output .= maxxfitness_close_markup( 'maxxfitness_widget_count', 'span' );

	}

	// Keep closing tag to avoid overwriting the inline JavaScript.
	return preg_replace( '#>((\s|&nbsp;)\((.*)\))#', '>' . $output, $content );

}


maxxfitness_add_filter( 'maxxfitness_widget_content_categories_output', 'maxxfitness_remove_widget_dropdown_label' );
maxxfitness_add_filter( 'maxxfitness_widget_content_archives_output', 'maxxfitness_remove_widget_dropdown_label' );

/**
 * Modify widget dropdown label.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function maxxfitness_remove_widget_dropdown_label( $content ) {

	return preg_replace( '#<label([^>]*)class="screen-reader-text"(.*?)>(.*?)</label>#', '', $content ) ;

}