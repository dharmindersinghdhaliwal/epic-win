<?php

function epic_get_count_id_of_user($i){
	$pid=get_the_ID();
	$jsondata=json_decode(get_post_meta($pid,epic_attendance_meta_key_encode(epic_attendance_date()),true),true);
	//		echo '<pre>'; print_r( $jsondata ); exit;
	if( !empty($jsondata ) ){
		$count=count($jsondata);
		if($i<=$count){ return $jsondata[$i]; }
	}	
}


function epic_get_value_of_attendance_meta_user($i,$key){
	$pid=get_the_ID();
	$jsondata=json_decode(get_post_meta($pid,epic_attendance_parameter_meta_key_encode(epic_attendance_date(),$key),true),true);
								
	//		echo '<pre>'; print_r( $jsondata ); exit;
	if( !empty($jsondata ) ){
		$count=count($jsondata);
		if($i<=$count){ return $jsondata[$i]; }
	}	
}

function epic_get_name_by_user_id($uid){
	if($uid){
		$user=get_userdata($uid);
		return $user->display_name;
	}
}

function return_current_date_of_class(){
	$pid	=	get_the_ID();
	$day1	=	get_post_meta($pid,'mrd_class_monday',true);
	$day2	=	get_post_meta($pid,'mrd_class_tuesday',true);
	$day3	=	get_post_meta($pid,'mrd_class_wednesday',true);
	$day4	=	get_post_meta($pid,'mrd_class_thursday',true);
	$day5	=	get_post_meta($pid,'mrd_class_friday',true);
	$day6	=	get_post_meta($pid,'mrd_class_saturday',true);
	$day7	=	get_post_meta($pid,'mrd_class_sunday',true);
	if($day1){ $day='Monday'; $time=$day1; }
	else if($day2){ $day='Tuesday'; $time=$day2; }
	else if($day3){ $day='Wednesday'; $time=$day3; }
	else if($day4){ $day='Thursday'; $time=$day4; }
	else if($day5){ $day='Friday'; $time=$day5; }
	else if($day6){ $day='Saturday'; $time=$day6; }
	else{ $day='Sunday'; $time=$day7; }
    if(isset($_GET['date'])){
		$date=$_GET['date'];
    }
    else{$date='';}
    if(isset($_GET['month'])){
        $month=$_GET['month'];
    }
	else{$month='';}
	return '<h2>'.$date.' '.$month.' '.$day.' - '.$time.'</h2>';
}

function epic_attendance_date(){
	if(isset($_GET['date'])){
		return $_GET['date'].' '.$_GET['month'];
	}
}

/*----------------------------------------*/
/* EPCI CLASS ATTENDANCE REGISTER [FORM] */
/*--------------------------------------*/
function epic_class_attendance_register($content){
	global $post;
	//	$categories = get_the_category();
	//	$postcat = get_the_category( $post->ID );
	$args = array( 'post_type' => 'mrd_classes' );

	$categories =	get_the_terms($post->ID, "mrd_classes_cats" );
	$cat_array 	=	array();

	foreach( $categories as $category ){
		$cat_array[]	=	$category->term_id;
	}

	//	$post_categories = wp_get_post_categories( $post->ID,$args );
	//	echo '<pre>'; print_r( $cat_array ); echo '</pre>'; 

	if($post->post_type=='mrd_classes'){
		$o='';
		$o.= '<form action="" id="attendance_register">';
		$o.=return_current_date_of_class().'<br>';
		$o.= '<input type="hidden" id="post_cat_id" name="post_cat_id" value="'.implode( ',', $cat_array ).'" />';
		if( !in_array(412, $cat_array)){		
			$o.='<div class="tratteleft">';
			$o.='<input type="hidden"value="'.epic_attendance_date().'" name="attendancedate" class="attendancedate">';

			for($i=0;$i<=24;$i++){
				$uid=epic_get_count_id_of_user($i);
				$uname=epic_get_name_by_user_id($uid);
				if($uid){
					$o.='<span class="arfldcont"><input type="text" value="'.$uname.'" name="traineeshow[]"><input class="trainee" type="hidden" value="'.$uid.'" name="trainee[]"></span><br>';
				}
				else{
					$o.='<span class="arfldcont"><input type="text" name="traineeshow[]"><input class="trainee" type="hidden" name="trainee[]"></span><br>';
				}
			}
												
			$val30 = get_post_meta($post->ID,epic_attendance_parameter_meta_key_encode(epic_attendance_date(),'minute30'),true);
			$val45 = get_post_meta($post->ID,epic_attendance_parameter_meta_key_encode(epic_attendance_date(),'minute45'),true);
			$val60 = get_post_meta($post->ID,epic_attendance_parameter_meta_key_encode(epic_attendance_date(),'minute60'),true);
			$valjon=get_post_meta($post->ID,epic_attendance_parameter_meta_key_encode(epic_attendance_date(),'jonT'),true);
			$valliam=get_post_meta($post->ID,epic_attendance_parameter_meta_key_encode(epic_attendance_date(),'liamT'),true);			
			$valshayla=get_post_meta($post->ID,epic_attendance_parameter_meta_key_encode(epic_attendance_date(),'shaylaT'),true);

			$o.='</div>';		
			$o.='<div class="tratteright">';
			$o.='<div class="durationblock"><p class="centerlabel">Duration</p>';
			$o.='<div class="durationlabels">';
			$o.='<p><span>30</span><span>45</span><span>60</span></p>';
			
			if( $val30){
				$o.='<p><span><input type="checkbox" name="30minute[]" value="1" checked="checked" /></span>';
			}else{
				$o.='<p><span><input type="checkbox" name="30minute[]" value="1" /></span>';
			}

			if( $val45 ){
				$o.='<span><input type="checkbox" name="45minute[]" value="1" checked="checked" /></span>';	
			}else{
				$o.='<span><input type="checkbox" name="45minute[]" value="1" /></span>';
			}	

			if( $val60 ){
				$o.='<span><input type="checkbox" name="60minute[]" value="1" checked="checked" /></span></p>';
			}else{
				$o.='<span><input type="checkbox" name="60minute[]" value="1" /></span></p>';
			}
			
			$o.='</div>';
			$o.='</div>';			
			$o.='<div class="trainerblock"><p class="centerlabel"><b>Trainer</b></p>';
			$o.='<div class="trainerlabels">';
			$o.='<p><span>Jon</span><span>Liam</span><span>Shayla</span></p>';

			if( $valjon ){
				$o.='<p><span><input type="checkbox" name="jon[]" value="1" checked="checked" /></span>';
			}else{
				$o.='<p><span><input type="checkbox" name="jon[]" value="1" /></span>';
			}

			if( $valliam ){
				$o.='<span><input type="checkbox" name="liam[]" value="1" checked="checked" /></span>';
			}else{
				$o.='<span><input type="checkbox" name="liam[]" value="1" /></span>';
			}

			if( $valshayla ){
				$o.='<span><input type="checkbox" name="shayla[]" value="1" checked="checked" /></span></p>';		
			}else{
				$o.='<span><input type="checkbox" name="shayla[]" value="1" /></span></p>';		
			}
			
			$o.='</div>';
			$o.='</div>';
			$o.='<div>';
			$o.='<input type="submit" value="Submit" id="submitattendance">';
			$o.='</div>';						
		}else{
			$o.='<div style="width:80% !important;" class="tratteleft">';
			$o.='<input type="hidden"value="'.epic_attendance_date().'" name="attendancedate" class="attendancedate">';
			$o.='<div class="arfldcont" style="width:60% !important;float:left;">&nbsp;</div>';
			$o.='<div class="duationTraining" style="margin-right:10px !important;">';
			$o.='<p class="duationHeading">Duration</p>';
			$o.='<div class="labelSubTitle"><div>30</div><div>45</div><div>60</div></div>';
			$o.='</div>';
			$o.='<div class="duationTraining">';
			$o.='<p class="duationHeading">Trainer</p>';
			$o.='<div class="labelSubTitle"><div>Jon</div><div>Liam</div><div>Shayla</div></div>';
			$o.='</div>';
			$o.='<div class="clearBoth">&nbsp;</div>';
			for($i=0;$i<=24;$i++){
				$uid=epic_get_count_id_of_user($i);
				$uname=epic_get_name_by_user_id($uid);
				if($uid){
					$o.='<div class="arfldcont" style="width:60% !important;float:left;"><input type="text" value="'.$uname.'" name="traineeshow[]"><input  class="trainee" type="hidden" value="'.$uid.'" name="trainee[]"></div>';
				}
				else{
					$o.='<div class="arfldcont" style="width:60% !important;float:left;"><input type="text" name="traineeshow[]"><input class="trainee" type="hidden" name="trainee[]"></div>';
				}

				$val30=epic_get_value_of_attendance_meta_user($i,'minute30');
				$val45=epic_get_value_of_attendance_meta_user($i,'minute45');
				$val60=epic_get_value_of_attendance_meta_user($i,'minute60');
				$valjon=epic_get_value_of_attendance_meta_user($i,'jonT');
				$valliam=epic_get_value_of_attendance_meta_user($i,'liamT');
				$valshayla=epic_get_value_of_attendance_meta_user($i,'shaylaT');

				$o.='<div class="duationTraining" style="margin-right:10px !important;">';
				$o.='<div class="labelSubTitle">';

				if( $val30 ){
					$o.='<div><input type="checkbox" name="30minute[]" value="1" checked="checked" /></div>';
				}else{
					$o.='<div><input type="checkbox" name="30minute[]" value="1"/></div>';
				}

				if( $val45 ){
					$o.='<div><input type="checkbox" name="45minute[]" value="1" checked="checked" /></div>';
				}else{
					$o.='<div><input type="checkbox" name="45minute[]" value="1"/></div>';
				}

				if( $val60 ){
					$o.='<div><input type="checkbox" name="60minute[]" value="1" checked="checked" /></div>';
				}else{
					$o.='<div><input type="checkbox" name="60minute[]" value="1"/></div>';
				}

				$o.='</div>';
				$o.='</div>';
				$o.='<div class="duationTraining">';
				$o.='<div class="labelSubTitle">';

				if( $valjon ){
					$o.='<div><input type="checkbox" name="jon[]" value="1" checked="checked" /></div>';
				}else{
					$o.='<div><input type="checkbox" name="jon[]" value="1" /></div>';
				}
				
				if( $valliam ){
					$o.='<div><input type="checkbox" name="liam[]" value="1" checked="checked" /></div>';
				}else{
					$o.='<div><input type="checkbox" name="liam[]" value="1" /></div>';
				}

				if( $valshayla ){
					$o.='<div><input type="checkbox" name="shayla[]" value="1" checked="checked" /></div>';
				}else{
					$o.='<div><input type="checkbox" name="shayla[]" value="1" /></div>';
				}

				$o.='</div>';
				$o.='</div>';
				$o.='<div class="clearBoth">&nbsp;</div>';
			}
			$o.='</div>';			
			$o.='<div class="submitattendanceblock">';
			$o.='<input type="submit" value="Submit" id="submitattendance" style="margin-top:35% !important;">';			
			$o.='</div>';

		}

		$o.='</div>';
		$o.= '</form>';
		ob_start(); 
		?>
		<style type="text/css">
		#attendance_register{padding:15px 0;display:block;overflow:hidden}#attendance_register h2{ font-size:20px; color:#111; margin-bottom:10px}.tratteleft,.tratteright{width:48%;display:inline-block;float:left}.tratteleft{padding-right:2%}.tratteleft input[type="text"]{width:100%;padding:3px}.tratteright{padding-right:2%;vertical-align:central}#submitattendance{margin-top:70%;width:300px;height:100px;display:inline-block;border:none;background:url(<?php echo plugins_url('img/submit.png',__FILE__); ?>);cursor:pointer;text-indent:-99999px;overflow:hidden; width:154px; background-size:contain;}#submitattendance:hover{opacity:0.8}.back-attend { background: #3399ff none repeat scroll 0 0; border: 1px solid #3399ff;border-radius: 4px;color: #fff; display: inline-block;padding: 7px; position: relative; text-decoration: none !important; top: 28px; width: 150px;}
		.close-attend { background: #3399ff none repeat scroll 0 0; border-radius: 4px;color: #fff;display: inline-block; padding: 7px; position: relative; text-decoration: none !important;top: 62px; width: 107px;}
		.txt2{font-size: 25px !important; font-weight: 700 !important;}
		.durationblock{
			display: inline-block;
			width: 50%;
			float: left;
		}

		.trainerblock{
			display: inline-block;
			width: 50%;
		}

		.durationlabels p span{ padding-left: 20%; }
		.trainerlabels p span{ padding-left: 20%; }
		.centerlabel{ text-align: center; font-weight: bold; font-size: 16px; }
		.duationTraining{
			width: 18%;
			text-align: center;			
			float: 	left;
		}

		.duationHeading{
			font-weight: bold;
			font-size: 16px;
			margin: 0 !important;
			padding: 0 !important;
		}

		.labelSubTitle{			
			margin: 0 !important;
			padding: 0 !important;
		}

		.labelSubTitle div {
		    width: 33% !important;
		    text-align: center;
		    float: left;
		}

		.submitattendanceblock{
			text-align: center;
		}

		.clearBoth{
			clear: 	both;
			line-height: 1px !important;
		}

		</style>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
	jQuery(document).ready(function(e){(function($){
		var availableTags=<?php echo epic_get_list_of_all_user(); ?>;
		$('#attendance_register input[type="text"]').autocomplete({source:availableTags,select:function(event,ui){event.preventDefault();
		$(this).parents('.arfldcont').children('.trainee').val(ui.item.value);
		$(this).val(ui.item.label)},});
		$('.arfldcont input[name="traineeshow[]"]').on('change',function(){
			if(!$(this).val()){
				$(this).parents('span').children('input[name="trainee[]"]').val('')
			}
		});
		$('#submitattendance').click(function(){
			var ajaxurl='<?php echo admin_url('admin-ajax.php'); ?>';
			var postid='<?php echo get_the_id(); ?>',trArr=$('input[name="trainee[]"]').map(function(){
				return $(this).val()
			}).get(),minute30= $('input[name="30minute[]"]').map(function(){
				if( $(this).is(":checked")){
					return 1;
				}else{
					return 0;
				}
			}).get(),minute45= $('input[name="45minute[]"]').map(function(){
				if( $(this).is(":checked")){
					return 1;
				}else{
					return 0;
				}
			}).get(),minute60= $('input[name="60minute[]"]').map(function(){
				if( $(this).is(":checked")){
					return 1;
				}else{
					return 0;
				}
			}).get(),jonT= $('input[name="jon[]"]').map(function(){
				if( $(this).is(":checked")){
					return 1;
				}else{
					return 0;
				}
			}).get(),liamT= $('input[name="liam[]"]').map(function(){
				if( $(this).is(":checked")){
					return 1;
				}else{
					return 0;
				}
			}).get(),shaylaT= $('input[name="shayla[]"]').map(function(){
				if( $(this).is(":checked")){
					return 1;
				}else{
					return 0;
				}
			}).get(),attendancedate=$('.attendancedate').val();

			var post_cat_id = jQuery( '#post_cat_id' ).val();

			jQuery.ajax({
				type:'POST',url:ajaxurl,
				data:{'action':'epic_class_attendance_ajax_request','attendancedate':attendancedate,'trArr':trArr,'minute30':minute30,'minute45':minute45,'minute60':minute60,'jonT':jonT,'liamT':liamT,'shaylaT':shaylaT,'postid':postid,'post_cat_id':post_cat_id},success:function(data){

					if(data=='1'){
						swal({
							title: "<small>Title</small>!",
							text: "<p class='txt2'>Class Attendance done!<p><br><a href='http://members.epicwinpt.com.au/attendance'class='back-attend'>Back to Attendance</a><br><a href='javascript:void(0)'class='close-attend'onClick='swal.close()'>close</a>",
							showConfirmButton:false,
							html: true
						});
					}
				}
			});
			return false
		})
	}(jQuery))});
	</script>
	<?php
		$out=ob_get_contents();
		ob_end_clean();
		$o.=$out;
		$content .=$o;
		return $content;
	}
}
add_shortcode('attendform','epic_class_attendance_register');

/*----------------------------------------------------------------------------------*/
/* FUNCTION TO GET EPIC USER LIST TO ADD IN ATTENDANCE REGISTER FOR AUTO SUGGETION */
/*--------------------------------------------------------------------------------*/
function epic_get_list_of_all_user(){	
	global $post;

	$args=array('role'=>'','order'=>'ASC');
	$users=get_users($args);
	$postID =	$post->ID;
	$o='';
	$o.='[';
	foreach($users as $user){
		if($postID == '5550' ){
			$retire_user =	'';
		}else{
			$retire_user = esc_attr( get_the_author_meta( 'retire_user', $user->ID ) );	
		}
		
		if(empty( $retire_user ) ){
			if( !empty( $user->date_of_birth ) ){
				$ageArray	= 	explode( '/',$user->date_of_birth);
				$ageArray[2] 	=	isset($ageArray[2])?$ageArray[2]:'';
				$ageArray[1] 	=	isset($ageArray[1])?$ageArray[1]:'';
				$ageArray[0] 	=	isset($ageArray[0])?$ageArray[0]:'';
				$dob   		=	$ageArray[2].'-'.$ageArray[1].'-'.$ageArray[0];
				$age		= 	(date('Y') - date('Y',strtotime($dob)));
			}else{
				$age   		= 	30;
			}			
			$o.='{label:"'.$user->display_name.'",value:"'.$user->ID.'",email:"'.$user->user_email.'",gender:"'.$user->gender.'",age:"'.$age.'"},';
		}			
	}
	$o.=']';
	return $o;
}

/*----------------------------------------------------------------------------------*/
/* FUNCTION TO GET EPIC USER LIST FOR NUCLEAR CODE */
/*--------------------------------------------------------------------------------*/
function epic_get_list_of_all_user_for_nuclear_code(){	
	global $post;

	$args=array('role'=>'','order'=>'ASC');
	$users=get_users($args);
	$postID =	$post->ID;
	$o='';
	$o.='[';
	foreach($users as $user){
		if($postID == '5550' ){
			$retire_user =	'';
		}else{
			$retire_user = esc_attr( get_the_author_meta( 'retire_user', $user->ID ) );	
		}
		
		if(empty( $retire_user ) ){
			if( !empty( $user->date_of_birth ) ){
				$ageArray	= 	explode( '/',$user->date_of_birth);
				$dob   		=	$ageArray[2].'-'.$ageArray[1].'-'.$ageArray[0];
				$age		= 	(date('Y') - date('Y',strtotime($dob)));
			}else{
				$age   		= 	30;
			}			
			$o.='{user_id:"'.$user->ID.'",value:"'.$user->display_name.'",email:"'.$user->user_email.'",gender:"'.$user->gender.'",age:"'.$age.'"},';
		}			
	}
	$o.=']';
	return $o;
}

/*--------------------------------------*/
/* FUNCTON TO SHOW ATTENDANCE REGISTER */
/*------------------------------------*/
function epic_all_attendance($uid, $sdate,$edate){
	$ct_arA=array();
	$ct_arB=array();
	$ct_arC=array();
	$ct_arD=array();
	$ct_arE=array();
	$cat1='ice';
	$cat2='train-the-trainer';
	$cat3='first-strike-boxing';
	$cat4='fire-classes';
	$cat5='personal-training-sessions';
	$a=0;
	$b=0;
	$c=0;
	$d=0;
	$e=0;
    $atten_arr=epic_get_post_meta();
	foreach($atten_arr as $atten){
		if($uid==''){
			$uid==0;
		}
						
		$att_pid	=	$atten->post_id;
		$att_dat	=	$atten->meta_key;
		$att_val 	=	$atten->meta_value;
		$cid 		=	$att_pid;
		$cat		=	get_the_terms($cid,'mrd_classes_cats');
		$catslug	=	$cat[0]->slug;
		$cattarra	=	json_decode($att_val,true);
		$cadate 	=	epic_attendance_meta_key_decode($att_dat);
		$cadateST	=	strtotime($cadate);
		$sdateST	=	strtotime(epic_attendance_meta_key_decode(epic_attendance_meta_key_encode($sdate)));
		$edateST	=	strtotime(epic_attendance_meta_key_decode(epic_attendance_meta_key_encode($edate)));
		if(empty($sdate) && empty($edate)){
			if(in_array($uid,$cattarra)){
				if ($catslug==$cat1){
					array_push($ct_arA,$cadate); $a++;
				}
				else if($catslug==$cat2){
					array_push($ct_arB,$cadate); $b++;
				}
				else if($catslug==$cat3){
					array_push($ct_arC,$cadate); $c++;
				}
				else if($catslug==$cat4){
					array_push($ct_arD,$cadate); $d++;
				}
				else{
					array_push($ct_arE,$cadate); $e++;
				}
			}
		}
		else if($sdate && empty($edate)){
			if($cadateST>=$sdateST){
				if(in_array($uid,$cattarra)){
					if ($catslug==$cat1){
						array_push($ct_arA,$cadate); $a++;
					}
					else if($catslug==$cat2){
						array_push($ct_arB,$cadate); $b++;
					}
					else if($catslug==$cat3){
						array_push($ct_arC,$cadate); $c++;
					}
					else if($catslug==$cat4){
						array_push($ct_arD,$cadate); $d++;
					}
					else{
						array_push($ct_arE,$cadate); $e++;
					}
				}
			}
		}
		else if(empty($sdate) && $edate){
			if($cadateST<=$edateST){
				if(in_array($uid,$cattarra)){
					if ($catslug==$cat1){
						array_push($ct_arA,$cadate); $a++;
					}
					else if($catslug==$cat2){
						array_push($ct_arB,$cadate); $b++;
					}
					else if($catslug==$cat3){
						array_push($ct_arC,$cadate); $c++;
					}
					else if($catslug==$cat4){
						array_push($ct_arD,$cadate); $d++;
					}
					else{
						array_push($ct_arE,$cadate); $e++;
					}
				}
			}
		}
		else if($sdate && $edate){
			if($cadateST>=$sdateST && $cadateST<=$edateST){
				if(in_array($uid,$cattarra)){
					if ($catslug==$cat1){
						array_push($ct_arA,$cadate); $a++;
					}
					else if($catslug==$cat2){
						array_push($ct_arB,$cadate); $b++;
					}
					else if($catslug==$cat3){
						array_push($ct_arC,$cadate); $c++;
					}
					else if($catslug==$cat4){
						array_push($ct_arD,$cadate); $d++;
					}
					else{
						array_push($ct_arE,$cadate); $e++;
					}
				}
			}
		}
		else{
			if(in_array($uid,$cattarra)){
				if ($catslug==$cat1){
					array_push($ct_arA,$cadate); $a++;
				}
				else if($catslug==$cat2){
					array_push($ct_arB,$cadate); $b++;
				}
				else if($catslug==$cat3){
					array_push($ct_arC,$cadate); $c++;
				}
				else if($catslug==$cat4){
					array_push($ct_arD,$cadate); $d++;
				}
				else{
					array_push($ct_arE,$cadate); $e++;
				}
			}
		}
	}	
	global $epic;
	$usdata	=	get_userdata($uid);
	$t		=	$a+$b+$c;
    $o='';    
	$o.='<div id="ustgpfsec">';	
	$o.='<div class="usec_l">';		
	if(isset($_GET['uid'])){
		$o.='<a href="'.get_site_url().'/member/?member='.$uid.'">';
		$o.= $epic->pic($uid,75);
		$o.='<b>'.$usdata->display_name.'</b>';
		$o.='</a>';
	}			
	update_user_meta($uid,'_dpa_ice',$a);
	update_user_meta($uid,'_dpa_first_strike_boxing',$c);
	$o.='</div>';
	$o.='<div class="usec_r">';
	$o.='<div class="totliat1"><span>ICE</span><b>'.$a.'</b></div>';
	$o.='<div class="totliat2"><span>Train the Trainer</span><b>'.$b.'</b></div>';
	$o.='<div class="totliat3"><span>First Strike Boxing</span><b>'.$c.'</b></div>';
	$o.='<div class="totliat0"><span>Total Cardio</span><b>'.$t.'</b></div>';
	$o.='<div class="totliat4"><span>FIRE</span><b>'.$d.'</b></div>';
	$o.='<div class="totliat5"><span>Personal Training</span><b>'.$e.'</b></div>';
	$o.='</div>';
	$o.='</div>';
	$o.='<span class="cardiocls">CARDIO CLASSES</span>';
	$o.='<ul class="attentblhead"><li class="attuli1">ICE</li><li class="attuli2">Train the Trainer</li><li class="attuli3">First Strike Boxing</li><li class="attuli4">FIRE</li><li class="attuli5">Personal Training</li></ul>';
	$o.='<div class="classcat classcat1"><ul>';
	for($i=0;$i<count($ct_arA);$i++){
		$o.='<li>'.$ct_arA[$i].'</li>';
	}
	$o.='</ul></div>';
	$o.='<div class="classcat classcat2"><ul>';
	for($i=0;$i<count($ct_arB);$i++){
		$o.='<li>'.$ct_arB[$i].'</li>';
	}
	$o.='</ul></div>';
	$o.='<div class="classcat classcat3"><ul>';
	for($i=0;$i<count($ct_arC);$i++){
		$o.='<li>'.$ct_arC[$i].'</li>';
	}
	$o.='</ul></div>';
	$o.='<div class="classcat classcat4"><ul>';
	for($i=0;$i<count($ct_arD);$i++){
		$o.='<li>'.$ct_arD[$i].'</li>';
	}
	$o.='</ul></div>';
	$o.='<div class="classcat classcat5"><ul>';
	for($i=0;$i<count($ct_arE);$i++){
		$o.='<li>'.$ct_arE[$i].'</li>';
	}
	$o.='</ul></div>';
	ob_start();
?>
<style>
#ustgpfsec{width:100%;display:block;clear:both; margin:15px 0; overflow:hidden}.usec_l{float:left;width:40%;line-height:75px; text-transform:capitalize}.usec_l img{width:72px;height:72px;float:left;vertical-align:central;margin-right:15px;border-radius:50%;border:1px solid #999;padding:3px}.usec_r{float:right;width:60%}.usec_r div{width:16.5%;text-align:center;float:left}.usec_r div span{display:block;padding:5px;color:#fff;font-weight:bold;line-height:18px;vertical-align:central;min-height:50px;border:1px solid #000}.totliat0>span,.totliat1>span,.totliat4>span{line-height:38px !important}.usec_r div b{color:#000;border:1px solid #000;display:block}#attentable{background:#fff;padding:2%;width:96%;float:left;display:block;clear:both;margin-left:2%}.cardiocls{margin-top:10px;display:block;clear:both;padding:5px;text-align:center;color:#fff;background:#f40000;width:60%}.attentblhead{display:block;clear:both}.attentblhead li{color:#fff;padding:5px 5px}.totliat0 span{background:#f40000}.totliat1 span,.attuli1{background:#3db1f3}.totliat2 span,.attuli2{background:#98d046}.totliat3 span,.attuli3{background:#6d31a2}.totliat4 span,.attuli4{background:#f9bf00}.totliat5 span,.attuli5{background:#3db1f3}.attentblhead li,.classcat{ width:19%; float:left; display:inline-block; padding:2px 5px; text-align:center}.classcat li{color:#111}.chk_attndnc{background-color:#5890e5;border:1px;border-radius:4px;color:#fff;font-family: Open Sans;font-size: 13px;font-weight: 500;margin-left: 30px;padding:10px;}.chk_attndnc:hover{background-color:#2472C9}.usr_name {font-size: 15px;font-weight: 700;padding: 12px;}.inpt { border: 1px solid #ddd;margin-left: 5px;padding: 7px;}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
	jQuery(document).ready(function(e){(function($){
		var availableTags = <?php echo epic_get_list_of_all_user(); ?>;
		$('#attentable .uname').autocomplete({
			source:availableTags,select:function(event,ui){
				event.preventDefault();
				$('#attentable .uid').val(ui.item.value);
				$(this).val(ui.item.label)
			}
		});
		$('.sdate').datepicker({dateFormat:'d MM, yy'});
		$('.edate').datepicker({dateFormat:'d MM, yy'})
	}(jQuery))});
	</script>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	$o.=$output;
	return $o;
}

/* FUNCTION TO GENERATE SHORTCODE OF SHOW ATTENDANCE REGISTER */
function epic_show_attendance_register_shortcode(){
	$atten_arr=epic_get_post_meta();
	$o='<div id="attentable">';
	$o.='<form action="">';
	$o.='<label class="usr_name ">User Name</label>
	<input class="uname inpt" type="text" placeholder="User Name">
	<input class="uid" type="hidden" name="uid">';
	$o.='<input type="text" class="sdate inpt" name="sdate" placeholder="From (Date)">';
	$o.='<input type="text" class="edate inpt" name="edate" placeholder="To (Date)">';
	$o.='<input value="Check Attendance" type="submit" class="chk_attndnc ">';
	$o.='</form>';
	if(isset($_GET['sdate'])){ $sdate=$_GET['sdate']; }else{$sdate='';}
	if(isset($_GET['edate'])){ $edate=$_GET['edate']; }else{$edate='';}
	if(isset($_GET['uid'])){ $uid=$_GET['uid']; }else{ $uid='0'; }
		$o.=epic_all_attendance($uid,$sdate,$edate);
	$o.='</div>';
	return $o;
}
add_shortcode('epci_attendance_register','epic_show_attendance_register_shortcode');

/*-------------------------------------------------------------------------------*/
/* FUNCTION TO MAKE EPIC POINT/ACHEVEMENTS UNLOCKS'S VALUSE 0 IF THERE IS BLANK */
/*-----------------------------------------------------------------------------*/
function epic_set_defualt_value_on_epic_point_achevements(){
	?>
	<script>
	jQuery(document).ready(function(e){(function($){
		$('.upme-fire-editor-view').click(function(){
			var dpa_pt=$('#wp__dpa_points-12').val(),
			dpa_un = $('#wp__dpa_unlocked_count-20').val();
			if(!dpa_pt){$('#wp__dpa_points-20').attr('value',0)}
			if(!dpa_un){$('#wp__dpa_unlocked_count-20').attr('value',0)}
		})
	}(jQuery))});
    </script>
	<style>.customlogout{ color:#f60707;line-height:80px}</style><?php
}
add_action('wp_footer','epic_set_defualt_value_on_epic_point_achevements');
/*-----------------------------------*/
/* REMOVE ADMIN BAR FOR OTHER USERS */
/*---------------------------------*/
if(is_admin()){}
else{
    add_filter('show_admin_bar','__return_false');
}

/*-----------------------------------------------*/
/* FUNCTION TO ADD CUSTOM LOGOUT LINK IN HEADER */
/*---------------------------------------------*/
function epic_custom_logout(){
    if(is_user_logged_in()){
        $o='';
        $o.='<a class="customlogout" href="'.wp_logout_url().'">Log Out</a>';
        return $o;
    }
}

add_shortcode('epiclogout','epic_custom_logout');