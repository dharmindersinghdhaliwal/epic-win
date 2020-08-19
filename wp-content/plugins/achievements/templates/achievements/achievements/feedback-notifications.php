<?php

/**
 * Notifications template part; usually used for the "you've unlocked an achievement!" pop-ups.
 *
 * @package Achievements
 * @since Achievements (3.5)
 * @subpackage ThemeCompatibility
 */

// Exit if accessed directly
if(!defined('ABSPATH')) exit;
function epic_is_valid_achievements_code($dpaapid){
	global $wpdb;
    $query = "SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key`='_dpa_redemption_code' AND `meta_value`='".$dpaapid."'";
    $apid = $wpdb->get_var($query);
	if($apid){
		return true;
	}
	else{
		return false;
	}
}
function epic_get_current_unlock_achievements($dpaapid){

	global $wpdb;
    $query = "SELECT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key`='_dpa_redemption_code' AND `meta_value`='".$dpaapid."'";
    $apid = $wpdb->get_var($query);
	$achievementid=$apid;	
	$post=get_post($achievementid);
	$dpa_points=get_post_meta($post->ID,'_dpa_points',true);
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
	$cats=get_the_category($post->ID);
	$o='';
	$o.='<div id="achlbox">';
		/*$o.='<a href="javascript:void(0)" onClick="swal.close()" class="closesa">Close</a>';*/
		$o.='<div id="acleft">';
		$o.='<span class="achunl">Achievement unlocked!</span>';
		$o.='<img src="'.$feat_image.'" width="250" height="250">';
		$o.='</div>';		
		$o.='<div id="acright">';
			$o.='<h3>'; foreach($cats as $cat){ $o.=$cat->name; } $o.=' &nbsp; &nbsp; '.$dpa_points.'pts'; $o.='</h3>';
			$o.='<span class="acoutcont" style="background:none">';
			$o.='<h4>'.$post->post_title.'</h4>';
				$o.='<span class="acincont">';
				$o.='<i>Hey, you have unlocked the "'.$post->post_title.'" achievement. Congratulations!</i><br><br><br><br>';
				$o.='<a href="javascript:void(0)" onClick="swal.close()" class="closesa">Close</a>';
				## Button Removed By Client
				/*$o.='<a style="display:inline-block;background-color:rgb(174,222,244);box-shadow:0px 0px 2px rgba(174,222,244,0.8),0px 0px 0px 1px rgba(0,0,0,0.05) inset;color:#fff;padding:10px 15px;border-radius:2px" class="dpa-toast-cta" href="'.esc_url(dpa_get_user_avatar_link('type=url&user_id='.get_current_user_id())).'">'.__('See your other achievements','dpa').'</a>';*/
				$o.='</span>';
			$o.='</span>';
		$o.='</div>';
	$o.='</div>';
	return $o;
}
	if(isset($_POST['dpa_code'])){
		if(epic_is_valid_achievements_code($_POST['dpa_code'])){
			//' Achievement Unlocked Successfully';
			?>
<script>jQuery(document).ready(function(e){(function($){	 
	swal({
		title:'',
		text:'<?php  echo epic_get_current_unlock_achievements($_POST['dpa_code']);?>',
		showConfirmButton:false,
		html:true
		});
	}(jQuery))});	</script>
<?php
		}
	}
?>
<?php /*?><script type="text/html" id="tmpl-achievements-wrapper">
<ul aria-live="polite" id="dpa-toaster" role="status" style="display:none">
		<h1><?php _e('Achievement unlocked!','dpa'); ?></h1>
	</ul>
</script>

<script type="text/html" id="tmpl-achievements-item">
	<li class="dpa-toast" id="dpa-toast-id-{{ data.ID }}">
		<# if (data.image_url) { #>
			<a href="{{ data.permalink }}"><img class="attachment-medium dpa-toast-image" src="{{ data.image_url }}"  style="width: {{ data.image_width }}px" /></a>
		<# } #>

		<h2>{{ data.title }}</h2>
		<p>
			<?php
			// translators: "{{ data.title }}" will be replaced with the name of the achievement; leave this bit exactly as is.
			_e( "Hey, you&#8217;ve unlocked the &#8220;{{ data.title }}&#8221; achievement. Congratulations!", 'dpa' );
			?>
		</p>

		<p><a class="dpa-toast-cta" href="<?php echo esc_url( dpa_get_user_avatar_link( 'type=url&user_id=' . get_current_user_id() ) ); ?>"><?php _e( 'See your other achievements', 'dpa' ); ?></a></p>
	</li>
</script><?php */?>