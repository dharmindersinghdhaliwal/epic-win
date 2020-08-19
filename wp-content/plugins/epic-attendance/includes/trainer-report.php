<?php
class Epic_trainer_report {
	public function __construct(){
		add_shortcode('TRAINER_REPORT',array($this,'trainer_report_block'));	
		add_action("wp_ajax_search_trainer_report",array($this, "search_trainer_report"));
		add_action("wp_ajax_nopriv_search_trainer_report", array($this,"search_trainer_report"));
		
	}											
	/**
	* Function: Search Trainer Report
	* Description: This section will be ajax section to search trainer report
	*/

	public function search_trainer_report(){
		//	echo '<pre>'; print_r( $_POST ); exit; 
		$result = array();
		//	Get start date
		$start_date_array 	=	(isset($_REQUEST[ 'start_date' ]))?explode('/', $_REQUEST[ 'start_date' ]):'';
		$end_date_array 	=	(isset($_REQUEST[ 'end_date' ]))?explode('/', $_REQUEST[ 'end_date' ]):'';
		$start_date 		=	$start_date_array[2].'-'.$start_date_array[1].'-'.$start_date_array[0];
		$end_date 			=	$end_date_array[2].'-'.$end_date_array[1].'-'.$end_date_array[0];

		$html.= $this->get_trainer_record_from_dates( $start_date, $end_date );
		$result[ 'response' ]  =  1; 
		$result[ 'html' ]  =  $html; 		
		echo json_encode( $result ); exit; 
	}

	/**
	* Function: trainer_report
	* Description: template for search trainer report with date range Shortcode: ATTENDANCE_SEARCH
	*/

	public function trainer_report_block(){				
		$current_day = strtolower(date('l'));
		$last_monday = '';
		$last_sunday = '';

		if( $current_day == 'monday' ){
			$last_monday	=	date('d/m/Y',strtotime('last monday')); 
			$start_date 	=	date('Y-m-d',strtotime('last monday')); 
		}else{
			$last_monday	=	date('d/m/Y',strtotime('last monday -7 days'));
			$start_date 	=	date('Y-m-d',strtotime('last monday -7 days'));
		}

		$last_sunday	=	date('d/m/Y',strtotime('last sunday')); 
		$end_date  		=	date('Y-m-d',strtotime('last sunday'));

		$html =	'<div class="trainer_block"><input type="hidden" id="admin_ajax_url" name="admin_ajax_url" value="'.admin_url( 'admin-ajax.php' ).'" />';
		$html.= '<p><span class="trainer_child"><input placeholder="Start Date" class="user-menus-title" type="text" name="trainer_start_date" id="trainer_start_date" autocomplete="off" value="'.$last_monday.'"></span>';

		$html.= '<span style="padding-left:3%" class="trainer_child"><input placeholder="End Date" class="user-menus-title" type="text" name="trainer_end_date" id="trainer_end_date" autocomplete="off" value="'.$last_sunday.'"></span>';

		$html.= '<span style="padding-left:3%" class="trainer_child"><button id="search_trainer_data" class="serch_user_notes">Search Trainer Records</button></span>';			
		
		$html.= '</p><br/><br/>';
		$html.= '</div>';
		$html.= '<div id="trainer_information_block">';		
		$html.= $this->get_trainer_record_from_dates( $start_date, $end_date );
		$html.= '</div>';

		$html.= '<script type="text/javascript" src="'.plugins_url().'/epic-attendance/js/trainer_record_search_new.js'.'"></script>';

		$html.= '<link rel="stylesheet" type="text/css" href="'.plugins_url().'/epic-attendance/css/trainer-report.css'.'" media="screen"/>';		
		return $html;
	}
				
	public function get_trainer_record_from_dates( $start_date, $end_date ){
		$current_date 	= 	$start_date;
		$header 		=  	array();
		//$header[1]		=	date('D d/m/Y', strtotime($current_date));

		$db_users = get_users();	
		$sorted_users = array();

		foreach( $db_users as $user ){
			$sorted_users[$user->data->ID]  =  $user->data->display_name;
		}

		//	Initiaize Variables for All Trainer Section
		$all_minute_30_array  =  array();
		$all_min_total_30 	  =  0;
		$all_minute_45_array  =  array();
		$all_min_total_45 	  =  0;
		$all_minute_60_array  =  array();
		$all_min_total_60 	  =  0;

		//	Initiaize Variables for Jon Trainer Section
		$jonT_minute_30_array  =  array();
		$jonT_minute_total_30  =  0;
		$jonT_minute_45_array  =  array();
		$jonT_minute_total_45  =  0;
		$jonT_minute_60_array  =  array();
		$jonT_minute_total_60  =  0;

		$jonT_30_users_array  =  array();
		$jonT_30_users_total  =  0;
		$jonT_45_users_array  =  array();
		$jonT_45_users_total  =  0;
		$jonT_60_users_array  =  array();
		$jonT_60_users_total  =  0;

		while( $current_date <= $end_date ){									
			$response 		=	json_decode( $this->get_trainer_record_detail( $current_date,$sorted_users ) );			
			
			// echo '<pre>'; print_r( $response ); exit; 

			// All Minute 30 Section
			$all_minute_30_array[] 	=		$response->all_minute30;			
			$all_min_total_30+= $response->all_total_30_sessions;			

			// All Minute 45 Section
			$all_minute_45_array[] 	=		$response->all_minute45;			
			$all_min_total_45+= $response->all_total_45_sessions;			

			// All Minute 60 Section
			$all_minute_60_array[] 	=		$response->all_minute60;			
			$all_min_total_60+= $response->all_total_60_sessions;	

			$all_total_sessions_array[]	=	$response->all_total_sessions;  
			$all_total_hours_array[]	=	$response->all_total_hours;  

			//  all_total_sessions_value
			$all_total_sessions_total+=  $response->all_total_sessions_value; 

			//	All Total Hours value
			$all_total_hours_total+= (float)$response->all_total_hours_value; 			

			// Jon Minute 30 Section
			$jonT_minute_30_array[] 	=		$response->jonT_minute30;			
			$jonT_minute_total_30+= $response->jonT_total_30_sessions;			

			// All Minute 45 Section
			$jonT_minute_45_array[] 	=		$response->jonT_minute45;			
			$jonT_minute_total_45+= $response->jonT_total_45_sessions;			

			// All Minute 60 Section
			$jonT_minute_60_array[] 	=		$response->jonT_minute60;			
			$jonT_minute_total_60+= $response->jonT_total_60_sessions;	

			$jonT_total_sessions_array[]	=	$response->jonT_total_sessions;  
			$jonT_total_hours_array[]	=	$response->jonT_total_hours;  

			//  all_total_sessions_value
			$jonT_total_sessions_total+=  $response->jonT_total_sessions_value; 

			//	All Total Hours value
			$jonT_total_hours_total+= (float)$response->jonT_total_hours_value; 

			$jonT_30_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->jonT_users_30.'</td>';

			$jonT_45_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->jonT_users_45.'</td>';

			$jonT_60_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->jonT_users_60.'</td>';

			// Liam Minute 30 Section
			$liamT_minute_30_array[] 	=	$response->liamT_minute30;			
			$liamT_minute_total_30+= $response->liamT_total_30_sessions;			

			// Liam Minute 45 Section
			$liamT_minute_45_array[] 	=	$response->liamT_minute45;			
			$liamT_minute_total_45+= $response->liamT_total_45_sessions;			

			// Liam Minute 60 Section
			$liamT_minute_60_array[] 	=	$response->liamT_minute60;			
			$liamT_minute_total_60+= $response->liamT_total_60_sessions;	

			$liamT_total_sessions_array[]	=	$response->liamT_total_sessions;  
			$liamT_total_hours_array[]	=	$response->liamT_total_hours;  

			//  Liam Total Sessions Value
			$liamT_total_sessions_total+=  $response->liamT_total_sessions_value; 

			//	Liam Total Hours value
			$liamT_total_hours_total+= (float)$response->liamT_total_hours_value;

			$liamT_30_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->liamT_users_30.'</td>';

			$liamT_45_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->liamT_users_45.'</td>';

			$liamT_60_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->liamT_users_60.'</td>';

			// Shayla Minute 30 Section
			$shaylaT_minute_30_array[] 	=	$response->shaylaT_minute30;			
			$shaylaT_minute_total_30+= $response->shaylaT_total_30_sessions;			

			// Shayla Minute 45 Section
			$shaylaT_minute_45_array[] 	=	$response->shaylaT_minute45;			
			$shaylaT_minute_total_45+= $response->shaylaT_total_45_sessions;			

			// Shayla Minute 60 Section
			$shaylaT_minute_60_array[] 	=		$response->shaylaT_minute60;			
			$shaylaT_minute_total_60+= $response->shaylaT_total_60_sessions;	

			$shaylaT_total_sessions_array[]	=	$response->shaylaT_total_sessions;  
			$shaylaT_total_hours_array[]	=	$response->shaylaT_total_hours;  

			//  Shayla Total Sessions Value
			$shaylaT_total_sessions_total+=  $response->shaylaT_total_sessions_value; 

			$shaylaT_30_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->shaylaT_users_30.'</td>';

			$shaylaT_45_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->shaylaT_users_45.'</td>';

			$shaylaT_60_users_array[]		=	'<td data-label="'.date('D d/m/Y', strtotime($current_date)).'">'.$response->shaylaT_users_60.'</td>';

			//	Shayla Total Hours value
			$shaylaT_total_hours_total+= (float)$response->shaylaT_total_hours_value; 						
			$header[]		=	'<th scope="col">'.date('D d/m/Y', strtotime($current_date)).'</th>';					
			$current_date 	=	date('Y-m-d', strtotime($current_date .' +1 day'));
						
		}

		//echo '<pre>'; print_r( $all_minute_30_array );print_r( $all_minute_45_array );print_r( $all_minute_60_array ); exit;  

		// All Minute 30 Array
		$all_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$all_min_total_30.'</td>');	
		$all_min_30_total_hrs   =  (float)($all_min_total_30/2);
		$all_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$all_min_30_total_hrs.'</td>');	

		// All Minute 45 Array
		$all_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$all_min_total_45.'</td>');	
		$all_min_45_total_hrs   =  (float)(($all_min_total_45*3)/4);
		$all_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$all_min_45_total_hrs.'</td>');	

		// All Minute 60 Array
		$all_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$all_min_total_60.'</td>');	
		$all_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$all_min_total_60.'</td>');	

		// All Total Session Array
		$all_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$all_total_sessions_total.'</td>');	

		$all_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">0</td>');	
		$all_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$all_total_hours_total.'</td>');							
		$all_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$all_total_hours_total.'</td>');

		// Jon 30 Minute Section
		$jonT_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$jonT_minute_total_30.'</td>');	
		$jonT_minute_30_total_hrs   =  (float)($jonT_minute_total_30/2);
		$jonT_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$jonT_minute_30_total_hrs.'</td>');	

		// Jon 45 Minute Section
		$jonT_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$jonT_minute_total_45.'</td>');	
		$jonT_minute_45_total_hrs   =  (float)(($jonT_minute_total_45*3)/4);
		$jonT_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$jonT_minute_45_total_hrs.'</td>');	

		// Jon 60 Minute Section
		$jonT_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$jonT_minute_total_60.'</td>');
		$jonT_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$jonT_minute_total_60.'</td>');	


		// JON Total Session Array
		$jonT_total_session_breakdown_array		=	array();
		$jonT_total_session_breakdown_array		=	$jonT_total_sessions_array;
		$jonT_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$jonT_total_sessions_total.'</td>');	

		$jonT_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">0</td>');	

		// JON Total Hours Array
		$jonT_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$jonT_total_hours_total.'</td>');							
		$jonT_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$jonT_total_hours_total.'</td>');
		$jonT_total_session_breakdown_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$jonT_total_sessions_total.'</td>');
		$jonT_total_session_breakdown_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$jonT_total_hours_total.'</td>');

		// Liam 30 Minute Section
		$liamT_total_session_breakdown_array	=	array();
		$liamT_total_session_breakdown_array	=	$liamT_total_sessions_array;
		$liamT_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$liamT_minute_total_30.'</td>');	
		$liamT_minute_30_total_hrs   =  (float)($liamT_minute_total_30/2);
		$liamT_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$liamT_minute_30_total_hrs.'</td>');	

		// Liam 45 Minute Section
		$liamT_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$liamT_minute_total_45.'</td>');	
		$liamT_minute_45_total_hrs   =  (float)(($liamT_minute_total_45*3)/4);
		$liamT_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$liamT_minute_45_total_hrs.'</td>');	

		// Liam 60 Minute Section
		$liamT_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$liamT_minute_total_60.'</td>');
		$liamT_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$liamT_minute_total_60.'</td>');	

		// Liam Total Session Array
		$liamT_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$liamT_total_sessions_total.'</td>');	

		$liamT_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">0</td>');	
		
		// Liam Total Hours Array
		$liamT_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$liamT_total_hours_total.'</td>');							
		$liamT_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$liamT_total_hours_total.'</td>');

		$liamT_total_session_breakdown_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$liamT_total_sessions_total.'</td>');
		$liamT_total_session_breakdown_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$liamT_total_hours_total.'</td>');

		// Shayla 30 Minute Section		
		$shaylaT_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$shaylaT_minute_total_30.'</td>');	
		$shaylaT_minute_30_total_hrs   =  (float)($shaylaT_minute_total_30/2);
		$shaylaT_minute_30_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$shaylaT_minute_30_total_hrs.'</td>');	

		// Shayla 45 Minute Section
		$shaylaT_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$shaylaT_minute_total_45.'</td>');	
		$shaylaT_minute_45_total_hrs   =  (float)(($shaylaT_minute_total_45*3)/4);
		$shaylaT_minute_45_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$shaylaT_minute_45_total_hrs.'</td>');	

		// Shayla 60 Minute Section
		$shaylaT_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$shaylaT_minute_total_60.'</td>');
		$shaylaT_minute_60_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$shaylaT_minute_total_60.'</td>');

		// Shayla Total Session Array
		$shaylaT_total_session_breakdown_array	=	array();
		$shaylaT_total_session_breakdown_array	=	$shaylaT_total_sessions_array;
		$shaylaT_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$shaylaT_total_sessions_total.'</td>');	

		$shaylaT_total_sessions_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">0</td>');	
		
		// Shayla Total Hours Array
		$shaylaT_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$shaylaT_total_hours_total.'</td>');							
		$shaylaT_total_hours_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$shaylaT_total_hours_total.'</td>');

		$shaylaT_total_session_breakdown_array[]	=  htmlspecialchars('<td data-label="TOTAL SESSIONS">'.$shaylaT_total_sessions_total.'</td>');
		$shaylaT_total_session_breakdown_array[]	=  htmlspecialchars('<td data-label="TOTAL HRS">'.$shaylaT_total_hours_total.'</td>');

		//echo '<pre>'; print_r( $header ); exit; 
		$response 	=  	array();				
		$html.= '<table class="table-one" id="trainer_table"><caption class="pd-t-b-20"><h2>The Week That Was… </h2>
            <h3>'.date( 'l d F,Y',strtotime( $start_date ) ).' - '.date( 'l d F,Y',strtotime( $end_date ) ).' </h3><h4>Trainer Productivity Report</h4></caption><thead><tr><th scope="col">All Trainers</th>'.implode('',$header).'<th scope="col">TOTAL SESSIONS</th><th scope="col">TOTAL HRS</th></tr></thead>';
                
        //echo '<pre>'; print_r( $all_minute_30_array ); exit; 
       	$all_minute_30_string =	 htmlspecialchars_decode(implode( '',$all_minute_30_array ));
       	$all_minute_45_string =	 htmlspecialchars_decode(implode( '',$all_minute_45_array ));
       	$all_minute_60_string =	 htmlspecialchars_decode(implode( '',$all_minute_60_array ));       	
       	$all_total_sessions_string  = htmlspecialchars_decode(implode( '',$all_total_sessions_array ));
       	$all_total_hours_string  = htmlspecialchars_decode(implode( '',$all_total_hours_array ));     	

        $html.= '<tbody><tr><td scope="row" data-label="All Trainers">30 Minute Sessions</td>'.$all_minute_30_string.'</tr><tr><td scope="row" data-label="All Trainers">45 Minute Sessions</td>'.$all_minute_45_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="All Trainers">60 Minute Sessions</td>'.$all_minute_60_string.'</tr>';
        $html.= '<tr class="total-session-clr">
                <td scope="row" data-label="All Trainers">Total Sessions</td>'.$all_total_sessions_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="All Trainers">Total Hours</td>'.$all_total_hours_string.'</tr></tbody><thead>';

        $jonT_minute_30_string =	 htmlspecialchars_decode(implode( '',$jonT_minute_30_array ));
       	$jonT_minute_45_string =	 htmlspecialchars_decode(implode( '',$jonT_minute_45_array ));
       	$jonT_minute_60_string =	 htmlspecialchars_decode(implode( '',$jonT_minute_60_array ));       	
       	$jonT_total_sessions_string  = htmlspecialchars_decode(implode( '',$jonT_total_sessions_array ));
       	$jonT_total_hours_string  = htmlspecialchars_decode(implode( '',$jonT_total_hours_array ));     	
       	$jonT_total_session_breakdown_string  = htmlspecialchars_decode(implode( '',$jonT_total_session_breakdown_array ));     	

        //	Jon All Sessions
        $html.= '<tr><th scope="col">Jon</th>'.implode('',$header).'<th scope="col">TOTAL SESSIONS</th><th scope="col">TOTAL HRS</th></tr>';
        
        $html.= '</thead><tbody>';
        $html.= '<tr><td scope="row" data-label="Jon">30 Minute Sessions</td>'.$jonT_minute_30_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="Jon">45 Minute Sessions</td>'.$jonT_minute_45_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="Jon">60 Minute Sessions</td>'.$jonT_minute_60_string.'</tr>';

        $html.= '<tr class="total-session-clr"><td scope="row" data-label="Jon">Total Sessions</td>'.$jonT_total_sessions_string.'</tr>';

        $html.= '<tr><td scope="row" data-label="Jon">Total Hours</td>'.$jonT_total_hours_string.'</tr>';
        $html.= '</tbody><thead>';

        $liamT_minute_30_string =	 htmlspecialchars_decode(implode( '',$liamT_minute_30_array ));
       	$liamT_minute_45_string =	 htmlspecialchars_decode(implode( '',$liamT_minute_45_array ));
       	$liamT_minute_60_string =	 htmlspecialchars_decode(implode( '',$liamT_minute_60_array ));       	
       	$liamT_total_sessions_string  = htmlspecialchars_decode(implode( '',$liamT_total_sessions_array ));
       	$liamT_total_hours_string  = htmlspecialchars_decode(implode( '',$liamT_total_hours_array ));     	
       	$liamT_total_session_breakdown_string  = htmlspecialchars_decode(implode( '',$liamT_total_session_breakdown_array ));     	

        //	Liam Sessions
        $html.= '<tr><th scope="col">Liam</th>'.implode('',$header).'<th scope="col">TOTAL SESSIONS</th><th scope="col">TOTAL HRS</th></tr>';

        $html.= '</thead><tbody>';
        $html.= '<tr><td scope="row" data-label="Liam">30 Minute Sessions</td>'.$liamT_minute_30_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="Liam">45 Minute Sessions</td>'.$liamT_minute_45_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="Liam">60 Minute Sessions</td>'.$liamT_minute_60_string.'</tr>';

        $html.= '<tr class="total-session-clr"><td scope="row" data-label="Liam">Total Sessions</td>'.$liamT_total_sessions_string.'</tr>';

        $html.= '<tr><td scope="row" data-label="Liam">Total Hours</td>'.$liamT_total_hours_string.'</tr>';
        $html.= '</tbody><thead>';

        $shaylaT_minute_30_string =	 htmlspecialchars_decode(implode( '',$shaylaT_minute_30_array ));
       	$shaylaT_minute_45_string =	 htmlspecialchars_decode(implode( '',$shaylaT_minute_45_array ));
       	$shaylaT_minute_60_string =	 htmlspecialchars_decode(implode( '',$shaylaT_minute_60_array ));       	
       	$shaylaT_total_sessions_string  = htmlspecialchars_decode(implode( '',$shaylaT_total_sessions_array ));
       	$shaylaT_total_hours_string  = htmlspecialchars_decode(implode( '',$shaylaT_total_hours_array ));     	
       	$shaylaT_total_session_breakdown_string  = htmlspecialchars_decode(implode( '',$shaylaT_total_session_breakdown_array ));     	

        //	Liam Sessions
        $html.= '<tr><th scope="col">Shayla</th>'.implode('',$header).'<th scope="col">TOTAL SESSIONS</th><th scope="col">TOTAL HRS</th></tr>';

        $html.= '</thead><tbody>';
        $html.= '<tr><td scope="row" data-label="Liam">30 Minute Sessions</td>'.$shaylaT_minute_30_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="Liam">45 Minute Sessions</td>'.$shaylaT_minute_45_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="Liam">60 Minute Sessions</td>'.$shaylaT_minute_60_string.'</tr>';

        $html.= '<tr class="total-session-clr"><td scope="row" data-label="Liam">Total Sessions</td>'.$shaylaT_total_sessions_string.'</tr>';

        $html.= '<tr><td scope="row" data-label="Liam">Total Hours</td>'.$shaylaT_total_hours_string.'</tr>';
        $html.= '</tbody></table><br/>';
        
        $html.= '<table class="table-two"><caption class="pd-t-b-20"><h2>Trainer/Client Break Down </h2></caption><thead>';
        $html.= '<tr><th scope="col">Jon Okulicz</th>'.implode('',$header).'<th scope="col">TOTAL SESSIONS</th><th scope="col">TOTAL HRS</th></tr></thead>';
        
        $html.= '<tbody class="ttbody-main"><tr><td scope="row" data-label="Jon Okulicz">30 Minute Sessions</td>'.implode('',$jonT_30_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';

        $html.= '<tr><td scope="row" data-label="Jon Okulicz">Total</td>'.$jonT_minute_30_string.'</tr>';

        $html.= '</tbody><tbody class="ttbody-main">';
        $html.= '<tr><td scope="row" data-label="Jon Okulicz">45 Minute Sessions</td>'.implode('',$jonT_45_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';
        
		$html.= '<tr><td scope="row" data-label="Jon Okulicz">Total</td>'.$jonT_minute_45_string.'</tr>';

        $html.= '</tbody><tbody class="ttbody-main">'; 
        
        $html.='<tr><td scope="row" data-label="Jon Okulicz">60 Minute Sessions</td>'.implode('',$jonT_60_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';

        $html.= '<tr><td scope="row" data-label="Jon Okulicz">Total</td>'.$jonT_minute_60_string.'</tr>';

        $html.= '<tr><td scope="row" data-label="Jon Okulicz">Total Sessions</td>'.$jonT_total_session_breakdown_string.'</tr>';
        
        $html.= '</tbody><thead>';
        $html.= '<tr><th scope="col">Liam Donovan</th>'.implode('',$header).'<th scope="col">TOTAL SESSIONS</th><th scope="col">TOTAL HRS</th></tr></thead><tbody class="ttbody-main">';
        $html.= '<tr><td scope="row" data-label="Liam Donovan">30 Minute Sessions</td>'.implode('',$liamT_30_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';
        
        $html.= '<tr><td scope="row" data-label="Liam Donovan">Total</td>'.$liamT_minute_30_string.'</tr>';

        $html.= '</tbody><tbody class="ttbody-main">';
        $html.= '<tr><td scope="row" data-label="Liam Donovan">45 Minute Sessions</td>'.implode('',$liamT_45_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';
        
        $html.= '<tr><td scope="row" data-label="Liam Donovan">Total</td>'.$liamT_minute_45_string.'</tr>';
        $html.= '</tbody><tbody class="ttbody-main">';
        
        $html.= '<tr><td scope="row" data-label="Liam Donovan">60 Minute Sessions</td>'.implode('',$liamT_60_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';

        $html.= '<tr><td scope="row" data-label="Liam Donovan">Total</td>'.$liamT_minute_60_string.'</tr>';
        
        $html.= '<tr><td scope="row" data-label="Liam Donovan">Total Sessions</td>'.$liamT_total_session_breakdown_string.'</tr>';
        
        $html.= '</tbody><thead>';

       	$html.='<tr><th scope="col">Shayla Fichera</th>'.implode('',$header).'<th scope="col">TOTAL SESSIONS</th><th scope="col">TOTAL HRS</th></tr>';
       	
       	$html.= '</thead><tbody class="ttbody-main">';

       	$html.= '<tr><td scope="row" data-label="Shayla Fichera">30 Minute Sessions</td>'.implode('',$shaylaT_30_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';
       	
       	
       	$html.= '<tr><td scope="row" data-label="Shayla Fichera">Total</td>'.$shaylaT_minute_30_string.'</tr>';

       	$html.= '</tbody><tbody class="ttbody-main">';
       	$html.= '<tr><td scope="row" data-label="Shayla Fichera">45 Minute Sessions</td>'.implode('',$shaylaT_45_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';

        $html.= '<tr><td scope="row" data-label="Shayla Fichera">Total</td>'.$shaylaT_minute_45_string.'</tr>';
        $html.= '</tbody><tbody class="ttbody-main">';

        $html.= '<tr><td scope="row" data-label="Shayla Fichera">60 Minute Sessions</td>'.implode('',$shaylaT_60_users_array).'<td data-label="TOTAL SESSIONS">&nbsp;</td><td data-label="TOTAL HRS">&nbsp;</td></tr>';

        $html.= '<tr><td scope="row" data-label="Shayla Fichera">Total</td>'.$shaylaT_minute_60_string.'</tr>';
        $html.= '<tr><td scope="row" data-label="Shayla Fichera">Total Sessions</td>'.$shaylaT_total_session_breakdown_string.'</tr>';        
        $html.= '</tbody></table>';
		return $html;
	}

	public function get_trainer_record_detail( $start_date,$sorted_users ){	
		global $wpdb;			
		$response  =  array();
		$minute_30_counter 	=	0;
		$minute_45_counter 	=	0;
		$minute_60_counter 	=	0;
		$jonT_30_counter 	=	0;
		$jonT_45_counter 	=	0;
		$jonT_60_counter 	=	0;
		$liamT_30_counter 	=	0;
		$liamT_45_counter 	=	0;
		$liamT_60_counter 	=	0;

		$shaylaT_30_counter 	=	0;
		$shaylaT_45_counter 	=	0;
		$shaylaT_60_counter 	=	0;
		$minute_30_index		=	'';
		$minute_45_index		=	'';
		$minute_60_index		=	'';
		
		$jon_30_index		=	'';
		$jon_45_index		=	'';
		$jon_60_index		=	'';
		
		$liam_30_index		=	'';
		$liam_45_index		=	'';
		$liam_60_index		=	'';
		
		$shayla_30_index	=	'';
		$shayla_45_index	=	'';
		$shayla_60_index	=	'';
						
		$postMetaTable	   =  $wpdb->prefix.'postmeta';
		$attendace_meta_key = strtolower('attendance_'.date('j_MY',strtotime($start_date)));
		$attendanceRecord 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like '".$attendace_meta_key."' order by `meta_id` DESC" );		
		//		echo '<pre>'; print_r( $attendanceRecord ); exit; 

		$resulted_users 	=	array();		
		$jon_users_30		=	array();
		$jon_users_45		=	array();
		$jon_users_60		=	array();
		$liam_users_30		=	array();
		$liam_users_45		=	array();
		$liam_users_60		=	array();
		$shayla_users_30	=	array();
		$shayla_users_45	=	array();
		$shayla_users_60	=	array();

		foreach( $attendanceRecord as $attendance ){																	
			$users_array 	=	json_decode( $attendance->meta_value );			

			$post_id  =	$attendance->post_id;	
			$session_name 	=	get_the_title( $post_id );
			$term_obj_list = get_the_terms( $post_id, 'mrd_classes_cats' );												
			//	echo '<pre>'; print_r( $users_array ); exit; 		
			$attendace_post_categories = array();

			foreach( $term_obj_list as $taxonomy ){
				$attendace_post_categories[] 	=	$taxonomy->term_id;
			}

			//	Create user array keys	
			$user_array_keys  = array_fill_keys($users_array, 1);
			$resulted_users   =  array_intersect_key($sorted_users, $user_array_keys);						

			//	echo '<pre>';
			//	Get Minute Jon record
			$jonT_key 	=	'jonT_'.date('j_MY',strtotime($start_date));									
			$jonT_record 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like '".$jonT_key."' and post_id='".$post_id."'  order by `meta_id` DESC" );			

			$jonT_value =		json_decode( $jonT_record[0]->meta_value );			

			//	Get Minute Liam record
			$liamT_key 		=	'liamT_'.date('j_MY',strtotime($start_date));			
			$liamT_record 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like '".$liamT_key."' and post_id='".$post_id."' order by `meta_id` DESC" );
			$liamT_value =		json_decode( $liamT_record[0]->meta_value );

			//	Get Minute Shyala record
			$shaylaT_key 		=	'shaylaT_'.date('j_MY',strtotime($start_date));			
			$shaylaT_record 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like '".$shaylaT_key."' and post_id='".$post_id."' order by `meta_id` DESC" );			

			$shaylaT_value 	=	json_decode( $shaylaT_record[0]->meta_value );				
			//echo '<pre>'; print_r( $jonT_value ); exit; 
			//	Get Minute 30 record
			$minute_30_key 	=	strtolower('minute30_'.date('j_MY',strtotime($start_date)));			
			$minute_30_record 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like '".$minute_30_key."' and post_id='".$post_id."' order by `meta_id` DESC" );			
			$minute_30_value =		json_decode( $minute_30_record[0]->meta_value );

			if( is_array( $minute_30_value ) ){
				$minute_30_index  = count(array_keys($minute_30_value, 1));
				
				if( $minute_30_index > 0 ){
					$minute_30_counter	=	$minute_30_counter+$minute_30_index;						
					//	Set Jon 30 Minute Value
					$jon_30_array = array_flip(array_keys($jonT_value, 1));
					$jon_30_intersection_keys  =  array_intersect_key($minute_30_value,$jon_30_array);
					$jon_30_valid_keys = array_keys($jon_30_intersection_keys, 1);
					$jon_30_index = count($jon_30_valid_keys);

					if( $jon_30_index > 0 ){
						$jonT_30_counter	=	$jonT_30_counter+$jon_30_index;						

						foreach( $jon_30_valid_keys as $array_30_value){
							$jon_users_30[]  =	 $sorted_users[$users_array[ $array_30_value]];
						}

						$jon_users_30 		=	array_unique( $jon_users_30 );
						// array_unshift($jon_users_30,$session_name);												
					}

					//	Set Liam 30 Minute Value						
					$liam_30_array = array_flip(array_keys($liamT_value, 1));
					$liam_30_intersection_keys  =  array_intersect_key($minute_30_value,$liam_30_array);
					$liam_30_valid_keys = array_keys($liam_30_intersection_keys, 1);
					$liam_30_index = count( $liam_30_valid_keys );				

					if( $liam_30_index > 0 ){

						$liamT_30_counter	=	$liamT_30_counter+$liam_30_index;	
						
						foreach( $liam_30_valid_keys as $array_30_value){
							$liam_users_30[]  =	 $sorted_users[$users_array[ $array_30_value]];
						}

						$liam_users_30 		=	array_unique( $liam_users_30 );
						// array_unshift($liam_users_30,$session_name);
					}


					//	Set Shayla 30 Minute Value		
					$shaylaT_30_array = array_flip(array_keys($shaylaT_value, 1));
					$shayla_30_intersection_keys  =  array_intersect_key($minute_30_value,$shaylaT_30_array);
					$shayla_30_valid_keys = array_keys($shayla_30_intersection_keys, 1);
					$shaylaT_30_index = count( $shayla_30_valid_keys );								

					if(  $shaylaT_30_index > 0 ){						
						$shaylaT_30_counter	=	$shaylaT_30_counter+$shaylaT_30_index;

						foreach( $shayla_30_valid_keys as $array_30_value){
							$shayla_users_30[]  =	 $sorted_users[$users_array[ $array_30_value]];
						}

						$shayla_users_30 		=	array_unique( $shayla_users_30 );
						// array_unshift($shayla_users_30,$session_name);
					}
				}				
			}else{
				if( $minute_30_value == 1 ){
					$minute_30_counter++;

					if( $jonT_value == 1 ){
						$jonT_30_counter++;
						array_unshift($jon_users_30,$session_name);
					}

					if( $liamT_value == 1 ){
						$liamT_30_counter++;						
						array_unshift($liam_users_30,$session_name);
					}

					if( $shaylaT_value == 1 ){
						$shaylaT_30_counter++;						
						array_unshift($shayla_users_30,$session_name);
					}
				}
			}
			
			//	Get Minute 45 record
			$minute_45_key 	=	strtolower('minute45_'.date('j_MY',strtotime($start_date)));			
			$minute_45_record 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like '".$minute_45_key."' and post_id='".$post_id."' order by `meta_id` DESC" );

			$minute_45_value =		json_decode( $minute_45_record[0]->meta_value );
			
			//print_r( $minute_45_record );
			if( is_array( $minute_45_value ) ){
				$minute_45_index  = count(array_keys($minute_45_value, 1));

				if( $minute_45_index > 0){
					$minute_45_counter++;

					//	Set Jon 45 Minute Value					
					$jon_45_array = array_flip(array_keys($jonT_value, 1));
					$jon_45_intersection_keys  =  array_intersect_key($minute_45_value,$jon_45_array);
					$jon_45_valid_keys = array_keys($jon_45_intersection_keys, 1);
					$jon_45_index = count( $jon_45_valid_keys );	

					if( $jon_45_index > 0 ){
						$jonT_45_counter	=	$jonT_45_counter+$jon_45_index;						

						foreach( $jon_45_valid_keys as $array_45_value){
							$jon_users_45[]  =	 $sorted_users[$users_array[ $array_45_value]];
						}

						$jon_users_45  =  array_unique( $jon_users_45 );
						// array_unshift($jon_users_45,$session_name);												
					}

					//	Set Liam 45 Minute Value
					$liam_45_array = array_flip(array_keys($liamT_value, 1));
					$liam_45_intersection_keys  =  array_intersect_key($minute_45_value,$liam_45_array);
					$liam_45_valid_keys = array_keys($liam_45_intersection_keys, 1);
					$liam_45_index = count( $liam_45_valid_keys );


					if( $liam_45_index > 0 ){						
						$liamT_45_counter	=	$liamT_45_counter+$liam_45_index;	

						foreach( $liam_45_valid_keys as $array_45_value){
							$liam_users_45[]  =	 $sorted_users[$users_array[ $array_45_value]];
						}
						
						$liam_users_45 		=	array_unique( $liam_users_45 );
						// array_unshift($liam_users_45,$session_name);
					}

					//	Set Shayla 45 Minute Value
					$shaylaT_45_array = array_flip(array_keys($shaylaT_value, 1));
					$shaylaT_45_intersection_keys  =  array_intersect_key($minute_45_value,$shaylaT_45_array);
					$shaylaT_45_valid_keys = array_keys($shaylaT_45_intersection_keys, 1);
					$shaylaT_45_index = count( $shaylaT_45_valid_keys );

					if( $shaylaT_45_index > 0 ){
						$shaylaT_45_counter	=	$shaylaT_45_counter+$shaylaT_45_index;	

						foreach( $shaylaT_45_valid_keys as $array_45_value){
							$shayla_users_45[]  =	 $sorted_users[$users_array[ $array_45_value]];
						}
						
						$shayla_users_45 		=	array_unique( $shayla_users_45 );
						// array_unshift($shayla_users_45,$session_name);
					}
				}
			}else{
				if( $minute_45_value == 1 ){
					$minute_45_counter++;
					
					if( $jonT_value ==  1){
						$jonT_45_counter++;
						array_unshift($jon_users_45,$session_name);
					}

					if( $liamT_value == 1 ){
						$liamT_45_counter++;						
						array_unshift($liam_users_45,$session_name);
					}

					if( $shaylaT_value == 1 ){
						$shaylaT_45_counter++;						
						array_unshift($shayla_users_45,$session_name);
					}
				}
			}

			//	Get Minute 60 record
			$minute_60_key 	=	strtolower('minute60_'.date('j_MY',strtotime($start_date)));			
			$minute_60_record 	=	$wpdb->get_results( "SELECT * FROM ".$postMetaTable." WHERE `meta_key` like '".$minute_60_key."' and post_id='".$post_id."' order by `meta_id` DESC" );

			$minute_60_value =		json_decode( $minute_60_record[0]->meta_value );

			if( is_array( $minute_60_value ) ){
				$minute_60_index  = count(array_keys($minute_60_value, 1));

				if( $minute_60_index > 0 ){
					$minute_60_counter++;

					//	Set Jon 60 Minute Value
					$jon_60_array = array_flip(array_keys($jonT_value, 1));
					$jon_60_intersection_keys  =  array_intersect_key($minute_60_value,$jon_60_array);
					$jon_60_valid_keys = array_keys($jon_60_intersection_keys, 1);
					$jon_60_index = count($jon_60_valid_keys);
					
					if( $jon_60_index > 0 ){
						$jonT_60_counter	=	$jonT_60_counter+$jon_60_index;						

						foreach( $jon_60_valid_keys as $array_45_value){
							$jon_users_60[]  =	 $sorted_users[$users_array[ $array_45_value]];
						}
						
						$jon_users_60 		=	array_unique( $jon_users_60 );
						//array_unshift($jon_users_60,$session_name);												
					}

					//	Set Liam 60 Minute Value
					$liam_60_array = array_flip(array_keys($liamT_value, 1));
					$liam_60_intersection_keys  =  array_intersect_key($minute_60_value,$liam_60_array);
					$liam_60_valid_keys = array_keys($liam_60_intersection_keys, 1);
					$liam_60_index = count( $liam_60_valid_keys );

					if( $liam_60_index > 0 ){
						$liamT_60_counter	=	$liamT_60_counter+$liam_60_index;						

						foreach( $liam_60_valid_keys as $array_45_value){
							$liam_users_60[]  =	 $sorted_users[$users_array[ $array_45_value]];
						}
						
						$liam_users_60 		=	array_unique( $liam_users_60 );
						// array_unshift($liam_users_60,$session_name);
					}

					//	Set Shayla 60 Minute Value
					$shaylaT_60_array = array_flip(array_keys($shaylaT_value, 1));
					$shaylaT_60_intersection_keys  =  array_intersect_key($minute_60_value,$shaylaT_60_array);
					$shaylaT_60_valid_keys = array_keys($shaylaT_60_intersection_keys, 1);
					$shaylaT_60_index = count( $shaylaT_60_valid_keys );

					if( $shaylaT_60_index > 0 ){
						$shaylaT_60_counter	=	$shaylaT_60_counter+$shaylaT_60_index;						
						
						foreach( $shaylaT_60_valid_keys as $array_45_value){
							$shayla_users_60[]  =	 $sorted_users[$users_array[ $array_45_value]];
						}
						
						$shayla_users_60 		=	array_unique( $shayla_users_60 );
						// array_unshift($shayla_users_60,$session_name);
					}
				}
			}else{
				if( $minute_60_value == 1 ){
					$minute_60_counter++;

					if( $jonT_value == 1 ){
						$jonT_60_counter++;
						array_unshift($jon_users_60,$session_name);
					}

					if( $liamT_value == 1 ){
						$liamT_60_counter++;						
						array_unshift($liam_users_60,$session_name);
					}

					if( $shaylaT_value == 1 ){
						$shaylaT_60_counter++;						
						array_unshift($shayla_users_60,$session_name);
					}
				}
			}
		}
		/*
		echo '<pre>';
		print_r( $liam_users_30 );
		print_r( $jon_users_30 );
		print_r( $jon_users_45 );
		print_r( $jon_users_60 );
		print_r( $liam_users_30 );
		print_r( $liam_users_45 );
		print_r( $liam_users_60 );
		print_r( $shayla_users_30 );
		print_r( $shayla_users_45 );
		print_r( $shayla_users_60 );
		exit; 
		*/

		$col_date =		date('D d/m/Y', strtotime($start_date));

		//	All Trainer Section
		$total_30_min_sessions 	=   $jonT_30_counter+$liamT_30_counter+$shaylaT_30_counter;
		$total_45_min_sessions 	=   $jonT_45_counter+$liamT_45_counter+$shaylaT_45_counter;
		$total_60_min_sessions 	=   $jonT_60_counter+$liamT_60_counter+$shaylaT_60_counter;

		$response[ 'all_minute30' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$total_30_min_sessions.'</td>');	
		$response[ 'all_minute45' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$total_45_min_sessions.'</td>');	
		$response[ 'all_minute60' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$total_60_min_sessions.'</td>');	
		$response[ 'all_total_30_sessions' ] =	$total_30_min_sessions;	
		$response[ 'all_total_45_sessions' ] =	$total_45_min_sessions;	
		$response[ 'all_total_60_sessions' ] =	$total_60_min_sessions;	
		$total_sessions 	=   $total_30_min_sessions+$total_45_min_sessions+$total_60_min_sessions;
		$response[ 'all_total_sessions' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$total_sessions.'</td>');
		$response[ 'all_total_sessions_value' ] =	$total_sessions;	

		$response[ 'all_total_hours' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.(float)(($total_30_min_sessions/2)+3*($total_45_min_sessions)/4+$total_60_min_sessions).'</td>');		

		$response[ 'all_total_hours_value' ] =	(float)(($total_30_min_sessions/2)+3*($total_45_min_sessions)/4+$total_60_min_sessions);	


		//	Jon Individual Section 
		$response[ 'jonT_minute30' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$jonT_30_counter.'</td>');	
		$response[ 'jonT_minute45' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$jonT_45_counter.'</td>');	
		$response[ 'jonT_minute60' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$jonT_60_counter.'</td>');	
		$response[ 'jonT_total_30_sessions' ] =	$jonT_30_counter;	
		$response[ 'jonT_total_45_sessions' ] =	$jonT_45_counter;	
		$response[ 'jonT_total_60_sessions' ] =	$jonT_60_counter;	
		$total_jon_sessions 	=   $jonT_30_counter+$jonT_45_counter+$jonT_60_counter;
		$response[ 'jonT_total_sessions' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$total_jon_sessions.'</td>');
		$response[ 'jonT_total_sessions_value' ] =	$total_jon_sessions;			
		$response[ 'jonT_total_hours' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.(float)(($jonT_30_counter/2)+3*($jonT_45_counter)/4+$jonT_60_counter).'</td>');		
		$response[ 'jonT_total_hours_value' ] =	(float)(($jonT_30_counter/2)+3*($jonT_45_counter)/4+$jonT_60_counter);	
		
		$response[ 'jonT_users_30' ] =	implode( '<br/>',$jon_users_30 );	
		$response[ 'jonT_users_45' ] =	implode( '<br/>',$jon_users_45 );		
		$response[ 'jonT_users_60' ] =	implode( '<br/>',$jon_users_60 );

		$response[ 'liamT_users_30' ] =	implode( '<br/>',$liam_users_30 );	
		$response[ 'liamT_users_45' ] =	implode( '<br/>',$liam_users_45 );		
		$response[ 'liamT_users_60' ] =	implode( '<br/>',$liam_users_60 );

		$response[ 'shaylaT_users_30' ] =	implode( '<br/>',$shayla_users_30 );	
		$response[ 'shaylaT_users_45' ] =	implode( '<br/>',$shayla_users_45 );		
		$response[ 'shaylaT_users_60' ] =	implode( '<br/>',$shayla_users_60 );		

		// Liam Individual Section 
		$response[ 'liamT_minute30' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$liamT_30_counter.'</td>');	
		$response[ 'liamT_minute45' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$liamT_45_counter.'</td>');	
		$response[ 'liamT_minute60' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$liamT_60_counter.'</td>');	
		$response[ 'liamT_total_30_sessions' ] =	$liamT_30_counter;	
		$response[ 'liamT_total_45_sessions' ] =	$liamT_45_counter;	
		$response[ 'liamT_total_60_sessions' ] =	$liamT_60_counter;	
		$total_lamT_sessions 	=   $liamT_30_counter+$liamT_45_counter+$liamT_60_counter;
		$response[ 'liamT_total_sessions' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$total_lamT_sessions.'</td>');
		$response[ 'liamT_total_sessions_value' ] =	$total_lamT_sessions;			
		$response[ 'liamT_total_hours' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.(float)(($liamT_30_counter/2)+3*($liamT_45_counter)/4+$liamT_60_counter).'</td>');				
		$response[ 'liamT_total_hours_value' ] =	(float)(($liamT_30_counter/2)+3*($liamT_45_counter)/4+$liamT_60_counter);	

		//	Shayla Individual Section 
		$response[ 'shaylaT_minute30' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$shaylaT_30_counter.'</td>');	
		$response[ 'shaylaT_minute45' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$shaylaT_45_counter.'</td>');	
		$response[ 'shaylaT_minute60' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$shaylaT_60_counter.'</td>');	
		$response[ 'shaylaT_total_30_sessions' ] =	$shaylaT_30_counter;	
		$response[ 'shaylaT_total_45_sessions' ] =	$shaylaT_45_counter;	
		$response[ 'shaylaT_total_60_sessions' ] =	$shaylaT_60_counter;	
		$total_shaylaT_sessions 	=   $shaylaT_30_counter+$shaylaT_45_counter+$shaylaT_60_counter;
		$response[ 'shaylaT_total_sessions' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.$total_shaylaT_sessions.'</td>');
		$response[ 'shaylaT_total_sessions_value' ] =	$total_shaylaT_sessions;			
		$response[ 'shaylaT_total_hours' ] =	htmlspecialchars('<td data-label="'.$col_date.'">'.(float)(($shaylaT_30_counter/2)+3*($shaylaT_45_counter)/4+$shaylaT_60_counter).'</td>');		
		$response[ 'shaylaT_total_hours_value' ] =	(float)(($shaylaT_30_counter/2)+3*($shaylaT_45_counter)/4+$shaylaT_60_counter);	
		return json_encode( $response );		
	}

	public function get_value_count_in_array( $value, $sample_array ){
		$counts = array_count_values($sample_array);
    	return $counts[$value];
	}
}

$TRAINER_REPORT	=	new Epic_trainer_report();