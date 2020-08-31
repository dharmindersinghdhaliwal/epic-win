<?php
/**
* Hitlist Challenge Shortcode Class
*
*This Class will Only Work with Achievement Plugin
*/

include('hit-list-admin.php');	/*Admin Section*/

class My_Challenge_Hit_list{

	public  function __construct(){
		add_shortcode('display_hit_list_challenges',array($this,'display_hit_list_challenges'));
		add_action( 'wp_ajax_my_challenge_hit_list',array($this,'my_challenge_hit_list'));
		add_action('wp_ajax_nopriv_my_challenge_hit_list',array($this,'my_challenge_hit_list'));
		add_action('wp_enqueue_scripts',array($this,'add_admin_js'));
	}

	public function add_admin_js(){
		wp_enqueue_script('hit-list',get_stylesheet_directory_uri().'/js/hit-list.js',array('jquery'),1.1,true);
	}

	/*************************************************************************************/
	/*	  		Shortcode to display Hit List Challenges Selected by User				*/
	/***********************************************************************************/
	public function display_hit_list_challenges(){
		ob_start();
		$uid	=	get_current_user_id();
		echo	$this->retrive_hit_list_challenges($uid);
		$o		=	ob_get_contents();
		ob_clean();
		return $o;
	}
	
	/*************************************************************************/
	/*					AJAX FUNCTION FOR HIT LIST PROGRAM					*/
	/***********************************************************************/
	public function my_challenge_hit_list(){
		if($_REQUEST){
			$act			=	$_REQUEST['act'];
			$uid			=	get_current_user_id();
			$challenge_id	=	$_REQUEST['challenge_id'];
			if($act=='add_to_hit_list'){
				$this->update_challenge_for_user($uid,$challenge_id);
			}
			if($act=='remove_hit_list'){
				$this->remove_hit_list($uid,$challenge_id);
			}
		}
		die();
	}
	
	/**  Update Challenges Ids
	*
	*	@param  new challenge id
	*
	*	@user id
	*
	*	add latest challenges with previously added challenges
	*/
	public function update_challenge_for_user($uid,$challenge_id){
		$challenge_ids	=json_decode(get_user_meta($uid,'challenge_hit_list',true));		
		if($challenge_ids==''){
			$challenge_ids	=	array('0',$challenge_id);
			$json = json_encode($challenge_ids);			
			if(update_user_meta($uid,'challenge_hit_list',$json)){
				$response = array( 'response' => 1 );
			}
			else{
				$response = array( 'response' => 0 );
			}
		}
		else{
			if(!in_array($challenge_id,$challenge_ids)){
				array_push($challenge_ids,$challenge_id);
				$json = json_encode($challenge_ids);			
				if(	update_user_meta($uid,'challenge_hit_list',$json)){
					$response = array( 'response' => 1 );
				}else{
					$response = array( 'response' => 0 );
				}
			}else{
				$response = array( 'response' => 0 );
			}
		}
		echo json_encode( $response );
	}

	/**
	*	Returns Selected Challenges
	*
	*	@param user id
	*
	*	Retrun: Array();
	*/
	public function retrive_hit_list_challenges($uid){
		if(!empty($uid)){
			$challenges	=	json_decode(get_user_meta($uid,'challenge_hit_list',true));?>
			<div class="hit-list-main uk-grid tm-page-workouts">
				<p class="achead"> Your Hit List</p><?php
					foreach($challenges as $challenge_id){
						if($challenge_id!=0){
							echo $this->hit_list_template($challenge_id,$uid);
						}
					}?>
			</div><?php
		}
	}

	/**
	* Template to display hit list Challenges
	*
	*	@param Post id
	*/
	public function hit_list_template($challenge_id,$uid){
		?><div class="uk-width-medium-1-3 ac-col uk-grid-margin"><?php
			if(dpa_has_user_unlocked_achievement($uid,intval($challenge_id))){
				?><img class="unlck"src="<?php echo get_stylesheet_directory_uri();?>/unlock.png"><?php
			}?>
			<article  class="uk-article" data-permalink="<?php echo get_the_permalink($challenge_id);?>">
				<a class="uk-align-left" href="<?php echo get_the_permalink($challenge_id);?>">
					<?php echo get_the_post_thumbnail($challenge_id,array(200,140)); ?>
				</a>
				<div class="uk-panel uk-width-1-1 ">
					<a href="javascript:void(0)"class="remove-hit-list"id="<?php echo $challenge_id;?>"><i class="fa fa-trash fa-6" aria-hidden="true"></i></a>
					<div class="uk-grid">
						<div class="uk-width-1-3">
							<span class="tm-uppercase"> <?php echo get_the_title($challenge_id);?></span>
						</div>
					</div>
				</div>
			</article>
		</div> <?php
	}
	/** Remove Challenge From hit List
	*
	* @param Challenge id
	*
	*/
	public function remove_hit_list($uid,$challenge_id){
		$challenges	=	json_decode(get_user_meta($uid,'challenge_hit_list',true));
		$new_array		=	$this->array_delete(intval($challenge_id),$challenges);
		$json 			= 	json_encode($new_array);
		$update_meta	=	update_user_meta($uid,'challenge_hit_list',$json);
		if($update_meta){
			$response	=	array('response'	=>	1 );
		}
		else{
			$response	=	array('response'	=>	0 );
		}
		echo json_encode($response);
	}

	/** Delete an array element
	*
	* @param delete value and an array
	*
	*/
	public function array_delete($del_val,$array) {
		if(is_array($del_val)) {
			 foreach ($del_val as $del_key => $del_value) {
				foreach ($array as $key => $value){
					if ($value == $del_value) {unset($array[$key]);	}
				}
			}
		} else {
			foreach ($array as $key => $value){
				if ($value == $del_val) {	unset($array[$key]);}
			}
		}
		return array_values($array);
	}

}
$class= new My_Challenge_Hit_list();