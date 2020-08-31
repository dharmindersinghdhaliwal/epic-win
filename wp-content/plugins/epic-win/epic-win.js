jQuery(document).ready(function(e){(function($){
	$(".ettvaluse li:nth-child(3)").addClass('weight');

	$(".add_col").click(function(){
		trow	=	$('.ettvaluse .firstli input').length;
		var i=trow-1;
		i++;
		$('.ettvaluse .mtrack').each(function(index, element) {
			var hl=$(this).attr('lvlid');
			$(this).append('<input name="comninpt[]" type="text"class="inptxt'+i+' comninpt added "value="" lvlid="'+hl+'">');
		});
		$('.weight input').addClass('reqrd');
		$('.ettvaluse .prntli:first-child').addClass('dateli');
		$('.dateli input').last().addClass('datepicker');
		$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' }).val();
		$('.datepicker').addClass('date-input');
		$('.undo').show(200);
		swal({title:"",text:"New column added!",type:"success"});
	});

	$('.undo').click(function(e){
		$('.gettotal').hide();
		$('.body_fat').hide();
		trow		=	$('.ettvaluse .firstli input').length;
		ctrow		=	trow-1;
		sumrow 	=	('ettvaluse li').length;
		$('.ettvaluse li').each(function(index, element) {
			$(this).children('.inptxt'+ctrow).remove();
		});
		swal({title:"",text:"Column  Deleted ",type:"success"});
	});

	$('.epusr_track_add').click(function(){
		$('.ettcont input').val('');
	});

	$(".epusr_track_edit,.epusr_track_add").click(function(){

		swal({title:"",text:"Go ahead to customize the fields!",type:"success"});

			$('.ettvaluse .prntli:first-child').addClass('dateli');
			$('.dateli input').addClass('datepicker').addClass('date-input');
			$(".rdonly").attr("readonly",false);

	});

	$(".ettouter").on('click','.epusr_track_update',function(){
		var serch_val=$('#search_user').val();
		if(serch_val==""){
			alert('Please Select user');
		}
		else{
			var weight_valu;
			$('.reqrd').each(function(index, element) {
				weight_valu =$(this).val();
			});
			if(weight_valu==""){
				$('.reqrd').css('border','1px solid red ');
				$('.reqrd').focus();
			}
			else{
				tarr = new Array();
				var uid	= $('#client_email').val();
				$('.mtrack').each(function(index){
					invl	=$(this).attr('lvlid');
					apnddarr = new Array();
					$(this).children("input").each(function(index, element) {
						apnddarr.push($(this).val());
					});
					tarr.push(Array(invl,apnddarr));
				});
				jQuery.ajax({
					type:'POST',
					url:wp_ajax_url(),
					data:{'action':'epic_insert_update_tracking_ajax','act':1,'uid':uid,'tarr':tarr},
					beforeSend: function(){
						$('#loadingm').show();
					},
					success:function(data){
						if(data){
							if(data==1){
								$('#loadingm h2').html('Success !');
								$('#loadingm .modal-body').html('<img src="/wp-content/themes/maxx-fitness-child-theme/x2_jump.gif">');
								$('#loadingm').delay(5000).hide(0, function(){
									$('#loadingm h2').html('Loading....');
									$('#loadingm .modal-body').html('<img src="/wp-content/themes/maxx-fitness-child-theme/x2_writing.gif">');
								});
								location.reload();
							}
							else{
								alert('Unable to update user data');
							}
						}
					}
				})
			}
		}
	});

	$('.client_search').click(function(){

		$('.comninpt').remove();
		$('.gettotal').remove();
		$('.body_fat').remove();
		var uid	= $('#client_email').val();
		var jsonarr=new Array();
		jQuery.ajax({
			type:'POST',url:wp_ajax_url(),data:{'action':'epic_insert_update_tracking_ajax','act':2,'uid':uid},
			beforeSend: function(){
				$('#loadingm').show();
			},
			success:function(data){
				$('#loadingm h2').html('Success !');
				$('#loadingm .modal-body').html('<img src="/wp-content/themes/maxx-fitness-child-theme/x2_jump.gif">');
				$('#loadingm').delay(5000).hide(0, function(){
					$('#loadingm h2').html('Loading....');
					$('#loadingm .modal-body').html('<img src="/wp-content/themes/maxx-fitness-child-theme/x2_writing.gif">');
				});
				if(data){
					var parsed	=	JSON.parse(data);
					var arr		=	[];
					var total		=	new Array();
					var arr		=	Object.keys(parsed).map(function(k) { return parsed[k] });
					for(i=0;i<arr.length;i++){
						tmid	=	arr[i][0];
						narr	=	arr[i][1];
						var total_data = new Array();
						for(j=0;j<narr.length;j++){
							$('.ettvaluse li').each(function(index, element){
								var col_number = j+1;
								if($(this).attr('lvlid')==tmid){
									$(this).append('<input name="comninpt[]" type="text" count="'+j+'"class="inptxt comninpt rdonly inptxt'+j+'" readonly="readonly"lvlid="'+tmid+'" value="'+narr[j]+'">');
								}
							});
						}
					}
					var wgt_lngth	=	$('.weight input').length;
					k=0;
					$('.weight input').each(function(index, element) {
						$(this).addClass('wegt_count').addClass('wegt_count'+k);
						k++;
					});
					var trow	=	$('.ettvaluse .firstli input').length;
					var tprt	=	$('.tprt').val();
					var pJSON	=	$('.pJSON').val();
					var p21	=	JSON.parse(pJSON);
					for(m=0;m<p21.length;m++){
						var pid	=	p21[m];
						for(n=1;n<trow;n++){
							total=$('.mtprt'+pid+' .inptxt'+n).map(function(){return $(this).val(); }).get();
							var get_totl=sum(total);
							$('.mttotal'+pid).append('<input type="text" class="sum'+n+' gettotal skin_total_count'+m+'" readonly="readonly" value="'+get_totl.toFixed(2)+'" style=" background-color: #fff;text-align: center;">');
						}
					}
					var skin_totl;
					var i=1;

					$('.skin_total_count2').each(function(index, element) {
						var skin_folds_total		 =	$(this).val();
						var weight							 =	$('.wegt_count'+i).val();
						var age									 =	$('#client_age').val();
  					var gender							 =	$('#client_gender').val();
						var skin_folds_sqr_total = parseFloat(squared(skin_folds_total));
						if(gender=='Male'){
							var Db = 1.112 - (.00043499 * skin_folds_total) + (.00000055 * skin_folds_sqr_total) - (.00028826 * parseInt(age));
						}
						else if(gender=='Female') {
							var Db = 1.097 - (.00046971 * skin_folds_total) + (.00000056 * skin_folds_sqr_total) - (.00012828 * parseInt(age));
						}
						var body_fat_per		=	parseFloat(((4.95/Db) - 4.5) * 100);
						var total_body_fat	=	parseFloat((body_fat_per/100) * weight);
						var lean_body_mass	=	parseFloat(weight-total_body_fat);
						$('.body-fat').append('<input type="text"readonly="readonly" value="'+body_fat_per.toFixed(2)+'" class="body_fat">');
						$('.body-mass').append('<input type="text"readonly="readonly" value="'+lean_body_mass.toFixed(2)+'"class="body_fat">');
						i++;
					});

				}
					$('.add_col,.undo,.epusr_track_update,.epusr_track_edit').show();
			}
		});
	});

	$('ul.ettvaluse').each(function(i,e){
		$('li .inptxt:first',e).addClass('current');
	});

	function getValues(selector){
		var tempValues = {};
		$(selector).each(function(){
			var th	=	$(this);
			tempValues[th.attr('title')] = th.val();
		});
		return tempValues;
	}

	function squared(num){
		return num*num;
	}

	$('.client_search').click(function(){
		var uid=$('#client_email').val();
		jQuery.ajax({
			type:'POST',url:wp_ajax_url(),data:{'action':'epic_get_user_img_ajax','act':3,'uid':uid},
			success:function(data){
				$('.pro_img').show().empty().prepend(data);
			}
		});
	});

	function sum(input){
		if (toString.call(input) !== "[object Array]")
			return false;
			var total =  0;
			for(var i=0;i<input.length;i++){
				if(isNaN(input[i])){
					continue
				}
				total += Number(input[i]);
			}
		return total;
	}

		/*--	Scroll Bar Starts	--*/
	$(window).on("load",function(){
		$.mCustomScrollbar.defaults.theme="light-2";
		$(".demo-x").mCustomScrollbar({
			axis:"x",
			advanced:{
				autoExpandHorizontalScroll:true,
				autoScrollOnFocus:true
				}
		});
	});

	$(".ettouter .dslc-module-front").removeAttr('id');
		/*--	Scroll Bar Ends		--*/

	$(document).ready(function(){
		$("#menu-item-342 a").mouseover(function(){
			$(".dslc-text-module-content").css("z-index", "0");
		});
		$("#menu-item-342 a").mouseout(function(){
			$(".dslc-text-module-content").css("z-index", "2");
		});
	});

}(jQuery))});
