<?php
/**
 * The Beans Layout API controls what and how Beans main section elements are displayed.
 *
 * @package API\Layout
 */


/**
 * Get the default layout.
 *
 * @since 1.0.0
 *
 * @return string The defautl layout.
 */
function maxxfitness_get_default_layout() {

	$default_layout = maxxfitness_has_widget_area( 'sidebar-a' ) ? 'c_sp' : 'c';

	/**
	 * Filter the default layout id.
	 *
	 * The default id is set to "c_sp" (content with sidebar primary). If the sidebar primary is deregistered, it fallback
	 * to "c" (content only).
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout The default layout id.
	 */
	return apply_filters( 'maxxfitness_default_layout', $default_layout );

}


/**
 * Get the current layout.
 *
 * This function return the current layout according the the view it is called from.
 *
 * @since 1.0.0
 *
 * @return bool Layout, false if no layout found.
 */
function maxxfitness_get_layout() {

	if ( is_singular() )
		$layout = maxxfitness_get_post_meta( 'maxxfitness_layout' );

	elseif ( is_home() )
		$layout = maxxfitness_get_post_meta( 'maxxfitness_layout', false, get_option( 'page_for_posts' ) );

	elseif ( is_category() || is_tag() || is_tax() )
		$layout = maxxfitness_get_term_meta( 'maxxfitness_layout' );

	// Fallback onto the global theme layout option if value is false or default_fallback.
	if ( !isset( $layout ) || !$layout || $layout === 'default_fallback' )
		$layout = get_theme_mod( 'maxxfitness_layout', maxxfitness_get_default_layout() );

	/**
	 * Filter the layout id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout The layout id.
	 */
	return apply_filters( 'maxxfitness_layout', $layout );

}


/**
 * Get the current layout.
 *
 * This function generate the layout class base on the current layout.
 *
 * @since 1.0.0
 *
 * @param string $id The searched layout section ID.
 *
 * @return bool Layout class, false if no layout class found.
 */
function maxxfitness_get_layout_class( $id ) {

	/**
	 * Filter the arguments used to define the layout grid.
	 *
	 * The content number of columns are automatically calculated based on the grid, sidebar primary and
	 * sidebar secondary columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type int    $grid              Total number of columns the grid contains. Default 4.
	 *     @type int    $sidebar_primary   The number of columns the sidebar primary takes. Default 1.
	 *     @type int    $sidebar_secondary The number of columns the sidebar secondary takes. Default 1.
	 *     @type string $breakpoint        The UIkit grid breakpoint which may be set to 'small', 'medium' or 'large'. Default 'medium'.
	 * }
	 */
	$args = apply_filters( 'maxxfitness_layout_grid_settings', array(
		'grid' => 4,
		'sidebar-a' => 1,
		'sidebar_secondary' => 1,
		'breakpoint' => 'medium'
	) );

	$g = maxxfitness_get( 'grid', $args ); // $g stands for grid.
	$c = $g; // $c stands for content. Same value as grid by default
	$sp = maxxfitness_get( 'sidebar-a', $args ); // $sp stands for sidebar primary.
	$ss = maxxfitness_get( 'sidebar_secondary', $args ); // $ss stands for 'sidebar secondary.
	$prefix = 'uk-width-' . maxxfitness_get( 'breakpoint', $args, 'medium' );

	$classes = array();

	switch ( $layout = maxxfitness_get_layout() ) {

		case 'c':

			$classes['content'] = "$prefix-$c-$g";

			break;

		default:

			$classes['content'] = "$prefix-$c-$g";

	}

	// Add sidebar primary layouts if the primary widget area is registered.
	if ( $has_primary = maxxfitness_has_widget_area( 'sidebar-a' ) ) {

		switch ( $layout ) {

			case 'c_sp':

				$c = $g - $sp;

				$classes['content'] = "$prefix-$c-$g";
				$classes['sidebar-a'] = "$prefix-$sp-$g";

				break;

			case 'sp_c':

				$c = $g - $sp;

				$classes['content'] = "$prefix-$c-$g uk-push-$sp-$g";
				$classes['sidebar-a'] = "$prefix-$sp-$g uk-pull-$c-$g";

				break;

		}

	}

	// Add sidebar secondary layouts if the primary and secondary widget area are registered.
	if ( $has_primary && maxxfitness_has_widget_area( 'sidebar_secondary' ) ) {

		switch ( $layout ) {

			case 'c_ss':

				$c = $g - $sp;

				$classes['content'] = "$prefix-$c-$g";
				$classes['sidebar_secondary'] = "$prefix-$sp-$g";

				break;

			case 'c_sp_ss':

				$c = $g - ( $sp + $ss );

				$classes['content'] = "$prefix-$c-$g";
				$classes['sidebar-a'] = "$prefix-$sp-$g";
				$classes['sidebar_secondary'] = "$prefix-$ss-$g";

				break;

			case 'ss_c':

				$c = $g - $sp;

				$classes['content'] = "$prefix-$c-$g uk-push-$sp-$g";
				$classes['sidebar_secondary'] = "$prefix-$sp-$g uk-pull-$c-$g";

				break;

			case 'sp_ss_c':

				$c = $g - ( $sp + $ss );
				$push_content = $sp + $ss;

				$classes['content'] = "$prefix-$c-$g uk-push-$push_content-$g";
				$classes['sidebar-a'] = "$prefix-$sp-$g uk-pull-$c-$g";
				$classes['sidebar_secondary'] = "$prefix-$ss-$g uk-pull-$c-$g";

				break;

			case 'sp_c_ss':

				$c = $g - ( $sp + $ss );

				$classes['content'] = "$prefix-$c-$g uk-push-$sp-$g";
				$classes['sidebar-a'] = "$prefix-$sp-$g uk-pull-$c-$g";
				$classes['sidebar_secondary'] = "$prefix-$ss-$g";

				break;

		}

	}

	/**
	 * Filter the layout class.
	 *
	 * The dynamic portion of the hook name refers to the searched layout section ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout The layout class.
	 */
	return apply_filters( "maxxfitness_layout_class_$id", maxxfitness_get( $id, $classes ) );

}


/**
 * Generate layout elements used by Beans 'imageradio' option type.
 *
 * Added layout should contain a unique ID as the array key and a URL path to its related image
 * as the array value.
 *
 * @since 1.0.0
 *
 * @param bool $add_default Optional. Whether the 'default_fallback' element is added or not.
 *
 * @return array Layouts ready for Beans 'imageradio' option type.
 */
function maxxfitness_get_layouts_for_options( $add_default = false ) {

	$base = maxxfitness_ADMIN_ASSETS_URL . 'images/layouts/';

	$layouts = array(
		'c' =>  $base . 'c.png'
	);

	// Add sidebar primary layouts if the primary widget area is registered.
	if ( $has_primary = maxxfitness_has_widget_area( 'sidebar-a' ) ) {

		$layouts['c_sp'] = $base . 'cs.png';
		$layouts['sp_c'] = $base . 'sc.png';

	}

	// Add sidebar secondary layouts if the primary and secondary widget area are registered.
	if ( $has_primary && maxxfitness_has_widget_area( 'sidebar_secondary' ) ) {

		$layouts['c_sp_ss'] = $base . 'css.png';
		$layouts['sp_ss_c'] = $base . 'ssc.png';
		$layouts['sp_c_ss'] = $base . 'scs.png';

	}

	/**
	 * Filter the layouts.
	 *
	 * - $c stands for content.
	 * - $sp stands for sidebar primary.
	 * - $ss stands for 'sidebar secondary.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args An array of layouts.
	 */
	$layouts = apply_filters( 'maxxfitness_layouts', $layouts );

	if ( $add_default )
		$layouts = array_merge( array( 'default_fallback' => sprintf(
			esc_html__( 'Use Default Layout (%s)',  'maxx-fitness' ),
			'<a href="' . admin_url( 'customize.php?autofocus[control]=maxxfitness_layout' ) . '">' . _x( 'Modify', 'Default layout', 'maxx-fitness' ) . '</a>'
		) ), $layouts );

	return $layouts;

}