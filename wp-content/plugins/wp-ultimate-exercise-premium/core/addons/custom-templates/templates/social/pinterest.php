<?php

class WPUEP_Template_Pinterest extends WPUEP_Template_Block {

    public $editorField = 'pinterest';
    public $layout = 'none';

    public function __construct( $type = 'pinterest' )
    {
        parent::__construct( $type );
    }

    public function layout( $layout )
    {
        $this->layout = $layout;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        if( WPUltimateExercise::is_premium_active() ) {
            $text = WPUltimateExercise::option('exercise_sharing_pinterest', '%title% - Powered by @ultimateexercise');
        } else {
            $text = '%title% - Powered by @ultimateexercise';
        }

        $text = str_ireplace('%title%', $exercise->title(), $text);

        $output = $this->before_output();
        ob_start();
?>
<div data-url="<?php echo $exercise->link(); ?>" data-media="<?php echo $exercise->image_url( 'full' ); ?>" data-description="<?php echo esc_attr( $text ); ?>" data-layout="<?php echo $this->layout; ?>"<?php echo $this->style(); ?>></div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}