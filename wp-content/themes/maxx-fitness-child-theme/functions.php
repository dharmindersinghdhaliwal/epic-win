<?php
/**
 *
 * Maxx Fitness child theme functions and definitions
 *
 * @package Maxx Fitness
 * @author  Torbara Team <support@torbara.com>
 *
 * @link https://themeforest.net/user/torbara/portfolio?ref=torbara
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 */

if ( defined('TT_WARP_PLUGIN_URL') ) {
  function maxxfitness_child_scripts() {
      wp_enqueue_style( 'maxxfitness-child-style',  get_stylesheet_directory_uri(). '/style.css',  array(), null );
  }
  add_action( 'wp_enqueue_scripts', 'maxxfitness_child_scripts' );
}
	## Create Shortcode of Widget
	## By this Way We user Shortcode [widget widget_name="DPA_(Widget name here)"]
function widget($atts) {
	global $wp_widget_factory;
  $id='';
  $instance= '';
	extract(shortcode_atts(array(
								'widget_name' => FALSE
								), $atts));
    $widget_name = wp_specialchars($widget_name);
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
		if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
		return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the PHP class name is correct"),'<strong>'.$class.'</strong>').'</p>';
		else:$class = $wp_class;
	endif;
	endif;
    ob_start();
    the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
	## Create Shortcode of Widget Ends Here
add_shortcode('widget','widget');

	##Custom Widgets Starts
	include('includes/class-rmc-widget.php');
	include('includes/class-bmr-widget.php');
	##Custom Widgets Ends

  #iNCLUDES
  include('includes/pagination.php');

### Custom Gender Field in Admin (user profile)
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>
	<h3>Extra profile information</h3>
	<table class="form-table">
		<tr>
			<th><label for="gender">Gender</label></th>
			<td>
            <select name="gender" id="gender" class="regular-text">
                <option value=""> Select Gender</option>
                <?php $gender	=	 esc_attr( get_the_author_meta( 'gender', $user->ID ) );?>
                <option value="Male"<?php echo $gender == 'Male'?'selected':''?>>Male</option>
                <option value="Female"<?php echo $gender == 'Female'?'selected':''?>>Female</option>
            </select><br />
				<span class="description">Please enter your Gender <?php _e('(required)'); ?></span>
			</td>
		</tr>
	</table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
function my_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_usermeta( $user_id, 'gender', $_POST['gender'] );
}

##if user not login
function epic_user_not_login(){
	if( !is_user_logged_in()){
		$url	=	get_site_url().'/login';
		if((is_page('login'))||(is_page('reset_password'))){}
		else{
			wp_redirect( $url );
		}
	}
}
add_action('wp_head','epic_user_not_login');

function add_font_family(){ ?>
	<!--<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">-->
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/styles/Oswald.css" rel="stylesheet"><?php

 }
 add_action('wp_head','add_font_family');

/***********	 INCLUDES	***********/
include('includes/client-notes.php');
include('includes/my-challenge-hit-list.php');
include('includes/weekly-challenges.php');
include('includes/spotlight-challenges.php');

/* Add Admin Url in Head to use  in Js file*/
function ajax_url_for_js(){
  ?>
<script> wp_ajax_url = function(){ return '<?php echo admin_url('admin-ajax.php'); ?>'; }</script>
	 <!--Add Audio to paly for super fly menu-->
	 <audio class="sfm-audio">
		<source src="https://www.dropbox.com/s/kjbsws8kml6h40n/sfmenu.ogg?dl=1" type="audio/ogg">
		<source src="https://www.dropbox.com/s/sjn41uqkj1yofb0/sfmenu.mp3?dl=1" type="audio/mpeg">
	 </audio>
	 <audio class="sfm-menu-item">
		<source src="https://www.dropbox.com/s/kjbsws8kml6h40n/sfmenu.ogg?dl=1" type="audio/ogg">
		<source src="https://www.dropbox.com/s/sjn41uqkj1yofb0/sfmenu.mp3?dl=1" type="audio/mpeg">
	 </audio>
	 <script type="text/javascript">

// Add a script element as a child of the body
function downloadJSAtOnload() {
  var element = document.createElement("script");
  element.src = "deferredfunctions.js";
  document.body.appendChild(element);
}

// Check for browser support of event handling capability
if (window.addEventListener)
  window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
  window.attachEvent("onload", downloadJSAtOnload);
else window.onload = downloadJSAtOnload;

</script>
	<?php
	if(is_page('your-menu')){
		?>
		<script src="https://code.jquery.com/jquery-1.9.0.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-1.2.1.js"></script>
		<?php
	}
}
add_action('wp_head','ajax_url_for_js');

// Ignore depcreciated constructor notices
add_filter('deprecated_constructor_trigger_error', '__return_false');
