<?php
/*
Plugin Name: Epic Win
Plugin URI: #
Description: Epic Win plugin user to manage functionaly of other plugin user achievements/upme.
Version: 0.1
Tested up to: 4.3
Author:Manoj Singh (Code Flox)
Author URI: http://www.manojsinghrwt.wordpress.com
Text Domain: epic
Domain Path: /lang/
*/
require_once('cat-img.php');
require_once('notificationarea.php');
require_once('classes-schedule.php');
require_once('epic-attendance.php');
require_once('class-user-unlock-achievement-widget.php');
require_once('inc/register-epic-tracking.php');
require_once('epic-option.php');

/*-----------------------------------------------------------------------*/
/* EPIC WIN ACHIEVEMENTS WIDGET CLASS TO DISPLAY ON MEMBER PROFILE PAGE */
/*---------------------------------------------------------------------*/
class MS_Epic_Win_Achievements_Widget extends WP_Widget {

	function __construct() {
		parent::__construct('MS_Epic_Win_Achievements_Widget',__('Achievements Widget for Profile Page', 'epic'),
		array( 'description' => __( 'This widget show category icon from Achievements', 'epic')));
	}

	public function widget($args, $instance){
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if(!empty($title))
		echo $args['before_title'].$title.$args['after_title'];
		echo do_shortcode('[memberachiev]');
		echo $args['after_widget'];
	}

	public function form($instance ){
		if(isset($instance['title'])){
			$title = $instance[ 'title' ];
		}
		else{
			$title = __('','epic');
		}	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
		</p><?php
	}

	public function update($new_instance,$old_instance){
		$instance = array();
		$instance['title']=(!empty($new_instance['title'])) ? strip_tags($new_instance['title']):'';
		return $instance;
	}
}

/*--------------------------------------*/
/* EXECUTE PROFILE ACHIEVEMENTS WIDGET */
/*------------------------------------*/
function ms_load_epic_win_achievements_widget(){
	register_widget('MS_Epic_Win_Achievements_Widget');
}
add_action('widgets_init','ms_load_epic_win_achievements_widget');

/*--------------------------------------------------------------------------*/
/* SHORTCODE FOR GET ACHIEVEMENTS CATEGORIES LIST FOR MEMEBER PROFILE PAGE */
/*------------------------------------------------------------------------*/
add_shortcode('memberachiev','get_epic_categories_from_achievemetns');
function get_epic_categories_from_achievemetns(){
	$args=array('orderby'=>'name','order'=>'ASC','hide_empty'=>false,'exclude'=>array(1),'hierarchical'=>true,'childless'=>false);
	$taxonomies = array('acategories');
	$term=get_terms($taxonomies,$args);
	$o='';
	$o.='<ul class="memberachiev">';
	foreach($term as $cat){
		$term_id	=	$cat->term_id;
		$name		=	$cat->name;
		$slug		=	$cat->slug;
		$count		=	$cat->count;
		$o.='<li><a href="'.get_site_url().'/acategories/'.$slug.'"><img term="'.$term_id.'" src="'.z_taxonomy_image_url($term_id).'" alt="'.$name.'" width="120" height="120"><span>';
		if(ms_count_post_in_cat($term_id)){
			$o.=ms_count_post_in_cat($term_id);
		}
		else{
			$o.='0';
		}
		$o.='/'.$count.'</span></a></li>';
	}
	$o.='</ul>';
	return $o;
}

function ms_get_current_user_achievement(){
	if(isset($_GET['member'])){$uid=$_GET['member'];}
	else{$uid=get_current_user_id();}
	$achievements = get_posts( array(
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'numberposts'         => -1,
		'post_status'         => 'publish',
		'post_type'           => dpa_get_achievement_post_type(),
		'suppress_filters'    => false,
	));
	$ach=array();
	foreach($achievements as $post){
		if(dpa_has_user_unlocked_achievement($uid,$post->ID)){
			$ach[]=$post->ID;
		}
	}
	return $ach;
}

function ms_count_post_in_cat($term_id){
	$o=0;
	$a=0;
	$posts=ms_get_current_user_achievement();
	for($i=0;$i<count($posts);$i++){
		if( has_term( $term_id, 'acategories',$posts[$i] ) ) {
			$o++;
		}
		$a=$o;
	}
	return $a;
}

add_action('wp_footer','ms_ad_memberachiev_style');
function ms_ad_memberachiev_style(){
	?>
	<style type="text/css">
	.classes-schedule-heading.dslc-clearfix{margin-bottom:20px;margin-top:20px}.memberachiev li{display:inline-block;max-width:25%;padding:0 0.8%}.memberachiev li span{display:block;clear:both;color:#fff;text-align:center;padding-bottom:5px}.memberachiev li img{cursor:pointer}.memberachiev li img:hover{opacity:0.6}.catmemberachi li{display:inline-block}a.aidclick{display:inline-block;padding:3px;background:#f1f1f1;width:40px;height:40px;border-radius:2px;position:relative}a.aidclick:hover{opacity:0.5;background:rgba(204,0,0,1)}a.aidclick:hover>span{display:block}.aidclicimg{display:block;background:rgba(0,0,0,0.9);border:1px solid rgba(0,0,0,0.9);left:0px;position:absolute;bottom:-35;width:auto;z-index:50;color:#fff;border-radius:4px;padding:5px;min-width:240px;font-size:12px;font-weight:normal}
	</style>
	<?php
	if(isset($_GET['member'])){
		$uid=$_GET['member'];
	}
	else{
		$uid=get_current_user_id();
	}
	?>
	<script>
		jQuery(document).ready(function(e){(function($){
			$( document ).on('hover','.aidclick',function(){$('.aidclick span').each(function(index, element){$(this).remove()})});
			$( document ).on('hover','.aidclick img',function(){
				var alt=$(this).attr('alt');
				$(this).parent('a').append('<span class="aidclicimg">'+alt+'</span>').mouseout(function(){
					$(this).children('span').remove();
					$('.aidclick span').each(function(index, element) {
						$(this).remove();
					});
				});
			});

			$( document ).on('mouseout','.aidclick img',function(){
				$(this).parent('a').remove('.aidclicimg');
			});
		}(jQuery))});
	</script><?php
}

add_action('wp_ajax_ms_get_current_user_achivment_from_click_cat','ms_get_current_user_achivment_from_click_cat');
add_action('wp_ajax_nopriv_ms_get_current_user_achivment_from_click_cat','ms_get_current_user_achivment_from_click_cat');

function ms_get_current_user_achivment_from_click_cat(){
	if(isset($_REQUEST)){
		$term=$_REQUEST['term'];
		if(isset($_REQUEST['uid'])){
            $uid=$_REQUEST['uid'];
        }
        else{$uid='';}
		$args = array(
					'posts_per_page'=>	-1,
					'orderby'	 	=> 'date',
					'order'			=> 'DESC',
					'post_type'	=> 'achievement',
					'post_status'	=> 'publish',
					'tax_query'	=>	array(
											array(
												'taxonomy'	=>	'acategories',
												'field' 	=>	'term_name',
												'terms' 	=>	$term
											)
										)
				);
		$posts=get_posts($args);
		echo '<h3>'.get_term($term,'acategories')->name.'</h3>';
		echo '<ul class="catmemberachi">';
		foreach($posts as $post){
			$id			=	$post->ID;
			$title		=	$post->post_title;
			$postids 	=	ms_get_current_user_achievement();
			if(dpa_has_user_unlocked_achievement($uid,$id)){
				?>
				<li>
					<a class="aidclick" term="<?php echo $term; ?>" uid="<?php echo $uid; ?>" href="javascript:void(0)" achiun="1" aid="<?php echo $id; ?>"><?php echo get_the_post_thumbnail($id, 'dpa-thumb',array('alt'=>dpa_get_achievement_title($id))); ?></a>
				</li>
	<?php 	}
			else{
				?>
				<li>
					<a class="aidclick" term="<?php echo $term; ?>" uid="<?php echo $uid; ?>" href="javascript:void(0)" achiun="0" aid="<?php echo $id; ?>"><img src="<?php echo plugins_url('img/red.png',__FILE__); ?>" alt="<?php echo dpa_get_achievement_title($id); ?>"/></a>
				</li>
	<?php	}
		}
		echo '</ul>';
	}
	die();
}

//	Initialize shortcode for user unlock redeem achievement
add_shortcode( 'user_redeem_achievement','user_redeem_achievement_block' );
function user_redeem_achievement_block( $atts = array() ){
	if( isset( $_POST[ 'unlock_redemption' ])){
		$redemption_code = isset($_POST['dpa_code']) ? sanitize_text_field(stripslashes($_POST['dpa_code'])):'';
		$redemption_code = apply_filters('dpa_form_redeem_achievement_code',$redemption_code);
		$achievements = dpa_get_achievements(
			array(
				'meta_key'   => '_dpa_redemption_code',
				'meta_value' => $redemption_code,
			));
			// Bail out early if no achievements found
			if(empty( $achievements)){
				dpa_add_error('dpa_redeem_achievement_nonce',__('That code was invalid. Try again!','dpa'));
				// If multisite and running network-wide, undo the switch_to_blog
				if(is_multisite() && dpa_is_running_networkwide())
					restore_current_blog();
			}
			else{
				$users 	= explode( '-', $_POST[ 'sqr' ] );

				foreach ($achievements as $achievement_obj ){
					$progress_obj = array();
					foreach( $users as $user_id ){
						$progress_obj = array();
						$existing_progress = array();
						$existing_progress	=	dpa_get_progress(array('author' => $user_id,'post_parent' => $achievement_obj->ID ) );
						//	echo '<pre>';print_r( $existing_progress);exit;

						foreach ( $existing_progress as $progress ) {
							if ( $achievement_obj->ID === $progress->post_parent ){
								if ( dpa_get_unlocked_status_id() === $progress->post_status )
									$progress_obj = false;
								else
									$progress_obj = $progress;
								break;
							}
						}
						if(false !== $progress_obj){
							dpa_maybe_unlock_achievement($user_id,'skip_validation',$progress_obj,$achievement_obj);
						}
					}
			}
			if(is_multisite() && dpa_is_running_networkwide())
				restore_current_blog();
			}
	} ?>
	<div><h2 style="color:#fff;">Redeem Achievement For Users</h2></div>
	<form role="search" method="post" id="dpa-user-unlockredemption-form" name="dpa-user-unlockredemption-form">
		<?php do_action( 'dpa_template_notices' ); ?>
		<input type="hidden" name="unlock_redemption" id="unlock_redemption" value="1"/>
		<p>
			<label for="dpa-redemption-code" style="color:#fff;"><?php _e( 'Select User:', 'dpa' ); ?></label>
			<!--<input class="uname" id="uname" type="text" placeholder="User Name"><input class="uid" type="hidden" name="uid" id="uid">
			-->
			<input name='tags3-1' placeholder="Search Users">
          	<input type="hidden" name="sqr" id="sqr"/>
		</p>
		<p>
			<label for="dpa-redemption-code" style="color:#fff;"><?php _e( 'Enter code:', 'dpa' ); ?></label>
			<input id="dpa-redemption-code" name="dpa_code" type="text" required />
		</p>
		<p>
			<input class="button" id="dpa-redemption-submit" value="<?php esc_attr_e( 'Unlock', 'dpa' ); ?>" type="submit" />
		</p>
		<?php //dpa_redeem_achievement_form_fields(); ?>
	</form>
	<!--
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="https://yaireo.github.io/tagify/dist/tagify.css">
    <script src="https://yaireo.github.io/tagify/dist/tagify.min.js"></script>
    <script src="https://yaireo.github.io/tagify/dist/jQuery.tagify.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	-->
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/tagify.css">
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/tagify.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ); ?>js/jQuery.tagify.min.js"></script>

	<script type="text/javascript">
		jQuery( document ).ready(function(){
			/*
			var availableTags = <?php echo epic_get_list_of_all_user_for_nuclear_code(); ?>;
			console.log( availableTags );

			jQuery('.uname').autocomplete({
				source:availableTags,select:function(event,ui){event.preventDefault();jQuery('.uid').val(ui.item.value);jQuery(this).val(ui.item.label)},
			});
			*/
			jQuery( '#dpa-redemption-submit' ).click(function(){
				var username 	=	jQuery.trim(jQuery('#sqr').val());
				var code 		=	jQuery.trim(jQuery('#dpa-redemption-code').val());
				if( username == '' ){
					alert( 'Please select the user.' );
					jQuery('[name="tags3-1"]').focus();
					return false;
				}
				else if( code == '' ){
					alert( 'Please enter achievement code.' );
					jQuery('#dpa-redemption-code').focus();
					return false;
				}
			});
		});
	</script>
	<script type="text/javascript">
            jQuery( document ).ready(function(){
            	var availableTags = <?php echo epic_get_list_of_all_user_for_nuclear_code(); ?>;
            	console.log( 'Js Files Updated' );

                var tagify = new Tagify(document.querySelector('input[name=tags3-1]'), {
                    delimiters : null,
                    tagTemplate : function(v, tagData){
                    return `<tag title='${v}'><x title=''></x><div><span>${v}</span></div></tag>`;
                },
                enforceWhitelist : true,
                whitelist : availableTags,
                dropdown : {
                    enabled: 1, // suggest tags after a single character input
                    classname : 'extra-properties', // custom class for the suggestions dropdown
                    itemTemplate : function(tagData){
                    return `<div class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'><span>${tagData.value}</span></div>`
                	}
                },
                mapValueToProp : "code", // map tags' values to this property name, so this property will be the actual value and not the printed value on the screen
                });

                tagify.on('remove', function(e){
                    console.log(e, e.detail);
                });

                tagify.on('add',function(e,tag){
	                var allv =  tagify.value;
	                $.each(allv,function(i){
	                    sv=allv[i].user_id;
	                    var search_value =  jQuery('#sqr').val();

	                    if( search_value == ''){
	                        jQuery('#sqr').val(sv);
	                    }else{
	                        var string_array = search_value.split( '-' );

	                        if(jQuery.inArray(sv, string_array) == -1)
	                            jQuery('#sqr').val(search_value+'-'+sv);
	                    }

	                });
	            });
            });
        </script>
    <style type="text/css">
        .tagify.countries .tagify__input{ min-width:175px; }
        .tagify.countries tag{ white-space:nowrap; }
    </style>
<?php
}

//	Adding Retire User field to Users
add_action( 'show_user_profile', 'retire_user_profile_field' );
add_action( 'edit_user_profile', 'retire_user_profile_field' );

/**
 *	This function add the retire user field to user profle
 *
 */

function retire_user_profile_field( $user ){
?>

	<h3><?php _e("Retire User Block", "epic"); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="retire_user"><?php _e("Retire User"); ?></label></th>
			<td><input type="checkbox" name="retire_user" id="retire_user" value="1" <?php if(esc_attr( get_the_author_meta( 'retire_user', $user->ID ) )){?>checked="checked"<?php } ?> class="regular-text" /><br /><span class="description"><?php _e("Do you want to retire this user?"); ?></span>
			</td>
		</tr>
	</table>
<?php
}

//	Saving Retire User field to database
add_action( 'personal_options_update', 'save_retire_user_profile_field' );
add_action( 'edit_user_profile_update', 'save_retire_user_profile_field' );

/**
 *	Save th retire user info for user
 */

function save_retire_user_profile_field( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
	//	echo '<pre>'; print_r( $_POST ); exit;

	update_user_meta( $user_id, 'retire_user', $_POST['retire_user'] );
}

// Add and Save new fields to user screen
add_action( 'user_new_form','add_custom_user_fields');
add_action( 'show_user_profile','add_custom_user_fields');
add_action( 'edit_user_profile','add_custom_user_fields');
add_action( 'user_register', 'epic_save_profile_fields');
add_action( 'personal_options_update', 'epic_save_profile_fields');
add_action( 'edit_user_profile_update', 'epic_save_profile_fields');

function epic_save_profile_fields( $user_id ) {
    if ( !current_user_can( 'administrator', $user_id ) )
        return false;

    $ice_client 	=	isset( $_POST['ice_client'])?$_POST['ice_client']:0;
	$pt_client 		=	isset( $_POST['pt_client'])?$_POST['pt_client']:0;
	$fire_client 	=	isset( $_POST['fire_client'])?$_POST['fire_client']:0;

    update_usermeta( $user_id, 'ice_client', $ice_client );
    update_usermeta( $user_id, 'pt_client', $pt_client );
    update_usermeta( $user_id, 'fire_client', $fire_client );
}

function add_custom_user_fields($user ){
	if ( !current_user_can( 'administrator', $user->ID ) )
        return false;


	$ice_client 	=	esc_attr( get_the_author_meta( 'ice_client', $user->ID ) );
	$pt_client 		=	esc_attr( get_the_author_meta( 'pt_client', $user->ID ) );
	$fire_client 	=	esc_attr( get_the_author_meta( 'fire_client', $user->ID ) );

    ?>
    <h3>Client Product and Services</h3>
    <table class="form-table">
        <tr>
            <th><label for="dropdown">ICE Client</label></th>
            <td>
                <input type="checkbox" class="regular-text" name="ice_client" id="ice_client" value="1" <?php if($ice_client == 1){ ?>checked="checked"<?php } ?> id="portal_cat" /><br />
            </td>
        </tr>
        <tr>
            <th><label for="dropdown">PT Client</label></th>
            <td>
                <input type="checkbox" class="regular-text" name="pt_client" id="pt_client" value="1" <?php if($pt_client == 1){ ?>checked="checked"<?php } ?>/><br />
            </td>
        </tr>
        <tr>
            <th><label for="dropdown">FIRE Client</label></th>
            <td>
                <input type="checkbox" class="regular-text" name="fire_client" id="fire_client" value="1" <?php if($fire_client == 1){ ?>checked="checked"<?php } ?>/><br />
            </td>
        </tr>
    </table>
<?php
}
