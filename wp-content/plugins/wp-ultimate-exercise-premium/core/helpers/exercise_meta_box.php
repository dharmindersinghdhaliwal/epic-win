<?php
class WPUEP_Exercise_Meta_Box {
	
    private $buttons_added = false;

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'add_exercise_meta_box' ));
        add_action( 'admin_menu', array( $this, 'remove_exercise_meta_boxes' ));

        if( is_admin() ) {
            add_action( 'media_buttons_context',  array( $this, 'add_shortcode_button' ) );
        }


        WPUltimateExercise::get()->helper('eassets')->add(
            array(
                'file' => '/css/admin_exercise_form.css',
                'admin' => true,
                'page' => 'exercise_form',
            ),
            array(
                'file' => '/js/exercise_form.js',
                'admin' => true,
                'page' => 'exercise_form',
                'deps' => array(
                    'jquery',
                    'jquery-ui-sortable',
                    'suggest',
                ),
                'data' => array(
                    'name' => 'wpuep_exercise_form',
                    'coreUrl' => WPUltimateExercise::get()->coreUrl,
                )
            )
        );
    }

    public function add_shortcode_button( $context )
    {
        $screen = get_current_screen();

        if( $screen->id == 'exercise' && !$this->buttons_added ) {
            $context .= '<a href="#" id="insert-exercise-shortcode" class="button" data-editor="content" title="Add Exercise Box">';
            $context .= __( 'Add Exercise Box', 'wp-ultimate-exercise' );
            $context .= '</a>';

            // Prevent adding buttons to other TinyMCE instances on the exercise edit page
            $this->buttons_added = true;
        }

        return $context;
    }

    public function add_exercise_meta_box()
    {
        add_meta_box(
            'exercise_meta_box',
            __( 'Exercise', 'wp-ultimate-exercise' ),
            array( $this, 'exercise_meta_box_content' ),
            'exercise',
            'normal',
            'high'
        );
    }

    public function exercise_meta_box_content( $post )
    {
        $exercise = new WPUEP_Exercise( $post );
        include( WPUltimateExercise::get()->coreDir . '/helpers/exercise_form.php' );
    }

    public function remove_exercise_meta_boxes()
    {
        remove_meta_box('tagsdiv-ingredient', 'exercise', 'side');
        remove_meta_box('ingredientdiv', 'exercise', 'side');
        remove_meta_box('stardiv', 'exercise', 'side');
    }
}