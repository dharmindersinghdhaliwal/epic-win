

<div class="wrap wpuep-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Import EasyExercise', 'wp-ultimate-exercise' ); ?></h2>
    <?php
    $wpuep_taxonomies = WPUltimateExercise::get()->tags();
    unset( $wpuep_taxonomies['ingredient'] );

    $new_tags = array(
        'course' => $_POST['wpuep_import_course'],
        'cuisine' => $_POST['wpuep_import_cuisine'],
    );

    foreach( $new_tags as $tag ) {
        if ( !array_key_exists( $tag, $wpuep_taxonomies ) ) {
            die( __( 'You should select a new tag for the imported exercises', 'wp-ultimate-exercise' ) );
        }
    }

    if( $new_tags['course'] == $new_tags['cuisine'] ) {
        die( __( 'You should select two distinct tags', 'wp-ultimate-exercise' ) );
    }
    ?>

    <?php
    $exercises = $this->get_easyexercise_exercises();

    if( count( $exercises['import'] ) == 0 ) {
        echo '<p>' . __( 'There are no exercises left to import', 'wp-ultimate-exercise' ) . '</p>';
    } else {
        echo '<p>' . __( 'Number of exercises left to import:', 'wp-ultimate-exercise' ) . ' ' . count( $exercises['import'] ) .'</p>';

        $post_id = reset( $exercises['import'] );

        $exercise = $this->get_easyexercise( $post_id );
    ?>
    <form method="POST" action="<?php echo admin_url( 'edit.php?post_type=exercise&page=wpuep_import_easyexercise_manual' ); ?>">
        <input type="hidden" name="action" value="import_easyexercise_manual">
        <?php wp_nonce_field( 'import_easyexercise_manual', 'import_easyexercise_manual', false ); ?>
        <input type="hidden" name="import_post_id" value="<?php echo $post_id; ?>">
        <input type="hidden" name="wpuep_import_course" value="<?php echo $new_tags['course']; ?>">
        <input type="hidden" name="wpuep_import_cuisine" value="<?php echo $new_tags['cuisine']; ?>">

        <h3><?php _e( 'Featured Image', 'wp-ultimate-exercise' );?></h3>
    <?php
    // Potential Featured Images
    $potential_images = array();

    $featured_image = get_post_thumbnail_id( $post_id, 'medium' );
    if( $featured_image != '' ) {
        $image = wp_get_attachment_image_src( $featured_image, array( 9999, 150 ) );

        $potential_images[] = array(
            'id' => $featured_image,
            'img' => $image[0],
        );
    }

    $potential_images = array_merge( $potential_images, $this->get_easyexercise_images( $exercise->innertext ) );

    foreach( $potential_images as $index => $image ) {
        echo '<input type="radio" name="featured-image" value="' . $image['id'] .'" class="radioImageSelect" data-image="' . $image['img'] . '"';
        if( $index == 0 ) {
            echo ' checked="checked"';
        }
        echo ' />';
    }

    if( count( $potential_images ) > 0 ) {
        echo '<br/>';
        $checked = '';
    } else {
        $checked = ' checked="checked"';
    }
    ?>
        <input type="radio" id="featured-image-none" name="featured-image" value="0"<?php echo $checked; ?> /><img/>
        <label for="featured-image-none"><?php _e( 'No featured image', 'wp-ultimate-exercise' );?></label>

        <script type="text/javascript">
            jQuery(document).ready( function() {
                jQuery('input.radioImageSelect').radioImageSelect();

                jQuery('input#featured-image-none').on( 'click', function() {
                    jQuery('input[name="featured-image"]').each(function() {
                        var myImg = jQuery(this).next('img');

                        // Add / Remove Checked class.
                        if ( jQuery(this).prop('checked') ) {
                            myImg.addClass('item-checked');
                        } else {
                            myImg.removeClass('item-checked');
                        }
                    });
                });
            } );
        </script>

    <?php
    // Exercise Ingredients
        $ingredient_list = $exercise->find( 'ul[class=ingredients]' );
        $ingredient_elements = isset( $ingredient_list[0] ) && is_object( $ingredient_list[0] ) ? $ingredient_list[0]->find( 'li[class=ingredient]' ) : array();

        $ingredients = array();
        foreach( $ingredient_elements as $ingredient ) {
            $text = $this->strip_easyexercise_tags( $ingredient->plaintext );

            if( strlen( $text ) > 0 ) {
                $ingredients[] = $text;
            }
        }

    // Pass ingredients to javascript
    ?>
        <script type="text/javascript">
            <?php echo 'var wpuep_import_ingredients = '. json_encode( $ingredients ) . ';'; ?>
        </script>

        <h3><?php _e( 'Ingredients', 'wp-ultimate-exercise' ); ?></h3>
        <table id="define-ingredient-details" class="import-table">
            <thead>
            <tr>
                <th><?php _e( 'Amount', 'wp-ultimate-exercise' );?></th>
                <th><?php _e( 'Unit', 'wp-ultimate-exercise' );?></th>
                <th><?php _e( 'Ingredient', 'wp-ultimate-exercise' );?></th>
                <th><?php _e( 'Notes', 'wp-ultimate-exercise' );?></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <?php submit_button( __( 'Import this Exercise', 'wp-ultimate-exercise' ) ); ?>
        <em><?php _e( 'Feel free to stop at anytime and come back later for the rest of the exercises.', 'wp-ultimate-exercise' ); ?></em>
    </form>
<?php } ?>
</div>