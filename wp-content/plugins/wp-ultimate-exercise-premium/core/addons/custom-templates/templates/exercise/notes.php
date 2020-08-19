<?php

class WPUEP_Template_Exercise_Notes extends WPUEP_Template_Block {

    public $editorField = 'exerciseNotes';

    public function __construct( $type = 'exercise-notes' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        $notes = wpautop( $exercise->notes() );

        // Add !important flags to styles added by visual editor
        if( WPUltimateExercise::option( 'exercise_template_force_style', '1' ) == '1' ) {
            preg_match_all( '/style="[^"]+/', $notes, $styles );

            foreach( $styles[0] as $style )
            {
                $new_style = preg_replace( "/([^;]+)/", "$1 !important", $style );

                $notes = str_ireplace( $style, $new_style, $notes );
            }
        }

        $output .= '<div' . $this->style() . '>' . $this->cut_off( $notes ) . '</div>';

        return $this->after_output( $output, $exercise );
    }
}