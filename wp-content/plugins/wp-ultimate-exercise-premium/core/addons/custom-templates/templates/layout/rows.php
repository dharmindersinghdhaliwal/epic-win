<?php

class WPUEP_Template_Rows extends WPUEP_Template_Block {

    public $rows;
    public $heights;

    public $editorField = 'rows';

    public function __construct( $type = 'rows' )
    {
        parent::__construct( $type );
    }

    public function rows( $rows )
    {
        $this->rows = $rows;
        return $this;
    }

    public function height( $heights )
    {
        $this->heights = $heights;
        foreach( $heights as $row => $height )
        {
            $this->add_style( 'height', $height, 'row-' . $row );
        }
        return $this;
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $args['max_width'] = $this->max_width && $args['max_width'] > $this->max_width ? $this->max_width : $args['max_width'];
        $args['max_height'] = $this->max_height && $args['max_height'] > $this->max_height ? $this->max_height : $args['max_height'];
        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $output = $this->before_output();

        ob_start();
?>
<div<?php echo $this->style(); ?>>
    <?php for( $i = 0; $i < $this->rows; $i++ ) { ?>
    <?php if( $this->show( $exercise, 'row-' . $i, $args ) ) { ?>
    <div class="wpuep-rows-row"<?php echo $this->style( 'row-' . $i ); ?>>
        <?php $this->output_children( $exercise, $i, 0, $args ); ?>
    </div>
    <?php } // end if show row ?>
    <?php } // end for rows ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}