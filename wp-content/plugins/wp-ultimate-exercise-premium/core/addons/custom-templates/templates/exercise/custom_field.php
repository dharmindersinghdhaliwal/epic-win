<?php

class WPUEP_Template_Exercise_Custom_Field extends WPUEP_Template_Block {

    public $editorField = 'exerciseCustomField';
    public $key;

    public function __construct( $type = 'custom-field' )
    {
        parent::__construct( $type );
    }

    public function key( $key )
    {
        $this->key = $key;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        if( !$this->key || !get_post_meta( $exercise->ID(), $this->key, true ) ) return '';

        $output .= '<span' . $this->style() . '>' . $this->cut_off( $exercise->custom_field( $this->key ) ) . '</span>';

        return $this->after_output( $output, $exercise );
    }
}