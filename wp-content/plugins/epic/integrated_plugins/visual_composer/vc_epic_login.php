<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_epic_Login extends VCExtend_epic{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'epic_login_vc', array( $this, 'renderLogin' ) );

    }
 
    public function integrateWithVC() {
        parent::integrateWithVC();
        
        
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("epic Login", 'epic'),
            "description" => __("Login form for epic", 'epic'),
            "base" => "epic_login_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/epic-vc.png', __FILE__), 
            "category" => __('epic', 'epic'),
            "params" => array(
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Use In Sidebar", 'epic'),
                  "param_name" => "use_in_sidebar",
                  "value" => array( __("No", 'epic') => 'no'  , __("Yes", 'epic') => 'yes'),
                  "std" => 'no',
                  "description" => __("This will change the CSS styling to better fit inside a small width sidebar.", 'epic')
                ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Redirect URL", 'epic'),
                  "param_name" => "redirect_to",
                  "value" => '', 
                  "description" => __("Useres are redirected to the specified URL after logging in.", 'epic')
              ),
               array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Captcha", 'epic'),
                  "param_name" => "captcha",
                  "value" => array( __("No", 'epic')  =>  'no', 
                                   __("Yes", 'epic')  =>  'yes', 
                                   __("reCaptcha", 'epic')  => 'recaptcha', 
                                   __("FunCaptcha", 'epic')  => 'funcaptcha',
                                   __("Captcha", 'epic')  => 'captcha',
                                  ),
                  "std" => 'no',
                  "description" => __("Show the Login Form with captcha, uses the captcha plugn selected in epic settings. You can specify the captcha to be used.", 'epic')
             ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Show Registration Link", 'epic'),
                  "param_name" => "register_link",
                  "value" => array( __("Yes", 'epic')  => 'yes', __("No", 'epic')  => 'no'), 
                  "std" => 'yes',
                  "description" => __("Enable/Disable registration link in login form.", 'epic')
             ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Show Forgot Password Link", 'epic'),
                  "param_name" => "forgot_link",
                  "value" => array( __("Yes", 'epic')  => 'yes', __("No", 'epic')  => 'no'),
                  "std" => 'yes',
                  "description" => __("Enable/Disable forgot password link in login form.", 'epic')
             ),
             array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Custom Register Link Text", 'epic'),
                  "param_name" => "register_text",
                  "value" => '', 
                  "description" => __("Display custom text for registration link in login form.", 'epic')
              ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Custom Forgot Password Link Text", 'epic'),
                  "param_name" => "forgot_text",
                  "value" => '', 
                  "description" => __("Display custom text for forgot password in login form.", 'epic')
              ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Custom Register Page URL", 'epic'),
                  "param_name" => "custom_register_url",
                  "value" => '', 
                  "description" => __("Custom URL for the registration page.", 'epic')
              ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Custom Forgot Password URL", 'epic'),
                  "param_name" => "custom_forgot_url",
                  "value" => '', 
                  "description" => __("Custom URL for the forgot password page.", 'epic')
              ),
              
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderLogin( $atts, $content = null ) {

      extract( shortcode_atts( array(
        'use_in_sidebar'   => 'no',
        'redirect_to' => '',
        'captcha' => 'no',
        'register_link'   => 'yes',
        'forgot_link' => 'yes',
        'register_text'   => '',
        'forgot_text' => '',
        'custom_register_url'   => '',
        'custom_forgot_url' => '',
      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
     
      $params = '';
      if($use_in_sidebar == 'yes'){
          $params .= ' use_in_sidebar="yes" ';
      }
      if($redirect_to != 'yes'){
          $params .= ' redirect_to="'.$redirect_to.'" ';
      }
      if($captcha != 'no'){
          $params .= ' captcha="'.$captcha.'" ';
      }

      $params .= ' register_link="'.$register_link.'" ';
      $params .= ' forgot_link="'.$forgot_link.'" ';
      $params .= ' register_text="'.$register_text.'" ';
      $params .= ' forgot_text="'.$forgot_text.'" ';
      $params .= ' custom_register_url="'.$custom_register_url.'" ';
      $params .= ' custom_forgot_url="'.$custom_forgot_url.'" ';   

      $output = do_shortcode('[epic_login '. $params .' ]');
        //"<div style='color:{$color};' data-foo='${foo}'>{$content}</div>";
      return $output;
    }

}
// Finally initialize code
new VCExtend_epic_Login();