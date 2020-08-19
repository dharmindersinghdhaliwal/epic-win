<?php _e( 'Create custom tags for your exercises.', 'wp-ultimate-exercise' ); ?>
<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
    <input type="hidden" name="action" value="add_taxonomy">
    <input type="hidden" id="wpuep_edit_tag_name" name="wpuep_edit" value="">
    <?php wp_nonce_field( 'add_taxonomy', 'add_taxonomy_nonce', false ); ?>

    <div id="wpuep_editing" class="wpuep_editing">
        <?php _e( 'Currently editing tag: ', 'wp-ultimate-exercise' ); ?><span id="wpuep_editing_tag"></span>
    </div>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Name', 'wp-ultimate-exercise' ); ?></th>
                <td>
                    <input type="text" id="wpuep_custom_taxonomy_name" name="wpuep_custom_taxonomy_name" />
                    <label for="wpuep_custom_taxonomy_name"><?php _e('(e.g. Courses)', 'wp-ultimate-exercise' ); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Singular Name', 'wp-ultimate-exercise' ); ?></th>
                <td>
                    <input type="text" id="wpuep_custom_taxonomy_singular_name" name="wpuep_custom_taxonomy_singular_name" />
                    <label for="wpuep_custom_taxonomy_singular_name"><?php _e('(e.g. Course)', 'wp-ultimate-exercise' ); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Slug', 'wp-ultimate-exercise' ); ?></th>
                <td>
                    <input type="text" id="wpuep_custom_taxonomy_slug" name="wpuep_custom_taxonomy_slug" />
                    <label for="wpuep_custom_taxonomy_slug"><?php _e('(e.g. http://www.yourwebsite.com/course/)', 'wp-ultimate-exercise' ); ?></label>
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <span class="wpuep_adding">
        <button type="button" class="button button-primary" disabled><?php _e( 'Add new tag', 'wp-ultimate-exercise' ); ?></button>
        <strong><?php _e( 'Adding new tags is only possible in', 'wp-ultimate-exercise' ); ?> <a href="http://www.wpultimateexercise.com/premium/" target="_blank">WP Ultimate Exercise Premium</a></strong>
    </span>
    <span class="wpuep_editing">
        <?php submit_button( __( 'Edit tag', 'wp-ultimate-exercise' ), 'primary', 'submit', false ); ?>
        <button type="button" id="wpuep_cancel_editing" class="button"><?php _e( 'Cancel Edit', 'wp-ultimate-exercise' ); ?></button>
    </span>
</form>