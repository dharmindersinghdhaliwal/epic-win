<?php

class WPUEP_Vafpress_Shortcode {

    public function __construct()
    {
        add_action( 'after_setup_theme', array( $this, 'vafpress_shortcode_init' ), 11 );
    }

    public function vafpress_shortcode_init()
    {
        require_once( WPUltimateExercise::get()->coreDir . '/helpers/vafpress/vafpress_shortcode_whitelist.php');
        require_once( WPUltimateExercise::get()->coreDir . '/helpers/vafpress/vafpress_shortcode_options.php');

        new VP_ShortcodeGenerator(array(
            'name'           => 'wpuep_shortcode_generator',
            'template'       => $shortcode_generator,
            'modal_title'    => 'WP Ultimate Exercise ' . __( 'Shortcodes', 'wp-ultimate-exercise' ),
            'button_title'   => 'WP Ultimate Exercise',
            'types'          => WPUltimateExercise::option( 'shortcode_editor_post_types', array( 'post', 'page', 'exercise' ) ),
            'main_image'     => WPUltimateExercise::get()->coreUrl . '/img/icon_20.png',
            'sprite_image'   => WPUltimateExercise::get()->coreUrl . '/img/icon_sprite.png',
        ));
    }
}