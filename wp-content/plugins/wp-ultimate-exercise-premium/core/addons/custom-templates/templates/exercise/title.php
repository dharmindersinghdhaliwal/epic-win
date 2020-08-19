<?php

class WPUEP_Template_Exercise_Title extends WPUEP_Template_Block {

    public $tag;

    public $editorField = 'exerciseTitle';

    public function __construct( $type = 'exercise-title' )
    {
        parent::__construct( $type );
    }

    public function tag( $tag )
    {
        $this->tag = $tag;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'exercise' && $args['desktop'] ? ' itemprop="name"' : '';

        $output = $this->before_output();

        $tag = isset( $this->tag ) ? $this->tag : 'span';
        $output .= '<' . $tag . $this->style() . $meta .'>' . $this->cut_off( $exercise->title() ) . '</' . $tag . '>';

        return $this->after_output( $output, $exercise );
    }
}