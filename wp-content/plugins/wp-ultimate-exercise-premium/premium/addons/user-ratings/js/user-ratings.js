jQuery(document).ready(function() {
    if(jQuery('.exercise-tooltip.vote-attention').length) {
        jQuery('.exercise-tooltip.vote-attention').mouseenter();
        jQuery('.vote-attention-message').show();
        jQuery('.user-rating-stats').hide();

        setTimeout(function() {
            jQuery('.exercise-tooltip.vote-attention').mouseleave();

            setTimeout(function() {
                jQuery('.vote-attention-message').hide();
                jQuery('.user-rating-stats').show();
            }, 500);
        }, 5000);
    }

    jQuery('.wpuep-container .user-star-rating.user-can-vote li').hover(function() {
        jQuery(this).addClass('selecting-rating');
        jQuery(this).prevAll().addClass('selecting-rating');
    }, function() {
        jQuery(this).removeClass('selecting-rating');
        jQuery(this).prevAll().removeClass('selecting-rating');
    });

    jQuery('.wpuep-container .user-star-rating.user-can-vote li').click(function() {
        var stars = jQuery(this).data('star-value');
        var rating_stars = jQuery(this).parent('ul');
        var exercise = rating_stars.data('exercise-id');

        var data = {
            action: 'rate_exercise',
            security: wpuep_user_ratings.nonce,
            stars: stars,
            exercise: exercise
        };

        jQuery.post(wpuep_user_ratings.ajax_url, data, function(rating) {
            var tooltip = rating_stars.nextAll('.exercise-tooltip-content:first');

            tooltip.find('.user-rating-votes').text(rating.votes);
            tooltip.find('.user-rating-rating').text(rating.rating);
            tooltip.find('.user-rating-current-rating').text(stars);

            rating_stars.find('li').each(function(index, elem) {
                jQuery(elem)
                    .removeClass('half-star')
                    .removeClass('full-star')
                    .removeClass('selecting-rating');

                if(index < rating.stars) {
                    jQuery(elem).addClass('full-star');
                } else if(index == rating.stars && rating.half_star == true) {
                    jQuery(elem).addClass('half-star');
                }
            });
        }, 'json');
    });
});