<?php

class WPUEP_Template_Linkedin extends WPUEP_Template_Block {

    public $editorField = 'linkedin';
    public $layout = '';

    public function __construct( $type = 'linkedin' )
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

        $output = $this->before_output();
        ob_start();
?>
<div data-url="<?php echo $exercise->link(); ?>" data-layout="<?php echo $this->layout; ?>"<?php echo $this->style(); ?>></div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}