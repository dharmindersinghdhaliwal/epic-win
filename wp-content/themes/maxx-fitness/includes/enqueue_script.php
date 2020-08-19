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

// add scripts
function maxxfitness_add_scripts() {
    global $warp;
    
    $js_path = WP_PLUGIN_URL .'/tt-warp7/warp/vendor/uikit/js';
    
    wp_enqueue_script( "comment-reply" );
    wp_enqueue_script( 'maxxfitness-uikit',             $js_path . '/uikit.js', array('jquery'), '', true);
    wp_enqueue_script( 'maxxfitness-autocomplete',      $js_path . '/components/autocomplete.js', array('jquery'), '', true);
    wp_enqueue_script( 'maxxfitness-search',            $js_path . '/components/search.js', array('jquery'), '', true);
    wp_enqueue_script( 'maxxfitness-circle-progress',   get_template_directory_uri().'/js/circle-progress.js', array('jquery'), '', true);

    wp_enqueue_script( 'maxxfitness-lightbox',          $js_path . '/components/lightbox.js', array('jquery'), '', true);
    wp_enqueue_script( 'maxxfitness-slideshow',         $js_path . '/components/slideshow.js', array('jquery'), '', true);
    wp_enqueue_script( 'maxxfitness-slideshow-fx',      $js_path . '/components/slideshow-fx.js', array('jquery'), '', true);
    
    if ($warp['config']->get('smoothscroll')=="0"){ 
        wp_enqueue_script( 'maxxfitness-smoothscroll', get_template_directory_uri().'/js/smoothscroll.js', array(), '', true);
    }

    //Additional components
    if ($warp['config']->get('uk_dynamic_grid')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-dynamic_grid', $js_path . '/components/grid.js', array(), '', true);
    }

    if ($warp['config']->get('uk_slider')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-slider', $js_path . '/components/slider.js', array(), '', true);
    }

    if ($warp['config']->get('uk_slideset')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-slideset', $js_path . '/components/slideset.js', array(), '', true);
    }

    if ($warp['config']->get('uk_parallax')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-parallax', $js_path . '/components/parallax.js', array(), '', true);
    }

    if ($warp['config']->get('uk_accordion')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-accordion', $js_path . '/components/accordion.js', array(), '', true);
    }

    if ($warp['config']->get('uk_sticky')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-sticky', $js_path . '/components/sticky.js', array(), '', true);
    }

    if ($warp['config']->get('uk_tooltip')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-tooltip', $js_path . '/components/tooltip.js', array(), '', true);
    }

    if ($warp['config']->get('uk_datepicker')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-datepicker', $js_path . '/components/datepicker.js', array(), '', true);
    }

    if ($warp['config']->get('uk_timepicker')=="0"){ 
        wp_enqueue_script( 'maxxfitness-uk-timepicker', $js_path . '/components/timepicker.js', array(), '', true);
    }
    
    if($warp['config']->get('style') == "default"){
        if ( file_exists( get_template_directory().'/js/theme.js' ) ) {
            wp_enqueue_script( 'maxxfitness-theme-js', get_template_directory_uri() . '/js/theme.js', array(), '', true);
        }
    }else{
        if ( file_exists( get_template_directory() . '/styles/'.$warp['config']->get('style').'/js/theme.js' ) ) {
            wp_enqueue_script( 'maxxfitness-theme-js', get_template_directory_uri() . '/styles/'.$warp['config']->get('style').'/js/theme.js', array(), '', true);
        }else{
            if ( file_exists( get_template_directory().'/js/theme.js' ) ) {
                wp_enqueue_script( 'maxxfitness-theme-js', get_template_directory_uri() . '/js/theme.js', array(), '', true);
            }
        }
    }

}

add_action('wp_enqueue_scripts', 'maxxfitness_add_scripts' );

// add css
function maxxfitness_add_css() {
    global $warp;
    
    if($warp['config']->get('style') == "default"){
        
        if ( file_exists( get_template_directory().'/css/theme.css' ) ) {
            wp_enqueue_style( 'maxxfitness-theme', get_template_directory_uri() . '/css/theme.css');
        }
        if ( file_exists( get_template_directory().'/css/woocommerce.css' ) ) {
            wp_enqueue_style( 'maxxfitness-woocommerce', get_template_directory_uri() . '/css/woocommerce.css');
        }
        if ( file_exists( get_template_directory().'/css/custom.css' ) ) {
            wp_enqueue_style( 'maxxfitness-custom', get_template_directory_uri() . '/css/custom.css');
        }
        
    }else{
        
        if ( file_exists( get_template_directory() . '/styles/'.$warp['config']->get('style').'/css/theme.css' ) ) {
            wp_enqueue_style( 'maxxfitness-theme', get_template_directory_uri() . '/styles/'.$warp['config']->get('style').'/css/theme.css');
        }else{
            if ( file_exists( get_template_directory().'/css/theme.css' ) ) {
                wp_enqueue_style( 'maxxfitness-theme', get_template_directory_uri() . '/css/theme.css');
            }
        }
        
        if ( file_exists( get_template_directory() . '/styles/'.$warp['config']->get('style').'/css/woocommerce.css' ) ) {
            wp_enqueue_style( 'maxxfitness-woocommerce', get_template_directory_uri() . '/styles/'.$warp['config']->get('style').'/css/woocommerce.css');
        }else{
            if ( file_exists( get_template_directory().'/css/woocommerce.css' ) ) {
                wp_enqueue_style( 'maxxfitness-woocommerce', get_template_directory_uri() . '/css/woocommerce.css');
            }
        }
        
        if ( file_exists( get_template_directory() . '/styles/'.$warp['config']->get('style').'/css/custom.css' ) ) {
            wp_enqueue_style( 'maxxfitness-custom', get_template_directory_uri() . '/styles/'.$warp['config']->get('style').'/css/custom.css');
        }else{
            if ( file_exists( get_template_directory().'/css/custom.css' ) ) {
                wp_enqueue_style( 'maxxfitness-custom', get_template_directory_uri() . '/css/custom.css');
            }
        }
        
    }
    

    $css_path = WP_PLUGIN_URL .'/tt-warp7/warp/vendor';
    
    wp_enqueue_style( 'maxxfitness-highlight', $css_path.'/highlight/highlight.css');
}
add_action('wp_enqueue_scripts', 'maxxfitness_add_css' );