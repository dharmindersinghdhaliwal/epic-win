<?php
/*
  Plugin Name: MaxxFitness Schedule widget
  Plugin URI: http://themeforest.net/user/torbara/?ref=torbara
  Description: MaxxFitness Schedule widget
  Author: Torbara
  Version: 3.0.0
  Author URI: http://themeforest.net/user/torbara/portfolio/?ref=torbara
 */

class MaxxFitnessSchedule extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'MaxxFitnessSchedule', 'description' => 'Displays a Class Scheduler');
        parent::__construct('MaxxFitnessSchedule', 'MaxxFitness Schedule', $widget_ops);
    }

    function form($instance) { ?>

        <?php $title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : "MaxxFitness Schedule"; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                Title: 
                <input class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                        type="text" 
                        value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        
        <?php $Monday = isset( $instance['Monday'] ) ? $instance['Monday'] : ""; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Monday')); ?>">
                Monday:
                <textarea class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('Monday')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('Monday')); ?>"><?php echo esc_html($Monday); ?></textarea>
            </label>
        </p>
        
        <?php $Tuesday = isset( $instance['Tuesday'] ) ? $instance['Tuesday'] : ""; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Tuesday')); ?>">
                Tuesday:
                <textarea class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('Tuesday')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('Tuesday')); ?>"><?php echo esc_html($Tuesday); ?></textarea>
            </label>
        </p>
        
        <?php $Wednesday = isset( $instance['Wednesday'] ) ? $instance['Wednesday'] : ""; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Wednesday')); ?>">
                Wednesday:
                <textarea class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('Wednesday')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('Wednesday')); ?>"><?php echo esc_html($Wednesday); ?></textarea>
            </label>
        </p>
        
        <?php $Thursday = isset( $instance['Thursday'] ) ? $instance['Thursday'] : ""; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Thursday')); ?>">
                Thursday:
                <textarea class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('Thursday')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('Thursday')); ?>"><?php echo esc_html($Thursday); ?></textarea>
            </label>
        </p>
        
        <?php $Friday = isset( $instance['Friday'] ) ? $instance['Friday'] : ""; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Friday')); ?>">
                Friday:
                <textarea class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('Friday')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('Friday')); ?>"><?php echo esc_html($Friday); ?></textarea>
            </label>
        </p>
        
        <?php $Saturday = isset( $instance['Saturday'] ) ? $instance['Saturday'] : ""; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Saturday')); ?>">
                Saturday:
                <textarea class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('Saturday')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('Saturday')); ?>"><?php echo esc_html($Saturday); ?></textarea>
            </label>
        </p>
        
        <?php $Sunday = isset( $instance['Sunday'] ) ? $instance['Sunday'] : ""; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Sunday')); ?>">
                Sunday:
                <textarea class="widefat" 
                        id="<?php echo esc_attr($this->get_field_id('Sunday')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('Sunday')); ?>"><?php echo esc_html($Sunday); ?></textarea>
            </label>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title']      = $new_instance['title'];
        $instance['Monday']     = $new_instance['Monday'];
        $instance['Tuesday']    = $new_instance['Tuesday'];
        $instance['Wednesday']  = $new_instance['Wednesday'];
        $instance['Thursday']   = $new_instance['Thursday'];
        $instance['Friday']     = $new_instance['Friday'];
        $instance['Saturday']   = $new_instance['Saturday'];
        $instance['Sunday']     = $new_instance['Sunday'];
        
        
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        
        $monday     = $this->DayObjPrepare($instance['Monday']);
        $tuesday    = $this->DayObjPrepare($instance['Tuesday']);
        $wednesday  = $this->DayObjPrepare($instance['Wednesday']);
        $thursday   = $this->DayObjPrepare($instance['Thursday']);
        $friday     = $this->DayObjPrepare($instance['Friday']);
        $saturday   = $this->DayObjPrepare($instance['Saturday']);
        $sunday     = $this->DayObjPrepare($instance['Sunday']);?>
        
        <div class="uk-position-relative">
            <div class="akmf-scroll">
                    <div class="akmf-header-placeholder gblue">&nbsp;</div>
                    <div class="akmf-dayname">
                        <h3 class="uk-margin-remove gwhite">Monday</h3>
                    </div>
                    <div class="akmf-dayname">
                        <h3 class="uk-margin-remove ggrey">Tuesday</h3>
                    </div>
                    <div class="akmf-dayname">
                        <h3 class="uk-margin-remove gwhite">Wednesday</h3>
                    </div>
                    <div class="akmf-dayname">
                        <h3 class="uk-margin-remove ggrey">Thursday</h3>
                    </div>
                    <div class="akmf-dayname">
                        <h3 class="uk-margin-remove gwhite">Friday</h3>
                    </div>
                    <div class="akmf-dayname">
                        <h3 class="uk-margin-remove ggrey">Saturday</h3>
                    </div>
                    <div class="akmf-dayname">
                        <h3 class="uk-margin-remove gwhite" style="padding-bottom: 16px;">Sunday</h3>
                    </div>    
                </div>
            <div class="akmf-box">
                <div class="akmf">
                    <div class="akmf-header uk-vertical-align">
                        <div class="placeholder">&nbsp;</div>
                        <div class="uk-vertical-align-bottom"><span class="hour">00:00</span><span class="half">00:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">01:00</span><span class="half">01:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">02:00</span><span class="half">02:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">03:00</span><span class="half">03:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">04:00</span><span class="half">04:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">05:00</span><span class="half">05:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">06:00</span><span class="half">06:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">07:00</span><span class="half">07:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">08:00</span><span class="half">08:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">09:00</span><span class="half">09:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">10:00</span><span class="half">10:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">11:00</span><span class="half">11:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">12:00</span><span class="half">12:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">13:00</span><span class="half">13:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">14:00</span><span class="half">14:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">15:00</span><span class="half">15:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">16:00</span><span class="half">16:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">17:00</span><span class="half">17:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">18:00</span><span class="half">18:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">19:00</span><span class="half">19:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">20:00</span><span class="half">20:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">21:00</span><span class="half">21:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">22:00</span><span class="half">22:30</span></div>
                        <div class="uk-vertical-align-bottom"><span class="hour">23:00</span><span class="half">23:30</span></div>
                    </div>

                    <div class="akmf-row">
                        <div class="akmf-dayname">&nbsp;</div>
                        <div class="akmf-day akmf-monday">
                            <?php echo ''.$this->DayObjDisplay($monday); ?>
                        </div>
                    </div>

                    <div class="akmf-row">
                        <div class="akmf-dayname">&nbsp;</div>
                        <div class="akmf-day akmf-tuesday">
                            <?php echo ''.$this->DayObjDisplay($tuesday); ?>
                        </div>
                    </div>    

                    <div class="akmf-row">
                        <div class="akmf-dayname">&nbsp;</div>
                        <div class="akmf-day akmf-wednesday">
                            <?php echo ''.$this->DayObjDisplay($wednesday); ?>
                        </div>
                    </div>    

                    <div class="akmf-row">
                        <div class="akmf-dayname">&nbsp;</div>
                        <div class="akmf-day akmf-thursday">
                            <?php echo ''.$this->DayObjDisplay($thursday); ?>
                        </div>
                    </div>

                    <div class="akmf-row">
                        <div class="akmf-dayname">&nbsp;</div>
                        <div class="akmf-day akmf-friday">
                            <?php echo ''.$this->DayObjDisplay($friday); ?>
                        </div>
                    </div>

                    <div class="akmf-row">
                        <div class="akmf-dayname">&nbsp;</div>
                        <div class="akmf-day akmf-saturday">
                            <?php echo ''.$this->DayObjDisplay($saturday); ?>
                        </div>
                    </div>    

                    <div class="akmf-row">
                        <div class="akmf-dayname">&nbsp;</div>
                        <div class="akmf-day akmf-sunday">
                            <?php echo ''.$this->DayObjDisplay($sunday); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>        
        
        
        <?php echo $after_widget;
    }
    
    
    /**
    * Prepare Day objects from params
    * 
    * @param string $day param from module
    * @return array
    */
   public function DayObjPrepare ($day) {
        $text = trim($day);
        $textAr = explode("\n", $text);
        $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind

        $arr = array();
        foreach ($textAr as $line) {
            $class = explode("|", $line);
            $res = new stdClass();
            list($res->starttime, $res->endtime) = explode("-", $class[0]);
            $res->name=$class[1];
            $res->url= @$class[2];
            $arr[]=$res;
        }
        return $arr;
    }

    /**
    * Display Day object
    * 
    * @param string $day prepared Day objects from params
    * @return string HTML output
    */
    public function DayObjDisplay ($day){
        $res = "";
        foreach ($day as $class){
            if($class->url){
                $res .= '<div class="akmf-class start-time-'.str_replace(":", "-", $class->starttime).' end-time-'.str_replace(":", "-", $class->endtime).'"><a href="'.esc_url($class->url).'">'.esc_html($class->name).'</a></div>';
            }else{
                $res .= '<div class="akmf-class start-time-'.str_replace(":", "-", $class->starttime).' end-time-'.str_replace(":", "-", $class->endtime).'">'.esc_html($class->name).'</div>';
            }

        }
        return $res;
    }
    
    
    

}

// Add maxx-fitness-schedule Styles & Scripts
add_action('wp_print_styles', 'add_mfschedule_assets');
function add_mfschedule_assets() {
    
    //Style.css
    $StyleUrl = WP_PLUGIN_URL . '/maxx-fitness-schedule/assets/css/style.css';
    $StyleFile = WP_PLUGIN_DIR . '/maxx-fitness-schedule/assets/css/style.css';
    if ( file_exists($StyleFile) ) {
        wp_register_style('mfsstyle', $StyleUrl);
        wp_enqueue_style('mfsstyle');
    }
    
    //jquery.jscrollpane.css
    $StyleUrl = WP_PLUGIN_URL . '/maxx-fitness-schedule/assets/css/jquery.jscrollpane.css';
    $StyleFile = WP_PLUGIN_DIR . '/maxx-fitness-schedule/assets/css/jquery.jscrollpane.css';
    if ( file_exists($StyleFile) ) {
        wp_register_style('mfsjscrollpane', $StyleUrl);
        wp_enqueue_style('mfsjscrollpane');
    }
    
    //jquery
    wp_enqueue_script('jquery');
    
    //jquery.mousewheel.js
    $ScriptUrl = WP_PLUGIN_URL . '/maxx-fitness-schedule/assets/js/jquery.mousewheel.js';
    $ScriptFile = WP_PLUGIN_DIR . '/maxx-fitness-schedule/assets/js/jquery.mousewheel.js';
    if ( file_exists($ScriptFile) ) {
        wp_register_script('mfsmousewheel', $ScriptUrl);
        wp_enqueue_script('mfsmousewheel');
    }
    
    //jquery.jscrollpane.min.js
    $ScriptUrl = WP_PLUGIN_URL . '/maxx-fitness-schedule/assets/js/jquery.jscrollpane.min.js';
    $ScriptFile = WP_PLUGIN_DIR . '/maxx-fitness-schedule/assets/js/jquery.jscrollpane.min.js';
    if ( file_exists($ScriptFile) ) {
        wp_register_script('mfsjscrollpane', $ScriptUrl);
        wp_enqueue_script('mfsjscrollpane');
    }
    
    //script.js
    $ScriptUrl = WP_PLUGIN_URL . '/maxx-fitness-schedule/assets/js/script.js';
    $ScriptFile = WP_PLUGIN_DIR . '/maxx-fitness-schedule/assets/js/script.js';
    if ( file_exists($ScriptFile) ) {
        wp_register_script('mfsscript', $ScriptUrl);
        wp_enqueue_script('mfsscript');
    }
    
}

// Additional links in plugin settings: Torbara.com | Portfolio
$tt_f_plugin_links = function($links) {
    array_push($links, '<a title="We are developing beautiful themes and applications for the web!" href="http://torbara.com" target="_blank" style="font-weight: bold; font-size: 14px;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0NTMuNSA0NTMuNSI+PHBhdGggZD0iTTM5NC43IDIzOC4zOTJjLTEuNSAwLTMgLjMtNC40LjdsLTUwLjEtMTEwYzMuOS0yLjQgNi41LTYuNyA2LjUtMTEuNiAwLTYuNi00LjctMTIuMS0xMC45LTEzLjQtLjYtLjEtMS4xLS4yLTEuNy0uMmgtMWMtNC43IDAtOC44IDIuMy0xMS4zIDUuOWwtNzcuNi0zMi41Yy4xLS43LjItMS40LjItMi4xIDAtNy42LTYuMS0xMy43LTEzLjctMTMuN3MtMTMuNyA2LjEtMTMuNyAxMy43di4zbC04OS40IDMwLjNjLTIuMS00LjktNi45LTguMy0xMi42LTguMy03LjUgMC0xMy43IDYuMS0xMy43IDEzLjcgMCA0LjkgMi42IDkuMiA2LjUgMTEuNmwtNDUuNiA4NS45Yy0uNS0uMS0xLS4xLTEuNi0uMS00LjUgMC04LjQgMi4xLTEwLjkgNS41LTEgMS4zLTEuNyAyLjctMi4yIDQuMy0uNCAxLjItLjYgMi42LS42IDMuOSAwIDcuNiA2LjEgMTMuNyAxMy43IDEzLjdoLjJsNDIuNiA5NS43Yy0yLjYgMi41LTQuMyA2LTQuMyA5LjkgMCA3LjUgNi4xIDEzLjcgMTMuNyAxMy43IDQuNyAwIDguOC0yLjMgMTEuMy01LjlsODYuNSAzOS41Yy41IDcuMSA2LjQgMTIuNiAxMy42IDEyLjYgNy41IDAgMTMuNi02IDEzLjctMTMuNWw5NC44LTMxLjJjMi40IDMuOSA2LjcgNi41IDExLjcgNi41IDcuNSAwIDEzLjctNi4xIDEzLjctMTMuNyAwLTUuNS0zLjMtMTAuMy04LTEyLjVsMzguNi03M2MxLjguOSAzLjkgMS41IDYuMiAxLjUgNy41IDAgMTMuNy02LjEgMTMuNy0xMy43LS4yLTcuNC02LjMtMTMuNS0xMy45LTEzLjV6bS03NC45LTEyNC4zYy0uMyAxLjEtLjQgMi4yLS40IDMuNCAwIDQuNCAyLjEgOC40IDUuNCAxMC45bC0xNi41IDQzLjNjLS44LS4yLTEuNy0uMi0yLjYtLjItMi4xIDAtNCAuNS01LjggMS4zbC01Ni42LTkwLjYgNzYuNSAzMS45em02MS40IDEzOS45bC02MS40IDMxLjhjLTIuNC0yLjEtNS42LTMuNC05LTMuNGgtMWwtMy4zLTgzLjZjMy4zLS4yIDYuMy0xLjYgOC42LTMuN2w2Ni4xIDU1LjNjLS4xLjUtLjEgMS4xLS4xIDEuNiAwIC43IDAgMS4zLjEgMnptLTI1NC45LTEzLjVsMTEtNTguN2guMmMyIDAgNC0uNSA1LjctMS4zbDU0LjggMTA1LjguMi0uMWMtLjEuMS0uMS4yLS4yLjNsLTYwLjctMjcuNi0yLjQtMS4xYy4xLS4zLjItLjcuMy0xIC4zLTEuMS40LTIuMy40LTMuNC4yLTYtMy44LTExLjEtOS4zLTEyLjl6bTIwLjktNjIuOGMxLjQtMS40IDIuNS0zLjEgMy4yLTVsNTkuNyAyLjNjLjYgNS43IDQuNyAxMC40IDEwLjIgMTEuOGwtMTEuNCA5MS45Yy0yLjkuMy01LjUgMS42LTcuNSAzLjRsLTU0LjItMTA0LjR6bTY2LjUgMTAxLjNsMTEuNC05MS44YzQuOS0uNSA5LjEtMy42IDExLThsNTYuMSAzLjJjLS4yLjktLjMgMS44LS4zIDIuNyAwIDQgMS43IDcuNyA0LjUgMTAuMmwtODIuNSA4My44LS40LjQuMi0uNXptODUuMi03OS4ybDEuOC0xLjkuOS4zLjEgMi42IDMuMiA4Mi44Yy0zLjQgMS42LTYuMSA0LjYtNy4yIDguMmgtNzMuNWMtLjEtNC40LTIuMy04LjItNS42LTEwLjZsODAuMy04MS40em0tNjguNS0xMTAuOWguMmMzLjQgMCA2LjYtMS4zIDktMy40bDU2LjMgOTAuMWMtLjYuNy0xLjIgMS40LTEuNyAyLjJsLTU2LjgtMy4zdi0uOGMwLTUuMy0zLjEtOS45LTcuNS0xMi4ybC41LTcyLjZ6bS01LjYtMS4zYy4yLjEuNS4yLjguM2wtLjUgNzIuMWMtLjQgMC0uOS0uMS0xLjMtLjEtNi40IDAtMTEuOCA0LjQtMTMuMyAxMC4zbC01OS4zLTIuM2MtLjEtMy41LTEuNC02LjctMy42LTlsNzcuMi03MS4zem0tOTYuMSAyMy42di0uN2w4OS4zLTMwLjNjLjcgMS43IDEuNyAzLjIgMi45IDQuNWwtNzcuMiA3MS4yYy0xLjgtLjktMy45LTEuNS02LjItMS41LTEuMSAwLTIuMS4xLTMuMS40bC0xMS0zMi44YzMuMi0yLjUgNS4zLTYuNCA1LjMtMTAuOHptLTE2LjQgMTMuNGMuOS4yIDEuOC4zIDIuNy4zIDEuNCAwIDIuOC0uMiA0LjEtLjZsMTAuOSAzMi40Yy0zLjcgMi40LTYuMSA2LjYtNi4xIDExLjQgMCA1LjggMy42IDEwLjcgOC43IDEyLjdsLTExIDU5Yy00LjEuMi03LjggMi4xLTEwLjEgNS4xbC0zNy43LTE4LjhjLjQtMS4yLjUtMi41LjUtMy44IDAtNS4zLTMtOS45LTcuNC0xMi4xbDQ1LjQtODUuNnptMiAyMDMuNWMtLjUgMC0uOS0uMS0xLjQtLjEtMS45IDAtMy43LjQtNS4zIDEuMWwtNDEuOS05NGMyLjQtLjkgNC40LTIuNSA1LjktNC42bDM3LjUgMTguN2MtLjQgMS4zLS43IDIuOC0uNyA0LjMgMCA2LjEgNCAxMS4yIDkuNCAxM2wtMy41IDYxLjZ6bTkuMyA1LjFjLTEuMi0xLjYtMi44LTIuOS00LjYtMy43bDMuNi02Mi4zYzQtLjIgNy42LTIuMSAxMC01bDY0LjMgMjkuMXYxYzAgMS4yLjIgMi40LjUgMy41bC03My44IDM3LjR6bTg3LjYgNTAuN2wtODUtMzguOGMuMy0xLjEuNC0yLjIuNC0zLjQgMC0xLjUtLjItMi45LS43LTQuMmw3My41LTM3LjNjMi41IDMuNSA2LjYgNS44IDExLjIgNS44aDFsOC4zIDY5Yy00LjIgMS4zLTcuNSA0LjctOC43IDguOXptMTE5LjgtMzEuNmwtOTMuOCAzMC45Yy0xLjgtNS4yLTYuNy04LjktMTIuNS05bC04LjQtNjkuNWMxLjgtLjggMy41LTIuMSA0LjgtMy42bDExMS4yIDQyLjNjLTEgMS45LTEuNSA0LTEuNSA2LjMtLjEuOSAwIDEuOC4yIDIuNnptLTEwNy41LTU1LjRsLS40LS4yaDc0LjFjLjQgNy4yIDYuMyAxMyAxMy43IDEzIDEuNCAwIDIuNy0uMiAzLjktLjZsMTguOCAyOS42LTExMC4xLTQxLjh6bTEyMS43IDM5LjJoLS44Yy0yLjIgMC00LjIuNS02IDEuNGwtMTkuNC0zMC42YzMuMy0yLjUgNS40LTYuNCA1LjQtMTAuOSAwLTIuMy0uNi00LjUtMS42LTYuNGw1OS45LTMxYy41IDEgMS4yIDEuOSAyIDIuN2wtMzkuNSA3NC44em0zNy41LTkwLjZsLTY0LjgtNTQuM2MuOS0xLjggMS40LTMuOSAxLjQtNi4xIDAtLjQgMC0uOC0uMS0xLjItLjEtMS44LS42LTMuNC0xLjQtNC45LTEuMi0yLjMtMy00LjMtNS4yLTUuNmwxNi40LTQyLjhjMS4zLjQgMi42LjYgNCAuNi45IDAgMS44LS4xIDIuNy0uM2w1MC40IDExMC42Yy0xLjQgMS4xLTIuNSAyLjQtMy40IDR6Ii8+PC9zdmc+" alt="" style="width: 24px; vertical-align: middle; position: relative; top: -1px;"> Torbara.com</a>');
    array_push($links, '<a title="Our portfolio on Envato Market" href="http://themeforest.net/user/torbara/portfolio?ref=torbara" target="_blank"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMDkuMjY3IDMwOS4yNjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMwOS4yNjcgMzA5LjI2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxwYXRoIHN0eWxlPSJmaWxsOiNEMDk5NEI7IiBkPSJNMjYwLjk0NCw0My40OTFIMTI1LjY0YzAsMC0xOC4zMjQtMjguOTk0LTI4Ljk5NC0yOC45OTRINDguMzIzYy0xMC42NywwLTE5LjMyOSw4LjY1LTE5LjMyOSwxOS4zMjkgICB2MjIyLjI4NmMwLDEwLjY3LDguNjU5LDE5LjMyOSwxOS4zMjksMTkuMzI5aDIxMi42MjFjMTAuNjcsMCwxOS4zMjktOC42NTksMTkuMzI5LTE5LjMyOVY2Mi44MiAgIEMyODAuMjczLDUyLjE1LDI3MS42MTQsNDMuNDkxLDI2MC45NDQsNDMuNDkxeiIvPgoJPHBhdGggc3R5bGU9ImZpbGw6I0U0RTdFNzsiIGQ9Ik0yOC45OTQsNzIuNDg0aDI1MS4yNzl2NzcuMzE3SDI4Ljk5NFY3Mi40ODR6Ii8+Cgk8cGF0aCBzdHlsZT0iZmlsbDojRjRCNDU5OyIgZD0iTTE5LjMyOSw5MS44MTRoMjcwLjYwOWMxMC42NywwLDE5LjMyOSw4LjY1LDE5LjMyOSwxOS4zMjlsLTE5LjMyOSwxNjQuMjk4ICAgYzAsMTAuNjctOC42NTksMTkuMzI5LTE5LjMyOSwxOS4zMjlIMzguNjU4Yy0xMC42NywwLTE5LjMyOS04LjY1OS0xOS4zMjktMTkuMzI5TDAsMTExLjE0M0MwLDEwMC40NjMsOC42NTksOTEuODE0LDE5LjMyOSw5MS44MTR6ICAgIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" alt="" style="width: 16px; vertical-align: middle; position: relative; top: -2px;"> Portfolio</a>');
    return $links;
};
add_filter( "plugin_action_links_".plugin_basename( __FILE__ ), $tt_f_plugin_links );

//add_action('widgets_init', create_function('', 'return register_widget("MaxxFitnessSchedule");'));
add_action('widgets_init', function(){return register_widget("MaxxFitnessSchedule");});