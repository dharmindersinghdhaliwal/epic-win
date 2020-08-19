<?php
// Exercise should never be null. Construct just allows easy access to WPUEP_Exercise functions in IDE.
if( is_null( $exercise ) ) $exercise = new WPUEP_Exercise(0);

//	echo '<pre>'; print_r( $exercise ); exit; 

if( !isset( $required_fields ) ) $required_fields = array();
?>

<script>
    function autoSuggestTag(id, type) {
        jQuery('#' + id).suggest("<?php echo get_bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php?action=ajax-tag-search&tax=" + type);
    }
</script>
<input type="hidden" name="exercise_meta_box_nonce" value="<?php echo wp_create_nonce( 'exercise' ); ?>" />
<div class="exercise-general-container">
    <h4><?php _e( 'General', 'wp-ultimate-exercise' ); ?></h4>
    <table class="exercise-general-form">
    <?php if( !isset( $wpuep_user_submission ) ) { ?>
        <tr class="exercise-general-form-title">
            <td class="exercise-general-form-label"><label for="exercise_title"><?php _e( 'Title', 'wp-ultimate-exercise' ); ?></label></td>
            <td class="exercise-general-form-field">
                <input type="text" name="exercise_title" id="exercise_title" value="<?php echo esc_attr( $exercise->title() ); ?>" />
                <span class="exercise-general-form-notes"> <?php _e( '(leave blank to use post title)', 'wp-ultimate-exercise' ) ?></span>
            </td>
        </tr>
    <?php } ?>
		<tr class="exercise-general-form-youtubelink">
            <td class="exercise-general-form-label"><label for="exercise_youtubelink"><?php _e('Youtube Link', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_youtubelink', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
            <td class="exercise-general-form-field">
                <input style="width:300px;" type="text" name="exercise_youtubelink" id="exercise_youtubelink" value="<?php echo esc_attr( $exercise->youtubelink() ); ?>" />
            </td>
        </tr>
		
        <tr class="exercise-general-form-description">
            <td class="exercise-general-form-label"><label for="exercise_description"><?php _e('Description', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_description', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
            <td class="exercise-general-form-field">
                <textarea name="exercise_description" id="exercise_description" rows="4"><?php echo $exercise->description(); ?></textarea>
            </td>
        </tr>
        <tr class="exercise-general-form-rating">
            <td class="exercise-general-form-label"><label for="exercise_rating"><?php _e( 'Rating', 'wp-ultimate-exercise' ); ?></label></td>
            <td class="exercise-general-form-field">
                <select name="exercise_rating" id="exercise_rating">
                    <?php
                    for ( $i = 0; $i <= 5; $i ++ ) {
                    ?>
                    <option value="<?php echo $i; ?>" <?php echo selected( $i, $exercise->rating_author() ); ?>>
                        <?php echo $i == 1 ? $i .' '. __( 'star', 'wp-ultimate-exercise' ) : $i .' '. __( 'stars', 'wp-ultimate-exercise' ); ?>
                    </option>
                    <?php } ?>
                </select>
            </td>
        </tr>
		
		<tr class="exercise-general-form-weight">
			<td class="exercise-general-form-label"><label for="exercise_weight"><?php _e( 'Weight', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_weight', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
			<td class="exercise-general-form-field">
				<!--<input type="number" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="4" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;">-->
				<?php 
					$weight		=	esc_attr( $exercise->weight());
					
					if( empty( $weight ) )
					{
						$weight		=	12;
					}
				
				?>
				
				<input type="text" name="exercise_weight" id="exercise_weight" value="<?php echo $weight; ?>" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"/>				
			</td>
		</tr>
		
		<tr class="exercise-general-form-sets">
			<td class="exercise-general-form-label"><label for="exercise_sets"><?php _e( 'Sets', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_sets', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
			<td class="exercise-general-form-field">				
				<?php 
					$sets	=	esc_attr( $exercise->sets() );
					
					if( empty( $sets ) )
					{
						$sets	=	3;
					}
				?>
				
				<input type="number" name="exercise_sets" id="exercise_sets" value="<?php echo $sets; ?>" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"/>				
			</td>
		</tr>
		
		<tr class="exercise-general-form-reps">
			<td class="exercise-general-form-label"><label for="exercise_reps"><?php _e( 'Reps', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_reps', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
			<td class="exercise-general-form-field">
				
				<?php 
					$reps 	=	esc_attr( $exercise->reps() );
					
					if( empty( $reps ) )
					{
						$reps	=	12;
					}
					
				?>
				
				<input type="number" name="exercise_reps" id="exercise_reps" value="<?php echo $reps; ?>" style="width:40px !important;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"/>				
			</td>
		</tr>
		
        <tr class="exercise-general-form-servings">
            <td class="exercise-general-form-label"><label for="exercise_servings"><?php _e( 'Servings', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_servings', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
            <td class="exercise-general-form-field">
                <input type="text" name="exercise_servings" id="exercise_servings" value="<?php echo esc_attr( $exercise->servings() ); ?>" />
                <input type="text" name="exercise_servings_type" id="exercise_servings_type" value="<?php echo esc_attr( $exercise->servings_type() ); ?>" />
                <span class="exercise-general-form-notes"> <?php _e( '(e.g. 2 people, 3 loafs, ...)', 'wp-ultimate-exercise' ); ?></span>
            </td>
        </tr>
        <tr class="exercise-general-form-prep-time">
            <td class="exercise-general-form-label"><label for="exercise_prep_time"><?php _e( 'Prep Time', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_prep_time', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
            <td class="exercise-general-form-field">
                <input type="text" name="exercise_prep_time" id="exercise_prep_time" value="<?php echo esc_attr( $exercise->prep_time() ); ?>" />
                <input type="text" name="exercise_prep_time_text" id="exercise_prep_time_text" value="<?php echo esc_attr( $exercise->prep_time_text() ); ?>" />
                <span class="exercise-general-form-notes"> <?php _e( '(e.g. 20 minutes, 1-2 hours, ...)', 'wp-ultimate-exercise' ); ?></span>
            </td>
        </tr>
        <tr class="exercise-general-form-cook-time">
            <td class="exercise-general-form-label"><label for="exercise_cook_time"><?php _e( 'Cook Time', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_cook_time', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
            <td class="exercise-general-form-field">
                <input type="text" name="exercise_cook_time" id="exercise_cook_time" value="<?php echo esc_attr( $exercise->cook_time() ); ?>" />
                <input type="text" name="exercise_cook_time_text" id="exercise_cook_time_text" value="<?php echo esc_attr( $exercise->cook_time_text() ); ?>" />
            </td>
        </tr>
        <tr class="exercise-general-form-passive-time">
            <td class="exercise-general-form-label"><label for="exercise_passive_time"><?php _e( 'Passive Time', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise_passive_time', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
            <td class="exercise-general-form-field">
                <input type="text" name="exercise_passive_time" id="exercise_passive_time" value="<?php echo esc_attr( $exercise->passive_time() ); ?>" />
                <input type="text" name="exercise_passive_time_text" id="exercise_passive_time_text" value="<?php echo esc_attr( $exercise->passive_time_text() ); ?>" />
            </td>
        </tr>
    <?php if( !isset( $wpuep_user_submission ) ) { ?>
        <tr>
            <td class="exercise-general-form-label">&nbsp;</td>
            <td class="exercise-general-form-field exercise-form-notes">
                <?php _e( "Don't forget that you can tag your exercise with <strong>Courses</strong> and <strong>Cuisines</strong> by using the boxes on the right. Use the <strong>featured image</strong> if you want a photo of the finished dish.", 'wp-ultimate-exercise' ) ?>
            </td>
        </tr>
    <?php } ?>
    </table>
</div>

<div class="exercise-ingredients-container">
    <h4><?php _e( 'Ingredients', 'wp-ultimate-exercise' ); ?></h4>
    <?php $ingredients = $exercise->ingredients(); ?>
    <table id="exercise-ingredients">
        <thead>
        <tr class="ingredient-group ingredient-group-first">
            <td>&nbsp;</td>
            <td><strong><?php _e( 'Group', 'wp-ultimate-exercise' ); ?>:</strong></td>
            <td colspan="2">
                <span class="ingredient-groups-disabled"><?php echo __( 'Main Ingredients', 'wp-ultimate-exercise' ) . ' ' . __( '(this label is not shown)', 'wp-ultimate-exercise' ); ?></span>
                <?php
                $previous_group = '';
                if( isset( $ingredients[0] ) && isset( $ingredients[0]['group'] ) ) {
                    $previous_group = $ingredients[0]['group'];
                }
                ?>
                <span class="ingredient-groups-enabled"><input type="text" class="ingredient-group-label" value="<?php echo esc_attr( $previous_group ); ?>" /></span>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="ingredient-field-header">
            <td>&nbsp;</td>
            <td><?php _e( 'Quantity', 'wp-ultimate-exercise' ); ?></td>
            <td><?php _e( 'Unit', 'wp-ultimate-exercise' ); ?></td>
            <td><?php _e( 'Ingredient', 'wp-ultimate-exercise' ); ?> <span class="wpuep-required">(<?php _e( 'required', 'wp-ultimate-exercise' ); ?>)</span></td>
            <td><?php _e( 'Notes', 'wp-ultimate-exercise' ); ?></td>
        </tr>
        </thead>
        <tbody>
        <tr class="ingredient-group-stub">
            <td>&nbsp;</td>
            <td><strong><?php _e( 'Group', 'wp-ultimate-exercise' ); ?>:</strong></td>
            <td colspan="2"><input type="text" class="ingredient-group-label" /></td>
            <td>&nbsp;</td>
            <td class="center-column"><span class="ingredient-group-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
        </tr>
        <?php
        $i = 0;
        if( $ingredients )
        {
            foreach( $ingredients as $ingredient ) {

                if( isset( $ingredient['ingredient_id'] ) ) {
                    $term = get_term( $ingredient['ingredient_id'], 'ingredient' );
                    if ( $term !== null && !is_wp_error( $term ) ) {
                        $ingredient['ingredient'] = $term->name;
                    }
                }

                if( !isset( $ingredient['group'] ) ) {
                    $ingredient['group'] = '';
                }

                if( $ingredient['group'] != $previous_group ) { ?>
                    <tr class="ingredient-group">
                        <td>&nbsp;</td>
                        <td><strong><?php _e( 'Group', 'wp-ultimate-exercise' ); ?>:</strong></td>
                        <td colspan="2"><input type="text" class="ingredient-group-label" value="<?php echo esc_attr( $ingredient['group'] ); ?>" /></td>
                        <td>&nbsp;</td>
                        <td class="center-column"><span class="ingredient-group-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
                    </tr>
                    <?php
                    $previous_group = $ingredient['group'];
                }
                ?>
                <tr class="ingredient">
                    <td class="sort-handle"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/arrows.png" width="18" height="16"></td>
                    <td><input type="text"   name="exercise_ingredients[<?php echo $i; ?>][amount]"     class="ingredients_amount" id="ingredients_amount_<?php echo $i; ?>" value="<?php echo esc_attr( $ingredient['amount'] ); ?>" /></td>
                    <td><input type="text"   name="exercise_ingredients[<?php echo $i; ?>][unit]"       class="ingredients_unit" id="ingredients_unit_<?php echo $i; ?>" value="<?php echo esc_attr( $ingredient['unit'] ); ?>" /></td>
                    <td><input type="text"   name="exercise_ingredients[<?php echo $i; ?>][ingredient]" class="ingredients_name" id="ingredients_<?php echo $i; ?>" onfocus="autoSuggestTag('ingredients_<?php echo $i; ?>', 'ingredient');"  value="<?php echo esc_attr( $ingredient['ingredient'] ); ?>" /></td>
                    <td>
                        <input type="text"   name="exercise_ingredients[<?php echo $i; ?>][notes]"      class="ingredients_notes" id="ingredient_notes_<?php echo $i; ?>" value="<?php echo esc_attr( $ingredient['notes'] ); ?>" />
                        <input type="hidden" name="exercise_ingredients[<?php echo $i; ?>][group]"      class="ingredients_group" id="ingredient_group_<?php echo $i; ?>" value="<?php echo esc_attr( $ingredient['group'] ); ?>" />
                    </td>
                    <td><span class="ingredients-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
                </tr>
                <?php
                $i++;
            }

        }
        ?>
        <tr class="ingredient">
            <td class="sort-handle"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/arrows.png" width="18" height="16"></td>
            <td><input type="text"   name="exercise_ingredients[<?php echo $i; ?>][amount]"     class="ingredients_amount" id="ingredients_amount_<?php echo $i; ?>" placeholder="1" /></td>
            <td><input type="text"   name="exercise_ingredients[<?php echo $i; ?>][unit]"       class="ingredients_unit" id="ingredients_unit_<?php echo $i; ?>" placeholder="<?php _e( 'tbsp', 'wp-ultimate-exercise' ); ?>" /></td>
            <td>
                <?php if( isset( $wpuep_user_submission ) && WPUltimateExercise::option( 'user_submission_ingredient_list', '0' ) == '1' ) { ?>
                    <select name="exercise_ingredients[<?php echo $i; ?>][ingredient]" class="ingredients_name_list" id="ingredients_<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select an ingredient', 'wp-ultimate-exercise' ); ?></option>
                        <?php
                        $args = array(
                            'orderby'       => 'name',
                            'order'         => 'ASC',
                            'hide_empty'    => false,
                        );
                        $ingredient_terms = get_terms( 'ingredient', $args );

                        foreach( $ingredient_terms as $term )
                        {
                            ?>
                            <option value="<?php echo esc_attr( $term->name ); ?>"><?php echo $term->name; ?></option>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                    <input type="text"   name="exercise_ingredients[<?php echo $i; ?>][ingredient]" class="ingredients_name" id="ingredients_<?php echo $i; ?>" onfocus="autoSuggestTag('ingredients_<?php echo $i; ?>', 'ingredient');" placeholder="<?php _e( 'olive oil', 'wp-ultimate-exercise' ); ?>" />
                <?php } ?>
            </td>
            <td>
                <input type="text"   name="exercise_ingredients[<?php echo $i; ?>][notes]"      class="ingredients_notes" id="ingredient_notes_<?php echo $i; ?>" placeholder="<?php _e( 'extra virgin', 'wp-ultimate-exercise' ); ?>" />
                <input type="hidden" name="exercise_ingredients[<?php echo $i; ?>][group]"    class="ingredients_group" id="ingredient_group_<?php echo $i; ?>" value="" />
            </td>
            <td><span class="ingredients-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
        </tr>
        </tbody>
    </table>
    <div id="ingredients-add-box">
        <a href="#" id="ingredients-add"><?php _e( 'Add an ingredient', 'wp-ultimate-exercise' ); ?></a>
    </div>
    <div id="ingredients-add-group-box">
        <a href="#" id="ingredients-add-group"><?php _e( 'Add an ingredient group', 'wp-ultimate-exercise' ); ?></a>
    </div>
    <div class="exercise-form-notes">
        <?php _e( "<strong>Use the TAB key</strong> while adding ingredients, it will automatically create new fields. <strong>Don't worry about empty lines</strong>, these will be ignored.", 'wp-ultimate-exercise' ); ?>
    </div>
</div>

<div class="exercise-instructions-container">
    <h4><?php _e( 'Instructions', 'wp-ultimate-exercise' ); ?></h4>
    <?php $instructions = $exercise->instructions(); ?>
    <table id="exercise-instructions">
        <thead>
        <tr class="instruction-group instruction-group-first">
            <td>&nbsp;</td>
            <td colspan="2">
                <strong><?php _e( 'Group', 'wp-ultimate-exercise' ); ?>:</strong>
                <span class="instruction-groups-disabled"><?php echo __( 'Main Instructions', 'wp-ultimate-exercise' ) . ' ' . __( '(this label is not shown)', 'wp-ultimate-exercise' ); ?></span>
                <?php
                $previous_group = '';
                if( isset( $instructions[0] ) && isset( $instructions[0]['group'] ) ) {
                    $previous_group = $instructions[0]['group'];
                }
                ?>
                <span class="instruction-groups-enabled"><input type="text" class="instruction-group-label" value="<?php echo esc_attr( $previous_group ); ?>"/></span>
            </td>
            <td>&nbsp;</td>
        </tr>
        </thead>
        <tbody>
        <tr class="instruction-group-stub">
            <td>&nbsp;</td>
            <td colspan="2">
                <strong><?php _e( 'Group', 'wp-ultimate-exercise' ); ?>:</strong>
                <input type="text" class="instruction-group-label" />
            </td>
            <td class="center-column"><span class="instruction-group-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
        </tr>
    <?php
    $i = 0;

    if( $instructions )
    {
        foreach( $instructions as $instruction ) {
            if( !isset( $instruction['group'] ) ) {
                $instruction['group'] = '';
            }

            if( $instruction['group'] != $previous_group )
            { ?>
                <tr class="instruction-group">
                    <td>&nbsp;</td>
                    <td colspan="2">
                        <strong><?php _e( 'Group', 'wp-ultimate-exercise' ); ?>:</strong>
                        <input type="text" class="instruction-group-label" value="<?php echo esc_attr( $instruction['group'] ); ?>"/>
                    </td>
                    <td class="center-column"><span class="instruction-group-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
                </tr>
    <?php
                $previous_group = $instruction['group'];
            }

            if( !isset( $instruction['image'] ) ) {
                $instruction['image'] = '';
            }

            if( $instruction['image'] )
            {
                $image = wp_get_attachment_image_src( $instruction['image'], 'thumbnail' );
                $image = $image[0];
                $has_image = true;
            }
            else
            {
                $image = WPUltimateExercise::get()->coreUrl . '/img/image_placeholder.png';
                $has_image = false;
            }
            ?>
            <tr class="instruction">
                <td class="sort-handle"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/arrows.png" width="18" height="16"></td>
                <td>
                    <textarea name="exercise_instructions[<?php echo $i; ?>][description]" rows="4" id="ingredient_description_<?php echo $i; ?>"><?php echo $instruction['description']; ?></textarea>
                    <input type="hidden" name="exercise_instructions[<?php echo $i; ?>][group]"    class="instructions_group" id="instruction_group_<?php echo $i; ?>" value="<?php echo esc_attr( $instruction['group'] ); ?>" />
                <?php if ( isset( $wpuep_user_submission ) && ( !current_user_can( 'upload_files' ) || WPUltimateExercise::option( 'user_submission_use_media_manager', '1' ) != '1' ) ) { ?>
                    <?php _e( 'Instruction Step Image', 'wp-ultimate-exercise' ); ?>:<br/>
                    <?php if( $has_image ) { ?>
                    <img src="<?php echo $image; ?>" class="exercise_instructions_thumbnail" />
                    <input type="hidden" value="<?php echo $instruction['image']; ?>" name="exercise_instructions[<?php echo $i; ?>][image]" /><br/>
                    <?php } ?>
                    <input class="exercise_instructions_image button" type="file" id="exercise_thumbnail" value="" size="50" name="exercise_thumbnail_<?php echo $i; ?>" />
                </td>
                <?php } else { ?>
                </td>
                <td>
                    <input name="exercise_instructions[<?php echo $i; ?>][image]" class="exercise_instructions_image" type="hidden" value="<?php echo $instruction['image']; ?>" />
                    <input class="exercise_instructions_add_image button<?php if($has_image) { echo ' wpuep-hide'; } ?>" rel="<?php echo $exercise->ID(); ?>" type="button" value="<?php _e( 'Add Image', 'wp-ultimate-exercise' ) ?>" />
                    <input class="exercise_instructions_remove_image button<?php if(!$has_image) { echo ' wpuep-hide'; } ?>" type="button" value="<?php _e( 'Remove Image', 'wp-ultimate-exercise' ) ?>" />
                    <br /><img src="<?php echo $image; ?>" class="exercise_instructions_thumbnail" />
                    <?php } ?>
                </td>
                <td><span class="instructions-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
            </tr>
            <?php
            $i++;
        }

    }

    $image = WPUltimateExercise::get()->coreUrl . '/img/image_placeholder.png';
    ?>
            <tr class="instruction">
                <td class="sort-handle"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/arrows.png" width="18" height="16"></td>
                <td>
                    <textarea name="exercise_instructions[<?php echo $i; ?>][description]" rows="4" id="ingredient_description_<?php echo $i; ?>"></textarea>
                    <input type="hidden" name="exercise_instructions[<?php echo $i; ?>][group]"    class="instructions_group" id="instruction_group_<?php echo $i; ?>" value="" />
                    <?php if ( isset( $wpuep_user_submission ) && ( !current_user_can( 'upload_files' ) || WPUltimateExercise::option( 'user_submission_use_media_manager', '1' ) != '1' ) ) { ?>
                        <?php _e( 'Instruction Step Image', 'wp-ultimate-exercise' ); ?>:<br/>
                        <input class="exercise_instructions_image button" type="file" id="exercise_thumbnail" value="" size="50" name="exercise_thumbnail_<?php echo $i; ?>" />
                        </td>
                    <?php } else { ?>
                </td>
                <td>

                    <input name="exercise_instructions[<?php echo $i; ?>][image]" class="exercise_instructions_image" type="hidden" value="" />
                    <input class="exercise_instructions_add_image button" rel="<?php echo $exercise->ID(); ?>" type="button" value="<?php _e('Add Image', 'wp-ultimate-exercise' ) ?>" />
                    <input class="exercise_instructions_remove_image button wpuep-hide" type="button" value="<?php _e( 'Remove Image', 'wp-ultimate-exercise' ) ?>" />
                    <br /><img src="<?php echo $image; ?>" class="exercise_instructions_thumbnail" />
                    <?php } ?>
                </td>
                <td><span class="instructions-delete"><img src="<?php echo WPUltimateExercise::get()->coreUrl; ?>/img/minus.png" width="16" height="16"></span></td>
            </tr>
        </tbody>
    </table>

    <div id="ingredients-add-box">
        <a href="#" id="instructions-add"><?php _e( 'Add an instruction', 'wp-ultimate-exercise' ); ?></a>
    </div>
    <div id="ingredients-add-group-box">
        <a href="#" id="instructions-add-group"><?php _e( 'Add an instruction group', 'wp-ultimate-exercise' ); ?></a>
    </div>
    <div class="exercise-form-notes">
        <?php _e( "<strong>Use the TAB key</strong> while adding instructions, it will automatically create new fields. <strong>Don't worry about empty lines</strong>, these will be ignored.", 'wp-ultimate-exercise' ); ?>
    </div>
</div>

<div class="exercise-notes-container">
    <h4><?php _e( 'Exercise notes', 'wp-ultimate-exercise' ) ?></h4>
    <?php
    $options = array(
        'textarea_rows' => 7
    );

    if( isset( $wpuep_user_submission ) ) {
        $options['media_buttons'] = false;
    }

    wp_editor( $exercise->notes(), 'exercise_notes',  $options );
    ?>
</div>
<?php
$custom_fields_addon = WPUltimateExercise::addon( 'custom-fields' );
if( $custom_fields_addon && ( !isset( $wpuep_user_submission ) || WPUltimateExercise::option( 'exercise_fields_in_user_submission', '1' ) == '1' ) )
{
    $custom_fields = $custom_fields_addon->get_custom_fields();
    $custom_fields_in_user_submission = WPUltimateExercise::option( 'exercise_fields_user_submission', array_keys( $custom_fields ) );

    if( count( $custom_fields ) > 0 ) {
?>
<div class="exercise-custom-fields-container">
    <h4><?php _e( 'Custom Fields', 'wp-ultimate-exercise' ) ?></h4>
    <table class="exercise-general-form">
        <?php foreach( $custom_fields as $key => $custom_field ) {
            if( isset( $wpuep_user_submission ) && !in_array( $key, $custom_fields_in_user_submission ) ) continue;
            ?>
            <tr>
                <td class="exercise-general-form-label"><label for="<?php echo $key; ?>"><?php echo $custom_field['name']; ?><?php if( in_array( $key, $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label></td>
                <td class="exercise-general-form-field">
                    <textarea name="<?php echo $key; ?>" id="<?php echo $key; ?>" rows="1"><?php echo $exercise->custom_field( $key ); ?></textarea>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<?php }
} ?>