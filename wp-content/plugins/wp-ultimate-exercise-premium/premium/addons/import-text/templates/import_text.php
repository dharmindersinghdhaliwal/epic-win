<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Add New Exercise from text', 'wp-ultimate-exercise' );?></h2>
    <p><?php _e( 'This is function is still in beta version. Any feedback is welcome!', 'wp-ultimate-exercise' ); ?></p>
    <h3><?php _e( 'Plain Text Exercise Input', 'wp-ultimate-exercise' );?></h3>
    <p><?php _e( 'Paste your exercise in this input field.', 'wp-ultimate-exercise' );?></p>
    <textarea id="input-exercise"></textarea>
    <h3><?php _e( 'Define Exercise Regions', 'wp-ultimate-exercise' );?></h3>
    <p><?php _e( 'Highlight a section in your exercise and click the corresponding button to define it.', 'wp-ultimate-exercise' );?></p>
    <table id="define-regions-container" class="import-table">
        <tbody>
        <tr>
            <td>
                <ul id="regions-buttons">
                    <li><button id="region-title" class="regions-button"><?php _e( 'Title', 'wp-ultimate-exercise' );?></button></li>
                    <li><button id="region-description" class="regions-button"><?php _e( 'Description', 'wp-ultimate-exercise' );?></button></li>
                    <li><button id="region-ingredients" class="regions-button"><?php _e( 'Ingredients', 'wp-ultimate-exercise' );?></button></li>
                    <li><button id="region-instructions" class="regions-button"><?php _e( 'Instructions', 'wp-ultimate-exercise' );?></button></li>
                    <li><button id="region-notes" class="regions-button"><?php _e( 'Notes', 'wp-ultimate-exercise' );?></button></li>
                </ul>
            </td>
            <td>
                <div id="regions-text"></div>
            </td>
        </tr>
        </tbody>
    </table>
    <form name="import_exercise" id="import_exercise" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="action" value="post" />
        <?php wp_nonce_field( 'exercise_submit', 'submitexercise' ); ?>
        <input type="hidden" name="exercise_meta_box_nonce" value="<?php echo wp_create_nonce('exercise'); ?>" />

    <h3><?php _e( 'General', 'wp-ultimate-exercise' );?></h3>
    <table class="form-table" class="import-table">
        <tbody>
        <tr>
            <th scope="row"><?php _e( 'Title', 'wp-ultimate-exercise' ); ?></th>
            <td>
                <input type="text" name="exercise_title" id="exercise_title" />
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e( 'Description', 'wp-ultimate-exercise' ); ?></th>
            <td>
                <textarea name="exercise_description" id="exercise_description" rows="4"></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e( 'Notes', 'wp-ultimate-exercise' ); ?></th>
            <td>
                <textarea name="exercise_notes" id="exercise_notes" rows="4"></textarea>
            </td>
        </tr>
        </tbody>
    </table>
    <h3><?php _e( 'Ingredients', 'wp-ultimate-exercise' );?></h3>
        <p><strong><?php _e( 'Tip', 'wp-ultimate-exercise' ); ?>:</strong> <?php _e( 'Lines starting with a ! will be starting a new group.', 'wp-ultimate-exercise' ); ?></p>
    <table id="define-ingredient-lines" class="import-table">
        <tbody>
        <tr>
            <td>
                <textarea name="raw_exercise_ingredients" id="raw_exercise_ingredients"></textarea>
            </td>
            <td>
                <ul id="raw_exercise_ingredients_lines">
                </ul>
            </td>
        </tr>
        </tbody>
    </table>

    </table>
    <h3><?php _e( 'Ingredient Details', 'wp-ultimate-exercise' );?></h3>
    <p><strong><?php _e( 'Tip', 'wp-ultimate-exercise' ); ?>:</strong> <?php _e( 'Only unit aliases defined in the settings will be recognized.', 'wp-ultimate-exercise' ); ?></p>
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

    <h3><?php _e( 'Instructions', 'wp-ultimate-exercise' );?></h3>
    <p><strong><?php _e( 'Tip', 'wp-ultimate-exercise' ); ?>:</strong> <?php _e( 'Lines starting with a ! will be starting a new group.', 'wp-ultimate-exercise' ); ?></p>
    <select id="exercise_instructions_separate_type">
        <option value="empty_line"><?php _e( 'Separate instructions with an empty line', 'wp-ultimate-exercise' );?></option>
        <option value="every_line"><?php _e( 'Every line is a separate instruction', 'wp-ultimate-exercise' );?></option>
    </select>
    <table id="define-instruction-lines" class="import-table">
        <tbody>
        <tr>
            <td>
                <textarea name="raw_exercise_instructions" id="raw_exercise_instructions"></textarea>
            </td>
            <td>
                <ol id="raw_exercise_instructions_lines">
                </ol>
            </td>
        </tr>
        </tbody>
    </table>
    <div id="exercise_instructions_output"></div>
        <?php submit_button( __( 'Add Exercise', 'wp-ultimate-exercise' ) ); ?>
    </form>
</div>