<?php

class WPUEP_Template_Exercise_Passive_Time extends WPUEP_Template_Block {

    public $editorField = 'exercisePassiveTime';

    public function __construct( $type = 'exercise-passive-time' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $exercise->passive_time() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}