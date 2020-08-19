<?php 
/**
 * Class used to create the epic log-in widget
 * @ignore
 * @package widget
 */
class epic_Login_Widget extends WP_Widget
{

	
	var $epic_args;


	function __construct() {

		$this->epic_args = array(
		'login-title' => __('epic Login','epic'),
		'display-register-link'=> __('Display Register Link','epic'),
		'custom-register-link'=> '',
		'display-forgot-password-link'=> __('Display Forgot Password Link','epic'),
		'forgot-password-link'=> '',
		'logout-link' => ''
		);

		$widget_ops = array('classname' => 'epic-sidebar epic-login-widget epic-clearfix', 'description' => __('Displays a log-in form for users','epic') );
		parent::__construct('epic_Login_Widget', __('epic Log-in','epic'), $widget_ops);
  	}
 
	function form($instance)  {
		$instance = wp_parse_args( (array) $instance, $this->epic_args ); 	
		?>

		<p>
			<?php
				printf('<label for="%s">%s </label>
							<input id="%s" name="%s" type="text" value="%s" class="widefat" />', 
					$this->get_field_id('login-title'),
					esc_html__('Title', 'epic'),
					$this->get_field_id('login-title'),
					$this->get_field_name('login-title'),
					 esc_attr($instance['login-title'])
				);
			?>
		</p>
		<p>
			<?php

				$checked_status_register =checked( '1', $instance['display-register-link'],false);

				printf('<input type="checkbox" id="%s" name="%s" %s value="1" />
							<label for="%s">%s </label>', 

					$this->get_field_id('display-register-link'),
					$this->get_field_name('display-register-link'),
					$checked_status_register,
					$this->get_field_id('display-register-link'),
					esc_html__('Display Register Link', 'epic')
				);
			?>
		</p>
		<p>
			<?php
				printf('<label for="%s">%s </label>
							<input id="%s" name="%s" type="text" value="%s" class="widefat" />', 
					$this->get_field_id('custom-register-link'),
					esc_html__('Custom Register URL', 'epic'),
					$this->get_field_id('custom-register-link'),
					$this->get_field_name('custom-register-link'),
					 esc_attr($instance['custom-register-link'])
				);
			?>
		</p>
		<p>
			<?php

				$checked_status =checked( '1', $instance['display-forgot-password-link'],false);

				printf('<input type="checkbox" id="%s" name="%s" %s value="1" />
						<label for="%s">%s </label>', 

					$this->get_field_id('display-forgot-password-link'),
					$this->get_field_name('display-forgot-password-link'),
					$checked_status,
					$this->get_field_id('display-forgot-password-link'),
					esc_html__('Display Forgot Password Link', 'epic')
				);
			?>
		</p>
		<p>
			<?php
				printf('<label for="%s">%s </label>
							<input id="%s" name="%s" type="text" value="%s" class="widefat" />', 
					$this->get_field_id('forgot-password-link'),
					esc_html__('Custom Forgot Password URL', 'epic'),
					$this->get_field_id('forgot-password-link'),
					$this->get_field_name('forgot-password-link'),
					 esc_attr($instance['forgot-password-link'])
				);
			?>
		</p>
		<p>
			<?php
				printf('<label for="%s">%s </label>
							<input id="%s" name="%s" type="text" value="%s" class="widefat" />', 
					$this->get_field_id('logout-link'),
					esc_html__('Logout URL', 'epic'),
					$this->get_field_id('logout-link'),
					$this->get_field_name('logout-link'),
					 esc_attr($instance['logout-link'])
				);
			?>
		</p>
		  <?php
	}
 

	function update($new_instance, $old_instance){
		$validated=array();
        $validated['login-title']= sanitize_text_field($new_instance['login-title']);
		$validated['display-register-link']= sanitize_text_field($new_instance['display-register-link']);
		$validated['custom-register-link'] = sanitize_text_field($new_instance['custom-register-link']);
		$validated['display-forgot-password-link']= sanitize_text_field($new_instance['display-forgot-password-link']);
		$validated['forgot-password-link']= sanitize_text_field($new_instance['forgot-password-link']);
		$validated['logout-link']= sanitize_text_field($new_instance['logout-link']);
		return $validated;
	}

 

	function widget($args, $instance){
		global $current_user,$epic;

    	echo $args['before_widget'];

    	$title = apply_filters('widget_title', $instance['login-title'] );

        if ( $title )
            echo $args['before_title'].$title.$args['after_title'];


    	$widget_settings = array(
    							'login-title' => $instance['login-title'],
    							'display-register-link' => $instance['display-register-link'],
    							'custom-register-link' => $instance['custom-register-link'],
    							'display-forgot-password-link' => $instance['display-forgot-password-link'],
    							'forgot-password-link' => $instance['forgot-password-link'],
    							'logout-link' => $instance['logout-link'],
    						);

		if (!is_user_logged_in()) {
			echo $epic->epic_sidebar_login($widget_settings);
		} else {

			echo $epic->epic_sidebar_mini_profile($widget_settings);
		}

		echo $args['after_widget'];
	}
}

/**
 * Registers widgets
 * Hooked onto widgets_init
 * @access private
*/
function epic_login_widgets_init(){
	register_widget('epic_Login_Widget');
}
add_action( 'widgets_init', 'epic_login_widgets_init');


?>