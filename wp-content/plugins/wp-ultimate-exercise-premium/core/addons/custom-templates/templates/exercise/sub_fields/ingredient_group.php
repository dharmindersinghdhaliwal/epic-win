<?php

class WPUEP_Template_Exercise_Ingredient_Group extends WPUEP_Template_Block {

    public $editorField = 'exerciseIngredientGroup';

    public function __construct( $type = 'exercise-ingredient-group' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['ingredient_group_name'] ) || !$args['ingredient_group_name'] ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['ingredient_group_name'] . '</span>';

        return $this->after_output( $output, $exercise );
    }
}