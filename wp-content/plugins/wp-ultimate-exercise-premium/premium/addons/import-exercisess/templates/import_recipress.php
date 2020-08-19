<div class="wrap wpuep-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Import ReciPress', 'wp-ultimate-exercise' ); ?></h2>
    <h3><?php _e( 'Before importing', 'wp-ultimate-exercise' ); ?></h3>
    <ol>
        <li><?php _e( "It's a good idea to backup your WP database before using the import feature.", 'wp-ultimate-exercise' ); ?></li>
        <li>Make sure the <strong>permalink structure</strong> for your exercises is the same as the current one. By default WP Ultimate Exercise uses the /exercise/ slug, but if this doesn't correspond with your current setup your old links won't work anymore. Potential solutions:
            <ul>
                <li>Change the /exercise/ slug on the <strong>Exercies > Settings > Exercise Archive</strong> page to /your-current-slug/</li>
                <li>Remove the /exercise/ slug on the <strong>Exercies > Settings > Advanced</strong> page</li>
                <li>Use the <a href="https://wordpress.org/plugins/custom-post-type-permalinks/" target="_blank">Custom Post Type Permalinks</a> plugin for more complex permalinks like /year/month/</li>
            </ul>
        </li>
        <li>
            You need to match the ReciPress tags with one of our custom tags:
        </li>
    </ol>
    <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>" onsubmit="return confirm('ReciPress <?php _e('exercises are about to be imported to', 'wp-ultimate-exercise' ); ?> WP Ultimate Exercise. <?php _e('Are you sure you want to do this?', 'wp-ultimate-exercise' ); ?>');">
        <input type="hidden" name="action" value="import_recipress">
        <?php wp_nonce_field( 'import_recipress', 'import_recipress_nonce', false ); ?>

        <?php
        $taxonomies = WPUltimateExercise::get()->tags();
        unset( $taxonomies['ingredient'] );
        ?>
        Course: <select id="wpuep_import_course" name="wpuep_import_course">
            <option value=""><?php _e( 'Select a exercise tag', 'wp-ultimate-exercise' ); ?></option>
            <?php
            foreach( $taxonomies as $taxonomy => $tag_options )
            {
                echo '<option value="' . $taxonomy . '"';
                if( $taxonomy == 'course' ) {
                    echo ' selected';
                }
                echo '>';
                echo $tag_options['labels']['name'];
                echo '</option>';
            }
            ?>
        </select><br/>
        Cuisine: <select id="wpuep_import_cuisine" name="wpuep_import_cuisine">
            <option value=""><?php _e( 'Select a exercise tag', 'wp-ultimate-exercise' ); ?></option>
            <?php
            foreach( $taxonomies as $taxonomy => $tag_options )
            {
                echo '<option value="' . $taxonomy . '"';
                if( $taxonomy == 'cuisine' ) {
                    echo ' selected';
                }
                echo '>';
                echo $tag_options['labels']['name'];
                echo '</option>';
            }
            ?>
        </select><br/>
        Skill Level: <select id="wpuep_import_skill_level" name="wpuep_import_skill_level">
            <option value=""><?php _e( 'Select a exercise tag', 'wp-ultimate-exercise' ); ?></option>
            <?php
            foreach( $taxonomies as $taxonomy => $tag_options )
            {
                echo '<option value="' . $taxonomy . '"';
                if( $taxonomy == 'skill_level' ) {
                    echo ' selected';
                }
                echo '>';
                echo $tag_options['labels']['name'];
                echo '</option>';
            }
            ?>
        </select>

        <?php submit_button( __( 'Import ReciPress', 'wp-ultimate-exercise' ) ); ?>
    </form>
</div>