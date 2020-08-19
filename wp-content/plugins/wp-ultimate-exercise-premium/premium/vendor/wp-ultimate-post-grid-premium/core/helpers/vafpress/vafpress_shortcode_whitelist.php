<?php
function wpupg_eshortcode_generator_grids_by_date()
{
    $args = array(
        'post_type' => WPUPG_EPOST_TYPE,
        'post_status' => 'any',
        'posts_per_page' => -1,
        'nopaging' => true,
    );

    $query = new WP_Query( $args );
    $posts = $query->have_posts() ? $query->posts : array();
    $egrids = array();

    foreach ( $posts as $post )
    {
        $egrids[] = array(
            'value' => $post->post_name,
            'label' => $post->post_title,
        );
    }

    return $egrids;
}

VP_Security::instance()->whitelist_function('wpupg_eshortcode_generator_grids_by_date');