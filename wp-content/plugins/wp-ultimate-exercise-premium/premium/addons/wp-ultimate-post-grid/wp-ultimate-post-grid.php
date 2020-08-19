<?php

class WPUEP_Wp_Ultimate_Post_Egrid extends WPUEP_Premium_Addon {

    public function __construct( $name = 'wp-eultimate-post-grid' ) {
        parent::__construct( $name );		
        add_filter( 'wpupg_meta_box_grid_templates', array( $this, 'meta_box_grid_templates' ) );

        if( !isset( $_GET['wpupg_etemplate_editor_preview'] ) ) {
            add_filter( 'wpupg_eoutput_grid_classes', array( $this, 'output_grid_classes' ), 10, 2 );
            add_filter( 'wpupg_eoutput_grid_template', array( $this, 'output_grid_template' ), 10, 2 );
            add_filter( 'wpupg_eoutput_grid_post', array( $this, 'output_grid_post' ), 10, 2 );
        }
    }

    public function meta_box_grid_templates( $templates ) {
        $mapping = WPUltimateExercise::addon( 'custom-templates' )->get_mapping();

        foreach( $mapping as $index => $template ) {
            $templates['wpuep-' . $index] = 'WP Ultimate Exercise - ' . $template;
        }
        return $templates;
    }

    public function output_grid_classes( $classes, $egrid ) {		
		$template_id = $egrid->template_id();
		
        if( substr( $template_id, 0, 6 ) == 'wpuep-' ) {
            $template_id = substr( $template_id, 6 );
            $mapping = WPUltimateExercise::addon( 'custom-templates' )->get_mapping();

            if( isset( $mapping[$template_id] ) ) {
                $classes = array(
                    'wp-eultimate-post-grid' => true,
                    'template_type' => 'exercise_grid',
                    'classes' => $classes
                );
            }
        }

        return $classes;
    }

    public function output_grid_template( $template, $egrid ) {
		$template_id = $egrid->template_id();	

        if( substr( $template_id, 0, 6 ) == 'wpuep-' ) {
            $template_id = substr( $template_id, 6 );
            $mapping = WPUltimateExercise::addon( 'custom-templates' )->get_mapping();

            if( isset( $mapping[$template_id] ) ) {
                $template = WPUltimateExercise::addon( 'custom-templates' )->get_template_code( $template_id );
            }

        }
        return $template;
    }

    public function output_grid_post( $post, $egrid ) {
        $template_id = $egrid->template_id();

        if( substr( $template_id, 0, 6 ) == 'wpuep-' ) {
            $template_id = substr( $template_id, 6 );
            $mapping = WPUltimateExercise::addon( 'custom-templates' )->get_mapping();

            if( isset( $mapping[$template_id] ) ) {
                $post = new WPUEP_Exercise( $post );
            }

        }

        return $post;
    }
}

WPUltimateExercise::loaded_addon( 'wp-eultimate-post-grid', new WPUEP_Wp_Ultimate_Post_Egrid() );