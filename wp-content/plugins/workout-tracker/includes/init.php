<?php
if(!defined('ABSPATH')){
	exit;
}

class EWP_WT_Init{
	public static function init(){
        $class = __CLASS__;
        new $class;
    }
	
	function __construct(){
		add_shortcode('tracker_table',array($this,'tracker_table_program_data'));
		add_shortcode('strength_data',array($this,'tracker_table_strength_data'));
		add_action( 'wp_enqueue_scripts',array($this,'css'));
		add_action( 'wp_enqueue_scripts',array($this,'js'));
		##SHORTCODE
		add_shortcode('display_workout_tracker',array($this,'display_workout_tracker'));
	}

	#-----------------#
	# Register Style  #
	#-----------------#
	function css() {
		wp_register_style('wt-css',WT_PLUGIN_DIR.'css/css.css',false,'0.0.1');
		wp_enqueue_style('wt-css');
	}
	#-------------#
	# Register JS #
	#-------------#
	function js() {		
		wp_register_script('popupoverlay',WT_PLUGIN_DIR.'js/jquery.popupoverlay.js',array('jquery'),'0.0.1',false);
		wp_enqueue_script('popupoverlay');		
	}
	
	public function display_workout_tracker(){		
		if(is_user_logged_in()) {
			$user = new WP_User(get_current_user_id());
			if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
				foreach ( $user->roles as $role )	{
					if($role=='administrator'){
						echo do_shortcode('[work_tracking_search_box_for_notes]');
					}
					else{?>
					<script>
					jQuery(document).ready(function(){(function($){
						wt_the_user_data_load(<?php echo get_current_user_id();?>);
						}(jQuery))});
					</script>
				<?php }
				}
			}
		} ?>
		<script src="<?php echo WT_PLUGIN_DIR ;?>js/js.js"></script>
		<a href="javascript:void(0)"class="submit_workout_notes"style="display:none">Submit</a>
		<div class="workout-main" style="display:none"> <!--workout-main Starts-->
		<?php echo do_shortcode('[Workout_tracker_tab]'); ?>
		
			<div id="loadingm" class="modal">
				  <div class="modal-content">
					<div class="modal-header">
					  <h2>Loading...</h2>
					</div>
					<div class="modal-body">
					<img src='<?php echo esc_url( plugins_url( 'img/x2_writing.gif', dirname(__FILE__) ) );?>' width="150" height="150" />
					</div>					
				  </div>
			</div>
			
			<div id="slide" class="well">
				<button class="slide_close btn btn-default">Close</button>
			<?php 	echo do_shortcode('[display_work_notes]');?>
			</div>
		<?php

			 if( current_user_can('administrator')) {
				 echo EWP_WT_Init::payment_method_field();
			 }
		?>
            <!-- The Modal -->
		<div id="myModal" class="modal"></div>
       
		</div> <!--workout-main Ends-->
	<?php
	}
	public function payment_method_field(){
		?>
			 <div class="paymentStyle"> 
				<span>Payment Style</span>
				<input type="text" id="paymentStyle" value="<?php get_user_meta(get_current_user_id(),'Workout_Payment_Method' )?>">
				<a href="javascript:void(0)" id="updateMethod">Update</a>
			</div>
		<?php
	}
	
}
add_action('plugins_loaded',array('EWP_WT_Init','init'));