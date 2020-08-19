<?php

class WPUEP_Template_Exercise_Ingredient_Quantity extends WPUEP_Template_Block {

    public $editorField = 'exerciseIngredientQuantity';

    public function __construct( $type = 'exercise-ingredient-quantity' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['ingredient_quantity'] ) || !$args['ingredient_quantity'] ) return '';

        $output = $this->before_output();

        $fraction = WPUltimateExercise::option('exercise_adjustable_servings_fractions', '0') == '1' ? true : false;
        $fraction = strpos( $args['ingredient_quantity'], '/' ) === false ? $fraction : true;

        $output .= '<span data-normalized="' . $args['ingredient_quantity_normalized'] . '" data-fraction="' . $fraction . '" data-original="' . $args['ingredient_quantity'] . '"' . $this->style() . '>' . $args['ingredient_quantity'] . '</span>';

        return $this->after_output( $output, $exercise );
    }
}