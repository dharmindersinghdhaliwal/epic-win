<?php

add_action( 'wp_ajax_epic_attendance_search_ajax','epic_attendance_search_ajax');
add_action('wp_ajax_nopriv_epic_attendance_search_ajax','epic_attendance_search_ajax');

function epic_attendance_search_ajax(){
	if($_REQUEST){
		$date			=	$_REQUEST['date'];
		$attend_search	=	new Epic_attandence_search();
		$html			=	$attend_search->attendance_search_template($date);
		if(!empty($html)){
			$response	=	$html;
		}
		echo $response;
		die();
	}
}


add_action( 'wp_ajax_epic_attendance_achievements_ajax','epic_attendance_achievements_ajax');
add_action('wp_ajax_nopriv_epic_attendance_achievements_ajax','epic_attendance_achievements_ajax');

function epic_attendance_achievements_ajax(){
	global $wpdb;
	if($_REQUEST){
		 $uid			=	$_REQUEST['uid'];
		 $tid			=	$_REQUEST['tid'];
		 $status_arr	=	$_REQUEST['status_arr'];
		 $act			=	$_REQUEST['act'];
		 $num			=	$_REQUEST['num'];
		if($act=='0'){
			
			$achievements	= 	epic_get_achievements_by_name($tid,$num);			
			$unlocked		=	get_user_meta($uid,$wpdb->prefix.'_dpa_unlocked_count',true);
			$unlocked		=	!empty($unlocked) ? $unlocked : 0;			
			epic_unlock_achievements_by_id($uid,$achievements);
				$response		=	array(
									'response'		=>	1,
									'message'		=>	'Status updated',
									'unlocked'		=>	$unlocked,
									'uid'			=>	$uid
								);
		}
		if($act=='1'){
			$achievements	=	epic_get_achievements_by_name($tid,$num);
			epic_remove_achievement_by_id($achievements,$uid);
			$unlocked		=	get_user_meta($uid,$wpdb->prefix.'_dpa_unlocked_count',true);
			$unlocked		=	!empty($unlocked) ? $unlocked : 0;
			$response	=	array(
								'response'		=>	1,
								'message'		=>	$achievements.' Removed',
								'unlocked'		=>	$unlocked,
								'uid'			=>	$uid
							);
		}
		if($act=='3'){			
			$achievement_id	=	$_REQUEST['id'];
			$unlock		=	epic_unlock_achievements_by_id($uid,$achievement_id);
			$unlock		=	$unlock ? 'Success' : 'Error';
			$response	=	array(
								'response'		=>	1,
								'message'		=>	$unlock,
							);
		}
	}
	echo json_encode($response);
	die();
}