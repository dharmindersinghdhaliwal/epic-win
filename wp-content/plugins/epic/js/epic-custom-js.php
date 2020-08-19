<script type="text/javascript">
jQuery(document).ready(function($) {
	/* Nice file upload */
	// Calling hidden and native element's action
	$('.epic-fileupload').click(function(){
	    if($('#file_'+$(this).attr('id')).length > 0)
	        $('#file_'+$(this).attr('id')).click();
	});
	// replace selected image path in custom div
	$(':file').change(function(){
	    if($('#'+$(this).attr('name')).length > 0)
	        $('#'+$(this).attr('name')).text($(this).val());
	});
	/* Tooltips */
	if($('.epic-tooltip').length > 0){
		$('.epic-tooltip').tipsy({
			trigger: 'hover',
			offset: 4
		});
	}
    if($('.epic-go-to-page').length > 0) {
    	$('.epic-go-to-page').on('change', function(){
    	    if($(this).val() != 0)
            window.location = String($(this).val());
    	});
    }
	/* Check/uncheck */
	$('.epic-hide-from-public').click(function(e){
		e.preventDefault();
		if ($(this).find('i').hasClass('epic-icon epic-icon-square-o')) {
			$(this).find('i').addClass('epic-icon epic-icon-check-square-o').removeClass('epic-icon epic-icon-square-o');
			$(this).find('input[type=hidden]').val(1);
		} else {
			$(this).find('i').addClass('epic-icon epic-icon-square-o').removeClass('epic-icon epic-icon-check-square-o');
			$(this).find('input[type=hidden]').val(0);
		}
	});

	$('.epic-rememberme').click(function(e){
		e.preventDefault();
		if ($(this).find('i').hasClass('epic-icon epic-icon-square-o')) {
			$(this).find('i').addClass('epic-icon epic-icon-check-square-o').removeClass('epic-icon epic-icon-square-o');
			$(this).find('input[type=hidden]').val(1);
		} else {
			$(this).find('i').addClass('epic-icon epic-icon-square-o').removeClass('epic-icon epic-icon-check-square-o');
			$(this).find('input[type=hidden]').val(0);
		}
	});
	
	/* Toggle edit inline */
	$('.epic-field-edit a.epic-fire-editor').click(function(e){
		e.preventDefault();
		this_form = $(this).parent().parent().parent().parent().parent();
		if ($(this_form).find('.epic-edit').is(':hidden')) {
			if ($(this_form).find('.epic-view').length > 0) {
				$(this_form).find('.epic-view').slideUp(function() {
					$(this_form).find('.epic-edit').slideDown();
					$(this_form).find('.epic-field-edit a.epic-fire-editor').html('<?php _e('View Profile','epic'); ?>');
				});
			} else {
				$(this_form).find('.epic-main').show();
				$(this_form).find('.epic-edit').slideDown();
				$(this_form).find('.epic-field-edit a.epic-fire-editor').html('<?php _e('View Profile','epic'); ?>');
			}
		} else {
			$(this_form).find('.epic-edit').slideUp(function() {
				if ($(this_form).find('.epic-main').hasClass('epic-main-compact')) {
				$(this_form).find('.epic-main').hide();
				}
				$(this_form).find('.epic-view').slideDown();
				$(this_form).find('.epic-field-edit a.epic-fire-editor').html('<?php _e('Edit Profile','epic'); ?>');
			});
		}
	});
	
	/* Registration Form: Blur on email */
	$('.epic-registration').find('#reg_user_email').change(function(){
		var new_user_email = $(this).val();
		$('.epic-registration .epic-pic').load('<?php echo epic_url; ?>ajax/epic-get-avatar.php?email=' + new_user_email );
	});
	
	/* Change display name as User type in */
	$('.epic-registration').find('#reg_user_login').bind('change keydown keyup',function(){
		$('.epic-registration .epic-name .epic-field-name').html( $('#reg_user_login').val() );
	});

    // New Password request JS Code
    jQuery('[id^=epic-forgot-pass-]').on('click', function(){
        var counter = jQuery(this).attr('id').replace('epic-forgot-pass-','');        
        jQuery('#epic-login-form-'+counter).css('display','none');
        jQuery('#epic-forgot-pass-holder-'+counter).css('display','block');
        jQuery('#login-heading-'+counter).html('<?php _e('Forgot Password','epic')?>');        
    });
    
    jQuery('[id^=epic-back-to-login-]').on('click', function(){
        var counter = jQuery(this).attr('id').replace('epic-back-to-login-','');        
        jQuery('#epic-login-form-'+counter).css('display','block');
        jQuery('#epic-forgot-pass-holder-'+counter).css('display','none');
        jQuery('#login-heading-'+counter).html('<?php _e('Login','epic')?>');        
    });
    
    jQuery('[id^=epic-forgot-pass-btn-]').on('click', function(){
    	var counter = jQuery(this).attr('id').replace('epic-forgot-pass-btn-','');        
        if(jQuery('#user_name_email-'+counter).val() == '') {
            alert('<?php _e('Please provide username or email address to reset password.','epic')?>');
        }
        else  {
        	jQuery.post(
    					'<?php echo admin_url( 'admin-ajax.php' )?>',    {
    				        'action': 'request_password',
    				        'user_details':   jQuery('#user_name_email-'+counter).val()
    				    }, 
    				    function(response){							
    				    	var forgot_pass_msg=	{
    				    	        "invalid_email" : "<?php _e('Please provide email address which is registered with us.','epic')?>",
    				    	        "invalid"       : "<?php _e('Please enter valid user name or email address.','epic')?>",
    				    	        "not_allowed"   : "<?php _e('User with given details is not allowed to change password.','epic')?>",
    				    	        "mail_error"    : "<?php _e('We are unable to deliver email to your email address. Please contact site admin.','epic')?>",
    				    	        "success"       : "<?php _e('We have sent password reset link to your email address.','epic')?>",
    				    	        "default"       : "<?php _e('Something went wrong, Please try again','epic');?>"        
    				    	    }
        				    if(typeof(forgot_pass_msg[response]) == 'undefined') {
        				    	alert(forgot_pass_msg['default']);
            				}
        				    else {
        				    	alert(forgot_pass_msg[response]);
        				      if(response == 'success')
        				    	    jQuery('#epic-back-to-login-'+counter).trigger('click');
            				}    				    	
    				    }
    				);
				}
    });
    jQuery("[id^=epic-forgot-pass-holder-]").css('display','none');
});
</script>