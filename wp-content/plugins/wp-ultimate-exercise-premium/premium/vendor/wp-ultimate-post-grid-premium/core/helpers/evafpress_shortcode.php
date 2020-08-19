<?php

class WPUPG_Evafpress_Shortcode {

    public function __construct()
    {
        add_action( 'after_setup_theme', array( $this, 'vafpress_shortcode_init' ), 11 );
    }

    public function vafpress_shortcode_init()
    {
        require_once( WPUltimateEPostGrid::get()->coreDir . '/helpers/vafpress/vafpress_shortcode_whitelist.php');
        require_once( WPUltimateEPostGrid::get()->coreDir . '/helpers/vafpress/vafpress_shortcode_options.php');

        new VP_ShortcodeGenerator(array(
            'name'           => 'wpupg_eshortcode_generator',
            'template'       => $shortcode_generator,
            'modal_title'    => 'WP Ultimate EPost Egrid ' . __( 'Shortcodes', 'wp-eultimate-post-grid' ),
            'button_title'   => 'WP Ultimate EPost Egrid',
            'types'          => WPUltimateEPostGrid::option( 'shortcode_editor_post_types', array( 'post', 'page' ) ),
            'main_image'     => WPUltimateEPostGrid::get()->coreUrl . '/img/icon_20.png',
            'sprite_image'   => WPUltimateEPostGrid::get()->coreUrl . '/img/icon_sprite.png',
        ));
    }
}