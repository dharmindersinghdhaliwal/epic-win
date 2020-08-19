<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_epic_Registration extends VCExtend_epic{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'epic_registration_vc', array( $this, 'renderRegistration' ) );

    }
 
    public function integrateWithVC() {
        global $epic_roles;
        $roles  = $epic_roles->epic_available_user_roles_registration();
        $roles_vc = array( __('Select','epic') => '');
        foreach($roles as $k=>$v){
            $roles_vc[$v] = $k;
        }
        
        parent::integrateWithVC();
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("epic Registration", 'epic'),
            "description" => __("Registration form for epic", 'epic'),
            "base" => "epic_registration_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/epic-vc.png', __FILE__), 
            "category" => __('epic', 'epic'),
            "params" => array(
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Name for Registration Form", 'epic'),
                  "param_name" => "name",
                  "value" => '', 
                  "description" => __("Add specific name to registration form to load different fields on different registration forms. If not specified, this will add a dynamic random string as the name.", 'epic')
              ),
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
                  "heading" => __("Display Login Link", 'epic'),
                  "param_name" => "display_login",
                  "value" => array( __("Yes", 'epic')  => 'yes', __("No", 'epic')  => 'no'), 
                  "std" => 'yes',
                  "description" => __("Displays the login link on registration form for already registered users.", 'epic')
             ),
             array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("User Role", 'epic'),
                  "param_name" => "user_role",
                  "value" => $roles_vc, 
                  "std" => 'yes',
                  "description" => __("Add specific to the registration form. Once user_role attribute is added, all users registred with this form will get the defined user role instead of default user role.", 'epic')
             ),
             

              
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderRegistration( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'use_in_sidebar'   => 'no',
        'redirect_to' => '',
        'captcha' => 'no',
        'display_login'   => 'yes',
        'name' => '',
        'user_role' => '',
      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
     
      $params = '';
      if($user_role != ''){
          $params .= ' user_role="'.$user_role.'" ';
      }
      if($name != ''){
          $params .= ' name="'.$name.'" ';
      }
      if($captcha != 'no'){
          $params .= ' captcha="'.$captcha.'" ';
      }
      if($redirect_to != ''){
          $params .= ' redirect_to="'.$redirect_to.'" ';
      }
      if($use_in_sidebar == 'yes'){
          $params .= ' use_in_sidebar="'.$use_in_sidebar.'" ';
      }

      $params .= ' display_login="'.$display_login.'" ';

      $output = do_shortcode('[epic_registration '. $params .' ]');
        //"<div style='color:{$color};' data-foo='${foo}'>{$content}</div>";
      return $output;
    }

}
// Finally initialize code
new VCExtend_epic_Registration();