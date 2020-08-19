<?php

class WPUEP_Template_Exercise_Tag_Terms extends WPUEP_Template_Block {

    public $editorField = 'exerciseTagTerms';

    public function __construct( $type = 'exercise-tag-terms' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['tag_terms'] ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['tag_terms'] . '</span>';

        return $this->after_output( $output, $exercise );
    }
}