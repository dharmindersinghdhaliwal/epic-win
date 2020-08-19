

<div class="wrap wpuep-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Import ExerciseCard', 'wp-ultimate-exercise' ); ?></h2>

    <?php
    $exercisecard_exercises = $this->get_exercisecard_exercises();

    if( count( $exercisecard_exercises['import'] ) == 0 ) {
        echo '<p>' . __( 'There are no exercises left to import', 'wp-ultimate-exercise' ) . '</p>';
    } else {
        echo '<p>' . __( 'Number of exercises left to import:', 'wp-ultimate-exercise' ) . ' ' . count( $exercisecard_exercises['import'] ) .'</p>';

        $exercisecard_id = reset( $exercisecard_exercises['import'] );
        $post_id = key( $exercisecard_exercises['import'] );

        $exercisecard = $this->get_exercisecard_exercise( $exercisecard_id );

        $ingredients = array();
        foreach( $exercisecard->ingredients as $ingredient_group ) {
            foreach( $ingredient_group->lines as $ingredient_line ) {
                $ingredients[] = $ingredient_line;
            }
        }

    // Pass ingredients to javascript
    ?>
    <script type="text/javascript">
        <?php echo 'var wpuep_import_ingredients = '. json_encode( $ingredients ) . ';'; ?>
    </script>

    <h3><?php _e( 'Ingredients', 'wp-ultimate-exercise' );?></h3>
    <form method="POST" action="<?php echo admin_url( 'edit.php?post_type=exercise&page=wpuep_import_exercisecard_manual' ); ?>">
        <input type="hidden" name="action" value="import_exercisecard_manual">
        <input type="hidden" name="import_exercisecard_id" value="<?php echo $exercisecard_id; ?>">
        <input type="hidden" name="import_post_id" value="<?php echo $post_id; ?>">
        <?php wp_nonce_field( 'import_exercisecard_manual', 'import_exercisecard_manual', false ); ?>

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