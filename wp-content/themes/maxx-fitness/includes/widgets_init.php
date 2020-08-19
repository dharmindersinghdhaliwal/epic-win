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

// Register sidebars
function maxxfitness_widgets_init() {
    $positions = array(
        "sidebar-a"     => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "sidebar-b"     => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "logo"          => "This is special sidebar for logo widget. In theme settings you can set up use it or not.",
        "menu"          => "This is special sidebar for main menu. All widgets in this position will be dropdown.",
        "search"=> "This is special sidebar for Search widget.",
        "fullscreen"    => "This is special sidebar for slide show. This sidebar have fullwidth layout.",
        "toolbar-l"     => "Widgets in this sidebar don't have title. Use it for side information.",
        "toolbar-r"     => "Widgets in this sidebar don't have title. Use it for side information.",
        "breadcrumbs"   => "This is special sidebar for Breadcrumbs. Use it for display your site navigation.",
        "top-a"         => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "top-b"         => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "top-c"         => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "top-d"         => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "bottom-a"      => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "bottom-b"      => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "bottom-c"      => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "bottom-d"      => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "main-top"      => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "main-bottom"   => "This is common sidebar. Widgets in this position can be displayed in different styles with additional icons and badges. Detailed in theme settings.",
        "social"        => "This is special sidebar for custom social links.",
        "footer"        => "Widgets in this sidebar have parallel layout. Use it for copyrights.",
        "offcanvas"     => "This is special sidebar for mobile menu.",
        "debug"         => "This is special sidebar for debugging your widgets.",
        "notexist"      => "This is special sidebar. Use it with widget-short codes."
    );

    
    foreach ($positions as $name => $desc) {
        register_sidebar(array(
            'name' => $name,
            'id' => $name,
            'description' => $desc,
            'before_widget' => '<!--widget-%1$s<%2$s>-->',
            'after_widget' => '<!--widget-end-->',
            'before_title' => '<!--title-start-->',
            'after_title' => '<!--title-end-->',
        ));
    }
}
add_action( 'widgets_init', 'maxxfitness_widgets_init' );