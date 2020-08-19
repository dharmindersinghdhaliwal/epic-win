<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

// Detect Warp 7 plugin. It is required plugin.
if ( defined('TT_WARP_PLUGIN_URL') ) {
    // get warp
    $warp = require(get_template_directory().'/warp.php');

    // render error layout
    echo $warp['template']->render('error', array('title' => esc_html__('Page not found', 'maxx-fitness'), 'error' => '404', 'message' => sprintf(__('404_page_message', 'maxx-fitness'), $warp['system']->url, $warp['config']->get('site_name'))));
} else {
    // Otherwise, we work in legacy mode.
    maxxfitness_load_document();
}