<?php

class WPUEP_Exercise_Search_Widget extends WP_Widget {

    public function __construct()
    {
        parent::__construct(
            'wpuep_exercise_search_widget',
            __( 'WPUEP Exercise Search', 'wp-ultimate-exercise' ),
            array(
                'description' => __( 'A customizable Exercise Search widget.', 'wp-ultimate-exercise' )
            )
        );
    }

    public function widget( $args, $instance )
    {
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];
        if ( !empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo '<form role="search" method="get" action="' . get_home_url() . '">';
        echo '<input type="hidden" value="" name="wpuep-search" id="wpuep-search">';

        if(false) {
            // TODO Keyword search
        } else {
            echo '<input type="hidden" value="" name="s" id="s">';
        }

        $tags = $instance['filter_tags'];

        foreach( $tags as $id => $name )
        {
            // Get name at this point in time to have correct WPML translation
            $taxonomy = get_taxonomy( $id );
            $label = __( $taxonomy->labels->singular_name, 'wp-ultimate-exercise' );

            wp_dropdown_categories( array(
                'show_option_all' => $label,
                'orderby' => 'NAME',
                'hierarchical' => 1,
                'name' => 'exercise-' . $id,
                'taxonomy' => $id,
            ) );

            echo '<br/>';
        }

        echo '<input type="submit" value="' . __( 'Search', 'wp-ultimate-exercise' ) . '">';
        echo '</form>';

        echo $args['after_widget'];
    }

    public function form( $instance )
    {
        // Parameters
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Exercise Search', 'wp-ultimate-exercise' );
        $filter_tags = isset( $instance['filter_tags'] ) ? $instance['filter_tags'] : array();

        // Get tags that can be used to filter
        $tags = WPUltimateExercise::get()->tags();

        $exercise_tags = array();
        foreach( $tags as $id => $tag ) {
            $exercise_tags[$id] = $tag['labels']['singular_name'];
        }

        if( WPUltimateExercise::option( 'exercise_tags_use_wp_categories', '1' ) == '1' ) {
            $exercise_tags['post_tag'] = __( 'Tag', 'wp-ultimate-exercise' );
            $exercise_tags['category'] = __( 'Category', 'wp-ultimate-exercise' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php foreach( $exercise_tags as $id => $name ) {
            $checked = isset( $filter_tags[$id] ) ? ' checked="checked"' : '';
        ?>
            <p>
                <input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'filter_tags' ); ?>[<?php echo $id; ?>]" name="<?php echo $this->get_field_name( 'filter_tags' ); ?>[<?php echo $id; ?>]" value="<?php echo esc_attr( $name ); ?>" <?php echo $checked; ?>>
                <label for="<?php echo $this->get_field_id( 'filter_tags' ); ?>[<?php echo $id; ?>]"><?php _e( 'Display', 'wp-ultimate-exercise' ); ?> <?php echo strtolower( $name ); ?> <?php _e( 'filter', 'wp-ultimate-exercise' ); ?>?</label>
            </p>
        <?php } ?>
    <?php
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['filter_tags'] = array();

        if( isset( $new_instance['filter_tags'] ) ) {
            foreach( $new_instance['filter_tags'] as $id => $value ) {
                $instance['filter_tags'][$id] = $value;
            }
        }

        return $instance;
    }
}

add_action( 'widgets_init', create_function( '', 'return register_widget("WPUEP_Exercise_Search_Widget");' ) );