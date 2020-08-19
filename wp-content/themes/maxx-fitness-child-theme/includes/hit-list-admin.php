<?php 
/**
* Hit list admin section class
*
* This is responsible for all actions ,
*	which are performed by admin to manage  hit list challenges
*/
class hit_list_challenges_admin {

	public function __construct(){
		add_shortcode( 'hit_list_users_search_box',array($this,'hit_list_users_search_box'));
		add_shortcode('display_challenges_by_user',array($this,'display_challenges_by_user'));
		add_action( 'wp_ajax_challenges_admin_ajax',array($this,'challenges_admin_ajax'));
		add_action('wp_ajax_nopriv_challenges_admin_ajax',array($this,'challenges_admin_ajax'));
	}

	/*-----------------------------------------------------------------------------------*/
	/*		  		Shortcode to display Hit List Challenges by user					*/
	/*---------------------------------------------------------------------------------*/
	public function display_challenges_by_user(){
		//if ( is_admin() ) {
			ob_start();
			echo do_shortcode('[hit_list_users_search_box]');
			echo '<div class="uk-grid tm-page-workouts admin-hit-list" id="admin-hit-list" data-uk-grid-match="" data-uk-grid-margin="">';
			echo'</div>';
			$o	=	ob_get_contents();
			ob_clean();
			return $o;
		//}
		//else{
		//	echo'<h1>Sorry You are not Administrator</h1>';
		//}
	}
	
	public function challenges_admin_ajax(){
		if($_REQUEST){
			$uid		=	$_REQUEST['uid'];
			$pid		=	$_REQUEST['pid'];
			$act		=	$_REQUEST['act'];
			$dpa_code 	= $_REQUEST['dpa_code'];
			if($act=='retrive_hit_list_challenges'){
				$this->retrive_hit_list_challenges_ajax($uid);
			}
			if($act=='unlock_challenge_ajax'){
				$this->unlock_challenge_ajax($uid,$dpa_code);
			}
		}
	}

	/**
	*	Returns Selected Challenges
	*
	*	@parm user id
	*
	*	Retrun: Array();
	*/
	public function retrive_hit_list_challenges_ajax($uid){
		$challenges	=	json_decode(get_user_meta($uid,'challenge_hit_list',true));
		foreach($challenges as $challenge_id){
			if($challenge_id!=0){
				$unlocked	=	dpa_has_user_unlocked_achievement($uid, intval($challenge_id));
				?>
				<div class="uk-width-medium-1-3" pid="<?php echo $challenge_id;?>">
					<article id="item-<?php echo $challenge_id;?>" <?php post_class('uk-article rltv');?> data-permalink="<?php echo get_the_permalink($challenge_id); ?>">
					<a class="uk-align-left " href="<?php echo get_the_permalink($challenge_id) ?>">
						<?php echo get_the_post_thumbnail($challenge_id,array(400,340));?></a>
					<div>
						<div class=" challenge-matter"><?php
						if($unlocked){?>
							<img class="unlck"src="<?php echo get_stylesheet_directory_uri();?>/unlock.png"><?php
						}?>
							<div>
								<h6 class="tm-uppercase">Name</h6>
								<span class="tm-uppercase"> <?php echo get_the_title($challenge_id);?></span>
							</div>
							<div>
							  <h6 class="tm-uppercase">Category</h6><?php
								$cats	=	get_the_category($challenge_id);
								foreach($cats as $cat){
									echo ' <span class="tm-uppercase">'.$cat->name.'</span>';
								}?>
							</div>
							<div>
							  <h6 class="tm-uppercase">Points</h6>
							  <span class="tm-uppercase"><?php echo get_post_meta(intval($challenge_id), '_dpa_points',true); ?></span>
							</div>
							<div><?php
							if(!$unlocked){
								?><a href="javascript:void(0)" class="unlock-achievement-btn" id="<?php echo $challenge_id;?>" dpa_code="<?php echo get_post_meta(intval($challenge_id), '_dpa_redemption_code',true); ?>">Unlock Achievement</a><?php
							}
							else{
								?><a href="javascript:void(0)" class="unlock-achievement-btn remove" id="<?php echo $challenge_id;?>" style="pointer-events: none;  background: #ed1c24"> Unlock Achievement</a><?php
							}
						?></div>
						</div>
					</div>
					</article>
				</div><?php
			}
		}
		die();
	}

	function unlock_challenge_ajax($uid,$dpa_code){
		$redemption_code = $dpa_code;
		$redemption_code = apply_filters('dpa_form_redeem_achievement_code',$redemption_code);
		$achievements = dpa_get_achievements( array(
							'meta_key'   => '_dpa_redemption_code',
							'meta_value' => $redemption_code,
		) );
		if(empty($achievements)){
			$response 	=	array(
							'response' => 0,
							'message'  => 'That code was invalid. Try again!'
						);
		}
		$existing_progress = dpa_get_progress( array('author' => $uid));
		foreach ( $achievements as $achievement_obj ) {
			$progress_obj = array();
			foreach ( $existing_progress as $progress ) {
				if ( $achievement_obj->ID === $progress->post_parent ){
					if ( dpa_get_unlocked_status_id() === $progress->post_status )
						$progress_obj = false;
					else
						$progress_obj = $progress;
					break;
				}
			}
			if(false !== $progress_obj)
				dpa_maybe_unlock_achievement( $uid, 'skip_validation', $progress_obj, $achievement_obj );
			$response 	=	array(
							'response' => 1,
							'message'  => 'Achievement Successfully Unlocked !'
						);
		}
		echo json_encode($response);
		die();
	}

	/*********************************************/
	/*			SEARCH BOX SHORTCODE			*/
	/*******************************************/
	public function hit_list_users_search_box(){
		add_thickbox();?>
		<div style="margin:0 auto;padding-left:32%">
			<input type="hidden" name="ajax_loader" id="ajax_loader" value="<?php echo get_template_directory_uri(); ?>/images/ajax.gif"/>
			<input type="hidden" id="hit_email" name="hit_email" />
			<input type="hidden" id="hit_program_id" name="hit_program_id"/>
			 <input type="hidden" id="hit_gender" name="hit_gender" />
			<input type="hidden" id="hit_age" name="hit_age"/>
			<span style="padding-left:10%">
				<input placeholder="Search Client" class="user-menus-title" type="text" name="search_user" id="search_user" autocomplete="off" />
			</span>
			<span id="search_hit_program" style="padding-left:3%"><button  class="serch_hit_list">Search Client</button></span>
		</div>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.css">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(e){(function($){
				var availableTags = <?php echo epic_get_list_of_all_user(); ?>;
				jQuery('#search_user').autocomplete({
					source:availableTags,select:function(event,ui){
						event.preventDefault();
						console.log(ui.item);
						$('#hit_email').val(ui.item.value);
						$('#hit_gender').val(ui.item.gender);
						$('#hit_age').val(ui.item.age)
						var lbl=$(this).val(ui.item.label);
					},
				});
				jQuery('#search_user').keyup(function(){
					var user_name =	jQuery.trim( jQuery( this ).val());
					if( user_name == ''){
						jQuery( '#email_hit_program' ).hide();
						jQuery( '#print_hit_program' ).hide();
						jQuery( '#selected_hit_programs' ).empty();
						jQuery( '#selected_hit_programs_content' ).empty();
					}
				});
			}(jQuery))});
		</script>
		<?php
	}
}
$class	=	new hit_list_challenges_admin();