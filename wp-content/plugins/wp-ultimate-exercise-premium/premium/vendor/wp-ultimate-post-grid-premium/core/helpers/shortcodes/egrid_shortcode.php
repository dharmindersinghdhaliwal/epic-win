<?php

class WPUPG_Egrid_Shortcode {

    public function __construct()
    {
		add_shortcode( 'wpupg-egrid', array( $this, 'shortcode' ) );		
		//	add_filter( 'mce_external_plugins', array( $this, 'tinymce_plugin' ) );
    }

    public function shortcode( $options )
    {		
		add_thickbox();
		
        $output	=	'';
        $slug	=	strtolower( trim( $options['id'] ) );				
        unset( $options['id'] );
		
        if( $slug ) {
            $post = get_page_by_path( $slug, OBJECT, WPUPG_EPOST_TYPE );			
			
            if( !is_null( $post ) ) {
                $egrid = new WPUPG_Egrid( $post );							
                // Check if we need to filter the grid dynamically
                $dynamic_rules = array();
				
                if( count( $options ) > 0 && WPUltimateEPostGrid::is_premium_active() ) {
                    foreach( $options as $taxonomy => $terms ) {
                        if( taxonomy_exists( $taxonomy ) ) {
                            $dynamic_rules[] = array(
                                'post_type' => 'wpupg_dynamic',
                                'taxonomy' => $taxonomy,
                                'values' => explode( ';', str_replace( ',', ';', $terms ) ),
                                'type' => 'restrict',
                            );
                        }
                    }
                }
				
                if( count( $dynamic_rules ) > 0 ) {					
                    $egrid->set_dynamic_rules( $dynamic_rules );
                }

                $link_type		=	$egrid->link_type();
                $link_target	=	$egrid->link_target();
                $layout_mode	=	$egrid->layout_mode();
                $centered		=	$egrid->centered() ? 'true' : 'false';
				
                $posts	=	'<div id="wpupg-egrid-' . esc_attr( $slug ) . '" class="wpupg-egrid" data-grid="' . esc_attr( $slug ) . '" data-link-type="' . $link_type . '" data-link-target="' . $link_target . '" data-layout-mode="' . $layout_mode . '" data-centered="' . $centered . '">';
                $posts .=	$egrid->draw_posts();
                $posts .=	'</div>';
				$output		=	apply_filters( 'wpupg_eposts_shortcode', $posts, $egrid );				
				
                $pagination	=	'';
				
                if( $egrid->pagination_type() == 'pages' ) {
                    $pagination_type = $egrid->pagination_type();
                    $pagination_style = $egrid->pagination_style();

                    $style_data = ' data-margin-vertical="' . $pagination_style['margin_vertical'] . '"';
                    $style_data .= ' data-margin-horizontal="' . $pagination_style['margin_horizontal'] . '"';
                    $style_data .= ' data-padding-vertical="' . $pagination_style['padding_vertical'] . '"';
                    $style_data .= ' data-padding-horizontal="' . $pagination_style['padding_horizontal'] . '"';
                    $style_data .= ' data-border-width="' . $pagination_style['border_width'] . '"';

                    $style_data .= ' data-background-color="' . $pagination_style['background_color'] . '"';
                    $style_data .= ' data-text-color="' . $pagination_style['text_color'] . '"';
                    $style_data .= ' data-border-color="' . $pagination_style['border_color'] . '"';

                    $style_data .= ' data-active-background-color="' . $pagination_style['background_active_color'] . '"';
                    $style_data .= ' data-active-text-color="' . $pagination_style['text_active_color'] . '"';
                    $style_data .= ' data-active-border-color="' . $pagination_style['border_active_color'] . '"';

                    $style_data .= ' data-hover-background-color="' . $pagination_style['background_hover_color'] . '"';
                    $style_data .= ' data-hover-text-color="' . $pagination_style['text_hover_color'] . '"';
                    $style_data .= ' data-hover-border-color="' . $pagination_style['border_hover_color'] . '"';
                    
                    $pagination .= '<div id="wpupg-egrid-' . esc_attr( $slug ) . '-pagination" class="wpupg-pagination wpupg-pagination-' . $pagination_type . '" style="text-align: ' . $pagination_style['alignment'] . ';" data-grid="' . esc_attr( $slug ) . '" data-type="' . $pagination_type . '"' . $style_data . '>';
                    $pagination .= $egrid->draw_pagination();
                    $pagination .= '</div>';
                }

                $output .= apply_filters( 'wpupg_epagination_shortcode', $pagination, $egrid );
            }
        }
		
        return $output;
    }

    public function tinymce_plugin( $plugin_array )
    {
        $plugin_array['wpupg_egrid_shortcode'] = WPUltimateEPostGrid::get()->coreUrl . '/js/tinymce_shortcode.js';
        return $plugin_array;
    }
}