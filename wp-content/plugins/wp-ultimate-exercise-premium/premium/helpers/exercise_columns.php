<?php

class WPUEP_Exercise_Columns {

    public function __construct()
    {
        add_filter( 'manage_edit-exercise_columns', array( $this, 'exercise_columns') );
        add_filter( 'manage_exercise_posts_custom_column' , array( $this, 'exercise_columns_content'), 10, 2 );
        add_filter( 'manage_exercise_posts_columns' , array( $this, 'exercise_columns_order' ) );
        add_filter( 'manage_edit-exercise_sortable_columns', array( $this, 'exercise_columns_sortable' ));
        add_filter( 'request', array( $this, 'exercise_columns_sort' ));
    }

    public function exercise_columns( $columns ) {
        // Thumbnails
        $columns['exercise_thumbnail'] = __( 'Featured image', 'wp-ultimate-exercise' );

        // User submissions
        $columns['exercise_author'] = __( 'Author', 'wp-ultimate-exercise' );

        // User ratings
        if( WPUltimateExercise::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
            $columns['exercise_rating'] = __( 'Rating', 'wp-ultimate-exercise' );
        }

        // Actions
        $columns['exercise_actions'] = __( 'Actions', 'wp-ultimate-exercise' );

        return $columns;
    }

    public function exercise_columns_content( $column, $post_ID ) {
        switch( $column ) {
            case 'exercise_thumbnail':
                echo the_post_thumbnail( array( 80, 80) );
                break;

            case 'exercise_author':
                $guestname = get_post_meta( $post_ID, 'exercise-author' );
                if( isset( $guestname['0'] ) ) {
                    echo $guestname['0'];
                } else {
                    echo get_the_author_meta( 'display_name' );
                }
                break;

            case 'exercise_rating':
                $rating = WPUEP_User_Ratings::get_exercise_rating( $post_ID );

                echo __( 'Votes', 'wp-ultimate-exercise' ) . ': ' . $rating['votes'];

                if( $rating['votes'] > 0 ) {
                    echo ' (<a href="#" class="reset-exercise-rating" data-exercise="'. $post_ID.'">' . __( 'reset', 'wp-ultimate-exercise' ) . '</a>)';
                }
                echo '<br/>';
                echo __( 'Rating', 'wp-ultimate-exercise' ) . ': ' . $rating['rating'];
                break;

            case 'exercise_actions':
                echo '<a href="#" class="clone-exercise" data-exercise="' . $post_ID . '" data-nonce="' . wp_create_nonce( 'exercise' ) . '">' . __( 'Clone Exercise', 'wp-ultimate-exercise' ) . '</a>';
                break;
        }
    }

    public function exercise_columns_order( $columns ) {
        $reordered = array(
            'cb' => '<input type="checkbox" />',
            'exercise_thumbnail' => __( 'Featured image', 'wp-ultimate-exercise' ),
            'title' => __( 'Title', 'wp-ultimate-exercise' ),
            'exercise_author' =>__( 'Author', 'wp-ultimate-exercise' ),
        );

        if( WPUltimateExercise::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
            $reordered = array_merge( $reordered, array(
                'exercise_rating' =>__( 'Rating', 'wp-ultimate-exercise' ),
            ) );
        }

        if( WPUltimateExercise::option( 'exercise_tags_use_wp_categories', '1' ) == '1' ) {
            $reordered = array_merge( $reordered, array(
                'categories' => __( 'Categories', 'wp-ultimate-exercise' ),
                'tags' => __( 'Tags', 'wp-ultimate-exercise' ),
            ) );
        }

        $reordered = array_merge( $reordered, array(
            'comments' => '<div title="Comments" class="comment-grey-bubble"</div>',
            'date' => __( 'Date', 'wp-ultimate-exercise' ),
        ) );

        $reordered = array_merge( $reordered, array(

            'exercise_actions' =>__( 'Actions', 'wp-ultimate-exercise' ),
        ) );

        return $reordered;
    }

    public function exercise_columns_sortable( $columns) {
        $columns['exercise_rating'] = 'exercise_rating';

        return $columns;
    }

    public function exercise_columns_sort( $vars ) {
        if ( is_admin() && isset( $vars['orderby'] ) && 'exercise_rating' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => 'exercise_user_ratings_rating',
                'orderby' => 'meta_value_num'
            ) );
        }

        return $vars;
    }
}