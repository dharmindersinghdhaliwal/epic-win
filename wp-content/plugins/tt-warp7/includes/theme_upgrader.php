<?php
/*
 * @package    TT Warp 7
 * @encoding   UTF-8
 * @version    7.3.29
 * @author     Alexander Khmelnitskiy (info@alexander.khmelnitskiy.ua)
 * @copyright  Copyright (C) 2016 torbarа (http://torbara.com/). All rights reserved.
 * @license    Copyrighted Commercial Software
 * @support    support@torbara.com
 */


// Stupid WordPress, burn in hell!
if (!session_id()) { session_start(); }

// Save theme settings config.json
// Define the upgrader_pre_install callback
function filter_upgrader_pre_install( $true, $args_hook_extra ) {
    
    if(!isset($args_hook_extra["theme"]) ) { return $true; } // Work only with themes
    
    $theme_config = get_template_directory(). "/config.json";
    if(file_exists($theme_config)){
        $config_json = file_get_contents($theme_config);
        $config_json_bak = 'config.json.txt';
        $upload = wp_upload_bits( $config_json_bak, null, $config_json );

        // Check for error
        if( $upload['error'] ){
            echo '<br><br> Error: Failed to save config.json.bak. Details: '. $upload['error'];
        } else {
            $_SESSION['config_json_bak'] = $upload['file'];
        }
    }

    return $true;
}
add_filter( 'upgrader_pre_install', 'filter_upgrader_pre_install', 10, 2 );



// Restore theme settings config.json
// define the upgrader_post_install callback
function filter_upgrader_post_install( $true, $args_hook_extra, $this_result ) { 
    
    if(!isset($args_hook_extra["theme"]) ) { return $true; } // Work only with themes
    
    $config_json = file_get_contents($_SESSION['config_json_bak']);
    $theme_config = get_template_directory(). "/config.json";
    if(!file_put_contents($theme_config, $config_json)){
        echo '<br><br> Error: Failed to restore config.json.';
    }
    
    return $true; 
}
add_filter( 'upgrader_post_install', 'filter_upgrader_post_install', 10, 3 ); 
