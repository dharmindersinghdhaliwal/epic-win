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

// widgets scheme layouts
if (!function_exists("maxxfitness_widgets_scheme_layouts_info")) {

    function maxxfitness_widgets_scheme_layouts_info() { ?>
        <?php add_thickbox(); ?>
            <div class="updated">
                <p>The <?php echo wp_get_theme(); ?> Theme comes with a broad range of layout options</p>
                <p><a href="#TB_inline?width=730&height=590&inlineId=tm-widgets-layout-scheme" title="<?php echo wp_get_theme(); ?> Widgets layout scheme" class="thickbox"><span class="dashicons dashicons-welcome-widgets-menus"></span> Show Widgets layout scheme</a></p>
                <div id="tm-widgets-layout-scheme" style="display:none;">
                    
                    <h3>Widget Layouts</h3>
                    <p>The blue widget positions allow to choose a widget layout which defines the widget alignment and proportions: parallel, stacked, first doubled, last doubled and center doubled. You can easily add your own widget layouts.</p>

                    <h3>Sidebar Layouts</h3>
                    <p>The two available sidebars, highlighted in red, can be switched to the left or right side and their widths can easily be set in the theme administration.</p>

                    <h3>Widget Style</h3>
                    <p>For widgets in the blue and red positions you can choose different widget styles.</p>
                    
                    <img src="<?php echo get_template_directory_uri(); ?>/images/scheme_layouts.png" alt="<?php echo wp_get_theme(); ?> Widgets layout scheme">
                    
                </div>
            </div>
        <?php
    }

}

if( !function_exists( "maxxfitness_widgets_scheme_layouts")){
    function maxxfitness_widgets_scheme_layouts (){
        global $pagenow;
        if ( $pagenow == 'widgets.php' ) { 
            add_action('admin_notices', 'maxxfitness_widgets_scheme_layouts_info'); 
        }
    }
}
maxxfitness_widgets_scheme_layouts();

