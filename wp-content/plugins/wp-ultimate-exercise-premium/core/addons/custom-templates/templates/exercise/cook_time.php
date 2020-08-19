<?php

class WPUEP_Template_Exercise_Cook_Time extends WPUEP_Template_Block {

    public $editorField = 'exerciseCookTime';

    public function __construct( $type = 'exercise-cook-time' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'exercise' && $args['desktop'] && $exercise->cook_time_meta() ? '<meta itemprop="cookTime" content="' . $exercise->cook_time_meta() . '">' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $meta . $exercise->cook_time() . '</span>';

        return $this->after_output( $output, $exercise );
    }
}