<?php
$nutritional = $exercise->nutritional();

$has_nutritional_information = false;
$main_info = false;
$sub_info = false;

foreach( $this->fields as $field => $unit ) {
    if( isset( $nutritional[$field] ) && trim( $nutritional[$field] ) !== '' ) {
        $$field = $nutritional[$field];

        if( isset( $this->daily[$field] ) ) {
            $perc_field = $field . '_perc';
            $$perc_field = round( floatval( $$field ) / $this->daily[$field] * 100 );
        }

        // Flags to know what to output
        $has_nutritional_information = true;
        if( in_array( $field, array( 'fat', 'cholesterol', 'sodium', 'potassium', 'carbohydrate', 'protein' ) ) ) {
            $main_info = true;
        } else if( in_array( $field, array( 'vitamin_a', 'vitamin_c', 'calcium', 'iron' ) ) ) {
            $sub_info = true;
        }
    }
}

if( $has_nutritional_information ) {

    // Calculate calories if not set
    if( !isset( $calories ) ) {
        $proteins = isset( $protein ) ? $protein : 0;
        $carbs = isset( $carbohydrate ) ? $carbohydrate : 0;
        $fat_calories = isset( $fat ) ? round( floatval( $fat ) * 9 ) : 0;

        $calories = ( ( $proteins + $carbs ) * 4 ) + $fat_calories;
    }
?>
    <div class="wpuep-nutrition-label">
        <div class="nutrition-title"><?php _e( 'Nutrition Facts', 'wp-ultimate-exercise' ); ?></div>
        <div class="nutrition-exercise"><?php echo $exercise->title(); ?></div>
        <div class="nutrition-line nutrition-line-big"></div>
        <div class="nutrition-serving"><?php _e( 'Amount Per Serving', 'wp-ultimate-exercise' ); ?></div>
        <div class="nutrition-item">
            <span class="nutrition-main"><strong><?php _e( 'Calories', 'wp-ultimate-exercise' ); ?></strong> <?php echo $calories; ?></span>
            <?php if( isset( $fat ) ) { ?>
            <span class="nutrition-percentage"><?php echo __( 'Calories from Fat', 'wp-ultimate-exercise' ) . ' ' . ( round( floatval( $fat ) * 9 ) ); ?></span>
            <?php } ?>
        </div>
<?php if( $main_info ) { ?>
        <div class="nutrition-line"></div>
        <div class="nutrition-item">
            <span class="nutrition-percentage"><strong><?php _e( '% Daily Value', 'wp-ultimate-exercise' ); ?>*</strong></span>
        </div>
        <?php if( isset( $fat ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><strong><?php _e( 'Total Fat', 'wp-ultimate-exercise' ); ?></strong> <?php echo $fat; ?>g</span>
            <span class="nutrition-percentage"><strong><?php echo $fat_perc; ?>%</strong></span>
        </div>
            <?php if( isset( $saturated_fat ) ) { ?>
            <div class="nutrition-sub-item">
                <span class="nutrition-sub"><?php _e( 'Saturated Fat', 'wp-ultimate-exercise' ); ?> <?php echo $saturated_fat; ?>g</span>
                <span class="nutrition-percentage"><strong><?php echo $saturated_fat_perc; ?>%</strong></span>
            </div>
            <?php } ?>
            <?php if( isset( $trans_fat ) ) { ?>
            <div class="nutrition-sub-item">
                <span class="nutrition-sub"><?php _e( 'Trans Fat', 'wp-ultimate-exercise' ); ?> <?php echo $trans_fat; ?>g</span>
            </div>
            <?php } ?>
            <?php if( isset( $polyunsaturated_fat ) ) { ?>
            <div class="nutrition-sub-item">
                <span class="nutrition-sub"><?php _e( 'Polyunsaturated Fat', 'wp-ultimate-exercise' ); ?> <?php echo $polyunsaturated_fat; ?>g</span>
            </div>
            <?php } ?>
            <?php if( isset( $monounsaturated_fat ) ) { ?>
            <div class="nutrition-sub-item">
                <span class="nutrition-sub"><?php _e( 'Monounsaturated Fat', 'wp-ultimate-exercise' ); ?> <?php echo $monounsaturated_fat; ?>g</span>
            </div>
            <?php } ?>
        <?php } ?>
        <?php if( isset( $cholesterol ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><strong><?php _e( 'Cholesterol', 'wp-ultimate-exercise' ); ?></strong> <?php echo $cholesterol; ?>mg</span>
            <span class="nutrition-percentage"><strong><?php echo $cholesterol_perc; ?>%</strong></span>
        </div>
        <?php } ?>
        <?php if( isset( $sodium ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><strong><?php _e( 'Sodium', 'wp-ultimate-exercise' ); ?></strong> <?php echo $sodium; ?>mg</span>
            <span class="nutrition-percentage"><strong><?php echo $sodium_perc; ?>%</strong></span>
        </div>
        <?php } ?>
        <?php if( isset( $potassium ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><strong><?php _e( 'Potassium', 'wp-ultimate-exercise' ); ?></strong> <?php echo $potassium; ?>mg</span>
            <span class="nutrition-percentage"><strong><?php echo $potassium_perc; ?>%</strong></span>
        </div>
        <?php } ?>
        <?php if( isset( $carbohydrate ) ) { ?>
            <div class="nutrition-item">
                <span class="nutrition-main"><strong><?php _e( 'Total Carbohydrates', 'wp-ultimate-exercise' ); ?></strong> <?php echo $carbohydrate; ?>g</span>
                <span class="nutrition-percentage"><strong><?php echo $carbohydrate_perc; ?>%</strong></span>
            </div>
            <?php if( isset( $fiber ) ) { ?>
            <div class="nutrition-sub-item">
                <span class="nutrition-sub"><?php _e( 'Dietary Fiber', 'wp-ultimate-exercise' ); ?> <?php echo $fiber; ?>g</span>
                <span class="nutrition-percentage"><strong><?php echo $fiber_perc; ?>%</strong></span>
            </div>
            <?php } ?>
            <?php if( isset( $sugar ) ) { ?>
            <div class="nutrition-sub-item">
                <span class="nutrition-sub"><?php _e( 'Sugars', 'wp-ultimate-exercise' ); ?> <?php echo $sugar; ?>g</span>
            </div>
            <?php } ?>
        <?php } ?>
        <?php if( isset( $protein ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><strong><?php _e( 'Protein', 'wp-ultimate-exercise' ); ?></strong> <?php echo $protein; ?>g</span>
            <span class="nutrition-percentage"><strong><?php echo $protein_perc; ?>%</strong></span>
        </div>
        <?php } ?>
<?php } ?>
<?php if( $sub_info ) { ?>
        <div class="nutrition-line nutrition-line-big"></div>
        <?php if( isset( $vitamin_a ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><?php _e( 'Vitamin A', 'wp-ultimate-exercise' ); ?></span>
            <span class="nutrition-percentage"><?php echo $vitamin_a; ?>%</span>
        </div>
        <?php } ?>
        <?php if( isset( $vitamin_c ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><?php _e( 'Vitamin C', 'wp-ultimate-exercise' ); ?></span>
            <span class="nutrition-percentage"><?php echo $vitamin_c; ?>%</span>
        </div>
        <?php } ?>
        <?php if( isset( $calcium ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><?php _e( 'Calcium', 'wp-ultimate-exercise' ); ?></span>
            <span class="nutrition-percentage"><?php echo $calcium; ?>%</span>
        </div>
        <?php } ?>
        <?php if( isset( $iron ) ) { ?>
        <div class="nutrition-item">
            <span class="nutrition-main"><?php _e( 'Iron', 'wp-ultimate-exercise' ); ?></span>
            <span class="nutrition-percentage"><?php echo $iron; ?>%</span>
        </div>
        <?php } ?>
<?php } ?>
        <div class="nutrition-warning">
            * <?php _e( 'Percent Daily Values are based on a 2000 calorie diet. ', 'wp-ultimate-exercise' ); ?>
        </div>
    </div>
<?php } else {
    // Doesn't have nutritional information
    _e( 'There is no Nutrition Label for this exercise yet.', 'wp-ultimate-exercise' );
} ?>