<?php

class WPUEP_Template_Exercise_Instruction_Text extends WPUEP_Template_Block {

    public $editorField = 'exerciseInstructionText';

    public function __construct( $type = 'exercise-instruction-text' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) || !isset( $args['instruction_description'] ) || !$args['instruction_description'] ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'exercise' && $args['desktop'] ? ' itemprop="exerciseInstructions"' : '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . $meta . '>' . $args['instruction_description'] . '</span>';

        return $this->after_output( $output, $exercise );
    }
}