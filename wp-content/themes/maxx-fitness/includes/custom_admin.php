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

//Custom admin styles
function maxxfitness_admin_style() { 
    $src = get_template_directory_uri() . '/css/admin.css';
    $handle = 'mall-admin-style';
    wp_register_script($handle, $src);
    wp_enqueue_style($handle, $src, array(), false, false);
}
add_action( 'admin_head', 'maxxfitness_admin_style' );