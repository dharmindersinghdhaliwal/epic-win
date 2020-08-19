<?php

class WPUEP_Template_Exercise_Servings_Changer extends WPUEP_Template_Block {

    public $editorField = 'servingsChanger';

    public function __construct( $type = 'exercise-servings-changer' )
    {
        parent::__construct( $type );

        $this->add_style( 'width', '40px', 'input' );
        $this->add_style( 'padding', '2px', 'input' );
        $this->add_style( 'background', 'white', 'input' );
        $this->add_style( 'border', '1px solid #bbbbbb', 'input' );
    }

    //TODO Refactor this.
    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        if( WPUltimateExercise::option('exercise_adjustable_servings', '1') == '1' ) {
            if( WPUltimateExercise::is_addon_active( 'unit-conversion' ) ) {
                $output .= '<span' . $this->style() . '><input type="number" class="advanced-adjust-exercise-servings" data-original="' . $exercise->servings_normalized() . '" data-start-servings="' . $exercise->servings_normalized() . '" value="' . $exercise->servings_normalized() . '"' . $this->style('input') . '/> ' . $exercise->servings_type() . '</span>';
            } else {
                $output = '<span'.$this->style().'><input type="number" class="adjust-exercise-servings" data-original="' . $exercise->servings_normalized() . '" data-start-servings="' . $exercise->servings_normalized() . '" value="' . $exercise->servings_normalized() . '"' . $this->style('input') . '/> ' . $exercise->servings_type() . '</span>';
            }
        }

        return $this->after_output( $output, $exercise );
    }
}