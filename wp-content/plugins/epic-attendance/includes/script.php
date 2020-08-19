<?php

	add_action( 'wp_enqueue_scripts','epic_attendance_add_js');
	add_action( 'wp_enqueue_scripts','epic_attendance_add_css');
	
	function epic_attendance_add_css(){
		wp_register_style('epic-attendance',plugins_url('/epic-attendance/css/epic-attendance.css'),array(),'1.1');
		wp_enqueue_style('epic-attendance');		
		wp_register_style('uicss',plugins_url('/epic-attendance/css/jquery-ui.css'),false,false);
		wp_enqueue_style('uicss');
		wp_register_style('rangecalendar',plugins_url('/epic-attendance/css/rangecalendar.css'),false,false);
		wp_enqueue_style('rangecalendar');
	}
	
	function epic_attendance_add_js(){
		wp_register_script('jquery-ui.min', plugins_url('/epic-attendance/js/jquery-ui.min.js'),array('jquery'),false);
		wp_enqueue_script('jquery-ui.min');
		wp_register_script('touch-punch', plugins_url('/epic-attendance/js/jquery.ui.touch-punch.min.js'),array('jquery'),false);
		wp_enqueue_script('touch-punch');
		wp_register_script('moment-with-langs','https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment-with-langs.min.js');
		wp_enqueue_script('moment-with-langs');
		wp_register_script('rangecalendar', plugins_url('/epic-attendance/js/jquery.rangecalendar.js'),array('jquery'),false);
		wp_enqueue_script('rangecalendar');
	}