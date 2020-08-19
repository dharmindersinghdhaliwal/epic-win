jQuery(document).ready(function() {
    jQuery('.wpuep-twitter').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { twitter: true },
            buttons: {
                twitter: {
                    count: jQuery(btn).data('layout'),
                    lang: wpuep_sharing_buttons.twitter_lang
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpuep-facebook').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { facebook: true },
            buttons: {
                facebook: {
                    action: 'like',
                    layout: jQuery(btn).data('layout'),
                    share: jQuery(btn).data('share'),
                    lang: wpuep_sharing_buttons.facebook_lang
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpuep-google').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { googlePlus: true },
            buttons: {
                googlePlus: {
                    size: jQuery(btn).data('layout'),
                    annotation: jQuery(btn).data('annotation'),
                    lang: wpuep_sharing_buttons.google_lang
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpuep-pinterest').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { pinterest: true },
            buttons: {
                pinterest: {
                    media: jQuery(btn).data('media'),
                    description: jQuery(btn).data('description'),
                    config: jQuery(btn).data('layout')
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false,
            click: function(api, options) {
                api.openPopup('pinterest');
            }
        });
    });

    jQuery('.wpuep-stumbleupon').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { stumbleupon: true },
            buttons: {
                stumbleupon: {
                    layout: jQuery(btn).data('layout')
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });

    jQuery('.wpuep-linkedin').each(function(index, elem) {
        var btn = jQuery(elem);
        btn.sharrre({
            share: { linkedin: true },
            buttons: {
                linkedin: {
                    counter: jQuery(btn).data('layout')
                }
            },
            enableHover: false,
            enableCounter: false,
            enableTracking: false
        });
    });
});