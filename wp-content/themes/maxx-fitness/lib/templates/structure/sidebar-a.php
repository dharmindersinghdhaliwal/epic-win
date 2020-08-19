<?php
/**
 * Echo the primary sidebar structural markup. It also calls the primary sidebar action hooks.
 *
 * @package Structure\Primary_Sidebar
 */

echo maxxfitness_open_markup( 'maxxfitness_sidebar_primary', 'aside', array(
	'class' => 'tm-secondary ' . maxxfitness_get_layout_class( 'sidebar-a' ), // Automatically escaped.
	'role' => 'complementary',
	'itemscope' => 'itemscope',
	'itemtype' => 'http://schema.org/WPSideBar'
) );

	/**
	 * Fires in the primary sidebar.
	 *
	 * @since 1.0.0
	 */
	do_action( 'maxxfitness_sidebar_primary' );

echo maxxfitness_close_markup( 'maxxfitness_sidebar_primary', 'aside' );