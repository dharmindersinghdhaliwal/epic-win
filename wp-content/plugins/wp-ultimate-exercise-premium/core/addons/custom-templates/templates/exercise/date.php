<?php

class WPUEP_Template_Exercise_Date extends WPUEP_Template_Block {

    public $format;

    public $editorField = 'exerciseDate';

    public function __construct( $type = 'exercise-date' )
    {
        parent::__construct( $type );
    }

    public function format( $format )
    {
        $this->format = $format;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . get_the_date( $this->format, $exercise->ID() ) . '</span>';

        return $this->after_output( $output, $exercise );
    }
}