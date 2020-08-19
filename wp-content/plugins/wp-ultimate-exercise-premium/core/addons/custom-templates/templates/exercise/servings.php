<?php

class WPUEP_Template_Exercise_Servings extends WPUEP_Template_Block {

    public $editorField = 'exerciseServings';

    public function __construct( $type = 'exercise-servings' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $exercise->servings() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}