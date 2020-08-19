<?php

class WPUEP_Template_Exercise_Tag_Name extends WPUEP_Template_Block {

    public $editorField = 'exerciseTagName';

    public function __construct( $type = 'exercise-tag-name' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['tag_name'] ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['tag_name'] . '</span>';

        return $this->after_output( $output, $exercise );
    }
}