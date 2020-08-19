<?php
/**
 * Modify the search from.
 *
 * @package Fragments\Search_Form
 */

// Filter.
maxxfitness_add_smart_action( 'get_search_form', 'maxxfitness_search_form' );

/**
 * Modify the search form.
 *
 * @since 1.0.0
 *
 * @return string The form.
 */
function maxxfitness_search_form() {

	$output = maxxfitness_open_markup( 'maxxfitness_search_form', 'form', array(
		'class' => 'uk-form uk-form-icon uk-form-icon-flip uk-width-1-1',
		'method' => 'get',
		'action' => esc_url( home_url( '/' ) ),
		'role' => 'search'
	) );

		$output .= maxxfitness_selfclose_markup( 'maxxfitness_search_form_input', 'input', array(
			'class' => 'uk-width-1-1',
			'type' => 'search',
			'placeholder' => esc_html__( 'Search', 'maxx-fitness' ), // Automatically escaped.
			'value' => esc_attr( get_search_query() ),
			'name' => 's'
		) );

		$output .= maxxfitness_open_markup( 'maxxfitness_search_form_input_icon', 'i', 'class=uk-icon-search' );

		$output .= maxxfitness_close_markup( 'maxxfitness_search_form_input_icon', 'i' );

	$output .= maxxfitness_close_markup( 'maxxfitness_search_form', 'form' );

	return $output;

}