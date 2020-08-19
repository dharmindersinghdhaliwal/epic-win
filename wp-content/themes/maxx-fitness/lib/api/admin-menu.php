<?php
/**
 * Beans admin page.
 *
 * @ignore
 */
final class maxxfitness_tt_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 150 );
		add_action( 'admin_init', array( $this, 'register' ), 20 );

	}


	/**
	 * Add beans menu.
	 */
	public function admin_menu() {

		add_theme_page( esc_html__( 'Settings', 'maxx-fitness' ), esc_html__( 'Settings', 'maxx-fitness' ), 'manage_options', 'maxxfitness_settings', array( $this, 'display_screen' ) );

	}


	/**
	 * Beans options page content.
	 */
	public function display_screen() {

		echo '<div class="wrap">';

			echo '<h2>' . esc_html__( 'Beans Settings', 'maxx-fitness' ) . esc_html__( 'Version ', 'maxx-fitness' ) . maxxfitness_VERSION . '</h2>';

			echo maxxfitness_options( 'maxxfitness_settings' );

		echo '</div>';

	}


	/**
	 * Register options.
	 */
	public function register() {

		global $wp_meta_boxes;

		$fields = array(
			array(
				'id' => 'maxxfitness_dev_mode',
				'checkbox_label' => esc_html__( 'Enable development mode', 'maxx-fitness' ),
				'type' => 'checkbox',
				'description' => esc_html__( 'This option should be enabled while your website is in development.', 'maxx-fitness' )
			)
		);

		maxxfitness_register_options( $fields, 'maxxfitness_settings', 'mode_options', array(
			'title' => esc_html__( 'Mode options', 'maxx-fitness' ),
			'context' => maxxfitness_get( 'maxxfitness_settings', $wp_meta_boxes ) ? 'column' : 'normal' // Check for other beans boxes.
		) );

	}

}

new maxxfitness_tt_Admin();
