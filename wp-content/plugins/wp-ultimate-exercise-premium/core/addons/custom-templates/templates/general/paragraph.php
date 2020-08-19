<?php

class WPUEP_Template_Paragraph extends WPUEP_Template_Block {

    public $text;

    public $editorField = 'paragraph';

    public function __construct( $type = 'paragraph' )
    {
        parent::__construct( $type );
    }

    public function text( $text )
    {
        $this->text = $text;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<div' . $this->style() . '>' . $this->text . '</div>';

        return $this->after_output( $output, $exercise );
    }
}