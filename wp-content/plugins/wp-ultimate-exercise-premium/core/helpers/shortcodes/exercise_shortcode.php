<?php

class WPUEP_Exercise_Shortcode {

    public function __construct()
    {
        add_shortcode( 'ultimate-exercise', array( $this, 'exercise_shortcode' ) );
    }

    function exercise_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'id' => 'random', // If no ID given, show a random exercise
            'template' => 'default'
        ), $options );

        $exercise_post = null;

        if( $options['id'] == 'random' ) {

            $posts = get_posts(array(
                'post_type' => 'exercise',
                'nopaging' => true
            ));

            $exercise_post = $posts[ array_rand( $posts ) ];
        } else {
            $exercise_post = get_post( intval( $options['id'] ) );
        }

        if( !is_null( $exercise_post ) && $exercise_post->post_type == 'exercise' && ( !is_feed() || WPUltimateExercise::option( 'exercise_rss_feed_shortcode', '1' ) == '1' ) )
        {
            $exercise = new WPUEP_Exercise( $exercise_post );

            $type = is_feed() ? 'feed' : 'exercise';
            $template = is_feed() ? null : $options['template'];
            $output = apply_filters( 'wpuep_output_exercise', $exercise->output_string( $type, $template ), $exercise );
        }
        else
        {
            $output = '';
        }

        return do_shortcode( $output );
    }
}