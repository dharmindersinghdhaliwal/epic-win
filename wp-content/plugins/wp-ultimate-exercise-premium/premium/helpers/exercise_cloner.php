<?php

class WPUEP_Exercise_Cloner {

    private $fields_to_clone = array(
        'exercise_title',
        'exercise_description',
        'exercise_rating',
        'exercise_servings',
        'exercise_servings_normalized',
        'exercise_servings_type',
        'exercise_prep_time',
        'exercise_prep_time_text',
        'exercise_cook_time',
        'exercise_cook_time_text',
        'exercise_passive_time',
        'exercise_passive_time_text',
        'exercise_instructions',
        'exercise_notes',
    );

    public function __construct()
    {
        add_action( 'init', array( $this, 'eassets' ) );

        add_action( 'wp_ajax_clone_exercise', array( $this, 'ajax_clone_exercise' ) );
        add_action( 'wp_ajax_nopriv_clone_exercise', array( $this, 'ajax_clone_exercise' ) );
    }

    public function eassets()
    {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => '/js/exercise_cloner.js',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_posts',
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpuep_exercise_cloner',
                    'ajax_url' => WPUltimateExercise::get()->helper('eajax')->url(),
                    'nonce' => wp_create_nonce( 'clone_exercise' )
                )
            )
        );
    }

    public function ajax_clone_exercise()
    {
        $exercise_id = intval( $_POST['exercise'] );

        if( check_ajax_referer( 'clone_exercise', 'security', false ) && 'exercise' == get_post_type( $exercise_id ) )
        {
            $exercise = new WPUEP_Exercise( $exercise_id );

            $post = array(
                'post_title' => $exercise->title(),
                'post_type'	=> 'exercise',
                'post_status' => 'draft',
                'post_author' => get_current_user_id(),
            );

            // Necessary to set the post terms correctly in exercise_save.
            $_POST['exercise_ingredients'] = get_post_meta( $exercise->ID(), 'exercise_ingredients', true );

            $post_id = wp_insert_post($post);

            foreach( $this->fields_to_clone as $field ) {
                $val = get_post_meta( $exercise->ID(), $field, true );
                update_post_meta( $post_id, $field, $val );
            }

            $url = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
            echo json_encode( array( 'redirect' => $url ) );
        }
        die();
    }
}