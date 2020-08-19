<?php

class WPUEP_Exercise_Post_Type {

    public function __construct()
    {
        add_action( 'init', array( $this, 'register_exercise_post_type' ), 1);

        add_filter( 'post_class', array( $this, 'exercise_post_class' ) );

        // Remove exercise slug
        add_filter( 'post_type_link', array( $this, 'remove_exercise_slug' ) , 10, 3 );
        add_action( 'pre_get_posts', array( $this, 'remove_exercise_slug_in_parse_request' ) );
    }

    public function register_exercise_post_type()
    {
        $slug = WPUltimateExercise::option( 'exercise_slug', 'exercise' );

        $name = __( 'Excercises', 'wp-ultimate-exercise' );
        $singular = __( 'Exercise', 'wp-ultimate-exercise' );

        $taxonomies = array( '' );
        if(WPUltimateExercise::option( 'exercise_tags_use_wp_categories', '1' ) == '1' ) {
            $taxonomies = array( 'category', 'post_tag' );
        }

        $has_archive = WPUltimateExercise::option( 'exercise_archive_disabled', '0' ) == '1' ? false : true;		

        $args = apply_filters( 'wpuep_register_post_type',
            array(
                'labels' => array(
                    'name' => $name,
                    'singular_name' => $singular,
                    'add_new' => __( 'Add New', 'wp-ultimate-exercise' ),
                    'add_new_item' => __( 'Add New', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'edit' => __( 'Edit', 'wp-ultimate-exercise' ),
                    'edit_item' => __( 'Edit', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'new_item' => __( 'New', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'view' => __( 'View', 'wp-ultimate-exercise' ),
                    'view_item' => __( 'View', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'search_items' => __( 'Search', 'wp-ultimate-exercise' ) . ' ' . $name,
                    'not_found' => __( 'No', 'wp-ultimate-exercise' ) . ' ' . $name . ' ' . __( 'found.', 'wp-ultimate-exercise' ),
                    'not_found_in_trash' => __( 'No', 'wp-ultimate-exercise' ) . ' ' . $name . ' ' . __( 'found in trash.', 'wp-ultimate-exercise' ),
                    'parent' => __( 'Parent', 'wp-ultimate-exercise' ) . ' ' . $singular,
                ),
                'public' => true,
                'menu_position' => 5,
                'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'author', 'publicize', 'shortlinks' ),
                'yarpp_support' => true,
                'taxonomies' => $taxonomies,
                'menu_icon' =>  WPUltimateExercise::get()->coreUrl . '/img/icon_16.png',
                'has_archive' => $has_archive,
                'rewrite' => array(
                    'slug' => $slug
                )
            )
        );

        register_post_type( 'exercise', $args );
    }

    public function exercise_post_class( $classes )
    {
        if ( get_post_type() == 'exercise' )
        {
            $classes[] = 'post';
            $classes[] = 'type-post';
        }

        return $classes;
    }

    /*
     * Remove the slug from published exercise post permalinks.
     */
    public function remove_exercise_slug( $post_link, $post, $leavename ) {

        if(WPUltimateExercise::option( 'remove_exercise_slug', '0' ) == '1' ) {
            if ( 'exercise' != $post->post_type || 'publish' != $post->post_status ) {
                return $post_link;
            }

            $slug = WPUltimateExercise::option( 'exercise_slug', 'exercise' );
            $post_link = str_replace( '/' . $slug . '/', '/', $post_link );
        }

        return $post_link;
    }

    /*
     * Some hackery to have WordPress match postname to any of our public post types
     * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
     * Typically core only accounts for posts and pages where the slug is /post-name/
     */
    public function remove_exercise_slug_in_parse_request( $query ) {
        if(WPUltimateExercise::option( 'remove_exercise_slug', '0' ) == '1' ) {
            if ( !$query->is_main_query() ) return;
            if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
                return;
            }

            if ( !empty( $query->query['name'] ) ) {
                $query->set( 'post_type', array( 'post', 'exercise', 'page' ) );
            }
        }
    }
}