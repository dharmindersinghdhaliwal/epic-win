<?php
/**
 * Add Beans assets.
 *
 * @package Assets
 */

maxxfitness_add_smart_action( 'maxxfitness_uikit_enqueue_scripts', 'maxxfitness_enqueue_uikit_components', 5 );

/**
 * Enqueue UIKit components and Beans style.
 *
 * Beans style is enqueued with the UIKit components to have access to UIKit LESS variables.
 *
 * @since 1.0.0
 */
function maxxfitness_enqueue_uikit_components() {

	$core = array(
		'base',
		'block',
		'grid',
		'article',
		'comment',
		'panel',
		'nav',
		'navbar',
		'subnav',
		'table',
		'breadcrumb',
		'pagination',
		'list',
		'form',
		'button',
		'badge',
		'alert',
		'dropdown',
		'offcanvas',
		'text',
		'utility',
		'icon'
	);

	maxxfitness_uikit_enqueue_components( $core, 'core', false );

	// Include uikit default theme.
	maxxfitness_uikit_enqueue_theme( 'default' );

	// Enqueue uikit overwrite theme folder.
	maxxfitness_uikit_enqueue_theme( 'beans', maxxfitness_ASSETS_PATH . 'less/uikit-overwrite' );

	// Add the theme style as a uikit fragment to have access to all the variables.
	maxxfitness_compiler_add_fragment( 'uikit', maxxfitness_ASSETS_PATH . 'less/style.less', 'less' );

	// Add the theme default style as a uikit fragment only if the theme supports it.
	if ( current_theme_supports( 'beans-default-styling' ) )
		maxxfitness_compiler_add_fragment( 'uikit', maxxfitness_ASSETS_PATH . 'less/default.less', 'less' );

}


maxxfitness_add_smart_action( 'wp_enqueue_scripts', 'maxxfitness_enqueue_assets', 5 );

/**
 * Enqueue Beans assets.
 *
 * @since 1.0.0
 */
function maxxfitness_enqueue_assets() {

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

}


maxxfitness_add_smart_action( 'after_setup_theme', 'maxxfitness_add_editor_assets' );

/**
 * Add Beans editor assets.
 *
 * @since 1.2.5
 */
function maxxfitness_add_editor_assets() {

	add_editor_style( maxxfitness_ASSETS_URL . 'css/editor' . maxxfitness_MIN_CSS . '.css' );

}