<?php
/**
 * @package API\Term_Meta
 */

/**
 * Get the current term meta value.
 *
 * @since 1.0.0
 *
 * @param string $field_id The term meta id searched.
 * @param mixed  $default  Optional. The default value to return if the term meta value doesn't exist.
 * @param int    $term_id  Optional. Overwrite the current term id.
 *
 * @return mixed Save data if the term meta value exists, otherwise set the default value.
 */
function maxxfitness_get_term_meta( $field_id, $default = false, $term_id = false ) {

	if ( !$term_id )
		$term_id = ( $_term_id = maxxfitness_get( 'term_id', get_queried_object() ) ) ? $_term_id : maxxfitness_get( 'tag_ID' );

	return get_option( "maxxfitness_term_{$term_id}_{$field_id}", $default );

}