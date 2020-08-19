<?php
/**
 * Social registration and login functionality for LinkedIn
 *
 * This class provides the common functionality required for connecting to LinkedIn
 * network and managing registration of new users for epic.
 *
 * @package     epic Social 
 * @subpackage  -
 * @since       1.0
 */
class epic_LinkedIn_Connect extends epic_Social_Connect{
	
	/**
	 * Connceting to LinkedIn network for retreiving profile informaion
	 *
	 * @access public
	 * @since 1.0
	 * @return void 
	 */
	public function login(){
        $epic_user_role		= isset($_GET['epic_user_role']) ? $_GET['epic_user_role'] : '';
		$callback_url 	= epic_add_query_string($this->callback_url(), 'epic_social_login=Linkedin&epic_social_action=verify&epic_user_role='.$epic_user_role);
		$epic_social_action		= isset($_GET['epic_social_action']) ? $_GET['epic_social_action'] : '';
		$response 		= new stdClass();

		/* Configuring settings for LinkedIn application */
		$app_config	= array(
			'appKey'		=>  $this->epic_settings['social_login_linkedin_app_key'],
			'appSecret'    	=>  $this->epic_settings['social_login_linkedin_app_secret'],
			'callbackUrl'	=>	$callback_url
		);

		@session_start();
		$linkedin_api = new epic_LinkedIn($app_config);

		if ($epic_social_action == 'login'){
			/* Retrive access token from LinkedIn */
			$response_linkedin = $linkedin_api->retrieveTokenRequest(array('scope'=>'r_emailaddress'));
			
			if($response_linkedin['success'] === TRUE) {
				/* Redirect the user to LinkedIn for login and authorizing the application */
				$_SESSION['oauth']['linkedin']['request'] = $response_linkedin['linkedin'];				
			  	$this->redirect(epic_LinkedIn::_URL_AUTH . $response_linkedin['linkedin']['oauth_token']);

			}else{
				$response->error_code 	 = 'req_token_fail';
				$response->error_message = epic_language_entry('Request token retrieval failed');

				$this->handle_error('Linkedin',$response->error_code);
			}
		}elseif(isset($_GET['oauth_verifier'])){

			/* LinkedIn has sent a response, user has granted permission, take the temp access 
			   token, the user's secret and the verifier to request the user's real secret key */
			$response_linkedin = $linkedin_api->retrieveTokenAccess($_SESSION['oauth']['linkedin']['request']['oauth_token'], $_SESSION['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);
			
			if($response_linkedin['success'] === TRUE){

				$linkedin_api->setTokenAccess($response_linkedin['linkedin']);
          		$linkedin_api->setResponseFormat(epic_LinkedIn::_RESPONSE_JSON);

          		/* Get user profile information using the retrived access token */
				$user_result = $linkedin_api->profile('~:(email-address,id,first-name,last-name,picture-url)');

				if($user_result['success'] === TRUE) {

					/* setting the user data object from the response */
				  	$data = json_decode($user_result['linkedin']);
				 	$response->status 		= TRUE;
					$response->epic_network_type = 'linkedin';
					$response->first_name	= $data->firstName;
					$response->last_name	= $data->lastName;
					$response->email		= $data->emailAddress;
					$response->username		= $data->emailAddress;
					$response->error_message = '';

				}else{

					/* Handling LinkedIn specific errors */
					$response->status 		= FALSE;
					$response->error_code 	= 'req_profile_fail';
					$response->error_message= epic_language_entry('Error retrieving profile information');
					$this->handle_social_error('Linkedin',$response->error_code);
				}
			}else{
				/* Handling LinkedIn specific errors */
				$response->status 		= FALSE;
				$response->error_code 	= 'access_token_fail';
				$response->error_message= epic_language_entry('Access token retrieval failed');
				$this->handle_social_error('Linkedin',$response->error_code);
			}
		}else{
			/* Handling LinkedIn specific errors */
			if( isset( $_GET['oauth_problem'] ) && $_GET['oauth_problem'] =='user_refused'){
				$response->status 		= FALSE;
				$response->error_code 	= 'user_refused';
				$response->error_message= epic_language_entry('User refused by application.');
				$this->handle_social_error('Linkedin',$response->error_code);
			}else{
				$response->status 		= FALSE;
				$response->error_code 	= 'req_cancel';
				$response->error_message= epic_language_entry('Request cancelled by user!');
				$this->handle_social_error('Linkedin',$response->error_code);
			}
		}
		return $response;
	}

	
	/**
	 * Redirect the user to login or automatically log the user based on the settings
	 *
	 * @access public
	 * @param $user_id User ID
	 * @since 1.0
	 * @return void 
	 */
	public function automatic_user_login($user_id){
		global $epic_register;
		
		// Set automatic login based on the setting value in admin
    	$activation_status = get_user_meta($user_id, 'epic_activation_status',true);
    	$approval_status = get_user_meta($user_id, 'epic_approval_status',true);    	
            
        $this->redirect_registered_users($user_id,$activation_status,$approval_status,'login');
	}
}
