function rating(rate, message) {
    return {
        rate: rate,
        messageKey: message
    };
}

function uncapitalize(str) {
    return str.substring(0, 1).toLowerCase() + str.substring(1);
}

jQuery(document).ready(function() {	
    var err_messages={
        "similar-to-username"   : Validate.ErrMsg.similartousername,
        "mismatch"              : Validate.ErrMsg.mismatch,
        "too-short"             : Validate.ErrMsg.tooshort,
        "very-weak"             : Validate.ErrMsg.veryweak,
        "weak"                  : Validate.ErrMsg.weak,
        "username-required"     : Validate.ErrMsg.usernamerequired,
        "email-required"        : Validate.ErrMsg.emailrequired,
        "valid-email-required"  : Validate.ErrMsg.validemailrequired,
        "username-exists"       : Validate.ErrMsg.usernameexists,
        "email-exists"          : Validate.ErrMsg.emailexists	        
    }

    var LOWER = /[a-z]/,
    UPPER = /[A-Z]/,
    DIGIT = /[0-9]/,
    DIGITS = /[0-9].*[0-9]/,
    SPECIAL = /[^a-zA-Z0-9]/,
    SAME = /^(.)\1+$/;

    var messages={
        "similar-to-username" : Validate.MeterMsg.similartousername,
        "mismatch" : Validate.MeterMsg.mismatch,
        "too-short" : Validate.MeterMsg.tooshort,
        "very-weak" : Validate.MeterMsg.veryweak,
        "weak" : Validate.MeterMsg.weak,
        "good" : Validate.MeterMsg.good,
        "strong" : Validate.MeterMsg.strong
    }
	
    jQuery('#epic-registration-form').submit(function(e){
        jQuery("#epic-reg-login-img").remove();
        jQuery("#epic-reg-email-img").remove();
        jQuery("#epic-reg-login-msg").remove();
        jQuery("#epic-reg-email-msg").remove();
        // Disable submit button to prevent duplicate submissions
        jQuery('#epic-register').attr('disabled',true);		
        if(jQuery('#epic-registration-form').data('success') == 'true') {     }
        else  {
            e.preventDefault();
            var err = false;
            var err_msg = '';
            var email_reg = /^([a-zA-Z0-9+_\.\-])+\@(([a-zA-Z0-9+\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
            jQuery('#epic-registration-form').find('.required').each(function(){				
                if(jQuery(this).attr('type') == 'radio' || jQuery(this).attr('type') == 'checkbox') {
                    // Cleaning the name of the element as in case of checkbox [] will create problem.
                    var clean_name = jQuery(this).attr('name').replace(']','').replace('[','');					
                    var count = 0;
					if('epic-terms-agreement' == clean_name && jQuery("input[name^=epic-terms-agreement]:checked").size() == 0)
					{
                        err = true;
                        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon epic-icon-remove"></i> '+ jQuery(this).attr('title') + '</span>';
                        jQuery(this).addClass('error');
                    }else if(jQuery("input[name^="+clean_name+"]:checked").size() == 0) {
                        err = true;
                        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon epic-icon-remove"></i> '+ jQuery(this).attr('title') + Validate.FieldRequiredText + '</span>';
                        jQuery(this).addClass('error');
                    }
                    else if(jQuery(this).hasClass('error')) {
                        jQuery(this).removeClass('error');
                    }
                }
                else if(jQuery(this).is('select')){
                    if(jQuery(this).val() == '' || jQuery(this).val() == null){
                        err = true;
                        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon epic-icon-remove"></i>'+ jQuery(this).attr('title') + Validate.FieldRequiredText + '</span>';
                        jQuery(this).addClass('error');                       
                    }
                    else if(jQuery(this).hasClass('error')) {
                        jQuery(this).removeClass('error');
                    }
                }
                else{
                    if(jQuery(this).val() == ''){
                        err = true;
                        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon epic-icon-remove"></i> '+ jQuery(this).attr('title') + Validate.FieldRequiredText + '</span>';
                        jQuery(this).addClass('error');
                    }
                    else if(jQuery(this).hasClass('error')){
                        jQuery(this).removeClass('error');
                    }
                }				
            });			
            if(!jQuery('#reg_user_email').hasClass('error')) {
                if(!email_reg.test(jQuery('#reg_user_email').val())){
                    err = true;
                    err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon epic-icon-remove"></i> '+ err_messages['valid-email-required']+'</span>';
                    jQuery('#reg_user_email').addClass('error');
                }
                else {
                    if(jQuery('#reg_user_email').hasClass('error'))
                        jQuery('#reg_user_email').removeClass('error');
                }
            }
            if(jQuery('#reg_user_pass').length > 0) {				
                if(!jQuery('#reg_user_pass').hasClass('error')) {                    
                    // Validate password strength using the system setting
                    var passData = epicValidatePasswordStrength("#reg_user_pass","#reg_user_pass_confirm","#reg_user_login");
                    if(passData[0])
                        err = true;
                    err_msg += passData[1];                    
                }
            }			
            if(err == true && err_msg!='') {
                jQuery('#pass_err_holder').css('display','block');
                jQuery('#pass_err_block').html(err_msg);
                // Redirect to top of the registration page to view errors without scrolling
                var registrationCordinates = jQuery("#epic-registration").position();
                jQuery("html, body").animate({
                    scrollTop: registrationCordinates.top
                }, 2000);
                // Enable submit button on errors
                jQuery('#epic-register').attr('disabled',false);                                
            }
            else {
                jQuery('.epic-chosen-multiple').each(function(){
                    if(jQuery(this).val() == null){
                       jQuery(this).val(jQuery(this).find("option:first").val());
                    }
                });
                jQuery.post(
                    Validate.ajaxurl,{
                        'action': 'check_email_username',
                        'user_name':   jQuery('#reg_user_login').val(),
                        'email_id': jQuery('#reg_user_email').val()
                    },
                    function(response){					    	
                        if(response.msg == 'success'){
                            jQuery('#epic-registration-form').data('success','true');
                            jQuery('#epic-registration-form').submit();
                        }
                        else if(response.msg == 'both_error'){
                            jQuery('#reg_user_login').addClass('error');
                            jQuery('#reg_user_email').addClass('error');
                            err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i> '+ err_messages['username-exists']+'</span>';
                            err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i> '+ err_messages['email-exists']+'</span>';
                        }
                        else if(response.msg == 'user_name_error'){
                            if(jQuery('#reg_user_login').hasClass('error'))
                                jQuery('#reg_user_login').removeClass('error');					    		
								jQuery('#reg_user_login').addClass('error');
								err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i> '+ err_messages['username-exists']+'</span>';
                        }
                        else if(response.msg == 'email_error'){
                            if(jQuery('#reg_user_email').hasClass('error'))
                                jQuery('#reg_user_email').removeClass('error');					    		
								jQuery('#reg_user_email').addClass('error');
								err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i> '+ err_messages['email-exists']+'</span>';
                        }					    	
                        if(response.msg != 'success') {
                            jQuery('#pass_err_holder').css('display','block');
                            jQuery('#pass_err_block').html(err_msg);
                            // Enable submit button on errors
                            jQuery('#epic-register').attr('disabled',false);
                            // Redirect to top of the registration page to view errors without scrolling
                            var registrationCordinates = jQuery("#epic-registration").position();
                            jQuery("html, body").animate({
                                scrollTop: registrationCordinates.top
                            }, 2000);
                        }
                    },"json");					
            }			
        }
    });
    // Validate profile edit form
    jQuery('.epic-edit-profile-form').submit(function(e){
        var edit_form = jQuery(this);
        var user_id = jQuery(edit_form).find('#epic-edit-usr-id').val();
        jQuery(this).removeClass('error');
        if(jQuery(edit_form).data('success') == 'true'){      }
        else{
            e.preventDefault();
            var err = false;
            var err_msg = '';
            var email_reg = /^([a-zA-Z0-9+_\.\-])+\@(([a-zA-Z0-9+\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var filtered_names = [];			
            jQuery(edit_form).find('.required').each(function(){				
                if(jQuery(this).attr('type') == 'radio' || jQuery(this).attr('type') == 'checkbox'){
                    // Cleaning the name of the element as in case of checkbox [] will create problem.
                    var clean_name = jQuery(this).attr('name').replace(']','').replace('[','');					
                    var count = 0;
                    if(jQuery("input[name^="+clean_name+"]:checked").size() == 0){
                        err = true;
                        if(jQuery.inArray(clean_name,filtered_names) == '-1'){
                            err_msg+='<span class="epic-error epic-error-block" epic-data-name="'+clean_name+'" ><i class="epic-icon epic-icon-remove"></i>'+ jQuery(this).attr('title') + Validate.FieldRequiredText + ' </span>';
                            jQuery(this).addClass('error');
                        }
                        filtered_names.push(clean_name);						
                    }
                    else if(jQuery(this).hasClass('error')) {
                        jQuery(this).removeClass('error');
                    }
                }
                else if(jQuery(this).attr('type') == 'file' ) {
                    var file_id = jQuery(this).attr('id')+'-upload-status';
                    if(jQuery('#'+file_id).val() == '' && jQuery(this).val() == '') {
                        err = true;
                        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'+ jQuery(this).attr('title') + Validate.FieldRequiredText + '</span>';
                        jQuery(this).addClass('error');                        
                    }else if(jQuery(this).hasClass('error')) {
                        jQuery(this).removeClass('error');
                    }
                }
                else if(jQuery(this).is('select')){
                    if(jQuery(this).val() == ''){
                        err = true;
                        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'+ jQuery(this).attr('title') + Validate.FieldRequiredText + '</span>';
                        jQuery(this).addClass('error');                        
                    }
                    else if(jQuery(this).hasClass('error')){
                        jQuery(this).removeClass('error');
                    }
                }
                else {
                    if(jQuery(this).val() == ''){
                        err = true;
                        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'+ jQuery(this).attr('title') + Validate.FieldRequiredText + '</span>';
                        jQuery(this).addClass('error');
                    }
                    else if(jQuery(this).hasClass('error')){
                        jQuery(this).removeClass('error');
                    }
                }				
            });
            //var user_id = jQuery('#epic-edit-profile-form').find('input[type="submit"]').attr("name").replace("epic-submit-", "");
            var email_field_id = '#user_email-'+ user_id;					
            if(!jQuery(email_field_id).hasClass('error') && jQuery(email_field_id).length > 0 ){
                if(!email_reg.test(jQuery(email_field_id).val())){
                    err = true;                    
                    err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'+ err_messages['valid-email-required']+'</span>';
                    jQuery(email_field_id).addClass('error');
                }
                else {
                    if(jQuery(email_field_id).hasClass('error'))
                        jQuery(email_field_id).removeClass('error');
                }
            }
            var password_field_id = '#user_pass-'+ user_id;
            var password_confirm_field_id = '#user_pass_confirm-'+ user_id;
            if(jQuery(password_field_id).length > 0 && ("" != jQuery(password_field_id).val() || "" != jQuery(password_confirm_field_id).val() )){				
                if(!jQuery(password_field_id).hasClass('error')){                    
                    // Validate password strength using the system setting
                    var passData = epicValidatePasswordStrength(password_field_id,password_confirm_field_id,"#epic-edit-usr-login");
                    if(passData[0])
                        err = true;
                    err_msg += passData[1];
                }
            }			
            // Check for Password
            if(err == true && err_msg!='') {
                jQuery(edit_form).prev('#epic-edit-form-err-holder').css('display','block');
                jQuery(edit_form).prev('#epic-edit-form-err-holder').html(err_msg);
                // Redirect to top of the registration page to view errors without scrolling
                var registrationCordinates = jQuery(edit_form).position();
                jQuery("html, body").animate({
                    scrollTop: registrationCordinates.top
                }, 2000);
                return false;                                
            }else{
                jQuery('.epic-chosen-multiple').each(function(){                    
                    if(jQuery(this).val() == null){ 
                       jQuery(this).val(jQuery(this).find("option:first").val());
                    }
                });
				console.log( 'New Password :',jQuery('.new-pass').val());
                jQuery.post(
                    Validate.ajaxurl, {
                        'action': 'epic_check_edit_email',
                        'email_id': jQuery(email_field_id).val(),
                        'user_id' : user_id,
						'new-pass': jQuery('.new-pass').val()	 //DHARMMINDER SINGH (TEM SOLUTION)
                    },
                    function(response){					    	
                        if(response.msg == 'success'){                          
                            jQuery(edit_form).data('success','true');
                            jQuery(edit_form).submit();
                        //return true;
                        }
                        else if(response.msg == 'email_error') {
                            if(jQuery(email_field_id).hasClass('error'))
                                jQuery(email_field_id).removeClass('error');					    		
                            jQuery(email_field_id).addClass('error');
                            err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'+ err_messages['email-exists']+'</span>';
                        }					    	
                        if(response.msg != 'success') {
                            jQuery(edit_form).prev('#epic-edit-form-err-holder').css('display','block');
                            jQuery(edit_form).prev('#epic-edit-form-err-holder').html(err_msg);
							// Redirect to top of the registration page to view errors without scrolling
                            var registrationCordinates = jQuery(edit_form).position();
                            jQuery("html, body").animate({
                                scrollTop: registrationCordinates.top
                            }, 2000);
                        }	
                    },"json");
            }			
        }
    });    
    var password_field_class = '.epic-edit-user_pass';
    var password_confirm_field_class = '.epic-edit-user_pass_confirm';  

    // Clear error messages on focus
    jQuery('.epic-edit-profile-form').find('.required').focus(function(){
        jQuery(this).removeClass('error');
    });

    jQuery(password_field_class).focus(function(){
        jQuery(this).removeClass('error');
    });

    jQuery(password_confirm_field_class).focus(function(){
        jQuery(this).removeClass('error');
    });

    /* Edit Profile Form: Validate username on focus out */
    var email_class = '.epic-edit-user_email';
    jQuery(email_class).focus(function(){
        jQuery(this).removeClass('error');
    });
    jQuery(email_class).blur(function(){
        var user_id = jQuery(this).closest('form').find('#epic-edit-usr-id').val();
        var newUserEmail = jQuery(this).val();
        var email = jQuery(this);
        var email_reg = /^([a-zA-Z0-9+_\.\-])+\@(([a-zA-Z0-9+\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var message;
        jQuery(email).removeClass('error');
        jQuery("#epic-reg-email-img").remove();
        jQuery("#epic-reg-email-msg").remove();

        if('' == newUserEmail){
            message = epicCustom.Messages.RegEmptyEmail;
            jQuery(email).addClass('error');
            jQuery(email).after('<div id="epic-reg-email-msg" class="epic-input-text-inline-error" ><i id="epic-reg-email-img" original-title="Invalid" class="epic-icon epic-icon-remove epic-input-text-font-cancel" ></i>' + message + '</div>');
               
        }else if(!email_reg.test(newUserEmail)){
            message = epicCustom.Messages.RegInvalidEmail;
            jQuery(email).addClass('error');
            jQuery(email).after('<div id="epic-reg-email-msg" class="epic-input-text-inline-error" ><i id="epic-reg-email-img" original-title="Invalid" class="epic-icon epic-icon-remove epic-input-text-font-cancel" ></i>' + message + '</div>');
               
        }else{
        jQuery.post(
            epicCustom.AdminAjax, {
                'action': 'epic_validate_edit_profile_email',
                'user_email':   newUserEmail,
                'user_id' : user_id
            },
            function(response){                
                switch(response.msg){
                    case 'RegExistEmail':
                        message = epicCustom.Messages.RegExistEmail; 
                        break;
                    case 'RegValidEmail':
                        message = epicCustom.Messages.RegValidEmail; 
                        break;
                    case 'RegInvalidEmail':
                        message = epicCustom.Messages.RegInvalidEmail;
                        break;
                    case 'RegEmptyEmail':
                        message = epicCustom.Messages.RegEmptyEmail;
                        break;
                }
                if(response.status){
                    jQuery(email).addClass('error');
                    jQuery(email).after('<div id="epic-reg-email-msg" class="epic-input-text-inline-error" ><i id="epic-reg-email-img" original-title="Invalid" class="epic-icon epic-icon-remove epic-input-text-font-cancel" ></i>' + message + '</div>');
                }else{
                    jQuery(email).after('<div id="epic-reg-email-msg" class="epic-input-text-inline-success" ><i id="epic-reg-email-img" original-title="Valid" class="epic-icon epic-icon-ok epic-input-text-font-accept" ></i>' + message + '</div>');
                }
            },"json");
        }
    });
    // Validate reset password form
    jQuery('#epic-reset-password-form').submit(function(e){
        var reset_form = jQuery(this);
        jQuery(this).removeClass('error');
        if(jQuery(reset_form).attr('data-status') == 'success') {
            return true;
        }
        else {
            jQuery(reset_form).attr('data-status','error');
            //e.preventDefault();
            var err = false;
            var err_msg = '';
            if(jQuery("#epic_new_password").val() == '') {
                err = true;
                err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>' +Validate.NewPasswordMsg +'</span>';
                jQuery("#epic_new_password").addClass('error');
            }
            else if(jQuery("#epic_new_password").hasClass('error')) {
                jQuery("#epic_new_password").removeClass('error');
            }
            if(jQuery("#epic_confirm_new_password").val() == '') {
                err = true;
                err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>' +Validate.ConfirmPassMsg +'</span>';
                jQuery("#epic_confirm_new_password").addClass('error');
            }
            else if(jQuery("#epic_confirm_new_password").hasClass('error')) {
                jQuery("#epic_confirm_new_password").removeClass('error');
            }
            if(jQuery("#epic_new_password").length > 0 && ("" != jQuery("#epic_new_password").val() && "" != jQuery("#epic_confirm_new_password").val() )){                
                if(!jQuery("#epic_new_password").hasClass('error')){                    
                    // Validate password strength using the system setting
                    var passData = epicValidatePasswordStrength("#epic_new_password","#epic_confirm_new_password","#epic-reset-pass-login");
                    if(passData[0])
                        err = true;
                    err_msg += passData[1];                    
                }
            }            
            // Check for Password            
            if(err == true && err_msg!=''){                
                jQuery(reset_form).prev('#epic-reset-form-err-holder').css('display','block');
                jQuery(reset_form).prev('#epic-reset-form-err-holder').html(err_msg);
                return false;                                
            }else{
                jQuery(reset_form).attr("data-status","success");
                jQuery(reset_form).submit(); 
            } 
        }
    });
});

// Display and handle password strength meter
jQuery(document).ready(function(){
      if(jQuery("#password-meter-message").length > 0){
            if(jQuery('#reg_user_pass').length > 0){
                epicPasswordStrengthMeter("#reg_user_pass","#reg_user_pass_confirm","#reg_user_login");            
            }
            var password_field_class = '.epic-edit-user_pass';
            var password_confirm_field_class = '.epic-edit-user_pass_confirm';
            if(jQuery(password_field_class).length > 0){
                epicPasswordStrengthMeter(password_field_class,password_confirm_field_class,"#epic-edit-usr-login");            
            }
            if(jQuery('#epic_new_password').length > 0){
                epicPasswordStrengthMeter("#epic_new_password","#epic_confirm_new_password","#epic-reset-pass-login");
            }
        }
    // Trigger password meter on password fields
    jQuery('#epic_new_password').blur(function(){
        jQuery(this).trigger('keyup');
    });

    jQuery('#epic_new_password').focus(function(){
        jQuery(this).trigger('keyup');
    });

    jQuery('#epic_confirm_new_password').blur(function(){
        jQuery(this).trigger('keyup');
    });

    jQuery('#epic_confirm_new_password').focus(function(){
        jQuery(this).trigger('keyup');
    });           


    jQuery('#reg_user_pass').focus(function(){
        jQuery(this).trigger('keyup');
    });
               
    jQuery('#reg_user_pass').blur(function(){
        jQuery(this).trigger('keyup');
    });

    jQuery('#reg_user_pass_confirm').focus(function(){
        jQuery(this).trigger('keyup');
    });
               
    jQuery('#reg_user_pass_confirm').blur(function(){
        jQuery(this).trigger('keyup');
    });
});

// Validate password strength and generate strength score
function epicPasswordStrengthMeter(passField,confirmPassField,usernameField){
    jQuery(passField).bind("keyup", function(){
        var pass1 = jQuery(passField).val();
        var pass2 = jQuery(confirmPassField).val();
        var username = jQuery(usernameField).val();
        var strength = passwordStrength(pass1, username, pass2);
        epicUpdatePasswordStrength(strength);
    });
    jQuery(confirmPassField).bind("keyup", function(){
        var pass1 = jQuery(passField).val();
        var pass2 = jQuery(confirmPassField).val();
        var username = jQuery(usernameField).val();
        var strength = passwordStrength(pass1, username, pass2);
        epicUpdatePasswordStrength(strength);
    });
}

// Display the values for password strength meter
function epicUpdatePasswordStrength(strength){
    //week-2 medium-3 strong-4 veryweek<2
    var status = new Array('very-week','very-week', 'week', 'medium', 'strong', 'mismatch');
    var dom = jQuery("#password-meter-message");
    switch(strength){
    case 0:
      dom.removeClass().addClass("password-meter-message").addClass(status[0]).text(Validate.MeterMsg.veryweak);
      break;
    case 1:
      dom.removeClass().addClass("password-meter-message").addClass(status[1]).text(Validate.MeterMsg.veryweak);
      break;
    case 2:
      dom.removeClass().addClass("password-meter-message").addClass(status[2]).text(Validate.MeterMsg.weak);
      break;
    case 3:
      dom.removeClass().addClass("password-meter-message").addClass(status[3]).text(Validate.MeterMsg.medium);
      break;
    case 4:
     dom.removeClass().addClass("password-meter-message").addClass(status[4]).text(Validate.MeterMsg.strong);
      break;
    case 5:
      dom.removeClass().addClass("password-meter-message").addClass(status[5]).text(Validate.MeterMsg.mismatch);
      break;
    default:
      //alert('something is wrong!');
    }
}

function epicValidatePasswordStrength(passField,confirmPassField,usernameField){
    var err_messages={
        "similar-to-username"   : Validate.ErrMsg.similartousername,
        "mismatch"              : Validate.ErrMsg.mismatch,
        "too-short"             : Validate.ErrMsg.tooshort,
        "very-weak"             : Validate.ErrMsg.veryweak,
        "weak"                  : Validate.ErrMsg.weak,
        "username-required"     : Validate.ErrMsg.usernamerequired,
        "email-required"        : Validate.ErrMsg.emailrequired,
        "valid-email-required"  : Validate.ErrMsg.validemailrequired,
        "username-exists"       : Validate.ErrMsg.usernameexists,
        "email-exists"          : Validate.ErrMsg.emailexists            
    }

    var messages={
        "similar-to-username" : Validate.MeterMsg.similartousername,
        "mismatch" : Validate.MeterMsg.mismatch,
        "too-short" : Validate.MeterMsg.tooshort,
        "very-weak" : Validate.MeterMsg.veryweak,
        "weak" : Validate.MeterMsg.weak,
        "medium" : Validate.MeterMsg.medium,
        "good" : Validate.MeterMsg.good,
        "strong" : Validate.MeterMsg.strong
    }

    var err = false;
    var err_msg = '';
    var status = new Array('0','2', '3', '4');
    var statusText = new Array('very-weak','weak', 'medium', 'strong');
    var pass1 = jQuery(passField).val();
    var pass2 = jQuery(confirmPassField).val();
    var username = jQuery(usernameField).val();
    var strength = passwordStrength(pass1, username, pass2);
    var passwordStrengthLevel = Validate.PasswordStrength;
    if(strength != '5'){
        if(passwordStrengthLevel == "0"){
            if(jQuery(confirmPassField).hasClass('error'))
                jQuery(confirmPassField).removeClass('error');        
            if(jQuery(passField).hasClass('error'))
                jQuery(passField).removeClass('error');
        }else if(status[passwordStrengthLevel] <= strength){
            if(jQuery(confirmPassField).hasClass('error'))
                jQuery(confirmPassField).removeClass('error');        
            if(jQuery(passField).hasClass('error'))
                jQuery(passField).removeClass('error');
        }else {            
            err = true;
            err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i> '+ Validate.MinPassStrength + ' ' + messages[statusText[passwordStrengthLevel]] + '</span>';       
            jQuery(passField).addClass('error');
            jQuery(confirmPassField).addClass('error');
        }
    }else{
        err = true;
        err_msg+='<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i> '+ err_messages["mismatch"]+'</span>';       
        jQuery(passField).addClass('error');
        jQuery(confirmPassField).addClass('error');
    }
    return Array(err,err_msg);
}