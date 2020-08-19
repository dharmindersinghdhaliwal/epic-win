<?php
/**
 * Add Beans admin options.
 *
 * @package Admin
 */

maxxfitness_add_smart_action( 'admin_init', 'maxxfitness_do_register_term_meta' );

/**
 * Add Beans term meta.
 *
 * @since 1.0.0
 */
function maxxfitness_do_register_term_meta() {

	// Get layout option without default for the count.
	$options = maxxfitness_get_layouts_for_options();

	// Stop here if there is less than two layouts options.
	if ( count( $options ) < 2 )
		return;

	$fields = array(
		array(
			'id' => 'maxxfitness_layout',
			'label' => _x( 'Layout', 'term meta', 'maxx-fitness' ),
			'type' => 'radio',
			'default' => 'default_fallback',
			'options' => maxxfitness_get_layouts_for_options( true )
		)
	);

	maxxfitness_register_term_meta( $fields, array( 'category', 'post_tag' ), 'maxx-fitness' );

}


maxxfitness_add_smart_action( 'admin_init', 'maxxfitness_do_register_post_meta' );

/**
 * Add Beans post meta.
 *
 * @since 1.0.0
 */
function maxxfitness_do_register_post_meta() {

	// Get layout option without default for the count.
	$options = maxxfitness_get_layouts_for_options();

	// Stop here if there is less than two layouts options.
	if ( count( $options ) < 2 )
		return;

	$fields = array(
		array(
			'id' => 'maxxfitness_layout',
			'label' => _x( 'Layout', 'post meta', 'maxx-fitness' ),
			'type' => 'radio',
			'default' => 'default_fallback',
			'options' => maxxfitness_get_layouts_for_options( true )
		)
	);

	maxxfitness_register_post_meta( $fields, array( 'post', 'page' ), 'maxx-fitness', array( 'title' => esc_html__( 'Post Options', 'maxx-fitness' ) ) );

}