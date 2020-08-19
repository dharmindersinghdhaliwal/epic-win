<?php

class WPUEP_Template_Title extends WPUEP_Template_Block {

    public $text;
    public $tag;

    public $editorField = 'title';

    public function __construct( $type = 'title' )
    {
        parent::__construct( $type );
    }

    public function text( $text )
    {
        $this->text = $text;
        return $this;
    }

    public function tag( $tag )
    {
        $this->tag = $tag;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        switch( $this->text ) {
            case 'Servings':
                $string = __( 'Servings', 'wp-ultimate-exercise' ); break;
            case 'Servings:':
                $string = __( 'Servings', 'wp-ultimate-exercise' ) . ':'; break;
            case 'Units:':
                $string = __( 'Units', 'wp-ultimate-exercise' ) . ':'; break;
            case 'Prep Time':
                $string = __( 'Prep Time', 'wp-ultimate-exercise' ); break;
            case 'Cook Time':
                $string = __( 'Cook Time', 'wp-ultimate-exercise' ); break;
            case 'Passive Time':
                $string = __( 'Passive Time', 'wp-ultimate-exercise' ); break;
            case 'Ingredients':
                $string = __( 'Ingredients1', 'wp-ultimate-exercise' ); break;
            case 'Instructions':
                $string = __( 'Instructions', 'wp-ultimate-exercise' ); break;
            case 'Exercise Notes':
                $string = __( 'Exercise Notes', 'wp-ultimate-exercise' ); break;
            case 'Share this Exercise':
                $string = __( 'Share this Exercise', 'wp-ultimate-exercise' ); break;
            case 'Powered by':
                $string = __( 'Powered by', 'wp-ultimate-exercise' ); break;
            default:
                $string = $this->text;
        }

        $tag = isset( $this->tag ) ? $this->tag : 'span';
        $output .= '<' . $tag . $this->style() . '>' . $string . '</' . $tag . '>';

        return $this->after_output( $output, $exercise );
    }
}