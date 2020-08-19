<?php

class WPUEP_Template_Exercise_Instruction_Group extends WPUEP_Template_Block {

    public $editorField = 'exerciseInstructionGroup';

    public function __construct( $type = 'exercise-instruction-group' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['instruction_group_name'] ) || !$args['instruction_group_name'] ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['instruction_group_name'] . '</span>';

        return $this->after_output( $output, $exercise );
    }
}