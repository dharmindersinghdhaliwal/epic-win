var WPUltimateEPostEgrid = WPUltimateEPostEgrid || {};

WPUltimateEPostEgrid.post_type = null;
WPUltimateEPostEgrid.filter_possible = false;
WPUltimateEPostEgrid.premium_features_used = [];

WPUltimateEPostEgrid.initForm = function() {
    // General selects
    jQuery('.wpupg-select2').select2wpupg({
        width: '100%'
    });

    // Datepicker
    jQuery('#wpupg_rules').find('.wpupg-date').datepicker();

    // Sliders
    var slider_args = {
        start: 1,
        step: 1,
        range: {
            'min': 1,
            'max': 500
        },
        behaviour: 'tap-drag',
        format: wNumb({
            decimals: 0
        })
    };

    var posts_per_page = jQuery('#wpupg_pagination_pages_posts_per_page');
    slider_args.start = posts_per_page.val();
    jQuery('#wpupg_pagination_pages_posts_per_page_slider').noUiSlider(slider_args).Link('lower').to(posts_per_page);

    var initial_posts = jQuery('#wpupg_pagination_load_more_initial_posts');
    slider_args.start = initial_posts.val();
    jQuery('#wpupg_pagination_load_more_initial_posts_slider').noUiSlider(slider_args).Link('lower').to(initial_posts);

    var posts_on_click = jQuery('#wpupg_pagination_load_more_posts_on_click');
    slider_args.start = posts_on_click.val();
    jQuery('#wpupg_pagination_load_more_posts_on_click_slider').noUiSlider(slider_args).Link('lower').to(posts_on_click);

    slider_args = {
        start: 0,
        step: 1,
        range: {
            'min': 0,
            'max': 50
        },
        behaviour: 'tap-drag',
        format: wNumb({
            decimals: 0
        })
    };

    var isotope_margin_vertical = jQuery('#wpupg_isotope_filter_style_margin_vertical');
    slider_args.start = isotope_margin_vertical.val();
    jQuery('#wpupg_isotope_filter_style_margin_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_margin_vertical);

    var isotope_margin_horizontal = jQuery('#wpupg_isotope_filter_style_margin_horizontal');
    slider_args.start = isotope_margin_horizontal.val();
    jQuery('#wpupg_isotope_filter_style_margin_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_margin_horizontal);

    var isotope_padding_vertical = jQuery('#wpupg_isotope_filter_style_padding_vertical');
    slider_args.start = isotope_padding_vertical.val();
    jQuery('#wpupg_isotope_filter_style_padding_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_padding_vertical);

    var isotope_padding_horizontal = jQuery('#wpupg_isotope_filter_style_padding_horizontal');
    slider_args.start = isotope_padding_horizontal.val();
    jQuery('#wpupg_isotope_filter_style_padding_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_padding_horizontal);

    var isotope_border_width = jQuery('#wpupg_isotope_filter_style_border_width');
    slider_args.start = isotope_border_width.val();
    jQuery('#wpupg_isotope_filter_style_border_width_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_border_width);

    
    var pagination_margin_vertical = jQuery('#wpupg_pagination_style_margin_vertical');
    slider_args.start = pagination_margin_vertical.val();
    jQuery('#wpupg_pagination_style_margin_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_margin_vertical);

    var pagination_margin_horizontal = jQuery('#wpupg_pagination_style_margin_horizontal');
    slider_args.start = pagination_margin_horizontal.val();
    jQuery('#wpupg_pagination_style_margin_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_margin_horizontal);

    var pagination_padding_vertical = jQuery('#wpupg_pagination_style_padding_vertical');
    slider_args.start = pagination_padding_vertical.val();
    jQuery('#wpupg_pagination_style_padding_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_padding_vertical);

    var pagination_padding_horizontal = jQuery('#wpupg_pagination_style_padding_horizontal');
    slider_args.start = pagination_padding_horizontal.val();
    jQuery('#wpupg_pagination_style_padding_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_padding_horizontal);

    var pagination_border_width = jQuery('#wpupg_pagination_style_border_width');
    slider_args.start = pagination_border_width.val();
    jQuery('#wpupg_pagination_style_border_width_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimateEPostEgrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_border_width);

    // Color Pickers
    jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker({
            change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
        });
    jQuery('#wpupg_isotope_filter_style_background_active_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_background_hover_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_active_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_hover_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_active_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_hover_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); }
    });

    jQuery('#wpupg_pagination_style_background_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_background_active_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_background_hover_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_text_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_text_active_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_text_hover_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_border_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_border_active_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_border_hover_color').wpColorPicker({
        change: function () { WPUltimateEPostEgrid.changedPaginationStyle(); }
    });

    // Initial State
    WPUltimateEPostEgrid.changedPostType();
    WPUltimateEPostEgrid.changedLimitPosts();
    WPUltimateEPostEgrid.initRules();
    WPUltimateEPostEgrid.changedFilterType();
    WPUltimateEPostEgrid.changedMultiselect();
    WPUltimateEPostEgrid.changedIsotopeFilterStyle();
    WPUltimateEPostEgrid.changedIsotopeFilterText();
    WPUltimateEPostEgrid.changedLayoutMode();
    WPUltimateEPostEgrid.changedPaginationStyle();
    WPUltimateEPostEgrid.changedPaginationType();
    WPUltimateEPostEgrid.changedPaginationText();

    // Events
    jQuery('#wpupg_post_types').on('change', function() { WPUltimateEPostEgrid.changedPostType(); });
    jQuery('#wpupg_limit_posts').on('change', function() { WPUltimateEPostEgrid.changedLimitPosts(); });
    jQuery('#wpupg_add_rule').on('click', function(e) { e.preventDefault(); WPUltimateEPostEgrid.addRule(); });
    jQuery('.wpupg_rule_delete').on('click', function(e) { e.preventDefault(); WPUltimateEPostEgrid.removeRule(jQuery(this).parents('tr').attr('data-rule')); });
    jQuery('.wpupg_rule_field').on('change', function(e) { WPUltimateEPostEgrid.changedRuleField(jQuery(this).parents('tr').attr('data-rule')); });
    jQuery('.rule_container_wpupg_general_date').find('select').on('change', function(e) { WPUltimateEPostEgrid.changedDateRule(jQuery(this).parents('tr').attr('data-rule')); });
    jQuery('.wpupg-date').on('change', function(e) { WPUltimateEPostEgrid.changedDateRule(jQuery(this).parents('tr').attr('data-rule')); });
    jQuery('#wpupg_filter_type').on('change', function() { WPUltimateEPostEgrid.changedFilterType(); });
    jQuery('#wpupg_filter_multiselect').on('change', function() { WPUltimateEPostEgrid.changedMultiselect(); });
    jQuery('#wpupg_layout_mode').on('change', function() { WPUltimateEPostEgrid.changedLayoutMode(); });
    jQuery('#wpupg_pagination_type').on('change', function() { WPUltimateEPostEgrid.changedPaginationType(); });
    jQuery('#wpupg_pagination_load_more_button_text').on('change keyup', function() { WPUltimateEPostEgrid.changedPaginationText(); });

    isotope_margin_vertical.on('change', function() { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); });
    isotope_margin_horizontal.on('change', function() { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); });
    isotope_padding_vertical.on('change', function() { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); });
    isotope_padding_horizontal.on('change', function() { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); });
    isotope_border_width.on('change', function() { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); });
    jQuery('#wpupg_isotope_filter_style_alignment').on('change', function() { WPUltimateEPostEgrid.changedIsotopeFilterStyle(); });
    jQuery('#wpupg_isotope_filter_style_all_button_text').on('change keyup', function() { WPUltimateEPostEgrid.changedIsotopeFilterText(); });

    pagination_margin_vertical.on('change', function() { WPUltimateEPostEgrid.changedPaginationStyle(); });
    pagination_margin_horizontal.on('change', function() { WPUltimateEPostEgrid.changedPaginationStyle(); });
    pagination_padding_vertical.on('change', function() { WPUltimateEPostEgrid.changedPaginationStyle(); });
    pagination_padding_horizontal.on('change', function() { WPUltimateEPostEgrid.changedPaginationStyle(); });
    pagination_border_width.on('change', function() { WPUltimateEPostEgrid.changedPaginationStyle(); });
    jQuery('#wpupg_pagination_style_alignment').on('change', function() { WPUltimateEPostEgrid.changedPaginationStyle(); });

    jQuery('.wpupg-filter-isotope-term').hover(function() {
        if(!jQuery(this).hasClass('active')) {
            var background_hover_color = jQuery('#wpupg_isotope_filter_style_background_hover_color').wpColorPicker('color');
            var text_hover_color = jQuery('#wpupg_isotope_filter_style_text_hover_color').wpColorPicker('color');
            var border_hover_color = jQuery('#wpupg_isotope_filter_style_border_hover_color').wpColorPicker('color');

            jQuery(this)
                .css('background-color', background_hover_color)
                .css('color', text_hover_color)
                .css('border-color', border_hover_color)
            ;
        }
    }, function() {
        if(!jQuery(this).hasClass('active')) {
            var background_color = jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker('color');
            var text_color = jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker('color');
            var border_color = jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker('color');

            jQuery(this)
                .css( 'background-color', background_color )
                .css( 'color', text_color)
                .css( 'border-color', border_color )
            ;
        }
    });

    jQuery('.wpupg-pagination-term').hover(function() {
        if(!jQuery(this).hasClass('active')) {
            var background_hover_color = jQuery('#wpupg_pagination_style_background_hover_color').wpColorPicker('color');
            var text_hover_color = jQuery('#wpupg_pagination_style_text_hover_color').wpColorPicker('color');
            var border_hover_color = jQuery('#wpupg_pagination_style_border_hover_color').wpColorPicker('color');

            jQuery(this)
                .css('background-color', background_hover_color)
                .css('color', text_hover_color)
                .css('border-color', border_hover_color)
            ;
        }
    }, function() {
        if(!jQuery(this).hasClass('active')) {
            var background_color = jQuery('#wpupg_pagination_style_background_color').wpColorPicker('color');
            var text_color = jQuery('#wpupg_pagination_style_text_color').wpColorPicker('color');
            var border_color = jQuery('#wpupg_pagination_style_border_color').wpColorPicker('color');

            jQuery(this)
                .css( 'background-color', background_color )
                .css( 'color', text_color)
                .css( 'border-color', border_color )
            ;
        }
    });
};

WPUltimateEPostEgrid.changedPostType = function() {
    var post_type = jQuery('#wpupg_post_types').find(':selected').val();

    jQuery('.wpupg_filter_taxonomy_container').hide();
    var container = jQuery('#wpupg_filter_taxonomy_' + post_type + '_container');

    if(container.length > 0) {
        jQuery('#wpupg_no_taxonomies').hide();
        jQuery('#wpupg_form_filter').show();
        container.show();
        WPUltimateEPostEgrid.filter_possible = true;
    } else {
        jQuery('#wpupg_no_taxonomies').show();
        jQuery('#wpupg_form_filter').hide();
        WPUltimateEPostEgrid.filter_possible = false;
    }

    WPUltimateEPostEgrid.changedFilterType();
};

WPUltimateEPostEgrid.changedLimitPosts = function() {
    if(jQuery('#wpupg_limit_posts').is(':checked')) {
        WPUltimateEPostEgrid.checkPremiumFeatures('limit_posts', true);
        jQuery('#wpupg_meta_box_limit_posts').show();
    } else {
        WPUltimateEPostEgrid.checkPremiumFeatures('limit_posts', false);
        jQuery('#wpupg_meta_box_limit_posts').hide();
    }
};

WPUltimateEPostEgrid.initRules = function() {
    jQuery('#wpupg_rules').find('tr').each(function() {
        var rule = jQuery(this);

        rule
            .find('select')
            .select2wpupg({
                width: '100%'
            })
        ;

        WPUltimateEPostEgrid.changedRuleField(rule.attr('data-rule'));
    });
};

WPUltimateEPostEgrid.addRule = function() {
    var rules = jQuery('#wpupg_rules');
    var rule_id = rules.find('tr:last').attr('data-rule');
    rule_id = rule_id == undefined ? 1 : parseInt(rule_id) + 1;

    var new_rule = jQuery('#wpupg_rule_placeholder').find('tr').first().clone(true);

    new_rule
        .appendTo(rules)
        .attr('id', function(index, val) {
            return val.replace(/(\d+)/, rule_id);
        })
        .attr('data-rule', function(index, val) {
            return val.replace(/(\d+)/, rule_id);
        })
        .find('select, input')
        .attr('name', function(index, val) {
            return val.replace(/(\d+)/, rule_id);
        })
        .attr('id', function(index, val) {
            return val.replace(/(\d+)/, rule_id);
        })
    ;

    new_rule
        .find('select')
        .select2wpupg({
            width: '100%'
        })
    ;

    new_rule.find('.wpupg-date').datepicker();

    WPUltimateEPostEgrid.changedRuleField(rule_id);
    WPUltimateEPostEgrid.changedDateRule(rule_id);
};

WPUltimateEPostEgrid.removeRule = function(id) {
    if(id != '0') {
        jQuery('#wpupg_rule_' + id).remove();
    }
};

WPUltimateEPostEgrid.changedRuleField = function(id) {
    var row = jQuery('#wpupg_rule_' + id);
    var field = jQuery('#wpupg_limit_posts_rule_field_' + id).val();
    field = field.replace('|','_');



    row.find('.rule_container').hide();
    row.find('.rule_container_' + field).show();
};

WPUltimateEPostEgrid.changedDateRule = function(id) {
    var condition = jQuery('#wpupg_limit_posts_rule_values_wpupg_general_date_condition_' + id).find(':selected').val();
    var date = jQuery('#wpupg_limit_posts_rule_values_wpupg_general_date_date_' + id).val();
    jQuery('#wpupg_limit_posts_rule_values_wpupg_general_date_' + id).val(condition + ';' + date);
};

WPUltimateEPostEgrid.changedFilterType = function() {
    var filter_type = jQuery('#wpupg_filter_type').find(':selected').val();

    // Hide & Show Filter Settings
    if(filter_type == 'none') {
        jQuery('#wpupg_form_filter').find('tr').not('.wpupg_no_filter').hide();
    } else {
        jQuery('#wpupg_form_filter').find('tr').not('.wpupg_no_filter').show();
    }

    // Premium only filters
    if(filter_type == 'dropdown') {
        WPUltimateEPostEgrid.checkPremiumFeatures('filter_type', true);
    } else {
        WPUltimateEPostEgrid.checkPremiumFeatures('filter_type', false);
    }

    // Hide & Show Filter Styles
    jQuery('#wpupg_meta_box_isotope_filter').hide();
    jQuery('#wpupg_meta_box_dropdown_filter').hide();

    if(WPUltimateEPostEgrid.filter_possible) {
        jQuery('#wpupg_meta_box_' + filter_type + '_filter').show();
    }
};

WPUltimateEPostEgrid.changedMultiselect = function() {
    var multiselect = jQuery('#wpupg_filter_multiselect').is(':checked');

    if(multiselect) {
        WPUltimateEPostEgrid.checkPremiumFeatures('multiselect', true);
        jQuery('.wpupg_multiselect').show();
    } else {
        WPUltimateEPostEgrid.checkPremiumFeatures('multiselect', false);
        jQuery('.wpupg_multiselect').hide();
    }
};

WPUltimateEPostEgrid.changedIsotopeFilterStyle = function() {
    var margin_vertical = parseInt(jQuery('#wpupg_isotope_filter_style_margin_vertical').val());
    var margin_horizontal = parseInt(jQuery('#wpupg_isotope_filter_style_margin_horizontal').val());
    var padding_vertical = parseInt(jQuery('#wpupg_isotope_filter_style_padding_vertical').val());
    var padding_horizontal = parseInt(jQuery('#wpupg_isotope_filter_style_padding_horizontal').val());
    var border_width = parseInt(jQuery('#wpupg_isotope_filter_style_border_width').val());

    var background_color = jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker('color');
    var text_color = jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker('color');
    var border_color = jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker('color');

    var alignment = jQuery('#wpupg_isotope_filter_style_alignment').find(':selected').val();
    jQuery('#wpupg_filter_preview_isotope_filter_style').css('text-align', alignment);

    jQuery('.wpupg-filter-isotope-term')
        .css( 'margin', margin_vertical + 'px ' + margin_horizontal + 'px' )
        .css( 'padding', padding_vertical + 'px ' + padding_horizontal + 'px' )
        .css( 'background-color', background_color )
        .css( 'color', text_color )
        .css( 'border', border_width + 'px solid ' + border_color )
    ;

    var background_active_color = jQuery('#wpupg_isotope_filter_style_background_active_color').wpColorPicker('color');
    var text_active_color = jQuery('#wpupg_isotope_filter_style_text_active_color').wpColorPicker('color');
    var border_active_color = jQuery('#wpupg_isotope_filter_style_border_active_color').wpColorPicker('color');

    jQuery('.wpupg-filter-isotope-term.active')
        .css( 'background-color', background_active_color )
        .css( 'color', text_active_color )
        .css( 'border', border_width + 'px solid ' + border_active_color )
    ;
};

WPUltimateEPostEgrid.changedIsotopeFilterText = function() {
    var text = jQuery('#wpupg_isotope_filter_style_all_button_text').val();
    jQuery('#wpupg_isotope_filter_style_all_button_text_preview').text(text);
};


WPUltimateEPostEgrid.changedLayoutMode = function() {
    var layout_mode = jQuery('#wpupg_layout_mode').find(':selected').val();

    if(layout_mode == 'masonry') {
        jQuery('.wpupg_masonry').show();
    } else {
        jQuery('.wpupg_masonry').hide();
    }
};

WPUltimateEPostEgrid.changedPaginationStyle = function() {
    var margin_vertical = parseInt(jQuery('#wpupg_pagination_style_margin_vertical').val());
    var margin_horizontal = parseInt(jQuery('#wpupg_pagination_style_margin_horizontal').val());
    var padding_vertical = parseInt(jQuery('#wpupg_pagination_style_padding_vertical').val());
    var padding_horizontal = parseInt(jQuery('#wpupg_pagination_style_padding_horizontal').val());
    var border_width = parseInt(jQuery('#wpupg_pagination_style_border_width').val());

    var background_color = jQuery('#wpupg_pagination_style_background_color').wpColorPicker('color');
    var text_color = jQuery('#wpupg_pagination_style_text_color').wpColorPicker('color');
    var border_color = jQuery('#wpupg_pagination_style_border_color').wpColorPicker('color');

    var alignment = jQuery('#wpupg_pagination_style_alignment').find(':selected').val();
    jQuery('.wpupg_filter_preview_pagination_style').css('text-align', alignment);

    jQuery('.wpupg-pagination-term')
        .css( 'margin', margin_vertical + 'px ' + margin_horizontal + 'px' )
        .css( 'padding', padding_vertical + 'px ' + padding_horizontal + 'px' )
        .css( 'background-color', background_color )
        .css( 'color', text_color )
        .css( 'border', border_width + 'px solid ' + border_color )
    ;

    var background_active_color = jQuery('#wpupg_pagination_style_background_active_color').wpColorPicker('color');
    var text_active_color = jQuery('#wpupg_pagination_style_text_active_color').wpColorPicker('color');
    var border_active_color = jQuery('#wpupg_pagination_style_border_active_color').wpColorPicker('color');

    jQuery('.wpupg-pagination-term.active')
        .css( 'background-color', background_active_color )
        .css( 'color', text_active_color )
        .css( 'border', border_width + 'px solid ' + border_active_color )
    ;
};

WPUltimateEPostEgrid.changedPaginationType = function() {
    var pagination_type = jQuery('#wpupg_pagination_type').find(':selected').val();

    // Hide & Show Pagination Settings
    jQuery('#wpupg_form_pagination').find('tbody').not('.wpupg_pagination_' + pagination_type).not('.wpupg_pagination_none').hide();
    jQuery('#wpupg_form_pagination').find('tbody.wpupg_pagination_' + pagination_type).show();

    // Premium only pagination
    if(pagination_type == 'load_more') {
        WPUltimateEPostEgrid.checkPremiumFeatures('pagination_type', true);
    } else {
        WPUltimateEPostEgrid.checkPremiumFeatures('pagination_type', false);
    }

    // Hide & Show Pagination Styles
    if(pagination_type != 'none') {
        jQuery('#wpupg_meta_box_pagination_style').show().find('.wpupg_filter_preview').hide();
        jQuery('#wpupg_filter_preview_pagination_style_' + pagination_type).show();
    } else {
        jQuery('#wpupg_meta_box_pagination_style').hide();
    }
};

WPUltimateEPostEgrid.changedPaginationText = function() {
    var text = jQuery('#wpupg_pagination_load_more_button_text').val();
    jQuery('#wpupg_filter_preview_pagination_style_load_more').find('.wpupg-pagination-term').text(text);
};

WPUltimateEPostEgrid.checkPremiumFeatures = function(feature, using) {
    if(using) {
        WPUltimateEPostEgrid.premium_features_used.push(feature);
    } else {
        // Remove feature from array
        WPUltimateEPostEgrid.premium_features_used = jQuery.grep(WPUltimateEPostEgrid.premium_features_used, function(value) {
                return value != feature;
            });
    }

    if(WPUltimateEPostEgrid.premium_features_used.length > 0) {
        jQuery('#wpupg_meta_box_premium_only').show();
    } else {
        jQuery('#wpupg_meta_box_premium_only').hide();
    }
};

jQuery(document).ready(function($) {
    if($('#wpupg_meta_box_data_source').length > 0) {
        WPUltimateEPostEgrid.initForm();
    }
});