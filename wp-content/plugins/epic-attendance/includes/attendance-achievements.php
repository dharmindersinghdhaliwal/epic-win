<?php
/**
	Attendance Achievements for all clients
*/	
add_shortcode('attendance_achievements','display_attendance_achievements');

function display_attendance_achievements(){
	?>
	<div class="attendance-achievements-main">
		<?php
		global $wpdb;
		$args	=	array('number' => $users_per_page,'paged' => $current_page);
		$users	=	new WP_User_Query( $args );
		
		if ( $users->get_results() ) foreach( $users->get_results() as $user ){
			$uid	=	$user->ID;

			$is_user_retired =	get_user_meta($uid,'retire_user',true);

			if( !empty( $is_user_retired ))
				continue;

			$name	=	$user->display_name;
			$ice	=	sizeof(epic_get_current_year_attendance($uid,'ice'));			
			$fire	=	sizeof(epic_get_current_year_attendance($uid,'fire-classes'));
			$pt		=	sizeof(epic_get_current_year_attendance($uid,'personal-training-sessions'));
			$boxing	=	sizeof(epic_get_current_year_attendance($uid,'first-strike-boxing'));

			$unlocked		=	get_user_meta($uid,$wpdb->prefix.'_dpa_unlocked_count',true);
			$unlocked		=	!empty($unlocked) ? $unlocked : 0;
			global $epic;
			?>
			<div class="repeat-attendance" id="<?php echo $uid; ?>">			
				<div class=" desktop-attendance attendance-achievements-label">
					<label>10</label><label>20</label><label>30</label><label>40</label><label>50</label>
					<label>60</label><label>70</label><label>80</label><label>90</label><label>100</label>
				</div> <!--attendance-achievements-label Ends-->
				
				<div class="attendance-achievements-left">
					<div class="attendance-achievements-img">
						<a href="<?php echo get_site_url().'/profile/'.$uid; ?>" title="<?php echo __($name,'cf');?>">
							<?php echo $epic->pic($uid,50);?>
						</a>
					</div> <!--attendance-achievements-img Ends-->
					<div class="attendance-achievements-info">
						<p class="info-name"> <?php echo __($name,'cf'); ?></p>
						<p class="info-achievements"><?php echo __('Achievement Unlocked:','cf');?></p>
						<p class="info-count"><?php echo $unlocked; ?></p>
						<p class="info-remind">
						<?php
							$is_unlock	=	dpa_has_user_unlocked_achievement($uid,6233);
							$is_unlock	=	$is_unlock? 'Success': '111 Unlock';
							$unlock		=	dpa_has_user_unlocked_achievement($uid,7350);
							$unlock		= 	$unlock? 'Success': '222 Unlock';
						?><span id="6233" uid="<?php echo $uid; ?>"><?php echo __($is_unlock,'cf');?></span>
							&nbsp;&nbsp;<span id="7350" uid="<?php echo $uid; ?>"><?php echo __($unlock,'cf');?></span>
						</p>
					</div>
				</div><!--attendance-achievements-left Ends-->
				<div class="attendance-achievements-right">
					<ul>
						<li id="411" uid="<?php echo $uid; ?>">
							<span class="class-title">
								<?php echo __('Ice Sessions','cf');?>
							</span>
							<span class="total-attendance"><?php echo $ice; ?></span>
							<?php	echo epic_loop_for_buttons($ice,100,$uid,411);	?>
						</li>
						
						<li id="412" uid="<?php echo $uid; ?>">
							<span class="class-title">
								<?php echo __('Personal Training','cf');?>
							</span>
							<span class="total-attendance"><?php echo $pt; ?></span>
							<?php echo epic_loop_for_buttons($pt,100,$uid,412);?>
						</li>
						
						<li id="409" uid="<?php echo $uid; ?>">
							<span class="class-title">
								<?php echo __('Fire','cf');?>
							</span>
							<span class="total-attendance"><?php echo $fire; ?></span>
							<?php echo epic_loop_for_buttons($fire,100,$uid,409);?>
						</li>
						
						<li id="410" uid="<?php echo $uid; ?>">
							<span class="class-title">
								<?php echo __('Strike Boxing','cf');?>
							</span>
							<span class="total-attendance"><?php echo $boxing; ?></span>
							<?php echo epic_loop_for_buttons($boxing,50,$uid,410);?>
						</li>
						
					</ul>

					<div class="mobile-attendance attendance-achievements-label">
						<label>10</label><label>20</label><label>30</label><label>40</label><label>50</label>
						<label>60</label><label>70</label><label>80</label><label>90</label><label>100</label>
					</div> <!--attendance-achievements-label Ends-->

				</div> <!-- attendance-achievements-right Ends-->
			</div><!--repeat-attendance End-->
		<?php
		}
		?>
		<div class="clear:both"></div>
	</div>	 <!--attendance-achievements-main Ends-->
	<script type="text/javascript" src="<?php echo plugins_url();?>/epic-attendance/js/attendance-search.js">
	</script> <!--Attendance Search js-->
	<?php
}

/**
*	Compares two numbers
*
*	@param:	 $num1,$num2 (int)
*	@return: $string
*/
function epic_button_name($num1,$num2){
	$string	=	$num1>=$num2? 'Success' : 'Unlock';
	return $string;
}

/**
*	Loop to display buttons
*
*	@param:	$num
*	@param: $limit (sets the limit of loop)
*	@param:	$uid
*	@param:	$tid (Term id)
*/
function epic_loop_for_buttons($num,$limit,$uid,$tid){		
	$j	=	0;				
	for($i=10;$i<=$limit;$i+=10){							
		$achievement_id	=	epic_get_achievements_by_name($tid,$i);				
		$is_unlock	=	dpa_has_user_unlocked_achievement($uid,$achievement_id[0]);				
		$is_unlock	=	$is_unlock ? $is_unlock : 0;		
		$class1		=	epic_button_name($num,$i);

		if(($is_unlock==0)&&($class1=='Success')){
			$class2='not-active';
		}		
		else if (($is_unlock==1)&&($class1=='Unlock')){
			$class2='not-active';
		}
		else if (($is_unlock==1)&&($class1=='Success')){
			$class2='active';
		}
		else{$class2='not-active';}
		
		$string		=	($is_unlock==1)&&($class2=='active')? 'Success' : 'Unlock';
		?>
		<a href="javascript:void(0)" class="<?php echo $class1.' '.$class2; ?>" status="<?php echo $is_unlock;?>" num="<?php echo $i;?>" title="<?php echo get_the_title($achievement_id[0]) ;?>">
		<?php echo __($string,'cf');?>
		</a>
		<?php
		$j++;
	}
}

/**
*	Get achievement by name
*
*	@param:	$id (taxonomy id)
*	@param:	$int
*	@return: array
*/	
function epic_get_achievements_by_name($id,$int){
	if($id==409){
		$name	=	$int.' FIRE Sessions';
	}
	if($id==410){
		 $name	=	$int.' First Strike Boxing Sessions';
	}
	if($id==411){
		 $name	=	$int.' I.C.E. Sessions';
	}
	if($id==412){
		 $name	=	$int.' PT Sessions';
	}
	$args = array(
		'post_type'	=>	'achievement',
		'fields' 		=> 'ids',
		'name' 			=> $name,
		);
	$achievements = get_posts($args);
	return $achievements;
}

/**
*	Unlock achievements by id without redemption code
*
*	@param:	int $user_id
*	@param: array/object $achievements
*/
function epic_unlock_achievements_by_id($user_id,$achievements){
	$achievements_to_add = is_array($achievements) ? $achievements : array($achievements);
	if ( ! empty( $achievements_to_add ) ) {
		// Get achievements to add
		$new_achievements = dpa_get_achievements( array(
			'post__in'       => $achievements_to_add,
			'posts_per_page' => count( $achievements_to_add ),
		) );			
		// Get any still-locked progress for this user
		$existing_progress = dpa_get_progress( array(
			'author'      => $user_id,
			'post_status' => dpa_get_locked_status_id(),
		) );
		foreach ( $new_achievements as $achievement_obj ) {
			$progress_obj = array();
			// If we have existing progress, pass that to dpa_maybe_unlock_achievement().
			foreach ( $existing_progress as $progress ) {
				if ( $achievement_obj->ID === $progress->post_parent ){
					$progress_obj = $progress;
					break;
				}
			}
			dpa_maybe_unlock_achievement( $user_id, 'skip_validation', $progress_obj, $achievement_obj );
		}
	}
}

/**
*	remove achievement by id
*
*	@param:	array() $achievement_id
*	@param: int $user_id
*/
function epic_remove_achievement_by_id($achievement_id,$user_id){
	
	$achievement = is_array($achievement_id) ? $achievement_id : array($achievement_id);
	// Remove achievements :(
	if ( ! empty( $achievement ) ) {
		foreach ( $achievement as $aid )
			dpa_delete_achievement_progress( $aid, $user_id );
	}
}

/**
*	Get current year attendance of user
*
*	@param:	$uid 
*	@param:	$term_slug
*	@return: array
*/
function epic_get_current_year_attendance($uid,$term_slug){
	$dates		=	array();		
	$meta_array	=	epic_attendance_post_meta($uid);
	// echo '<pre>'; print_r( $meta_array ); exit; 

	$sdate 	=	'1 January,'.date('Y');
	//echo '<br/>';
	$edate 	=	date('j F,Y');
	
	foreach($meta_array as $attendance){						
		$id			=	$attendance->post_id;
		$meta_key	=	$attendance->meta_key;
		$meta_value=	$attendance->meta_value;		
		$cat		=	get_the_terms($id,'mrd_classes_cats');
		$slug		=	$cat[0]->slug;		
		$cattarra	=	json_decode($meta_value,true);
		$cadate 	=	epic_attendance_meta_key_decode($meta_key);				
		$cadateST	=	strtotime($cadate);		
		$sdateST	=	strtotime(epic_attendance_meta_key_decode(epic_attendance_meta_key_encode($sdate)));
		$edateST	=	strtotime(epic_attendance_meta_key_decode(epic_attendance_meta_key_encode($edate)));
		/*
		if($cadateST>=$sdateST){
			if(in_array($uid,$cattarra)){
				if ($slug==$term_slug){
					array_push($dates,$cadate);
				}
			}
		}
		*/

		if(empty($sdate) && empty($edate)){
			if(in_array($uid,$cattarra)){
				if ($slug==$term_slug){
					array_push($dates,$cadate);
				}
			}
		}
		else if($sdate && empty($edate)){
			if($cadateST>=$sdateST){
				if(in_array($uid,$cattarra)){
					if ($slug==$term_slug){
						array_push($dates,$cadate);
					}
				}
			}
		}
		else if(empty($sdate) && $edate){
			if($cadateST<=$edateST){
				if(in_array($uid,$cattarra)){
					if ($slug==$term_slug){
						array_push($dates,$cadate);
					}
				}
			}
		}
		else if($sdate && $edate){
			if($cadateST>=$sdateST && $cadateST<=$edateST){
				if(in_array($uid,$cattarra)){
					if ($slug==$term_slug){
						array_push($dates,$cadate);
					}
				}
			}
		}
		else{
			if(in_array($uid,$cattarra)){
				if ($slug==$term_slug){
					array_push($dates,$cadate);
				}
			}
		}
	}
	return $dates;
}

/**
*	Search attendance key from postmeta
*
*	@return: array
*/
function epic_attendance_post_meta($uid){
    global $wpdb;
	$uid ='"'.$uid.'"';
    $pfx=$wpdb->base_prefix;	
	//return $wpdb->get_results("SELECT `post_id`,`meta_key`,`meta_value` FROM `".$pfx."postmeta` WHERE `meta_key` LIKE '%attendance_%'");

	return $wpdb->get_results("SELECT `post_id`,`meta_key`,`meta_value` FROM `".$pfx."postmeta` WHERE `meta_value` LIKE '%$uid%' AND `meta_key` LIKE 'attendance_%'");
}