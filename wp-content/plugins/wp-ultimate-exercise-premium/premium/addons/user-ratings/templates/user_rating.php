<?php
    $rating = WPUEP_User_Ratings::get_exercise_rating( $exercise->ID() );

    $classes = '';
    if( WPUEP_User_Ratings::is_current_user_allowed_to_vote() ) {
        $classes .= ' user-can-vote';

        $current_user_rating = WPUEP_User_Ratings::get_current_user_rating_for( $exercise->ID() );

        if( !$current_user_rating && WPUltimateExercise::option( 'user_ratings_vote_attention', '1' ) == '1' ) {
            $classes .= ' vote-attention';
        }
    }
?>
<ul data-exercise-id="<?php echo $exercise->ID(); ?>" class="user-star-rating exercise-tooltip<?php echo $classes; ?>">
    <?php
    for( $i = 1; $i <= 5; $i++ )
    {
        if( $i <= $rating['stars'] ) {
            $class = 'full-star';
        } else if( $i-1 == $rating['stars'] && $rating['half_star'] == true ) {
            $class = 'half-star';
        }  else {
            $class = '';
        }

        echo '<li data-star-value="'.$i.'" class="'.$class.'">'.$i.'</li>';
    }
    ?>
</ul>
<div class="exercise-tooltip-content">
    <div class="user-rating-stats">
        <?php _e( 'Votes', 'wp-ultimate-exercise' ); ?>: <span class="user-rating-votes"><?php echo $rating['votes']; ?></span><br/>
        <?php _e( 'Rating', 'wp-ultimate-exercise' ); ?>: <span class="user-rating-rating"><?php echo $rating['rating']; ?></span><br/>
        <?php if( isset( $current_user_rating ) ) { _e( 'You', 'wp-ultimate-exercise' ); ?>: <span class="user-rating-current-rating"><?php echo $current_user_rating; ?></span><?php } ?>
    </div>
    <div class="vote-attention-message">
        <?php _e( 'Rate this exercise!', 'wp-ultimate-exercise' ); ?>
    </div>
</div>