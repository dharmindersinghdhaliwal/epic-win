<?php

class WPUEP_Template_Yummly extends WPUEP_Template_Block {

    public $editorField = 'yummly';

    public function __construct( $type = 'yummly' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';


        $output = $this->before_output();
        ob_start();
?>
<a href="//yummly.com" class="YUMMLY-YUM-BUTTON">Yum</a>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}