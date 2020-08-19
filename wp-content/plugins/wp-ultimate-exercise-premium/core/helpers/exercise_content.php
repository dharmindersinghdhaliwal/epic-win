<?php

class WPUEP_Exercise_Content {

    public function __construct()
    {
        add_filter( 'the_content', array( $this, 'content_filter' ), 10 );
        add_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10 );
    }

    public function content_filter( $content )
    {
        $ignore_query = !in_the_loop() || !is_main_query();
        if ( apply_filters( 'wpuep_exercise_content_loop_check', $ignore_query ) ) {
            return $content;
        }

        if ( get_post_type() == 'exercise' ) {
            remove_filter( 'the_content', array( $this, 'content_filter' ), 10 );

            $exercise = new WPUEP_Exercise( get_post() );

            if ( !post_password_required() && ( is_single() || WPUltimateExercise::option( 'exercise_archive_display', 'full' ) == 'full' || ( is_feed() && WPUltimateExercise::option( 'exercise_rss_feed_display', 'full' ) == 'full' ) ) )
            {
                $taxonomies = WPUltimateExercise::get()->tags();
                unset($taxonomies['ingredient']);

                $type = is_feed() ? 'feed' : 'exercise';
                $exercise_box = apply_filters( 'wpuep_output_exercise', $exercise->output_string( $type ), $exercise );

                if( strpos( $content, '[exercise]' ) !== false ) {
                    $content = str_replace( '[exercise]', $exercise_box, $content );
                } else if( preg_match("/<!--\s*nextpage.*-->/", $exercise->post_content(), $out ) ) {
                    // Add metadata if there is a 'nextpage' tag and there wasn't a '[exercise]' tag on this specific page
                    $content .= $exercise->output_string( 'metadata' );
                } else if( is_single() || !preg_match("/<!--\s*more.*-->/", $exercise->post_content(), $out ) ) {
                    // Add exercise box to the end of single pages or excerpts (unless there's a 'more' tag
                    $content .= $exercise_box;
                }
            }
            else
            {
                $content = str_replace( '[exercise]', '', $content ); // Remove shortcode from excerpt
                $content = $this->excerpt_filter( $content );
            }

            // Remove searchable part
            $content = preg_replace("/\[wpuep-searchable-exercise\][^\[]*\[\/wpuep-searchable-exercise\]/", "", $content);

            add_filter( 'the_content', array( $this, 'content_filter' ), 10 );
        }

        return $content;
    }

    public function excerpt_filter( $content )
    {
        $ignore_query = !in_the_loop() || !is_main_query();
        if ( apply_filters( 'wpuep_exercise_content_loop_check', $ignore_query ) ) {
            return $content;
        }

        if ( get_post_type() == 'exercise' ) {
            remove_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10) ;

            $exercise = new WPUEP_Exercise( get_post() );
            $excerpt = $exercise->excerpt();

	        $post_content = $exercise->post_content();
	        $post_content = trim( preg_replace("/\[wpuep-searchable-exercise\][^\[]*\[\/wpuep-searchable-exercise\]/", "", $post_content) );

            if( $post_content == '' && empty( $excerpt ) ) {
                $content = $exercise->description();
            } else if( $content == '' ) {
                $content = get_the_excerpt();
            }

            $content = apply_filters( 'wpuep_output_exercise_excerpt', $content, $exercise );

            add_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10 );
        }

        return $content;
    }
}