<?php

class WPUEP_Query_Posts {

    public function __construct()
    {
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts_exercises' ) );

        add_filter( 'init', array( $this, 'edit_posts_page_fix' ));
        add_filter( 'getarchives_where' , array( $this, 'monthly_archives_fix' ), 10 , 2 );
        add_filter( 'get_previous_post_where', array( $this, 'adjacent_posts_where_fix' ) );
        add_filter( 'get_next_post_where', array( $this, 'adjacent_posts_where_fix' ) );
        add_filter( 'get_previous_post_join', array( $this, 'adjacent_posts_join_fix' ) );
        add_filter( 'get_next_post_join', array( $this, 'adjacent_posts_join_fix' ) );

    }

    function pre_get_posts_exercises( $query ) {

        // Are exercises acting as posts?
        if( WPUltimateExercise::option( 'exercise_as_posts', '1' ) == '1' )
        {
            // Hide exercises in admin posts overview when enabled
            if( WPUltimateExercise::option( 'show_exercises_in_posts', '0' ) != '1' )
            {
                global $pagenow;

                if( $pagenow == 'edit.php' ) {
                    return;
                }
            }

            // Querying specific page (not set as home/posts page) or attachment
            if( !$query->is_home() ) {
                if( $query->is_page() || $query->is_attachment() ) {
                    return;
                }
            }

            // Querying a specific taxonomy
            if( !is_null( $query->tax_query ) ) {
                $tax_queries = $query->tax_query->queries;
                $exercise_taxonomies = get_object_taxonomies( 'exercise' );

                if( is_array($tax_queries) ) {
                    foreach( $tax_queries as $tax_query )
                    {
                        if( isset( $tax_query['taxonomy'] ) && $tax_query['taxonomy'] !== '' && !in_array( $tax_query['taxonomy'], $exercise_taxonomies ) ) {
                            return;
                        }
                    }
                }
            }

            $post_type = $query->get( 'post_type' );

            if( $post_type == '' || $post_type == 'post' )
            {
                $post_type = array( 'post','exercise' );
            }
            else if( is_array($post_type) )
            {
                if( in_array('post', $post_type) && !in_array('exercise', $post_type) ) {
                    $post_type[] = 'exercise';
                }
            }

            $post_type = apply_filters( 'wpuep_query_posts', $post_type, $query );

            $query->set( 'post_type', $post_type );

            return;
        }
        else
        {
            $secondary_query = !in_the_loop () || !$query->is_main_query();
            if ( apply_filters( 'wpuep_query_posts_loop_check', $secondary_query, $query ) ) {
                return;
            }

            if( WPUltimateExercise::option( 'exercise_tags_use_wp_categories', '1' ) == '1' && WPUltimateExercise::option( 'exercise_tags_show_in_archives', '1' ) == '1' )
            {
                if( is_category() || is_tag() ) {
                    $post_type = $query->get( 'post_type' );
                    if( $post_type ) {
                        $post_type = $post_type;
                    } else {
                        $post_type = array( 'post', 'exercise' );
                    }

                    $post_type = apply_filters( 'wpuep_query_posts', $post_type, $query );

                    $query->set( 'post_type', $post_type );
                    return;
                }
            }
        }

        return;
    }

    function edit_posts_page_fix()
    {
        if( WPUltimateExercise::option( 'exercise_as_posts', '1' ) == '1' && WPUltimateExercise::option( 'show_exercises_in_posts', '0' ) == '1' )
        {
            global $pagenow, $typenow;

            if( $pagenow == 'edit.php' && isset( $_REQUEST['post_type'] ) && $_REQUEST['post_type'] === 'Array' ) {
                $_REQUEST['post_type'] = 'post';
                $typenow = 'post';
            }
        }
    }

    public function monthly_archives_fix( $where , $r )
    {
        if( WPUltimateExercise::option( 'exercise_as_posts', '1' ) == '1' )
        {
            $where = str_replace( "post_type = 'post'" , "post_type IN ( 'post', 'exercise' )" , $where );
        }

        return $where;
    }

    function adjacent_posts_where_fix( $where ) {
        if( WPUltimateExercise::option( 'exercise_as_posts', '1' ) == '1' )
        {
            $where = str_replace( "post_type = 'post'" , "post_type IN ( 'post', 'exercise' )" , $where );
            $where = str_replace( "post_type = 'exercise'" , "post_type IN ( 'post', 'exercise' )" , $where );
        }
        return $where;
    }

    function adjacent_posts_join_fix( $join )
    {
        if( WPUltimateExercise::option( 'exercise_as_posts', '1' ) == '1' )
        {
            // Needed when using WPML
            $join = str_replace( "element_type = 'post_exercise'" , "element_type IN ( 'post_exercise', 'post_post' )" , $join );
        }
        return $join;
    }
}