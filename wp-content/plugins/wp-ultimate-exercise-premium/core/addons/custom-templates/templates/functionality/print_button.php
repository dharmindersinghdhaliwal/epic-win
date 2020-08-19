<?php

class WPUEP_Template_Exercise_Print_Button extends WPUEP_Template_Block {

    public $icon;

    public $editorField = 'printButton';

    public function __construct( $type = 'exercise-print-button' )
    {
        parent::__construct( $type );
    }

    public function icon( $icon )
    {
        $this->icon = $icon;
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        if( !$this->icon ) {
            $icon = '<img src="' . WPUltimateExercise::get()->coreUrl . '/img/printer.png">';
        } else {
            $icon = '<i class="fa ' . esc_attr( $this->icon ) . '"></i>';
        }

        $tooltip_text = WPUltimateExercise::option( 'print_tooltip_text', __('Print Exercise', 'wp-ultimate-exercise') );
        if( $tooltip_text ) $this->classes = array( 'exercise-tooltip' );

        $output = $this->before_output();
        ob_start();
?>
<a href="#"<?php echo $this->style(); ?> data-exercise-id="<?php echo $exercise->ID(); ?>"><?php echo $icon; ?></a>
<?php if( $tooltip_text ) { ?>
<div class="exercise-tooltip-content">
    <?php echo $tooltip_text; ?>
</div>
<?php } ?>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}