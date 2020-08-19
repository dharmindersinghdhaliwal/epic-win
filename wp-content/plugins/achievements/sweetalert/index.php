<?php
/*---------------------*/
/* Add sweet alert js */
/*-------------------*/
add_action('wp_head','add_sweetalert_js');
function add_sweetalert_js(){
	wp_register_script('sweet-alert',plugins_url('sweetalert.min.js',__FILE__));
	wp_enqueue_script('sweet-alert');
}
/*----------------------*/
/* Add sweet alert css */
/*--------------------*/
add_action('wp_head','add_sweetalert_css');
function add_sweetalert_css(){
	wp_enqueue_style('sweet-css', plugins_url('sweetalert.css',__FILE__));
}
/*-------------------------------------------*/
/* INDIVDUAL MEMBER PROFILE SHORTCODE TO SHOW */
/*-----------------------------------------*/
function individual_member_profile(){
	$o='';
	if(isset($_GET['member'])){
		$o.=show_user_porfile($_GET['member']);
	}
	else{
		$o.=show_user_porfile(get_current_user_id());
	}
	return $o;
}
add_shortcode('memberachiv','individual_member_profile');
function show_user_porfile($userid){
	global $wpdb;
	$o='';
	$userdata=get_userdata($userid);
	$fname=$userdata->first_name;
	$lname=$userdata->last_name;
	$username=$fname.' '.$lname;
	if($username){
		$name=$username;
	}
	else{
		$name=$userdata->display_name;
	}
	$email=$userdata->user_email;
	$user_url=$userdata->user_url;	
	$user_pic			=	get_user_meta($userid,'user_pic',true);
	$coverimg			=	get_user_meta($userid,'user_cover_pic-upload-status',true);
	$dob				=	get_user_meta($userid,'date_of_birth',true);
	$epic_points		=	get_user_meta($userid,'_dpa_points',true);
	$archievements		=	get_user_meta($userid, $wpdb->prefix.'_dpa_unlocked_count',true);
	$ice_challenges		=	get_user_meta($userid,'category_ice_challenge',true);
	$feats_strength		=	get_user_meta($userid,'category_feats_of_strength',true);
	$body_blitzes		=	get_user_meta($userid,'category_body_blitz',true);
	$about_bio			=	get_user_meta($userid,'description',true);
	$facbook			=	get_user_meta($userid,'facebook',true);
	$twitter			=	get_user_meta($userid,'twitter',true);
	$google				=	get_user_meta($userid,'googleplus',true);
	$hide_dob			=	get_user_meta($userid,'hide_date_of_birth',true);
	$hide_facebook		=	get_user_meta($userid,'hide_facebook',true);
	$hide_twitter		=	get_user_meta($userid,'hide_twitter',true);
	$hide_googleplus	=	get_user_meta($userid,'hide_googleplus',true);	
	/*
	$o.='<div data-dslc-preset="none" data-dslc-anim-easing="ease" data-dslc-anim-duration="650" data-dslc-anim-delay="0" data-dslc-anim="none" data-dslc-module-size="12" data-dslc-module-id="DSLC_Text_Simple" data-module-id="36" class="dslc-module-front dslc-module-DSLC_Text_Simple dslc-in-viewport-check dslc-in-viewport-anim-none  dslc-col dslc-12-col dslc-last-col  dslc-module-handle-like-regular dslc-in-viewport" id="memberindprofiel" style="animation: 0.65s ease 0s normal none 1 running forwards">';
	$o.='<div class="dslc-text-module-content"><div class="upme-column-wrap"><div class="upme-wrap upme-12 upme-width-1"><div class="upme-inner upme-clearfix upme-view-panel"><div class="upme-head"><div class="upme-left"><div class="upme-pic">';	
	if($user_pic){
		$o.='<a href="javascript:void(0)"><img class="avatar avatar-50" src="'.$user_pic.'" id="upme-avatar-user_pic"></a>'; //
	}
	else{
		$o.='<a href="javascript:void(0)"><img class="avatar avatar-50" src="'.plugins_url('profile.png',__FILE__).'" id="upme-avatar-user_pic"></a>'; //
	}	
	$o.='</div><div class="upme-name"><div class="upme-field-name"><a href="#">'.$name.'</a></div>';
	$o.='</div></div><div class="upme-right"><div class="upme-social"><div class="upme-envelope">';
	$o.='<a class="upme-tooltip" href="mailto:'.$email.'" rel="external nofollow" target="_blank" original-title="Send E-mail"><i class="upme-icon upme-icon-envelope"></i></a></div>';
	if($facbook){
		$o.='<div class="upme-facebook"><a class="upme-tooltip" href="http://www.facebook.com/'.$facbook.'" rel="external nofollow" target="_blank" original-title="Connect via Facebook"><i class="upme-icon upme-icon-facebook"></i></a></div>';
	}	
	if($twitter){
		$o.='<div class="upme-twitter"><a class="upme-tooltip" href="http://twitter.com/'.$twitter.'" rel="external nofollow" target="_blank" original-title="Connect via Twitter"><i class="upme-icon upme-icon-twitter"></i></a></div>';
	}	
	if($google){
		$o.='<div class="upme-google-plus"><a class="upme-tooltip" href="https://plus.google.com/'.$google.'" rel="external nofollow" target="_blank" original-title="Connect via Google+"><i class="upme-icon upme-icon-google-plus"></i></a></div>';
	}	
	$o.='</div><div class="upme-clear"></div></div><div class="upme-clear"></div></div>';	
	$o.='<div class="upme-profile-tab-panel upme-main upme-main-" id="upme-profile-panel"><div class="upme-field upme-view upme-first_name"><div class="upme-field-type"><i class="upme-icon upme-icon-user"></i><span>Name</span></div><div class="upme-field-value"><span>';
	$o.=$name;
	$o.='</span></div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-date_of_birth"><div class="upme-field-type"><i class="upme-icon upme-icon-birthday-cake"></i><span>D.o.B. (dd/mm/yyyy) </span></div><div class="upme-field-value"><span>';
	$o.=$dob;	
	$o.='</span></div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-wp__dpa_points"><div class="upme-field-type"><i class="upme-icon upme-icon-trophy"></i><span><b>Epic Points</b></span></div><div class="upme-field-value"><span>';	
	$o.=$epic_points;	
	$o.='</span></div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-wp__dpa_unlocked_count"><div class="upme-field-type"><i class="upme-icon upme-icon-gamepad"></i><span><b>Achievements Unlocked</b></span></div><div class="upme-field-value"><span>';	
	$o.=$archievements;
	$o.='</span></div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-category_ice_challenge"><div class="upme-field-type"><i class="upme-icon upme-icon-cogs"></i><span>ICE Challenges Achieved</span></div><div class="upme-field-value"><span>';	
	$o.=$ice_challenges;
	$o.='</span>';	
	$o.='</div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-category_feats_of_strength"><div class="upme-field-type"><i class="upme-icon upme-icon-child"></i><span>Feats of Strength Achieved</span></div><div class="upme-field-value"><span>';
	$o.=$feats_strength;
	$o.='</span>';		
	$o.='</div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-category_body_blitz"><div class="upme-field-type"><i class="upme-icon upme-icon-user"></i><span>Body Blitzes Achieved</span></div><div class="upme-field-value"><span>';
	$o.=$body_blitzes;
	$o.='</span></div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-description"><div class="upme-field-type"><i class="upme-icon upme-icon-pencil"></i><span>';
	$o.='About / Bio</span></div><div class="upme-field-value">';	
	$o.=$about_bio;
	$o.='</div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-user_email"><div class="upme-field-type"><i class="upme-icon upme-icon-envelope"></i><span>Email</span></div><div class="upme-field-value"><span>';
	$o.=$email;
	$o.='</span></div></div><div class="upme-clear"></div><div class="upme-field upme-view upme-user_url"><div class="upme-field-type"><i class="upme-icon upme-icon-link"></i><span>Website</span></div><div class="upme-field-value"><span>';
	$o.=$user_url;
	$o.='</span></div></div></div></div></div></div></div></div>';
	$o.='';	
	$o.='<style>';
	$o.='#dslc-theme-content-inner{overflow:hidden;background:none;padding:0}
	#memberindprofiel{max-width:660px}
	';
	$o.='</style>';
	*/
	$o.=get_personal_achivement_of_a_member($userid);
	return $o;
}
/*--------------------------------------*/
/* GET PERSONAL ACHIVEMENT OF A MEMBER */
/*------------------------------------*/
function get_personal_achivement_of_a_member($userid){
	$o='';
	$achievements = get_posts( array(
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'numberposts'         => '-1',
		'post_status'         => 'publish',
		'post_type'           => dpa_get_achievement_post_type(),
		'suppress_filters'    => false,
	));	
	//$o.='<div data-size="6" class="dslc-modules-area dslc-col dslc-6-col dslc-last-col"><div data-dslc-preset="none" data-dslc-anim-easing="ease" data-dslc-anim-duration="650" data-dslc-anim-delay="0" data-dslc-anim="none" data-dslc-module-size="12" data-dslc-module-id="DSLC_Widgets" data-module-id="73" class="dslc-module-front dslc-module-DSLC_Widgets dslc-in-viewport-check dslc-in-viewport-anim-none  dslc-col dslc-12-col dslc-last-col  dslc-module-handle-like-regular dslc-in-viewport" id="dslc-module-73" style="animation: 0.65s ease 0s normal none 1 running forwards"><div class="dslc-widgets dslc-clearfix dslc-widgets-12-col"><div class="dslc-widgets-wrap dslc-clearfix"><div class="dslc-widget dslc-col widget_dpa_available_achievements" id="dpa_available_achievements_widget-4"><div class="dslc-widget-wrap"><h3 class="dslc-widget-title"><span class="widget-title-line"></span><span class="widget-title-text">Select Your Challenge</span></h3>';
	$o.='<ul style="list-style:none">';
	foreach($achievements as $post){
		if(has_post_thumbnail($post->ID)):
			if(dpa_has_user_unlocked_achievement($userid,$post->ID)){
				$o.='<li style="list-stype:none;display:inline-block">';
				//dpa_achievement_permalink($post->ID); 
				$o.='<a class="aidclick" href="javascript:void(0)" achiun="1" aid="'.$post->ID.'">'.get_the_post_thumbnail($post->ID,'dpa-thumb',array( 'alt'=>dpa_get_achievement_title($post->ID))).'</a>';
				$o.='</li>';
			}
			else{
			}
		endif;
	}
	$o.='</ul>';
	//$o.='</div></div></div></div></div></div>';
	return $o;
}
?>