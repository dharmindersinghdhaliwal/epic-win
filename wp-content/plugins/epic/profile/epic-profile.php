<?php
add_action('wp_ajax_epic_delete_profile_images', 'epic_delete_profile_images');
add_action('wp_ajax_nopriv_epic_delete_profile_images', 'epic_delete_profile_images');
// Delete the profile images from the edit screen
function epic_delete_profile_images() {
    global $user;	
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $custom_field_name = isset($_POST['field_name']) ? $_POST['field_name'] : '';
    if (is_user_logged_in ()) {
        $current_user_id = get_current_user_id();
        if (current_user_can('edit_user', $current_user_id) || current_user_can('manage_epic_options', $current_user_id) ) {
            $image_url = esc_url(get_the_author_meta($custom_field_name, $user_id));
            $del_status = epic_delete_uploads_folder_files($image_url);
            if ($del_status && delete_user_meta($user_id, $custom_field_name)) {
                echo json_encode(array("status" => TRUE));
            } else {
                echo json_encode(array("status" => FALSE));
            }
        }
    }
    die();
}

// Include the frontend scripts to Iframe for crop funcitionality
add_action('epic_crop_iframe_head', 'epic_crop_iframe_head');

function epic_crop_iframe_head() {
    $html  = '<link type="text/css" href="'.epic_url . 'css/font-awesome.min.css." rel="stylesheet" />';
    $html .= '<link type="text/css" href="'.epic_url . 'css/epic.css" rel="stylesheet" />';
    $html .= '<link type="text/css" href="'.site_url('wp-includes/js/jcrop/jquery.Jcrop.min.css').'" rel="stylesheet" />';    
    /* Add style */
    $settings = get_option('epic_options');
    if ($settings['style']) {
        $html .= '<link type="text/css" href="'.epic_url . 'styles/' . $settings['style'] . '.css" rel="stylesheet" />';
    }
    $html .= '<link type="text/css" href="'.epic_url . 'css/epic-responsive.css" rel="stylesheet" />';
    $html .= '<script type="text/javascript" src="'.site_url('wp-includes/js/jquery/jquery.js') . '" ></script>';
    $html .= '<script type="text/javascript" src="'.site_url('wp-includes/js/jquery/jquery-migrate.min.js').'" >
	</script>';
    $html .= '<script type="text/javascript" src="'.site_url('wp-includes/js/jcrop/jquery.Jcrop.min.js') . '" >
	</script>';
    $html .= '<script type="text/javascript" src="'.epic_url . 'js/epic-custom.js" ></script>';
    $html .= '<script type="text/javascript" src="'.epic_url . 'js/epic-crop.js" ></script>';
    $del_msg = __("Are you sure you want to delete this image?", "epic");
    $upload_msg = __("Please select an image to upload.", "epic");
    $admin_ajax = admin_url("admin-ajax.php");
    $html .= '<script type="text/javascript">
                var epicCustom={"Messages":{"DelPromptMessage":"'.$del_msg.'","UploadEmptyMessage":"'.$upload_msg.'"},"AdminAjax":"'.$admin_ajax.'"};
              </script>';
    echo $html;
}
add_action('wp_ajax_epic_initialize_upload_box', 'epic_initialize_upload_box');

// Display the upload box for image uploading and cropping
function epic_initialize_upload_box() {
    global $current_user,$epic_save;
    $id = $_GET['epic_id'];
    $meta = isset($_GET['epic_meta']) ? $_GET['epic_meta'] : '';
    $disabled = isset($_GET['epic_disabled']) ? $_GET['epic_disabled'] : '';
    $settings = get_option('epic_options');
    $display = '<html>
                    <head>
                        ' . epic_crop_iframe_head() . '
                        <style type="text/css">   html{ overflow: hidden; }</style>
                    </head>
                    <body>
                        <form id="epic-crop-frm" action="" method="post" enctype="multipart/form-data">';
    $display .= '           <div class="epic-crop-wrap">';
    $display .= '           <div class="epic-wrap">';
    $display .= '               <div class="epic-field epic-separator epic-edit epic-clearfix" style="display: block;">' . __('Update Profile Picture', 'epic') . '</div>';
    $profile_pic_url = get_the_author_meta($meta, $id);
    if (is_array($epic_save->errors) && count($epic_save->errors) != 0 ) {
        if (($id == $current_user->ID || current_user_can('edit_users') || current_user_can('manage_epic_options') ) && is_numeric($id)) {
            $display .= epic_display_upload_box($id, $meta, $disabled, $profile_pic_url, 'block');
            $display .= epic_display_crop_box($id, $meta, $profile_pic_url, 'none');
        }        
    } elseif( isset($_POST['epic-upload-submit-' . $id]) || isset($_POST['epic-crop-request-' . $id]) ) {
        // Display crop area on file upload or crop link click
        if (($id == $current_user->ID || current_user_can('edit_users') || current_user_can('manage_epic_options') ) && is_numeric($id)) {
            $display .= epic_display_crop_box($id, $meta, $profile_pic_url, 'block');
        }
    } elseif (isset($_POST['epic-crop-submit-' . $id])) {
        // Crop the image on area selection and submit
        $data_x1 = isset($_POST['epic-crop-x1']) ? $_POST['epic-crop-x1'] : 0;
        $data_y1 = isset($_POST['epic-crop-y1']) ? $_POST['epic-crop-y1'] : 0;
        $data_width = isset($_POST['epic-crop-width']) ? $_POST['epic-crop-width'] : 50;
        $data_height = isset($_POST['epic-crop-height']) ? $_POST['epic-crop-height'] : 50;
        $src = get_the_author_meta($meta, $id);
        $epic_upload_path = '';
        $epic_upload_url = '';
        if ($upload_dir = epic_get_uploads_folder_details()) {
            $epic_upload_path = $upload_dir['basedir'] . "/epic/";
            $epic_upload_url = $upload_dir['baseurl'] . "/epic/";
            $src = str_replace($epic_upload_url, $epic_upload_path, $src);
        }
        if (is_readable($src)) {
            $result = wp_crop_image($src,$data_x1, $data_y1, $data_width, $data_height, $data_width, $data_height);
            if (!is_wp_error($result)) {
                $cropped_path = str_replace($epic_upload_path, $epic_upload_url, $result);
                update_user_meta($id, $meta, $cropped_path);
                $display .= epic_display_upload_box($id, $meta, $disabled, $profile_pic_url, 'block');
            }
        }
        update_crop_image_display($id,$meta,$cropped_path);
    } elseif (isset($_POST['epic-crop-save-' . $id])) {
        $src = get_the_author_meta($meta, $id);
        update_crop_image_display($id,$meta,$src);
    } else {
        if (($id == $current_user->ID || current_user_can('edit_users') || current_user_can('manage_epic_options')) && is_numeric($id)) {
            $display .= epic_display_upload_box($id, $meta, $disabled, $profile_pic_url, 'block');
            $display .= epic_display_crop_box($id, $meta, $profile_pic_url, 'none');
        }
    }
    $display .= '           </div>';
    $display .= '           </div>';
    $display .= '       </form>
                    </body>
                </html>';
    echo $display;
    exit;
}

/* Display the exisitng profile picture with image upload field  */

function epic_display_upload_box($id, $meta, $disabled, $profile_pic_url, $visibility = 'block') {
    global $epic_save;
    $display  = '';
    $display .= '   <div class="epic-field epic-edit" style="display:' . $visibility . '">
                        <div class="epic-field-value"><div class="epic-note"><strong>' . __('Current Picture:', 'epic') . ' </strong></div></div>';
    if (!empty($profile_pic_url)) {
        $display .= '       <div class="epic-field-value">
                            <div class="epic-note">
                                <img class="epic-preview-current" alt="" src="' . $profile_pic_url . '">
                                <div epic-data-user-id="' . $id . '" epic-data-field-name="' . $meta . '" class="epic-delete-userpic-wrapper">
                                    <i original-title="remove" class="epic-icon epic-icon-remove"></i> 
                                    <label class="epic-delete-image">' . __('Delete Image', 'epic') . '</label>
                                </div>
                                <div id="epic-spinner-' . $meta . '" class="epic-delete-spinner">
                                    <i original-title="spinner" class="epic-icon epic-icon-spinner epic-tooltip3"></i>
                                    <label>'.__('Loading','epic').'</label>
                                </div>
                                <div id="epic-crop-request" epic-data-user-id="' . $id . '" epic-data-field-name="' . $meta . '" class="epic-crop-image-wrapper">
                                    <i original-title="crop" class="epic-icon epic-icon-crop"></i> 
                                    <label class="epic-delete-image">' . __('Crop Image', 'epic') . '</label>
                                </div>
                                 <div class="clear"></div>
                            </div>
                        </div>
                    </div>';
    }
    if(is_array($epic_save->errors) && count($epic_save->errors) != 0){
        $display  .= '<div class="epic-clear"></div><div id="epic-crop-upload-err-holder" style="display: block;" class="epic-errors">
                            <span id="epic-crop-upload-err-block" class="epic-error epic-error-block">';
        foreach($epic_save->errors as $err){
            $display  .= '<span class="epic-error epic-error-block"><i class="epic-icon epic-icon-remove"></i>'.$err.'</span>';
        }
        $display  .= '</span></div>';
    }
    $display .= '   <div class="epic-field epic-edit" style="display:' . $visibility . '">
                        <div id="epic-crop-upload-err-holder" style="display: none;" class="epic-errors">
                                <span id="epic-crop-upload-err-block" class="epic-error epic-error-block"></span>         
                        </div><div class="epic-field-value">';
    if (epic_is_safari() || epic_is_opera()) {
        $display .= '<input class="epic-fileupload-field" ' . $disabled . ' type="file" name="' . $meta . '-' . $id . '" id="file_' . $meta . '-' . $id . '" style="display:block;" />
                     <input id="epic-upload-image" epic-data-meta="' . $meta . '" epic-data-id="' . $id . '" type="button" name="epic-upload-image-' . $id . '" class="epic-button-alt-wide epic-fire-editor" value="' . __('Upload Image', 'epic') . '" />';
    } else {
        $display .= '
                     <input class="epic-fileupload-field" ' . $disabled . ' type="file" name="' . $meta . '-' . $id . '" id="file_' . $meta . '-' . $id . '"  style="display:block;" />
                     <input id="epic-upload-image" epic-data-meta="' . $meta . '" epic-data-id="' . $id . '" type="button" name="epic-upload-image-' . $id . '" class="epic-button-alt-wide epic-fire-editor" value="' . __('Upload Image', 'epic') . '" />';
		}
    $display .= '</div></div>';
    return $display;
}
/* Display the crop image area after image upload or clicking the crop link */
function epic_display_crop_box($id, $meta, $profile_pic_url, $visibility = 'block'){
    $display='<div class="epic-field epic-edit" style="display:'.$visibility.'">
					<div class="epic-crop-column1">
						<div class="epic-field-value">
							<div class="epic-note">
								<strong>'.__('Crop Your New Profile Picture', 'epic').'</strong>
                                    <input id="epic-crop-submit" type="submit" value="'.__('Crop Image','epic').'" class="epic-button-alt epic-fire-editor" name="epic-crop-submit-'.$id.'">
                                    <input id="epic-crop-save" type="submit" value="'.__('Save Image','epic').'" class="epic-button-alt epic-fire-editor" name="epic-crop-save-'.$id.'">
                                </div>
                            </div>                        
                            <div class="epic-crop-field-value">
                                <div class="epic-note">
                                    <img id="target" alt="" src="' . $profile_pic_url . '">
                                </div>
                            </div>
                        </div>
                        <div class="epic-crop-column2">
                            <div class="epic-field-value">
                                <div class="epic-note">
                                    <input type="hidden" name="epic-crop-x1" id="epic-crop-x1"/>
                                    <input type="hidden" name="epic-crop-x2" id="epic-crop-x2"/>
                                    <input type="hidden" name="epic-crop-y1" id="epic-crop-y1"/>
                                    <input type="hidden" name="epic-crop-y2" id="epic-crop-y2"/>
                                    <input type="hidden" name="epic-crop-width" id="epic-crop-width"/>
                                    <input type="hidden" name="epic-crop-height" id="epic-crop-height"/>
                                </div>
                                <div class="epic-note">
                                    <div id="epic-preview-pane">
                                        <div class="epic-preview-container">
                                            <img src="'.$profile_pic_url.'" class="jcrop-preview" alt="Preview"/>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>';
    return $display;
}
// Adding AJAX action for logged in and guest both.
add_action('wp_ajax_epic_check_edit_email', 'epic_check_edit_email');
add_action('wp_ajax_nopriv_epic_check_edit_email', 'epic_check_edit_email');
function epic_check_edit_email() {
    $email_exists = false;
    $email_id = isset($_POST['email_id']) ? $_POST['email_id'] : 0;
    $current_user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
    $user_id = email_exists($email_id);
    if ($user_id && ($user_id != $current_user_id)) {
        $email_exists = true;		
    }
    if ($email_exists == false) {
		$new_pass		=	$_POST['new-pass']; ############//DHARMINDR SINGH (TEMP Solution)#############
		if(!empty($new_pass)){
			$update_info	=	array('ID'	 => $current_user_id,'user_pass'	=> $new_pass);
			wp_update_user($update_info);
		}
        echo json_encode(array("status" => TRUE, "msg" => "success"));
    } else if ($email_exists == true) {
        echo json_encode(array("status" => FALSE, "msg" => "email_error"));
    }
    die;
}
// Adding AJAX actions for validating registration fields.
add_action('wp_ajax_epic_validate_edit_profile_email', 'epic_validate_edit_profile_email');
add_action('wp_ajax_nopriv_epic_validate_edit_profile_email', 'epic_validate_edit_profile_email');
function epic_validate_edit_profile_email(){
    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';   
    $current_user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
    $user_id = email_exists($user_email);
    $email_exists = false;
    if ($user_id && ($user_id != $current_user_id)) {
        $email_exists = true;
    }
    if (!empty($user_email)) {
        $user_email = sanitize_email($user_email);
        if (is_email($user_email)) {
            // Check the existence of user email from database
            if ($email_exists) {
                echo json_encode(array("status" => TRUE, "msg" => "RegExistEmail"));
            } else {
                echo json_encode(array("status" => FALSE, "msg" => "RegValidEmail"));
            }
        } else {
            echo json_encode(array("status" => TRUE, "msg" => "RegInvalidEmail"));
        }
    } else {
        echo json_encode(array("status" => TRUE, "msg" => "RegEmptyEmail"));
    }
    die();
}
// Update the displayed image after cropping and close the cropping window
function update_crop_image_display($id,$meta,$image_path){ ?>
        <!-- Close the window and update the new image after cropping -->
<style type="text/css">@media only screen and (min-width: 480px) and (max-width: 767px){.jcrop-holder #epic-preview-pane{display:block;position:absolute;z-index:2000;right:-150px;padding:0 6px}}</style>
<script type="text/javascript">
jQuery(document).ready(function(){
	var userId = "<?php echo $id; ?>";
	var imageMeta = "<?php echo $meta; ?>";
	var imagePath = "<?php echo $image_path; ?>";
	var profileWindow = jQuery(this).parent();
	if(window.parent.jQuery("#epic-preview-"+imageMeta).length == 0){
		window.parent.jQuery(".epic-current-pic-note").remove();
		window.parent.jQuery("#epic-current-picture").after('<div class="epic-note epic-current-pic-note">'+
			'<img id="epic-preview-user_pic" src="'+imagePath+'" alt="">'+
			'<div class="epic-delete-userpic-wrapper" epic-data-field-name="'+imageMeta+'" epic-data-user-id="'+ userId +'">'+
			'<i class="epic-icon epic-icon-remove" original-title="remove"></i> '+
			'<label class="epic-delete-image"><?php echo __("Delete Image", "epic"); ?> </label>'+
			'</div>'+
			'<div id="epic-spinner-'+imageMeta+'" class="epic-delete-spinner"><i original-title="spinner" class="epic-icon epic-icon-spinner epic-tooltip3"></i><label><?php echo __("Loading", "epic") ?></label></div>'+
			'</div>');
		window.parent.jQuery(".epic-pic img").attr("src",imagePath);
	}else{
		window.parent.jQuery("#epic-preview-"+imageMeta).attr("src",imagePath);
		window.parent.jQuery("#epic-avatar-"+imageMeta).attr("src",imagePath);
	}
	//self.parent.tb_remove();
	parent.jQuery.fancybox.close();      
});
</script><?php 
}
add_action('wp_ajax_epic_initialize_profile_modal', 'epic_initialize_profile_modal');
add_action('wp_ajax_nopriv_epic_initialize_profile_modal', 'epic_initialize_profile_modal');

function epic_initialize_profile_modal(){
    global $current_user;
    $id = $_POST['epic_id'];
    $settings = get_option('epic_options');    
    /* epic Filter for cusmizing profile window shortcode */
    $profile_shortcode = apply_filters('epic_profile_modal_shortcode',$settings['profile_modal_window_shortcode'],$id);    
    $profile_shortcode = str_replace('[epic' ,'[epic modal_view=yes id="'.$id.'" ',$profile_shortcode);
    $display  = do_shortcode($profile_shortcode);
    echo $display;
    exit;
}