<?php

class WPUEP_Template_Exercise_Ingredient_Notes extends WPUEP_Template_Block {

    public $editorField = 'exerciseIngredientNotes';

    public function __construct( $type = 'exercise-ingredient-notes' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['ingredient_notes'] ) || !$args['ingredient_notes'] ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['ingredient_notes'] . '</span>';

        return $this->after_output( $output, $exercise );
    }
}