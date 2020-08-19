<?php
/**
 * Handle the Beans Term Meta workflow.
 *
 * @ignore
 *
 * @package API\Term_meta
 */
final class maxxfitness_tt_Term_Meta {

	/**
	 * Fields section.
	 *
	 * @type string
	 */
	private $section;

	/**
	 * Constructor.
	 */
	public function __construct( $section ) {

		$this->section = $section;
		$this->do_once();

		add_action( maxxfitness_get( 'taxonomy' ). '_edit_form_fields', array( $this, 'fields' ) );

	}


	/**
	 * Trigger actions only once.
	 */
	private function do_once() {

		static $once = false;

		if ( !$once ) :

			add_action( maxxfitness_get( 'taxonomy' ). '_edit_form', array( $this, 'nonce' ) );
			add_action( 'edit_term', array( $this, 'save' ) );
			add_action( 'delete_term', array( $this, 'delete' ), 10, 3 );

			$once = true;

		endif;

	}


	/**
	 * Post meta nonce.
	 */
	public function nonce( $tag ) {

		echo '<input type="hidden" name="maxxfitness_term_meta_nonce" value="' . esc_attr( wp_create_nonce( 'maxxfitness_term_meta_nonce' ) ) . '" />';

	}


	/**
	 * Fields content.
	 */
	public function fields( $tag ) {

		maxxfitness_remove_action( 'maxxfitness_field_label' );
		maxxfitness_modify_action_hook( 'maxxfitness_field_description', 'maxxfitness_field_wrap_after_markup' );
		maxxfitness_modify_markup( 'maxxfitness_field_description', 'p' );
		maxxfitness_add_attribute( 'maxxfitness_field_description', 'class', 'description' );

		foreach ( maxxfitness_get_fields( 'term_meta', $this->section ) as $field ) {

			echo '<tr class="form-field">';
				echo '<th scope="row">';
					maxxfitness_field_label( $field );
				echo '</th>';
				echo '<td>';
					maxxfitness_field( $field );
				echo '</td>';
			echo '</tr>';

		}

	}


	/**
	 * Save Term Meta.
	 */
	public function save( $term_id ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return $term_id;

		if ( !wp_verify_nonce( maxxfitness_post( 'maxxfitness_term_meta_nonce' ), 'maxxfitness_term_meta_nonce' ) )
			return $term_id;

		if ( !$fields = maxxfitness_post( 'maxxfitness_fields' ) )
			return $term_id;

		foreach ( $fields as $field => $value )
			update_option( "maxxfitness_term_{$term_id}_{$field}", stripslashes_deep( $value ) );

	}


	/**
	 * Delete Term Meta.
	 */
	public function delete( $term, $term_id, $taxonomy ) {

		global $wpdb;

		$wpdb->query( $wpdb->prepare(
			"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
			"maxxfitness_term_{$term_id}_%"
		) );

	}

}