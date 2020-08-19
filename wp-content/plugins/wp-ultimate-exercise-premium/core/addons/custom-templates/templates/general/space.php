<?php

class WPUEP_Template_Space extends WPUEP_Template_Block {

    public $non_breaking;

    public $editorField = 'space';

    public function __construct( $type = 'space' )
    {
        parent::__construct( $type );
    }

    public function non_breaking( $non_breaking )
    {
        $this->non_breaking = $non_breaking;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        $output .= $this->non_breaking ? '&nbsp;' : ' ';

        return $this->after_output( $output, $exercise );
    }
}