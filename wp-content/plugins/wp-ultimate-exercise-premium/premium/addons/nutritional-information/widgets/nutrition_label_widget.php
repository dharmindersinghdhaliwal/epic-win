<?php

class WPUEP_Nutrition_Label_Widget extends WP_Widget {

    public function __construct()
    {
        parent::__construct(
            'wpuep_nutrition_label_widget',
            __( 'WPUEP Nutrition Label', 'wp-ultimate-exercise' ),
            array(
                'description' => __( 'Display the Nutrition Label of a exercise.', 'wp-ultimate-exercise' )
            )
        );
    }

    public function widget( $args, $instance )
    {
        $title = apply_filters( 'widget_title', $instance['title'] );

        // Only display on exercise pages if no Exercise ID set
        if( $instance['exercise'] == 0 && !is_singular( 'exercise' ) ) return;

        echo $args['before_widget'];
        if ( !empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if( $instance['exercise'] == 0 ) {
            $exercise = new WPUEP_Exercise( get_post() );
        } else {
            $exercise = new WPUEP_Exercise( $instance['exercise'] );
        }

        echo WPUltimateExercise::addon( 'nutritional-information' )->label( $exercise );

        echo $args['after_widget'];
    }

    public function form( $instance )
    {
        // Parameters
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Nutrition Label', 'wp-ultimate-exercise' );
        $exercise_id = isset( $instance['exercise'] ) ? $instance['exercise'] : 0;

        // All published exercises
        $exercises = WPUltimateExercise::get()->helper( 'cache' )->get( 'exercises_by_title' );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'exercise' ); ?>"><?php _e( 'Exercise', 'wp-ultimate-exercise' ); ?>:</label>
            <select name="<?php echo $this->get_field_name( 'exercise' ); ?>" id="<?php echo $this->get_field_id( 'exercise' ); ?>" class="widefat">
                <option value="0" id="0"<?php if( $exercise_id == 0) echo ' selected="selected"'; ?>><?php _e( 'Exercise shown on exercise page', 'wp-ultimate-exercise' );?></option>
                <?php
                foreach ( $exercises as $exercise ) {
                    $selected = $exercise['value'] == $exercise_id ? ' selected="selected"' : '';
                    echo '<option value="' . $exercise['value'] . '" id="' . $exercise['value'] . '"' . $selected . '>' . $exercise['label'] . '</option>';
                }
                ?>
            </select>
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['exercise'] = ( !empty( $new_instance['exercise'] ) ) ? intval( $new_instance['exercise'] ) : 0;

        return $instance;
    }
}

//  add_action( 'widgets_init', create_function( '', 'return register_widget("WPUEP_Nutrition_Label_Widget");' ) );
add_action('widgets_init', 'WPUEP_Nutrition_Label_Widget');

function WPUEP_Nutrition_Label_Widget()
{
    return register_widget('WPUEP_Nutrition_Label_Widget');
}

