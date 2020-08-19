jQuery(document).ready(function($) {

    /* Email Template Settings */
    $('#email_template').val('0').trigger("chosen:updated");

    $('#reset-epic-email-template').click(function(){
        $('#epic-email-settings-msg').html('').removeClass('').hide();

        if($('#email_template').val() == '0'){
            $('#epic-email-settings-msg').html('<p>' + epicAdminModules.emailTitleRequired + '</p>');
            $('#epic-email-settings-msg').addClass('error').show();
        }else{

            $.post(
                epicAdminModules.AdminAjax,
                {
                    'action'                : 'epic_reset_email_template',
                    'template_name'         : $('#email_template').val(),
                },
                function(response){

                    if(response.status == 'success'){
                        $('#epic-email-settings-msg').html('<p>' + response.html + '</p>');
                        $('#epic-email-settings-msg').addClass('success').removeClass('error').show();
                        $('#email_subject').val(response.temp_subject).show();
                        $('#email_template_editor').val(response.temp_content).show();
                        $('#email_status').val(response.temp_status).trigger("chosen:updated");
                    }

                },"json"
            );

        }
    });

    $('#save-epic-email-template').click(function(){

        $('#epic-email-settings-msg').html('').removeClass('').hide();

        if($('#email_template').val() == '0'){
            $('#epic-email-settings-msg').html('<p>' + epicAdminModules.emailTitleRequired + '</p>');
            $('#epic-email-settings-msg').addClass('error').show();
        }
        else if($('#email_subject').val().trim() == ''){
            $('#epic-email-settings-msg').html('<p>' + epicAdminModules.emailSubjectRequired + '</p>');
            $('#epic-email-settings-msg').addClass('error').show();
        }
        else{

            $.post(
                epicAdminModules.AdminAjax,
                {
                    'action'                : 'epic_save_email_template',
                    'template_name'         : $('#email_template').val(),
                    'template_content'      : $('#email_template_editor').val(),
                    'template_status'       : $('#email_status').val(),
                    'template_subject'       : $('#email_subject').val(),
                },
                function(response){

                    if(response.status == 'success'){
                        $('#epic-email-settings-msg').html('<p>' + response.html + '</p>');
                        $('#epic-email-settings-msg').addClass('success').show();
                    }

                },"json"
            );

        }

    });

    $('#email_template').change(function(){

        var email_template_editor = $('#email_template_editor');
        var email_template_editor_parent = $(email_template_editor).parent().parent();
        var email_status = $('#email_status');
        var email_status_parent = $(email_status).parent().parent();
        var email_subject = $('#email_subject');
        var email_subject_parent = $(email_subject).parent().parent();

        $(email_status_parent).hide();
        $(email_template_editor_parent).hide();
        $(email_subject_parent).hide();
        $(email_status).attr('checked', false);

        if($(this).val() == '0'){
            return;
        }else{            
            $.post(
                epicAdminModules.AdminAjax,
                {
                    'action'        : 'epic_get_email_template',
                    'template_name'   : $(this).val(),
                },
                function(response){

                    if(response.status == 'success'){
                        $(email_template_editor).val(response.temp_content).show();
                        $(email_template_editor_parent).show();
                        $('#email_status').val(response.temp_status).trigger("chosen:updated");
                        $(email_status_parent).show();
                        $(email_subject).val(response.temp_subject).show();
                        $(email_subject_parent).show();
                    }

                },"json"
            );
        }
    });

	/* Private Content Restriction Settings */
    $('#epic-display-create-res-rule').click(function(){
    	$('#epic-site-restrictions-list').hide();
    	$('#epic-site-restrictions-create').show();
    });
    
    $('#epic-display-list-res-rule').click(function(){
    	$('#epic-site-restrictions-list').show();
    	$('#epic-site-restrictions-create').hide();
    });

    if($('#site_content_section_restrictions')){
		epic_show_content_section_params();
	}

	$('#site_content_section_restrictions').change(function(){
		epic_show_content_section_params();
	});

	if($('#site_content_user_restrictions')){
		epic_show_user_restriction_params();
	}	

	$('#site_content_user_restrictions').change(function(){
		epic_show_user_restriction_params();
	});

	

	$('#add-epic-site-restriction-rule').click(function(){
		
		$('#epic-site-restrictions-settings-msg').html('').removeClass('').hide();
		$('#epic-add-site-restrictions-settings-msg').html('').removeClass('').hide();
		$('#epic-modules-settings-saved').hide();
    	
    	var data = $('#epic-site-restrictions-create-form').serialize();
    	var user_restrictions = $('#site_content_user_restrictions').val();

    	var error = 0;
    	var error_msg = '';
    	$('#add-epic-site-restriction-rule').attr("disabled", "disabled");
    	$('#add-epic-site-restriction-rule').val(epicAdminModules.savingResRule);

    	if($('#site_content_redirect_url').val() == '0'){
    		error_msg += '<p>'+ epicAdminModules.redirectURLRequired +'</p>';
    		error++;
    	}
    	if(user_restrictions == 'by_user_roles'){
    		var checked = false;
    		$(".site_content_allowed_roles :checkbox:checked").each(
                function() {
                    checked = true;
                }
            );

            if(!checked){
            	error_msg += '<p>'+ epicAdminModules.userRoleRequired +'</p>';
            	error++;
            }    		
    	}

    	var site_content_section_restrictions = $('#site_content_section_restrictions').val();
    	if(site_content_section_restrictions == 'restrict_selected_pages' || site_content_section_restrictions == 'restrict_selected_posts' || site_content_section_restrictions == 'restrict_sub_selected_pages' || site_content_section_restrictions == 'restrict_sub_include_selected_pages' || site_content_section_restrictions == 'restrict_posts_by_categories' ){
            
    		var site_content_page_restrictions = $('#site_content_page_restrictions').val();    		
    		var site_content_post_restrictions = $('#site_content_post_restrictions').val();
            var site_content_category_restrictions = $('#site_content_category_restrictions').val();
            
    		if( ( site_content_section_restrictions == 'restrict_selected_pages' || site_content_section_restrictions == 'restrict_sub_selected_pages' || site_content_section_restrictions == 'restrict_sub_include_selected_pages'  ) && site_content_page_restrictions == null){
    			error_msg += '<p>'+ epicAdminModules.pageRequired +'</p>';
            	error++;
    		}else if(site_content_section_restrictions == 'restrict_selected_posts' && site_content_post_restrictions == null){
    			error_msg += '<p>'+ epicAdminModules.postRequired +'</p>';
            	error++;
    		}else if(site_content_section_restrictions == 'restrict_posts_by_categories' && site_content_category_restrictions == null){

    			error_msg += '<p>'+ epicAdminModules.categoriesRequired +'</p>';
            	error++;
    		}
    		
    	}

    	if(error != 0){
    		$('#epic-add-site-restrictions-settings-msg').html(error_msg).addClass('error').show();
    		
    		$('#add-epic-site-restriction-rule').removeAttr("disabled");
    		$('#add-epic-site-restriction-rule').val(epicAdminModules.saveResRule);

    	}else{
    	
	    	$.post(
		        epicAdminModules.AdminAjax,
		        {
		            'action': 'epic_save_site_restriction_rules',
		            'data':   data,
		        },
		        function(response){

		        	if(response.status == 'success'){
		        		$('#epic-modules-settings-saved').show();
	

	                    var htm = $('#epic_site_restriction_rules_titles').clone().wrap('<div>').parent().html() + response.rules;

	                    $('#epic_site_restriction_rules').html(htm);
		        	}

		        	// Reset form after adding a rule
		        	$('#site_content_user_restrictions').val('by_all_users').trigger('change').trigger("chosen:updated");
		        	$('#site_content_section_restrictions').val('all_pages').trigger('change').trigger("chosen:updated");
		        	$('#site_content_page_restrictions').val('').trigger("chosen:updated");
		        	$('#site_content_post_restrictions').val('').trigger("chosen:updated");
                    $('#site_content_category_restrictions').val('').trigger("chosen:updated");
		        	$('#site_content_redirect_url').val('0').trigger("chosen:updated");
		        	$(".site_content_allowed_roles :checkbox").each(function(){
		                $(this).removeAttr('checked');
		            });

		        	$('#add-epic-site-restriction-rule').removeAttr("disabled");
    				$('#add-epic-site-restriction-rule').val(epicAdminModules.saveResRule);

		        },"json"
			);
	    }
    });
    
    epic_show_field_export_section();
    $('#site_export_field_type').change(function () {
        epic_show_field_export_section();
    });

    epic_show_field_import_section();
    $('#site_import_field_type').change(function () {
        epic_show_field_import_section();
    });


    epic_show_settings_export_section();
    $('#site_export_settings_type').change(function () {
        epic_show_settings_export_section();
    });

    epic_show_settings_import_section();
    $('#site_import_settings_type').change(function () {
        epic_show_settings_import_section();
    });

	$(document.body).on('click', '.epic_delete_restriction_rule',function(){
		var rule_id = $(this).parent().find('#epic_rule_id').val();

		$.post(
	        epicAdminModules.AdminAjax,
	        {
	            'action': 'epic_delete_site_restriction_rules',
	            'rule_id':   rule_id,
	        },
	        function(response){
	        	
	        	if(response.status == 'success'){
	        		$('#epic-modules-settings-saved').show();

                    var htm = $('#epic_site_restriction_rules_titles').clone().wrap('<div>').parent().html() + response.rules;

                    $('#epic_site_restriction_rules').html(htm);
	        	}
	        },"json"
		);
	});

    $(document.body).on('change', '.site_content_enable_restriction',function(){
        
        var rule_status = 0;
        if($(this).is(':checked')){
            rule_status = 1;
        }else{
            rule_status = 0;
        }
        
        var rule_id = $(this).parent().parent().find('#epic_rule_id').val();

        $.post(
            epicAdminModules.AdminAjax,
            {
                'action': 'epic_enable_site_restriction_rules',
                'rule_id':   rule_id,
                'rule_status' : rule_status,
            },
            function(response){
                
                if(response.status == 'success'){
                    $('#epic-modules-settings-saved').show();

                    var htm = $('#epic_site_restriction_rules_titles').clone().wrap('<div>').parent().html() + response.rules;

                    $('#epic_site_restriction_rules').html(htm);
                }
            },"json"
        );
    });

	// TODO - SAVE Modules Options
	$('.epic-save-module-options').click(function(){
    	
    	var btn_id = $(this).attr('id');    	
    	var current_tab = btn_id.replace('save-','');
    	var form_id = current_tab + '-form';
    	
    	$('#'+btn_id).attr("disabled", "disabled");
    	$('#'+btn_id).val(epicAdminModules.savingSetting);
    	
    	$.post(
    	        epicAdminModules.AdminAjax,
    	        {
    	            'action': 'save_epic_module_settings',
    	            'data':   $("#"+form_id).serialize(),
    	            'current_tab' : current_tab
    	        },
    	        function(response){
    	        	if(response.status == 'success'){
    	        		$('#'+btn_id).val(epicAdminModules.saveSetting);
	    	        	$('#'+btn_id).removeAttr("disabled");
	    	        	
	    	        	$('#epic-modules-settings-saved').show();
	    	        	setTimeout(function(){
	                        jQuery("#epic-modules-settings-saved").hide();
	                    }, 3000);
    	        	}
    	        	
    	        	
    	        }
    		,'json');
    });

	$('.epic-reset-module-options').click(function(){
	    	
	    var btn_id = $(this).attr('id');    	
    	var current_tab = btn_id.replace('reset-','');
    	var form_id = current_tab + '-form';
    	
    	$('#'+btn_id).attr("disabled", "disabled");
    	$('#'+btn_id).val(epicAdminModules.resettingSetting);    	
    	
    	$.post(
    	        epicAdminModules.AdminAjax,
    	        {
    	            'action': 'reset_epic_module_settings',
    	            'current_tab' : current_tab
    	        },
    	        function(response){
    	        	if(response.status == 'success'){
    	        		$('#'+btn_id).val(epicAdminModules.resetSetting);
	    	        	$('#'+btn_id).removeAttr("disabled");
	    	        	
	    	        	window.location = epicAdminModules.adminURL + '&reset=' +form_id;
    	        	}

	   	        }
    		,'json');
    });

    $('#site_lockdown_status').click(function(){
    	epic_show_lockdown_fields();
    });
    
    epic_show_lockdown_fields();
    
    
    /*Field Export Button Click*/
    $('#epic-download-export-fields').click(function () {

        var btn_id = $(this).attr('id');
        var current_tab = btn_id.replace('save-', '');
        var form_id = current_tab + '-form';

        console.log($("#" + form_id));

        //$('#' + btn_id).val(epicAdminModules.downloadFields);

        document.location.href = epicAdminModules.AdminAjax+'?action=epic_download_export_fields&' +
        'current_tab='+current_tab+'&'+$("#" + form_id).serialize();
    });

    /*Field Import Button Click*/
    $('#epic-upload-import-fields-form').submit(function (e) {
        var btn_id = $(this).attr('id');
        var current_tab = btn_id.replace('save-', '');
        var form_id = current_tab + '-form';

        //$('#' + btn_id).val(epicAdminModules.downloadFields);

        // Get some values from elements on the page:

        var formObj = $(this);
        var formURL = epicAdminModules.AdminAjax+'?action=epic_upload_import_fields&'+'current_tab='+current_tab;
        var formData = new FormData(this);
        $.ajax({
            url: formURL,
            type: 'POST',
            data:  formData,
            mimeType:"multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data, textStatus, jqXHR)
            {
                var obj = jQuery.parseJSON(data);
                if(obj.status == 'success') {
                    $('#epic-modules-import-success').show();
                    setTimeout(function () {
                        jQuery("#epic-modules-import-success").hide();
                    }, 3000);
                }else{
                    $('#epic-modules-import-success').hide();
                    $('#epic-modules-import-error').show();
                    setTimeout(function () {
                        jQuery("#epic-modules-import-error").hide();
                    }, 3000);
                }

            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                var obj = jQuery.parseJSON(data);

                console.log('error');
            }
        });
        e.preventDefault(); //Prevent Default action.
        //e.unbind();

    });

    /* Settings Export Button Click*/
    $('#epic-download-export-settings').click(function () {

        var btn_id = $(this).attr('id');
        var current_tab = btn_id.replace('save-', '');
        var form_id = current_tab + '-form';

        //$('#' + btn_id).val(epicAdminModules.downloadSettings);

        document.location.href = epicAdminModules.AdminAjax+'?action=epic_download_export_settings&' +
        'current_tab='+current_tab+'&'+$("#" + form_id).serialize();
    });

    /*Field Import Button Click*/
    $('#epic-upload-import-settings-form').submit(function (e) {
        var btn_id = $(this).attr('id');
        var current_tab = btn_id.replace('save-', '');
        var form_id = current_tab + '-form';

        //$('#' + btn_id).val(epicAdminModules.downloadSettings);

        // Get some values from elements on the page:

        var formObj = $(this);
        var formURL = epicAdminModules.AdminAjax+'?action=epic_upload_import_settings&'+'current_tab='+current_tab;
        var formData = new FormData(this);
        $.ajax({
            url: formURL,
            type: 'POST',
            data:  formData,
            mimeType:"multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data, textStatus, jqXHR)
            {
                var obj = jQuery.parseJSON(data);
                if(obj.status == 'success') {
                    $('#epic-modules-import-success').show();
                    setTimeout(function () {
                        jQuery("#epic-modules-import-success").hide();
                    }, 3000);
                }else{
                    $('#epic-modules-import-success').hide();
                    $('#epic-modules-import-error').show();
                    setTimeout(function () {
                        jQuery("#epic-modules-import-error").hide();
                    }, 3000);
                }

            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                var obj = jQuery.parseJSON(data);

                console.log('error');
            }
        });
        e.preventDefault();
    });

    $('.button-import-export').click(function(){

        $('.button-import-export').removeClass('active');
        $(this).addClass('active');
        
        var id = $(this).attr('id');
        id = id.replace("btn", "panel");
        $('.panel-import-export').hide();
        $('#'+id).show();
    });
    
    /* Settings Export Button Click*/
    $('#epic-download-export-users').click(function () {

        var btn_id = $(this).attr('id');
        var current_tab = btn_id.replace('save-', '');
        var form_id = current_tab + '-form';

        //$('#' + btn_id).val(epicAdminModules.downloadSettings);

        document.location.href = epicAdminModules.AdminAjax+'?action=epic_download_export_users&' +
        'current_tab='+current_tab+'&'+$("#" + form_id).serialize();
    });
    
    /* epic Posts/Pages related settings */
    $('#favorite_enabled_status').change(function(){
        epic_favorite_enabled_fields($(this));
    });    
    
    if( $('#favorite_enabled_status').length > 0 ){
        epic_favorite_enabled_fields($('#favorite_enabled_status'));
    }
    
    $('#reader_enabled_status').change(function(){
        epic_reader_enabled_fields($(this));
    });    
    
    if( $('#reader_enabled_status').length > 0 ){
        epic_reader_enabled_fields($('#reader_enabled_status'));
    } 
    
    $('#recommend_enabled_status').change(function(){
        epic_recommend_enabled_fields($(this));
    });    
    
    if( $('#recommend_enabled_status').length > 0 ){
        epic_recommend_enabled_fields($('#recommend_enabled_status'));
    } 

});

function epic_show_content_section_params(){
    jQuery('#site_content_page_recursive_status').parent().parent().hide();

	var section_res = jQuery('#site_content_section_restrictions').val();
	if(section_res == 'all_posts' || section_res == 'all_pages'){
        
		jQuery('#site_content_page_restrictions').parent().parent().hide();
		jQuery('#site_content_post_restrictions').parent().parent().hide();
        jQuery('#site_content_category_restrictions').parent().parent().hide();
        
	}else if(section_res == 'restrict_selected_pages'){
        
		jQuery('#site_content_post_restrictions').parent().parent().hide();
		jQuery('#site_content_page_restrictions').parent().parent().show();
        jQuery('#site_content_category_restrictions').parent().parent().hide();
        
	}else if(section_res == 'restrict_sub_selected_pages'){
        
		jQuery('#site_content_post_restrictions').parent().parent().hide();
		jQuery('#site_content_page_restrictions').parent().parent().show();
        jQuery('#site_content_category_restrictions').parent().parent().hide();
        jQuery('#site_content_page_recursive_status').parent().parent().show();
        
	}else if(section_res == 'restrict_sub_include_selected_pages'){
        
		jQuery('#site_content_post_restrictions').parent().parent().hide();
		jQuery('#site_content_page_restrictions').parent().parent().show();
        jQuery('#site_content_category_restrictions').parent().parent().hide();
        jQuery('#site_content_page_recursive_status').parent().parent().show();
        
	}else if(section_res == 'restrict_selected_posts'){
        
		jQuery('#site_content_page_restrictions').parent().parent().hide();
		jQuery('#site_content_post_restrictions').parent().parent().show();
        jQuery('#site_content_category_restrictions').parent().parent().hide();
        
	}else if(section_res == 'restrict_posts_by_categories'){
        
		jQuery('#site_content_page_restrictions').parent().parent().hide();
		jQuery('#site_content_post_restrictions').parent().parent().hide();
        jQuery('#site_content_category_restrictions').parent().parent().show();
        
	}else {
        
		jQuery('#site_content_page_restrictions').parent().parent().show();
		jQuery('#site_content_post_restrictions').parent().parent().show();
        jQuery('#site_content_category_restrictions').parent().parent().show();
        
	}
}

function epic_show_user_restriction_params(){
	if(jQuery('#site_content_user_restrictions').val() == 'by_user_roles'){
		jQuery('#site_content_allowed_roles').parent().parent().show();
	}else{
		jQuery('#site_content_allowed_roles').parent().parent().hide();
	}	
}

function epic_show_lockdown_fields(){
		if(jQuery("#site_lockdown_status").is(':checked')){
			jQuery('#site_lockdown_allowed_pages,#site_lockdown_allowed_posts,#site_lockdown_allowed_urls,#site_lockdown_redirect_url').parent().parent().show();
		}else{
			jQuery('#site_lockdown_allowed_pages,#site_lockdown_allowed_posts,#site_lockdown_allowed_urls,#site_lockdown_redirect_url').parent().parent().hide();
		}
}

/*epic Field Export*/
function epic_show_field_export_section() {

    var section_res = jQuery('#site_export_field_type').val();

    if (section_res == 'selected_fields') {
        jQuery('#site_export_fields').parent().parent().show();
    } else {
        jQuery('#site_export_fields').parent().parent().hide();
    }
}

/*epic Field Import*/
function epic_show_field_import_section() {

    var section_res = jQuery('#site_import_field_type').val();

    if (section_res == 'selected_fields') {
        jQuery('#site_import_fields').parent().parent().show();
    } else {
        jQuery('#site_import_fields').parent().parent().hide();
    }
}

/*epic Settings Export*/
function epic_show_settings_export_section() {

    var section_res = jQuery('#site_export_settings_type').val();

    if (section_res == 'selected_settings') {
        jQuery('#site_export_settings_sections').parent().parent().show();
    } else {
        jQuery('#site_export_settings_sections').parent().parent().hide();
    }
}

/*epic Settings Import*/
function epic_show_settings_import_section() {

    var section_res = jQuery('#site_import_settings_type').val();

    if (section_res == 'selected_settings') {
        jQuery('#site_import_settings_sections').parent().parent().show();
    } else {
        jQuery('#site_import_settings_sections').parent().parent().hide();
    }
}

/* epic Posts and Pages Settings */
function epic_favorite_enabled_fields(obj){
    if(obj.val() == '0'){
        jQuery('#favorite_enabled_post_types').parent().parent().hide();
        jQuery('#favorite_enabled_user_roles').parent().parent().hide();
        jQuery('#favorite_default_featured_image').parent().parent().hide();


    }else{
        jQuery('#favorite_enabled_post_types').parent().parent().show();
        jQuery('#favorite_enabled_user_roles').parent().parent().show();
        jQuery('#favorite_default_featured_image').parent().parent().show();
    }
}

function epic_recommend_enabled_fields(obj){
    if(obj.val() == '0'){
        jQuery('#recommend_enabled_post_types').parent().parent().hide();
        jQuery('#recommend_enabled_user_roles').parent().parent().hide();
        jQuery('#recommend_default_featured_image').parent().parent().hide();


    }else{
        jQuery('#recommend_enabled_post_types').parent().parent().show();
        jQuery('#recommend_enabled_user_roles').parent().parent().show();
        jQuery('#recommend_default_featured_image').parent().parent().show();
    }
}

function epic_reader_enabled_fields(obj){
    if(obj.val() == '0'){
        jQuery('#reader_enabled_post_types').parent().parent().hide();
        jQuery('#reader_enabled_user_roles').parent().parent().hide();
        jQuery('#reader_default_featured_image').parent().parent().hide();


    }else{
        jQuery('#reader_enabled_post_types').parent().parent().show();
        jQuery('#reader_enabled_user_roles').parent().parent().show();
        jQuery('#reader_default_featured_image').parent().parent().show();
    }
}