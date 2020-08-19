<?php

class WPUEP_Template_Code extends WPUEP_Template_Block {

    public $text;

    public $editorField = 'code';

    public function __construct( $type = 'code' )
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

        $text = do_shortcode( $this->text );

        $output .= '<span' . $this->style() . '>' . $text . '</span>';

        return $this->after_output( $output, $exercise );
    }
}