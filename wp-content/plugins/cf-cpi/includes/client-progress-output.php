<?php
$cf_client_progress=new CF_Client_Progress_Output();
class CF_Client_Progress_Output{
	function init(){
        $class = __CLASS__;
        new $class;
    }
	
	function __construct(){
		add_action('wp_ajax_get_client_progress',array($this,'get_client_progress'));
		add_action('wp_ajax_nopriv_get_client_progress',array($this,'get_client_progress'));
		add_action('wp_ajax_get_client_progress_images_ajax',array($this,'get_client_progress_images_ajax'));
		add_action('wp_ajax_nopriv_get_client_progress_images_ajax',array($this,'get_client_progress_images_ajax'));
		//add_action('wp_head',array($this,'output_js')); //JS added into cropit js
		add_shortcode('display_search_section',array($this,'display_search_section'));			
	}	
	
	public function get_client_progress(){
		if($_REQUEST){
			$uid	=	$_REQUEST['uid'];
			$args = array(
				'orderby'       	=>  'post_date',
				'order'         	=>  'ASC',
				'post_type'			=>	'cpi',
				'posts_per_page'	=> -1
			);
			$posts = get_posts($args);
			$parray	=	array();
			foreach($posts as $post){
				$puid	=	get_post_meta($post->ID,'uid',true);
				if($puid==$uid){
					$pid		=	$post->ID;
					$p_parent	=	$post->post_parent;
					$post_date	=	get_post_meta($pid,'date',true);
					$img1_id		=	get_post_meta($pid,'img1',true);
					$img2_id		=	get_post_meta($pid,'img2',true);
					$img3_id		=	get_post_meta($pid,'img3',true);
					$data	=	array(
						'pid'		=>	$pid,
						'post-date'	=>	$post_date,
						'img1'		=>	$img1,
						'img2'		=>	$img2,
						'img3'		=>	$img3
					);
					array_push($parray,$data);
				}
			}
			echo 	json_encode($parray);
		}
		die();
	}
	
	public function get_client_progress_images_ajax(){
		if($_REQUEST){
			$parray	=	array();
			$pid	=	$_REQUEST['pid'];
			$size	=	array('700', '600');
			$post_date	=	get_post_meta($pid,'date',true);
			$img1_id		=	get_post_meta($pid,'img1',true);
			$img1			=	wp_get_attachment_url($img1_id);
			//$img1			=	wp_get_attachment_image($img1_id,$size);
			
			$img2_id		=	get_post_meta($pid,'img2',true);
			$img2			=	wp_get_attachment_url($img2_id);
			//$img2			=	wp_get_attachment_image($img2_id,$size);
			
			$img3_id		=	get_post_meta($pid,'img3',true);
			$img3			=	wp_get_attachment_url($img3_id);
			//$img3			=	wp_get_attachment_image($img3_id,$size);
			
			$cmt1		=	get_post_meta($pid,'cmt1',true);
			$cmt2		=	get_post_meta($pid,'cmt2',true);
			$cmt3		=	get_post_meta($pid,'cmt3',true);
			$data	=	array(
				'pid'		=>	$pid,
				'post-date'	=>	$post_date,
				'cmt1'		=>	$cmt1,
				'cmt2'		=>	$cmt2,
				'cmt3'		=>	$cmt3,
				'img1'		=>	$img1,
				'img2'		=>	$img2,
				'img3'		=>	$img3
				);
			array_push($parray,$data);
			echo 	json_encode($parray);
		}
		die();
	}
	
	public function display_search_section(){
		ob_start();
		?>
        <style>
		.client_search{display:none}  /*Default Search Button */
		</style>
        <div class="cpi">
        <?php
		echo   do_shortcode('[client_tracking_search_box]');		
		echo '<a href="javascript:void(0)" class="progress_users btn-save"> Search Users</a>';
		$uploads = wp_upload_dir();
		$upload_path = $uploads['baseurl'].'/';
		echo '<input type="hidden" value="'.$upload_path.'" id="path">';
		?>
        </div>
		<?php
		echo '<div class="p_content">';
		echo '<div class="dates"><ul class="p_dates"></ul> </div>';
		echo '<div class="p_images"></div>';
		echo '</div>';		
		$o=ob_get_contents();
		ob_end_clean();
		return $o;
	}
	
	public function output_js(){
		?>        
        <script>
		jQuery(document).ready(function(e){(function($){
			$('.progress_users').click(function(){
				var uid	=	$('#client_email').val();
				$.ajax({
					type:"POST",
					url:wp_ajax_url(),
					data:{'action':'get_client_progress','uid':uid},
					success:function(data){
						if(data){
							$('.p_dates').empty();
							var parsed	=	JSON.parse(data);
							var arr		=	[];
							var arr		=	Object.keys(parsed).map(function(k) { return parsed[k] });
                            $('.p_dates').append('<h4 class="cpihead"> Photo Dates</h4>');
							for(i=0;i<arr.length;i++){
								pid	=	arr[i]['pid'];
								pdate	=	arr[i]['post-date'];
								$('.p_dates').append('<li><a href="javascript:void(0)" class="imgs" pid="'+pid+'">'+pdate+'</a><a href="javascript:void(0)" class="imga" >1</a><a href="javascript:void(0)" class="imgb">2</a><a href="javascript:void(0)" class="imgc">3</a></li>');
								
								// Display data of First li on page load
								if(i==0){
									$('.p_dates li').addClass('Clicked');
									$.ajax({
										type:"POST",
										url:wp_ajax_url(),
										data:{'action':'get_client_progress_images_ajax','pid':pid},
										beforeSend: function(){	swal({title:"",text:"Wait",type:"info",showConfirmButton:false})},
										success:function(data){
											swal.close();
											if(data){
												console.log(data);
												$('.p_images').empty();
												var parsed	=	JSON.parse(data);
												var arr		=	[];
												var arr		=	Object.keys(parsed).map(function(k) { return parsed[k] });											
												for(i=0;i<arr.length;i++){
													pdate	=	arr[i]['post-date'];
													cmt1	=	arr[i]['cmt1'];
													cmt2	=	arr[i]['cmt2'];
													cmt3	=	arr[i]['cmt3'];
													img1	=	arr[i]['img1'];
													img2	=	arr[i]['img2'];
													img3	=	arr[i]['img3'];
								
													$('.p_images').append('<div class="img1"><img src="'+img1+'"><div class="cmt"><span>'+pdate+'</span><p>'+cmt1+'</p></div></div><div class="img2"><img src="'+img2+'"><div class="cmt"><span>'+pdate+'</span><p>'+cmt2+'</p></div></div><div class="img3"><img src="'+img3+'"><div class="cmt"><span>'+pdate+'</span><p>'+cmt3+'</p></div></div>');
												}
											}
										}
									});
								}
							}
						}
						else{
							console.log('Error or no Post for this user');
						}
					}
				});
			});
			
			//  Click on date  Display Images
			$(document).on('click','.imgs',function(){
				pid	=	$(this).attr("pid");
				$('ul li').removeClass('Clicked');
				$(this).closest('li').addClass('Clicked');
				$.ajax({
					type:"POST",
					url:wp_ajax_url(),
					data:{'action':'get_client_progress_images_ajax','pid':pid},
					beforeSend: function(){	swal({title:"",text:"Wait",type:"info",showConfirmButton:false, customClass: 'swal-wide'})},
					success:function(data){
						swal.close();
						if(data){
							console.log(data);
							$('.p_images').empty();
							var parsed	=	JSON.parse(data);
							var arr		=	[];
							var arr		=	Object.keys(parsed).map(function(k) { return parsed[k] });												
							for(i=0;i<arr.length;i++){
							pdate	=	arr[i]['post-date'];
							cmt1	=	arr[i]['cmt1'];
							cmt2	=	arr[i]['cmt2'];
							cmt3	=	arr[i]['cmt3'];
							img1	=	arr[i]['img1'];
							img2	=	arr[i]['img2'];
							img3	=	arr[i]['img3'];
							$('.p_images').append('<div class="img1"><img src="'+img1+'"><div class="cmt"><span>'+pdate+'</span><p>'+cmt1+'</p></div></div><div class="img2"><img src="'+img2+'"><div class="cmt"><span>'+pdate+'</span><p>'+cmt2+'</p></div></div><div class="img3"><img src="'+img3+'"><div class="cmt"><span>'+pdate+'</span><p>'+cmt3+'</p></div></div>');
						}
					}
				}
			});
		});
		
		$( document).on('click','.Clicked .imga',function(){
			$('.imgb').removeClass('showimg');
			$('.imgc').removeClass('showimg');
			$(this).addClass('showimg');
			$('.img1').show();
			$('.img2').hide();
			$('.img3').hide();
		});
		$(document).on('click','.Clicked .imgb',function(){		
			$('.imgc').removeClass('showimg');
			$('.imga').removeClass('showimg');
			$(this).addClass('showimg');
			$('.img2').show();
			$('.img1').hide();
			$('.img3').hide();
		});
		$(document).on('click','.Clicked .imgc',function(){
			$('.imgb').removeClass('showimg');
			$('.imga').removeClass('showimg');
			$(this).addClass('showimg');
			$('.img3').show();
			$('.img1').hide();
			$('.img2').hide();
		});
				
	}(jQuery))});
		</script>
        <?php
	}
}