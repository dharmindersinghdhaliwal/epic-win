<?php
/**
 * Display social login button for specified networks
 *
 * @access public
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string $html HTML button for social login 
 */
add_shortcode( 'epic_social_login_button', 'epic_social_login_button' );
function epic_social_login_button( $atts, $content = null ){

	/* Merge the default and provided paramneters for shortcodes */
	extract(shortcode_atts(array(
		'network'	=>	'',
		'design'	=>  '',
        'epic_user_role' =>  '',
     	), $atts));
    
    $user_role_param = '';
    if($epic_user_role != ''){
        $user_role_param = '&epic_user_role='.$epic_user_role;
    }

	$link = '?epic_social_login='.$network.'&epic_social_action=login'.$user_role_param;

	$social_network_keys = array('Facebook' => 'facebook','Linkedin' => 'linkedin','Twitter' => 'twitter', 'Google' => 'google');

	/* Include styles for social icon fonts */
	wp_register_style('epic-social-icons', epic_url . 'modules/social/css/zocial/zocial.css');
    wp_enqueue_style('epic-social-icons');


	$html = '<a href="' . $link . '" class="zocial '. $design .' '. $social_network_keys[$network] .' '. $design . '">
			   '. __('Login with ','epic'). ucfirst($network) .'
			  </a>';

	return $html;
}


/**
 * Display social login buttons independetly from epic registration
 *
 * @access public
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string $html HTML button for social login 
 */
add_shortcode( 'epic_social_login_panel', 'epic_social_login_panel' );
function epic_social_login_panel( $atts, $content = null ){

	extract(shortcode_atts(array(
		'class'	=>	'',
		'design'	=>  '',
     	), $atts));

	$epic_settings = get_option('epic_options');
	$allowed_networks = isset($epic_settings['social_login_allowed_networks']) ? $epic_settings['social_login_allowed_networks'] :array();
	
	if (get_option('users_can_register') == '1') {

		$html = '<div align="center" style="margin:10px">';
		$html .= '<div align="center" class="epic-social-header"  >'. $epic_settings['social_login_display_message'] .'</div>';
		foreach ($allowed_networks as $key => $network) {
			$network = ucfirst($network);
			$html .= do_shortcode('[epic_social_login_button class="' . $class . '" network="'.$network.'" design="'.$design.'" ]');
			
		}

		$html .= '</div>';
	}

    return $html;
}
