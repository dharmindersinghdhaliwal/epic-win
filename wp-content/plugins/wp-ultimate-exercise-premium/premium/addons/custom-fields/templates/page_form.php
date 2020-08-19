<?php _e( 'Create custom fields for your exercises.', 'wp-ultimate-exercise' ); ?>
<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
    <input type="hidden" name="action" value="add_custom_field">
    <?php wp_nonce_field( 'add_custom_field', 'add_custom_field_nonce', false ); ?>

    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Key', 'wp-ultimate-exercise' ); ?></th>
                <td>
                    <input type="text" id="wpuep_custom_field_key" name="wpuep_custom_field_key" />
                    <label for="wpuep_custom_field_key"><?php _e( '(e.g. exercise_chef)', 'wp-ultimate-exercise' ); ?> <?php _e( 'Make sure this is unique!', 'wp-ultimate-exercise' ); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Name', 'wp-ultimate-exercise' ); ?></th>
                <td>
                    <input type="text" id="wpuep_custom_field_name" name="wpuep_custom_field_name" />
                    <label for="wpuep_custom_field_name"><?php _e( '(e.g. Exercise Chef)', 'wp-ultimate-exercise' ); ?></label>
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <?php submit_button( __( 'Add new field', 'wp-ultimate-exercise' ), 'primary wpuep_adding', 'submit', false ); ?>
</form>