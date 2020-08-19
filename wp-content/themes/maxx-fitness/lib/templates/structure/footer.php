<?php
/**
 * Despite its name, this template echos between the closing primary markup and the closing HTML markup.
 *
 * This template must be called using get_footer().
 *
 * @package Structure\Footer
 */

						echo maxxfitness_close_markup( 'maxxfitness_primary', 'div' );

					echo maxxfitness_close_markup( 'maxxfitness_main_grid', 'div' );

				echo maxxfitness_close_markup( 'maxxfitness_fixed_wrap[_main]', 'div' );

			echo maxxfitness_close_markup( 'maxxfitness_main', 'main' );

		echo maxxfitness_close_markup( 'maxxfitness_site', 'div' );

		wp_footer();

	echo maxxfitness_close_markup( 'maxxfitness_body', 'body' );

echo maxxfitness_close_markup( 'maxxfitness_html', 'html' );