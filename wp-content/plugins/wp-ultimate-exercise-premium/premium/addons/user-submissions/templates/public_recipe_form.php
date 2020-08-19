<?php $required_fields = WPUltimateExercise::option( 'user_submission_required_fields', array() ); ?>
<div id="wpuep_user_submission_form" class="postbox">
    <form id="new_exercise" name="new_exercise" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="exercise_id" value="<?php echo $exercise->ID(); ?>" />
        <div class="exercise-title-container">
            <p>
                <label for="title"><?php _e( 'Exercise title', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'title', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label><br />
                <input type="text" id="title" value="<?php echo isset( $_POST['title'] ) ? $_POST['title'] : $exercise->title();  ?>" size="20" name="title" />
            </p>
        </div>

<?php if( !is_user_logged_in() ) { ?>
        <div class="exercise-author-container">
            <p>
                <label for="exercise-author"><?php _e( 'Your name', 'wp-ultimate-exercise' ); ?><?php if( in_array( 'exercise-author', $required_fields ) ) echo '<span class="wpuep-required">*</span>'; ?></label><br />
                <input type="text" id="exercise-author" value="<?php echo isset( $_POST['exercise-author'] ) ? $_POST['exercise-author'] : $exercise->author();  ?>" size="50" name="exercise-author" />
            </p>
        </div>
<?php } ?>
        <div class="exercise-image-container">
<?php $has_image = $exercise->image_ID() > 0 ? true : false; ?>
<?php if ( !current_user_can( 'upload_files' ) || WPUltimateExercise::option( 'user_submission_use_media_manager', '1' ) != '1' ) { ?>
            <p>
                <label for="exercise-thumbnail"><?php _e( 'Featured image', 'wp-ultimate-exercise' ); ?></label><br />
                <?php if( $has_image ) { ?>
                <img src="<?php echo $exercise->image_url( 'thumbnail' ); ?>" class="exercise_thumbnail" /><br/>
                <?php } ?>
                <input class="exercise_thumbnail_image button" type="file" id="exercise-thumbnail" value="" size="50" name="exercise-thumbnail" />
            </p>
<?php } else { ?>
            <p>
                <input name="exercise_thumbnail" class="exercise_thumbnail_image" type="hidden" value="<?php echo $exercise->image_ID(); ?>" />
                <input class="exercise_thumbnail_add_image button button<?php if($has_image) { echo ' wpuep-hide'; } ?>" rel="<?php echo $exercise->ID(); ?>" type="button" value="<?php _e( 'Add Featured Image', 'wp-ultimate-exercise' ); ?>" />
                <input class="exercise_thumbnail_remove_image button<?php if(!$has_image) { echo ' wpuep-hide'; } ?>" type="button" value="<?php _e('Remove Featured Image', 'wp-ultimate-exercise' ); ?>" />
                <br /><img src="<?php echo $exercise->image_url( 'thumbnail' ); ?>" class="exercise_thumbnail" />
            </p>
<?php } ?>
        </div>
        <div class="exercise-tags-container">
            <p class="taxonomy-select-boxes">
<?php
        $select_fields = array();
        $multiselect = WPUltimateExercise::option( 'exercise_tags_user_submissions_multiselect', '1' ) == '1' ? true : false;

        $taxonomies = WPUltimateExercise::get()->tags();
        unset( $taxonomies['ingredient'] );

        $args = array(
            'echo' => 0,
            'orderby' => 'NAME',
            'hide_empty' => 0,
            'hierarchical' => 1,
        );

        $hide_tags = WPUltimateExercise::option( 'user_submission_hide_tags', array() );

        foreach( $taxonomies as $taxonomy => $options ) {
            if( !in_array( $taxonomy, $hide_tags ) ) {
                $args['show_option_none'] = $multiselect ? '' : $options['labels']['singular_name'];
                $args['taxonomy'] = $taxonomy;
                $args['name'] = 'exercise-' . $taxonomy;

                $select_fields[$taxonomy] = array(
                    'label' => $options['labels']['singular_name'],
                    'dropdown' => wp_dropdown_categories( $args ),
                );
            }
        }

        if( WPUltimateExercise::option( 'exercise_tags_user_submissions_categories', '0' ) == '1' ) {
            $args['show_option_none'] = $multiselect ? '' : __( 'Category', 'wp-ultimate-exercise' );
            $args['taxonomy'] = 'category';
            $args['name'] = 'exercise-category';

            $exclude = WPUltimateExercise::option( 'user_submission_hide_category_terms', array() );
            $args['exclude'] = implode( ',', $exclude );

            $select_fields['category'] = array(
                'label' => __( 'Category', 'wp-ultimate-exercise' ),
                'dropdown' => wp_dropdown_categories( $args ),
            );
        }

        if( WPUltimateExercise::option( 'exercise_tags_user_submissions_tags', '0' ) == '1' ) {
            $args['show_option_none'] = $multiselect ? '' : __( 'Tag', 'wp-ultimate-exercise' );
            $args['taxonomy'] = 'post_tag';
            $args['name'] = 'exercise-post_tag';

            $exclude = WPUltimateExercise::option( 'user_submission_hide_tag_terms', array() );
            $args['exclude'] = implode( ',', $exclude );

            $select_fields['post_tag'] = array(
                'label' => __( 'Tag', 'wp-ultimate-exercise' ),
                'dropdown' => wp_dropdown_categories( $args ),
            );
        }

        foreach( $select_fields as $taxonomy => $select_field ) {

            // Multiselect
            if( $multiselect ) {
                preg_match( "/<select[^>]+>/i", $select_field['dropdown'], $select_field_match );
                if( isset( $select_field_match[0] ) ) {
                    $select_multiple = preg_replace( "/name='([^']+)/i", "$0[]' data-placeholder='".$select_field['label']."' multiple='multiple", $select_field_match[0] );
                    $select_field['dropdown'] = str_ireplace( $select_field_match[0], $select_multiple, $select_field['dropdown'] );
                }
            }

            // Selected terms
            $terms = wp_get_post_terms( $exercise->ID(), $taxonomy, array( 'fields' => 'ids' ) );
            foreach( $terms as $term_id ) {
                $select_field['dropdown'] = str_replace( ' value="'. $term_id .'"', ' value="'. $term_id .'" selected="selected"', $select_field['dropdown'] );
            }

            echo $select_field['dropdown'];
        }
?>
            </p>
        </div>
<?php
        $wpuep_user_submission = true;
        include( WPUltimateExercise::get()->coreDir . '/helpers/exercise_form.php' );
?>
<?php if( WPUltimateExercise::option( 'user_submissions_use_security_question', '' ) == '1' ) { ?>
    <div class="security-question-container">
        <h4><?php _e( 'Security Question', 'wp-ultimate-exercise' ); ?><span class="wpuep-required">*</span></h4>
        <p>
            <label for="security-answer"><?php echo WPUltimateExercise::option( 'user_submissions_security_question', '4 + 7 =' ); ?></label> <input type="text" id="security-answer" value="<?php echo isset( $_POST['security-answer'] ) ? $_POST['security-answer'] : '';  ?>" size="25" name="security-answer" />
        </p>
    </div>
<?php } ?>
        <p align="right">
            <?php if( WPUltimateExercise::option( 'user_submission_preview_button', '1') == '1' ) { ?>
            <input type="submit" value="<?php _e( 'Preview', 'wp-ultimate-exercise' ); ?>" id="preview" name="preview" />
            <?php } ?>
            <input type="submit" value="<?php _e( 'Submit', 'wp-ultimate-exercise' ); ?>" id="submit" name="submit" />
        </p>
        <input type="hidden" name="action" value="post" />
        <?php echo wp_nonce_field( 'exercise_submit', 'submitexercise' ); ?>
    </form>
</div>