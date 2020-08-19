<?php
class Epic_attandence_search {
	public function __construct(){
		add_shortcode('ATTENDANCE_SEARCH',array($this,'attendance_search'));
		add_shortcode('PREVIOUS_DAY_ATTENDANCE',array($this,'previous_day_attendance'));
		add_shortcode('NEW_DASHBOARD',array($this,'new_dashboard'));		
	}	

	/**
	* Function: attendance_search
	* Description: template for search by date. Shortcode: ATTENDANCE_SEARCH
	*/
	public function attendance_search(){
		?>
		<script type="text/javascript" src="<?php echo plugins_url();?>/epic-attendance/js/attendance-search.js">
		</script> <!--Attendance Search js-->

		<div class="attendance-search-main">		
			<div class="searchSection">
				<input type="date" id="attendanceDate" placeholder="Date" />
				<a href="javascript:void(0)" id="searchAttendance">Search</a>
			</div>

			<div id="attendanceHtml">		</div> <!--Ajax content append here-->
		</div> <!--attendance-search-main Ends-->		
		<?php		
	}

	public function new_dashboard(){
?>
	<style>		
		.column {
			float: left;
			width: 50%;
		}

		/* Clear floats after the columns */												
		.row:after {
			content: "";
			display: table;
			clear: both;
		}																						
	</style>												
<?php
		global $wpdb;
		$commonArray 	=	array();	
		$finalArray 	=	array();
		
		//	Get Ice Clients
		$users = get_users(array(
		    'meta_key'     => 'ice_client',
		    'meta_value'   => '1',
		    'meta_compare' => '=',
		));	

		$iceClients = array_column( $users,'ID' );					
		//	echo '<pre>';print_r( $iceClients ); exit; 
		

		//	Get users for last Sunday ice session
		$lastSundayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last sunday') ));	
		$lastSunday 	=	strtolower('attendance_'.date('j_MY',strtotime('last sunday'))); 				
		/*
		$sun4963		=	get_post_meta( 4963,$lastSunday,true);	
		$sun4963Array 	=	json_decode($sun4963 );		
		$sun4963Array	=	(!empty( $sun4963Array))?$sun4963Array:array();		
		*/	
		$sun4964		=	get_post_meta( 4964,$lastSunday,true);
		$sun4964Array 	=	json_decode($sun4964 );				
		$sun4964Array	=	(!empty( $sun4964Array))?$sun4964Array:array();				
		$finalArray 	=	array_unique(array_merge($finalArray, $sun4964Array));

		$commonArray[$lastSundayDays]	= implode( ',', $finalArray );	

		$secSundayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last sunday -7 days') ));	

		$secondLastSunday 	=	strtolower('attendance_'.date('j_MY',strtotime('last sunday -7 days'))); 		
		/*
		$sun4963		=	get_post_meta( 4963,$secondLastSunday,true);
		$sun4963Array 	=	json_decode($sun4963 );			
		$sun4963Array	=	(!empty( $sun4963Array))?$sun4963Array:array();		
		*/
		$sun4964		=	get_post_meta( 4964,$secondLastSunday,true);
		$sun4964Array 	=	json_decode($sun4964 );			
		$sun4964Array	=	(!empty( $sun4964Array))?$sun4964Array:array();
		$finalArray 	=	array_unique(array_merge($finalArray, $sun4964Array));

		//	Get users for last Monday ice session
		$lastMondayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last monday') ));		

		$lastMonday 	=	strtolower('attendance_'.date('j_MY',strtotime('last monday'))); 				
		$mon110			=	get_post_meta( 110,$lastMonday,true);	
		$mon110Array 	=	json_decode($mon110 );		
		$mon110Array	=	(!empty( $mon110Array))?$mon110Array:array();				
		$finalArray 	=	array_unique(array_merge($finalArray, $mon110Array));
		$mon111			=	get_post_meta( 111,$lastMonday,true);	
		$mon111Array 	=	json_decode($mon111 );		
		$mon111Array	=	(!empty( $mon111Array))?$mon111Array:array();				
		$finalArray 	=	array_unique(array_merge($finalArray, $mon111Array));

		//	First Strike Boxing for last Monday
		$mon4958		=	get_post_meta( 4958,$lastMonday,true);	
		$mon4958Array 	=	json_decode($mon4958 );		
		$mon4958Array	=	(!empty( $mon4958Array))?$mon4958Array:array();				
		$finalArray 	=	array_unique(array_merge($finalArray, $mon4958Array));

		$secMondayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last monday -7 days') ));			
		$secondLastMonday	=	strtolower('attendance_'.date('j_MY',strtotime('last monday -7 days'))); 		
		$secLastMon110			=	get_post_meta( 110,$secondLastMonday,true);	
		$secLastMon110Array 	=	json_decode($secLastMon110 );		
		$secLastMon110Array		=	(!empty( $secLastMon110Array))?$secLastMon110Array:array();			
		$finalArray 	=	array_unique(array_merge($finalArray, $secLastMon110Array));

		$secLastMon111			=	get_post_meta( 111,$secondLastMonday,true);	
		$secLastMon111Array 	=	json_decode($secLastMon111 );		
		$secLastMon111Array		=	(!empty( $secLastMon111Array))?$secLastMon111Array:array();		
		$finalArray 	=	array_unique(array_merge($finalArray, $secLastMon111Array));	

		//	First Strike Boxing for second last Monday
		$secLastMon4958			=	get_post_meta( 4958,$secondLastMonday,true);	
		$secLastMon4958Array 	=	json_decode( $secLastMon4958 );		
		$secLastMon4958Array	=	(!empty( $secLastMon4958Array))?$secLastMon4958Array:array();		
		$finalArray 			=	array_unique(array_merge($finalArray, $secLastMon4958Array));

		//	Get users for last Friday ice session
		$lastFridayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last friday') ));	
		$lastFriday 	=	strtolower('attendance_'.date('j_MY',strtotime('last friday'))); 		
		$fri4963		=	get_post_meta( 4963,$lastFriday,true);	
		$fri4963Array 	=	json_decode($fri4963 );		
		$fri4963Array	=	(!empty( $fri4963Array))?$fri4963Array:array();	
		$finalArray 	=	array_unique(array_merge($finalArray, $fri4963Array));		
		$fri676			=	get_post_meta( 676,$lastFriday,true);
		$fri676Array	=	json_decode($fri676 );		
		$fri676Array	=	(!empty( $fri676Array))?$fri676Array:array();		
		$finalArray 	=	array_unique(array_merge($finalArray, $fri676Array));		
		$commonArray[$lastFridayDays]	= implode( ',', $finalArray );		

		$seclastFridayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last friday -7 days') ));
		$secondLastFriday 	=	strtolower('attendance_'.date('j_MY',strtotime('last friday -7 days'))); 				
		$fri4963		=	get_post_meta( 4963,$secondLastFriday,true);	
		$fri4963Array 	=	json_decode($fri4963 );		
		$fri4963Array	=	(!empty( $fri4963Array))?$fri4963Array:array();	
		$finalArray 	=	array_unique(array_merge($finalArray, $fri4963Array));	
		$fri676			=	get_post_meta( 676,$secondLastFriday,true);
		$fri676Array	=	json_decode($fri676 );	
		$fri676Array	=	(!empty( $fri676Array))?$fri676Array:array();		
		$finalArray 	=	array_unique(array_merge($finalArray, $fri676Array));			
		
		//	Get users for last Wednesday ice session
		$lastWednesdayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last wednesday') ));
		$lastWednesday		=	strtolower('attendance_'.date('j_MY',strtotime('last wednesday'))); 
		$wed669	=	get_post_meta( 669,$lastWednesday,true);
		$wed669Array	=	json_decode($wed669 );				
		$wed669Array	=	(!empty( $wed669Array))?$wed669Array:array();		
		$finalArray 	=	array_unique(array_merge ($finalArray, $wed669Array));	
		$commonArray[$lastWednesdayDays]	= implode( ',', $finalArray );							
		$wed670	=	get_post_meta( 670,$lastWednesday,true);		
		$wed670Array	=	json_decode( $wed670 );						
		$wed670Array	=	(!empty( $wed670Array))?$wed670Array:array();				
		$finalArray 	=	array_unique(array_merge ($finalArray, $wed670Array));							
		$wed4961	=	get_post_meta( 4961,$lastWednesday,true);		
		$wed4961Array	=	json_decode( $wed4961 );				
		$wed4961Array	=	(!empty( $wed4961Array))?$wed4961Array:array();					
		$finalArray 	=	array_unique(array_merge ($finalArray, $wed4961Array));			
		
		$secLastWednesdayDays =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y',strtotime('last wednesday -7 days') ));								
		$secondLastWednesday 	=	strtolower('attendance_'.date('j_MY',strtotime('last wednesday -7 days'))); 	
		$wed669	=	get_post_meta( 669,$secondLastWednesday,true);
		$wed669Array	=	json_decode($wed669 );				
		$wed669Array	=	(!empty( $wed669Array))?$wed669Array:array();	
		$finalArray 	=	array_unique(array_merge ($finalArray, $wed669Array));	

		$wed670	=	get_post_meta( 670,$secondLastWednesday,true);
		$wed670Array	=	json_decode( $wed670 );				
		$wed670Array	=	(!empty( $wed670Array))?$wed670Array:array();		
		$finalArray 	=	array_unique(array_merge ($finalArray, $wed670Array));	

		$wed4961	=	get_post_meta( 4961,$secondLastWednesday,true);	
		$wed4961Array	=	json_decode( $wed4961 );				
		$wed4961Array	=	(!empty( $wed4961Array))?$wed4961Array:array();			
		$finalArray 	=	array_unique(array_merge ($finalArray, $wed4961Array));					

		//	echo '<pre>'; print_r( $iceClients ); print_r( $finalArray ); exit; 						
		$nonAttenders 	=	array_diff($iceClients,$finalArray);
		$nonAttenders 	=	array_values( $nonAttenders );		
		$userMetaTable	=	$wpdb->prefix.'usermeta';
		$retire_detail 	=	(array)$wpdb->get_results( 'SELECT user_id FROM '.$userMetaTable.' WHERE meta_key="retire_user" and meta_value="1" and user_id IN('.implode( ',',$nonAttenders ).')' );
		$iceClasses 	=	array( 4963,4964,4958,676,669,670,4961,	);				
		$retire_user_ids 	=	array_column( $retire_detail,'user_id' );
		$finalNonAttenders 	=	array_diff($nonAttenders,$retire_user_ids);

		$missingArray 	=	array();

		foreach( $finalNonAttenders as $nonUser ){
			$postMetaTable	=	$wpdb->prefix.'postmeta';
			$userLatestRecord 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like 'attendance_%' and meta_value like '%\"".$nonUser."\"%' and meta_value NOT like '%\"".$nonUser."\":%' and post_id IN(4963,4964,4958,676,669,670,4961,12418,110,111) order by `meta_id` DESC limit 0,1" );

			$nonDate 	=	str_replace('attendance_','',$userLatestRecord[0]->meta_key);
			$nonDateDetail 	=	explode( '_',$nonDate);

			if( $nonDateDetail[0] < 10 ){
				$day 	=	'0'.$nonDateDetail[0];			
			}else{
				$day 	=	$nonDateDetail[0];			
			}

			$year 	=	substr($nonDateDetail[1], -4);							
			$month 	=	str_replace( $year, '' , $nonDateDetail[1]);			
			$finalDate 	=	$year.'-'.$month.'-'.$day;		
			
			$days =	$this->calculate_number_of_days( date( 'd-m-Y' ), date( 'd-m-Y', strtotime($finalDate) ) );			
			$missingArray[$days]	=	$nonUser;	
		}
		
		krsort($missingArray);
		//echo '<pre>'; print_r( $missingArray); exit; 
		//	10188,10187		
		$quiz 	 = wp_get_attachment_url(10188);
		$message = wp_get_attachment_url(10187);	

		$current_user = wp_get_current_user();


		//	echo '<pre>'; print_r( $current_user ); exit; 
		$username =	 $current_user->user_login;
		$commentTable =	 $wpdb->prefix.'comments';		
		$ungraded = $wpdb->get_results("SELECT count(*) as totalUngraded FROM $commentTable WHERE comment_approved = '
			ungraded'", ARRAY_A);
		
		$postTable =	 $wpdb->prefix.'postmeta';		
		$messages = $wpdb->get_results("SELECT count(*) as totalMessages FROM $postTable WHERE meta_key = '_receiver' AND  meta_value = '".$username."'", ARRAY_A);
		
   		$totalUngraded 	=	$ungraded[0]['totalUngraded'];
   		$userMessages 	=	$messages[0]['totalMessages'];

?>

		<div class="row">
		  	<div class="column" id="imageSection">
		  		<div>
		  		<a href="http://members.epicwinpt.com.au/wp-admin/admin.php?page=sensei_grading"><img src="<?php echo $quiz; ?>" /></a>
		  		</div>
		  		<div class="new_dashboard_bar">
		  		<a href="http://members.epicwinpt.com.au/wp-admin/admin.php?page=sensei_grading&view=ungraded">You have <?php echo $totalUngraded; ?> Ungraded quiz submissions</a>
		  		</div>
		  		<div>
		  		<a href="http://members.epicwinpt.com.au/wp-admin/edit.php?post_type=sensei_message"><img src="<?php echo $message; ?>" /></a>	
		  		</div>
		  		<div class="new_dashboard_bar">
		  		<a href="http://members.epicwinpt.com.au/wp-admin/edit.php?post_type=sensei_message"><?php echo $userMessages;  ?> unread message(s)</a>	
		  		</div>
		  	</div>
			<div class="column" id="leaderboardSection">
				<table class="dpa-leaderboard-widget" id="ledboardmem">			
					<thead>
						<tr>
							<th id="dpa-leaderboard-position" scope="col" style="background-color:red;color:#fff !important;">#</th>
							<th id="dpa-leaderboard-name" scope="col" style="background-color:red;color:#fff !important;">Name</th>
							<th id="dpa-leaderboard-karma" scope="col" style="background-color:red;color:#fff !important;">DAYS SINCE LAST VISIT</th>
						</tr>				
					</thead>			
					<tfoot class="screen-reader-text">				
						<tr>
							<th scope="col">#</th>
							<th scope="col">Name</th>
							<th scope="col">Karma</th>
						</tr>				
					</tfoot>			
					<tbody>	
					<?php
						$i=1;
						foreach( $missingArray as $days=>$user_id ){
							$user = get_user_by( 'id', $user_id );						
							$is_retire =	get_user_meta( $user_id,'retire_user',true);

							if( !empty( $is_retire )){
								continue;
							}
					?>													
						<tr>
			    			<th scope="row" headers="dpa-leaderboard-position" class="position"><?php echo $i; ?></th>
							<td headers="dpa-leaderboard-name" align="center" id="<?php echo $user_id; ?>"><?php echo ucfirst($user->first_name) . ' ' . $user->last_name; ?></td>					
							<td headers="dpa-leaderboard-karma" align="center"><?php echo $days; ?> days</td>					
						</tr>	
					<?php 
							$i++;
						}
					?>												
					</tbody>
				</table>	
			</div>	
		</div>	
		<DIV style="clear:both"></DIV>
		<?php
		

		//	$this->get_non_attenders_for_last_two_week();
		$this->previous_day_attendance();
	}

	public function get_non_attenders_for_last_two_week(){
		global $wpdb;
		echo 'Current Date='.date('Y-m-d'); 
		exit;

	}

	public function calculate_number_of_days( $date1,$date2 ){
		// Calulating the difference in timestamps 
	    $diff = strtotime($date2) - strtotime($date1); 
	      
	    // 1 day = 24 hours 
	    // 24 * 60 * 60 = 86400 seconds 
	    return abs(round($diff / 86400)); 
	}

	/**
	* Function:	retrive_classes_ids
	*
	* @return :	array()
	*/
	public function retrive_classes_ids(){
		$args = array(
			'numberposts' 	=> -1,
			'post_type'   	=> 'mrd_classes',
			'post_status'	=> 'publish',
			'fields'		=>	'ids'
		);
		$ids = get_posts($args);
		return $ids;
	}

	/**
	* Function	:	retrive_users_by_date
	*	
	* @return 	:	$previous_attedance_array
	*/
	
	public function previous_day_attendance(){
		$date = date('Y-m-d',strtotime("-1 days"));
		Epic_attandence_search::attendance_search_template( $date );	
	}
	
	/**
	* Function	:	retrive_users_by_date
	*
	* @param	:	$post_ids, $date
	* @return 	:	$mixed_array
	*/
	public function retrive_users_by_date($post_ids=array(),$date){
		$mixed_array	=	array();
		$a_search	=	new Epic_attandence_search();
		$meta_key	=	$a_search->dynamic_meta_key($date);
		foreach($post_ids as $pid){
			$user_ids	=	get_post_meta($pid,$meta_key,true);
			if(!empty($user_ids)){
				$users	=	json_decode(str_replace('\/',' ',$user_ids),true);
				$mixed_array[$pid] = $users;
			}
		}
		return $mixed_array;
	}

	/**
	* Function:	dynamic_meta_key
	*
	* @param:	$date
	* @return:	$meta_key (lower case)
	**/
	public function dynamic_meta_key($date){
		$date_format	=	new DateTime($date);
		$new_date		=	$date_format->format('j_MY');
		$meta_key		=	trim('attendance_'.$new_date);
		return strtolower($meta_key);
	}

	/**
	* Function:	dynamic_parameter_meta_key
	*
	* @param:	$date
	* @return:	$meta_key (lower case)
	**/
	public function dynamic_parameter_meta_key($key,$date){
		$date_format	=	new DateTime($date);
		$new_date		=	$date_format->format('j_MY');
		$meta_key		=	trim($key.'_'.strtolower($new_date));
		return $meta_key;
	}
	
	/**
	* Function:	attendance_search_template
	*
	* @param:	$date
	**/
	public function attendance_search_template($date){
		$attend_search	=	new Epic_attandence_search();
		$post_ids		=	$attend_search->retrive_classes_ids();
		$mixed_array	=	$attend_search->retrive_users_by_date($post_ids,$date);						
		?>												
		<div style="margin-bottom:20px;font-weight:bold" class="search_section"><?php echo date( 'd F,Y',strtotime( $date ) ); ?></div>										
		<?php 			
		foreach($mixed_array as $key=>$values){
			$categories =	get_the_terms($key, "mrd_classes_cats" );
			$cat_array 	=	array();
			$post_id =  $key;

			foreach( $categories as $category ){
				$cat_array[]	=	$category->term_id;
			}

			?>									
			<div class="attendanceDay" pid="<?php echo $key; ?>" id="dayPart1">
				<div class="attendance-row-first">
				<span>					
					<img src="<?php echo get_the_post_thumbnail_url($key);?>" alt="<?php echo __(get_the_title($key),'cf');?>">					
					<h3><?php echo __(get_the_title($key),'cf');?></h3>
				</span>				
				<ul>								
			<?php	
			$minute30key = $attend_search->dynamic_parameter_meta_key( 'minute30', $date );					
			$minute45key = $attend_search->dynamic_parameter_meta_key( 'minute45', $date );
			$minute60key = $attend_search->dynamic_parameter_meta_key( 'minute60', $date );
			$jonTkey 	 = $attend_search->dynamic_parameter_meta_key( 'jonT', $date );				
			$liamTkey 	 = $attend_search->dynamic_parameter_meta_key( 'liamT', $date );
			$shaylaTkey  = $attend_search->dynamic_parameter_meta_key( 'shaylaT', $date );		 
			
			$durationArray 	=	array();
			$trainerArray 	=	array();		
			$counter = 0;

			foreach($values as $value){				
				$user_info = get_userdata($value);	

				if( in_array(412, $cat_array)){	
					$tempA = array();	
					$tempB = array();		
					$val30 = json_decode(get_post_meta($post_id,$minute30key,true),true);

					if( !empty( $val30[$counter])){
						$tempA[] = '30';
					}

					$val45 = json_decode(get_post_meta($post_id,$minute45key,true));

					if( !empty( $val45[$counter])){
						$tempA[] = '45';
					}

					$val60 = json_decode(get_post_meta($post_id,$minute60key,true));

					if( !empty( $val60[$counter])){
						$tempA[] = '60';
					}

					$valjon = json_decode(get_post_meta($post_id,$jonTkey,true));

					if( !empty( $valjon[$counter])){
						$tempB[] = 'Jon';
					}

					$valliam = json_decode(get_post_meta($post_id,$liamTkey,true));

					if( !empty( $valliam[$counter])){
						$tempB[] = 'Liam';
					}		

					$valshayla = json_decode(get_post_meta($post_id,$shaylaTkey,true));

					if( !empty( $valshayla[$counter])){
						$tempB[] = 'Shayla';
					}	

					$durationArray[]  = implode( ',',$tempA );
					$trainerArray[]  = implode( ',',$tempB );
					
				}			
			?>			
					<li><?php echo $user_info->first_name . ' ' . $user_info->last_name; ?></li>				
			<?php		
				$counter++;		
			}						
			?>												
				</ul>
			</div>			
			<?php	
				if( !in_array(412, $cat_array)){	
					$tempA = array();	
					$tempB = array();		
					$val30 = get_post_meta($post_id,$minute30key,true);

					if( !empty( $val30)){
						$tempA[] = '30';
					}

					$val45 = get_post_meta($post_id,$minute45key,true);

					if( !empty( $val45)){
						$tempA[] = '45';
					}

					$val60 = get_post_meta($post_id,$minute60key,true);

					if( !empty( $val60)){
						$tempA[] = '60';
					}

					$durationArray[]  = implode( ',',$tempA );

					$valjon=get_post_meta($post_id,$jonTkey,true);

					if( !empty( $valjon)){
						$tempB[] = 'Jon';
					}

					$valliam=get_post_meta($post_id,$liamTkey,true);
					if( !empty( $valliam)){
						$tempB[] = 'Liam';
					}		

					$valshayla=get_post_meta($post_id,$shaylaTkey,true);

					if( !empty( $valshayla)){
						$tempB[] = 'Shayla';
					}		

					$trainerArray[]  = implode( ',',$tempB );
				}else{


				}				
			?>
			<div class="attendance-duration-row">
				<span>					
					<h3>Duration</h3>
				</span>				
				<ul>					
					<?php 
						foreach( $durationArray as $duration ){
					?>
							<li><?php echo $duration; ?></li>
					<?php
						}
					?>										
				</ul>
			</div>												
			<div class="attendance-trainer-row">
				<span>					
					<h3>Trainer</h3>
				</span>				
				<ul>											
					<?php 
						foreach( $trainerArray as $trainer ){
					?>
							<li><?php echo $trainer; ?></li>
					<?php
						}
					?>
				</ul>
			</div>

			</div>
			<?php
		}
	}
}

$ATTENDANCE_SEARCH	=	new Epic_attandence_search();