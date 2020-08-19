<?php

class WPUEP_Template_Exercise_Link extends WPUEP_Template_Block {

    public $editorField = 'exerciseLink';

    public function __construct( $type = 'exercise-link' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $exercise->link() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}