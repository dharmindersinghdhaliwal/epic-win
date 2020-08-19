<?php
/**
 * @package API\Fields\Types
 */

maxxfitness_add_smart_action( 'maxxfitness_field_enqueue_scripts_image', 'maxxfitness_field_image_assets' );

/**
 * Enqueued assets required by the beans image field.
 *
 * @since 1.0.0
 */
function maxxfitness_field_image_assets() {

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'beans-field-media', maxxfitness_API_URL . 'fields/assets/js/media' . maxxfitness_MIN_CSS . '.js', array( 'jquery' ), maxxfitness_VERSION );

}


maxxfitness_add_smart_action( 'maxxfitness_field_image', 'maxxfitness_field_image' );

/**
 * Echo image field type.
 *
 * @since 1.0.0
 *
 * @param array $field {
 *      For best practices, pass the array of data obtained using {@see maxxfitness_get_fields()}.
 *
 *      @type mixed  $value      The field value.
 *      @type string $name       The field name value.
 *      @type array  $attributes An array of attributes to add to the field. The array key defines the
 *            					 attribute name and the array value defines the attribute value. Default array.
 *      @type mixed  $default    The default value. Default false.
 *      @type string $multiple   Set to true to enable mutliple images (gallery). Default false.
 * }
 */
function maxxfitness_field_image( $field ) {

	// Set the images variable and add placeholder to the array.
	$images = array_merge( (array) $field['value'], array( 'placeholder' ) );

	// Is multiple set.
	$multiple = maxxfitness_get( 'multiple', $field );

	// Hide beans if it is a single image and an image already exists
	$hide = !$multiple && is_numeric( $field['value'] ) ? 'style  =  "display: none"' : '';

	?>
	<a href="#" class="bs-add-image button button-small" <?php echo esc_attr($hide); ?>><?php echo _n( 'Add Image', 'Add Images', ( $multiple ? 2 : 1 ), 'maxx-fitness' ); ?></a>
	<input type="hidden" name="<?php echo esc_attr( $field['name'] ); ?>" value="">
	<div class="bs-images-wrap" data-multiple="<?php echo esc_attr( $multiple ); ?>">
		<?php foreach ( $images as $id ) :

			// Stop here if the id is false.
			if ( !$id )
				continue;

			$class = '';
			$img = wp_get_attachment_image_src( $id, 'thumbnail' );

			$attributes = array_merge( array(
				'class' => 'image-id',
				'type' => 'hidden',
				'name' => $multiple ? $field['name'] . '[]' : $field['name'], // Return single value if not multiple.
				'value' => $id
			), $field['attributes'] );

			// Set placeholder.
			if ( $id == 'placeholder' ) {

				$class = 'bs-image-template';
				$attributes = array_merge( $attributes, array( 'disabled' => 'disabled', 'value' => false ) );

			}

			?>
			<div class="bs-image-wrap <?php echo esc_attr( $class ); ?>">
				<input <?php echo maxxfitness_esc_attributes( $attributes ); ?>/>
				<img src="<?php echo esc_url( maxxfitness_get( 0, $img ) ); ?>">
				<div class="bs-toolbar">
					<?php if ( $multiple ) : ?>
						<a href="#" class="dashicons dashicons-menu"></a>
					<?php endif; ?>
					<a href="#" class="dashicons dashicons-edit"></a>
					<a href="#" class="dashicons dashicons-post-trash"></a>
				</div>
			</div>

		<?php endforeach; ?>
	</div>
	<?php

}