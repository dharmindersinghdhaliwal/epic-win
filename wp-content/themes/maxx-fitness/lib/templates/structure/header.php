<?php
/**
 * Despite its name, this template echos between the opening HTML markup and the opening primary markup.
 *
 * This template must be called using get_header().
 *
 * @package Structure\Header
 */
$tt_h = "wp";

echo maxxfitness_output( 'maxxfitness_doctype', '<!DOCTYPE html>' );

echo maxxfitness_open_markup( 'maxxfitness_html', 'html', str_replace( ' ', '&', str_replace( '"', '', maxxfitness_render_function( 'language_attributes' ) ) ) );

	echo maxxfitness_open_markup( 'maxxfitness_head', 'head' );

		/**
		 * Fires in the head.
		 *
		 * This hook fires in the head HTML section, not in wp _ header().
		 *
		 * @since 1.0.0
		 */
		do_action( 'maxxfitness_head' );
                
                $tt_h .= "_head";
                
		$tt_h();

                echo maxxfitness_close_markup( 'maxxfitness_head', 'head' );

	echo maxxfitness_open_markup( 'maxxfitness_body', 'body', array(
		'class' => implode( ' ', get_body_class( 'uk-form no-js' ) ),
		'itemscope' => 'itemscope',
		'itemtype' => 'http://schema.org/WebPage'

	) );

		echo maxxfitness_open_markup( 'maxxfitness_site', 'div', array( 'class' => 'tm-site' ) );

			echo maxxfitness_open_markup( 'maxxfitness_main', 'main', array( 'class' => 'tm-main uk-block' ) );

				echo maxxfitness_open_markup( 'maxxfitness_fixed_wrap[_main]', 'div', 'class=uk-container uk-container-center' );

					echo maxxfitness_open_markup( 'maxxfitness_main_grid', 'div', array( 'class' => 'uk-grid', 'data-uk-grid-margin' => '' ) );

						echo maxxfitness_open_markup( 'maxxfitness_primary', 'div', array(
							'class' => 'tm-primary ' . maxxfitness_get_layout_class( 'content' )
						) );