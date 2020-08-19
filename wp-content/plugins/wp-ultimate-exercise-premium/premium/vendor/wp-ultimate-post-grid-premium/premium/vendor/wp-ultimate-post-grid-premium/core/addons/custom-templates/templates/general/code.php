<?php

class WPUPG_Etemplate_Code extends WPUPG_Etemplate_Block  {

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

    public function output( $post, $args = array() )
    {
        if( !$this->output_block( $post, $args ) ) return '';

        $output = $this->before_output();

        $text = do_shortcode( $this->text );

        $output .= '<span' . $this->style() . '>' . $text . '</span>';

        return $this->after_output( $output, $post );
    }
}