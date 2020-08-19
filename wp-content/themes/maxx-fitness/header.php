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
    // start output buffer to capture content for use in footer.php
    ob_start();
} else {
    // Otherwise, we work in legacy mode.
    // Template situated in /lib/templates/structure/header.php
    get_template_part('lib/templates/structure/header');
}
?>

<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
