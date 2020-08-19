<?php

class WPUPG_Efilter_Dropdown extends WPUPG_Epremium_Addon {

    public function __construct( $name = 'filter-dropdown' ) {
        parent::__construct( $name );
		
		echo 'Drop Down';
		exit;
		
        add_action( 'init', array( $this, 'eassets' ) );

        add_filter( 'wpupg_egrid_cache_filter', array( $this, 'grid_cache_filter' ), 10, 3 );
        add_filter( 'wpupg_efilter_shortcode', array( $this, 'filter_shortcode' ), 10, 2 );
    }
	
    public function eassets()
    {		
        if( !is_admin() ) {															
            WPUltimateEPostGrid::get()->helper( 'eassets' )->add(			
                array(
					'name' => 'exercise_filter_dropdown',
                    'file' => $this->addonPath . '/css/filter-dropdown.css',					
                    'premium' => true,
                    'public' => true,
                ),				
                array(				
					'name' => 'select2css',
                    'file' => WPUltimateEPostGrid::get()->coreUrl . '/vendor/select2/css/select2.css',
                    'direct' => true,
                    'public' => true,
                ),
                array(
                    'name' => 'select2ewpupg',
                    'file' => '/vendor/select2/js/select2.js',
                    'public' => true,
                    'deps' => array(
                        'jquery',
                    ),
                ),
                array(
                    'name' => 'filter-edropdown',					
                    'file' => $this->addonPath . '/js/filter-dropdown.js',										
                    'premium' => true,					
                    'public' => true,					
                    'deps' => array(
                        'jquery',
                        'select2ewpupg',
                    )					
                )				
            );			
        }		
    }
	
    public function grid_cache_filter( $filter, $cache, $egrid ) {
        if( $egrid->filter_type() == 'dropdown' ) {
            $taxonomies = $egrid->filter_taxonomies();

            foreach( $taxonomies as $taxonomy_key ) {
                if( isset( $cache['taxonomies'][$taxonomy_key] ) ) {
                    $taxonomy = get_taxonomy( $taxonomy_key );
                    $category_args = array(
                        'show_option_none' => 'none',
                        'taxonomy' => $taxonomy_key,
                        'echo' => 0,
                        'hide_empty' => 1,
                        'class' => 'wpupg-efilter-dropdown-item',
                        'show_count' => 0,
                        'orderby' => 'name',
                        'hierarchical' => true,
                        'hide_if_empty' => true,
                        'parent' => 0,
                    );

                    $options = get_categories( $category_args );

                    if( $egrid->filter_multiselect() ) {
                        $empty_option = '';
                        $multiple = ' multiple';
                    } else {
                        $empty_option = '<option></option>';
                        $multiple = '';
                    }
                    $placeholder = $taxonomy->labels->name;

                    $select = '<select name="wpupg-efilter-dropdown-'.$taxonomy_key.'" id="wpupg-efilter-dropdown-'.$taxonomy_key.'" class="wpupg-efilter-dropdown-item" data-taxonomy="' . $taxonomy_key . '" data-placeholder="'.$placeholder.'"'. $multiple .'>';
                    $select .= $empty_option;

                    $select_options = $this->generate_hierarchical_select( $category_args, $cache, $taxonomy_key, $options );
                    $select .= $select_options;

                    $select .= '</select>';

                    if( $select_options ) {
                        $filter .= $select;
                    }
                }
            }
        }

        return $filter;
    }

    private function generate_hierarchical_select( $args, $cache, $taxonomy_key, $options, $level = 0 ) {
        $select = '';

        foreach( $options as $option ) {
            if( array_key_exists( $option->slug, $cache['taxonomies'][$taxonomy_key] ) ) {
                $indent = str_repeat( '&nbsp;&nbsp;', $level );
                $select .= '<option value="'.$option->slug.'">'.$indent.$option->name.'</option>';
            }

            $args['parent'] = $option->term_id;
            $children = get_categories( $args );
            $select .= $this->generate_hierarchical_select( $args, $cache, $taxonomy_key, $children, $level+1);
        }

        return $select;
    }

    public function filter_shortcode( $output, $egrid) {
		echo 'Filter Type is ='.$egrid->filter_type();
		exit; 
        if( $egrid->filter_type() == 'dropdown' ) {
            $output = '<div id="wpupg-egrid-' . $egrid->slug() . '-filter" class="wpupg-efilter wpupg-efilter-dropdown" data-grid="' . $egrid->slug() . '" data-type="dropdown" data-multiselect-type="' . $egrid->filter_multiselect_type() . '">';
            $output .= $egrid->filter();
            $output .= '</div>';
        }

        return $output;
    }
}

WPUltimateEPostGrid::loaded_addon( 'filter-dropdown', new WPUPG_Efilter_Dropdown() );