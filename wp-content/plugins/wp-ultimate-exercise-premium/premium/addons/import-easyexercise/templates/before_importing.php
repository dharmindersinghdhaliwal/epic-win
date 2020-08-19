<div class="wrap wpuep-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Import EasyExercise', 'wp-ultimate-exercise' ); ?></h2>
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
            WP Ultimate Exercise stores 4 different parts for each ingredient: quantity, unit, name and notes. This allows us to do things like the Unit Conversion feature and many others. Unfortunately EasyExercise stores ingredients as one piece of text so we can't simply migrate this. We have an automated process that tries it's best but there will be mistakes. To ensure a good migration:
            <ul>
                <li>Make sure commonly used units are in the <strong>Exercies > Settings > Unit Conversion > Unit Aliases</strong> list</li>
                <li>Any other units you might use can be added on the <strong>Exercies > Settings > Import Exercies</strong> page</li>
            </ul>
        </li>
        <li>
            Custom Fields will be created for fields that are not part of our exercises by default. You can manage these on the <strong>Exercies > Custom Fields</strong> page
        </li>
        <li>
            In WP Ultimate Exercise 1 exercise = 1 exercise post, so posts containing multiple exercises cannot be imported automatically. You can create a separate exercise for those and then include them with our [ultimate-exercise id=""] shortcode. There is some more information on the <strong>Exercies > FAQ</strong> page.
        </li>
        <li>
            In WP Ultimate Exercise the featured image is the main image of the exercise. You'll be able to pick one of the images you used in your EasyExercise exercise as the featured image. This one will also be used as the Google Exercise View image.
        </li>
        <li>
            There are a few things that cannot be imported due to plugin differences:
            <ul>
                <li>Links and bold & italic text outside of summary, instructions and notes</li>
                <li>Images outside of instructions and notes (see note above about the featured image)</li>
                <li>Google Exercise View image (see note above about the featured image)</li>
                <li>Cooking times: hours will be added as minutes (e.g. 1 hour, 15 minutes will become 75 minutes)</li>
                <li>Unsaturated fat is mapped to our Polyunsaturated Fat field (we cannot split between poly and mono)</li>
            </ul>
        </li>
        <li>
            You need to match Exercise Type and Cuisine with one of our custom tags:
        </li>
    </ol>
    <form method="POST" action="<?php echo admin_url( 'edit.php?post_type=exercise&page=wpuep_import_easyexercise_manual' ); ?>">
        <input type="hidden" name="action" value="import_easyexercise_manual">
        <?php wp_nonce_field( 'import_easyexercise_manual', 'import_easyexercise_manual', false ); ?>

        <?php
        $taxonomies = WPUltimateExercise::get()->tags();
        unset( $taxonomies['ingredient'] );
        ?>
        Exercise Type: <select id="wpuep_import_course" name="wpuep_import_course">
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
        </select>

        <?php submit_button( __( 'Import EasyExercise', 'wp-ultimate-exercise' ) ); ?>
    </form>

<?php
$easyexercise_exercises = $this->get_easyexercise_exercises();

if( count( $easyexercise_exercises['problem'] ) != 0 ) {
?>
    <h3><?php _e( 'Unable to import', 'wp-ultimate-exercise' ); ?></h3>
    <p><?php _e( 'We are unable to import these posts or pages automatically:', 'wp-ultimate-exercise' ); ?></p>
    <?php
    foreach( $easyexercise_exercises['problem'] as $post_id ) {
        $post = get_post( $post_id );
        echo ucfirst( $post->post_type ) . ' - <a href="' . get_permalink( $post_id ) . '">' . $post->post_title . '</a><br/>';
    }
    ?>
<?php } ?>
</div>