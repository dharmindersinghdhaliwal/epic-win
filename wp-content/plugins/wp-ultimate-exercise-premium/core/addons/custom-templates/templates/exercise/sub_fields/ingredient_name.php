<?php

class WPUEP_Template_Exercise_Ingredient_Name extends WPUEP_Template_Block {

    public $editorField = 'exerciseIngredientName';

    public function __construct( $type = 'exercise-ingredient-name' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['ingredient_name'] ) ) return '';

        $taxonomy = get_term_by('name', $args['ingredient_name'], 'ingredient');
        $ingredient_links = WPUltimateExercise::option('exercise_ingredient_links', 'archive_custom');

        $output = $this->before_output();

        $plural = WPUEP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'plural' );
        $plural = is_array( $plural ) ? false : $plural;
        $plural_data = $plural ? ' data-singular="' . esc_attr( $args['ingredient_name'] ) . '" data-plural="' . esc_attr( $plural ) . '"' : '';

        $output .= '<span' . $this->style() . $plural_data . '>';

        $closing_tag = '';
        if ( !empty( $taxonomy ) && $ingredient_links != 'disabled' ) {

            if( $ingredient_links == 'archive_custom' || $ingredient_links == 'custom' ) {
                $custom_link = WPUEP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'link' );
            } else {
                $custom_link = false;
            }

            if( WPUEP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'hide_link' ) !== '1' ) {
                if( $custom_link !== false && $custom_link !== '' ) {
                    $nofollow = WPUltimateExercise::option( 'exercise_ingredient_custom_links_nofollow', '0' ) == '1' ? ' rel="nofollow"' : '';

                    $output .= '<a href="'.$custom_link.'" class="custom-ingredient-link" target="'.WPUltimateExercise::option( 'exercise_ingredient_custom_links_target', '_blank' ).'"' . $nofollow . '>';
                    $closing_tag = '</a>';
                } else if( $ingredient_links != 'custom' ) {
                    $output .= '<a href="'.get_term_link( $taxonomy->slug, 'ingredient' ).'">';
                    $closing_tag = '</a>';
                }
            }
        }

        $output .= $plural && $args['ingredient_quantity_normalized'] != 1 ? $plural : $args['ingredient_name'];
        $output .= $closing_tag;
        $output .= '</span>';

        return $this->after_output( $output, $exercise );
    }
}