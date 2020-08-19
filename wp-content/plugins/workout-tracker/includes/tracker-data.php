<?php
if(!defined('ABSPATH')){
	exit;
}
class EWP_WT_Tracker_Data{
	public static function init(){
        $class = __CLASS__;
        new $class;
    }

	function __construct(){
		add_action( 'wp_ajax_exercise_detail_by_id_ajax', array( $this, 'exercise_detail_by_id_ajax' ) );
       add_action( 'wp_ajax_nopriv_exercise_detail_by_id_ajax', array( $this, 'exercise_detail_by_id_ajax' ) );
	   add_action( 'wp_ajax_update_payment_method', array( $this, 'update_payment_method' ) );
       add_action( 'wp_ajax_nopriv_update_payment_method', array( $this, 'update_payment_method' ) );
	}

	function table($uid,$pid){
		?><script>
		jQuery(document).ready(function(){(function($){
			var exerciseTags 	=  <?php echo EWP_WT_Tracker_Data::wt_get_all_exercises(); ?>;
			$( function(){ $(".datepicker").datepicker();});
			$('.inputsrch').autocomplete({
				source:exerciseTags,select:function(event,ui){
					event.preventDefault();
					$(this).attr('exeid',ui.item.value);
					$(this).val(ui.item.label);
				},
			});
		}(jQuery))});
		</script>
		<div id="ewtwtt">
        	<div class="pde-tab">
                <ul class="wt_lable"><li></li>
                <li class="wtExer"><b>Exercise</b></li>
                <?php	echo EWP_WT_Tracker_Data::wt_get_exercise_list($pid);	?>
                </ul>
			</div>
			<div class="scroll-x">
				<div class="scroll-width"><?php
					for($i=1;$i<=12;$i++){ EWP_WT_Tracker_Data::program_data_filed($i,13,$uid,$pid);}?>
					<input type="hidden" id="wtpid" value="<?php echo $pid; ?>">
					<input type="hidden" id="wtuid" value="<?php echo $uid; ?>">
				</div>
			</div>
		</div><?php
	}

	/*			PROGRAM DATA		 */

	function program_data_filed($srno,$num,$uid,$pid){
		echo '<ul class="wt_data wt_data'.$srno.'" ulno="'.$srno.'">';
		echo '<li class="wtsn">'.$srno.'</li>';
		echo '<li class="wtdt"  lino="0"><input dt="d" class="wt_dat datepicker" readonly type="text" value="'.get_user_meta($uid,'wt_'.$pid.'_d_'.$srno.'_0',true).'"></li>';
		for($i=1;$i<=$num;$i++){
			$wat='wt_'.$pid.'_w_'.$srno.'_'.$i;
			$rv1='wt_'.$pid.'_r1_'.$srno.'_'.$i;
			$rv2='wt_'.$pid.'_r2_'.$srno.'_'.$i;
			$rv3='wt_'.$pid.'_r3_'.$srno.'_'.$i;
			echo '<li class="wt_data_li wt_data_li_'.$i.'" lino="'.$i.'">',
			'<input dt="w"class="wt_wet" type="tel" value="'.get_user_meta($uid,$wat,true).'">',
			'<input dt="r1" class="wt_rep wt_rep0" type="tel" value="'.get_user_meta($uid,$rv1,true).'">',
			'<input dt="r2" class="wt_rep wt_rep1" type="tel" value="'.get_user_meta($uid,$rv2,true).'">',
			'<input dt="r3" class="wt_rep wt_rep2" type="tel" value="'.get_user_meta($uid,$rv3,true).'" key="'.$rv3.'">',
			'</li>';
		}
		echo '</ul>';
	}

	function get_tracker_post($uid){
		$posts=get_posts(array('post_type'=>'workout_tracker','posts_per_page'=>-1,	'author'=> $uid,));
		if($posts){
			$i=1;
			foreach($posts as $post){
				$pid 	=	$post->ID;
				$title	=	$post->post_title;
				$user	=	$post->post_author;
				if($i==1){
					echo '<li pid="'.$pid.'" class="wttpst wttpst'.$pid.' wttabact" user_id="'.$user.'">
					<a pid="'.$pid.'" href="javascript:void(0)">'.$title.'</a>
						<input type="hidden" class="wt-pid" value="'.$pid.'">
					</li>';
				}
				else{
					$i>3 ? $hidden_class='toggleli' : $hidden_class='';
				echo '<li pid="'.$pid.'" class="wttpst wttpst'.$pid.' '.$hidden_class.'" user_id="'.$user.'">
							<a pid="'.$pid.'" href="javascript:void(0)">'.$title.'</a>
					</li>';
					
				}
				$i++;
			}
			echo '<li><input id="wtuserid" type="hidden" value="'.$uid.'"></li>';
			if(sizeof($posts)>3){
				echo'<a href="javascript:void(0)" id="hideSHOW" style="color:red;font-size:15px;font-weight:700">Expand</a>';
			}
		}
	}

	/*****************************************/
	/*			STRENGTH DATA			 	*/
	/***************************************/
	function get_strength_data($num,$uid,$pid){
		echo '<div id="ewtsdt">';
			echo '<div class="pde-tab"><ul class="wt_lable"><li></li>';
				echo '<li class="wtExer"><b>Exercise</b></li>';
				echo EWP_WT_Tracker_Data::exercise_list($pid);
			echo '</ul></div>';?>
            <div class="scroll-x">
			<div class="scroll-width"><?php
			for($srno=1;$srno<=12;$srno++){
				echo '<ul class="wt_data wt_data'.$srno.'" ulno="'.$srno.'">';
					echo '<li class="wtsn">'.$srno.'</li>';
					echo '<li class="wtdt"  lino="0">';
					echo '<span>';
					$date 	=	get_user_meta($uid,'wt_'.$pid.'_d_'.$srno.'_0',true);
					if($date){echo $date;}
					echo '</span>';
					echo '</li>';

					for($i=1;$i<=$num;$i++){
						//echo 'Tracker -'.$i.'<br/>';
						
						$wat	= 'wt_'.$pid.'_w_'.$srno.'_'.$i;
						$rv1	= 'wt_'.$pid.'_r1_'.$srno.'_'.$i;
						$rv2	= 'wt_'.$pid.'_r2_'.$srno.'_'.$i;
						$rv3	= 'wt_'.$pid.'_r3_'.$srno.'_'.$i;
						$wt		= 	get_user_meta($uid,$wat,true);						
						$r1 	= 	get_user_meta($uid,$rv1,true);
						$r2 	= 	get_user_meta($uid,$rv2,true);
						$r3 	= 	get_user_meta($uid,$rv3,true);
						
						echo '<li class="wt_data_li wt_data_li_'.$i.'" lino="'.$i.'">';
						
						if($wt){						
							$nw=explode('/',$wt);														
							$wnum=count($nw);
							
							if($wnum>0){
								if($wnum==3){									
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[0],$r1)).'|';
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[1],$r2)).'|';
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[2],$r3));									
								}
								else if($wnum==2){
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[0],$r1)).'|';
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[1],$r2));
								}
								else if($wnum==1){
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[0],$r1)).'|';
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[0],$r2)).'|';
									echo ceil(EWP_WT_Tracker_Data::wt_formula_1($nw[0],$r3));
								}
							}							
						}
												
						echo '</li>';
						
					}
				echo '</ul>';
			}?>
          </div></div><?php
		echo '</div>';
	}

	/*************************************/
	/*			 VOLUME DATA			*/
	/***********************************/
	function get_volume_data($num,$uid,$pid){
		echo '<div id="ewtsdt">';
			echo '<div class="pde-tab">';
					echo'<ul class="wt_lable">';
						echo'<li></li>';
						echo '<li class="wtExer"><b>Exercise</b></li>';
						echo EWP_WT_Tracker_Data::exercise_list($pid);
						echo '<li>Total</li>';
				echo '</ul>';
			echo'</div>';
          echo'<div class="scroll-x"><div class="scroll-width">';
				for($srno=1;$srno<=12;$srno++){
					echo '<ul class="wt_data wt_data'.$srno.'" ulno="'.$srno.'">';
						echo '<li class="wtsn">'.$srno.'</li>';
						echo '<li class="wtdt"  lino="0">';
							echo '<span>';
							$date 	=	get_user_meta($uid,'wt_'.$pid.'_d_'.$srno.'_0',true);
							if($date){echo $date;}
							echo '</span>';
						echo '</li>';
						$tarr = array();
						for($i=1;$i<=$num;$i++){
							$wat = 'wt_'.$pid.'_w_'.$srno.'_'.$i;
							$rv1 = 'wt_'.$pid.'_r1_'.$srno.'_'.$i;
							$rv2 = 'wt_'.$pid.'_r2_'.$srno.'_'.$i;
							$rv3 = 'wt_'.$pid.'_r3_'.$srno.'_'.$i;
							$wt  = get_user_meta($uid,$wat,true);
							$r1  = get_user_meta($uid,$rv1,true);
							$r2  = get_user_meta($uid,$rv2,true);
							$r3  = get_user_meta($uid,$rv3,true);
							echo '<li class="wt_data_li wt_data_li_'.$i.'" lino="'.$i.'">';
							if($wt){
								$nw=explode('/',$wt);
								$wnum=count($nw);
								if($wnum>0){
									if($wnum==3){
										$val1=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[0],$r1));
										$val2=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[1],$r2));
										$val3=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[2],$r3));
										$t1= $val1+$val2+$val3;
										echo $t1;
										array_push($tarr,$t1);
									}
									else if($wnum==2){
										$val1=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[0],$r1));
										$val2=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[1],$r2));
										$t1= $val1+$val2;
										echo $t1;
										array_push($tarr,$t1);
									}
									else if($wnum==1){
										$val1=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[0],$r1));
										$val2=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[0],$r2));
										$val3=ceil(EWP_WT_Tracker_Data::wt_formula_2($nw[0],$r3));
										$t1=  $val1+$val2+$val3;
										echo $t1;
										array_push($tarr,$t1);
									}
								}
							}
							echo '</li>';
						}
						echo '<li>';
						$total	= array_sum($tarr);
						if($total>0){
							echo $total;
						}
						echo '</li>';
					echo '</ul>';
				}
			echo'</div></div>';
		echo '</div>';
	}

	/*************************************/
	/*		 	FREE FORM DATA			*/
	/***********************************/
	function get_free_form_data($pid){
			$form_data	=	get_post_meta($pid,'free_form_data',true);
			echo'<textarea rows="20" cols="98" name="free-form-data" class="free-form-data" value="'.$form_data.'">'.$form_data.'</textarea>';
		echo '</div>';
	}
	
	/*****************************************/
	/*			CALCULATOR DATA				*/
	/***************************************/
	function get_calculator_data($pid){
		$inbetween	=	get_post_meta($pid,'inbetween',true);
		$txtarea		=	get_post_meta($pid,'txtarea',true);
		$rmwght		=	get_post_meta($pid,'rmwght',true);
		echo '
		<ul class="pinn-exercise">
				<li><h4>PINNACLE EXERCISE</h4></li>
				<li><input type="text" value="'.$inbetween.'"></li>
				<li>Previous Workout<textarea rows="2" cols="19">'.$txtarea.'</textarea></li>
			</ul>';
		echo '<ul class="sfd">
						<li><h4>See Freeform Data</h4></li>
						<ul class="rmweight">	
						<li>RM WEIGHT</li><li>REPS</li><li>12</li>
						<li>10</li><li>6</li><li>3</li><li>1</li>
						</ul>
					<ul class="see-from-data">
					<li><input type="tel" class="from-data-weight" value="'.$rmwght.'"></li>
					<li>WEIGHT</li>';
					$reps	=	json_decode(get_post_meta($pid,'reps',true));
					if($reps){
							echo'<li class="reps reps12">'.$reps[0].'</li>';
							echo'<li class="reps reps10">'.$reps[1].'</li>';
							echo'<li class="reps reps6">'.$reps[2].'</li>';
							echo'<li class="reps reps3">'.$reps[3].'</li>';
							echo'<li class="reps reps1">'.$reps[4].'</li>';
					}
					else{
				echo '<li class="reps12"></li>
						<li class="reps10"></li>
						<li class="reps6"></li>
						<li class="reps3"></li>
						<li class="reps1"></li>';
					}
					echo'</ul>
			</ul>';
		echo '<ul class="secodary-exercise">';
				echo'<li><h4>SECONDARY EXERCISE</h4></li>';
				echo EWP_WT_Tracker_Data::exercise_list($pid);
		echo'</ul>';
		echo '
			<ul class="cal-weight">
				<li><h4>WEIGHT</h4></li>';
				$values	=	json_decode(get_post_meta($pid,'calweight',true));
				if($values){
					$i=0;
					foreach($values as  $value){
						echo'<li no="'.$i.'"><input type="tel" value="'.$value.'"></li>';
						$i++;
					}
				}
				else{
					for($i=0;$i<=12;$i++){
						echo'<li no="'.$i.'"><input type="tel"></li>';
					}
				}
			echo '</ul>';
		echo '
			<ul class="warmup">';
				$warmups	=	json_decode(get_post_meta($pid,'warmup',true));
				if($warmups){
					$i=0;
					echo '<li><h6>Warm Up Set @ 60% for 6-8 reps</h6></li>';
					foreach($warmups as  $warmup){
						echo'<li no="'.$i.'">'.$warmup.'</li>';
						$i++;
					}
				}
				else{
					echo '<li><h6>Warm Up Set @ 60% for 6-8 reps</h6></li>';
					for($i=0;$i<=12;$i++){
						echo'<li no="'.$i.'"></li>';
					}
				}
			echo'</ul>';
	}

	
	/**
	**Function: Retrive All Exercise
	*
	**Return: Array of All Exercise
	*
	** Author: Dharminder Singh
	*/
	public function wt_get_all_exercises(){
		$args	=	array(
					"posts_per_page"	=>	-1,
					"post_status"		=>	"publish",
					'post_type'		=>	'exercise'					 
					);
		$exercises = get_posts($args);
		$o='';
		$o.='[';
		foreach($exercises as $exercise){
			$o.='{label:"'.$exercise->post_title.'",value:"'.$exercise->ID.'"},';
		}
		$o.=']';
		return $o;
	}
	
	function wt_formula_1($w,$r){ # Strenght Data Formula
		$w=intval($w);
		return (($w * 0.03) * intval($r)) + $w;
	}
	
	function wt_formula_2($w,$r){	# Volume Data Formula
		$w	=	intval($w);
		$r	=	intval($r);
		return ($w*$r);
	}

	/**************************************************/
	/*		 EXERCISE LIST FOR PROGRAM TAB 			 */
	/************************************************/
	function wt_get_exercise_list($pid){
		$values	=	json_decode(get_post_meta($pid,'exercise_list',true));
		if($values){
			$i=0;
			foreach($values as  $key=>$value){
				echo '<li no="'.$i.'">';
					if($value){
						echo'<span><i class="fa fa-info-circle exinfo my_popup_open "aria-hidden="true"></i></span>';
						echo'<input type="search" class="inputsrch" value="'.get_the_title($value).'"exeid="'.$value.'" autocomplete="off">';
					}
					else{
						echo'<span></span>';
						echo'<input type="search" class="inputsrch" value="'.get_the_title($value).'"exeid="'.$value.'" autocomplete="off">';
					}
					echo'</li>';
					$i++;
			}
		}else{
			for($i=0;$i<=12;$i++){
				echo '<li no="'.$i.'"><span></span><input type="search" class="inputsrch" value="" exeid="" autocomplete="off"></li>';
			}
		}
	}	

	/*****************************************************************/
	/*		 EXERCISE LIST FOR STENGTH  TAB  AND VOLUME TAB			  */
	/***************************************************************/
	function exercise_list($pid){
		$values	=	json_decode(get_post_meta($pid,'exercise_list',true));
		if($values){
			$i=0;
			foreach($values as  $key=>$value){
				echo '<li no="'.$i.'">'.get_the_title($value).'</li>';
				$i++;
			}
		}
		else{	for($i=0;$i<=12;$i++){ echo '<li no="'.$i.'"></li>';}}
	}
	
	/**
	*
	**Get exercise detail by ajax
	*
	**Return :Template
	*/
	public function exercise_detail_by_id_ajax(){
		if($_REQUEST){
			$pid	=	$_POST['program_id'];
			EWP_WT_Tracker_Data::wt_get_exercise_template($pid);
		}
		die();
	}
	
	public function update_payment_method(){
		if($_REQUEST){
			$pid				=	$_POST['uid'];
			$payment_method	=	$_POST['method'];
			$act				=	$_POST['act'];
			if($act=='update'){
				if(update_user_meta($pid,'Workout_Payment_Method',$payment_method)){
					$response=array(
								'response'=>1,
								'response'=>'Method updated'
					);
				}
				else{
					$response=array(
								'response'=>0,
								'response'=>'Method could not updated'
					);
				}
			}
			if($act=='retrive'){
				$value	=	get_user_meta($pid,'Workout_Payment_Method',true);
				$response=array(
								'response'=>1,
								'response'=>'Method could not updated',
								'value'=>$value
					);
			}
			echo json_encode($response);
			die();
		}
	}
	
	public function wt_get_exercise_template($pid){
		$exercise_title			=	get_post_meta($pid,'exercise_title',true);
		$exercise_description		=	get_post_meta($pid,'exercise_description',true);
		$exercise_instructions	=	get_post_meta($pid,'exercise_instructions',true);
		$youtube_link				=	get_post_meta($pid,'youtube_link',true);
		echo '<div class="modal-content">
			<div class="modal-header">
				<span class="close">&times;</span>
				<h2>'.$exercise_title.'</h2>
			</div>
		<div class="modal-body">';
		echo get_the_post_thumbnail($pid);
		if($exercise_description){
			echo '<h4>DESCRIPTION</h4>';
			echo '<p class="exdes">'.$exercise_description.'</p>';
		}
		echo '<ul class="einst">';
		echo '<h4>INSTRUCTIONS</h4>';
		for($i=0;$i<count($exercise_instructions);$i++){
			echo '<li>'.$exercise_instructions[$i]['description'].'</li>';
		}
		echo '</ul>';
		if($youtube_link){
			echo '<iframe width="550" height="300" src="https://www.youtube.com/embed/'.$youtube_link.'" frameborder="0" allowfullscreen></iframe>';
		}
		echo '</div>';
		echo '</div>';
		die();
	}
}
add_action('plugins_loaded',array('EWP_WT_Tracker_Data','init'));