<?php
/*
 * 
 * @encoding     UTF-8
 * @author       Alexander Khmelnitskiy (info@alexander.khmelnitskiy.ua)
 * @copyright    Copyright (C) 2016 torbara (http://torbara.com/). All rights reserved.
 * @license      Copyrighted Commercial Software
 * @support      support@torbara.com
 * 
 */

use Warp\Warp;
use Warp\Autoload\ClassLoader;
use Warp\Config\Repository;

if (!function_exists('torbara_warp_init')) {
    function torbara_warp_init () {
        global $warp;
        
        if (!$warp) {
        
            require_once(WP_PLUGIN_DIR.'/tt-warp7/warp/src/Warp/Autoload/ClassLoader.php');

            // set loader
            $loader = new ClassLoader;
            $loader->add('Warp', WP_PLUGIN_DIR.'/tt-warp7/warp/src');
            $loader->add('Warp\Wordpress', WP_PLUGIN_DIR.'/tt-warp7/warp/systems/wordpress/src');
            $loader->register();

            // set config
            $config = new Repository;
            $config->load(WP_PLUGIN_DIR.'/tt-warp7/warp/config.php');
            $config->load(WP_PLUGIN_DIR.'/tt-warp7/warp/systems/wordpress/config.php');
            $config->load(get_template_directory().'/config.php');

            // set warp
            $warp = new Warp(compact('loader', 'config'));
            $warp['system']->init();
        }
        return $warp;
    }
}

return torbara_warp_init(); 
