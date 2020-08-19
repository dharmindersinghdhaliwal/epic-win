<?php
/**
 * Echo footer fragments.
 *
 * @package Fragments\Footer
 */

maxxfitness_add_smart_action( 'maxxfitness_footer', 'maxxfitness_footer_content' );

/**
 * Echo the footer content.
 *
 * @since 1.0.0
 */
function maxxfitness_footer_content() {

	echo maxxfitness_open_markup( 'maxxfitness_footer_credit', 'div', array( 'class' => 'uk-clearfix uk-text-small uk-text-muted' ) );

		echo maxxfitness_open_markup( 'maxxfitness_footer_credit_left', 'span', array(
			'class' => 'uk-align-medium-left uk-margin-small-bottom'
		) );

			echo maxxfitness_output( 'maxxfitness_footer_credit_text', sprintf(
				esc_html__( '&#x000A9; %1$s - %2$s. All rights reserved.', 'maxx-fitness' ),
				date( "Y" ),
				get_bloginfo( 'name' )
			) );

		echo maxxfitness_close_markup( 'maxxfitness_footer_credit_left', 'span' );

		$framework_link = maxxfitness_open_markup( 'maxxfitness_footer_credit_framework_link', 'a', array(
			'href' => 'http://torbara.com', // Automatically escaped.
		) );

			$framework_link .= maxxfitness_output( 'maxxfitness_footer_credit_framework_link_text', 'maxx-fitness' );

		$framework_link .= maxxfitness_close_markup( 'maxxfitness_footer_credit_framework_link', 'a' );

		echo maxxfitness_open_markup( 'maxxfitness_footer_credit_right', 'span', array(
			'class' => 'uk-align-medium-right uk-margin-bottom-remove'
		) );

			echo maxxfitness_output( 'maxxfitness_footer_credit_right_text', sprintf(
				esc_html__( '%1$s theme for WordPress.', 'maxx-fitness' ),
				$framework_link
			) );

		echo maxxfitness_close_markup( 'maxxfitness_footer_credit_right', 'span' );


	echo maxxfitness_close_markup( 'maxxfitness_footer_credit', 'div' );

}