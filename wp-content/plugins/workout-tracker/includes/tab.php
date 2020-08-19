<?php
if(!defined('ABSPATH')){
	exit;
}
class EWP_WT_Tab{
	public static function init(){
        $class = __CLASS__;
        new $class;
    }

	function __construct(){
		add_shortcode('Workout_tracker_tab',array($this,'tabs'));
		add_action('wp_ajax_wt_tab_get_user_data_ajax',array($this,'wt_tab_get_user_data_ajax'));
		add_action('wp_ajax_nopriv_wt_tab_get_user_data_ajax',array($this,'wt_tab_get_user_data_ajax'));
	}

	function wt_tab_get_user_data_ajax(){
		if($_REQUEST){
			$act	=	$_REQUEST['act'];
			$uid 	=	$_REQUEST['uid'];
			$pid 	=	$_REQUEST['pid'];
			if($act=='get_data'){
				$wt_is_data 	=	get_user_meta($uid,'wt_is_data',$uid);
				if($wt_is_data){	echo EWP_WT_Tracker_Data::table($uid,$pid);}
				else{echo EWP_WT_Tracker_Data::table($uid,$pid);}
			}
			if($act=='update_data'){
				$key 	=	$_REQUEST['key'];
				$val	=	$_REQUEST['val'];
				update_user_meta($uid,$key,$val);
			}
			if($act=='get_strength_data'){EWP_WT_Tracker_Data::get_strength_data(13,$uid,$pid);}
			if($act=='get_volume_data'){EWP_WT_Tracker_Data::get_volume_data(13,$uid,$pid);}
			if($act=='get_free_form_data'){EWP_WT_Tracker_Data::get_free_form_data($pid);}
			if($act=='get_calculator_data'){EWP_WT_Tracker_Data::get_calculator_data($pid);}
			if($act=='sidebar_menu'){
				EWP_WT_Tracker_Data::get_tracker_post($uid);
			}
		}
		die();
	}

	/* Workout Tracking Data Tabe*/
	function tabs(){
		ob_start(); ?>
		<div id="wottabs">
			<div class="wttlist"><?php
			if(is_user_logged_in()) {
				$user = new WP_User(get_current_user_id());
				if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
					foreach ( $user->roles as $role ){
						if($role=='administrator'){ ?>
							<li class="wttadd">
								<a href="javascript:void(0)" class="add-table">+ Add Title</a><input type="text" name="post_title" class="post_title" style="display:none">
								<a href="javascript:void(0)" class="add-title" style="display:none">ADD</a>
							</li><?php
						}
					}
				}
			} ?>
				<ul class="wt_ul">	</ul>
				<input class="wt-pid" value="" type="hidden">
			</div>
			<div class="wtttabs">
				<ul class="wttabul">
					<li class="wttabli wttabli1 wttabact" wttab="1">
						<a href="javascript:void(0)">Program Data</a>
					</li>
					<li class="wttabli wttabli4" wttab="4">
						<a href="javascript:void(0)">Freeform Data</a>
					</li>
					<li class="wttabli wttabli2" wttab="2">
						<a href="javascript:void(0)">Strength Data</a>
					</li>
					<li class="wttabli wttabli3" wttab="3">
						<a href="javascript:void(0)">Volume Data</a>
					</li>
					<li class="wttabli wttabli5" wttab="5">
						<a href="javascript:void(0)">Calculator</a>
					</li>
					<li>
						<a href="javascript:void(0)" class="submit_note">Save Data</a>
						</li>
					<li>
						<button class="initialism slide_open" >
							<img src="<?php echo  plugins_url('book.png', __FILE__ );?>">
						</button>
					</li>
				</ul>
                <div class="timer-tabs">
					<ul>
						<li>
							<button type="button" onclick="play_audio('#saudio1','#eaudio1','#timebtn1',30)" id="timebtn1"><i class="fa fa-sticky-note fa-6" aria-hidden="true"></i> 30 Sec Timer</button>
							<audio id="saudio1" preload="auto">
								<source src="https://www.dropbox.com/s/jqulsu95vanmkbc/30sec-start.ogg?dl=1" type="audio/ogg">
								<source src="https://www.dropbox.com/s/rdagedve9jzb4dh/30sec-start-32.mp3?dl=1" type="audio/mpeg">
							</audio>

							<audio id="eaudio1" preload="auto">
								<source src="https://www.dropbox.com/s/b2jivs4jq6wklvc/30sec-out.ogg?dl=1 " type="audio/ogg">
								<source src="https://www.dropbox.com/s/v6qblwwntmb9j2a/30sec-out.mp3?dl=1 " type="audio/mpeg">
							</audio>
						</li>
						<li>
							<button type="button" onclick="play_audio('#saudio2','#eaudio2','#timebtn2',60)" id="timebtn2"><i class="fa fa-sticky-note fa-6" aria-hidden="true"></i> 60 Sec Timer</button>
							<audio id="saudio2" preload="auto">
								<source src="https://www.dropbox.com/s/ruoogfznnufn0kf/60sec-start.ogg?dl=1"    type="audio/ogg">
								<source src="https://www.dropbox.com/s/mjrot2jc3knhdm3/60sec-start.mp3?dl=1"  type="audio/mpeg">
							</audio>

							<audio id="eaudio2" preload="auto">
								<source src="https://www.dropbox.com/s/3volj0zxzie427g/60sec-out.ogg?dl=1" type="audio/ogg">
								<source src="https://www.dropbox.com/s/c25e3uxox998qdp/60sec-out.mp3?dl=1" type="audio/mpeg">
							</audio>
						</li>

						<li>
							<button type="button" onclick="play_audio('#saudio3','#eaudio3','#timebtn3',120)" id="timebtn3"><i class="fa fa-sticky-note fa-6" aria-hidden="true"></i> 120 Sec Timer</button>
							<audio id="saudio3" preload="auto">
								<source src="https://www.dropbox.com/s/5hq8vus0h68m0jb/120sec-start.ogg?dl=1"type="audio/ogg">
								<source src="https://www.dropbox.com/s/iz9abei4j7wpwva/120sec-start.mp3?dl=1" type="audio/mpeg">
							</audio>

							<audio id="eaudio3" preload="auto">
								<source src="https://www.dropbox.com/s/kymz35tfkpvbuv1/120sec-out.ogg?dl=1"type="audio/ogg">
								<source src="https://www.dropbox.com/s/iz9abei4j7wpwva/120sec-start.mp3?dl=1"type="audio/mpeg">
							</audio>
						</li>

						<li>
							<button type="button" onclick="play_audio('#saudio4','#eaudio4','#timebtn4',180)" id="timebtn4"><i class="fa fa-sticky-note fa-6" aria-hidden="true"></i> 180 Sec Timer</button>
							<audio id="saudio4" preload="auto">
								<source src="https://www.dropbox.com/s/8rtdrnbgfig6vqa/180sec-start.ogg?dl=1"type="audio/ogg">
								<source src="https://www.dropbox.com/s/v1vyzdc4ryua2l7/180sec-start.mp3?dl=1"  type="audio/mpeg">
							</audio>

							<audio id="eaudio4" preload="auto">
								<source src="https://www.dropbox.com/s/u7q1gn027ojl1sn/180sec-out.ogg?dl=1"     type="audio/ogg">
								<source src="https://www.dropbox.com/s/sugbk80pxcy4nts/180sec-out.mp3?dl=1"  type="audio/mpeg">
							</audio>
					</li>
                  </ul>
                </div>
				<div class="wtttabcnt">
					<ul id="suggesstion-box"></ul>
					<div class="wtttab wtttab1"></div><div class="wtttab wtttab2"></div>
					<div class="wtttab wtttab3"></div><div class="wtttab wtttab4"></div>
					<div class="wtttab wtttab5 wtab-rp"></div>
				</div>
			</div>
		</div><?php
		$o=ob_get_contents();
		ob_end_clean();
		return $o;
	}
}
add_action('plugins_loaded',array('EWP_WT_Tab','init'));