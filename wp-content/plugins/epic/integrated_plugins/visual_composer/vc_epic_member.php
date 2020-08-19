<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_epic_Member extends VCExtend_epic{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'epic_member_vc', array( $this, 'renderMemberContent' ) );

    }
 
    public function integrateWithVC() {
        parent::integrateWithVC();
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("epic Member Content", 'epic'),
            "description" => __("Content for epic Members", 'epic'),
            "base" => "epic_member_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/epic-vc.png', __FILE__), 
            "category" => __('epic', 'epic'),
            "params" => array(
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __( "Content", "epic" ),
                    "param_name" => "content", 
                    "value" => "",
                    "description" => __( "Enter your content for members.", "epic" )
                 )
              
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderMemberContent( $atts, $content = null ) {

      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
      $output = do_shortcode('[epic_member]'. $content .'[/epic_member]');
      return $output;
    }

}
// Finally initialize code
new VCExtend_epic_Member();