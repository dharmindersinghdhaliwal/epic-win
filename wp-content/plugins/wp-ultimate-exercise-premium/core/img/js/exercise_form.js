//TODO Refactor this asap.
jQuery(document).ready(function() {

    /*
     * Add shortcode buttons
     */
    jQuery('#insert-exercise-shortcode').on('click', function(){
        wpuep_add_to_editor('[exercise]');
    });

    jQuery('#insert-nutrition-shortcode').on('click', function(){
        wpuep_add_to_editor('[nutrition-label]');
    });

    var text_editor = jQuery('textarea#content');
    function wpuep_add_to_editor(text) {
        if( !tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
            var current = text_editor.val();
            text_editor.val(current + text);
        } else {
            tinyMCE.execCommand('mceInsertContent', false, text);
        }
    }

    // Remove searchable exercise div
    if(text_editor.length > 0) {
        var content = text_editor.val();
        content = content.replace( /<div class="wpuep-searchable-exercise"[^<]*<\/div>/g, function( match ) { return ''; });
        content = content.replace( /\[wpuep-searchable-exercise\][^\[]*\[\/wpuep-searchable-exercise\]/g, function( match ) { return ''; });
        text_editor.val(content);
    }


    /*
     * Do not allow removal of first ingredient/instruction
     */
    jQuery('#exercise-ingredients tr.ingredient:first').find('span.ingredients-delete').hide();
    jQuery('#exercise-instructions tr.instruction:first').find('span.instructions-delete').hide();

    /*
     * Exercise Star rating
     * */
    var exercise_rating = jQuery('select#exercise_rating');
    if(exercise_rating.length == 1)
    {
        var current_rating = exercise_rating.find('option:selected').val();

        var star_full = '<img src="'+ wpuep_exercise_form.coreUrl +'/img/star.png" width="15" height="14" />';
        var star_empty = '<img src="'+ wpuep_exercise_form.coreUrl +'/img/star_grey.png" width="15" height="14" />';

        var rating_selection = '<div id="exercise_rating_star_selection">';

        for(var i=1; i <= 5; i++)
        {
            rating_selection += '<span class="star" id="exercise-star-'+i+'" data-star="'+i+'">';

            if(current_rating >= i) {
                rating_selection += star_full;
            } else {
                rating_selection += star_empty;
            }

            rating_selection += '</span>';
        }

        rating_selection += '</div>';

        exercise_rating
            .hide()
            .after(rating_selection);


        jQuery(document).on('click', '#exercise_rating_star_selection .star', function() {
            var star = jQuery(this);

            star.html(star_full).prevAll().html(star_full);
            star.nextAll().html(star_empty);

            exercise_rating.val(star.data("star"));
        });
    }


    /*
     * Ingredient Groups
     * */

    calculateIngredientGroups();

    jQuery('#ingredients-add-group').on('click', function(e){
        e.preventDefault();
        addExerciseIngredientGroup();
    });

    var calculateIngredientGroupsTimer;
    jQuery('.ingredient-group-label').on('input', function() {
        window.clearTimeout(calculateIngredientGroupsTimer);
        calculateIngredientGroupsTimer = window.setTimeout(function() {
            calculateIngredientGroups();
        }, 500);
    });

    function addExerciseIngredientGroup()
    {
        var last_group = jQuery('#exercise-ingredients tr.ingredient-group-stub')
        var last_row = jQuery('#exercise-ingredients tr:last')
        var clone_group = last_group.clone(true);

        clone_group
            .insertAfter(last_row)
            .removeClass('ingredient-group-stub')
            .addClass('ingredient-group');

        jQuery('.ingredient-groups-disabled').hide();
        jQuery('.ingredient-groups-enabled').show();

        calculateIngredientGroups();
    }

    jQuery('.ingredient-group-delete').on('click', function(){
        jQuery(this).parents('tr').remove();

        calculateIngredientGroups();
    });

    function calculateIngredientGroups()
    {
        if(jQuery('.ingredient-group').length == 1) {
            jQuery('#exercise-ingredients .ingredient .ingredients_group').val('');

            jQuery('.ingredient-groups-disabled').show();
            jQuery('.ingredient-groups-enabled').hide();
        } else {
            jQuery('#exercise-ingredients tr.ingredient').each(function(i, row){
                var group = jQuery(row).prevAll('.ingredient-group:first').find('.ingredient-group-label').val();

                if(group === undefined) {
                    group = jQuery('.ingredient-group-first').find('.ingredient-group-label').val();
                }

                jQuery(row).find('.ingredients_group').val(group);
            });

            jQuery('.ingredient-groups-disabled').hide();
            jQuery('.ingredient-groups-enabled').show();
        }
    }

    /*
     * Instruction Groups
     * */
    calculateInstructionGroups();

    jQuery('#instructions-add-group').on('click', function(e){
        e.preventDefault();
        addExerciseInstructionGroup();
    });

    var calculateInstructionGroupsTimer;
    jQuery('.instruction-group-label').on('input', function() {
        window.clearTimeout(calculateInstructionGroupsTimer);
        calculateInstructionGroupsTimer = window.setTimeout(function() {
            calculateInstructionGroups();
        }, 500);
    });

    function addExerciseInstructionGroup()
    {
        var last_group = jQuery('#exercise-instructions tr.instruction-group-stub')
        var last_row = jQuery('#exercise-instructions tr:last')
        var clone_group = last_group.clone(true);

        clone_group
            .insertAfter(last_row)
            .removeClass('instruction-group-stub')
            .addClass('instruction-group');

        jQuery('.instruction-groups-disabled').hide();
        jQuery('.instruction-groups-enabled').show();

        calculateInstructionGroups();
    }

    jQuery('.instruction-group-delete').on('click', function(){
        jQuery(this).parents('tr').remove();

        calculateInstructionGroups();
    });

    function calculateInstructionGroups()
    {
        if(jQuery('.instruction-group').length == 1) {
            jQuery('#exercise-instructions .instruction .instructions_group').val('');

            jQuery('.instruction-groups-disabled').show();
            jQuery('.instruction-groups-enabled').hide();
        } else {
            jQuery('#exercise-instructions tr.instruction').each(function(i, row){
                var group = jQuery(row).prevAll('.instruction-group:first').find('.instruction-group-label').val();

                if(group === undefined) {
                    group = jQuery('.instruction-group-first').find('.instruction-group-label').val();
                }

                jQuery(row).find('.instructions_group').val(group);
            });

            jQuery('.instruction-groups-disabled').hide();
            jQuery('.instruction-groups-enabled').show();
        }
    }

    /*
     * Exercise ingredients
     * */
    jQuery('#exercise-ingredients tbody').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.sort-handle',
        update: function() {
            addExerciseIngredientOnTab();
            calculateIngredientGroups();
            updateIngredientIndex();
        }
    });

    // Hide AutoSuggest box on TAB or click
    jQuery('#exercise-ingredients').on('keydown', function(e) {
        var keyCode = e.keyCode || e.which;

        if (keyCode == 9) {
            jQuery('ul.ac_results').hide();
        }
    });
    jQuery('#exercise-ingredients').on('click', function() {
        jQuery('ul.ac_results').hide();
    });

    jQuery('.ingredients-delete').on('click', function(){
        jQuery(this).parents('tr').remove();
        addExerciseIngredientOnTab();
        updateIngredientIndex();
    });

    jQuery('#ingredients-add').on('click', function(e){
        e.preventDefault();
        addExerciseIngredient();
    });

    function addExerciseIngredient()
    {
        var nbr_ingredients = jQuery('#exercise-ingredients tr.ingredient').length;
        var last_row = jQuery('#exercise-ingredients tr:last')
        var last_ingredient = jQuery('#exercise-ingredients tr.ingredient:last')
        var clone_ingredient = last_ingredient.clone(true);

        clone_ingredient
            .insertAfter(last_row)
            .find('input, select').val('')
            .attr('name', function(index, name) {
                return name.replace(/(\d+)/, nbr_ingredients);
            })
            .attr('id', function(index, id) {
                return id.replace(/(\d+)/, nbr_ingredients);
            })
            .parent().find('input.ingredients_name')
            .attr('onfocus', function(index, onfocus) {
                return onfocus.replace(/(\d+)/, nbr_ingredients);
            });

        last_ingredient.find('input').attr('placeholder','');
        clone_ingredient.find('span.ingredients-delete').show();

        addExerciseIngredientOnTab();

        jQuery('#exercise-ingredients tr:last .ingredients_amount').focus();
        calculateIngredientGroups();
    }

    addExerciseIngredientOnTab();
    function addExerciseIngredientOnTab()
    {
        jQuery('#exercise-ingredients .ingredients_notes')
            .unbind('keydown')
            .last()
            .bind('keydown', function(e) {
                var keyCode = e.keyCode || e.which;

                if (keyCode == 9) {
                    e.preventDefault();
                    addExerciseIngredient();
                }
            });
    }

    function updateIngredientIndex()
    {
        jQuery('#exercise-ingredients tr.ingredient').each(function(i) {
            jQuery(this)
                .find('input')
                .attr('name', function(index, name) {
                    return name.replace(/(\d+)/, i);
                })
                .attr('id', function(index, id) {
                    return id.replace(/(\d+)/, i);
                })
                .parent().find('input.ingredients_name')
                .attr('onfocus', function(index, onfocus) {
                    return onfocus.replace(/(\d+)/, i);
                });
        });
    }

    /*
     * Exercise instructions
     * */
    jQuery('#exercise-instructions tbody').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.sort-handle',
        update: function() {
            addExerciseInstructionOnTab();
            calculateInstructionGroups();
            updateInstructionIndex();
        }
    });

    jQuery('.instructions-delete').on('click', function(){
        jQuery(this).parents('tr').remove();
        addExerciseInstructionOnTab();
        updateInstructionIndex();
    });

    jQuery('#instructions-add').on('click', function(e){
        e.preventDefault();
        addExerciseInstruction();
    });

    function addExerciseInstruction()
    {
        var nbr_instructions = jQuery('#exercise-instructions tr.instruction').length;
        var new_instruction = jQuery('#exercise-instructions tr.instruction:last').clone(true);

        new_instruction
            .insertAfter('#exercise-instructions tr:last')
            .find('textarea').val('')
            .attr('name', function(index, name) {
                return name.replace(/(\d+)/, nbr_instructions);
            })
            .attr('id', function(index, id) {
                return id.replace(/(\d+)/, nbr_instructions);
            });

        new_instruction
            .find('.exercise_instructions_remove_image').addClass('wpuep-hide')

        new_instruction
            .find('.exercise_instructions_add_image').removeClass('wpuep-hide')

        new_instruction
            .find('.exercise_instructions_image').val('')

        new_instruction
            .find('.exercise_instructions_thumbnail').attr('src', wpuep_exercise_form.coreUrl + '/img/image_placeholder.png')

        new_instruction
            .find('.exercise_instructions_image')
            .attr('name', function(index, name) {
                return name.replace(/(\d+)/, nbr_instructions);
            });

        new_instruction
            .find('.instructions_group')
            .attr('name', function(index, name) {
                return name.replace(/(\d+)/, nbr_instructions);
            })
            .attr('id', function(index, id) {
                return id.replace(/(\d+)/, nbr_instructions);
            });


        new_instruction.find('span.instructions-delete').show();
        addExerciseInstructionOnTab();

        jQuery('#exercise-instructions tr:last textarea').focus();
        calculateInstructionGroups();

    }

    addExerciseInstructionOnTab();
    function addExerciseInstructionOnTab()
    {
        jQuery('#exercise-instructions textarea')
            .unbind('keydown')
            .last()
            .bind('keydown', function(e) {
                var keyCode = e.keyCode || e.which;

                if (keyCode == 9 && e.shiftKey == false) {
                    var last_focused = jQuery('#exercise-instructions tr:last').find('textarea').is(':focus')

                    if(last_focused == true) {
                        e.preventDefault();
                        addExerciseInstruction();
                    }

                }
            });
    }

    function updateInstructionIndex()
    {
        jQuery('#exercise-instructions tr.instruction').each(function(i) {
            jQuery(this)
                .find('textarea')
                .attr('name', function(index, name) {
                    return name.replace(/(\d+)/, i);
                })
                .attr('id', function(index, id) {
                    return id.replace(/(\d+)/, i);
                });

            jQuery(this)
                .find('.exercise_instructions_image')
                .attr('name', function(index, name) {
                    return name.replace(/(\d+)/, i);
                });

            jQuery(this)
                .find('.instructions_group')
                .attr('name', function(index, name) {
                    return name.replace(/(\d+)/, i);
                })
                .attr('id', function(index, id) {
                    return id.replace(/(\d+)/, i);
                });
        });
    }

    // TODO To user submission js
    jQuery('.exercise_thumbnail_add_image').on('click', function(e) {

        e.preventDefault();

        var button = jQuery(this);

        image = button.siblings('.exercise_thumbnail_image');
        preview = button.siblings('.exercise_thumbnail');

        if(typeof wp.media == 'function') {
            var custom_uploader = wp.media({
                title: 'Insert Media',
                button: {
                    text: 'Add featured image'
                },
                multiple: false
            })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery(preview).attr('src', attachment.url);
                    jQuery(image).val(attachment.id).trigger('change');
                })
                .open();
        } else { //fallback
            post_id = button.attr('rel');

            tb_show(button.attr('value'), 'wp-admin/media-upload.php?post_id='+post_id+'&type=image&TB_iframe=1');

            window.send_to_editor = function(html) {
                img = jQuery('img', html);
                imgurl = img.attr('src');
                classes = img.attr('class');
                id = classes.replace(/(.*?)wp-image-/, '');
                image.val(id).trigger('change');
                preview.attr('src', imgurl);
                tb_remove();
            }
        }

    });

    jQuery('.exercise_thumbnail_remove_image').on('click', function(e) {
        e.preventDefault();

        var button = jQuery(this);

        button.siblings('.exercise_thumbnail_image').val('').trigger('change');
        button.siblings('.exercise_thumbnail').attr('src', wpuep_exercise_form.coreUrl + '/img/image_placeholder.png');
    });

    jQuery('.exercise_thumbnail_image').on('change', function() {
        var image = jQuery(this);
        if(image.val() == '') {
            image.siblings('.exercise_thumbnail_add_image').removeClass('wpuep-hide');
            image.siblings('.exercise_thumbnail_remove_image').addClass('wpuep-hide');
        } else {
            image.siblings('.exercise_thumbnail_remove_image').removeClass('wpuep-hide');
            image.siblings('.exercise_thumbnail_add_image').addClass('wpuep-hide');
        }
    });

    jQuery('.exercise_instructions_add_image').on('click', function(e) {

        e.preventDefault();

        var button = jQuery(this);

        image = button.siblings('.exercise_instructions_image');
        preview = button.siblings('.exercise_instructions_thumbnail');

        if(typeof wp.media == 'function') {
            var custom_uploader = wp.media({
                title: 'Insert Media',
                button: {
                    text: 'Add instruction image'
                },
                multiple: false
            })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery(preview).attr('src', attachment.url);
                    jQuery(image).val(attachment.id).trigger('change');
                })
                .open();
        } else { //fallback
            post_id = button.attr('rel');

            tb_show(button.attr('value'), 'wp-admin/media-upload.php?post_id='+post_id+'&type=image&TB_iframe=1');

            window.send_to_editor = function(html) {
                img = jQuery('img', html);
                imgurl = img.attr('src');
                classes = img.attr('class');
                id = classes.replace(/(.*?)wp-image-/, '');
                image.val(id).trigger('change');
                preview.attr('src', imgurl);
                tb_remove();
            }
        }

    });

    jQuery('.exercise_instructions_remove_image').on('click', function(e) {
        e.preventDefault();

        var button = jQuery(this);

        button.siblings('.exercise_instructions_image').val('').trigger('change');
        button.siblings('.exercise_instructions_thumbnail').attr('src', wpuep_exercise_form.coreUrl + '/img/image_placeholder.png');
    });

    jQuery('.exercise_instructions_image').on('change', function() {
        var image = jQuery(this);
        if(image.val() == '') {
            image.siblings('.exercise_instructions_add_image').removeClass('wpuep-hide');
            image.siblings('.exercise_instructions_remove_image').addClass('wpuep-hide');
        } else {
            image.siblings('.exercise_instructions_remove_image').removeClass('wpuep-hide');
            image.siblings('.exercise_instructions_add_image').addClass('wpuep-hide');
        }
    });

    jQuery('#wpuep-insert-exercise').on('click', function() {
        var shortcode = '[ultimate-exercise id=';

        shortcode += jQuery('#wpuep-exercise').find('option:selected').val();
        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tinyMCE.activeEditor.windowManager.close();
    });

    /* 
     * Image preview settings fields
     */
    jQuery('.wpuep-preview-select').on('change', function() {
        var prefix = jQuery(this).siblings('.wpuep-preview-img').children('img').attr('alt');

        if( prefix.split('-').length > 1 ) {
            prefix = prefix.split('-')[0] + '-';
        }

        var old_img = jQuery(this).siblings('.wpuep-preview-img').children('img').attr('src');
        var new_img = prefix + jQuery(this).val() + '.jpg';
        var old_img_file = old_img.split('/');

        old_img_file = old_img_file[old_img_file.length - 1];

        new_img = old_img.replace(old_img_file, new_img);

        jQuery(this).siblings('.wpuep-preview-img').children('img').attr('src', new_img)

    });

    /*
     * Image upload settings fields
     * @TODO: This pretty much repeats the instruction image code, we should combine them
     */
    jQuery('.wpuep-file-upload').on('click', function(e) {

        e.preventDefault();

        var button = jQuery(this);

        preview = button.siblings('img');
        fieldname = preview.attr('class');
        image = button.siblings('.' + fieldname + '_image');

        if(typeof wp.media == 'function') {
            var custom_uploader = wp.media({
                title: 'Insert Media',
                button: {
                    text: 'Add image'
                },
                multiple: false
            })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery(preview).attr('src', attachment.url);
                    jQuery(image).val(attachment.id);
                })
                .open();
        } else { //fallback
            post_id = button.attr('rel');

            tb_show(button.attr('value'), 'wp-admin/media-upload.php?post_id='+post_id+'&type=image&TB_iframe=1');

            window.send_to_editor = function(html) {
                img = jQuery('img', html);
                imgurl = img.attr('src');
                classes = img.attr('class');
                id = classes.replace(/(.*?)wp-image-/, '');
                image.val(id).trigger('change');
                preview.attr('src', imgurl);
                tb_remove();
            }
        }

        button.addClass('wpuep-hide');
        button.siblings('.wpuep-file-remove').removeClass('wpuep-hide');

    });

    jQuery('.wpuep-file-remove').on('click', function(e) {
        e.preventDefault();

        var button = jQuery(this);

        preview = button.siblings('img');
        fieldname = preview.attr('class');

        button.siblings('.' + fieldname + '_image').val('');
        button.siblings('.' + fieldname).attr('src', '');

        button.siblings('.wpuep-file-upload').removeClass('wpuep-hide');
        button.addClass('wpuep-hide');
    });

});