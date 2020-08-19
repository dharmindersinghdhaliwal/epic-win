<?php
/**
 * @package API\Fields\Types
 */

maxxfitness_add_smart_action( 'maxxfitness_field_checkbox', 'maxxfitness_field_checkbox' );

/**
 * Echo checkbox field type.
 *
 * @since 1.0.0
 *
 * @param array $field {
 *      For best practices, pass the array of data obtained using {@see maxxfitness_get_fields()}.
 *
 *      @type mixed  $value          The field value.
 *      @type string $name           The field name value.
 *      @type array  $attributes     An array of attributes to add to the field. The array key defines the
 *            					     attribute name and the array value defines the attribute value. Default array.
 *      @type mixed  $default        The default value. Default false.
 *      @type string $checkbox_label The field checkbox label. Default 'Enable'.
 * }
 */
function maxxfitness_field_checkbox( $field ) {

	?>
	<input type="hidden" value="0" name="<?php echo esc_attr( $field['name'] ); ?>" />
	<input type="checkbox" name="<?php echo esc_attr( $field['name'] ); ?>" value="1" <?php checked( $field['value'], 1 ); ?> <?php echo maxxfitness_esc_attributes( $field['attributes'] ); ?>/>
	<?php if ( $checkbox_label = maxxfitness_get( 'checkbox_label', $field, esc_html__( 'Enable', 'maxx-fitness' ) ) ) : ?>
		<span class="bs-checkbox-label"><?php echo esc_html($checkbox_label); ?></span>
	<?php endif;

}