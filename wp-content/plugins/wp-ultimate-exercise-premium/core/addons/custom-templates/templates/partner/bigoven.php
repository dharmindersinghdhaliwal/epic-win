<?php

class WPUEP_Template_Bigoven extends WPUEP_Template_Block {

    public $editorField = 'bigOven';

    public function __construct( $type = 'bigoven' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $title = __( 'BigOven - Save exercise or add to grocery list', 'wp-ultimate-exercise' );

        $output = $this->before_output();
        ob_start();
?>
<img src="http://media.bigoven.com/assets/images/saveexercise.png" style="cursor:pointer" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" onclick="javascript:wpuep_bigoven();"/>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}