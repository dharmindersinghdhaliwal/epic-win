<?php
/**
 * Extends WordPress Embed.
 *
 * @package Fragments\Embed
 */

// Filter.
maxxfitness_add_smart_action( 'embed_oembed_html', 'maxxfitness_embed_oembed' );

/**
 * Add markup to embed.
 *
 * @since 1.0.0
 *
 * @param string $html The embed HTML.
 *
 * @return string The modified embed HTML.
 */
function maxxfitness_embed_oembed( $html ) {

	$output = maxxfitness_open_markup( 'maxxfitness_embed_oembed', 'div', 'class=tm-oembed' );

		$output .= $html;

	$output .= maxxfitness_close_markup( 'maxxfitness_embed_oembed', 'div' );

	return $output;

}