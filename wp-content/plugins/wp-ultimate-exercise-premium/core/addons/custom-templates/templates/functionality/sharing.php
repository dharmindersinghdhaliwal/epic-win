<?php
// Block needed for backwards compatibility
class WPUEP_Template_Exercise_Sharing extends WPUEP_Template_Block {

    public $editorField = 'exerciseSharing';

    public $columns;
    public $widths;

    public function __construct( $type = 'exercise-sharing' )
    {
        parent::__construct( $type );
    }

    public function output( $exercise, $args = array() )
    {
        if( !$this->output_block( $exercise, $args ) ) return '';

        $output = $this->before_output();

        // Backwards compatibility
        $this->add_style( 'text-align', 'center' );
        $this->add_style( 'text-align', 'center', 'td' );

        $this->add_style( 'vertical-align', 'top', 'td' );

        $this->columns = 4;
        $widths = array( '25%', '25%', '25%', 'auto' );

        $this->widths = $widths;
        foreach( $widths as $column => $width )
        {
            $this->add_style( 'width', $width, 'col-' . $column );
        }

        if( WPUltimateExercise::is_premium_active() ) {
            $twitter_text = WPUltimateExercise::option('exercise_sharing_twitter', '%title% - Powered by @WPUltimExercise');
            $pinterest_text = WPUltimateExercise::option('exercise_sharing_pinterest', '%title% - Powered by @ultimateexercise');
        } else {
            $twitter_text = '%title% - Powered by @WPUltimExercise';
            $pinterest_text = '%title% - Powered by @ultimateexercise';
        }

        $twitter_text = str_ireplace('%title%', $exercise->title(), $twitter_text);
        $pinterest_text = str_ireplace('%title%', $exercise->title(), $pinterest_text);

        ob_start();
?>
<table<?php echo $this->style(); ?>>
    <tbody>
    <tr>
        <td<?php echo $this->style( array( 'td', 'col-0' ) ); ?>>
            <div data-url="<?php echo $exercise->link(); ?>" data-text="<?php echo esc_attr( $twitter_text ); ?>" data-layout="vertical" class="wpuep-twitter"></div>
        </td>
        <td<?php echo $this->style( array( 'td', 'col-1' ) ); ?>>
            <div data-url="<?php echo $exercise->link(); ?>" data-layout="box_count" class="wpuep-facebook"></div>
        </td>
        <td<?php echo $this->style( array( 'td', 'col-2' ) ); ?>>
            <div data-url="<?php echo $exercise->link(); ?>" data-layout="tall" data-annotation="bubble" class="wpuep-google"></div>
        </td>
        <td<?php echo $this->style( array( 'td', 'col-3' ) ); ?>><?php
        if( !is_null( $exercise->image_url( 'full' ) ) ) { ?>
            <div data-url="<?php echo $exercise->link(); ?>" data-media="<?php echo $exercise->image_url( 'full' ); ?>" data-description="<?php echo esc_attr( $pinterest_text ); ?>" data-layout="above" class="wpuep-pinterest"></div>
        <?php } else { ?>&nbsp;<?php } ?></td>
    </tr>
    </tbody>
</table>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $exercise );
    }
}