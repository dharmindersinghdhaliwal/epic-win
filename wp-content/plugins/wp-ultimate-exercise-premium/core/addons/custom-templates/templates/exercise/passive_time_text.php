<?php

class WPUEP_Template_Exercise_Passive_Time_Text extends WPUEP_Template_Block {

    public $editorField = 'exercisePassiveTimeUnit';

    public function __construct( $type = 'exercise-passive-time-text' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $exercise->passive_time_text() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}