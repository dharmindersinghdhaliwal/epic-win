<?php
$cf_client_progress=new CF_Client_Progress();
class CF_Client_Progress{
	function init(){
        $class = __CLASS__;
        new $class;
    }
	function __construct(){		
		add_action('plugins_loaded', array($this,'create_defualt_pages'));
		add_action( 'wp_enqueue_scripts',array($this,'add_css'));
		add_action( 'wp_enqueue_scripts',array($this,'add_js'));
		add_action('wp_ajax_save_image_ajax',array($this,'save_image_ajax'));
		add_action('wp_ajax_nopriv_save_image_ajax',array($this,'save_image_ajax'));
		add_action('wp_ajax_get_image_ajax',array($this,'get_image_ajax'));
		add_action('wp_ajax_nopriv_get_image_ajax',array($this,'get_image_ajax'));
		add_shortcode('add_new_cpi',array($this,'add_new_cpi'));
	}
	
	public function create_defualt_pages(){
		WP_CodeFlox::create_page('Add Client Progress Images','add-client-progress-images','[add_new_cpi]','');
	}
	
	public function add_css(){
		wp_enqueue_script( 'wp-ajax-response' );
		wp_enqueue_script('image-edit');
		wp_enqueue_style('imgareaselect');
		wp_register_style('fitness-progress',plugins_url('/css/epic-fitness-progress.css',__FILE__),false,false);
		wp_enqueue_style('fitness-progress');
	}

	public function add_js(){
		wp_register_script('cropit',plugins_url('/js/jquery.cropit.js',__FILE__),false,false);
		wp_enqueue_script('cropit');
	}
		
	public static function image_box($i){
		echo '<div class="upload-col">';
		echo '<div class="image-editor" id="image-editor'.$i.'">';
		echo '<span class=" spn1 dspan"></span><span class=" spn2 dspan"></span><span class=" spn3 dspan"></span>';
		echo '<input type="file" class="cropit-image-input">';
		echo '<div class="cropit-preview"></div>';
		echo '<input type="range" class="cropit-image-zoom-input">';
		echo '<input type="hidden" name="image-data'.$i.'" class="img'.$i.'">';
		echo '</div>';
		echo '<a href="javascript:void(0)" class="btn-save import-image'.$i.' btn-valid">Crop Image</a>';
		echo '<textarea name="cmt'.$i.'" rows="2" cols="20" class="cmtimg" id="cmt'.$i.'" placeholder="Comments" require maxlength="200">';
		echo '</textarea>';
		echo '</div>';
	}
	function add_new_cpi(){
		ob_start();
		if(isset($_POST['submit'])){
			$title		=	$_POST['date'];
			$uid		=	$_POST['client_email'];
			$data1		=	$_POST['image-data1'];
			$data2		=	$_POST['image-data2'];
			$data3		=	$_POST['image-data3'];
			$cmt1		=	$_POST['cmt1'];
			$cmt2		=	$_POST['cmt2'];
			$cmt3		=	$_POST['cmt3'];
			$pid	=	wp_insert_post(
							array(
								'post_title'	=>	$title,
								'post_status'	=>	'publish',
								'post_type'		=>	'cpi'));
			$updateig1	=	update_post_meta($pid,'img1',CF_Client_Progress::upload_base_img($data1,$pid));
			$updateig2	=	update_post_meta($pid,'img2',CF_Client_Progress::upload_base_img($data2,$pid));
			$updateig3	=	update_post_meta($pid,'img3',CF_Client_Progress::upload_base_img($data3,$pid));
			update_post_meta($pid,'uid',$uid);
			update_post_meta($pid,'date',$title);
			update_post_meta($pid,'cmt1',$cmt1);
			update_post_meta($pid,'cmt2',$cmt2);
			update_post_meta($pid,'cmt3',$cmt3);
			if($updateig1&&$updateig2&&$updateig3){?><script>alert('Data Saved Successfully');</script> <?php 
				header('location: http://members.epicwinpt.com.au/add-client-progress-images/');
			}
			 else{?><script>alert('Error'); </script><?php
			}
		} 
		else{
			?><style>.client_search{display:none}</style>
			<form method="POST"enctype="multipart/form-data" name="form2" onsubmit="return check(this)">
				<div class="images-upload"><?php
					echo do_shortcode('[client_tracking_search_box]');
					CF_Client_Progress::current_date();
					?>
					<div class="esec_post">
						<?php
						CF_Client_Progress::image_box(1);
						CF_Client_Progress::image_box(2);
						CF_Client_Progress::image_box(3);
						?>
						<input name="submit" value="Submit" class="btn-save" id="save-images" type="submit">
					</div>
					<script>jQuery(document).ready(function(e){(function($){
						$('#image-editor1').cropit();
						$('#image-editor2').cropit();
						$('#image-editor3').cropit();
						
						$('.import-image1').click(function(){
							var imageData1	=	$('#image-editor1').cropit('export');
							if(imageData1){
								$('.import-image1').css('background-color','#51BE4B');
								$('.img1').val(imageData1);								
							}
							else{alert('Please Add an Image To Crop');}
						});
					
						$('.import-image2').click(function(){
							var imageData2	=	$('#image-editor2').cropit('export');
							if(imageData2){
								$('.import-image2').css('background-color','#51BE4B');
								$('.img2').val(imageData2);								
							}
							else{alert('Please Add an Image To Crop');}
						});
						$('.import-image3').click(function(){
							var imageData3	=	$('#image-editor3').cropit('export');
							if(imageData3!=''){
								$('.import-image3').css('background-color','#51BE4B');
								$('.img3').val(imageData3);								
							}
							else{alert('Please Add an Image To Crop');}
						});				
				}(jQuery))});</script>
                <script type="text/javascript">
				function check(form2){
					if(form2.client_email.value==""){
					alert("Please Select USer Name");form2.client_email.focus();return false;}
					if(form2.date.value==""){alert("Please Select Date");form2.date.focus();return false;}					
					if(form2.cmt1.value==""){alert("Please Add Your Commnets");form2.cmt1.focus();return false;}					
					if(form2.cmt2.value==""){alert("Please Add Your Commnets");form2.cmt2.focus();return false;}					
					if(form2.cmt3.value==""){alert("Please Add Your Commnets");form2.cmt3.focus();return false;}					
				}
				</script>
			</div>  <!-- Class images-upload Ends -->
			</form>
        	<?php
		}
		$o=ob_get_contents();
		ob_end_clean();
		return $o;
	}
	public static function current_date(){
		?><script>jQuery(document).ready(function(e){(function($){$( "#datepicker" ).datepicker()}(jQuery))});</script>
		<input type="text" id="datepicker" placeholder="Select Date" name="date">
        <?php
	}
	function append_add_new_cpi($content){
		global $post;
		if($post->post_type=='cpi'){
			return $content.CF_Client_Progress::add_new_cpi();
		}
		else{
			return $content;
		}
	}
	public function upload_base_img($img,$pid){		
		$upload_dir       	=	wp_upload_dir();
		$upload_path      	=	str_replace('/',DIRECTORY_SEPARATOR,$upload_dir['path']).DIRECTORY_SEPARATOR;
		$img				=	str_replace('data:image/png;base64,','',$img);
		$img				=	str_replace(' ','+',$img);
		$decoded          	=	base64_decode($img) ;
		$filename         	=	'p.jpg';
		$hashed_filename  	=	md5($filename.microtime()).'_'.$filename;
		$image_upload     	=	file_put_contents($upload_path.$hashed_filename,$decoded);
		if(!function_exists('wp_handle_sideload')){
			require_once(ABSPATH.'wp-admin/includes/file.php');
		}
		if(!function_exists( 'wp_get_current_user' ) ) {
			require_once(ABSPATH.'wp-includes/pluggable.php' );
		}
		$file             	=	array();
		$file['error']    	=	'';
		$file['tmp_name'] 	=	$upload_path.$hashed_filename;
		$file['name']     	=	$hashed_filename;
		$file['type']     	=	'image/jpg';
		$file['size']     	=	filesize($upload_path.$hashed_filename);
		$file_return      	=	wp_handle_sideload( $file, array( 'test_form' => false ) );
		$filename			=	$file_return['file'];
		$attachment = array(
			 'post_mime_type'	=>	$file_return['type'],
			 'post_title'		=>	preg_replace('/\.[^.]+$/', '', basename($filename)),
			 'post_content'		=>	'',
			 'post_status'		=>	'inherit',
			 'guid'				=>	$wp_upload_dir['url'] . '/' . basename($filename)
		);
		$attach_id	=	wp_insert_attachment( $attachment, $filename, $pid);
		if($attach_id){
			return	$attach_id;
		}
		else{
			return 'Not Uploaded';
		}
	}
}