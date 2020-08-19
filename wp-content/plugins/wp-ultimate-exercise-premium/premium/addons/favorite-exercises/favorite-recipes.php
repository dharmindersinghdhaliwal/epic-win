<?php

class WPUEP_Favorite_Exercies extends WPUEP_Premium_Addon {

    public function __construct( $name = 'favorite-exercises' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'init', array( $this, 'eassets' ) );

        // Ajax
        add_action( 'wp_ajax_favorite_exercise', array( $this, 'ajax_favorite_exercise' ) );
        add_action( 'wp_ajax_nopriv_favorite_exercise', array( $this, 'ajax_favorite_exercise' ) );

        // Shortcode
        add_shortcode( 'ultimate-exercise-favorites', array( $this, 'favorite_exercises_shortcode' ) );
    }

    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => $this->addonPath . '/js/favorite-exercises.js',
                'premium' => true,
                'public' => true,
                'setting' => array( 'favorite_exercises_enabled', '1' ),
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpuep_favorite_exercise',
                    'ajaxurl' => WPUltimateExercise::get()->helper('eajax')->url(),
                    'nonce' => wp_create_nonce( 'wpuep_favorite_exercise' ),
                )
            )
        );
    }

    public function ajax_favorite_exercise()
    {
        if(check_ajax_referer( 'wpuep_favorite_exercise', 'security', false ) )
        {
            $exercise_id = intval( $_POST['exercise_id'] );
            $user_id = get_current_user_id();

            $favorites = get_user_meta( $user_id, 'wpuep_favorites', true );
            $favorites = is_array( $favorites ) ? $favorites : array();

            if( in_array( $exercise_id, $favorites ) ) {
                $key = array_search( $exercise_id, $favorites );
                unset( $favorites[$key ] );
            } else {
                $favorites[] = $exercise_id;
            }

            update_user_meta( $user_id, 'wpuep_favorites', $favorites );
        }

        die();
    }

    public static function is_favorite_exercise( $exercise_id )
    {
        $user_id = get_current_user_id();

        $favorites = get_user_meta( $user_id, 'wpuep_favorites', true );
        $favorites = is_array( $favorites ) ? $favorites : array();

        return in_array( $exercise_id, $favorites );
    }

    public function favorite_exercises_shortcode( $options )
    {
        $options = shortcode_atts( array(
        ), $options );

        $user_id = get_current_user_id();

        $output = '';

        if( $user_id !== 0 ) {
            $favorites = get_user_meta( $user_id, 'wpuep_favorites', true );
            $favorites = is_array( $favorites ) ? $favorites : array();

            $exercises = WPUltimateExercise::get()->query()->ids( $favorites )->order_by('name')->order('ASC')->get();

            if( count( $favorites ) == 0 || count( $exercises ) == 0 ) {
                echo '<p class="wpuep-no-favorite-exercises">' . __( "You don't have any favorite exercises.", 'wp-ultimate-exercise' ) . '</p>';
            } else {
                $output .= '<ul class="wpuep-favorite-exercises">';
                foreach ( $exercises as $exercise ) {
                    $item = '<li><a href="' . $exercise->link() . '">' . $exercise->title() . '</a></li>';
                    $output .= apply_filters( 'wpuep_favorite_exercises_item', $item, $exercise );
                }
                $output .= '</ul>';
            }
        }

        return $output;
    }
}

WPUltimateExercise::loaded_addon( 'favorite-exercises', new WPUEP_Favorite_Exercies() );