<?php
/**
 * Handle the Beans Options workflow.
 *
 * @ignore
 *
 * @package API\Options
 */
final class maxxfitness_tt_Options {

	/**
	 * Metabox arguments.
	 *
	 * @type array
	 */
	private $args = array();

	/**
	 * Form submission status.
	 *
	 * @type bool
	 */
	private $success = false;

	/**
	 * Fields section.
	 *
	 * @type string
	 */
	private $section;


	/**
	 * Register options.
	 */
	public function register( $section, $args ) {

		$defaults = array(
			'title' => esc_html__( 'Undefined', 'maxx-fitness' ),
			'context' => 'normal'
		);

		$this->section = $section;
		$this->args = array_merge( $defaults, $args );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		$this->register_metabox();

	}


	/**
	 * Enqueue assets.
	 */
	public function enqueue_assets() {

		wp_enqueue_script( 'postbox' );

	}


	/**
	 * Register the Metabox.
	 */
	private function register_metabox() {

		add_meta_box(
			$this->section,
			$this->args['title'],
			array( $this, 'metabox_content' ),
			maxxfitness_get( 'page' ),
			$this->args['context'],
			'default'
		);

	}


	/**
	 * Metabox content.
	 */
	public function metabox_content() {

		foreach ( maxxfitness_get_fields( 'option', $this->section ) as $field )
			maxxfitness_field( $field );

	}


	/**
	 * Page content.
	 */
	public function page( $page ) {

		global $wp_meta_boxes;

		if ( !$boxes = maxxfitness_get( $page, $wp_meta_boxes ) )
			return;

		// Only add column class if there is more than 1 metaboxes.
		$column_class = maxxfitness_get( 'column', $boxes, array() ) ? ' column' : false;

		// Set page data which will be used by the postbox.
		echo '<form action="" method="post" class="bs-options" data-page="' . maxxfitness_get( 'page' ) . '">';

			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
			echo '<input type="hidden" name="maxxfitness_options_nonce" value="' . esc_attr( wp_create_nonce( 'maxxfitness_options_nonce' ) ) . '" />';

			echo '<div class="metabox-holder' . $column_class . '">';

				do_meta_boxes( $page, 'normal', null );

				if ( $column_class )
					do_meta_boxes( $page, 'column', null );

			echo '</div>';

			echo '<p class="bs-options-form-actions">
				<input type="submit" name="maxxfitness_save_options" value="' . esc_attr__( 'Save', 'maxx-fitness' ) . '" class="button-primary">
				<input type="submit" name="maxxfitness_reset_options" value="' . esc_attr__( 'Reset', 'maxx-fitness' ) . '" class="button-secondary">
			</p>';

		echo '</form>';

	}


	/**
	 * Form actions.
	 */
	public function actions() {

		if ( maxxfitness_post( 'maxxfitness_save_options' ) ) {

			$this->save();
			add_action( 'admin_notices', array( $this, 'save_notices' ) );

		}

		if ( maxxfitness_post( 'maxxfitness_reset_options' ) ) {

			$this->reset();
			add_action( 'admin_notices', array( $this, 'reset_notices' ) );

		}

	}


	/**
	 * Save options.
	 */
	private function save() {

		if ( !wp_verify_nonce( maxxfitness_post( 'maxxfitness_options_nonce' ), 'maxxfitness_options_nonce' ) )
			return false;

		if ( !( $fields = maxxfitness_post( 'maxxfitness_fields' ) ) )
			return false;

		foreach ( $fields as $field => $value )
			update_option( $field, stripslashes_deep( $value ) );

		$this->success = true;

	}


	/**
	 * Reset options.
	 */
	private function reset() {

		if ( !wp_verify_nonce( maxxfitness_post( 'maxxfitness_options_nonce' ), 'maxxfitness_options_nonce' ) )
			return false;

		if ( !( $fields = maxxfitness_post( 'maxxfitness_fields' ) ) )
			return false;

		foreach ( $fields as $field => $value )
			delete_option( $field );

		$this->success = true;

	}


	/**
	 * Save notice content.
	 */
	public function save_notices() {

		if ( $this->success )
			echo '<div id="message" class="updated"><p>' . esc_html__( 'Settings saved successfully!', 'maxx-fitness' ) . '</p></div>';
		else
			echo '<div id="message" class="error"><p>' . esc_html__( 'Settings could not be saved, please try again.', 'maxx-fitness' ) . '</p></div>';


	}


	/**
	 * Reset notice content.
	 */
	public function reset_notices() {

		if ( $this->success )
			echo '<div id="message" class="updated"><p>' . esc_html__( 'Settings reset successfully!', 'maxx-fitness' ) . '</p></div>';
		else
			echo '<div id="message" class="error"><p>' . esc_html__( 'Settings could not be reset, please try again.', 'maxx-fitness' ) . '</p></div>';

	}

}