<?php

class WPUEP_Thumbnails {

    public function __construct()
    {
        add_action( 'init', array( $this, 'theme_thumbnail_support' ), 20 );
        add_filter( 'post_thumbnail_html', array( $this, 'hide_theme_thumbnail' ), 10 );

        // TODO Refactor
        if ( function_exists( 'add_image_size' ) ) {
            add_image_size( 'exercise-thumbnail', 150, 9999 );
            add_image_size( 'exercise-large', 600, 9999 );
        }
    }

    public function hide_theme_thumbnail( $html )
    {
        if ( get_post_type() == 'exercise' && in_the_loop() )
        {
            $thumb = WPUltimateExercise::option( 'exercise_theme_thumbnail', 'archive' );

            if( $thumb == 'never' || ( $thumb == 'archive' && is_single() ) || ( $thumb == 'exercise' && !is_single() ) ) {
                $html = ''; // Hide theme thumbnail
            }
        }

        return $html;
    }

    public function theme_thumbnail_support()
    {
        $thumbs = get_theme_support( 'post-thumbnails' );

        if( $thumbs !== true )
        {
            $support = array( 'exercise' );

            if( is_array( $thumbs ) && !array_key_exists( 'exercise', $thumbs[0] ) )
            {
                $thumbs[0][] = 'exercise';
                $support = $thumbs[0];
            }

            add_theme_support( 'post-thumbnails', $support );
        }
    }
}