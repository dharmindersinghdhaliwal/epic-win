/* Confirm deletion */
function confirmAction(){
    var confirmed = confirm(epicAdmin.fieldDeleteConfirm);
    return confirmed;
}

jQuery(document).ready(function($) {

    /* show/toggle choices */
    $('.epic-inputtype select').change(function(){
        val = $(this).val();
        if (val == 'select' || val == 'radio' || val == 'checkbox' || val == 'chosen_select' || val == 'chosen_multiple') {
            $(this).parent().parent().parent().find('.epic-choices').show();
        } else {
            $(this).parent().parent().parent().find('.epic-choices').hide();
        }
    });

    // Check the availability of tipsy plugin before calling
    if($.isFunction($.fn.tipsy)){
        /* Tooltips */
        $('.epic-tooltip').tipsy({
            trigger: 'hover',
            gravity: 's',
            offset: 4
        });
        
        /* Tooltip in table */
        $('.epic-tooltip2').tipsy({
            trigger: 'hover',
            gravity: 'w',
            offset: 4
        });
        
        /* Tooltip for icon */
        $('.epic-tooltip3').tipsy({
            trigger: 'hover',
            offset: 4
        });
    }

    
	
    /* Toggle ADD form */
    $('.epic-toggle').click(function(){
        $('.epic-add-form').toggle();
    });
	
    $('.epic-add-form-cancel').click(function(){
        $('.epic-add-form').hide();
    });
	
    /* Toggle inline edit 
    $('.epic-edit').click(function(){
        if ($(this).parent().parent().next('tr.epic-editor').is(':hidden')) {
            $(this).parent().parent().next('tr.epic-editor').show();
        } else {
            $(this).parent().parent().next('tr.epic-editor').hide();
        }
        $(this).parent().parent().parent().find('tr.epic-editor').not($(this).parent().parent().next('tr.epic-editor')).hide();
        return false;
    });*/

    /* Toggle inline edit */
    $('.epic-edit').click(function(){

        jQuery('#epic_all_update_processing').hide();
        jQuery('.epic_single_update_processing').hide();

        var editor = $(this).parent().parent().find('div.epic-editor');
        if (editor.is(':hidden')) {
            editor.show();
        } else {
            editor.hide();
        }
        $(this).parent().parent().parent().find('div.epic-editor').not($(this).parent().parent().find('div.epic-editor')).hide();
        return false;
    });
	
    $('.epic-inline-cancel').click(function(){
        $(this).parent().parent().parent().hide();
    });
	
    /* Toggle icon edit */
    $('.epic-inline-icon-epic-edit').click(function(e){
        e.preventDefault();
        if ($(this).parent().parent().find('.epic-icons').is(':hidden')) {
            $(this).parent().parent().find('.epic-icons').show();
        } else {
            $(this).parent().parent().find('.epic-icons').hide();
        }
    });
	
    /* Switch field type */
    $('#up_type').change(function(){
        if ($(this).val() == 'separator') {
            $('#up_social,#up_can_edit,#up_can_hide,#up_tooltip,#up_field, #up_allow_html, #up_private, #up_required,#up_meta,#up_edit_by_user_role,#up_edit_by_user_role_list,#up_help_text').parent().parent().hide();
            //$('#up_name').parent().parent().after($('#up_meta_custom').parent().parent());
            //$('#up_name').parent().parent().after($('#up_meta').parent().parent());


            $('#up_meta_custom').parent().parent().show().find('label').html(epicAdmin.separatorKey);  
            $('#up_name').parent().parent().show().find('label').html(epicAdmin.separatorLabel);  
            $('#up_meta_custom').parent().find('i').attr('original-title',epicAdmin.separatorHelp);        
            $('.epic-icons-holder').hide();
        } else {
            $('#up_social,#up_can_edit,#up_can_hide,#up_tooltip,#up_field, #up_allow_html, #up_private, #up_required,#up_meta,#up_edit_by_user_role,#up_edit_by_user_role_list,#up_help_text').parent().parent().show();
            $('#up_meta_custom').parent().parent().hide().find('label').html(epicAdmin.profileKey);
            $('#up_name').parent().parent().hide().find('label').html(epicAdmin.profileLabel); 
            $('#up_meta_custom').parent().find('i').attr('original-title',epicAdmin.profileHelp);  
            $('.epic-icons-holder').show();

        }
    });
	
    $('#up_meta').change(function(){
        if ($(this).val() == '1') {
            $('#up_meta_custom').parent().parent().show();
        } else {
            $('#up_meta_custom').parent().parent().hide();
            $('#up_meta_custom').val('');
        }
    });
	
    $('#up_field').change(function(){
        if ($('#up_private').val() == '1' || $.inArray( $(this).val() , epicAdmin.customFileFieldTypes ) != '-1') {
//        if ($(this).val() == 'fileupload' || $('#up_private').val() == '1' || $.inArray( $(this).val() , epicAdmin.customFileFieldTypes ) != '-1') {
            $('#up_show_in_register').parent().parent().hide();
            $('#up_show_in_register').val(0);
        } else {
            $('#up_show_in_register').parent().parent().show();
        }
        
        // Remove social and allow html attributes for fileupload fields
        if($(this).val() == 'fileupload' || $.inArray( $(this).val() , epicAdmin.customFileFieldTypes ) != '-1'){
            $('#up_social').parent().parent().hide();
            $('#up_social').val(0);
            
            $('#up_allow_html').parent().parent().hide();
            $('#up_allow_html').val(0);
            
        }else{
            $('#up_social').parent().parent().show();
            $('#up_allow_html').parent().parent().show();
        }


        if ($.inArray( $(this).val() , epicAdmin.customFileFieldTypes ) != '-1'){
            $("#up_required").parent().parent().hide();
        }else{
            $("#up_required").parent().parent().show();
        }
    });
	
    $('#captcha_plugin').change(function(){
        if ($(this).val() == 'recaptcha' || $(this).val() == 'nocaptcharecaptcha')
        {
            $('#recaptcha_public_key_holder').show();
            $('#recaptcha_private_key_holder').show();
            $('#recaptcha_theme_holder').show();
        }
        else
        {
            $('#recaptcha_public_key_holder').hide();
            $('#recaptcha_private_key_holder').hide();
            $('#recaptcha_theme_holder').hide();
        }
		
        if($(this).val() == 'none')
        {
            $('#captcha_label_holder').hide();
        }
        else
        {
            $('#captcha_label_holder').show();
        }
		
    });
    
	
    $('#captcha_plugin').trigger('change');
	
	
	
    $('#up_private').change(function(){
        $('#up_field').trigger('change');
    });
	
	
    if($('#epic-shortcode-popup-tabs').length > 0)
    {
        $('#epic-shortcode-popup-tabs').tabify();
    }

    // Validate automtic login and email confirmation on user selected password setting
    $('#set_password').change(function(){
        if ($(this).val() == '1')
        {
            $('#automatic_login_holder').show();

            if($('#automatic_login').val() == '0'){
                $('#set_email_confirmation_holder').show();
                $('#profile_approval_status_holder').show();
            }
        }
        else
        {
            $('#automatic_login_holder').hide();
            $('#set_email_confirmation').val('0');
            $('#set_email_confirmation_holder').hide();

            
            $('#profile_approval_status').val('0');
            $('#profile_approval_status_holder').hide();
        }

    });

    $('#set_password').trigger('change');

    // Validate email confirmation on automtic login setting
    $('#automatic_login').change(function(){
        if ($(this).val() == '1')
        {
            $('#set_email_confirmation').val('0');
            $('#set_email_confirmation_holder').hide();

            $('#profile_approval_status').val('0');
            $('#profile_approval_status_holder').hide();
        }
        else
        {
            
            $('#set_email_confirmation_holder').show();
            $('#profile_approval_status_holder').show();
        }

    });

    $('#automatic_login').trigger('change');

    // Remove required validation for file upload fields
    $('.epic_edit_field_type').change(function(){
        var fieldId = $(this).attr("id");
		
        userId = fieldId.replace("epic_","").replace("_field","");
        fieldId = "#"+fieldId;

        if($.inArray( $(this).val() , epicAdmin.customFileFieldTypes ) != '-1'){
            $("#epic_"+ userId +"_required").parent().hide();
        }else{
            $("#epic_"+ userId +"_required").parent().show();
        }
        
        
        // Remove social and allow HTML attributes for file upload fields
        if($(this).val() == 'fileupload' || $.inArray( $(this).val() , epicAdmin.customFileFieldTypes ) != '-1'){
            $("#epic_"+ userId +"_social").parent().hide();
            $("#epic_"+ userId +"_social").val(0);
            
            $("#epic_"+ userId +"_allow_html").parent().hide();
            $("#epic_"+ userId +"_allow_html").val(0);
            
        }else{
            $("#epic_"+ userId +"_social").parent().show();
            $("#epic_"+ userId +"_allow_html").parent().show();
        }
    });

    /* Enable user roles list on settings change */
    $('#up_show_to_user_role').change(function(){
        if ($(this).val() == '1') {
            $('#up_show_to_user_role_list').parent().parent().show();
        } else {
            $('#up_show_to_user_role_list').parent().parent().hide();
        }
    });

    $('#up_edit_by_user_role').change(function(){
        if ($(this).val() == '1') {
            $('#up_edit_by_user_role_list').parent().parent().show();
        } else {
            $('#up_edit_by_user_role_list').parent().parent().hide();
        }
    });
    
    $('#up_can_hide').change(function(){
        if ($(this).val() == '5') {
            $('#up_can_hide_role_list').parent().parent().show();
        } else {
            $('#up_can_hide_role_list').parent().parent().hide();
        }
    });

    $('.epic_show_to_user_role').change(function(){
        
        var elementId = $(this).attr("id");
        if ($(this).val() == '1') {
            $('#'+elementId+'_list').parent().parent().show();
        } else {
            
            var elementClass = $('#'+elementId+'_list').attr("class");

            $('.'+elementClass).each(
                function() {
              
                    $(this).attr('checked', false);
                }
                );
            $('#'+elementId+'_list').parent().parent().hide();
        }
    });

    $('.epic_edit_by_user_role').change(function(){

        var elementId = $(this).attr("id");

        if ($(this).val() == '1') {
            $('#'+elementId+'_list').parent().parent().show();
        } else {
            var elementClass = $('#'+elementId+'_list').attr("class");
            $('.'+elementClass).each(
                function() {
                    $(this).attr('checked', false);
                }
                );
            $('#'+elementId+'_list').parent().parent().hide();
        }
    });
    
    $('.epic_can_hide').change(function(){

        var elementId = $(this).attr("id");

        if ($(this).val() == '5') {
            $('#'+elementId+'_role_list').parent().parent().show();
        } else {
            var elementClass = $('#'+elementId+'_role_list').attr("class");
            $('.'+elementClass).each(
                function() {
                    $(this).attr('checked', false);
                }
                );
            $('#'+elementId+'_role_list').parent().parent().hide();
        }
    });

    $('.epic-edit-field-type').change(function(){

        var fieldTypeId = $(this).attr('id');
        var fieldInputId = '#'+ fieldTypeId.replace('_type','_field');
        var fieldMetaId = '#'+ fieldTypeId.replace('_type','_meta');
        var fieldMetaCustomId = '#'+ fieldTypeId.replace('_type','_meta_custom');
        var fieldNameId = '#'+ fieldTypeId.replace('_type','_name');


        var fieldTooltipId = '#'+ fieldTypeId.replace('_type','_tooltip');
        var fieldSocialId = '#'+ fieldTypeId.replace('_type','_social');
        var fieldCanEditId = '#'+ fieldTypeId.replace('_type','_can_edit');
        var fieldAllowHTMLId = '#'+ fieldTypeId.replace('_type','_allow_html');
        var fieldCanHideId = '#'+ fieldTypeId.replace('_type','_can_hide');
        var fieldPrivateId = '#'+ fieldTypeId.replace('_type','_private');
        var fieldRequiredId = '#'+ fieldTypeId.replace('_type','_required');
        var fieldChoicesId = '#'+ fieldTypeId.replace('_type','_choices');
        var fieldPredefinedLoopId = '#'+ fieldTypeId.replace('_type','_predefined_loop');
        var fieldIconId = '#'+ fieldTypeId.replace('_type','_icon');
        var fieldIconsClass = '.'+ fieldTypeId.replace('_type','_icons');

        var itemIds = fieldMetaId + "," +fieldInputId + "," +fieldTooltipId + "," +fieldSocialId + "," +fieldCanEditId 
        + "," +fieldAllowHTMLId + "," +fieldCanHideId + "," +fieldPrivateId + "," +fieldRequiredId + "," +fieldChoicesId 
        + "," +fieldPredefinedLoopId + "," +fieldIconId;

        if ($(this).val() == 'separator') {
            $(fieldMetaCustomId).parent().find('label').html(epicAdmin.separatorKey);  
            $(fieldNameId).parent().find('label').html(epicAdmin.separatorLabel);  

            $(itemIds).attr('disabled','disabled');
            $(fieldIconsClass).attr('disabled','disabled');
            //$(fieldInputId).attr('disabled','disabled');
            //$(fieldMetaId).parent().hide();
            $(itemIds).parent().hide();
            $(fieldIconsClass).parent().parent().hide();
            $(fieldMetaCustomId).val($(fieldMetaId).val());
            

        }else{
            $(fieldMetaCustomId).parent().find('label').html(epicAdmin.profileKey);
            $(fieldNameId).parent().find('label').html(epicAdmin.profileLabel);  
    
            $(itemIds).removeAttr('disabled');
            $(fieldIconsClass).removeAttr('disabled');
            //$(fieldInputId).removeAttr('disabled');
            $(itemIds).parent().show();
            $(fieldIconsClass).parent().parent().show();
            //$(fieldInputId).parent().show();
            
        }
    });

    /*$('.epic-add-form-cancel').click(function(){
        alert("ddd");
        $('#up_show_to_user_role_list').parent().parent().hide();
        $('#up_edit_by_user_role_list').parent().parent().hide();
        $('#epic-custom-field-add').reset();
        document.getElementById("epic-custom-field-add").reset();
    });*/

    $(".epic-add-form-cancel").on("click", function(event){

        $('#up_show_to_user_role_list').parent().parent().hide();
        $('#up_edit_by_user_role_list').parent().parent().hide();

        event.preventDefault();
        $(this).closest('form').get(0).reset();
    

    });

    $(".epic-inline-cancel").on("click", function(event){

        $(this).closest('tr').find('.epic-user-roles-list').parent().hide();

        event.preventDefault();
        $(this).closest('form').get(0).reset();
    

    });
    
    
    $('#epic-update-user-cache').click(function(){
    	
        $('#epic-update-user-cache').data('last_procesed_user','0');
    	
        $('#epic-processing-tag').show();
    	
        $("#epic-update-user-cache").attr("disabled", "disabled");
    	
        update_user_cache(0);
    	
    });
    
    // New Setting Page Tabs 
    
    $('.epic-tab').click(function(){
        if(!$(this).hasClass('active'))
        {
        	// Change Active Class for Tabs 
        	$('.epic-tab').removeClass('active');
        	$(this).addClass('active');
        	
        	// Show relevant field box
        	var content_id = $(this).attr('id').replace('-tab','-content');
        	
        	$('.epic-tab-content').hide();
        	$('#'+content_id).show();
        }
    });
    
    $('.epic-save-options').click(function(){
    	
    	var btn_id = $(this).attr('id');
    	var form_id = btn_id.replace('save-','');
    	form_id = form_id.replace('-tab','-form');
    	
    	$('#'+btn_id).attr("disabled", "disabled");
    	$('#'+btn_id).val(epicAdmin.savingSetting);
    	
    	$.post(
    	        epicAdmin.AdminAjax,
    	        {
    	            'action': 'save_epic_settings',
    	            'data':   $("#"+form_id).serialize(),
    	            'current_tab' : form_id
    	        },
    	        function(response){
    	        	$('#'+btn_id).val(epicAdmin.saveSetting);
    	        	$('#'+btn_id).removeAttr("disabled");
    	        	
    	        	$('#epic-settings-saved').show();
    	        	setTimeout(function(){
                        jQuery("#epic-settings-saved").hide();
                    }, 3000);
    	        	
    	        }
    		);
    });
    
    $('.epic-reset-options').click(function(){
    	
    	var btn_id = $(this).attr('id');
    	var form_id = btn_id.replace('reset-','');
    	var tab_id = form_id; 
    	form_id = form_id.replace('-tab','');
    	
    	$('#'+btn_id).attr("disabled", "disabled");
    	$('#'+btn_id).val(epicAdmin.resettingSetting);
    	
    	$.post(
    	        epicAdmin.AdminAjax,
    	        {
    	            'action': 'reset_epic_settings',
    	            'current_tab' : form_id
    	        },
    	        function(response){
    	        	$('#'+btn_id).val(epicAdmin.resetSetting);
    	        	$('#'+btn_id).removeAttr("disabled");

    	        	window.location = epicAdmin.adminURL + '&reset=' +tab_id;
    	        }
    		);
    });
    
    
    var query_var = [], hash;
    var q = document.URL.split('?')[1];
    if(q != undefined)
    {
        q = q.split('&');
        for(var i = 0; i < q.length; i++){
            hash = q[i].split('=');
            query_var.push(hash[1]);
            query_var[hash[0]] = hash[1];
        }
    }
    if(query_var['reset'] != undefined)
    {
    	$('#'+query_var['reset']).trigger('click');
    	$('#epic-settings-reset').show();
    	setTimeout(function(){
            jQuery("#epic-settings-reset").hide();
        }, 3000);
    }
    

    // Display/hide sub settings for user role selection
    $('#select_user_role_in_registration').click(function(){
        epic_show_hide_user_role_rields();
        
    });
    
    $('.epic-save-options').click(function(){
        epic_show_hide_user_role_rields();
    });

    if(jQuery('#select_user_role_in_registration')){
        epic_show_hide_user_role_rields();
    }

    // Display/hide sub settings for user posts on profiles
    $('#show_recent_user_posts').click(function(){
        epic_show_hide_user_post_fields();        
    });

    $('.epic-save-options').click(function(){
        epic_show_hide_user_post_fields();
    });

    if(jQuery('#show_recent_user_posts')){
        epic_show_hide_user_post_fields();
    }

    // Display/hide sub settings for user profile status
    $('#profile_view_status').click(function(){
        epic_show_hide_user_profile_status_fields();        
    });

    if(jQuery('#profile_view_status')){
        epic_show_hide_user_profile_status_fields();
    }

    //Show hide user roles for view profile based on - Logging in user viewing of other profiles
    $('#users_can_view').change(function(){

        if($(this).val() == '2'){
            $('#choose_roles_for_view_profile').parent().parent().show();
        }else{
            $('#choose_roles_for_view_profile').parent().parent().hide();
        }          
    });

    $('#users_can_view').trigger('change');


    // Update options of custom fields when clicking Update button
    $('.epic-field-update').click(function(){
        epic_update_field_settings($(this),'single');
    });

    $('.epic-all-field-update').click(function(){
        epic_update_field_settings($(this),'all');
    });

    // Create new custom field using AJAX
    $('.epic-field-create').click(function(){
        $('.epic_field_add_msg').remove();
        epic_create_field_settings();
    });


    // Reset custom fields through AJAX
    $('.epic-field-reset').click(function(){
        epic_reset_field_settings();
    });
    
    if(jQuery("#epic-form-customizer-table-data").length > 0){
        $( "#epic-form-customizer-table-data" ).sortable({
            update: function(e,ui){

                var counter = 1;
                jQuery('#epic-form-customizer-table-data li').each(function(index,ele){
                        var id = jQuery(ele).attr('id').replace('value-holder-tr-','');
                        jQuery('#epic_'+id+'_position').val(counter);
                        counter++;
           
                });

                
            },
        });
    }
    
    $('#save-epic-separator-groups-settings').click(function(){
    	
    	var btn_id = $(this).attr('id');
    	var form_id = 'epic-separator-field-groups-settings-form';
    	
    	$('#'+btn_id).attr("disabled", "disabled");
    	$('#'+btn_id).val(epicAdmin.savingSetting);
    	
    	$.post(
    	        epicAdmin.AdminAjax,
    	        {
    	            'action': 'epic_save_separator_field_groups',
    	            'data':   $("#"+form_id).serialize(),
    	        },
    	        function(response){
    	        	$('#'+btn_id).val(epicAdmin.saveSetting);
    	        	$('#'+btn_id).removeAttr("disabled");
    	        	
    	        	$('#epic-settings-saved').show();
    	        	setTimeout(function(){
                        jQuery("#epic-settings-saved").hide();
                    }, 3000);
    	        	
    	        }
    		);
    });

    /* epic Module Settings - Pages and Posts */
    $(".epic-admin-setting-upload-btn").click(function(){
        
        
        var uploadObject = $(this);
        var sendAttachmentMeta = wp.media.editor.send.attachment;

        wp.media.editor.send.attachment = function(props, attachment) {
            $(uploadObject).parent().find('img').remove();
            $(uploadObject).parent().find(".epic-admin-setting-upload-hidden").before("<img class='epic-admin-setting-img-prev' style='width:75px;height:75px' src='"+ attachment.url +"' />");
            $(uploadObject).parent().find(".epic-admin-setting-upload-hidden").val(attachment.url);


            wp.media.editor.send.attachment = sendAttachmentMeta;
        }

        wp.media.editor.open();

        return false;   
    });
});

function update_user_cache(user_id)
{
    jQuery.post(
        epicAdmin.AdminAjax,
        {
            'action': 'update_user_cache',
            'last_user':   user_id
        },
        function(response){
            	
            jQuery('#epic-completed-users').show();
            	
            if(response == 'completed')
            {
                jQuery('#epic-completed-users').hide();
                jQuery('#epic-processing-tag').hide();
                jQuery("#epic-update-user-cache").removeAttr("disabled");
                jQuery("#epic-upgrade-success").show();
                setTimeout(function(){
                    jQuery("#epic-upgrade-success").hide();
                }, 5000);
            }
            else
            {
                jQuery('#epic-completed-users').html(response+epicAdmin.cacheCompletedUsers);
                update_user_cache(response);
            }
        }
        );
}

function epic_show_hide_user_role_rields(){

    if(jQuery('#select_user_role_in_registration').is(':checked')) {
            jQuery('#label_for_registration_user_role_holder').show();
            jQuery('#choose_roles_for_registration_holder').show();
        }else{
            jQuery('#label_for_registration_user_role_holder').hide();
            jQuery('#choose_roles_for_registration_holder').hide();
        }
}

// Display/hide sub settings for user post on profile
function epic_show_hide_user_post_fields(){

    if(jQuery('#show_recent_user_posts').is(':checked')) {
            jQuery('#maximum_allowed_posts_holder').show();
            jQuery('#show_feature_image_posts_holder').show();
        }else{
            jQuery('#maximum_allowed_posts_holder').hide();
            jQuery('#show_feature_image_posts_holder').hide();
        }
}

// Display/hide sub settings for user profile status
function epic_show_hide_user_profile_status_fields(){

    if(jQuery('#profile_view_status').is(':checked')) {
        jQuery('#display_profile_status').parent().parent().show();
    }else{
        jQuery('#display_profile_status').parent().parent().hide();
    }
}

function epic_update_field_settings(obj,type){

    jQuery('#epic_all_update_processing').hide();
    var single_update_processing = obj.parent().find('.epic_single_update_processing');
    single_update_processing.hide();

    if('all' == type){
        jQuery('#epic_all_update_processing').html(epicAdmin.fieldUpdateProcessing);
        jQuery('#epic_all_update_processing').show();
    }else{
        single_update_processing.html(epicAdmin.fieldUpdateProcessing);
        single_update_processing.show();
    }

    var field_options   = jQuery( '#epic-custom-field-edit').serialize();

    jQuery.post(
        epicAdmin.AdminAjax,
        {
            'action': 'epic_update_custom_field',
            'field_options':   field_options
        },
        function(response){
                if(response.status == 'success'){
                    if('all' == type){
                        jQuery('#epic_all_update_processing').html(epicAdmin.fieldUpdateCompleted);
                    }else{
                        single_update_processing.html(epicAdmin.fieldUpdateCompleted);
                    }
                }

        },"json"
    );
}

function epic_create_field_settings(obj,type){

    jQuery('#epic_create_processing').hide();
    jQuery('#epic_create_processing').html(epicAdmin.fieldUpdateProcessing);
    jQuery('#epic_create_processing').show();

    var field_options = jQuery('#epic-custom-field-add').serialize();

    jQuery.post(
        epicAdmin.AdminAjax,
        {
            'action': 'epic_create_custom_field',
            'field_options':   field_options
        },
        function(response){
                if(response.status == 'success'){
                    var curr_pos = jQuery('#up_position').val();
                    jQuery('#up_position').val(parseInt(curr_pos)+1);
                  
                }
                
                jQuery('#epic_create_processing').html('');
                jQuery('#epic-custom-field-add').prepend(response.msg);
                

                var createFormCordinates = jQuery('#epic-custom-field-add').position();
                jQuery("html, body").animate({
                   scrollTop: createFormCordinates.top
                }, 2000);

        },"json"
    );
}

function epic_reset_field_settings(){
    jQuery.post(
        epicAdmin.AdminAjax,
        {
            'action': 'epic_reset_custom_fields'
        },
        function(response){

                if(response.status == 'success'){
                    
                    window.location.href = response.redirect_to;
                }           
        },"json"
    );
}