<?php

class WPUEP_Template_Exercise_Prep_Time extends WPUEP_Template_Block {

    public $editorField = 'exercisePrepTime';

    public function __construct( $type = 'exercise-prep-time' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'exercise' && $args['desktop'] && $exercise->prep_time_meta() ? '<meta itemprop="prepTime" content="' . $exercise->prep_time_meta() . '">' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $meta . $exercise->prep_time() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}