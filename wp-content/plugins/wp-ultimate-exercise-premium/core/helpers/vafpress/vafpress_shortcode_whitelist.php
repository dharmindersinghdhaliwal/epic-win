<?php
function wpuep_shortcode_generator_templates()
{
    $template_list = array();
    $templates = WPUltimateExercise::addon( 'custom-templates' )->get_mapping();

    foreach ( $templates as $index => $template ) {
        $template_list[] = array(
            'value' => $index,
            'label' => $template,
        );
    }

    $template_list[] = array(
        'value' => 'default',
        'label' => 'Default',
    );

    return $template_list;
}

function wpuep_shortcode_generator_exercises_by_date()
{
    return WPUltimateExercise::get()->helper( 'cache' )->get( 'exercises_by_date' );
}

function wpuep_shortcode_generator_exercises_by_title()
{
    return WPUltimateExercise::get()->helper( 'cache' )->get( 'exercises_by_title' );
}

function wpuep_shortcode_generator_menus_by_date()
{
    return wpuep_shortcode_generator_menus('date', 'DESC');
}

function wpuep_shortcode_generator_menus_by_title()
{
    return wpuep_shortcode_generator_menus('title', 'ASC');
}

function wpuep_shortcode_generator_menus( $orderby, $order )
{
    $exercise_list = array();

    $args = array(
        'post_type' => 'menu',
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'no-paging' => true,
        'posts_per_page' => -1,
    );

    $menus = get_posts( $args );
    foreach ( $menus as $menu ) {

        $exercise_list[] = array(
            'value' => $menu->ID,
            'label' => $menu->post_title,
        );

    }

    return $exercise_list;
}

function wpuep_shortcode_generator_taxonomies()
{
    $taxonomy_list = array();

    $args = array(
        'object_type' => array('exercise')
    );

    $taxonomies = get_taxonomies( $args, 'objects' );

    foreach ($taxonomies  as $taxonomy ) {

        if($taxonomy->name != 'rating') {
            $taxonomy_list[] = array(
                'value' => $taxonomy->name,
                'label' => $taxonomy->labels->name,
            );
        }
    }

    return $taxonomy_list;
}

function wpuep_shortcode_generator_authors()
{
    return WPUltimateExercise::get()->helper( 'cache' )->get( 'exercise_authors' );
}

function wpuep_shortcode_generator_user_menu_authors()
{
    $menu_authors = array();
    $menu_author_ids = array();

    // Get all menus one by one
    $limit = 100;
    $offset = 0;

    while(true) {
        $args = array(
            'post_type' => 'menu',
            'post_status' => array( 'publish', 'private' ),
            'posts_per_page' => $limit,
            'offset' => $offset,
        );

        $query = new WP_Query( $args );

        if (!$query->have_posts()) break;

        $posts = $query->posts;

        foreach( $posts as $post ) {
            $id = $post->ID;
            $author = $post->post_author;

            if( !in_array( $author, $menu_author_ids ) )
            {
                $menu_author_ids[] = $author;

                $user = get_userdata( $author );

                $name = $user ? $user->display_name : __( 'n/a', 'wp-ultimate-exercise' );

                $menu_authors[] = array(
                    'value' => $author,
                    'label' => $name,
                );
            }

            wp_cache_delete( $id, 'posts' );
            wp_cache_delete( $id, 'post_meta' );
        }

        $offset += $limit;
        wp_cache_flush();
    }

    return $menu_authors;
}

VP_Security::instance()->whitelist_function('wpuep_shortcode_generator_templates');
VP_Security::instance()->whitelist_function('wpuep_shortcode_generator_exercises_by_date');
VP_Security::instance()->whitelist_function('wpuep_shortcode_generator_exercises_by_title');
VP_Security::instance()->whitelist_function('wpuep_shortcode_generator_taxonomies');
VP_Security::instance()->whitelist_function('wpuep_shortcode_generator_authors');
VP_Security::instance()->whitelist_function('wpuep_shortcode_generator_user_menu_authors');