jQuery(document).ready(function() {
    jQuery('.reset-exercise-rating').on('click', function() {
        if(confirm(wpuep_user_ratings.confirm)) {
            var exercise = jQuery(this).data('exercise');

            var data = {
                action: 'reset_exercise_rating',
                security: wpuep_user_ratings.nonce,
                exercise: exercise
            };

            jQuery.post(wpuep_user_ratings.ajax_url, data, function(out) {
                window.location.reload();
            });
        }
    });
});