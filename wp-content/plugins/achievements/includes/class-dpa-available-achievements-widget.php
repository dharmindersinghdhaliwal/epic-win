<?php
/**
 * Achievements widgets
 *
 * @package Achievements
 * @subpackage CommonWidgets
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Available Achievements widget; displays a grid of pictures of available Achievements.
 *
 * @since Achievements (2.0)
 */
class DPA_Available_Achievements_Widget extends WP_Widget {
	/**
	 * Featured Achievement widget
	 *
	 * @since Achievements (3.3)
	 */
	public function __construct() {
		$widget_ops = apply_filters( 'dpa_available_achievements_widget_options', array(
			'classname'   => 'widget_dpa_available_achievements',
			'description' => __( 'Displays a photo grid of the achievements.', 'dpa' ),
		) );
		parent::__construct( false, __( '(Achievements) Photo Grid', 'dpa' ), $widget_ops );
	}
	/**
	 * Register the widget
	 *
	 * @since Achievements (3.3)
	 */
	static public function register_widget() {
		register_widget( 'DPA_Available_Achievements_Widget' );
	}

	/**
	 * Displays the output
	 *
	 * @param array $args
	 * @param array $instance
	 * @since Achievements (2.0)
	 */
	 
	public function widget( $args, $instance ){			
		$settings	=	$this->parse_settings( $instance );		
		// Use these filters
		$settings['limit'] = absint( apply_filters( 'dpa_available_achievements_limit', $settings['limit'], $instance, $this->id_base ) );
		$settings['title'] = apply_filters( 'dpa_available_achievements_title', $settings['title'], $instance, $this->id_base);		
		$settings['title'] = apply_filters( 'widget_title', $settings['title'], $instance, $this->id_base );		
		echo $args['before_widget'];
		if(!empty( $settings['title']))
			echo $args['before_title'].$settings['title'].$args['after_title'];
		//	Get the posts								
		$achievements = get_posts( array(
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'numberposts'         => $settings['limit'],
			'post_status'         => 'publish',
			'post_type'           => dpa_get_achievement_post_type(),
			'suppress_filters'    => false,
		));		
		//	Bail if no posts
		if ( empty( $achievements ) )
			return;		
		echo '<ul>';		
		foreach ( $achievements as $post ){
				if ( has_post_thumbnail( $post->ID ) ) :
			/*-------------------------------------------------*/
			if(dpa_has_user_unlocked_achievement(get_current_user_id(), $post->ID )){ ?>
            <li>
            <?php //dpa_achievement_permalink($post->ID); ?>
					<a class="aidclick" href="javascript:void(0)" achiun="1" aid="<?php echo $post->ID; ?>"><?php echo get_the_post_thumbnail( $post->ID, 'dpa-thumb', array( 'alt' => dpa_get_achievement_title( $post->ID ) ) ); ?></a>
				</li>
			<?php }
			else{ ?>	
				<li>
                <a class="aidclick"  href="javascript:void(0)" achiun="0"  aid="<?php echo $post->ID; ?>"><img src="<?php echo plugins_url('red.png',__FILE__); ?>" alt="<?php echo  dpa_get_achievement_title( $post->ID ); ?>"/></a>
                </li><?php
			}
			/*-------------------------------------------------------------*/
			endif;
		}
		echo '</ul>';
		echo $args['after_widget'];
	}	
	/**
	 * Deals with the settings when they are saved by the admin.
	 *
	 * @param array $new_instance New settings
	 * @param array $old_instance Old settings
	 * @return array The validated and (if necessary) amended settings
	 * @since Achievements (2.0)
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['limit'] = absint( $new_instance['limit'] );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}
	/**
	 * Output the widget options form
	 *
	 * @param array $instance The instance of the widget.
	 * @since Achievements (2.0)
	 */
	public function form( $instance ) {
		$settings = $this->parse_settings( $instance );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title'); ?>"><?php _e( 'Title:', 'dpa' ); ?></label><br />
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Show up to this many items:', 'dpa' ); ?></label><br />
			<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" min="1" value="<?php echo esc_attr( $settings['limit'] ); ?>" />
		</p>
		<?php
	}
	/**
	 * Merge the widget settings into defaults array.
	 *
	 * @param array $instance Optional; the instance of the widget.
	 * @since Achievements (3.3)
	 */
	public function parse_settings( array $instance = array() ) {
		return dpa_parse_args( $instance, array(
			'limit' => 40,
			'title' => __( 'Available achievements', 'dpa' ),
		), 'available_achievements_widget_settings' );
	}
}
add_action('wp_head','do_ajax_for_achievement_popup');
function do_ajax_for_achievement_popup(){?>
<script type="text/javascript">jQuery(document).ready(function(e){(function($){
	var ajaxurl='<?php echo admin_url('admin-ajax.php'); ?>';
	$( document ).on('click','.aidclick',function(){	
		var achievementid=$(this).attr('aid');
		var achiun=$(this).attr('achiun');
		var term=$(this).attr('term');
		jQuery.ajax({
			type:"POST",url:ajaxurl,
			data:{'action':'show_achievement_popup','achievementid':achievementid,'achiun':achiun,'term':term},
			beforeSend:function(){
				swal({
				title:"",
				text: "<?php echo __("wait..",'epic'); ?>",
				imageUrl: "http://rohitwebguru.com/epicwinptmembers/wp-content/uploads/2015/09/NYAN1.gif",showConfirmButton:false
				})},
			success:function(data){
				if(data){swal({title:"",text:data,showConfirmButton:false,html:true})}
			}
		});
	return false;
	});
	$( document ).on('click','.closepop',function(){		
		var term=$(this).attr('term');
		var uid =$(this).attr('uid');
		<?php
		$pid=get_the_ID();
		if($pid==341){
		}
		else{ ?>
		jQuery.ajax({
			type:"POST",url:ajaxurl,data:{'action':'ms_get_current_user_achivment_from_click_cat','term':term,'uid':<?php echo get_current_user_id(); ?>},beforeSend:function(){
				swal({
					title:"",text: "<?php echo __("wait..",'epic'); ?>",imageUrl: "http://rohitwebguru.com/epicwinptmembers/wp-content/uploads/2015/09/NYAN1.gif",showConfirmButton:false
					})
					;},
		success:function(data){
			swal({title:null,text:data,html:true,confirmButtonColor:'#c94136'});
		}
		});	
		<?php }	?>
	});
}(jQuery))});</script>

<style>
.sweet-alert h2{display:none !important}
.sweet-alert{padding:15px !important}
#achlbox{width:100%;position:relative;display:block;float:left}
.closesa { background: #00688c none repeat scroll 0 0; border-radius: 4px; color: #fff; font-size: 18px;		  height: 30px;padding: 4px; position: absolute; text-align: center; text-transform: lowercase; width: 100px;}
#acleft{float:left;max-width:260px; width:40%; position:relative}
.achunl{background:#fff;font-size:18px;font-weight:bold;opacity:0.83;padding:5px;position:absolute;top:-6px;width:100%;text-align:center}
#acleft img{max-height:250px}
#acright{float:right; width:58%}
#acright h3{font-size:18px;font-size:15px}
#acright h4{font-size:20px;font-size:16px}
#acright h3,#acright h4{font-family:Montserrat;text-transform:uppercase;margin-bottom:0}
.acincont{padding:10px;height:168px;overflow:auto;display:block}
.acoutcont{background:#f1f1f1;height:204px;display:block}
</style>
<?php
}
add_action('wp_ajax_show_achievement_popup','show_achievement_popup');
add_action('wp_ajax_nopriv_show_achievement_popup','show_achievement_popup');
function show_achievement_popup(){
	if(isset($_REQUEST)){
		$achievementid=$_REQUEST['achievementid'];
		$achiun=$_REQUEST['achiun'];
		$term=$_REQUEST['term'];
		$post=get_post($achievementid);
		$dpa_points=get_post_meta($post->ID,'_dpa_points',true);
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
		$cats=get_the_category($post->ID);
		$o='';
		
		$o.='<div id="achlbox">';
			$o.='<a href="javascript:void(0)" onClick="swal.close()" term="'.$term.'" class="closesa closepop">x</a>';
			$o.='<div id="acleft">';
			if($achiun==1){
				$o.='<span class="achunl dummy">Achievement unlocked!</span>';
			}
			else{
			}
			$o.='<img src="'.$feat_image.'" width="230" height="230">';
			$o.='</div>';
			
			$o.='<div id="acright">';
				$o.='<h3>'; foreach($cats as $cat){ $o.=$cat->name; } $o.=' &nbsp; &nbsp; '.$dpa_points.'pts'; $o.='</h3>';
				$o.='<span class="acoutcont">';
				$o.='<h4>'.$post->post_title.'</h4>';
					$o.='<span class="acincont">';
					$o.='<i>'.$post->post_content.'</i><br>';
					$o.='</span>';
				$o.='</span>';
			$o.='</div>';
		$o.='</div>';		
		echo $o;
	}
	die();
}