<?php
/**
 * Echo the widget area and widget loop structural markup. It also calls the widget area and widget loop
 * action hooks.
 *
 * @package Structure\Widget_Area
 */

// This includes everything added to wp hooks before the widgets.
echo maxxfitness_get_widget_area( 'before_widgets' );

	if ( maxxfitness_get_widget_area( 'maxxfitness_type' ) == 'grid' )
		echo maxxfitness_open_markup( 'maxxfitness_widget_area_grid' . maxxfitness_tt_widget_area_subfilters(), 'div', array( 'class' => 'uk-grid', 'data-uk-grid-margin' => '' ) );

	if ( maxxfitness_get_widget_area( 'maxxfitness_type' ) == 'offcanvas' ) {

		echo maxxfitness_open_markup( 'maxxfitness_widget_area_offcanvas_wrap' . maxxfitness_tt_widget_area_subfilters(), 'div', array(
			'id' => maxxfitness_get_widget_area( 'id' ), // Automatically escaped.
			'class' => 'uk-offcanvas'
		) );

			echo maxxfitness_open_markup( 'maxxfitness_widget_area_offcanvas_bar' . maxxfitness_tt_widget_area_subfilters(), 'div', array( 'class' => 'uk-offcanvas-bar' ) );

	}

		// Widgets.
		if ( maxxfitness_have_widgets() ) :

			/**
			 * Fires before widgets loop.
			 *
			 * This hook only fires if widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'maxxfitness_before_widgets_loop' );

				while ( maxxfitness_have_widgets() ) : maxxfitness_setup_widget();

					if ( maxxfitness_get_widget_area( 'maxxfitness_type' ) == 'grid' )
						echo maxxfitness_open_markup( 'maxxfitness_widget_grid' . maxxfitness_tt_widget_subfilters(), 'div', maxxfitness_widget_shortcodes( 'class=uk-width-medium-1-{count}' ) );

						echo maxxfitness_open_markup( 'maxxfitness_widget_panel' . maxxfitness_tt_widget_subfilters(), 'div', maxxfitness_widget_shortcodes( 'class=tm-widget uk-panel widget_{type} {id}' ) );

							/**
							 * Fires in each widget panel structural HTML.
							 *
							 * @since 1.0.0
							 */
							do_action( 'maxxfitness_widget' );

						echo maxxfitness_close_markup( 'maxxfitness_widget_panel' . maxxfitness_tt_widget_subfilters(), 'div' );

					if ( maxxfitness_get_widget_area( 'maxxfitness_type' ) == 'grid' )
						echo maxxfitness_close_markup( 'maxxfitness_widget_grid' . maxxfitness_tt_widget_subfilters(), 'div' );

				endwhile;

			/**
			 * Fires after the widgets loop.
			 *
			 * This hook only fires if widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'maxxfitness_after_widgets_loop' );

		else :

			/**
			 * Fires if no widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'maxxfitness_no_widget' );

		endif;

	if ( maxxfitness_get_widget_area( 'maxxfitness_type' ) == 'offcanvas' ) {

			echo maxxfitness_close_markup( 'maxxfitness_widget_area_offcanvas_bar' . maxxfitness_tt_widget_area_subfilters(), 'div' );

		echo maxxfitness_close_markup( 'maxxfitness_widget_area_offcanvas_wrap' . maxxfitness_tt_widget_area_subfilters(), 'div' );

	}

	if ( maxxfitness_get_widget_area( 'maxxfitness_type' ) == 'grid' )
		echo maxxfitness_close_markup( 'maxxfitness_widget_area_grid' . maxxfitness_tt_widget_area_subfilters(), 'div' );

// This includes everything added to wp hooks after the widgets.
echo maxxfitness_get_widget_area( 'after_widgets' );