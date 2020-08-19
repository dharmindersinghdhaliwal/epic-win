<?php
$args = array(
    'post_type' => 'exercise',
    'post_status' => 'any',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => 100,
    'meta_query' => array(
        array(
            'key' => 'wpuep_text_search_3',
            'compare' => 'NOT EXISTS',
        ),
    ),
);

$query = new WP_Query( $args );

if( $query->have_posts() ) {
    $posts = $query->posts;

    foreach( $posts as $post ) {
        $exercise = new WPUEP_Exercise( $post );

        $searchable_exercise = $exercise->title();

        $searchable_exercise .= ' - ';
        $searchable_exercise .= $exercise->description();
        $searchable_exercise .= ' - ';

        if( $exercise->has_ingredients() ) {
            $previous_group = null;
            foreach( $exercise->ingredients() as $ingredient ) {
                $group = isset( $ingredient['group'] ) ? $ingredient['group'] : '';

                if( $group !== $previous_group && $group ) {
                    $searchable_exercise .= $group . ': ';
                    $previous_group = $group;
                }

                $searchable_exercise .= $ingredient['ingredient'];
                if( trim( $ingredient['notes'] ) !== '' ) {
                    $searchable_exercise .= ' (' . $ingredient['notes'] . ')';
                }
                $searchable_exercise .= ', ';
            }
        }

        if( $exercise->has_instructions() ) {
            $previous_group = null;
            foreach( $exercise->instructions() as $instruction ) {
                $group = isset( $instruction['group'] ) ? $instruction['group'] : '';

                if( $group !== $previous_group && $group ) {
                    $searchable_exercise .= $group . ': ';
                    $previous_group = $group;
                }

                $searchable_exercise .= $instruction['description'] . '; ';
            }
        }

        $searchable_exercise .= ' - ';
        $searchable_exercise .= $exercise->notes();

        // Prevent shortcodes
        $searchable_exercise = str_replace( '[', '(', $searchable_exercise );
        $searchable_exercise = str_replace( ']', ')', $searchable_exercise );

        $post_content = preg_replace("/<div class=\"wpuep-searchable-exercise\"[^<]*<\/div>/", "", $post->post_content); // Backwards compatibility
        $post_content = preg_replace("/\[wpuep-searchable-exercise\][^\[]*\[\/wpuep-searchable-exercise\]/", "", $post_content);
        $post_content .= '[wpuep-searchable-exercise]';
        $post_content .= htmlentities( $searchable_exercise );
        $post_content .= '[/wpuep-searchable-exercise]';

        wp_update_post(
            array(
                'ID' => $exercise->ID(),
                'post_content' => $post_content,
            )
        );
        update_post_meta( $exercise->ID(), 'wpuep_text_search_3', time() );
    }
} else {
    // Finished migrating, all exercises have a full text search
    update_option( 'wpuep_cron_migrate_version', '2.3.3' );
}