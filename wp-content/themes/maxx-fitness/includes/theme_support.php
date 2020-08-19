<?php
/**
 * 
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara?ref=torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 * @support      support@torbara.com
 * 
 */

//Shortcodes in text widget
add_filter('widget_text', 'do_shortcode');
if ( ! isset( $content_width ) ) { $content_width = 1200; }
add_theme_support( 'automatic-feed-links' );
add_theme_support( "title-tag" );

//Add Widgets Shortcode support
require_once( WP_PLUGIN_DIR .'/tt-warp7/warp/config/layouts/fields/tt_widget_shortcode.php');

// Add KingComposer Pro license
define('KC_LICENSE', 'l483kg4m-jxbv-ju7k-or7h-yhgd-q3jl1ec3fqyi');