<div class="wrap wpuep-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Export XML', 'wp-ultimate-exercise' ); ?></h2>
    <h3><?php _e( 'Select exercises', 'wp-ultimate-exercise' ); ?></h3>
    <form method="POST" action="<?php echo admin_url( 'edit.php?post_type=exercise&page=wpuep_export_xml_manual' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="export_xml_manual">
        <?php wp_nonce_field( 'exercise_submit', 'submitexercise' ); ?>
        <input type="hidden" name="exercise_meta_box_nonce" value="<?php echo wp_create_nonce('exercise'); ?>" />
        <?php _e( 'Select', 'wp-ultimate-exercise' ); ?>: <a href="#" onclick="ExportXML.deselectAllExercies()"><?php _e( 'None', 'wp-ultimate-exercise' ); ?></a>, <a href="#" onclick="ExportXML.selectAllExercies()"><?php _e( 'All', 'wp-ultimate-exercise' ); ?></a><br/><br/>
        <?php
        $exercises = WPUltimateExercise::get()->helper( 'cache' )->get( 'exercises_by_title' );

        foreach( $exercises as $exercise ) {
            echo '<input type="checkbox" name="exercises[]" value="' . $exercise['value'] . '" class="xml-exercise"/> ' . $exercise['label'] . '<br/>';
        }
        ?>
        <?php submit_button( __( 'Export XML', 'wp-ultimate-exercise' ) ); ?>
    </form>
</div>