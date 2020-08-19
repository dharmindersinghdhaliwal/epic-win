<?php
/**
 * Options and Actions used by Beans Compiler.
 *
 * @ignore
 *
 * @package API\Compiler
 */
final class maxxfitness_tt_Compiler_Options {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'admin_init', array( $this, 'flush' ) , -1 );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'maxxfitness_field_flush_cache', array( $this, 'option' ) );
		add_action( 'maxxfitness_field_descriptionmaxxfitness_tt_compile_all_styles_append_markup', array( $this, 'maybe_disable_style_notice' ) );
		add_action( 'maxxfitness_field_descriptionmaxxfitness_tt_compile_all_scripts_group_append_markup', array( $this, 'maybe_disable_scripts_notice' ) );

	}


	/**
	 * Register options.
	 */
	public function register() {

		$fields = array(
			array(
				'id' => 'maxxfitness_compiler_items',
				'type' => 'flush_cache',
				'description' => esc_html__( 'Clear CSS and Javascript cached files. New cached versions will be compiled on page load.', 'maxx-fitness' )
			)
		);

		// Add styles compiler option only if supported
		if ( maxxfitness_get_component_support( 'wp_styles_compiler' ) )
			$fields = array_merge( $fields, array(
				array(
					'id' => 'maxxfitness_compile_all_styles',
					'label' => false,
					'checkbox_label' => esc_html__( 'Compile all WordPress styles', 'maxx-fitness' ),
					'type' => 'checkbox',
					'default' => false,
					'description' => esc_html__( 'Compile and cache all the CSS files that have been enqueued to the WordPress head.', 'maxx-fitness' )
				)
			) );

		// Add scripts compiler option only if supported
		if ( maxxfitness_get_component_support( 'wp_scripts_compiler' ) )
			$fields = array_merge( $fields, array(
				array(
					'id' => 'maxxfitness_compile_all_scripts_group',
					'label' => esc_html__( 'Compile all WordPress scripts', 'maxx-fitness' ),
					'type' => 'group',
					'fields' => array(
						array(
							'id' => 'maxxfitness_compile_all_scripts',
							'type' => 'activation',
							'default' => false
						),
						array(
							'id' => 'maxxfitness_compile_all_scripts_mode',
							'type' => 'select',
							'default' => array( 'aggressive' ),
							'attributes' => array( 'style' => 'margin: -3px 0 0 -8px;' ),
							'options' => array(
								'aggressive' => esc_html__( 'Aggressive', 'maxx-fitness' ),
								'standard' => esc_html__( 'Standard', 'maxx-fitness' )
							)
						),
					),
					'description' => esc_html__( 'Compile and cache all the Javascript files that have been enqueued to the WordPress head.<!--more-->JavaSript is outputted in the footer if the level is set to <strong>Aggressive</strong> and might conflict with some third party plugins which are not following WordPress standards.', 'maxx-fitness' )
				)
			) );

		maxxfitness_register_options( $fields, 'maxxfitness_settings', 'compiler_options', array(
			'title' => esc_html__( 'Compiler options', 'maxx-fitness' ),
			'context' => 'normal'
		) );

	}


	/**
	 * Flush images for all folders set.
	 */
	public function flush() {

		if ( !maxxfitness_post( 'maxxfitness_flush_compiler_cache' ) )
			return;

		maxxfitness_remove_dir( maxxfitness_get_compiler_dir() );

	}


	/**
	 * Cache cleaner notice.
	 */
	public function admin_notice() {

		if ( !maxxfitness_post( 'maxxfitness_flush_compiler_cache' ) )
			return;

		echo '<div id="message" class="updated"><p>' . esc_html__( 'Cache flushed successfully!', 'maxx-fitness' ) . '</p></div>' . "\n";

	}


	/**
	 * Add button used to flush cache.
	 */
	public function option( $field ) {

		if ( $field['id'] !== 'maxxfitness_compiler_items' )
			return;

		echo '<input type="submit" name="maxxfitness_flush_compiler_cache" value="' . esc_html__( 'Flush assets cache', 'maxx-fitness' ) . '" class="button-secondary" />';

	}


	/**
	 * Maybe show disabled notice.
	 */
	public function maybe_disable_style_notice() {

		if ( get_option( 'maxxfitness_compile_all_styles' ) && maxxfitness_tt_is_compiler_dev_mode() )
			echo '<br /><span>' . esc_html__( 'Styles are not compiled in development mode.', 'maxx-fitness' ) . '</span>';

	}

	/**
	 * Maybe show disabled notice.
	 */
	public function maybe_disable_scripts_notice() {

		if ( get_option( 'maxxfitness_compile_all_scripts' ) && maxxfitness_tt_is_compiler_dev_mode() )
			echo '<br /><span>' . esc_html__( 'Scripts are not compiled in development mode.', 'maxx-fitness' ) . '</span>';

	}

}

new maxxfitness_tt_Compiler_Options();
