<?php

class WPUEP_Index_Shortcode {

    public function __construct()
    {
        add_shortcode( 'ultimate-exercise-index', array( $this, 'index_shortcode' ) );
    }

    function index_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'headers' => 'false'
        ), $options );

        $query = WPUltimateExercise::get()->helper( 'query_exercises' );
        $exercises = $query->order_by( 'title')->order( 'ASC' )->get();

        $out = '<div class="wpuep-index-container">';
        if( $exercises ) {

            $letters = array();

            foreach( $exercises as $exercise )
            {
                $title = $exercise->title();

                if( $title )
                {
                    if ( $options['headers'] != 'false' )
                    {
                        $first_letter = strtoupper( mb_substr( $title, 0, 1 ) );

                        if( !in_array( $first_letter, $letters ) )
                        {
                            $letters[] = $first_letter;
                            $out .= '<h2>';
                            $out .= $first_letter;
                            $out .= '</h2>';
                        }
                    }

                    $out .= '<a href="' . $exercise->link() . '">';
                    $out .= $title;
                    $out .= '</a><br/>';
                }
            }
        }
        else
        {
            $out .= __( "You have to create a exercise first, check the 'Exercies' menu on the left.", 'wp-ultimate-exercise' );
        }
        $out .= '</div>';

        return $out;
    }
}