<?php
##--------------------------------------------------
#   Creating 1 Repetition Max Calculator  widget  	|
##--------------------------------------------------
	class RMC extends WP_Widget {
		function __construct() {
			parent::__construct(
			'RMC',
			__('RMC', 'RMC_domain'),
			array( 'description' => __( 'Widget For Menu Developed By Codeflox', 'RMC_domain' ), )
			);
			add_shortcode('RMC',array($this,'widget'));
		}
		# Creating widget front-end
		public function widget( $args, $instance ) {
			//$title = apply_filters( 'widget_title', $instance['title'] );
			//echo $args['before_widget'];
			if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			?>			
			<script type="text/javascript">
			function getId(id){	return document.getElementById(id);}			
			function Show1rmResults(){
				var weight_lifted	=	getId('weight_lifted').value;
				var no_of_reps		=	getId('no_of_reps').value;
				if(weight_lifted==""){alert("Please enter weight lifted.");getId('weight_lifted').focus();return false;}
				if(isNaN(weight_lifted)==true){alert("Weight lifted must be numeric");getId('weight_lifted').focus();return false;}
				if(no_of_reps==''){alert("Please enter no of reps.");getId('no_of_reps').focus();return false;}
				if(isNaN(no_of_reps)==true){alert("No of reps must be numeric");getId('no_of_reps').focus();return false;}				
				var result = parseInt( Math.ceil(( (weight_lifted * 0.03) * no_of_reps)) );
				result	=	result + parseInt( weight_lifted );
				document.getElementById("rep_max_results").setAttribute("value", result);
				document.getElementById("T").setAttribute("value", result);
				var result95 	=	(result*95)/100;
				document.getElementById("S").setAttribute("value", result95);
				var result93	=	(result*93)/100;
				document.getElementById("R").setAttribute("value", result93);
				var result85	=	(result*85)/100;
				document.getElementById("Q").setAttribute("value", result85);
				var result87	=	(result*87)/100;
				document.getElementById("P").setAttribute("value", result87);
				var result85	=	(result*85)/100;
				document.getElementById("O").setAttribute("value", result85);
				var result83	=	(result*83)/100;
				document.getElementById("N").setAttribute("value", result83);
				var result80	=	(result*80)/100;
				document.getElementById("M").setAttribute("value", result80);
				var result77	=	(result*77)/100;
				document.getElementById("L").setAttribute("value", result77);
				var result75	=	(result*75)/100;
				document.getElementById("K").setAttribute("value", result75);
				var result73	=	(result*73)/100;
				document.getElementById("J").setAttribute("value", result73);
				var result70	=	(result*70)/100;
				document.getElementById("I").setAttribute("value", result70);
				var result67	=	(result*67)/100;
				document.getElementById("H").setAttribute("value", result67);
				var result65 	=	(result*65)/100;
				document.getElementById("G").setAttribute("value", result65);
				var result63	=	(result*63)/100;
				document.getElementById("F").setAttribute("value", result63);
				var result60	=	(result*60)/100;
				document.getElementById("E").setAttribute("value", result60);
				var result57	=	(result*57)/100;
				document.getElementById("D").setAttribute("value", result57);
				var result55	=	(result*55)/100;
				document.getElementById("C").setAttribute("value", result55);
				var result53	=	(result*53)/100;
				document.getElementById("B").setAttribute("value", result53);
				var result50	=	(result*50)/100;
				document.getElementById("A").setAttribute("value", result50);
			}			
			function reset1rmForm(){
				getId('weight_lifted').value	=	'';
				getId('no_of_reps').value		=	'';
				getId('rep_max_results').innerHTML	=	'0';
				getId('1rm_stats').innerHTML		=	'';
			}
			</script>
          <h2>ONE REP MAX (1RM) CALCULATOR</h2>
<FORM>
<P><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">  
</TABLE></P>
<P><TABLE WIDTH="100%" BORDER="1" CELLSPACING="0" CELLPADDING="5"
CLASS="StackTable">
  <TR>
    <TD ROWSPAN="2">
    <P><INPUT NAME="weight_lifted" TYPE="text" SIZE="5" id="weight_lifted"> Weight Lifted</P>
    <P><INPUT NAME="no_of_reps" TYPE="text" SIZE="5" id="no_of_reps"> Reps Performed</P>
    <P><CENTER><input type="button" value="Calculate" ONCLICK="return Show1rmResults();"><INPUT
    TYPE="reset" VALUE="Reset" NAME="Reset"></CENTER></TD>
    <TD COLSPAN="2" ALIGN="CENTER">
    <DL>
      <DT><CENTER><INPUT NAME="OneRepMax" TYPE="text" SIZE="5" id="rep_max_results"> kg
          </div><br>One-rep
      max</CENTER>
    </DL>
</TD>
  </TR>
  <TR>
    <TD WIDTH="33%" HEIGHT="112" VALIGN="TOP">
    <DL>
      <DT>20 REPS At 50% <INPUT NAME="A" TYPE="text" SIZE="5" id="A"> 
      <DT>19 REPS At 53% <INPUT NAME="B" TYPE="text" SIZE="5" id="B">
      <DT>18 REPS At 55% <INPUT NAME="C" TYPE="text" SIZE="5" id="C">
      <DT>17 REPS At 57% <INPUT NAME="D" TYPE="text" SIZE="5" id="D">
      <DT>16 REPS At 60% <INPUT NAME="E" TYPE="text" SIZE="5" id="E">
      <DT>15 REPS At 63% <INPUT NAME="F" TYPE="text" SIZE="5" id="F">
      <DT>14 REPS At 65% <INPUT NAME="G" TYPE="text" SIZE="5" id="G">
      <DT>13 REPS At 67% <INPUT NAME="H" TYPE="text" SIZE="5" id="H"> 
      <DT>12 REPS At 60% <INPUT NAME="I" TYPE="text" SIZE="5" id="I">
      <DT>11 REPS At 73% <INPUT NAME="J" TYPE="text" SIZE="5" id="J"> 
    </DL>
</TD>
    <TD WIDTH="34%" VALIGN="TOP">
    <DL>
      <DT>10 REPS At 75% <INPUT NAME="K" TYPE="text" SIZE="5" id="K">
      <DT>9 REPS At 77%  <INPUT NAME="L" TYPE="text" SIZE="5" id="L"> 
      <DT>8 REPS At 80%  <INPUT NAME="M" TYPE="text" SIZE="5" id="M"> 
      <DT>7 REPS At 83%  <INPUT NAME="N" TYPE="text" SIZE="5" id="N">
      <DT>6 REPS At 85%  <INPUT NAME="O" TYPE="text" SIZE="5" id="O"> 
      <DT>5 REPS At 87%  <INPUT NAME="P" TYPE="text" SIZE="5" id="P"> 
      <DT>4 REPS At 90%  <INPUT NAME="Q" TYPE="text" SIZE="5" id="Q"> 
      <DT>3 REPS At 93%  <INPUT NAME="R" TYPE="text" SIZE="5" id="R"> 
      <DT>2 REPS At 95%  <INPUT NAME="S" TYPE="text" SIZE="5" id="S"> 
      <DT>1 REP At 100%  <INPUT NAME="T" TYPE="text" SIZE="5" id="T"> 
    </DL>
</TD>
  </TR>
</TABLE></P>
          <?php
          // echo $args['after_widget'];
		 }
		 ## Widget Backend
		 public function form( $instance ) {
			 if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ];}
			else {$title = __( 'Title of Widget', 'RMC_domain' );}
			## Widget admin form
			?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
			<?php
          }
		  ## Updating widget replacing old instances with new
		  public function update( $new_instance, $old_instance ){
			  $instance = array();
			  $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title']) :'';
			  return $instance;
		  }
	}
## Register and load the widget
 function wpb_load_widget() {
	 register_widget( 'RMC' );
 }
add_action( 'widgets_init', 'wpb_load_widget' );