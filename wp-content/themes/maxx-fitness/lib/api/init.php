<?php
/**
 *
 * Load components.
 *
 * @ignore
 *
 * @package Beans
 */

// Stop here if the API was already loaded.
if ( defined( 'maxxfitness_API' ) )
	return;

// Declare Beans API.
define( 'maxxfitness_API', true );

// Mode.
if ( !defined( 'SCRIPT_DEBUG' ) )
	define( 'SCRIPT_DEBUG', false );

// Assets.
define( 'maxxfitness_MIN_CSS', SCRIPT_DEBUG ? '' : '.min' );
define( 'maxxfitness_MIN_JS', SCRIPT_DEBUG ? '' : '.min' );

// Path.
if ( !defined( 'maxxfitness_API_PATH' ) )
	define( 'maxxfitness_API_PATH', wp_normalize_path( trailingslashit( get_template_directory().'/lib/api/' ) ) );

define( 'maxxfitness_API_ADMIN_PATH', maxxfitness_API_PATH . 'admin/' );

// Load dependencies here as it is used further down.
require_once( maxxfitness_API_PATH . 'utilities/functions.php' );
require_once( maxxfitness_API_PATH . 'utilities/deprecated.php' );
require_once( maxxfitness_API_PATH . 'components.php' );

// Url.
if ( !defined( 'maxxfitness_API_URL' ) )
	define( 'maxxfitness_API_URL', maxxfitness_path_to_url( maxxfitness_API_PATH ) );

// Backwards compatibility constants.
define( 'maxxfitness_API_COMPONENTS_PATH', maxxfitness_API_PATH );
define( 'maxxfitness_API_COMPONENTS_ADMIN_PATH', maxxfitness_API_PATH . 'admin/' );
define( 'maxxfitness_API_COMPONENTS_URL', maxxfitness_API_URL );