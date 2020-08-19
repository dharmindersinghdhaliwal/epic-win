<?php
/**
 * Prepare and initialize the Beans framework.
 *
 * @package Initialize
 */


add_action( 'maxxfitness_init', 'maxxfitness_define_constants', -1 );

/**
 * Define constants.
 *
 * @ignore
 */
function maxxfitness_define_constants() {

	// Define version.
	define( 'maxxfitness_VERSION', '1.3.1' );

	// Define paths.
	if ( !defined( 'maxxfitness_THEME_PATH' ) )
		define( 'maxxfitness_THEME_PATH', wp_normalize_path( trailingslashit( get_template_directory() ) ) );

	define( 'maxxfitness_PATH', maxxfitness_THEME_PATH . 'lib/' );
	define( 'maxxfitness_API_PATH', maxxfitness_PATH . 'api/' );
	define( 'maxxfitness_ASSETS_PATH', maxxfitness_PATH . 'assets/' );
	define( 'maxxfitness_RENDER_PATH', maxxfitness_PATH . 'render/' );
	define( 'maxxfitness_TEMPLATES_PATH', maxxfitness_PATH . 'templates/' );
	define( 'maxxfitness_STRUCTURE_PATH', maxxfitness_TEMPLATES_PATH . 'structure/' );
	define( 'maxxfitness_FRAGMENTS_PATH', maxxfitness_TEMPLATES_PATH . 'fragments/' );

	// Define urls.
	if ( !defined( 'maxxfitness_THEME_URL' ) )
		define( 'maxxfitness_THEME_URL', trailingslashit( get_template_directory_uri() ) );

	define( 'maxxfitness_URL', maxxfitness_THEME_URL . 'lib/' );
	define( 'maxxfitness_API_URL', maxxfitness_URL . 'api/' );
	define( 'maxxfitness_ASSETS_URL', maxxfitness_URL . 'assets/' );
	define( 'maxxfitness_LESS_URL', maxxfitness_ASSETS_URL . 'less/' );
	define( 'maxxfitness_JS_URL', maxxfitness_ASSETS_URL . 'js/' );
	define( 'maxxfitness_IMAGE_URL', maxxfitness_ASSETS_URL . 'images/' );

	// Define admin paths.
	define( 'maxxfitness_ADMIN_PATH', maxxfitness_PATH . 'admin/' );

	// Define admin url.
	define( 'maxxfitness_ADMIN_URL', maxxfitness_URL . 'admin/' );
	define( 'maxxfitness_ADMIN_ASSETS_URL', maxxfitness_ADMIN_URL . 'assets/' );
	define( 'maxxfitness_ADMIN_JS_URL', maxxfitness_ADMIN_ASSETS_URL . 'js/' );

}


add_action( 'maxxfitness_init', 'maxxfitness_load_dependencies', -1 );

/**
 * Load dependencies.
 *
 * @ignore
 */
function maxxfitness_load_dependencies() {

	require_once( maxxfitness_API_PATH . 'init.php' );

	// Load the necessary Beans components.
	maxxfitness_load_api_components( array(
		'actions',
		'html',
		'term-meta',
		'post-meta',
		'image',
		'wp-customize',
		'compiler',
		'uikit',
		'template',
		'layout',
		'widget'
	) );

	// Add third party styles and scripts compiler support.
	maxxfitness_add_api_component_support( 'wp_styles_compiler' );
	maxxfitness_add_api_component_support( 'wp_scripts_compiler' );

	/**
	 * Fires after Beans API loads.
	 *
	 * @since 1.0.0
	 */
	do_action( 'maxxfitness_after_load_api' );

}


add_action( 'maxxfitness_init', 'maxxfitness_add_theme_support' );

/**
 * Add theme support.
 *
 * @ignore
 */
function maxxfitness_add_theme_support() {

	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-background' );
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	add_theme_support( 'custom-header', array(
		'width' => 2000,
		'height' => 500,
		'flex-height' => true,
		'flex-width' => true,
		'header-text' => false
	) );

	// Beans specific.
	add_theme_support( 'offcanvas-menu' );
	add_theme_support( 'beans-default-styling' );

}


add_action( 'maxxfitness_init', 'maxxfitness_includes' );

/**
 * Include framework files.
 *
 * @ignore
 */
function maxxfitness_includes() {

	// Include admin.
	if ( is_admin() ) {

		require_once( maxxfitness_ADMIN_PATH . 'options.php' );
		require_once( maxxfitness_ADMIN_PATH . 'updater.php' );

	}

	// Include assets.
	require_once( maxxfitness_ASSETS_PATH . 'assets.php' );

	// Include customizer.
	if ( is_customize_preview() )
		require_once( maxxfitness_ADMIN_PATH . 'wp-customize.php' );

	// Include renderers.
	require_once( maxxfitness_RENDER_PATH . 'template-parts.php' );
	require_once( maxxfitness_RENDER_PATH . 'fragments.php' );
	require_once( maxxfitness_RENDER_PATH . 'widget-area.php' );
	require_once( maxxfitness_RENDER_PATH . 'walker.php' );
	require_once( maxxfitness_RENDER_PATH . 'menu.php' );

}

/**
 * Fires before Beans loads.
 *
 * @since 1.0.0
 */
do_action( 'maxxfitness_before_init' );

	/**
	 * Load Beans framework.
	 *
	 * @since 1.0.0
	 */
	do_action( 'maxxfitness_init' );

/**
 * Fires after Beans loads.
 *
 * @since 1.0.0
 */
do_action( 'maxxfitness_after_init' );