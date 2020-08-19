<?php

class WPUEP_Template_Exercise_Nutrition_Label extends WPUEP_Template_Block {

    public $editorField = 'nutritionLabel';

    public function __construct( $type = 'exercise-nutrition-label' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        $output .= do_shortcode( '[ultimate-nutrition-label id=' . $exercise->ID()  .']');

        return $this->after_output( $output, $exercise );
    }
}