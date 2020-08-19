<?php
/**
 * Echo the structural markup for the main content. It also calls the content action hooks.
 *
 * @package Structure\Content
 */

$content_attributes = array(
	'class' => 'tm-content',
	'role' => 'main',
	'itemprop' => 'mainEntityOfPage'
);

// Blog specific attributes.
if ( is_home() || is_page_template( 'page_blog.php' ) || is_singular( 'post' ) || is_archive() ) {

	$content_attributes['itemscope'] = 'itemscope'; // Automatically escaped.
	$content_attributes['itemtype']  = 'http://schema.org/Blog'; // Automatically escaped.

}

// Blog specific attributes.
if ( is_search() ) {

	$content_attributes['itemscope'] = 'itemscope'; // Automatically escaped.
	$content_attributes['itemtype'] = 'http://schema.org/SearchResultsPage'; // Automatically escaped.

}

echo maxxfitness_open_markup( 'maxxfitness_content', 'div', $content_attributes );

	/**
	 * Fires in the main content.
	 *
	 * @since 1.0.0
	 */
	do_action( 'maxxfitness_content' );

echo maxxfitness_close_markup( 'maxxfitness_content', 'div' );
