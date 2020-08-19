<?php
/**
 * Beans images options.
 *
 * @ignore
 *
 * @package API\Image
 */
final class maxxfitness_tt_Image_Options {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Load in priority 15 so that we can check if other beans metaboxes exists.
		add_action( 'admin_init', array( $this, 'register' ), 15 );
		add_action( 'admin_init', array( $this, 'flush' ) , -1 );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'maxxfitness_field_flush_edited_images', array( $this, 'option' ) );

	}


	/**
	 * Register options.
	 */
	public function register() {

		global $wp_meta_boxes;

		$fields = array(
			array(
				'id' => 'maxxfitness_edited_images_directories',
				'type' => 'flush_edited_images',
				'description' => esc_html__( 'Clear all edited images. New images will be created on page load.', 'maxx-fitness' )
			)
		);

		maxxfitness_register_options( $fields, 'maxxfitness_settings', 'images_options', array(
			'title' => esc_html__( 'Images options', 'maxx-fitness' ),
			'context' => maxxfitness_get( 'maxxfitness_settings', $wp_meta_boxes ) ? 'column' : 'normal' // Check of other beans boxes.
		) );

	}


	/**
	 * Flush images for all folders set.
	 */
	public function flush() {

		if ( !maxxfitness_post( 'maxxfitness_flush_edited_images' ) )
			return;

		maxxfitness_remove_dir( maxxfitness_get_images_dir() );

	}


	/**
	 * Image editor notice notice.
	 */
	public function admin_notice() {

		if ( !maxxfitness_post( 'maxxfitness_flush_edited_images' ) )
			return;

		echo '<div id="message" class="updated"><p>' . esc_html__( 'Images flushed successfully!', 'maxx-fitness' ) . '</p></div>' . "\n";

	}


	/**
	 * Add button used to flush images.
	 */
	public function option( $field ) {

		if ( $field['id'] !== 'maxxfitness_edited_images_directories' )
			return;

		echo '<input type="submit" name="maxxfitness_flush_edited_images" value="' . esc_html__( 'Flush images', 'maxx-fitness' ) . '" class="button-secondary" />';

	}

}

new maxxfitness_tt_Image_Options();
