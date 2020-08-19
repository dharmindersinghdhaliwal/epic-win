<?php
/**
 * Echo header fragments.
 *
 * @package Fragments\Header
 */

maxxfitness_add_smart_action( 'maxxfitness_head', 'maxxfitness_head_meta', 0 );

/**
 * Echo head meta.
 *
 * @since 1.0.0
 */
function maxxfitness_head_meta() {

	echo '<meta charset="' . get_bloginfo( 'charset' ) . '" />' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

}


maxxfitness_add_smart_action( 'wp_head', 'maxxfitness_head_pingback' );

/**
 * Echo head pingback.
 *
 * @since 1.0.0
 */
function maxxfitness_head_pingback() {

	echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '">' . "\n";

}


maxxfitness_add_smart_action( 'wp_head', 'maxxfitness_header_image' );

/**
 * Print the header image css inline in the header.
 *
 * @since 1.0.0
 */
function maxxfitness_header_image() {

	if ( !current_theme_supports( 'custom-header' ) || !( $header_image = get_header_image() ) || empty( $header_image ) )
		return;
}


maxxfitness_add_smart_action( 'maxxfitness_header', 'maxxfitness_site_branding' );

/**
 * Echo header site branding.
 *
 * @since 1.0.0
 */
function maxxfitness_site_branding() {

	echo maxxfitness_open_markup( 'maxxfitness_site_branding', 'div', array(
		'class' => 'tm-site-branding uk-float-left' . ( !get_bloginfo( 'description' ) ? ' uk-margin-small-top' : null ),
	) );

		echo maxxfitness_open_markup( 'maxxfitness_site_title_link', 'a', array(
			'href' => home_url('/'), // Automatically escaped.
			'rel' => 'home',
			'itemprop' => 'headline'
		) );

			if ( $logo = get_theme_mod( 'maxxfitness_logo_image', false ) )
				echo maxxfitness_selfclose_markup( 'maxxfitness_logo_image', 'img', array(
					'class' => 'tm-logo',
					'src' => $logo, // Automatically escaped.
					'alt' => get_bloginfo( 'name' ), // Automatically escaped.
				) );
			else
				echo maxxfitness_output( 'maxxfitness_site_title_text', get_bloginfo( 'name' ) );

		echo maxxfitness_close_markup( 'maxxfitness_site_title_link', 'a' );

	echo maxxfitness_close_markup( 'maxxfitness_site_branding', 'div' );

}


maxxfitness_add_smart_action( 'maxxfitness_site_branding_append_markup', 'maxxfitness_site_title_tag' );

/**
 * Echo header site title tag.
 *
 * @since 1.0.0
 */
function maxxfitness_site_title_tag() {

	// Stop here if there isn't a description.
	if ( !$description = get_bloginfo( 'description' ) )
		return;

	echo maxxfitness_open_markup( 'maxxfitness_site_title_tag', 'span', array(
		'class' => 'tm-site-title-tag uk-text-small uk-text-muted uk-display-block',
		'itemprop' => 'description'
	) );

		echo maxxfitness_output( 'maxxfitness_site_title_tag_text', $description );

	echo maxxfitness_close_markup( 'maxxfitness_site_title_tag', 'span' );

}