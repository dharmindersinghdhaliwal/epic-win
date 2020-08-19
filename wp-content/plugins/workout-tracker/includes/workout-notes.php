<?php
class Workout_Notes{
	public static function init(){
		$class = __CLASS__;
        new $class;
    }

	public function __construct(){
		add_action( 'init', array($this,'register_workout_tracker'), 0 );
		add_action( 'wp_ajax_get_workout_notes',array($this,'get_workout_notes'));
		add_action('wp_ajax_nopriv_get_workout_notes',array($this,'get_workout_notes'));
		add_action( 'wp_ajax_save_workout_notes',array($this,'save_workout_notes'));
		add_action('wp_ajax_nopriv_save_workout_notes',array($this,'save_workout_notes'));
		add_action( 'wp_ajax_add_post',array($this,'add_post'));
		add_action('wp_ajax_nopriv_add_post',array($this,'add_post'));
		##SHORTCODE
		add_shortcode('display_work_notes',array($this,'display_work_notes'));
		add_shortcode( 'work_tracking_search_box_for_notes',array($this,'work_tracking_search_box_for_notes'));
	}

	function register_workout_tracker() {
		$labels = array(
			'name'                  => _x('Workout Tracker Record','Post Type General Name','cf' ),
			'singular_name'         => _x('Workout Tracker Records','Post Type Singular Name','cf' ),
			'menu_name'             => __('Workout Tracker', 'cf' ),
			'name_admin_bar'        => __('Workout Tracker', 'cf' ),
			'archives'              => __('Item Archives', 'cf' ),
			'attributes'            => __('Item Attributes', 'cf' ),
			'parent_item_colon'     => __('Parent Item:', 'cf' ),
			'all_items'             => __('All Items', 'cf' ),
			'add_new_item'          => __('Add New Item', 'cf' ),
			'add_new'               => __('Add New', 'cf' ),
			'new_item'              => __('New Item', 'cf' ),
			'edit_item'             => __('Edit Item', 'cf' ),
			'update_item'           => __('Update Item', 'cf' ),
			'view_item'             => __('View Item', 'cf' ),
			'view_items'            => __('View Items', 'cf' ),
			'search_items'          => __('Search Item', 'cf' ),
			'not_found'             => __('Not found', 'cf' ),
			'not_found_in_trash'    => __('Not found in Trash', 'cf' ),
			'not_found_in_trash'    => __('Not found in Trash', 'cf' ),
			'featured_image'        => __('Featured Image', 'cf' ),
			'set_featured_image'    => __('Set featured image', 'cf' ),
			'remove_featured_image' => __('Remove featured image', 'cf' ),
			'use_featured_image'    => __('Use as featured image', 'cf' ),
			'insert_into_item'      => __('Insert into item', 'cf' ),
			'uploaded_to_this_item' => __('Uploaded to this item', 'cf' ),
			'items_list'            => __('Items list', 'cf' ),
			'items_list_navigation' => __('Items list navigation', 'cf' ),
			'filter_items_list'     => __('Filter items list', 'cf' ),
		);
		$args = array(
			'label'                 => __('Workout Tracker Record', 'cf' ),
			'description'           => __('Post Type Description', 'cf' ),
			'labels'                => $labels,
			'supports'              => array('title','custom-fields', ),
			'taxonomies'            => array('wt_category'),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type('workout_tracker',$args);
	}

	public function display_work_notes(){?>
		<div class="work-notes-main">
			<div class="comment-section">
				<a href="javascript:void(0)" class="record_note">Record Session Notes</a>
				<textarea rows="4" class="cmt" style="display:none"></textarea>
			</div>
			<h2>Trainer Notes</h2>
			<div class="display_comment">
			</div>
		</div><?php
	}

	public function save_workout_notes(){
		 if($_REQUEST){
			 $act			=	$_REQUEST['act'];
			 $metaid		=	get_current_user_id();
			 $comment		=	$_POST['comment'];
			 $pid			=	$_POST['postid'];
			 $free_from	=	$_POST['freefrom'];
			 $exercises	=	$_POST['exercises'];
			 $num			=	$_POST['num'];
			 $num			=	$num+1;
			 $json			=	json_encode($exercises);
			 $inbetween	=	$_POST['inbetween'];
			 $txtarea		=	$_POST['txtarea'];
			 $rmwght		=	$_POST['rmwght'];
			 $reps			=	json_encode($_POST['reps']);
			 $calweight	=	json_encode($_POST['calweight']);
			 $warmup		=	json_encode($_POST['warmup']);
			 $time			=	date('h:i:s:a',current_time( 'timestamp', 0 ));			
			 $date			=	date("M-d");
			 if($act=='wt_save_trainer_notes'){
				 if(!empty($comment)){
					 update_post_meta($pid,'notes_date_'.$num,$date.'_'.$time);
					 update_post_meta($pid,'trainer_who_commented_'.$num,$metaid);
					 $notes	= update_post_meta($pid,'tnotes_'.$num,$comment);
					 if($notes){echo '1';}
				 }
			 }			 
			 if($act=='wt_save_program_data_exercise'){
				 Workout_Notes::wt_save_program_data_exercise($pid,$json);
			}
			if($act=='wt_save_free_form_data'){
				echo	Workout_Notes::wt_save_free_form_data($pid,$free_from);
			}
			if($act=='save_calulator_tab_data'){
				Workout_Notes::wt_save_calculator_data($pid,$inbetween,$txtarea,$rmwght,$reps,$calweight,$warmup);
			}
		}
		die();
	 }

	 public function wt_save_program_data_exercise($pid,$json){
		 $exercise	=	update_post_meta($pid,'exercise_list',$json);
		 if($exercise){	echo '1';	}
		 else{'Exercise list not updated';}
	 }

	 public function wt_save_free_form_data($pid,$free_from){
		 $form	=	update_post_meta($pid,'free_form_data',$free_from);
		 if($form){ echo '1';}
		 else{	 echo 'Form Could not updated'; }
	 }

	public function wt_save_calculator_data($pid,$inbetween,$txtarea,$rmwght,$reps,$calweight,$warmup){
		 if(!empty($inbetween)){ 	update_post_meta($pid,'inbetween',$inbetween); }
		 if(!empty($txtarea)){	update_post_meta($pid,'txtarea',$txtarea); }
		 if(!empty($rmwght)){		update_post_meta($pid,'rmwght',$rmwght); }
		 if(!empty($reps)){		update_post_meta($pid,'reps',$reps);}
		 if(!empty($calweight)){	update_post_meta($pid,'calweight',$calweight);}
		 if(!empty($warmup)){		update_post_meta($pid,'warmup',$warmup); }
		echo '1';
	}

	 public function add_post(){
		 if($_REQUEST){
			 $uid			=	$_POST['uid'];
			 $post_title	=	$_POST['title'];
			 $my_post 	= array(
				'ID'				=>	 $post_id,
				'post_title'		=>  $post_title,
				'post_status'		=> 'publish',
				'post_author'		=> $uid,
				'post_type'		=> 'workout_tracker',
			);
			$post_id	=	wp_insert_post($my_post);
			if($post_id){	echo $post_id;}
			else{	echo 'error!';}
		 }
		 die();
	 }

	public function get_workout_notes(){
		if($_REQUEST){
			$pid		=	$_REQUEST['pid'];
			global $epic;	
			$notes	=	Workout_Notes::get_number_of_comment($pid);
			for($i=count($notes);$i>0;$i--){
				$counter = $i-1;
				$trainer_id	=	get_post_meta($pid,'trainer_who_commented_'.$i,true);
				$time			=	get_post_meta($pid,'notes_date_'.$i,true);
				echo '<div>'.$epic->pic($trainer_id, 'medium');
				$user_info = get_userdata($trainer_id);
				echo '<p class="work-name">'.$user_info->first_name .' '. $user_info->last_name.'</p>';
				echo '<span>'.implode("   ",explode('_',$time)).'</span><br>';				
				echo '</div>';
				$note		=	$notes[$counter][0];
				echo '<p class="cnotes">'.get_post_meta($pid,$note,true).'</p>';
			}
		}
		die();
	}
		
	public function get_number_of_comment($pid){
		global $wpdb;
		$results = $wpdb->get_results(
		"SELECT meta_key  FROM {$wpdb->prefix}postmeta
		WHERE meta_key LIKE 'tnotes_%'	AND post_id=".$pid,ARRAY_N);
		return $results;
	}
	
	public function work_tracking_search_box_for_notes(){
		add_thickbox();	?>
			<div style="margin:0 auto;padding-left:32%">
				<input type="hidden" name="ajax_loader" id="ajax_loader" value="<?php echo get_template_directory_uri(); ?>/images/ajax.gif"/>
				<input type="hidden" id="work_email" name="work_email" />
				<input type="hidden" id="work_program_id" name="work_program_id"/>
				 <input type="hidden" id="work_gender" name="work_gender" />
				<input type="hidden" id="work_age" name="work_age"/>
				<span style="padding-left:10%">
					<input placeholder="Search Client" class="user-menus-title" type="text" name="search_user" id="search_user" autocomplete="off" />
				</span>
				<span id="search_work_program" style="padding-left:3%"><button  class="serch_user_notes">Search Client</button></span>
			</div>
			<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
			<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.css">
			<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
			<script src="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.min.js"></script>
			<script type="text/javascript">
				
				jQuery(document).ready(function(e){(function($){
					var availableTags = <?php echo epic_get_list_of_all_user(); ?>;
					jQuery('#search_user').autocomplete({
						source:availableTags,select:function(event,ui){
							event.preventDefault();
							console.log(ui.item);
							$('#work_email').val(ui.item.value);
							$('#work_gender').val(ui.item.gender);
							$('#work_age').val(ui.item.age)
							var lbl=$(this).val(ui.item.label);
						},
					});					
					jQuery('#search_user').keyup(function(){
						var user_name =	jQuery.trim( jQuery( this ).val());
						if( user_name == ''){
							jQuery( '#email_work_program' ).hide();
							jQuery( '#print_work_program' ).hide();
							jQuery( '#selected_work_programs' ).empty();
							jQuery( '#selected_work_programs_content' ).empty();
						}
					});
				}(jQuery))});
			</script>
	<?php }
}
add_action('plugins_loaded',array('Workout_Notes','init'));