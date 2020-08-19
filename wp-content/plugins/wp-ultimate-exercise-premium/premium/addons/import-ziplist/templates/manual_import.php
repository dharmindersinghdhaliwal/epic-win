

<div class="wrap wpuep-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Import Ziplist', 'wp-ultimate-exercise' ); ?></h2>

    <?php
    $ziplist_exercises = $this->get_ziplist_exercises();

    if( count( $ziplist_exercises['import'] ) == 0 ) {
        echo '<p>' . __( 'There are no exercises left to import', 'wp-ultimate-exercise' ) . '</p>';
    } else {
        echo '<p>' . __( 'Number of exercises left to import:', 'wp-ultimate-exercise' ) . ' ' . count( $ziplist_exercises['import'] ) .'</p>';

        $ziplist_id = reset( $ziplist_exercises['import'] );
        $post_id = key( $ziplist_exercises['import'] );

        $ziplist = $this->ziplist_get_exercise( $ziplist_id );
        $ziplist_ingredients = explode( "\n", $ziplist->ingredients );

        $ingredients = array();
        foreach( $ziplist_ingredients as $item ) {
            if( preg_match( "/^%(\S*)/", $item, $matches ) || preg_match( "/^!(.*)/", $item, $matches ) ) {
                // Image or label, don't process
            } else {
                // Remove bold, italic and links
                $ingredients[] = $this->ziplist_derichify( $item );
            }
        }

    // Pass ingredients to javascript
    ?>
    <script type="text/javascript">
        <?php echo 'var wpuep_import_ingredients = '. json_encode( $ingredients ) . ';'; ?>
    </script>

    <h3><?php _e( 'Ingredients', 'wp-ultimate-exercise' );?></h3>
    <form method="POST" action="<?php echo admin_url( 'edit.php?post_type=exercise&page=wpuep_import_ziplist_manual' ); ?>">
        <input type="hidden" name="action" value="import_ziplist_manual">
        <input type="hidden" name="import_ziplist_id" value="<?php echo $ziplist_id; ?>">
        <input type="hidden" name="import_post_id" value="<?php echo $post_id; ?>">
        <?php wp_nonce_field( 'import_ziplist_manual', 'import_ziplist_manual', false ); ?>

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