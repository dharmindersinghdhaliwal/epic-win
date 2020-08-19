<?php

class WPUEP_Template_Exercise_Author extends WPUEP_Template_Block {

    public $editorField = 'exerciseAuthor';

    public function __construct( $type = 'exercise-author' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $exercise->author() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}