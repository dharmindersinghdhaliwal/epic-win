<?php
// Register Custom Post Type
function register_client_notes() {
	$labels = array(
		'name'                  => _x( 'Client Notes', 'Post Type General Name', 'cf' ),
		'singular_name'         => _x( 'Client Note', 'Post Type Singular Name', 'cf' ),
		'menu_name'             => __( 'Client Notes', 'cf' ),
		'name_admin_bar'        => __( 'Post Type', 'cf' ),
		'archives'              => __( 'Item Archives', 'cf' ),
		'attributes'            => __( 'Item Attributes', 'cf' ),
		'parent_item_colon'     => __( 'Parent Item:', 'cf' ),
		'all_items'             => __( 'All Items', 'cf' ),
		'add_new_item'          => __( 'Add New Item', 'cf' ),
		'add_new'               => __( 'Add New Client Note', 'cf' ),
		'new_item'              => __( 'New Item', 'cf' ),
		'edit_item'             => __( 'Edit Item', 'cf' ),
		'update_item'           => __( 'Update Item', 'cf' ),
		'view_item'             => __( 'View Item', 'cf' ),
		'view_items'            => __( 'View Items', 'cf' ),
		'search_items'          => __( 'Search Item', 'cf' ),
		'not_found'             => __( 'Not found', 'cf' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'cf' ),
		'featured_image'        => __( 'Featured Image', 'cf' ),
		'set_featured_image'    => __( 'Set featured image', 'cf' ),
		'remove_featured_image' => __( 'Remove featured image', 'cf' ),
		'use_featured_image'    => __( 'Use as featured image', 'cf' ),
		'insert_into_item'      => __( 'Insert into item', 'cf' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'cf' ),
		'items_list'            => __( 'Items list', 'cf' ),
		'items_list_navigation' => __( 'Items list navigation', 'cf' ),
		'filter_items_list'     => __( 'Filter items list', 'cf' ),
	);
	$args = array(
		'label'                 => __( 'Client Note', 'cf' ),
		'description'           => __( 'Post Type Description', 'cf' ),
		'labels'                => $labels,
		'supports'              => array( 'editor', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'client_notes', $args );

}
add_action( 'init', 'register_client_notes', 0 );


function display_client_notes(){
	?>
	<style>
	.page-id-5550 .client_search{
		display:none;
	}
	</style>	
	<div class="client-notes-main">
		<div id="loadingm" class="modal" style="display:none">
			  <div class="modal-content">
				<div class="modal-header">
				  <h2>Loading......</h2>
				</div>
				<div class="modal-body">
				<img src='https://members.epicwinpt.com.au/wp-content/themes/maxx-fitness-child-theme/x2_writing.gif' width="150" height="150" />
				</div>					
			  </div>
		</div>
		<div class="comment-section">
			<a href="javascript:void(0)" class="record_note">Record Client Notes</a>	
			<textarea rows="4" class="cmt" style="display:none"></textarea>	
			<a href="javascript:void(0)" class="submit_note"style="display:none">Submit Comment</a>
		</div>
		<h2>Client History</h2>
		<div class="display_comment">		
		</div>
	
	</div>	
	<script>
    jQuery(document).ready(function(e){(function($){
		$('.record_note').click(function(){
			$('.cmt').show();
			$('.submit_note').show();
			$('.record_note').hide();
		});
		
		//Save User Notes ajax
		$('.submit_note').click(function(){
			var metaid		= 	$('#client_email').val();
			var comment	=	$('.cmt').val();
			if(metaid==''){
				alert('Please Select Client');
				return false;
			}
			if(comment==''){
				alert('Please Add Comment');
				return false;
			}	
			jQuery.ajax({
				type:'POST',
				url:'<?php echo admin_url('admin-ajax.php'); ?>',
				data:{'action':'save_user_notes','metaid':metaid,'comment':comment},
				beforeSend: function(){$('#loadingm').show();},
				success:function(data){
					if(data){						
						$('#loadingm h2').html('Success !');
						$('#loadingm .modal-body').html('<img src="https://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/success.gif" width="150" height="150">');
						$('#loadingm').delay(5000).hide(0, function(){
							$('#loadingm').hide();				
							$('#loadingm h2').html('Loading....');
							$('#loadingm .modal-body').html('<img src="https://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/x2_writing.gif" width="150" height="150">');				
						});			
						$('.cmt').hide();
						$('.submit_note').hide();
						$('.record_note').show();
						$('.cmt').val('');						
					}
					else{
						alert('Unable to Save Comment');
					}
				}
			})
			jQuery.ajax({
				type:'POST',
				url:'<?php echo admin_url('admin-ajax.php'); ?>',
				data:{'action':'get_user_notes','metaid':metaid},
				success:function(data){					
					if(data){
						$('.display_comment').html(data);
					}
					else{
						$('.display_comment').html('');
						$('.display_comment').html('<h3 style="color:red">Nothing Found For this Client</h3>');
					}
				}
			})
		});
		
		//Retrive User Notes ajax
		$('.serch_user_notes').click(function(){
			var metaid		= 	$('#client_email').val();
			if(metaid==''){
				alert('Please Select User');
				return false;
			}			
			jQuery.ajax({
				type:'POST',
				url:'<?php echo admin_url('admin-ajax.php'); ?>',
				data:{'action':'get_user_notes','metaid':metaid},
				beforeSend: function(){
					$('#loadingm').show();					
				},
				success:function(data){
					$('#loadingm h2').html('Success !');
					$('#loadingm .modal-body').html('<img src="https://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/success.gif" width="150" height="150">');
					$('#loadingm').delay(5000).hide(0, function(){
						$('#loadingm').hide();				
						$('#loadingm h2').html('Loading....');
						$('#loadingm .modal-body').html('<img src="https://members.epicwinpt.com.au/wp-content/plugins/workout-tracker/img/x2_writing.gif" width="150" height="150">');				
					});											
					if(data){$('.display_comment').html(data);}
					else{
						$('.display_comment').html('');
						$('.display_comment').html('<h3 style="color:red">Nothing Found For this Client</h3>');
					}
				}
			})
		});
		
	}(jQuery))});
    </script>	
	<?php
} 
 add_shortcode('display_client_notes','display_client_notes');
 	
 function save_user_notes(){
	 if($_REQUEST){	
		 $uid			=	get_current_user_id();
		 $metaid	=	$_POST['metaid'];
		 $comment		=	$_POST['comment'];
		 
		 $my_post = array(
			'post_title'    => date("Y/m/d"),
			'post_content'  => $comment,
			'post_status'   => 'publish',
			'post_author'   => $uid,
			'post_type'		 => 'client_notes',
		);
		$post_id	=	wp_insert_post( $my_post );
		echo update_post_meta($post_id,'client_name',$metaid);
	}
	die();
 }
add_action( 'wp_ajax_save_user_notes','save_user_notes');
add_action('wp_ajax_nopriv_save_user_notes','save_user_notes');

function get_user_notes(){
	if($_REQUEST){
		$metaid		=	$_REQUEST['metaid'];
		$args = array(
			'post_type'		=>	'client_notes',
			'posts_per_page'	=>	-1,
			'meta_query' => array(
				array(
					'key' => 'client_name',
					'value' => $metaid
				)
			),
		);	
		$loop = new WP_Query($args);
		global $epic;
				while ( $loop->have_posts() ) : $loop->the_post();
				$client_note	=	$loop->post->post_content;				
				if($client_note){					
					echo '<div>';//.$epic->pic($client_note->post_author);					
					echo '<p class="client-name">'.get_author_name($client_note->post_author).'</p>';
					echo '<span>'.get_the_date($client_note->ID ).'</span>';
					echo ' / <span>'.get_the_time($client_note->ID).'</span></div>';	
					echo '<p class="cnotes">'.$client_note.'</p>';
				}
				else{
					echo 'Nothing Found For this Client';
				}
				endwhile;		
	}
	die();
}
add_action( 'wp_ajax_get_user_notes','get_user_notes');
add_action('wp_ajax_nopriv_get_user_notes','get_user_notes');

 function client_tracking_search_box_for_notes(){
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
			<span id="search_client_program" style="padding-left:3%"><button  class="serch_user_notes">Search Client</button></span>
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
add_shortcode( 'client_tracking_search_box_for_notes','client_tracking_search_box_for_notes');