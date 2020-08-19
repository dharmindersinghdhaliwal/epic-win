<?php 
/**
 * Exclusively on Envato Market: http://themeforest.net/user/torbara/?ref=torbara
 * @encoding     UTF-8
 * @version      1.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

add_shortcode( 'tt_widget', 'tt_widget_shortcode' );

/**
 * Output a widget using 'tt_widget' shortcode.
 *
 * Requires the widget ID.
 * You can overwrite widget args: before_widget, before_title, after_title, after_widget
 *
 * @example [tt_widget id="text-1"]
 * @since 1.0
 */
if ( !function_exists('tt_widget_shortcode') ) { 
    function tt_widget_shortcode( $atts, $content = null ) {
	$atts['echo'] = false;
	return tt_do_widget( $atts );
    } 
}

/**
 * Displays a widget
 *
 * @param mixed args
 * @since 1.0
 * @return string widget output
 */
if ( !function_exists('tt_do_widget') ) { 
    function tt_do_widget( $args ) {
        global $_wp_sidebars_widgets, $wp_registered_widgets, $wp_registered_sidebars;

        extract( shortcode_atts( array(
                'id' => '',
                'title' => true, /* wheather to display the widget title */
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'before_title' => '<h2 class="widgettitle">',
                'after_title' => '</h2>',
                'after_widget' => '</div>',
                'echo' => true
        ), $args, 'widget' ) );

        if( empty( $id ) || ! isset( $wp_registered_widgets[$id] ) ) { return; }

        // get the widget instance options
        preg_match( '/(\d+)/', $id, $number );
        $options = get_option( $wp_registered_widgets[$id]['callback'][0]->option_name );
        $instance = $options[$number[0]];
        $class = get_class( $wp_registered_widgets[$id]['callback'][0] );
        $widgets_map = tt_widget_shortcode_get_widgets_map();
        $_original_widget_position = $widgets_map[$id];

        // maybe the widget is removed or deregistered
        if( ! $class ) { return; }

        $show_title = ( '0' == $title ) ? false : true;

        /* build the widget args that needs to be filtered through dynamic_sidebar_params */
        $params = array(
                0 => array(
                        'name' => $wp_registered_sidebars[$_original_widget_position]['name'],
                        'id' => $wp_registered_sidebars[$_original_widget_position]['id'],
                        'description' => $wp_registered_sidebars[$_original_widget_position]['description'],
                        'before_widget' => $before_widget,
                        'before_title' => $before_title,
                        'after_title' => $after_title,
                        'after_widget' => $after_widget,
                        'widget_id' => $id,
                        'widget_name' => $wp_registered_widgets[$id]['name']
                ),
                1 => array(
                        'number' => $number[0]
                )
        );
        $params = apply_filters( 'dynamic_sidebar_params', $params );

        if( ! $show_title ) {
                $params[0]['before_title'] = '<h3 class="widgettitle">';
                $params[0]['after_title'] = '</h3>';
        } elseif( is_string( $title ) && strlen( $title ) > 0 ) {
                $instance['title'] = $title;
        }

        // Substitute HTML id and class attributes into before_widget
        $classname_ = '';
        foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
                if ( is_string( $cn ) )
                        $classname_ .= '_' . $cn;
                elseif ( is_object($cn) )
                        $classname_ .= '_' . get_class( $cn );
        }
        $classname_ = ltrim( $classname_, '_' );
        $params[0]['before_widget'] = sprintf( $params[0]['before_widget'], $id, $classname_ );

        // render the widget
        ob_start();
        the_widget( $class, $instance, $params[0] );
        $content = ob_get_clean();

        // supress the title if we wish
        if( ! $show_title ) {
                $content = preg_replace( '/<h3 class="widgettitle">(.*?)<\/h3>/', '', $content );
        }

        if( $echo !== true ) { return $content; }

        echo $content;
    }
}

/**
 * Returns an array of all widgets as the key, their position as the value
 *
 * @since 1.0
 * @return array
 */
if ( !function_exists('tt_widget_shortcode_get_widgets_map') ) {
    function tt_widget_shortcode_get_widgets_map() {
        $sidebars_widgets = wp_get_sidebars_widgets();
        $widgets_map = array();
        if ( ! empty( $sidebars_widgets ) ){
            foreach( $sidebars_widgets as $position => $widgets ){
                if( ! empty( $widgets) ) {
                    foreach( $widgets as $widget ) {
                        $widgets_map[$widget] = $position;
                    }
                }
            }
        }
        return $widgets_map;
    }
}