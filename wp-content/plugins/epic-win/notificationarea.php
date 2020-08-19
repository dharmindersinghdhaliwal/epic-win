<?php
function epic_is_valid_achievements_code2($dpaapid){
	global $wpdb;
    $query = "SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key`='_dpa_redemption_code' AND `meta_value`='".$dpaapid."'";
    $apid = $wpdb->get_var($query);
	if($apid){	return true;}
	else{return false;}
}

function epic_notificationarea(){
	$o='';
	global $wpdb;
	$pf=$wpdb->prefix;
	$query = "SELECT * FROM {$wpdb->usermeta} WHERE `meta_key`='epic_notic' ORDER BY `".$pf."usermeta`.`umeta_id` DESC";
	$results=$wpdb->get_results($query);
	$o.='<table>';
		$o.='<tr>';
			$o.='<td></td>';
			$o.='<td>Profile Picture</td>';
			$o.='<td>Date</td>';
			$o.='<td>Time</td>';
			$o.='<td>Text</td>';
		$o.='</tr>';
	$i=1;
	foreach($results as $r){
		$user_id=$r->user_id;
		$umeta_id=$r->umeta_id;
		$meta_value=$r->meta_value;
		$jsontophp=json_decode($meta_value,true);
		$user_data=get_userdata($user_id);
		$user_link=get_site_url().'/member/?member='.$user_id;
		$propic=get_user_meta($user_id,'user_pic',true);
		if($propic){
			$profile_pic='<img src="'.$propic.'" width="24" height="24">';
		}
		else{
			$profile_pic='<img src="https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678099-profile-filled-32.png" width="24" height="24">';
		}		
		$o.='<tr>';
			$o.='<td>'.$i.'</td>';
			$o.='<td>'.$profile_pic.'</td>';
        if(isset($jsontophp['date'])){
			$o.='<td>'.$jsontophp['date'].'</td>';
        }
        else{$o.='<td></td>';}
		if(isset($jsontophp['time'])){
            $o.='<td>'.$jsontophp['time'].'</td>';
        }
        else{
            $o.='<td></td>';
        }
			$o.='<td><a href="'.$user_link.'">'.$user_data->display_name.'</a> '.$jsontophp['msg'].'</td>';
		$o.='</tr>';
		$i++;
	}
	$o.='</table>';
	return $o;
}

add_shortcode('notificationarea','epic_notificationarea');

function epic_update_unlock_achievements_message(){	
	if(isset($_POST['dpa_code'])){		
		if(epic_is_valid_achievements_code2($_POST['dpa_code'])){
			global $wpdb;
			$query = "SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key`='_dpa_redemption_code' AND `meta_value`='".$_POST['dpa_code']."'";
			$apid = $wpdb->get_var($query);
			$achievementid	=	$apid;
			$post			=	get_post($achievementid);
			$dpa_points		=	get_post_meta($post->ID,'_dpa_points',true);
			$feat_image 	=	wp_get_attachment_url( get_post_thumbnail_id($post->ID));
			$cats			=	get_the_category($post->ID);			
			$achie_title	=	$post->post_title;							

			if( isset( $_POST[ 'sqr' ] )){
				$nuclear_users 	=	explode( '-',$_POST[ 'sqr' ] );

				foreach( $nuclear_users as $nuclear_user_id ){
					epic_send_unlock_achievements_notification_to_user($nuclear_user_id,$dpa_points,$achie_title);
				}
			}else{
				$user_id		=	get_current_user_id();		
				epic_send_unlock_achievements_notification_to_user($user_id,$dpa_points,$achie_title);
			}	
		}
	}
}

add_action('wp_head','epic_update_unlock_achievements_message');

/**
 *	@des   	This function will send notification to user for unlock achievement. 
 *	@param 	user_id 	This will be the id of the user	
 *			dpa_points 	This will be total dpa points	
 *			title 		This will be the achievement title *				
 *		
 *	@return False
 */

function epic_send_unlock_achievements_notification_to_user( $user_id = '',$dpa_points = '', $achie_title ='' ){
	$user_detail	=	get_user_by( 'ID',$user_id );			
	$user_email		=	$user_detail->user_email;
	$user_data		=	get_userdata($user_id);
	$user_link		=	get_site_url().'/member/?member='.$user_id;

	//	Get Total Points
	$total_points 	=	(int) dpa_get_user_points( $user_id );		

	$msg	=	'Hello, <br><br><a href="'.$user_link.'">'.$user_data->display_name.'</a> unlocked the '.$achie_title.' achievement!<br><br>This achievement was worth:'.$dpa_points.'<br/><br/>Total Points are:'.$total_points.'<br><br>Epic Win PT Members';			

	//	Send Notification To Admin
	epic_send_notification_single_mail(get_bloginfo('admin_email'),'Achievement Unlocked!',$msg);

	//	Send Notification To User
	$user_msg	=	'Hello, <br><br>Congratulations! You have unlocked the '.$achie_title.' achievement!<br><br>This achievement was worth:'.$dpa_points.'<br/><br/>Your total points so far this year is:'.$total_points.'<br><br>Epic Win PT Members';	
	epic_send_notification_single_mail($user_email,'Achievement Unlocked!',$user_msg);
}

function epic_email_content_type() { return 'text/html'; }

add_filter( 'wp_mail_content_type', 'epic_email_content_type' );
function epic_send_notification_single_mail($to,$subject,$content){
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$response = false;
	if($content)
	$response = wp_mail($to,$subject,$content,$headers);
	remove_filter('wp_mail_content_type','epic_email_content_type');
	return $response;
}