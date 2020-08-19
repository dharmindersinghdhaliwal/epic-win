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

use Warp\Warp;
use Warp\Autoload\ClassLoader;
use Warp\Config\Repository;

if (!function_exists('maxxfitness_warp_init')) {
    function maxxfitness_warp_init () {
        global $warp;
        
        if (!$warp) {
            
            require_once(TT_WARP_PLUGIN_DIR.'warp/src/Warp/Autoload/ClassLoader.php');

            // set loader
            $loader = new ClassLoader;
            $loader->add('Warp', TT_WARP_PLUGIN_DIR.'warp/src');
            $loader->add('Warp\Wordpress', TT_WARP_PLUGIN_DIR.'warp/systems/wordpress/src');
            $loader->register();

            // set config
            $config = new Repository;
            $config->load(TT_WARP_PLUGIN_DIR.'warp/config.php');
            $config->load(TT_WARP_PLUGIN_DIR.'warp/systems/wordpress/config.php');
            $config->load(TT_WARP_PLUGIN_DIR.'/config.php');

            // set warp
            $warp = new Warp(compact('loader', 'config'));
            $warp['system']->init();
        }
        return $warp;
    }
}

return maxxfitness_warp_init();