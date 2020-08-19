<?php
function epic_get_post_meta($post_id=0){
    global $wpdb;
    $pfx=$wpdb->base_prefix;	
	return $wpdb->get_results("SELECT * FROM `".$pfx."postmeta` WHERE `meta_key` LIKE '%attendance_%'");
}

/* FUNCTION TO GENERATE META KEY ACCORDING TO DATE */
function epic_attendance_meta_key_encode($str_date){
	$str_date_1= str_replace(',','',$str_date);
	$str_date_2=str_replace(' ','_',$str_date_1);
	return 'attendance_'.strtolower($str_date_2);
}

/* FUNCTION TO GENERATE META KEY ACCORDING TO Paramter and DATE */
function epic_attendance_parameter_meta_key_encode($str_date,$key){
	$str_date_1= str_replace(',','',$str_date);
	$str_date_2=str_replace(' ','_',$str_date_1);
	return $key.'_'.strtolower($str_date_2);
}

function epic_attendance_meta_key_decode($str_date){
	$str_date_1=str_replace('attendance_','',$str_date);
	$str_date_2=str_replace('_',' ',$str_date_1);
	return strtoupper($str_date_2);
}

/*-------------------------------------------*/
/* FUNCTION AJAX REQUEST TO SAVE ATTENDANCE */
/*-----------------------------------------*/
add_action('wp_ajax_epic_class_attendance_ajax_request','epic_class_attendance_ajax_request');
add_action('wp_ajax_nopriv_epic_class_attendance_ajax_request','epic_class_attendance_ajax_request');

function epic_class_attendance_ajax_request(){
	if(isset($_REQUEST)){
		$trArr			=	$_REQUEST['trArr'];		
		$postid			=	$_REQUEST['postid'];
		$post_cat_id	=	$_REQUEST['post_cat_id'];
		$post_cat_array 	=	explode( ',', $post_cat_id );
		$atten_date		=	$_REQUEST['attendancedate'];
		$newArr			=	array_filter($trArr);
		$newArra		=	array_unique($newArr);
		$jsoncod		=	json_encode($newArra);

		if( !in_array(412, $post_cat_array)){		
			$minute30		=	$_REQUEST['minute30'];
			$minute45		=	$_REQUEST['minute45'];
			$minute60		=	$_REQUEST['minute60'];
			$jonT			=	$_REQUEST['jonT'];
			$liamT			=	$_REQUEST['liamT'];
			$shaylaT		=	$_REQUEST['shaylaT'];
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'minute30'),$minute30[0]);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'minute45'),$minute45[0]);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'minute60'),$minute60[0]);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'jonT'),$jonT[0]);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'liamT'),$liamT[0]);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'shaylaT'),$shaylaT[0]);
		}else{
			$minute30		=	json_encode($_REQUEST['minute30']);
			$minute45		=	json_encode($_REQUEST['minute45']);
			$minute60		=	json_encode($_REQUEST['minute60']);
			$jonT			=	json_encode($_REQUEST['jonT']);
			$liamT			=	json_encode($_REQUEST['liamT']);
			$shaylaT		=	json_encode($_REQUEST['shaylaT']);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'minute30'),$minute30);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'minute45'),$minute45);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'minute60'),$minute60);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'jonT'),$jonT);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'liamT'),$liamT);
			update_post_meta($postid,epic_attendance_parameter_meta_key_encode($atten_date,'shaylaT'),$shaylaT);
		}

		update_post_meta($postid,epic_attendance_meta_key_encode($atten_date),$jsoncod);
		
		

		for($i=0;$i<count($newArra);$i++){
			epic_add_total_attendance_in_meta($newArra[$i]);
		}
		echo '1';
	}
	die();
}

/*------------------------------------------------------------*/
/* FUNCTION TO ADD ATTENDANCE OF A USER IN USER'S META FILED */
/*----------------------------------------------------------*/
function epic_add_total_attendance_in_meta($uid){
	$ct_arA		=	array();
	$ct_arB		=	array();
	$ct_arC		=	array();
	$ct_arD		=	array();
	$ct_arE		=	array();
	$cat1		=	'ice';
	$cat2		=	'train-the-trainer';
	$cat3		=	'first-strike-boxing';
	$cat4		=	'fire-classes';
	$cat5		=	'personal-training-sessions';
	$a=0; $b=0; $c=0; $d=0; $e=0;
    $atten_arr	=	epic_get_post_meta();
	foreach($atten_arr as $atten){
		if($uid==''){
			$uid==0;
		}
		$att_pid	=	$atten->post_id;
		$att_dat	=	$atten->meta_key;
		$att_val	=	$atten->meta_value;
		$cid 		=	$att_pid;
		$cat		=	get_the_terms($cid,'mrd_classes_cats');
		$catslug	=	$cat[0]->slug;
		$cattarra	=	json_decode($att_val,true);
		$cadate 	=	epic_attendance_meta_key_decode($att_dat);
		$cadateST	=	strtotime($cadate);
		if(in_array($uid,$cattarra)){
			if ($catslug==$cat1){ $a++; }
			else if($catslug==$cat2){ $b++; }
			else if($catslug==$cat3){ $c++; }
			else if($catslug==$cat4){ $d++; }
			else{$e++;}
		}
	}
	$t	=	$a+$b+$c;
	update_user_meta($uid,'_dpa_ice',$a);
	update_user_meta($uid,'_dpa_first_strike_boxing',$c);
	update_user_meta($uid,'fire_class',$d);
	update_user_meta($uid,'pt_sessions_attended',$e);	
	update_user_meta($uid,'grp_class_attended',$t);	
}