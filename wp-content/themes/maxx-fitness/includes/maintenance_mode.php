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

// Activate WordPress Maintenance Mode
function maxxfitness_maintenance_mode(){
    global $warp;

    if ( ! is_admin() ) {
        if ( ! (stristr($_SERVER['REQUEST_URI'], '/wp-login') || stristr($_SERVER['REQUEST_URI'], '/wp-admin') ) ) {
            if ( (!current_user_can('edit_themes') || !is_user_logged_in()) && ($warp['config']->get('maintenance_mode')=="0") ){
                echo $warp['template']->render('offline'); 
                die();
            }
        }
    }
}
add_action('init', 'maxxfitness_maintenance_mode');