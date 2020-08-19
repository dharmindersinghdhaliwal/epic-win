<?php
/*
Plugin Name:Epic Data Reset
Plugin URI: #
Description: To remove data: points, achievements, attendance 
Version: 1.1
Tested up to: 4.2.4
Author: CodeFlox
Author URI: http://www.codeflox.com
Text Domain: epic
Domain Path: /lang/
*/

class Epic_data_reset{
	
	public function __construct(){
		add_action('admin_menu',array($this,'reset_add_menu'));
		add_action('wp_ajax_epic_data_clear_ajax',array($this,'epic_data_clear_ajax'));
		add_action('wp_ajax_nopriv_epic_data_clear_ajax',array($this,'epic_data_clear_ajax'));	
		add_filter( 'send_email_change_email', '__return_false' );			
	}
	
	/**
	*	Add Admin Menu
	*
	*	@since: 1.0
	*/
	public function reset_add_menu(){
		add_menu_page('Epic Data Reset','Epic Data Reset','administrator',__FILE__,array('Epic_data_reset','epic_data_reset_page'),plugins_url('epic.png',__FILE__));
	}
	
	/**
	*	Clear epic points
	*
	*	@since: 1.0
	*/
	public  function clear_epic_points(){
		global $wpdb;

		$table_prefix	=	$wpdb->prefix;

		$users	=	epic_data_reset::get_all_users();
		foreach($users as $uid){
			//delete_user_meta( $uid,'dismissed_wp_pointers');
			$epic_meta =	$table_prefix.'_dpa_points';
			delete_user_meta( $uid,$epic_meta );
		}
	}
	
	/**
	*	Clear achievements
	*
	*	@since: 1.0
	*/
	public  function clear_achievements(){		
		global $wpdb;
		$users	=	epic_data_reset::get_all_users();
		$achievements	=	epic_data_reset::get_all_achievement_ids();

		foreach($users as $uid){						
			epic_data_reset::reset_achievement_by_id($achievements,$uid);			
			delete_user_meta($uid,'epic_notic');
			delete_user_meta($uid, $wpdb->prefix.'_dpa_unlocked_count');			
			delete_user_meta($uid,'_dpa_last_unlocked');
			delete_user_meta($uid,'dismissed_wp_pointers');
			$users[] = $uid;
		}


	}
	
   /**
	*	remove achievement by id
	*
	*	@param:	array() $achievement_ids
	*	@param: int $user_id
	*/			
	public function reset_achievement_by_id($achievement_id,$user_id){				
		$achievements = is_array($achievement_id) ? $achievement_id : array($achievement_id);		
		// Remove achievements :(		
		if ( ! empty( $achievements ) ) {
			foreach ( $achievements as $aid ){
				dpa_delete_achievement_progress( $aid, $user_id );
			}			
		}		
	}

	/**
	*	Clear all attendance
	*
	*	@since: 1.0
	*/
	public function clear_attendance(){
		$attendanceArr	=	epic_data_reset::get_all_attendance();
		$pst			=	epic_data_reset::get_all_posts();
		for($k=0;$k<count($pst);$k++){
			$pid	=	$pst[$k];
			for($i=0;$i<count($attendanceArr);$i++){
				$at	=	$attendanceArr[$i];
				delete_post_meta($pid,$at);
			}
		}
	}
	
	/**
	*	Clear user meta
	* 	function does not delete user attendance from classes meta
	*
	*	@since: 1.1
	*/
	public function clear_user_attendance_record_meta(){
		$users	=	epic_data_reset::get_all_users();
		foreach($users as $uid){
			delete_user_meta($uid,'_dpa_ice');
			delete_user_meta($uid,'fire_class');
			delete_user_meta($uid,'pt_sessions_attended');
			delete_user_meta($uid,'_dpa_first_strike_boxing');
		}
	}
	
	/**
	*	Retrieve all users
	*
	*	@return: array
	*	@since: 1.0
	*/
	public function get_all_users(){
		$users		=	get_users();
		$newArray	=	array();
		foreach ($users as $user){
			$uid	=	$user->ID;
			array_push($newArray,$uid);
		}
		return $newArray;
	}
	
	/**
	*	Retrieve attendance of all users
	*
	*	@return: array
	*	@since: 1.0
	*/
	public function get_all_attendance(){
		global $wpdb;
		$pfx		=	$wpdb->base_prefix;
		$aposts		=	$wpdb->get_results("SELECT * FROM `".$pfx."postmeta` WHERE `meta_key` LIKE '%attendance_%'");
		$newArr		=	array();
		if(!empty($aposts)){
			foreach($aposts as $ap){
				array_push($newArr,$ap->meta_key);
			}
		}
		return $newArr;
	}
	
	/**
	*	Retrieve ids of all achievements
	*
	*	@return: array
	*	@since: 1.0
	*/
	public function get_all_achievement_ids(){
		$args = array(
					'posts_per_page'=>	-1,
					'post_type'	=> 'achievement',
					'post_status'	=> 'publish',
					'fields'		=>	'ids'
			);
		$achievements	=	get_posts($args);
		return $achievements;
	}
	
	/**
	*	Retrieve meta key
	*
	*	@return: array
	*	@since: 1.0
	*/
	public function get_all_posts(){
		global $wpdb;
		$pfx	=	$wpdb->base_prefix;
		$aposts	=	$wpdb->get_results("SELECT * FROM `".$pfx."postmeta` WHERE `meta_key` LIKE '%attendance_%'");
		$newArr	=	array();
		if($aposts&&!empty($aposts)){
			foreach($aposts as $ap){
				array_push($newArr,$ap->post_id);
			}
		}
		return $newArr;
	}	
	
	/**
	*	ajax function
	*
	*	@since: 1.0
	*/
	public function epic_data_clear_ajax(){		
		if($_REQUEST){
			$act	=	$_REQUEST['act'];
			if($act==1){
				epic_data_reset::clear_epic_points();
				$response	=	array(
					'response'=>1,
					'message'=>'Epic Point Data has been cleared!'
				);
			}
			if($act==2){
				epic_data_reset::clear_achievements();
				$response	=	array(
					'response'=>1,
					'message'=>'Epic Achievement Data has been cleared !'
				);				
			}
			if($act==3){
				epic_data_reset::clear_attendance();	
				$response	=	array(
					'response'=>1,
					'message'=>'Epic Attendance Data has been cleared !'
				);
			}
			if($act==4){
				epic_data_reset::clear_epic_points();
				epic_data_reset::clear_achievements();
				epic_data_reset::clear_attendance();
				$response	=	array(
					'response'=>1,
					'message'=>'Epic All Data has been cleared !'
				);
			}
			if($act==5){
				epic_data_reset::clear_user_attendance_record_meta();
				$response	=	array(
					'response'	=>	1,
					'message'	=>	'All users attendance record has been cleared !'
				);
			}
			echo json_encode( $response );
		}
		die();
	}
	
	public function epic_data_reset_page(){
		?>
		<div class="wrap" id="epci_rest_controle">
			<h2><?php echo __('Reset Epic Data','epic'); ?></h2>
			<div class="epicdrinner">
				<span>
					<label><?php echo __('Epic Points:','epic'); ?></label>
					<a class="epbtn ep_reset_cep" href="javascript:void(0)"><?php echo __('Reset Data','epic'); ?></a>
				</span>
				<span>
					<label><?php echo __('Achievements:','epic'); ?></label>
						<a class="epbtn  ep_reset_achivement" href="javascript:void(0)"><?php echo __('Reset Data','epic'); ?></a>
				</span>
				<span>
					<label><?php echo __('Attendance Record:','epic'); ?></label>
						<a class="epbtn  reset_user_meta" href="javascript:void(0)"><?php echo __('Reset Data','epic'); ?></a>
				</span>
				<!--<span class="eepdrspn">
					<label><?php //echo __('Attendance Meta:','epic'); ?></label>
						<a class="epbtn  ep_reset_attendance" href="javascript:void(0)"><?php //echo __('Reset Data','epic'); ?></a>
				</span>-->
				<center>
				<a class="epbtn ep_reset_all" href="javascript:void(0)"><?php echo __('Reset All Data','epic'); ?></a>
				</center>
			</div>
		</div>
		<style>
		.epicdrinner{max-width:350px;background:#3f47cc;padding:15px;float:left;width:100%}.epicdrinner span{ display:inline-flex;clear:both;color:#fff;font-size:16px; padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.5);margin-bottom:10px;float:left; width:100%}.epicdrinner label{ display:inline-block; min-width:220px}.epicdrinner a.epbtn{ border:2px solid #fff;padding:10px 15px;color:#fff;border-radius:8px;text-align:center;text-decoration:none}
		.epicdrinner a.epbtn:hover{ background:rgba(255,255,255,0.9);color:#3f47cc}.epicdrinner center{ display:block;clear:both padding:10px}span.eepdrspn{margin-bottom:25px;}
        </style>
        <script>
		jQuery(document).ready(function(e){(function($){			
			$('.ep_reset_cep').click(function(){
				swal({
					title:"Are you sure?",text:"You will not be able to recover this ",type:'warning',showCancelButton:true,confirmButtonColor:'#DD6B55',confirmButtonText:'Clear Data',
					closeOnConfirm:false},
					function(confirm){if(confirm){
						jQuery.ajax({
							type:'POST',url:'<?php echo admin_url('admin-ajax.php'); ?>',
							data:{'action':'epic_data_clear_ajax','act':1},beforeSend:function(){swal('','Please wait...!','info')},
							success:function(data){
								console.log(data);
								var res		=	$.parseJSON(data);
								if(res.response == 1){
									swal("", res.message, "success");
								}
								else{swal("","Unable to clear Epic Point Data","error");}
							}
						});
					}
				});
			});
			$('.ep_reset_achivement').click(function(){
				swal({
					title:"Are you sure?",text:"You will not be able to recover this ",type:'warning',
					showCancelButton:true,confirmButtonColor:'#DD6B55',confirmButtonText:'Clear Data',
					closeOnConfirm:false},
					function(confirm){
						if(confirm){
							jQuery.ajax({
								type:'POST',url:'<?php echo admin_url('admin-ajax.php'); ?>',
								data:{'action':'epic_data_clear_ajax','act':2},beforeSend:function(){swal('','Please wait...!','info')},
								success:function(data){
									var res		=	$.parseJSON(data);
									if(res.response == 1){swal("", res.message, "success");}
									else{swal("","Unable to clear Epic Achivement Data","error");}
								}
							});
						}
					})
				});
				$('.ep_reset_attendance').click(function(){
					swal({title:"Are you sure?",text:"You will not be able to recover this ",type:'warning',
						showCancelButton:true,confirmButtonColor:'#DD6B55',confirmButtonText:'Clear Data',closeOnConfirm:false},
						function(confirm){if(confirm){
							jQuery.ajax({
								type:'POST',url:'<?php echo admin_url('admin-ajax.php'); ?>',
								data:{'action':'epic_data_clear_ajax','act':3},beforeSend:function(){swal('','Please wait...!','info')},
								success:function(data){
									var res		=	$.parseJSON(data);
									if(res.response == 1){										
										swal("",res.message,"success");
									}
									else{swal("","Unable to clear Epic Attendance Data","error");}
								}
							});
						}
					})
				});
				$('.ep_reset_all').click(function(){
					swal({title:"Are you sure?",text:"You will not be able to recover this ",type:'warning',
						showCancelButton:true,confirmButtonColor:'#DD6B55',confirmButtonText:'Clear Data',closeOnConfirm:false},
						function(confirm){if(confirm){
							jQuery.ajax({
								type:'POST',url:'<?php echo admin_url('admin-ajax.php'); ?>',
								data:{'action':'epic_data_clear_ajax','act':4},beforeSend:function(){swal('','Please wait...!','info')},
								success:function(data){
									var res	=	$.parseJSON(data);
									if(res.response == 1){										
										swal("", res.message, "success");
									}
									else{swal("","Unable to clear Epic All Data","error");	}
								}
							});
						}
					})
				});
						

				$('.reset_user_meta').click(function(){
					swal({title:"Are you sure?",text:"This will only reset user attendance record ",type:'warning',
						showCancelButton:true,confirmButtonColor:'#DD6B55',confirmButtonText:'Clear Data',closeOnConfirm:false},
						function(confirm){if(confirm){
							jQuery.ajax({
								type:'POST',url:'<?php echo admin_url('admin-ajax.php'); ?>',
								data:{'action':'epic_data_clear_ajax','act':5},beforeSend:function(){swal('','Please wait...!','info')},
								success:function(data){
									console.log(data);
									var res	=	$.parseJSON(data);
									if(res.response == 1){										
										swal("",res.message, "success");
									}
									else{swal("","Unable to clear Data","error");	}
								}
							});
						}
					})
				});
			}(jQuery));
        });
        </script>
        <?php
	}
}

$data_reset_obj	=	new Epic_data_reset();

include('sweetalert/index.php');