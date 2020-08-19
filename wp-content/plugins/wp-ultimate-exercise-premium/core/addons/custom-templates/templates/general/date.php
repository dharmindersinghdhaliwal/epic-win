<?php

class WPUEP_Template_Date extends WPUEP_Template_Block {

    public $format;

    public $editorField = 'date';

    public function __construct( $type = 'date' )
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

        $output .= '<span' . $this->style() . '>' . date($this->format) . '</span>';

        return $this->after_output( $output, $exercise );
    }
}