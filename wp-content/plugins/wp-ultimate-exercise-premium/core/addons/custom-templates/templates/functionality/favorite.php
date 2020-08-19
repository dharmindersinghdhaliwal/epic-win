<?php

class WPUEP_Template_Exercise_Favorite extends WPUEP_Template_Block {

    public $icon = 'fa-heart-o';
    public $iconAlt = 'fa-heart';

    public $editorField = 'favoriteExercise';

    public function __construct( $type = 'exercise-favorite' )
    {
        parent::__construct( $type );
    }

    public function icon( $icon )
    {
        $this->icon = $icon;
        return $this;
    }

    public function iconAlt( $iconAlt )
    {
        $this->iconAlt = $iconAlt;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';
        if( !is_user_logged_in() || !WPUltimateExercise::is_addon_active( 'favorite-exercises' ) ) return '';

        $current_icon = WPUEP_Favorite_Exercies::is_favorite_exercise( $exercise->ID() ) ? $this->iconAlt : $this->icon;

        $icon = '<i class="fa ' . esc_attr( $current_icon ) . '" data-icon="' . esc_attr( $this->icon ) . '" data-icon-alt="' . esc_attr( $this->iconAlt ) . '"></i>';

        $tooltip_text = WPUltimateExercise::option( 'favorite_exercises_tooltip_text', __('Add to your Favorite Exercies', 'wp-ultimate-exercise') );
        $tooltip_alt_text = WPUltimateExercise::option( 'favorited_exercises_tooltip_text', __('This exercise is in your Favorite Exercies', 'wp-ultimate-exercise') );
        if( $tooltip_text && $tooltip_alt_text ) $this->classes = array( 'exercise-tooltip' );

        if( WPUEP_Favorite_Exercies::is_favorite_exercise( $exercise->ID() ) ) {
            $tooltip_text_backup = $tooltip_text;
            $tooltip_text = $tooltip_alt_text;
            $tooltip_alt_text = $tooltip_text_backup;
        }

        $output = $this->before_output();
        ob_start();
?>
<a href="#"<?php echo $this->style(); ?> data-exercise-id="<?php echo $exercise->ID(); ?>"><?php echo $icon; ?></a>
<?php if( $tooltip_text && $tooltip_alt_text ) { ?>
    <div class="exercise-tooltip-content">
        <div class="tooltip-shown"><?php echo $tooltip_text; ?></div>
        <div class="tooltip-alt"><?php echo $tooltip_alt_text; ?></div>
    </div>
<?php } ?>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}