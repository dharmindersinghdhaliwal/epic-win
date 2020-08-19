<?php

class WPUPG_Etemplate_Title extends WPUPG_Etemplate_Block  {

    public $text;
    public $tag;

    public $editorField = 'title';

    public function __construct( $type = 'title' )
    {
        parent::__construct( $type );
    }

    public function text( $text )
    {
        $this->text = $text;
        return $this;
    }

    public function tag( $tag )
    {
        $this->tag = $tag;
        return $this;
    }

    public function output( $post, $args = array() )
    {
        if( !$this->output_block( $post, $args ) ) return '';

        $output = $this->before_output();

        switch( $this->text ) {
            default:
                $string = $this->text;
        }

        $tag = isset( $this->tag ) ? $this->tag : 'span';
        $output .= '<' . $tag . $this->style() . '>' . $string . '</' . $tag . '>';

        return $this->after_output( $output, $post );
    }
}