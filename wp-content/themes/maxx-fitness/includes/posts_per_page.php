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

// Сustom posts per page for categories
function maxxfitness_custom_posts_per_page($query){
    if(is_category('5')) {//Trainers category
        $query->set('posts_per_page', 4);
    }
    if(is_category('17')) {//Locations category
        $query->set('posts_per_page', 4);
    }
    if(is_category('3')) {//Classes category
        $query->set('posts_per_page', 4);
    }
    if(is_category('4')) {//Workouts category
        $query->set('posts_per_page', 9);
    }
}

// Limits only for Frontend
if(!is_admin()){
    add_action('pre_get_posts','maxxfitness_custom_posts_per_page');
}