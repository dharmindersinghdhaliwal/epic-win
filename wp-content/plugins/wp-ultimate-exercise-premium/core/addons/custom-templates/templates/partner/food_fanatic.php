<?php

class WPUEP_Template_Food_Fanatic extends WPUEP_Template_Block {

    public $editorField = 'foodFanatic';

    public function __construct( $type = 'food-fanatic' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $url = urlencode( $exercise->link() );
        $img = WPUltimateExercise::get()->coreUrl . '/img/foodfanatic.png';

        $output = $this->before_output();
        ob_start();
?>
<a href="http://www.foodfanatic.com/exercise-box/add/?url=<?php echo $url; ?>" target="_blank"><img src="<?php echo $img; ?>"/></a>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}