<?php
class WPUEP_Template_Exercise_Add_To_Shopping_List extends WPUEP_Template_Block {
    public $icon = 'fa-shopping-cart';
    public $editorField = 'addToShoppingList';
    public function __construct( $type = 'exercise-add-to-shopping-list' ){
        parent::__construct( $type );
    }
    public function icon( $icon ){
        $this->icon = $icon;
        return $this;
    }
    public function output( $exercise, $args = array() ){
        if( !$this->output_block( $exercise, $args ) ) return '';
        $icon = '<i class="fa ' . esc_attr( $this->icon ) . '"></i>';
        $classes = array();
        $shopping_list_exercises = array();
		$wp_session	=	WP_Session::get_instance();
		$COOKIE_E =	$wp_session['WPUEP_Shopping_List_Exercies_v2'];			
        if($COOKIE_E) {
            $shopping_list_exercises = explode( ';', stripslashes($COOKIE_E) );
        }
        $in_shopping_list = in_array( $exercise->ID(), $shopping_list_exercises );
        if( $in_shopping_list ) $classes[] = 'in-shopping-list';
        $tooltip_text = WPUltimateExercise::option( 'add_to_shopping_list_tooltip_text', __('Add to Shopping List', 'wp-ultimate-exercise') );
        $tooltip_alt_text = WPUltimateExercise::option( 'added_to_shopping_list_tooltip_text', __('This exercise is in your Shopping List', 'wp-ultimate-exercise') );
        if( $tooltip_text && $tooltip_alt_text ) $classes[] = 'exercise-tooltip';
        if( $in_shopping_list ) {
            $tooltip_text_backup = $tooltip_text;
            $tooltip_text = $tooltip_alt_text;
            $tooltip_alt_text = $tooltip_text_backup;
        }
        $this->classes = $classes;
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