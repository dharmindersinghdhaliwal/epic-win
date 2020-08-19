<div class="wrap wpuep-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Import XML', 'wp-ultimate-exercise' ); ?></h2>
    <h3><?php _e( 'Before importing', 'wp-ultimate-exercise' ); ?></h3>
    <ol>
        <li><?php _e( "It's a good idea to backup your WP database before using the import feature.", 'wp-ultimate-exercise' ); ?></li>
        <li>Select the XML file containing exercises in the WP Ultimate Exercise format:</li>
    </ol>
    <form method="POST" action="<?php echo admin_url( 'edit.php?post_type=exercise&page=wpuep_import_xml_manual' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="import_xml_manual">
        <?php wp_nonce_field( 'exercise_submit', 'submitexercise' ); ?>
        <input type="hidden" name="exercise_meta_box_nonce" value="<?php echo wp_create_nonce('exercise'); ?>" />
        <input type="file" name="xml">
        <?php submit_button( __( 'Import XML', 'wp-ultimate-exercise' ) ); ?>
    </form>
</div>