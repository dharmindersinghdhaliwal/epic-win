<?php

class WPUEP_Template_Exercise_Prep_Time_Text extends WPUEP_Template_Block {

    public $editorField = 'exercisePrepTimeUnit';

    public function __construct( $type = 'exercise-prep-time-text' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $exercise->prep_time_text() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}