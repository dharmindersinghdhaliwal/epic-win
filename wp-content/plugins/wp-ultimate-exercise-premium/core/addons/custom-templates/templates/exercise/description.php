<?php

class WPUEP_Template_Exercise_Description extends WPUEP_Template_Block {

    public $editorField = 'exerciseDescription';

    public function __construct( $type = 'exercise-description' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'exercise' && $args['desktop'] ? ' itemprop="description"' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . $meta . '>' . $this->cut_off( $exercise->description() ) . '</span>';

        return $this->after_output( $output, $exercise );
    }
}