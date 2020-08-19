<?php

class WPUEP_Extended_Index_Shortcode {

    public function __construct()
    {
        remove_shortcode( 'ultimate-exercise-index' );
        add_shortcode( 'ultimate-exercise-index', array( $this, 'extended_index_shortcode' ) );
    }

    // TODO Could use some refactoring
    function extended_index_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'headers' => 'false',
            'group_by' => 'none',
            'sort_by' => 'title',
            'sort_order' => 'ASC',
            'limit_author' => '',
            'limit_by_tag' => 'false',
            'limit_by_values' => '',
            'limit_exercises' => '-1',
        ), $options );

        $headers = $options['headers'] == 'false' ? false : true;
        $group_by = strtolower( $options['group_by'] );

        if( $headers && $group_by == 'none' ) {
            $group_by = 'name';
        }

        $sort_by = strtolower( $options['sort_by'] );
        $sort_order = strtoupper( $options['sort_order'] );
        $limit_author = $options['limit_author'];
        $limit_by_tag = $options['limit_by_tag'] == 'false' || $options['limit_by_values'] == 'false' || !taxonomy_exists( $options['limit_by_tag'] ) ? false : strtolower( $options['limit_by_tag'] );
        $limit_by_values = strtolower( $options['limit_by_values'] );

        $limit_exercises_nbr = intval( $options['limit_exercises'] ) != 0 ? intval( $options['limit_exercises'] ) : -1;

        $sort_by = in_array( $sort_by, array( 'author', 'name', 'title', 'date', 'rand') ) ? $sort_by : 'title';
        $sort_order = in_array( $sort_order, array( 'ASC', 'DESC' ) ) ? $sort_order : 'ASC';

        $exercises_grouped = array();
        $limit_exercises = array();

        if( $limit_by_tag != false && strlen( $limit_by_values ) > 0 ) {
            $tag_values = explode( ';', $limit_by_values );

            foreach( $tag_values as $term )
            {
                $limit_exercises = array_merge(
                    $limit_exercises,
                    WPUltimateExercise::get()->query()->order_by( $sort_by )->order( $sort_order )->taxonomy( $limit_by_tag )->term( $term )->get()
                );
            }
        }
        else
        {
            $limit_exercises = WPUltimateExercise::get()->query()->get();
        }

        // Only exercises from a specific author or list of authors
        if( !is_null( $limit_author ) && $limit_author != '' )
        {
            if( $limit_author == 'current_user' ) {
                $limit_author = get_current_user_id();
            }

            if( $limit_author == 0 ) { // Not logged in
                $limit_exercises = array();
            } else {
                $limit_exercises = $this->intersect_exercise_arrays(
                    $limit_exercises,
                    WPUltimateExercise::get()->query()->order_by( $sort_by )->order( $sort_order )->author( $limit_author )->get()
                );
            }
        }

        if( !in_array( $group_by, array( 'none', 'name', 'title' ) ) && taxonomy_exists( $group_by ) )
        {
            $terms = get_terms( $group_by );

            foreach( $terms as $term ) {
                $exercises = WPUltimateExercise::get()->query()->order_by( $sort_by )->order( $sort_order )->taxonomy( $group_by )->term( $term->slug )->limit( $limit_exercises_nbr )->get();
                $exercises = $this->intersect_exercise_arrays( $limit_exercises, $exercises );

                if( count( $exercises ) > 0 ) {
                    $exercises_grouped[] = array(
                        'header' => $term->name,
                        'exercises' => $exercises
                    );
                }
            }
        }
        else if( in_array( $group_by, array( 'name', 'title' ) ) )
        {
            $exercises = WPUltimateExercise::get()->query()->order_by( 'title' )->order( $sort_order )->limit( $limit_exercises_nbr )->get();
            $exercises = $this->intersect_exercise_arrays( $limit_exercises, $exercises );

            $letters = array();

            foreach( $exercises as $exercise )
            {
                $title = $exercise->title();

                if($title != '')
                {
                    $first_letter = strtoupper( mb_substr( $title, 0, 1 ) );

                    if( !in_array( $first_letter, $letters ) )
                    {
                        $letters[] = $first_letter;
                        $exercises_grouped[] = array(
                            'header' => $first_letter,
                            'exercises' => array( $exercise )
                        );
                    } else {
                        $exercises_grouped[count( $exercises_grouped ) - 1]['exercises'][] = $exercise;
                    }
                }
            }
        }
        else if( $group_by == 'author' )
        {
            $args = array(
                'orderby' => 'display_name',
                'who' => 'authors'
            );
            $user_query = new WP_User_Query( $args );

            if( !empty( $user_query->results ) ) {
                foreach( $user_query->results as $user ) {

                    $author_exercises = $this->intersect_exercise_arrays(
                        $limit_exercises,
                        WPUltimateExercise::get()->query()->order_by( $sort_by )->order( $sort_order )->limit( $limit_exercises_nbr )->author( $user->ID )->get()
                    );

                    if( !empty( $author_exercises )) {
                        $exercises_grouped[] = array(
                            'header' => $user->display_name,
                            'exercises' => $author_exercises
                        );
                    }
                }
            }
        }
        else
        {
            $exercises = $this->intersect_exercise_arrays(
                $limit_exercises,
                WPUltimateExercise::get()->query()->order_by( $sort_by )->order( $sort_order )->taxonomy( $group_by )->limit( $limit_exercises_nbr )->get()
            );

            $exercises_grouped[] = array(
                'header' => false,
                'exercises' => $exercises
            );
        }

        $out = '<div class="wpuep-index-container">';
        if( count( $exercises_grouped ) == 0 ) {
            $out .= __( "You have to create a exercise first, check the 'Exercies' menu on the left.", 'wp-ultimate-exercise' );
        }
        else if( $exercises_grouped[0]['header'] == false )
        {
            foreach( $exercises_grouped[0]['exercises'] as $exercise )
            {
                $out .= '<a href="' . $exercise->link() . '">';
                $out .= $exercise->title();
                $out .= '</a><br/>';
            }
        }
        else
        {
            foreach( $exercises_grouped as $exercises_group )
            {
                $out .= '<h2>';
                $out .= $exercises_group['header'];
                $out .= '</h2>';

                foreach( $exercises_group['exercises'] as $exercise )
                {
                    $out .= '<a href="' . $exercise->link() . '">';
                    $out .= $exercise->title();
                    $out .= '</a><br/>';
                }
            }
        }

        $out .= '</div>';

        return $out;
    }

    /**
     * Limit array 2 by array 1
     */
    private function intersect_exercise_arrays( $arr1, $arr2 )
    {
        $allowed_exercises = array();

        foreach( $arr1 as $exercise )
        {
            $allowed_exercises[] = $exercise->ID();
        }

        foreach( $arr2 as $index => $exercise )
        {
            if( !in_array( $exercise->ID(), $allowed_exercises ) ) {
                unset( $arr2[$index] );
            }
        }

        return $arr2;
    }
}