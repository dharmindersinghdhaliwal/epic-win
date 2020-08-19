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


// Detect Warp 7 plugin. It is required plugin.
if ( defined('TT_WARP_PLUGIN_URL') ) {    
    //Check compatibility
    require_once get_template_directory().'/includes/check_compatibility.php';

    //Custom login (registration) page
    require_once get_template_directory().'/includes/custom_login.php';

    //Woocommerce actions
    require_once get_template_directory().'/includes/woocommerce.php';

    //Plugin activation
    require_once get_template_directory().'/includes/plugin_activation.php';

    //Widgets scheme layouts
    require_once get_template_directory().'/includes/scheme_layouts.php';

    //Register sidebars
    require_once get_template_directory().'/includes/widgets_init.php';

    //Theme Support
    require_once get_template_directory().'/includes/theme_support.php';

    //Admin styles
    require_once get_template_directory().'/includes/custom_admin.php';

    //Add scripts and styles
    require_once get_template_directory().'/includes/enqueue_script.php';

    //Maintenance Mode
    require_once get_template_directory().'/includes/maintenance_mode.php';

    // One click demo install
    require_once get_template_directory().'/includes/demoinstall_ajax.php';
    
    //Сustom posts per page for categories
    require_once get_template_directory().'/includes/posts_per_page.php';		
	
} else { 
    // Otherwise, we work in legacy mode.
    require_once( get_template_directory() . '/lib/init.php' );
    require_once( get_template_directory() . '/includes/plugin_activation.php' );
}
