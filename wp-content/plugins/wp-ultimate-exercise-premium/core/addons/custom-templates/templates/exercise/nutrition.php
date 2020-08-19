<?php

class WPUEP_Template_Exercise_Nutrition extends WPUEP_Template_Block {

    public $editorField = 'exerciseNutrition';
    public $field;
    public $percentage = false;
    public $unit = true;

    public function __construct( $type = 'exercise-nutrition' )
    {
        parent::__construct( $type );
    }

    public function field( $field )
    {
        $this->field = $field;
        return $this;
    }

    public function percentage( $percentage )
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function unit( $unit )
    {
        $this->unit = $unit;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $value = $exercise->nutritional( $this->field );
        $unit = '';

        if( $this->percentage && $value != '' ) {
            $daily = WPUltimateExercise::is_addon_active( 'nutritional-information') ? WPUltimateExercise::addon( 'nutritional-information' )->daily : array();
            $value = isset( $daily[$this->field] ) ? round( floatval( $value ) / $daily[$this->field] * 100 ) : $value;
            if( $this->unit ) {
                $unit = '%';
            }
        } else if( $this->unit && $value != '' ) {
            $fields = WPUltimateExercise::is_addon_active( 'nutritional-information') ? WPUltimateExercise::addon( 'nutritional-information' )->fields : array();
            $unit = isset( $fields[$this->field] ) ? $fields[$this->field] : '';
        }

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $value . $unit . '</span>';

        return $this->after_output( $output, $exercise );
    }
}