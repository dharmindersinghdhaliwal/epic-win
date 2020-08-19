<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>" onsubmit="return confirm('<?php _e('Do you really want to delete this taxonomy?', 'wp-ultimate-exercise'); ?>');">
    <input type="hidden" name="action" value="delete_taxonomy">
    <?php wp_nonce_field( 'delete_taxonomy', 'delete_taxonomy_nonce', false ); ?>

    <table id="wpuep-tags-table" class="wp-list-table widefat" cellspacing="0">
        <thead>
        <tr>
            <th scope="col" id="tag" class="manage-column">
                <?php _e( 'Tag', 'wp-ultimate-exercise' ); ?>
            </th>
            <th scope="col" id="singular-name" class="manage-column">
                <?php _e( 'Singular Name', 'wp-ultimate-exercise' ); ?>
            </th>
            <th scope="col" id="name" class="manage-column">
                <?php _e( 'Name', 'wp-ultimate-exercise' ); ?>
            </th>
            <th scope="col" id="slug" class="manage-column">
                <?php _e( 'Slug', 'wp-ultimate-exercise' ); ?>
            </th>
            <th scope="col" id="action" class="manage-column">
                <?php _e( 'Actions', 'wp-ultimate-exercise' ); ?>
            </th>
        </tr>
        </thead>

        <tbody id="the-list">
<?php
$taxonomies = get_object_taxonomies( 'exercise', 'objects' );

if ( $taxonomies ) {
    foreach ( $taxonomies as $taxonomy ) {

        if( !in_array( $taxonomy->name, $this->ignoreTaxonomies ) ) {
?>
            <tr>
                <td><strong><?php echo $taxonomy->name; ?></strong></td>
                <td class="singular-name"><?php echo $taxonomy->labels->singular_name; ?></td>
                <td class="name"><?php echo $taxonomy->labels->name; ?></td>
                <td class="slug"><?php echo $taxonomy->rewrite['slug']; ?></td>
                <td>
                    <span class="wpuep_adding">
                        <button type="button" class="button wpuep-edit-tag" data-tag="<?php echo $taxonomy->name; ?>"><?php _e( 'Edit', 'wp-ultimate-exercise' ); ?></button>
                        <?php
                        if( !in_array( $taxonomy->name, $this->protectedTaxonomies ) ) {
                            submit_button( __( 'Delete', 'wp-ultimate-exercise' ), 'delete', 'submit-delete-' . $taxonomy->name, false );
                        }
                        ?>
                    </span>
                </td>
            </tr>
<?php
        }
    }
}
?>
        </tbody>
    </table>
</form>
<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>" onsubmit="return confirm('<?php _e('Do you really want to reset the taxonomies? All custom tags will be deleted.', 'wp-ultimate-exercise' ); ?>');">
    <input type="hidden" name="action" value="reset_taxonomies">
    <?php wp_nonce_field( 'reset_taxonomies', 'reset_taxonomies_nonce', false ); ?>
    <?php submit_button( __( 'Reset to defaults', 'wp-ultimate-exercise' ) ); ?>
</form>