<?php

class WPUEP_Template_Exercise_Post_Content extends WPUEP_Template_Block {

    public $editorField = 'exercisePostContent';

    public function __construct( $type = 'exercise-post-content' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $this->cut_off( $exercise->post_content() ) . '</span>';

        return $this->after_output( $output, $exercise );
    }
}