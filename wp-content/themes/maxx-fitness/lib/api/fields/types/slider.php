<?php
/**
 * @package API\Fields\Types
 */

maxxfitness_add_smart_action( 'maxxfitness_field_enqueue_scripts_slider', 'maxxfitness_field_slider_assets' );

/**
 * Enqueued assets required by the beans slider field.
 *
 * @since 1.0.0
 */
function maxxfitness_field_slider_assets() {

	wp_enqueue_script( 'jquery-ui-slider' );

}


maxxfitness_add_smart_action( 'maxxfitness_field_slider', 'maxxfitness_field_slider' );

/**
 * Echo slider field type.
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
 *      @type string $min        The slider minimum value. Default 0.
 *      @type string $max        The slider maximum value. Default 100.
 *      @type string $interval   The slider interval. Default 1.
 *      @type string $unit       The slider unit. Default null.
 * }
 */
function maxxfitness_field_slider( $field ) {

	$defaults = array(
		'min' => 0,
		'max' => 100,
		'interval' => 1,
		'unit' => null,
	);

	$field	= array_merge( $defaults, $field );

	?>
	<div class="bs-slider-wrap" slider_min="<?php echo (int) $field['min']; ?>" slider_max="<?php echo (int) $field['max']; ?>" slider_interval="<?php echo (int) $field['interval']; ?>">

		<?php // Don't make this a hidden field to prevent triggering issues with wp_customise. ?>
		<input type="text" value="<?php echo esc_attr( $field['value'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" <?php echo maxxfitness_esc_attributes( $field['attributes'] ); ?> style  =  "display: none;"/>

	</div>
	<span class="bs-slider-value"><?php echo  esc_attr($field['value']); ?></span><?php

}