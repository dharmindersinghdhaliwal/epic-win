<?php
##------------------------
#  Creating BMR widget  |
##------------------------
class BMR extends WP_Widget {
	function __construct(){
		parent::__construct(
		'BMR',
		__('BMR', 'BMR_domain'),
		// Widget description
		array( 'description' => __( 'Widget For Menu Developed By Codeflox', 'BMR_domain' ), )
		);
		add_shortcode('BMR',array($this,'widget'));
	}
	# Creating widget front-end
	public function widget( $args, $instance ) {
		//$title = apply_filters( 'widget_title', $instance['title'] );
		//echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		?>
        <script>
		function calc (form) {
			var W, H, S
			W=form.Weight.value/form.WeightUnit.options[form.WeightUnit.selectedIndex].value
			H=form.Height.value*form.HeightUnit.options[form.HeightUnit.selectedIndex].value
			S=form.Sex.options[form.Sex.selectedIndex].value
			L= W - W*form.Height.value/100
			if (H==0) {form.BMR.value = Math.round(370 + 21.6*L)}
			if (H>0 && S=="Male"){form.BMR.value = Math.round(66.473 + 13.751*W + 5.0033*H - 6.755*form.Age.value)}
			if (H>0 && S=="Female"){form.BMR.value = Math.round(655.0955 + 9.463*W + 1.8496*H - 4.6756*form.Age.value)}
			form.TotalActivity.value = 1*form.Resting.value+1*form.VeryLight.value+1*form.Light.value+1*form.Moderate.value+1*form.Heavy.value
			if (form.TotalActivity.value != 24) {
				alert("Sum of activity hours must equal 24 hours. Your sum is " + form.TotalActivity.value)}
				form.TotalCalories.value = Math.round(form.BMR.value/24*(form.Resting.value*1+(form.VeryLight.value*1.5)+(form.Light.value*2.5)+(form.Moderate.value*5)+(form.Heavy.value*7)))
				form.ActivityCalories.value = form.TotalCalories.value-form.BMR.value
				var result = form.TotalCalories.value*4.184
				form.TotalKilojoules.value	=Math.round(result)
		}		
		</script> 
         <h2>DAILY ENERGY REQUIREMENTS</h2>
<h3>BMR CALCULATOR</h3>
        <FORM>           		
		<table class="StackTable" width="100%" cellspacing="0" cellpadding="5" border="1">
          <tbody>
          <tr>
            <td width="33%" valign="TOP">
            <p><select name="Sex">
                <option value="Male" selected="">Male</option>
                <option value="Female">Female</option>
            </select> Sex</p>
            <p><input name="Age" size="5" type="text"> Age</p>
            <p><input name="Weight" size="5" type="text">
            <select name="WeightUnit">
                <option value="2.2" selected="">Pounds</option>
                <option value="1">Kilograms</option>
            </select> Weight</p>
            <p><input name="Height" size="5" type="text">
            <select name="HeightUnit">
                <option value="2.54" selected="">Inches</option>
                <option value="1">Centimeters</option>
                <option value="0">% Body Fat</option>
            </select> </p></td> 
            <td width="33%" valign="TOP">
                <p><input name="Resting" size="5" type="text"> Resting</p>
                <p><input name="VeryLight" size="5" type="text"> Very Light</p>
                <p><input name="Light" size="5" type="text"> Light</p>
                <p><input name="Moderate" size="5" type="text"> Moderate</p>
                <p><input name="Heavy" size="5" type="text"> Heavy</p>
                <p></p><hr align="LEFT"><p></p>
                <p><input name="TotalActivity" size="5" type="text"> Total (24 hrs)</p>
            </td> 
            <td width="34%" valign="TOP">
                <p><input name="BMR" size="5" type="text"> BMR (Calories)</p>
                <p><input name="ActivityCalories" size="5" type="text"> Activity (Calories)</p>
                <p></p><hr align="LEFT"><p></p>
                <p><input name="TotalCalories" size="5" type="text"> Total Calories</p>
                <p><input name="TotalKilojoules" size="5" type="text"> Total Kilojoules</p>
                <p>&nbsp;</p><p>&nbsp;</p>
                <p></p><center><input value="Calculate" onclick="calc(this.form)" type="button"><input value="Reset" name="Reset" type="reset"></center>
            </td>
          </tr>
   		 </tbody>
  		</table>
	</FORM>
<P><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
  	<TR>
        <TD WIDTH="99%" VALIGN="TOP">
        <H3>Directions</H3>
        <BLOCKQUOTE>
          <P>Fill first column with age in years, weight, and height (inches
          or centimeters) or percent body fat (eg: 21.2); select appropriate
          menu options. Fill the number of hours spent on respected activity
          levels considering the example activities below. Decimal values
          are allowed (e.g. 2.5, 0.25). The total must equal 24 hours;
          &quot;Total&quot; is not a required entry. Use weighted average
          values since activity levels probably vary from day to day. Keep
          in mind, most people over estimate their activity level. Click
          &quot;Calculate&quot; when complete.
          </P>              
        </BLOCKQUOTE>
        <P><HR align="left"width="96%"></P>
        <P><B>Resting</B></P>
        <DL>
          <DD>Sleeping, reclining
        </DL>
        <P><B>Very light</B></P>
        <DL>
          <DD>Seated and standing activities, painting trades, driving,
          laboratory work, typing, sewing, ironing, cooking, playing cards,
          playing a musical instrument
        </DL>
        <P><B>Light</B></P>
        <DL>
          <DD>Walking on a level surface at 2.5 to 3 mph, garage work,
          electrical trades, carpentry, restaurant trades, house cleaning,
          child care, golf, sailing, table tennis
        </DL>
        <P><B>Moderate</B></P>
        <DL>
          <DD>Walking 3.5 to 4 mph, weeding and hoeing, carrying a load,
          cycling, skiing, tennis, dancing, weight training including rest
          between sets.
        </DL>
        <P><B>Heavy</B></P>
        <DL>
          <DD>Walking with load uphill, tree felling, heavy manual digging,
          basketball, climbing, football, soccer
        </DL>
        <P><HR align="left"width="96%"></P>
		   <?php
           //echo $args['after_widget'];
	}
	## Widget Backend
	 public function form( $instance ) {
		 if ( isset( $instance[ 'title' ] ) ) {$title = $instance[ 'title' ];}
		 else {$title = __( 'Title of Widget', 'BMR_domain' );}
		 ## Widget admin form
		 ?>
         <p>
         <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
         <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
         </p>
		 <?php
     }
	 // Updating widget replacing old instances with new
	 public function update( $new_instance, $old_instance ){
		 $instance = array();
		 $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		 return $instance;
	}
}
## Register and load the widget
function second_load_widget() {
	register_widget( 'BMR' );
}
add_action( 'widgets_init', 'second_load_widget' );