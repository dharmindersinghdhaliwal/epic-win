<?php
class Epic_Manage_Tracking{
	public static function init(){
        $class = __CLASS__;
        new $class;
    }

	public function __construct(){
		add_action( 'init', array($this,'manage_tracking'), 0 );
		add_action( 'init', array($this,'epctrack_levels'), 0 );
		add_shortcode('epic_tracking',array($this,'show_tracking_level'));
		add_shortcode('epic_tracking_user_list',array($this,'drop_down'));		
		add_action( 'wp_ajax_epic_insert_update_tracking_ajax',array($this,'epic_insert_update_tracking_ajax'));
  		add_action('wp_ajax_nopriv_epic_insert_update_tracking_ajax',array($this,'epic_insert_update_tracking_ajax'));
		add_action( 'wp_ajax_client_tracking_search_box', array( $this, 'client_tracking_search_box' ) );
		add_action('wp_ajax_nopriv_client_tracking_search_box',array($this,'client_tracking_search_box'));
		add_action( 'wp_ajax_epic_get_user_img_ajax', array( $this, 'epic_get_user_img_ajax' ) );
		add_action('wp_ajax_nopriv_epic_get_user_img_ajax',array($this,'epic_get_user_img_ajax'));
		add_shortcode( 'client_tracking_search_box', array( 'WPUEP_User_Menus', 'client_tracking_search_box' ) );
		add_action( 'wp_ajax_epic_do_calculate_ajax', array( $this, 'do_calculate_ajax' ) );
		add_action('wp_ajax_nopriv_do_calculate_ajax',array($this,'do_calculate_ajax'));
		add_action( 'wp_ajax_epic_epic_user_info_ajax', array( $this, 'epic_user_info_ajax' ) );
		add_action('wp_ajax_nopriv_epic_user_info_ajax',array($this,'epic_user_info_ajax'));
		add_action( 'wp_enqueue_scripts',array($this,'add_css'));
		add_action( 'wp_enqueue_scripts',array($this,'add_js'));
		add_shortcode('login_redriect',array($this,'login_redriect'));
	}

	public function login_redriect(){
		if(!is_user_logged_in()){
			wp_redirect('"'.get_site_url().'"/login');
		}
	}

	function add_css(){
		 wp_enqueue_style('mCustomScrollbar','/wp-content/plugins/epic-win/CustomScrollbar.css');
		 wp_enqueue_style('mCustomScrollbar');
	}

	function add_js(){
		 wp_enqueue_script('CustomScrollbar-min','/wp-content/plugins/epic-win/CustomScrollbar-min.js');
		 wp_enqueue_script('CustomScrollbar-min');
	}

	# Register Custom Taxonomy For Tracking Level
	function manage_tracking(){
		$labels = array(
			'name'                  => _x( 'Epic Trackings', 'Post Type General Name', 'epic' ),
			'singular_name'         => _x( 'EpicTracking', 'Post Type Singular Name', 'epic' ),
			'menu_name'             => __( 'Epic Tracking', 'epic' ),
			'name_admin_bar'        => __( 'Epic Tracking', 'epic' ),
			'archives'              => __( 'Item Archives', 'epic' ),
			'parent_item_colon'     => __( 'Parent Item:', 'epic' ),
			'all_items'             => __( 'All Items', 'epic' ),
			'add_new_item'          => __( 'Add New Item', 'epic' ),
			'add_new'               => __( 'Add New', 'epic' ),
			'new_item'              => __( 'New Item', 'epic' ),
			'edit_item'             => __( 'Edit Item', 'epic' ),
			'update_item'           => __( 'Update Item', 'epic' ),
			'view_item'             => __( 'View Item', 'epic' ),
			'search_items'          => __( 'Search Item', 'epic' ),
			'not_found'             => __( 'Not found', 'epic' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'epic' ),
			'featured_image'        => __( 'Featured Image', 'epic' ),
			'set_featured_image'    => __( 'Set featured image', 'epic' ),
			'remove_featured_image' => __( 'Remove featured image', 'epic' ),
			'use_featured_image'    => __( 'Use as featured image', 'epic' ),
			'insert_into_item'      => __( 'Insert into item', 'epic' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'epic' ),
			'items_list'            => __( 'Items list', 'epic' ),
			'items_list_navigation' => __( 'Items list navigation', 'epic' ),
			'filter_items_list'     => __( 'Filter items list', 'epic' ),
		);
		$args = array(
			'label'                 => __( 'EpicTracking', 'epic' ),
			'description'           => __( 'Epic Tracking', 'epic' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'custom-fields', ),
			'taxonomies'            => array( 'epctrack' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => plugins_url('/img/i.png',__FILE__),
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
		);
		register_post_type('epic_tracking',$args );
	}
	
	function epctrack_levels(){
		$labels = array(
			'name'                       => _x( 'Tracking Levels', 'Taxonomy General Name', 'epic' ),
			'singular_name'              => _x( 'Tracking Level', 'Taxonomy Singular Name', 'epic' ),
			'menu_name'                  => __( 'Tracking Level', 'epic' ),
			'all_items'                  => __( 'All Items', 'epic' ),
			'parent_item'                => __( 'Parent Item', 'epic' ),
			'parent_item_colon'          => __( 'Parent Item:', 'epic' ),
			'new_item_name'              => __( 'New Item Name', 'epic' ),
			'add_new_item'               => __( 'Add New Item', 'epic' ),
			'edit_item'                  => __( 'Edit Item', 'epic' ),
			'update_item'                => __( 'Update Item', 'epic' ),
			'view_item'                  => __( 'View Item', 'epic' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'epic' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'epic' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'epic' ),
			'popular_items'              => __( 'Popular Items', 'epic' ),
			'search_items'               => __( 'Search Items', 'epic' ),
			'not_found'                  => __( 'Not Found', 'epic' ),
			'no_terms'                   => __( 'No items', 'epic' ),
			'items_list'                 => __( 'Items list', 'epic' ),
			'items_list_navigation'      => __( 'Items list navigation', 'epic' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => true,
		);
		register_taxonomy('epctrack', array('epic_tracking'),$args);
	}

	public static function show_tracking_level(){
		ob_start();
		epic_manage_tracking::insert_user_detail();
		$epctrack		=	epic_manage_tracking::get_terms_id_title_ARR('epctrack');
		$taxonomyName	=	'epctrack';
		$parent_terms	=	get_terms($taxonomyName, array('parent'=>0,'orderby'=>'slug','hide_empty'=>false)); ?>
		<script src="<?php echo plugins_url();?>/epic-win/epic-win.js"></script>
        <div id="loadingm" class="modal" style="display: none">
			<div class="modal-content">
				<div class="modal-header"><h2>Loading...</h2></div>
					<div class="modal-body">
						<img src='http://members.epicwinpt.com.au/wp-content/themes/maxx-fitness-child-theme/x2_writing.gif' width="150" height="150"/>
					</div>
			</div>
		</div>
        <?php
		echo '<div class="pro_img" style="display:none"></div>';
		echo'<div class="ettouter">';
		echo do_shortcode('[epic_tracking_user_list]');
		echo '<a href="javascript:void(0)" class="eptrkbtn add_col"> Add New </a>';
		echo '<a href="javascript:void(0)" class="eptrkbtn undo" style="display:none">Delete</a>';
		echo'<div class="note"><span>&nbsp;Notes</span></div>';
			echo'<div class="ettcont demo-x" id="xscroll">';
			echo'<ul class="ettvaluse">';
			$j=0;
			foreach ($parent_terms as $pterm){
				$i=0;
				$ptid=$pterm->term_id;
				$terms = get_terms($taxonomyName,array('parent'=>$ptid,'orderby'=>'slug','hide_empty'=>false));
				if($i==0||$i==1){
					echo'<li lvlid="'.$pterm->term_id.'" class="mtrack prntli" style="border-bottom:1px solid #ddd !important; list-style:none">';
						echo '<label style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px" class="parnt">'.$pterm->name.'</label>';
					echo'</li>';
				}
				else{
					echo'<li><label><b>'.$pterm->name.'</b></label></li>';
				}
				foreach ($terms as $term) {
					echo'<li lvlid="'.$term->term_id.'" class="mtrack mtprt'.$ptid.' mctrack'.$term->term_id.' ';
					if($j==0){echo 'firstli';	}
					echo '" style="border-bottom:1px solid #ddd !important; list-style:none">';
					echo'<label style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">'.$term->name.'</label>';
					echo'</li>';
					$i++;
					if(count($terms)==$i){
						echo '<li class="mttotal'.$ptid.' "><label class="total"><b><i>Total:</i></b></label></li>';
					}
					$j++;
				}
			}
			
			echo '<li class="body-fat" style="border-bottom:1px solid #ddd !important;border-top:1px solid #ddd !important; list-style:none"><label class="total"><b><i>Body Fat %:</i></b></label></li>
		<li class="body-mass" style="border-bottom:1px solid #ddd !important; list-style:none"><label class="total"><b><i>Lean body mass:</i></b></label></li>';
		echo'</ul>';
		echo '</div>';
		echo '<ul class="demo">
		<li style="border-bottom:1px solid #ddd !important; list-style:none">
		<label  class="fstlbl" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px">Date</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="fstlbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px">Date Significance</label></li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="fstlbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px">Weight</label></li>
		<li  style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="fstlbl1"  style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px">Girth Measurements</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Shoulders</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Chest</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Waist</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Umbilical</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Hip</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Left thigh 1</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Left thigh 2</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Right thigh 1</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Right thigh 2</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Left calf</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Right calf</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Left bicep</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Right bicep</label>
		</li>
		<li><label class="demototal"><b><i>Total:</i></b></label></li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl8" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px">Skin Folds</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none">
		<label  class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Tricep</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Sub scap</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label  class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Pec</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label  class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Axila</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Supra Il</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Umbilical</label>
		</li>
		<li style="border-bottom:1px solid #ddd !important; list-style:none" >
		<label class="demolbl1" style="border-right:1px solid #ddd;display:inline-block;margin-right:0px;width:200px;">Thigh</label>
		</li>
		<li style="background-color:#fff;"><label class="demototal"><b><i> Total:</i></b></label></li>
		<li style="background-color:#fff;border-top:1px solid #ddd !important;"><label class="demototal"><b><i>Body Fat %:</i></b></label></li>
		<li style="background-color:#fff;border-top:1px solid #ddd !important;"><label class="demototal"><b><i>Lean body mass:</i></b></label></li>
		</ul>';
		echo '<br><a href="javascript:void(0)" class="eptrkbtn epusr_track_update">Update</a>';
		echo '<a href="javascript:void(0)" class="eptrkbtn epusr_track_edit">Edit</a>';
		echo'</div>';
		$o	=	ob_get_contents();
		ob_clean();
		return $o;
	}
	
	public function insert_user_detail(){
		$parentt=get_terms('epctrack',array('parent'=>0,'orderby'=>'slug','hide_empty'=>false));
		$p21=array();
		$i=0;
		foreach ($parentt as $pterm){
			$ptid	=	$pterm->term_id;
			$terms	= get_terms('epctrack',array('parent'=>$ptid,'orderby'=>'slug','hide_empty'=>false));
			if($i==0||$i==1){}
			else{	array_push($p21,$ptid);}
			foreach ($terms as $term){
				if($j==0){}
				if(count($terms)==$i){}
				$j++;
			}
			$i++;
		}
	?>
	<input  type="hidden" class="tprt" value="<?php echo count($parentt)-2; ?>">
	<input  type="hidden" class="pJSON" value="<?php echo json_encode($p21);?>">
	<?php
	}

	public static function do_calculate_ajax(){
		if($_REQUEST){
			$uid		=	$_REQUEST['userid'];
			$prtArr		=	array(21,29);
			$eputrk		=	get_user_meta($uid,'eputrk',true);
			$eArr		=	json_decode($eputrk,true);
			$stp1		=	array();
			$stp2		=	array();
			$stp3		=	array();
			for($i=0;$i<count($eArr);$i++){
				if($i>=2){
					$tterms		=	$eArr[$i][0];
					$tvaluse	=	$eArr[$i][1];
					$cterm		=	array($tterms,$tvaluse);
					array_push($stp1,$cterm);
				}
			}
			$sumArr	=	array();
			for($j=0;$j<sizeof($stp1);$j++){
				$ttid	=	$stp1[$j][0];
				$ttArr	=	$stp1[$j][1];
				$ptid 	=	Epic_Manage_Tracking::get_parent_id($ttid);
				if($ptid!=0){
					$stp2[$j][$ptid]=$ttArr;
				}
			}
			$stp2	=	array_values($stp2);
			for($k=0;$k<count($prtArr);$k++){
				 $ttid1		=	$prtArr[$k];
				for($l=0;$l<count($stp2);$l++){
				 	$pindex=$stp2[$l];
					 if($prtArr[$k]!=$pindex){
						 echo $prtArr[$k];
					}
				}
			}
		}
		die();
	}
	
	public function get_parent_id($id){
		$term = get_term($id,'epctrack');
		$termParent = $term ? $term->parent : false;
		return $termParent;
	}
	
	public static function get_terms_id_title_ARR($taxName){
		$taxonomy	=	get_terms(array('taxonomy'=>$taxName,'hide_empty'=>false));
		$newARR		=	array();
		foreach($taxonomy as $term){
			$tid	=	$term->term_id;
			$name	=	$term->name;
			$newARR[$tid]=$name;
		}
		if($newARR){return $newARR; }
	}
	
	function drop_down(){
		ob_start();
		$users=get_users();
		$o	=	ob_get_contents();
		ob_clean();
		return $o;
	}
	
	public static function get_user_name($uid=''){
		if($uid){ $cid=$uid; }else{ $cid=get_current_user_id();}
		if($cid){
			$userdata		=	get_userdata($cid);
			@$dname 		=	$userdata->display_name;
			@$first_name	=	get_user_meta($cid,'first_name',true);
			@$last_name	=	get_user_meta($cid,'last_name',true);
			if($first_name){ $name=$first_name.' '.$last_name; }
			else{ $name=$dname; }
			return $name;
		}
	}
	
	public function epic_insert_update_tracking_ajax(){
		if($_REQUEST){
			$act	=	$_REQUEST['act'];
			$uid	=	$_REQUEST['uid'];
			if($act==1){
				$tarr	=	$_REQUEST['tarr'];
				$json	=	json_encode($tarr);
				 update_user_meta($uid,'eputrk',$json);
				echo 1;
			}
			if($act==2){
				$eputrk=get_user_meta($uid,'eputrk',true);
				echo $eputrk;
			}
		}
		die();
	}
	
	public function epic_get_user_img_ajax(){
		 if($_REQUEST){
			 $act = $_REQUEST['act'];
			 $uid = $_REQUEST['uid'];
			 if($act==3){
				global $epic;
				echo  $epic->pic($uid);
			}
		}
		die();
	}
}
add_action('plugins_loaded',array('Epic_Manage_Tracking','init'));