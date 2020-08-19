<?php
/**
 * Social registration and login functionality for Twitter
 *
 * This class provides the common functionality required for connecting to LinkedIn
 * network and managing registration of new users for epic.
 *
 * @package     epic Social 
 * @subpackage  -
 * @since       1.0
 */
class epic_Twitter_Connect extends epic_Social_Connect{
	
	/**
	 * Connceting to Twitter network for retreiving profile informaion
	 *
	 * @access public
	 * @since 1.0
	 * @return void 
	 */
	public function login(){
        $epic_user_role		= isset($_GET['epic_user_role']) ? $_GET['epic_user_role'] : '';
		$callback_url 	= epic_add_query_string($this->callback_url(), 'epic_social_login=Twitter&epic_social_action=verify&epic_user_role='.$epic_user_role);
		$epic_social_action		= isset($_GET['epic_social_action']) ? $_GET['epic_social_action'] : '';
		$response 		= new stdClass();

		/* Configuring settings for Twitter application */
		$app_config	= array(
			'appKey'		=>  $this->epic_settings['social_login_twitter_app_key'],
			'appSecret'    	=>  $this->epic_settings['social_login_twitter_app_secret']
		);

		@session_start();		

		if ($epic_social_action == 'login'){

			$twitter_api = new  epic_TwitterOAuth($app_config['appKey'], $app_config['appSecret']);

			/* Retrive access token from Twitter */
			$response_twitter = $twitter_api->getRequestToken($callback_url);

			$_SESSION['oauth_token'] = $response_twitter['oauth_token'];
			$_SESSION['oauth_token_secret'] = $response_twitter['oauth_token_secret'];

			if($twitter_api->http_code == 200){
			    /* Generate the URL and redirect to Twitter for authentication */
			    $url = $twitter_api->getAuthorizeURL($response_twitter['oauth_token']);

			    $this->redirect($url);
			} else {
			    $response->error_code 	 = $twitter_api->http_code;
				$response->error_message = epic_language_entry('Request token retrieval failed');

				$this->handle_social_error('Twitter',$response->error_code);
			}

		}elseif(isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier'])){

			$twitter_api = new epic_TwitterOAuth($app_config['appKey'], $app_config['appSecret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

			$access_token = $twitter_api->getAccessToken($_GET['oauth_verifier']);

			$_SESSION['access_token'] = $access_token;

			$user_info = $twitter_api->get('account/verify_credentials');

			if($user_info){

				$response->status 		= TRUE;
				$response->epic_network_type = 'twitter';
				$response->first_name	= str_replace(' ','',$user_info->name);
				$response->last_name	= '';
				$response->email		= $user_info->screen_name.'@twitter.com';
				$response->username		= $user_info->screen_name;
				$response->error_message = '';

			}else{
				/* Handling Twitter specific errors */
				$response->status 		= FALSE;
				$response->error_code 	= 'user_profile_failed';
				$response->error_message= epic_language_entry('Error retrieving profile information');
				$this->handle_social_error('Twitter',$response->error_code);
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
