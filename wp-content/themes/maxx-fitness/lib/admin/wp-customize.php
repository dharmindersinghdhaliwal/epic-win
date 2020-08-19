<?php
/**
 * Add Beans options to the WordPress Customizer.
 *
 * @package Admin
 */

maxxfitness_add_smart_action( 'customize_preview_init', 'maxxfitness_do_enqueue_wp_customize_assets' );

/**
 * Enqueue Beans assets for the WordPress Customizer.
 *
 * @since 1.0.0
 */
function maxxfitness_do_enqueue_wp_customize_assets() {

	wp_enqueue_script( 'beans-wp-customize-preview', maxxfitness_ADMIN_JS_URL . 'wp-customize-preview.js', array( 'jquery', 'customize-preview' ), maxxfitness_VERSION, true );

}


maxxfitness_add_smart_action( 'customize_register', 'maxxfitness_do_register_wp_customize_options' );

/**
 * Add Beans options to the WordPress Customizer.
 *
 * @since 1.0.0
 */
function maxxfitness_do_register_wp_customize_options() {

	$fields = array(
		array(
			'id' => 'maxxfitness_logo_image',
			'label' => esc_html__( 'Logo Image', 'maxx-fitness' ),
			'type' => 'WP_Customize_Image_Control',
			'transport' => 'refresh'
		)
	);

	maxxfitness_register_wp_customize_options( $fields, 'title_tagline', array( 'title' => esc_html__( 'Branding', 'maxx-fitness' ) ) );

	// Get layout option without default for the count.
	$options = maxxfitness_get_layouts_for_options();

	// Only show the layout options if more than two layouts are registered.
	if ( count( $options ) > 2 ) {

		$fields = array(
			array(
				'id' => 'maxxfitness_layout',
				'label' => esc_html__( 'Default Layout', 'maxx-fitness' ),
				'type' => 'radio',
				'default' => maxxfitness_get_default_layout(),
				'options' => $options,
				'transport' => 'refresh'
			)
		);

		maxxfitness_register_wp_customize_options( $fields, 'maxxfitness_layout', array( 'title' => esc_html__( 'Default Layout', 'maxx-fitness' ), 'priority' => 1000 ) );

	}

	$fields = array(
		array(
			'id' => 'maxxfitness_viewport_width_group',
			'label' => esc_html__( 'Viewport Width', 'maxx-fitness' ),
			'type' => 'group',
			'fields' => array(
				array(
					'id' => 'maxxfitness_enable_viewport_width',
					'type' => 'activation',
					'default' => false
				),
				array(
					'id' => 'maxxfitness_viewport_width',
					'type' => 'slider',
					'default' => 1000,
					'min' => 300,
					'max' => 2500,
					'interval' => 10,
					'unit' => 'px'
				),
			)
		),
		array(
			'id' => 'maxxfitness_viewport_height_group',
			'label' => esc_html__( 'Viewport Height', 'maxx-fitness' ),
			'type' => 'group',
			'fields' => array(
				array(
					'id' => 'maxxfitness_enable_viewport_height',
					'type' => 'activation',
					'default' => false
				),
				array(
					'id' => 'maxxfitness_viewport_height',
					'type' => 'slider',
					'default' => 1000,
					'min' => 300,
					'max' => 2500,
					'interval' => 10,
					'unit' => 'px'
				),
			)
		)
	);

	maxxfitness_register_wp_customize_options( $fields, 'maxxfitness_preview', array( 'title' => esc_html__( 'Preview Tools', 'maxx-fitness' ), 'priority' => 1010 ) );

}