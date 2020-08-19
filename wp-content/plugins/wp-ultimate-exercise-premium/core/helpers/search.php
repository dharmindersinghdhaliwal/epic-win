<?php

class WPUEP_Search {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'save' ), 15, 2 );

        add_shortcode( 'wpuep-searchable-exercise', array( $this, 'shortcode' ) );
    }

    /**
     * Handles saving of exercises
     */
    public function save( $id, $post )
    {
        if( $post->post_type == 'exercise' )
        {
            if ( !isset( $_POST['exercise_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['exercise_meta_box_nonce'], 'exercise' ) )
            {
                return $id;
            }

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

            remove_action( 'save_post', array( $this, 'save' ), 15, 2 );
            wp_update_post(
                array(
                    'ID' => $exercise->ID(),
                    'post_content' => $post_content,
                )
            );
            update_post_meta( $exercise->ID(), 'wpuep_text_search_3', time() );
            add_action( 'save_post', array( $this, 'save' ), 15, 2 );
        }
    }

    public function shortcode( $options )
    {
        // This is just to make sure the searchable part is not being output
    }
}