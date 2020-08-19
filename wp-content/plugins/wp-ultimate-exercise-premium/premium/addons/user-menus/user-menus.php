<?php
class WPUEP_User_Menus extends WPUEP_Premium_Addon {
    public function __construct( $name = 'user-menus' ) {
        parent::__construct( $name );
        // Actions
        add_action( 'init', array( $this, 'eassets' ) );
        add_action( 'init', array( $this, 'menus_init' ));
        add_action( 'admin_init', array( $this, 'user_menus_admin_init' ));
        add_action( 'admin_menu', array( $this, 'ingredient_groups_menu' ), 5 );
        // Ajax
        add_action( 'wp_ajax_user_menus_groupby', array( $this, 'ajax_user_menus_groupby' ) );
        add_action( 'wp_ajax_nopriv_user_menus_groupby', array( $this, 'ajax_user_menus_groupby' ) );
        add_action( 'wp_ajax_user_menus_get_ingredients', array( $this, 'ajax_user_menus_get_ingredients' ) );
        add_action( 'wp_ajax_nopriv_user_menus_get_ingredients', array( $this, 'ajax_user_menus_get_ingredients' ) );
        add_action( 'wp_ajax_user_menus_delete', array( $this, 'ajax_user_menus_delete' ) );
        add_action( 'wp_ajax_nopriv_user_menus_delete', array( $this, 'ajax_user_menus_delete' ) );
        add_action( 'wp_ajax_user_menus_save', array( $this, 'ajax_user_menus_save' ) );
        add_action( 'wp_ajax_nopriv_user_menus_save', array( $this, 'ajax_user_menus_save' ) );
        add_action( 'wp_ajax_ingredient_groups_save', array( $this, 'ajax_ingredient_groups_save' ) );				
        add_action( 'wp_ajax_nopriv_ingredient_groups_save', array( $this, 'ajax_ingredient_groups_save' ) );
        add_action( 'wp_ajax_add_to_shopping_list', array( $this, 'ajax_add_to_shopping_list' ) );
        add_action( 'wp_ajax_nopriv_add_to_shopping_list', array( $this, 'ajax_add_to_shopping_list' ) );
        add_action( 'wp_ajax_update_shopping_list', array( $this, 'ajax_update_shopping_list' ) );
        add_action( 'wp_ajax_nopriv_update_shopping_list', array( $this, 'ajax_update_shopping_list' ) );
		add_action( 'wp_ajax_email_program', array( $this, 'ajax_email_program' ) );
        add_action( 'wp_ajax_nopriv_email_program', array( $this, 'ajax_email_program' ) );
		add_action( 'wp_ajax_program_detail', array( $this, 'ajax_program_detail' ) );
        add_action( 'wp_ajax_nopriv_program_detail', array( $this, 'ajax_program_detail' ) );
		add_action( 'wp_ajax_client_program_search', array( $this, 'ajax_client_program_search' ) );
        add_action( 'wp_ajax_nopriv_client_program_search', array( $this, 'ajax_client_program_search' ) );
		add_action( 'wp_ajax_client_email_program', array( $this, 'client_email_program' ) );
        add_action( 'wp_ajax_nopriv_client_email_program', array( $this, 'client_email_program' ) );		
		add_action( 'wp_ajax_exercise_email_program', array( $this, 'ajax_exercise_email_program' ) );
        add_action( 'wp_ajax_nopriv_exercise_email_program', array( $this, 'ajax_exercise_email_program' ) );
        add_filter( 'the_content', array( $this, 'user_menus_content' ), 10 );
        add_shortcode( 'wpuep_user_menus', array( $this, 'user_menus_shortcode' ) );
        add_shortcode( 'ultimate-exercise-user-menus', array( $this, 'user_menus_shortcode' ) );
        add_shortcode( 'ultimate-exercise-menu', array( $this, 'display_menu_shortcode' ) );
        add_shortcode( 'ultimate-exercise-user-menus-by', array( $this, 'display_user_menus_by_shortcode' ) );			
		add_shortcode( 'user_specific_programs', array( $this, 'list_user_programs' ) );						
		add_shortcode( 'user_program_content', array( $this, 'list_user_program_content' ) );								
		add_shortcode( 'client_program_search', array( $this, 'client_program_search_block' ) );	
		add_action('wp_head',array( $this, 'add_j_script') );									
    }	
	public function client_tracking_search_box(){
		add_thickbox();
	?>
		<div style="margin:0 auto;padding-left:32%">
			<input type="hidden" name="ajax_loader" id="ajax_loader" value="<?php echo get_template_directory_uri(); ?>/images/ajax.gif"/>
			<input type="hidden" id="client_email" name="client_email" />
			<input type="hidden" id="client_program_id" name="client_program_id"/>	
             <input type="hidden" id="client_gender" name="client_gender" />
		<input type="hidden" id="client_age" name="client_age"/>							
			<span style="padding-left:10%">
				<input placeholder="Search Client" class="user-menus-title" type="text" name="search_user" id="search_user" autocomplete="off" />
			</span>
			<span id="search_client_program" style="padding-left:3%"><button  class="client_search"style="background-color:#7BE558 !important;border-color:#7BE558 !important">Search Client</button></span>
		</div>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.css">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.min.js"></script>
		<style type="text/css">
			.exercise_program{color:#FFF;font-size:14px;}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function(e){(function($){
				var availableTags = <?php echo epic_get_list_of_all_user(); ?>;
				jQuery('#search_user').autocomplete({
					source:availableTags,select:function(event,ui){
						event.preventDefault();
						console.log(ui.item);
						$('#client_email').val(ui.item.value);
						$('#client_gender').val(ui.item.gender);
						$('#client_age').val(ui.item.age)
						var lbl=$(this).val(ui.item.label);
					},
				});
				jQuery('#search_user').keyup(function(){
					var user_name =	jQuery.trim( jQuery( this ).val() );
					if( user_name == ''){
						jQuery( '#email_client_program' ).hide();
						jQuery( '#print_client_program' ).hide();
						jQuery( '#selected_client_programs' ).empty();
						jQuery( '#selected_client_programs_content' ).empty();
					}
				});
			}(jQuery))});
		</script>
<?php }
public function add_j_script(){
		?>
       <script type="text/javascript">
	   jQuery(document).ready(function(e){(function($){
		   var li = $('body').on('click','.user_pu li', function() {
			   $(this).parent('ul').children('li').removeClass('selected');
			   $(this).addClass('selected');
			  });
		}(jQuery))});
        </script>
		<?php
}
	public function client_program_search_block(){
		add_thickbox();
	?><div style="margin:0 auto;padding-left:32%;max-width:100% !important;">
    	<input type="hidden" name="ajax_loader" id="ajax_loader" value="<?php echo get_template_directory_uri(); ?>/images/ajax.gif"/>
        <input type="hidden" id="client_email" name="client_email" />
		<input type="hidden" id="client_program_id" name="client_program_id"/>        
        <input type="hidden" id="client_gender" name="client_gender" />
		<input type="hidden" id="client_age" name="client_age"/>
        <span style="padding-left:10%">
        <input placeholder="Search User" class="user-menus-title" type="text" name="search_user" id="search_user" autocomplete="off" />
        </span>
        <span id="search_client_program" style="padding-left:3%"><button onclick="javascript:ExerciseUserMenus.search_client_program();" style="background-color:#7BE558 !important;border-color:#7BE558 !important">Search Program</button></span>
        <span id="email_client_program" style="padding-left:2%;display:none"><button onclick="javascript:ExerciseUserMenus.clientEmailProgram();">Email Program</button></span>
        <span id="print_client_program" style="padding-left:2%;display:none"><button onclick="javascript:ExerciseUserMenus.print_client_program();">Print Program</button></span>			
    </div>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="<?php echo WP_PLUGIN_URL; ?>/wp-ultimate-exercise-premium/core/dist/sweetalert.min.js"></script>
    <style type="text/css">
		.exercise_program{color:#FFF !important;font-size:14px;}
			.clint_pro{ display:block; clear:both; width:100%; overflow:hidden; margin-top:15px}
			#selected_client_programs{ width:22%;float:left;padding:0}
			.user_p{background-color:#1c1c1c;}
			#exercises_div{	background:#f1f1f1;	width:72%; padding:2%; float:right}
			.user_p > ul{ padding:0;margin:0;}
			.user_p li{ list-style:none; padding:10px;text-align:center;border-bottom:1px solid}
			.selected{background-color:#ff0000 !important;};
		</style>
	<script type="text/javascript">jQuery(document).ready(function(e){(function($){
		var availableTags = <?php echo epic_get_list_of_all_user(); ?>;
		jQuery('#search_user').autocomplete({
			source:availableTags,select:function(event,ui){
				event.preventDefault();
				$('#client_email').val(ui.item.email);
				$('#client_gender').val(ui.item.gender);
				$('#client_age').val(ui.item.age);
				$(this).val(ui.item.label);
			},
		});
		jQuery('#search_user').keyup(function(){
			var user_name =	jQuery.trim( jQuery( this ).val() );
			if( user_name == ''){
				jQuery( '#email_client_program' ).hide();
				jQuery( '#print_client_program' ).hide();
				jQuery( '#selected_client_programs' ).empty();
				jQuery( '#selected_client_programs_content' ).empty();
			}
		});
	}(jQuery))});</script>
<?php
	}
	public function ajax_client_program_search(){
		global $wpdb;
		$client_email		=	$_POST[ 'selected_client_email' ];
		$user_programs	 	=	$this->get_user_programs( $client_email );
		$user_programs_list =	'';
		$user_programs_content_template		=	'';
		$latest_program		=	'';
		if( !empty( $user_programs )){
			$latest_program		=	$user_programs[0];
			$programs_ids	=	implode( ',',$user_programs );
			$querystr 		=	"SELECT * FROM `".$wpdb->prefix."posts` WHERE ID IN(".$programs_ids.") ORDER BY ID DESC";
			$user_programs	=	$wpdb->get_results($querystr, OBJECT);	
			$user_programs_list .=	'
			<div class="user_p">
			<div class="yp" style="font-size:25px;font-family:Michroma;padding:15px;text-transform:none;color:#FFF;text-align:center; ">Your Programs</div>';
			$i = 0;
			$user_programs_list .='<ul class="user_pu">';
			foreach( $user_programs as $program ){
				if( $i == 0 ){
					$user_programs_list .=	'<li style="border-top:1px solid" class="selected"><a  class="exercise_program" href="javascript:void(0);" id="'.$program->ID.'">'.$program->post_name.'</a></li>';					
				}
				else{
					$user_programs_list .=	'<li><a class="exercise_program" href="javascript:void(0);" id="'.$program->ID.'">'.$program->post_name.'</a></li>';
				}
				$i++;
			}	
			$user_programs_list .=	'</ul>';
		}
		else{
			$user_programs_list .=	'<div style="font-size: 25px;font-family: Michroma;padding:15pxtext-transform: none;color: #000;">No Program Found</div>';
		}
		if( empty( $latest_program ) ){
			$user_programs_content_template .= 	'<div style="font-size: 25px;font-family: Michroma;margin-bottom: 10px;text-transform: none;font-weight: bold;color: #000;" id="program_name">No Program Found</div>';	
		}
		else{
			$program_detail 	=	get_post( $latest_program );
			$exercises			=	get_post_meta( $latest_program, 'user-menus-exercises', TRUE );
			$order				=	get_post_meta( $latest_program, 'user-menus-order', TRUE );
			$nbrExercies		=	get_post_meta( $latest_program, 'user-menus-nbrExercies', TRUE );
			$unitSystem			=   get_post_meta( $latest_program, 'user-menus-unitSystem', TRUE );			
			$user_programs_content_template .= 	'<div id="exercises_div">';
			$user_programs_content_template .= 	'<div style="font-size: 25px;font-family: Michroma;margin-bottom: 10px;text-transform: none;font-weight: bold;color: #000;" id="program_name">'.ucfirst( $program_detail->post_title).'</div>';
			foreach( $exercises as $exercise ) {
				$user_programs_content_template .=	$this->get_exercise_template( $exercise );	
			}
			$user_programs_content_template .= 	'</div></div>';
		}
		$response 	=	array(
							'response' => 1,
							'user_programs' => $user_programs_list,
							'user_programs_content'	=>	$user_programs_content_template,
							'latest_program' =>$latest_program
						);
		echo json_encode( $response );
		exit;
	}
	public function list_user_programs(){?>
		<style type="text/css">
			.exercise_program{color:#FFF !important;font-size:14px;	}
			#exsdiv{ display:block; clear:both; width:100%; overflow:hidden}
			.user_p{ width:22%;float:left; background-color:#1c1c1c;}
			#exercises_div{	background:#f1f1f1;width:72%; padding:2%; float:right}
			.user_p > ul{ padding:0; margin:0;}
			.user_p li{ list-style:none; padding:10px; text-align: center;border-bottom:1px solid}
			.selected{background-color:#ff0000 !important;};
		</style>
<?php
		global $wpdb,$current_user;
		$user_email			=	$current_user->data->user_email;
		$user_programs 		=	$this->get_user_programs( $user_email );
		if( !empty( $user_programs )){
			$programs_ids	=	implode( ',',$user_programs );
			$querystr 		=	"SELECT * FROM `".$wpdb->prefix."posts` WHERE ID IN(".$programs_ids.") ORDER BY ID DESC";
			$user_programs	=	$wpdb->get_results($querystr, OBJECT);
			echo '<div id="exsdiv">';
			echo '<div class="user_p">';
			echo '<div class="yp" style="font-size: 25px;padding:15px;text-transform: nonefont-family: Michroma;;color:#FFF !important;text-align:center">Your Programs</div>';
			$i	=	0;
			echo '<ul class="user_pu">';
			foreach( $user_programs as $program ){
				if( $i == 0){
					echo '<li style="border-top:1px solid" class="selected"><a class="exercise_program" href="javascript:void(0)" id="'.$program->ID.'">'.$program->post_name.'</a></li>';
				}
				else{
					echo '<li><a class="exercise_program" href="javascript:void(0);" id="'.$program->ID.'">'.$program->post_name.'</a></li>';
				}
				$i++;
			}
			echo '</ul>';
			echo '</div>';
		}
	}	
	public function list_user_program_content(){
		add_thickbox();
		global $wpdb,$current_user;
		$user_email			=	$current_user->data->user_email;
		$user_programs 		=	$this->get_user_programs( $user_email );
		$content			=	'';
		if( !empty( $user_programs ) ){
			$program	=	$user_programs[0];
		}
		$program_detail 	=	get_post( $program );		
		$exercises 			=	get_post_meta( $program_detail->ID, 'user-menus-exercises',TRUE);
		$order				=	get_post_meta( $program, 'user-menus-order', TRUE );
		$nbrExercies		=	get_post_meta( $program, 'user-menus-nbrExercies', TRUE );
       $unitSystem			=   get_post_meta( $program, 'user-menus-unitSystem', TRUE );
		$content .= 	'<div id="exercises_div">';		
		$content .= 	'<div style="font-size:25px;font-family:Michroma;margin-bottom:10px;text-transform: none;font-weight:bold;color:#000" id="program_name">'.ucfirst( $program_detail->post_title).'</div>';
		foreach($exercises as $exercise ) {
			$content .=	$this->get_exercise_template( $exercise );
		}
		$content .= 	'</div></div>';
		echo $content;
	}
	public function ajax_exercise_email_program(){
		$exercise_id		=	$_REQUEST['exercise_id'];
		$exercise			=	new WPUEP_Exercise( $exercise_id );
		$post_thumbnail_id	=	get_post_thumbnail_id( $exercise->ID() );
		$feature_image_url	=	wp_get_attachment_url( $post_thumbnail_id );
		$ins_array			=	array();
		if( $exercise->has_instructions()){
			foreach( $exercise->instructions() as $instruction ) {
				$ins_array[]	=	$instruction['description'];						
			}
		}
		$exercise_instructions 	=	'';
		if( !empty( $ins_array ) ){
			$exercise_instructions		=	implode( "||" , $ins_array );
		}
		$exercise_array			=	array(
										'ID' => $exercise_id,
										'image_link' => $feature_image_url,
										'link' => get_permalink( $exercise->ID() ),
										'instructions' => $exercise_instructions,
										'name'		=>	$exercise->title(),
										'description' => $exercise->description(),
										'title'		=>	$exercise->title(),
										'youtubelink' =>	$exercise->youtubelink(),
										'weight' 	=>	$exercise->weight(),
										'sets' 		=>	$exercise->sets(),
										'reps' 		=>	$exercise->reps()
									);
		echo $exercise_template 	=	$this->get_exercise_popup_template( $exercise_array );
		die();
	}
	public function get_exercise_popup_template( $exercise = array() ){
		$template 	=	'';
		$instruction_list	=	'<ol class="wpuep-exercise-instructions" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;margin:0 23px 5px 23px !important;">';		
		$instructions 		=	explode( '||', $exercise[ 'instructions' ] );
		$instructions 		=	explode( '||', $exercise[ 'instructions' ] );
		foreach( $instructions as $instruction ){
			$instruction_list.=  '<li class="wpuep-exercise-instruction" style="padding-top:5px !important;padding-bottom:15px !important;margin-bottom:10px !important;border-bottom:1px dashed #999 !important;list-style:decimal !important;"><span style="vertical-align:top !important;" itemprop="exerciseInstructions">'.$instruction.'</span></li>';	
		}
	    $instruction_list.=  '</ol>';
		if( !empty( $exercise ) ){
			$template .= 	'<table class="wpuep-table" style="border:0px !important;width:100% !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><tbody>';
			$template .= 	'<tr>
								<td style="text-align:inherit !important;border:0px !important;height:auto !important;width:auto !important;">
									<table class="wpuep-table" style="border:0px !important;width:100% !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;">
										<tbody>
											<tr>
												<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">				
									+				<span class="wpuep-exercise-title" style="border:0px !important;padding-left:10px !important;position:static !important;text-align:center !important;vertical-align:middle !important;font-weight:bold !important;font-size:20px !important;color:rgba(0,0,0,1) !important;" itemprop="name">'.$exercise[ 'name' ].'</span>
												</td>										
												<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
													<table class="wpuep-table" style="border:0px !important;width:100% !important;float:right !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;">
														<tbody>
															<tr>
																<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;"></td>
																<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;"></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>';
			$template .= 	'<tr>
								<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
									<div itemprop="image" itemscope="" itemtype="http://schema.org/ImageObject">
										<meta itemprop="contentUrl" content="'.$exercise[ 'image_link'].'">
										<img src="'.$exercise[ 'image_link'].'" itemprop="thumbnailUrl" alt="" title="plank" class="wpuep-exercise-image" style="padding-left:10px !important;width:95% !important;height:auto !important;position:static !important;border-width:0px 0px 0px 0px !important;border-style:dashed !important;text-align:inherit !important;vertical-align:inherit !important;">
									</div>
								</td>
							</tr>';
			$template .= 	'<tr>
								<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
									<span class="wpuep-box" style="border:0px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;">
										<table class="wpuep-table" style="border:0px !important;width:100% !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;">
											<tbody>
												<tr>
													<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
														<table class="wpuep-table" style="border:0px !important;width:100% !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;">
															<tbody>
																<tr>
																	<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">&nbsp;</td>
																	
																	<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">&nbsp;</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>												
												<tr>
													<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
														<table class="wpuep-table" style="border:0px !important;width:100% !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;">
														<tbody>';
		if( !empty( $exercise['youtubelink'] ) ){
			$template .= 
							'<tr>
								<td style="border:0px !important;text-align:right !important;height:auto !important;width:auto !important;">
									<span class="wpuep-title" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:15px !important;color:rgba(0,0,0,1) !important;">Not Sure? <a href="'.$exercise['youtubelink'].'">Watch the video</a></span>                        
								</td>
							</tr>';
		}
		$template .= 	'<tr>
		<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;" align="right">
																		<span class="wpuep-title" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:15px !important;color:rgba(0,0,0,1) !important;">Exercise Details</span>                        
																	</td>
																</tr>																
																<tr>
																	<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
																		<span class="wpuep-exercise-description" style="border:0px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;" itemprop="description">'.$exercise['description'].'</span>                        
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>												
												<tr>
													<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
														<table class="wpuep-table" style="border:0px !important;width:100% !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;">
															<tbody>
																<tr>
																	<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">
																		<span class="wpuep-title" style="border:0px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:15px !important;color:rgba(0,0,0,1) !important;">Instructions</span>                        
																	</td>
																</tr>																
																<tr>
																	<td style="border:0px !important;text-align:inherit !important;height:auto !important;width:auto !important;">'.$instruction_list.'</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</span>
								</td>
							</tr>';			
			$template .= 	'</tbody></table>';
		}
		return $template;
	}
	public function get_exercise_template( $exercise = array()){
		$template 	=	'';
		$ajax_url =	add_query_arg(
						array(
							'action' => 'exercise_email_program',
							'exercise_id' => $exercise[ 'id' ],
							'height' => 550,
							'width' => 800
						),
						admin_url( 'admin-ajax.php' )
					);
		if( !empty( $exercise ) ) {
			$template .= 	'<div class="wpupg-type-exercise wpuep-container">';
			$template .= 	'<table class="wpuep-table"><tbody>';
			$template .= 	'<tr>';
			$template .= 	'<td valign="top" style="width:50% !important;text-align:inherit !important;height:auto !important;">';
			$template .= 	'<a href="'.$ajax_url.'" class="thickbox">';
			$template .= 	'<span class="wpuep-exercise-title" style="margin-left:5px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:30px !important;color:rgba(0,0,0,1) !important;">'.$exercise['name'].'</span>';
			$template .= 	'<div><img src="'.$exercise['image_link'].'" alt="'.$exercise['image_link'].'" title="'.$exercise['title'].'" class="wpuep-exercise-image" style="width:92% !important;text-align:inherit !important;vertical-align:inherit !important;"></div></a>';
			$template .= 	'<table class="wpuep-table" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;"><tbody>';
			$template .= 	'<tr><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px;color:rgba(0,0,0,1) !important;">Weight</span></td>';
			$template .= 	'<td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" value="'.$exercise['weight'].'" style="width:40px;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td>';
			$template .= 	'<td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px ;color:rgba(0,0,0,1) !important;">Sets</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:40px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" value="'.$exercise['sets'].'" style="width:40px;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td>';
			$template .= 	'<td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="width:81px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px;color:rgba(0,0,0,1) !important;">Reps/Time</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:80px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" value="'.$exercise['reps'].'" style="width:40px;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"></span></td></tr></tbody></table></td>';						
			$template .= 	'<td width="50%" style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="margin-left:10px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:18px !important;color:rgba(0,0,0,1) !important;">INSTRUCTIONS</span>';						
			$template .= 	'<ol class="wpuep-exercise-instructions" style="font-size:14px !important;height:350px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;">';			
			if( $exercise['instructions'] != '' ){
				$instructions	=	explode( '||', $exercise['instructions'] );				
				for( $k=0; $k<sizeof($instructions); $k++ ){
					$template.=	'<li>'.$instructions[$k].'</li>';
				}
			}
			$template .= 	'</ol></td></tr></tbody></table>';
			$template .= 	'</tr></tbody></table>';
			$template .= 	'</div>';
		}
		return	$template;
	}
	public function get_user_programs( $user_email = 0 ){
		global $wpdb,$current_user;
		$querystr 		=	"SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `meta_key`='user-menus-email' AND `meta_value`='".$user_email."' ORDER BY meta_id DESC";
		$user_programs	=	$wpdb->get_results($querystr, OBJECT);
		$programs	=	array();
		foreach( $user_programs as $program ){
			$programs[]	=	$program->post_id;
		}
		return $programs;
	}
    public function eassets() {
        WPUltimateExercise::get()->helper( 'eassets' )->add(
            array(
                'file' => WPUltimateExercise::get()->coreUrl . '/vendor/select2/select2.css',
                'direct' => true,
                'public' => true,
                'setting_inverse' => array( 'user_menus_enable', 'off' ),
            ),
            array(
                'file' => $this->addonPath . '/css/user-menus.css',
                'premium' => true,
                'public' => true,
                'setting_inverse' => array( 'user_menus_enable', 'off' ),
            ),
            array(
                'name' => 'select2',
                'file' => '/vendor/select2/select2.min.js',
                'public' => true,
                'setting_inverse' => array( 'user_menus_enable', 'off' ),
                'deps' => array(
                    'jquery',
                ),
            ),
            array(
                'name' => 'wpuep-user-menus',
                'file' => $this->addonPath . '/js/user-menus.js',
                'premium' => true,
                'public' => true,
                'setting_inverse' => array( 'user_menus_enable', 'off' ),
                'deps' => array(
                    'jquery',
                    'wpuep-unit-conversion',
                    'js-quantities',
                    'jquery-ui-sortable',
                    'jquery-ui-droppable',
                    'select2',
                ),
                'data' => array(
                    'name' => 'wpuep_user_menus',
                    'ajaxurl' => WPUltimateExercise::get()->helper('eajax')->url(),
                    'addonUrl' => $this->addonUrl,
                    'nonce' => wp_create_nonce( 'wpuep_user_menus' ),
                    'consolidate_ingredients' => WPUltimateExercise::option( 'user_menus_consolidate_ingredients', '1' ),
                    'adjustable_system' => WPUltimateExercise::option( 'user_menus_dynamic_unit_system', '1' ),
                    'default_system' => WPUltimateExercise::option( 'user_menus_default_unit_system', '0' ),
                    'static_systems' => $this->get_static_unit_systems(),
                    'checkboxes' => WPUltimateExercise::option( 'user_menus_checkboxes', '1' ),
                    'ingredient_notes' => WPUltimateExercise::option( 'user_menus_ingredient_notes', '0' ) == '1' ? true : false,
                    'fractions' => WPUltimateExercise::option( 'exercise_adjustable_servings_fractions', '0' ) == '1' ? true : false,
                    'print_exercise_list' => WPUltimateExercise::option( 'user_menus_print_with_menu', '0' ) == '1' ? true : false,
                    'print_exercise_list_header' => '<tr><th>' . __( 'Exercise', 'wp-ultimate-exercise' ) . '</th><th>' . __( 'Servings', 'wp-ultimate-exercise' ) . '</th></tr>',
                    'custom_print_shoppinglist_css' => WPUltimateExercise::option( 'custom_code_print_shoppinglist_css', '' ),
                )
            ),
            array(
                'file' => $this->addonPath . '/js/add-to-shopping-list.js',
                'premium' => true,
                'public' => true,
                'setting_inverse' => array( 'user_menus_add_to_shopping_list', 'off' ),
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpuep_add_to_shopping_list',
                    'ajaxurl' => WPUltimateExercise::get()->helper('eajax')->url(),
                    'nonce' => wp_create_nonce( 'wpuep_add_to_shopping_list' ),
                )
            ),
            array(
                'file' => $this->addonPath . '/css/ingredient-groups.css',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_ingredient_groups',
            ),
            array(
                'file' => $this->addonPath . '/js/ingredient-groups.js',
                'premium' => true,
                'admin' => true,
                'page' => 'exercise_page_wpuep_ingredient_groups',
                'deps' => array(
                    'jquery',
                    'jquery-ui-sortable',
                    'jquery-ui-droppable',
                ),
                'data' => array(
                    'name' => 'wpuep_ingredient_groups',
                    'ajaxurl' => WPUltimateExercise::get()->helper('eajax')->url(),
                    'nonce' => wp_create_nonce( 'wpuep_ingredient_groups' )
                )
            )
        );
    }
    public function ingredient_groups_menu(){
        add_submenu_page( 'edit.php?post_type=exercise', 'WP Ultimate Exercise ' . __( 'Ingredient Groups', 'wp-ultimate-exercise' ), __( 'Ingredient Groups', 'wp-ultimate-exercise' ), 'manage_options', 'wpuep_ingredient_groups', array( $this, 'ingredient_groups_menu_page' ) );
    }
    public function ingredient_groups_menu_page() {
        include( $this->addonDir . '/templates/ingredient-groups.php' );
    }
    public function ajax_ingredient_groups_save(){
        if(check_ajax_referer( 'wpuep_ingredient_groups', 'security', false ) ){
            $ingredients = $_POST['ingredients'];
            $group = $_POST['group'];
            foreach( $ingredients as $slug) {
                WPUEP_Taxonomy_MetaData::set( 'ingredient', $slug, 'group', $group );
            }
        }
        die();
    }
    public function ajax_add_to_shopping_list(){
		echo 'Exercise Function';
        if(check_ajax_referer( 'wpuep_add_to_shopping_list', 'security', false ) ){
            $exercise_id = intval( $_POST['exercise_id'] );
            $servings_wanted = intval( $_POST['servings_wanted'] );
            $exercise = new WPUEP_Exercise( $exercise_id );
            $servings_wanted = $servings_wanted < 1 ? $exercise->servings_normalized() : $servings_wanted;
            $shopping_list_exercises = array();
			$wp_session	=	WP_Session::get_instance();
			$COOKIE_E =	$wp_session['WPUEP_Shopping_List_Exercies_v2'];
			if($COOKIE_E ){
               $shopping_list_exercises = explode( ';', stripslashes( $COOKIE_E ) );
            }
           /* if( isset( $_COOKIE['WPUEP_Shopping_List_Exercies_v2'] ) ) {
                $shopping_list_exercises = explode( ';', stripslashes( $_COOKIE['WPUEP_Shopping_List_Exercies_v2'] ) );
            }*/            
			$shopping_list_exercises[] = $exercise_id;
			$shopping_list_servings = array();
			$COOKIE_S =	$wp_session['WPUEP_Shopping_List_Servings_v2'];
			if($COOKIE_S ){
               $shopping_list_servings = explode( ';', stripslashes( $COOKIE_S ) );
            }
          // if( isset( $_COOKIE['WPUEP_Shopping_List_Servings_v2'] ) ) {
            //   $shopping_list_servings = explode( ';', stripslashes( $_COOKIE['WPUEP_Shopping_List_Servings_v2'] ) );
		   //}		   
		   $shopping_list_servings[] = $servings_wanted;
		   $shopping_list_order = array();
		   $COOKIE_O =	$wp_session['WPUEP_Shopping_List_Order_v2'];
			if($COOKIE_O ){
               $shopping_list_order = explode( ';', stripslashes( $COOKIE_O ) );
            }
           // if( isset( $_COOKIE['WPUEP_Shopping_List_Order_v2'] ) ) {
             //   $shopping_list_order = explode( ';', stripslashes( $_COOKIE['WPUEP_Shopping_List_Order_v2'] ) );
 //           }
            $shopping_list_order[] = ( count( $shopping_list_exercises ) -1 );
            //	Set or update cookies, expires in 30 days
			$shop_exercise	= implode( ';',$shopping_list_exercises );
			$wp_session['WPUEP_Shopping_List_Exercies_v2']	=	$shop_exercise;
			
			$shop_serving	= implode( ';',$shopping_list_servings );		
			$wp_session['WPUEP_Shopping_List_Servings_v2']	=	$shop_serving;
			
			$shop_order	= implode( ';',$shopping_list_order );		
			$wp_session['WPUEP_Shopping_List_Order_v2']	=	$shop_order;			
          //setcookie( 'WPUEP_Shopping_List_Exercies_v2', implode( ';', $shopping_list_exercises ), time()+60*60*24*30, '/' );
           //setcookie( 'WPUEP_Shopping_List_Servings_v2', implode( ';', $shopping_list_servings ), time()+60*60*24*30, '/' );
            //setcookie( 'WPUEP_Shopping_List_Order_v2', implode( ';', $shopping_list_order ), time()+60*60*24*30, '/' );
        }
        die();
    }
	public function ajax_email_program( ){
		if(	check_ajax_referer( 'wpuep_user_menus', 'security', false ) ){
			$settings		=	array();
			$settings['full_exercises']	=	$_POST['exercises'];
            $settings['order']			=	$_POST['order'];
			$settings['title']			=	$_POST['title'];
			$settings['client_email']	=	$_POST['user_email'];
			$upload_dir 				=	wp_upload_dir();

			$header	=	'<style type="text/css">.wpuep-container{ border:2px solid black;}</style>';						
			$header .=	'<div style="margin: 0 auto;width:95% !important;">';
			$header .= 	'<table cellpadding="5px" autosize="1" width="100%" style="overflow: wrap"><tr>';			
			$header .= 	'<td style="font-size:20px;" width="50%" valign="bottom"><span></span id="exercise_date">'.$exercise_date.' <span id="program_name">'.$settings['title'].'</span></td>';
			$header .= 	'<td width="50%" align="right"><img style="width:30% !important;" src="'.$upload_dir['baseurl'].'/2015/07/logo.png" alt="" title=""></td>';
			$header .= 	'</tr></table>';
			$header .= 	'<hr style="border: 3px outset #595955;">';
			$settings['header']	=	$header;
			$response	=	$this->send_email_with_attachment( $settings );
			echo $response;
			exit;
		}
		die();
	}
	public function client_email_program(){

		if(	check_ajax_referer( 'wpuep_user_menus', 'security', false ) ){
			$settings		=	array();			
			$program_id					=	$_REQUEST['program_id']; //'6799';			
			$settings['full_exercises']	=	get_post_meta( $program_id, 'user-menus-exercises', TRUE );			
            $settings['order']			=	array();
			$settings['title']			=	get_the_title( $program_id );
			$settings['client_email']	=	$_REQUEST['user_email'];
			$upload_dir 				=	wp_upload_dir();

			//	Get the header
			$header	=	'<style type="text/css">.wpuep-container{ border:2px solid black;}</style>';						
			$header .=	'<div style="margin: 0 auto;width:95% !important;">';
			$header .= 	'<table cellpadding="5px" autosize="1" width="100%" style="overflow: wrap"><tr>';			
			$header .= 	'<td style="font-size:20px;" width="50%" valign="bottom"><span></span id="exercise_date">'.$exercise_date.' <span id="program_name">'.$settings['title'].'</span></td>';
			$header .= 	'<td width="50%" align="right"><img style="width:30% !important;" src="'.$upload_dir['baseurl'].'/2015/07/logo.png" alt="" title=""></td>';
			$header .= 	'</tr></table>';
			$header .= 	'<hr style="border: 3px outset #595955;">';
			$settings['header']	=	$header;
			$response	=	$this->send_email_with_attachment( $settings );
			echo $response;
			exit; 
		}
		die();
	}
	public function send_email_with_attachment( $settings = array() ){		
		require_once( WP_PLUGIN_DIR  .'/wp-ultimate-exercise-premium/mailer/class.phpmailer.php' );	
		//	require_once( WP_PLUGIN_DIR  .'/wp-ultimate-exercise-premium/mpdf/src/Mpdf.php' );		
		require_once( WP_PLUGIN_DIR  .'/wp-ultimate-exercise-premium/vendor/autoload.php' );
		//	Create new mPDF Document
		$mpdf = new \Mpdf\Mpdf();		 		
		$pdf_template	=	'';
		//	Get Settings
		$exercises		=	$settings['full_exercises'];
		$order			=	$settings['order'];
		$title			=	$settings['title'];
		$client_email	=	$settings['client_email']; //'developer.codeflox@gmail.com';
		$header			=	$settings['header'];

		//	Get the content	
		$content .= 	'<div id="exercises_div">';
		if( !empty( $exercises )){
			foreach( $exercises as $exercise ){
				$exercise_detail	=	new WPUEP_Exercise( $exercise['id'] );
				$header .= 	'<div class="wpuep-container">';
				$header .= 	'<table class="wpuep-table" style="width:100%;position:static !important;text-align:inherit !important;vertical-align:inherit !important;border:2px solid black !important;"><tbody>';
				$header .= 	'<tr>';
				$header .= 	'<td valign="top" style="width:50% !important;text-align:inherit !important;height:auto !important;">';
				$header .= 	'<a href="'.$exercise['link'].'" class="thickbox">';
				$header .= 	'<span class="wpuep-exercise-title" style="margin-left:5px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:30px !important;color:rgba(0,0,0,1) !important;">'.$exercise['name'].'</span>';
				$header .= 	'<div><img src="'.$exercise['image_link'].'" alt="'.$exercise['image_link'].'" title="'.$exercise['image_link'].'" class="wpuep-exercise-image" style="height:50% !important;width:92% !important;text-align:inherit !important;vertical-align:inherit !important;"></div></a><table class="wpuep-table" style="position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;"><tbody><tr><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px ;color:rgba(0,0,0,1) !important;">Weight</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="float:left !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input readonly="readonly" type="text" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'.$exercise['weight'].'" style="width:40px;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="float:left !important;width:40px;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px;color:rgba(0,0,0,1) !important;">Sets</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:40px;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input type="number" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'.$exercise['sets'].'" style="width:40px;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"> </span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="width:81px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:16px;color:rgba(0,0,0,1) !important;">Reps/Time</span></td><td style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-exercise-servings-changer" style="width:80px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;"><input type="number" class="advanced-adjust-exercise-servings" data-original="4" data-start-servings="4" value="'.$exercise['reps'].'" style="width:40px;padding:2px !important;background:white !important;border:1px solid #bbbbbb !important;"></span></td></tr></tbody></table></td>';						
				$header .= 	'<td width="50%" style="text-align:inherit !important;height:auto !important;width:auto !important;"><span class="wpuep-title" style="margin-left:10px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;font-weight:bold !important;font-size:18px !important;color:rgba(0,0,0,1) !important;">INSTRUCTIONS</span>';						
				$header .= 	'<ol class="wpuep-exercise-instructions" style="font-size:14px !important;height:350px !important;position:static !important;text-align:inherit !important;vertical-align:inherit !important;color:rgba(0,0,0,1) !important;">';
				if( $exercise['instructions'] != '' ){
					$instructions	=	explode( '||', $exercise['instructions'] );				
					for( $k=0; $k<sizeof($instructions); $k++ ){
						$header.=	'<li>'.$instructions[$k].'</li>';
					}
				}
				$header .= 	'</ol></td></tr></tbody></table>';
				$header .= 	'</tr></tbody></table>';
				$header .= 	'</div>';
			}	
		}

		$content .= 	'</div>';	
		// Merge it all into the HTML
		$html = $header;;// . $content;
		// Write HTML
		$mpdf->WriteHTML( $html );
		$pdf	=	$mpdf->Output( '', 'S' );						
		$mail = new PHPMailer();
		$body	=	'<div style="font-size:13px;margin-bottm:7px;">Dear Epic Win PT Client,</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Please find attached the exercise program that has been devised for you based on our assessments.</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;color:red;">Your program can be found online in your Epic Win PT Members Profile.<a href="'.get_permalink(1312).'">HERE</a></div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Should you have any concerns relating to any of the exercises included, please contact us immediately.</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Keep on Winning!</div><br/>'; 
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Regards,</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">The Epic Win PT Team</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">www.epicwinpt.com.au</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Phone: 0412 522 792</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Facebook: www.facebook.com/EpicWinPT</div><br/>';

		$mail->AddReplyTo("info@epicwinpt.com.au","Epic Win PT INFO");
		$mail->SetFrom('info@epicwinpt.com.au', 'Epic Win PT INFO');		
		$mail->AddAddress($client_email, "");
		$mail->Subject    = 'Epic Win PT - Exercise Program';
		$mail->AltBody    = 'Epic Win PT - Exercise Program';
		$mail->MsgHTML($body);
		$path			=	$title.'.pdf';
		$response 	=	 array();

		/*if(mail($client_email,'Epic Win PT - Exercise Program',$body)){
			$response	=	json_encode( array( 'response' => 1  ));
		}
		else{
			$response	=	json_encode( array( 'response' => 0  ));
		}*/
		$mail->AddStringAttachment($pdf, $path, $encosding = 'base64', $type = 'application/pdf');
		
		if( $mail->Send() ){
			$response	=	json_encode(
			 							array( 
			 								'response' => 1,
			 								'message' => "Email successfully delivered"
			 							 )
			 						);
		}
		else{
			$response	=	json_encode( 
										array(
											 'response' => 0,
											 'message' => "Email not delivered"
										 )
									);
		}
		
		return	$response;
	}
	public function ajax_program_detail(){
		if(check_ajax_referer( 'wpuep_user_menus', 'security', false ) ){
            $program_id		=	$_POST['program_id'];
			$exercises		=	get_post_meta( $program_id, 'user-menus-exercises', TRUE );
			foreach( $exercises as $exercise ) {
				$content .=	$this->get_exercise_template( $exercise );
			}
			echo json_encode( array( 'response' => 1,'data' => $content ) );
			exit;
		}
		else{
			echo json_encode( array( 'response' => 0 ) );
			exit;
		}
	}
    public function ajax_update_shopping_list(){
        if(check_ajax_referer( 'wpuep_user_menus', 'security', false ) ) {
            $full_exercises = $_POST['exercises'];
            $order = $_POST['order'];
            $exercises = array();
            $servings = array();
            foreach( $full_exercises as $exercise ) {
                $exercises[] = $exercise['id'];
                $servings[] = $exercise['servings_wanted'];
            }
            // Set or update cookies, expires in 30 days
			$wp_session	=	WP_Session::get_instance();
			$shop_exercise	= implode( ';',$exercises );
			$wp_session['WPUEP_Shopping_List_Exercies_v2']	=	$shop_exercise;
			
			$shop_serving	= implode( ';',$servings );		
			$wp_session['WPUEP_Shopping_List_Servings_v2']	=	$shop_serving;
			
			$shop_order	= implode( ';',$order );
			$wp_session['WPUEP_Shopping_List_Order_v2']	=	$shop_order;
			
           //setcookie( 'WPUEP_Shopping_List_Exercies_v2', implode( ';', $exercises ), time()+60*60*24*30, '/' );
            //setcookie( 'WPUEP_Shopping_List_Servings_v2', implode( ';', $servings ), time()+60*60*24*30, '/' );
            //setcookie( 'WPUEP_Shopping_List_Order_v2', implode( ';', $order ), time()+60*60*24*30, '/' );
        }
        die();
    }
    public function menus_init(){
        $slug = WPUltimateExercise::option( 'user_emenus_slug', 'exercise_menu' );
        $name = __( 'Exercise Menus', 'wp-ultimate-exercise' );
        $singular = __( 'Exercise Menu', 'wp-ultimate-exercise' );
        $args = apply_filters( 'wpuep_register_menu_post_type',
            array(
                'labels' => array(
                    'name' => $name,
                    'singular_name' => $singular,
                    'add_new' => __( 'Add New', 'wp-ultimate-exercise' ),
                    'add_new_item' => __( 'Add New', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'edit' => __( 'Edit', 'wp-ultimate-exercise' ),
                    'edit_item' => __( 'Edit', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'new_item' => __( 'New', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'view' => __( 'View', 'wp-ultimate-exercise' ),
                    'view_item' => __( 'View', 'wp-ultimate-exercise' ) . ' ' . $singular,
                    'search_items' => __( 'Search', 'wp-ultimate-exercise' ) . ' ' . $name,
                    'not_found' => __( 'No', 'wp-ultimate-exercise' ) . ' ' . $name . ' ' . __( 'found.', 'wp-ultimate-exercise' ),
                    'not_found_in_trash' => __( 'No', 'wp-ultimate-exercise' ) . ' ' . $name . ' ' . __( 'found in trash.', 'wp-ultimate-exercise' ),
                    'parent' => __( 'Parent', 'wp-ultimate-exercise' ) . ' ' . $singular,
                ),
                'public' => true,
                'menu_position' => 5,
                'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'author' ),
                'taxonomies' => array( '' ),
                'menu_icon' =>  WPUltimateExercise::get()->coreUrl . '/img/icon_16.png',
                'has_archive' => true,
                'rewrite' => array(
                    'slug' => $slug
                ),
                'show_in_menu' => 'edit.php?post_type=exercise',
            )
        );		
        register_post_type( 'exercise_menu', $args );
    }
    public function user_menus_admin_init() {
        add_meta_box(
            'user_menus_meta_box',
            __( 'Menu', 'wp-ultimate-exercise' ),
            array( $this, 'user_menus_meta_box' ),
            'menu',
            'normal',
            'high'
        );
    }
    public function user_menus_meta_box( $menu, $menu_id = '' ){
        _e( 'The menu can be edited from the front end:', 'wp-ultimate-exercise' );
        echo '<br/>';
        echo '<a href="'.get_permalink( $menu->ID ).'">';
        echo get_the_title( $menu->ID );
        echo '</a>';
    }
    public function user_menus_content( $content ) {
        if ( is_single() && get_post_type() == 'menu' ) {
            remove_filter( 'the_content', array( $this, 'user_menus_content' ), 10 );
            $menu = get_post();
            $exercises = get_post_meta( $menu->ID, 'user-menus-exercises' );
            $order = get_post_meta( $menu->ID, 'user-menus-order' );
            $script_name = WPUltimateExercise::option( 'assets_use_minified', '1' ) == '1' ? 'wpuep_script_minified' : 'wpuep-user-menus';
            wp_localize_script( $script_name, 'wpuep_user_menu',
                array(
                    'exercises' => $exercises[0],
                    'order' => $order[0],
                    'nbrExercies' => get_post_meta( $menu->ID, 'user-menus-nbrExercies', true ),
                    'unitSystem' => get_post_meta( $menu->ID, 'user-menus-unitSystem', true ),
                    'menuId' => $menu->ID,
                )
            );
            ob_start();
            include( $this->addonDir . '/templates/user-menus.php');
            $menu_content = ob_get_contents();
            ob_end_clean();			
            $content .= apply_filters( 'wpuep_user_menus_form', $menu_content, $menu );
            add_filter('the_content', array( $this, 'user_menus_content' ), 10);
        }		
        return $content;
    }	
    public function ajax_user_menus_delete(){
        if( check_ajax_referer( 'wpuep_user_menus', 'security', false ) ){
            global $user_ID;
            $menu_id = intval( $_POST['menuId'] );
            $menu = get_post( $menu_id );
            if( $menu->post_type == 'menu' && ( current_user_can( 'manage_options' ) || $menu->post_author == $user_ID ) ) {
                wp_delete_post( $menu_id );
            }			
            die( get_home_url() );
        }		
        die();
    }	
    public function ajax_user_menus_save(){
		//	Initialize response array	
		$response	=	array();
        if( check_ajax_referer( 'wpuep_user_menus', 'security', false ) ){
			global $user_ID;
           $menu_id 		=	intval( $_POST['menuId'] );
           $title 			=	$_POST['title'];
           $exercises		=	$_POST['exercises'];
			$user_email		=	$_POST[ 'user_email' ];
           $order 			=	$_POST['order'];
           $nbrExercies	=	$_POST['nbrExercies'];
           $unitSystem		=	$_POST['unitSystem'];
           // Create new menu
           if( $menu_id === 0 ){
			   $post = array(
					'post_status' => 'publish',
                  'post_author' => $user_ID,
                  'post_type' => 'exercise_menu',
                  'post_title' => $title,
                );
				// Save post
				$menu_id = wp_insert_post( $post );
				$email_settings		=	array(
											'user_email' => $user_email,
											'program_id' => $menu_id
										);
                // Blank slate for User Menus
				$wp_session	=	WP_Session::get_instance();
				$wp_session['WPUEP_Shopping_List_Exercies_v2'];
				$wp_session['WPUEP_Shopping_List_Servings_v2'];
				$wp_session['WPUEP_Shopping_List_Order_v2'];
				/*setcookie( 'WPUEP_Shopping_List_Exercies_v2', '', time()+60*60*24*30, '/' );
            setcookie( 'WPUEP_Shopping_List_Servings_v2', '', time()+60*60*24*30, '/' );
            setcookie( 'WPUEP_Shopping_List_Order_v2', '', time()+60*60*24*30, '/' );	*/
			} else {
                $post = array(
                    'ID' => $menu_id,
                    'post_title' => $title
                );
                wp_update_post( $post );
            }
			update_post_meta( $menu_id, 'user-menus-exercises', $exercises );
           update_post_meta( $menu_id, 'user-menus-order', $order );
			update_post_meta( $menu_id, 'user-menus-email', $user_email );
           update_post_meta( $menu_id, 'user-menus-nbrExercies', $nbrExercies );
           update_post_meta( $menu_id, 'user-menus-unitSystem', $unitSystem );          
			echo json_encode( array( 'response' => 1, 'menu_link' => get_permalink( $menu_id ) ) );			
			$wp_session=WP_Session::get_instance();
			$reset	=	$wp_session->reset();			
			die();
        }
		else{
			echo json_encode( array( 'response' => 0 ) );
			exit;
		}		
        die();
    }	
	public function send_program_email( $settings = array() ){
		$body	=	'<div style="font-size:13px;margin-bottm:7px;">Dear Epic Win PT Client,</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Please find attached the exercise program that has been devised for you based on our assessments.</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;color:red;">Your program can be found online in your Epic Win PT Members Profile.<a href="'.get_permalink(1312).'">HERE</a></div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Should you have any concerns relating to any of the exercises included, please contact us immediately.</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Keep on Winning!</div><br/>'; 
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Regards,</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">The Epic Win PT Team</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">www.epicwinpt.com.au</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Phone: 0412 522 792</div><br/>';
		$body.=		'<div style="font-size:13px;margin-bottm:7px;">Facebook: www.facebook.com/EpicWinPT</div><br/>';
		//	Define Email Variables
		$from_name 		=	'Epic Win PT INFO';
		$from_mail		=	'info@epicwinpt.com.au';
		$replyto		=	'info@epicwinpt.com.au';
		$mail_to		=	$client_email;
		//	$mail_to		=	'jonokulicz@gmail.com';	
		$subject		=	'Epic Win PT - Exercise Program';
		$uid			=	md5(uniqid(time()));
		$eol 			=	PHP_EOL;
		$headers	='MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html;charset=iso-8859-1' . "\r\n";
		$header	 .= "From: ".$from_name." <".$from_mail.">".$eol;
		$header  .= "Reply-To: ".$replyto.$eol;
		$mail	=	mail($to, $subject, $body, $headers);
		if(!$mail) {
			echo "Error sending email";
		}
		else {
			echo "Your email was sent successfully.";
		}
	}
    public function ajax_user_menus_get_ingredients(){
        if( check_ajax_referer( 'wpuep_user_menus', 'security', false ) ){
            $exercise_id = intval( $_POST['exercise_id'] );
            $ingredients = get_post_meta( $exercise_id, 'exercise_ingredients' );
            $ingredients = $ingredients[0];
            $ingredients_with_groups = array();
            if( is_array( $ingredients ) ) {
                foreach( $ingredients as $ingredient ){
                    $term = get_term( $ingredient['ingredient_id'], 'ingredient' );
                    if ( $term !== null && !is_wp_error( $term ) ) {
                        $ingredient['ingredient'] = $term->name;
                    }
                    $plural = WPUEP_Taxonomy_MetaData::get( 'ingredient', $term->slug, 'plural' );
                    if( $plural && !is_array( $plural ) ) {
                        $ingredient['plural'] = $plural;
                    }
                    $ingredient['group'] = WPUEP_Taxonomy_MetaData::get( 'ingredient', $term->slug, 'group' );
                    if( !$ingredient['group'] ) {
                        $ingredient['group'] = __( 'Other', 'wp-ultimate-exercise' );
                    }
                    $ingredients_with_groups[] = $ingredient;
                }
            }
            echo json_encode( $ingredients_with_groups );
        }
        die();
    }
    public function ajax_user_menus_groupby(){
        if( check_ajax_referer( 'wpuep_user_menus', 'security', false ) ){
            $groupby = $_POST['groupby'];
            $groups = $this->get_exercises_grouped_by( $groupby );
            echo $this->get_select_options( $groups );
        }
        die();
    }
    public function get_exercises_grouped_by( $groupby ){
        global $wpuep;
        $exercises_grouped = array();
        switch( $groupby ) {
            case 'a-z':
                $exercises = WPUltimateExercise::get()->query()->order_by( 'title' )->order( 'ASC' )->get();
                $current_letter = '';
                $current_exercises = array();
                foreach( $exercises as $exercise ) {
                    $letter = strtoupper( mb_substr( $exercise->title(), 0, 1 ) );
                    if( $letter != $current_letter ) {
                        if($current_letter != '') {
                            $exercises_grouped[] = array(
                                'group_name' => $current_letter,
                                'exercises' => $current_exercises
                            );
                        }
                        $current_letter = $letter;
                        $current_exercises = array();
                    }
                    $current_exercises[] = $exercise;
                }
                if( count( $current_exercises ) > 0 ) {
                    $exercises_grouped[] = array(
                        'group_name' => $current_letter,
                        'exercises' => $current_exercises
                    );
                }
                break;
            default:
                $terms = get_terms( $groupby );
                foreach( $terms as $term ) {
                    $exercises_grouped[] = array(
                        'group_name'    => $term->name,
                        'exercises' => WPUltimateExercise::get()->query()->order_by( 'title' )->order( 'ASC' )->taxonomy( $groupby )->term( $term->slug )->get(),
                    );
                }
                break;
        }
        return $exercises_grouped;
    }	
    public function get_select_options($exercise_groups){
        $out = '<option></option>';
        foreach( $exercise_groups as $group ){
            $out .= '<optgroup label="'.$group['group_name'].'">';
            foreach( $group['exercises'] as $exercise ){				
                $servings = $exercise->servings_normalized();				
                if( $servings < 1 ) {
                    $servings = 1;
                }
				$post_thumbnail_id	=	get_post_thumbnail_id( $exercise->ID() );
				$feature_image_url	=	wp_get_attachment_url( $post_thumbnail_id );
				$exercise_array		=	array();
				if( $exercise->has_instructions() ){
					foreach( $exercise->instructions() as $instruction ) {	
						$exercise_array[]	=	$instruction['description'];
					}
				}
				$exercise_instructions 	=	'';
				if( !empty( $exercise_array ) ){
					$exercise_instructions		=	implode( "||" , $exercise_array );
				}
                $out .= '<option value="' . $exercise->ID() . '" data-sets="'.$exercise->sets().'" data-weight="'.$exercise->weight().'" data-reps="'.$exercise->reps().'" data-servings="' . $servings . '" data-link="' . $exercise->link() . '" data-image="'.$feature_image_url.'" data-instructions="'.$exercise_instructions.'">' . $exercise->title() . '</option>';
            }
            $out .= '</optgroup>';
        }
        return $out;
    }
    public function user_menus_shortcode(){
        switch( WPUltimateExercise::option( 'user_menus_enable', 'guests' ) ) {
            case 'off':
                return '<p class="errorbox">' . __( 'Sorry, the site administrator has disabled user menus.', 'wp-ultimate-exercise' ) . '</p>';
                break;
            case 'registered':
                if( !is_user_logged_in() ) {
                    return '<p class="errorbox">' . __( 'Sorry, only registered users may create menus.', 'wp-ultimate-exercise' ) . '</p>';
                }
            case 'guests':
                // Check for cookie
				$wp_session	=	WP_Session::get_instance();
			$COOKIE_E =	$wp_session['WPUEP_Shopping_List_Exercies_v2'];			
			$COOKIE_S =	$wp_session['WPUEP_Shopping_List_Servings_v2'];			
			$COOKIE_O =	$wp_session['WPUEP_Shopping_List_Order_v2'];			
					if(($COOKIE_E)&&($COOKIE_S)&&($COOKIE_O)){
            /*    if( isset( $_COOKIE['WPUEP_Shopping_List_Exercies_v2'] ) && isset( $_COOKIE['WPUEP_Shopping_List_Servings_v2'] ) && isset( $_COOKIE['WPUEP_Shopping_List_Order_v2'] ) ) {*/
					$exercise_ids = explode( ';', stripslashes( $COOKIE_E ) );					
                 /*$exercise_ids = explode( ';', stripslashes( $_COOKIE['WPUEP_Shopping_List_Exercies_v2'] ) );*/
					$servings = explode( ';', stripslashes( $COOKIE_S ) );	
                    //$servings = explode( ';', stripslashes( $_COOKIE['WPUEP_Shopping_List_Servings_v2'] ) );
					$order = explode( ';', stripslashes( $COOKIE_O ) );	
                    //$order = explode( ';', stripslashes( $_COOKIE['WPUEP_Shopping_List_Order_v2'] ) );
                    $exercises = array();
                    foreach( $exercise_ids as $index => $exercise_id ) {
                        $exercise = new WPUEP_Exercise( $exercise_id );
                        if( $exercise ) {
							$post_thumbnail_id	=	get_post_thumbnail_id( $exercise->ID() );
							$feature_image_url	=	wp_get_attachment_url( $post_thumbnail_id );														
							$exercise_array		=	array();
							if( $exercise->has_instructions() ){
								foreach( $exercise->instructions() as $instruction ) {
									$exercise_array[]	=	$instruction['description'];
								}
							}
							$exercise_instructions 	=	'';
							if( !empty( $exercise_array ) ){
								$exercise_instructions		=	implode( "||" , $exercise_array );
							}
                            $exercises[] = array(
                                'id' => $exercise->ID(),
                                'name' => $exercise->title(),
                                'link' => $exercise->link(),
								'instructions' => $exercise_instructions,
								'image_link' => $feature_image_url,
								'weight' => $exercise->weight(),
								'sets' => $exercise->sets(),
								'reps' => $exercise->reps(),
                                'servings_original' => $exercise->servings_normalized(),
                                'servings_wanted' => isset($servings[$index]) ? $servings[$index] : $exercise->servings_normalized(),
                            );
                        }
                    }
                    if( is_array( $exercises ) && is_array( $order ) ) {
                        $script_name = WPUltimateExercise::option( 'assets_use_minified', '1' ) == '1' ? 'wpuep_script_minified' : 'wpuep-user-menus';
                        wp_localize_script( $script_name, 'wpuep_user_menu',
                            array(
                                'exercises' => $exercises,
                                'order' => $order,
                                'nbrExercies' => count( $order ),
                                'unitSystem' => WPUltimateExercise::option('user_menus_default_unit_system','0'),
                                'menuId' => 0,
                            )
                        );
                    }
                }
                // Include template
                ob_start();
                include( $this->addonDir . '/templates/user-menus.php' );
                return ob_get_clean();
                break;
        }
    }
    public function display_menu_shortcode( $options ){
        $options = shortcode_atts( array(
            'id' => 'random', // If no ID given, show a random menu
            'template' => 'default'
        ), $options );
        $menu = null;
        if( $options['id'] == 'random' ) {
            $posts = get_posts(array(
                'post_type' => 'menu',
                'nopaging' => true
            ));
            $menu = $posts[ array_rand( $posts ) ];
        } else {
            $menu = get_post( intval( $options['id'] ) );
        }
        if( !is_null( $menu ) && $menu->post_type == 'menu' ){
            $exercises = get_post_meta( $menu->ID, 'user-menus-exercises' );
            $order = get_post_meta( $menu->ID, 'user-menus-order' );
            $script_name = WPUltimateExercise::option( 'assets_use_minified', '1' ) == '1' ? 'wpuep_script_minified' : 'wpuep-user-menus';
            wp_localize_script( $script_name, 'wpuep_user_menu',
                array(
                    'exercises' => $exercises[0],
                    'order' => $order[0],
                    'nbrExercies' => get_post_meta( $menu->ID, 'user-menus-nbrExercies', true ),
                    'unitSystem' => get_post_meta( $menu->ID, 'user-menus-unitSystem', true ),
                    'menuId' => $menu->ID,
                )
            );
            $menu_display_only = true;
            ob_start();
            include( $this->addonDir . '/templates/user-menus.php');
            $output = ob_get_contents();
            ob_end_clean();
        }
        else{
            $output = '';
        }
        return $output;
    }
    public function display_user_menus_by_shortcode( $options ){
        $options = shortcode_atts( array(
            'author' => 'current_user',
            'sort_by' => 'title',
            'sort_order' => 'ASC',
        ), $options );
        $author = strtolower( $options['author'] );
        $sort_by = strtolower( $options['sort_by'] );
        $sort_order = strtoupper( $options['sort_order'] );
        $sort_by = in_array( $sort_by, array( 'name', 'title', 'date' ) ) ? $sort_by : 'title';
        $sort_order = in_array( $sort_order, array( 'ASC', 'DESC' ) ) ? $sort_order : 'ASC';
        if( $author == 'current_user' ) {
            $author = get_current_user_id();
        } else {
            $author = intval( $author );
        }
        $output = '';
        if( $author !== 0 ) {
            $args = array(
                'post_type' => 'menu',
                'post_status' => 'publish',
                'author' => $author,
                'orderby' => $sort_by,
                'order' => $sort_order,
                'no-paging' => true,
                'posts_per_page' => -1,
            );
            $menus = get_posts( $args );
            if( count( $menus ) !== 0 ) {
                $output .= '<ul class="wpuep-user-menus-by">';
                foreach ( $menus as $menu ) {
                    $item = '<li><a href="' . get_permalink( $menu->ID ) . '">' . $menu->post_title .'</a></li>';
                    $output .= apply_filters( 'wpuep_user_menus_by_item', $item, $menu );
                }
                $output .= '</ul>';
            }
        }
        return $output;
    }
    public function get_static_unit_systems(){
        $nbr_of_systems = intval( WPUltimateExercise::option( 'user_menus_static_nbr_systems', '1' ) );
        $systems = array();
        for( $i = 1; $i <= $nbr_of_systems; $i++ ) {
            $systems[] = intval( WPUltimateExercise::option( 'user_menus_static_system_' . $i, '0' ) );
        }
        return $systems;
    }
}
WPUltimateExercise::loaded_addon( 'user-menus', new WPUEP_User_Menus() );