<?php
/*---------------------*/
/* Add sweet alert js */
/*-------------------*/
add_action('admin_enqueue_scripts','sd_add_sweetalert_js');
function sd_add_sweetalert_js(){
	wp_register_script('sweet-alert',plugins_url('sweetalert.min.js',__FILE__));
	wp_enqueue_script('sweet-alert');
}
/*----------------------*/
/* Add sweet alert css */
/*--------------------*/
add_action('admin_enqueue_scripts','sd_add_sweetalert_css');
function sd_add_sweetalert_css(){
	wp_enqueue_style('sweet-css',plugins_url('sweetalert.css',__FILE__));
}
?>