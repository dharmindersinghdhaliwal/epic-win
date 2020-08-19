<?php

class WPUEP_Template_Exercise_Unit_Changer extends WPUEP_Template_Block {

    public $editorField = 'unitChanger';

    public function __construct( $type = 'exercise-unit-changer' )
    {
        parent::__construct( $type );

        $this->add_style( 'background', 'white', 'select' );
        $this->add_style( 'border', '1px solid #bbbbbb', 'select' );
    }

    //TODO Refactor this.
    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        if( WPUltimateExercise::is_addon_active( 'unit-conversion' ) && WPUltimateExercise::option('exercise_adjustable_units', '1' ) == '1' ) {
            $output = '<span'.$this->style().'>';

            $output .= '<select onchange="ExerciseUnitConversion.recalculate(this)" class="adjust-exercise-unit"'.$this->style('select').'>';
            $systems = WPUltimateExercise::get()->helper( 'ingredient_units' )->get_active_systems();

            foreach($systems as $i => $system) {
                $output .= '<option value="'.$i.'">'.$system['name'].'</option>';
            }

            $output .= '</select></span>';
        }

        return $this->after_output( $output, $exercise );
    }
}