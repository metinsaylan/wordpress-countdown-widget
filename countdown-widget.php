<?php
/*
* Plugin Name: Countdown Widget
* Plugin URI: https://wpassist.me/plugins/countdown/
* Description: Countdown/Countup Timer Widget + Shortcode. Supports multiple instances, easy translation & customizations. Visit settings page to translate labels. <a href="https://wpassist.me/donate">❤️ Donate to This Plugin</a>
* Version: 3.1.7
* Author: Metin Saylan
* Author URI: https://metinsaylan.com/
* Text Domain: countdown-widget
*/

include_once('countdown-util.php'); // Utility functions for backward compatibility
include_once('countdown-shortcodes.php'); // Shortcode functions

global $countdown_shortcode_ids;

/**
 * Shailan Countdown Widget Class
 */
class shailan_CountdownWidget extends WP_Widget {

    /** constructor */
    function __construct() {

    $widget_ops = array( 
      'classname' => 'shailan_CountdownWidget', 
      'description' => __( 'Countdown/countup timer widget' , 'countdown-widget') 
    );
    
    parent::__construct( 
      'shailan-countdown-widget', 
      __('CountDown/Up Widget ⌛', 'countdown-widget'), 
      $widget_ops 
    );

    $this->alt_option_name = 'widget_shailan_countdown';

    add_action( 'admin_init', array(&$this, 'common_header'), 100, 1 );
    add_action( 'wp_enqueue_scripts', array(&$this, 'common_header'), 100, 1 );

    add_action( 'wp_footer', array(&$this, 'print_scripts'), 1000, 1 );
    add_action( 'wp_print_styles', array(&$this, 'print_styles'), 1000, 1 );
    add_action( 'admin_print_styles', array(&$this, 'print_styles'), 1000, 1 );

    $current_offset = get_option('gmt_offset');

    $this->pluginname = "Countdown Widget";
    $this->version = "3.1";
    $this->help_url = "https://wpassist.me/plugins/countdown/help/";

    $this->settings_key = "wp_countdown_widget";
    $this->options_page = "wp-countdown-widget";

    // Include options array
    require_once("countdown-options.php");
    $this->options = $options;
    $this->settings = $this->get_plugin_settings();

    add_action('admin_menu', array( &$this, 'admin_header') );

    $this->defaults = array(
      'title'   =>'',
      'event'   => '',

      'month'   => date( 'm' ),
      'day'   => date( 'd' ),
      'year'    => date( 'Y' ),

      'date'    => date( 'd-m-Y' ),

      'hour'    => 0,
      'minutes' => 0,
      'seconds' => 0,

      'format'  => 'yowdHMS',
      'color'   => '#000000',
      'bgcolor' => '#FFFFFF',
      'width'   => '',
      'link'    => false,
      'timezone'  => $current_offset,
      'eventurl'  => '',
      'direction' => 'Down',
      'height'  => '',
      'radius' => ''
    );

    $this->timezones = array(
      "SCW_NONE" => "None",
      "-12" => "-12",
      "-11" => "-11",
      "-10" => "-10",
      "-9" => "-9",
      "-8" => "-8",
      "-7" => "-7",
      "-6" => "-6",
      "-5" => "-5",
      "-4" => "-4",
      "-3.5" => "-3",
      "-3" => "-3",
      "-2" => "-2",
      "-1" => "-1",
      "0" => "GMT",
      "+1" => "+1",
      "+2" => "+2",
      "+3" => "+3",
      "+3.5" => "+3:30",
      "+4" => "+4",
      "+4.5" => "+4:30",
      "+5" => "+5",
      "+5.5" => "+5:30",
      "+6" => "+6",
      "+7" => "+7",
      "+8" => "+8",
      "+9" => "+9",
      "+9.5" => "+9:30",
      "+10" => "+10",
      "+11" => "+11",
      "+12" => "+12"
    );

    }

    function widget($args, $instance) {
    global $post, $countdown_shortcode_ids;

        extract( $args );

    $widget_options = wp_parse_args( $instance, $this->defaults );
    extract( $widget_options, EXTR_SKIP );

    // Get a new id
    $countdown_shortcode_ids++;

    if( !empty( $instance['link'] ) ){ $link = (bool) $link; }

    $style = "";

    if ( ( $timestamp = strtotime( $date ) ) !== false ) {
      $month  = date("n", $timestamp );
      $day  = date("j", $timestamp );
      $year   = date("Y", $timestamp );
    }

    // If this is not a widget
    if( isset( $isWidget ) && false === $isWidget ){

      $style=" style=\"";

      if(!empty($bgcolor)){
        $style .= "background-color:".$bgcolor.";";
      }

      if(!empty($color)){ $style .=  " color:".$color. ";"; }
      if(!empty($width) && $width>0){ $style .= " width:".$width."px;"; }
      if(!empty($radius) && $radius>0){ $style .= " border-radius:".$radius."px;"; }
        $style .= " margin:0px auto; \"";

    }



      ?>
          <?php echo $before_widget; ?>
          <?php if ( $title )
              echo $before_title . $title . $after_title;
          ?>

        <div id="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>" class="shailan-countdown-<?php echo $this->number ?> countdown" <?php echo $style; ?>></div>

        <?php
        if( !$link ){echo '<div '.$style.'><small><a href="https://wpassist.me/plugins/countdown/" title="WordPress Countdown Plugin" class="countdown_infolink">i</a></small></div>';};
        ?>

<script>
(function($){
  $(document).ready(function($) {
    var event_month = <?php echo $month; ?> - 1; 
    $('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').countdown({
      <?php if($direction == 'down'){ ?>until<?php } else { ?>since<?php } ?>: new Date(<?php echo $year; ?>, event_month, <?php echo $day; ?>, <?php echo $hour; ?>, <?php echo $minutes; ?>, <?php echo $seconds; ?>, 0),
      description: '<?php $event = addslashes(force_balance_tags($event)); echo $event; ?>',
      format: '<?php echo $format; ?>'<?php if($timezone != 'SCW_NONE'){ ?>,
      timezone: '<?php echo $timezone; ?>'<?php } ?>
    }); 
  }); 
})(jQuery);
</script>
          <?php echo $after_widget; ?>
      <?php

    }

    function update( $new_instance, $old_instance ) {

    $date = DateTime::createFromFormat( "d-m-Y", $new_instance['date'] );
    $new_instance['day'] = $date->format("d");
    $new_instance['month'] = $date->format("m");
    $new_instance['year'] = $date->format("Y");

    if( $new_instance['hour'] > 24 ) $new_instance['hour'] = '00';
    if( $new_instance['minutes'] > 59 ) $new_instance['minutes'] = '59';
    if( $new_instance['seconds'] > 59 ) $new_instance['seconds'] = '59';

    if( $new_instance['timezone'] == '' ) $new_instance['timezone'] = 'SCW_NONE';

    $color = $new_instance['color'];
    if( (preg_match('/^#[a-f0-9]{6}$/i', $color) != 1 && preg_match('/^[a-f0-9]{6}$/i', $color) ) || (preg_match('/^#[a-f0-9]{3}$/i', $color) != 1 && preg_match('/^[a-f0-9]{3}$/i', $color) ) ){
      $color = "#" . $color;
    }
    $new_instance['color'] = $color;

    $bgcolor = $new_instance['bgcolor'];
    if( (preg_match('/^#[a-f0-9]{6}$/i', $bgcolor) != 1 && preg_match('/^[a-f0-9]{6}$/i', $bgcolor) ) || (preg_match('/^#[a-f0-9]{3}$/i', $bgcolor) != 1 && preg_match('/^[a-f0-9]{3}$/i', $bgcolor)) ){
      $bgcolor = "#" . $bgcolor;
    }
    $new_instance['bgcolor'] = $bgcolor;

    $new_instance['format'] = preg_replace( "/[^yowdhmsYOWDHMS]+/", "", $new_instance['format'] );
    if( strlen( $new_instance[ 'format' ] ) <= 0 ){ $new_instance['format'] = 'wdHMS'; }

        return $new_instance;
    }

    function form($instance) {

    global $post, $countdown_shortcode_ids;

    $widget_options = wp_parse_args( $instance, $this->defaults );
    extract( $widget_options, EXTR_SKIP );

    $event_display = addslashes($event);
    $event = htmlspecialchars( force_balance_tags( $event ) );

    if( !empty( $instance['link'] ) ){ $link = (bool) $link; }

    if ( ( $timestamp = strtotime( $date ) ) !== false) {
      $month  = date("n", $timestamp );
      $day  = date("j", $timestamp );
      $year   = date("Y", $timestamp );
    }

    $countdown_shortcode_ids++;

        ?>
    <div id="countdown-preview">
      <div id="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>" class="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?> countdown"></div>
<script>
(function($) {
  $(document).ready(function($) {
    var event_month = <?php echo $month; ?> - 1;

    $('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').countdown({
      <?php if($direction == 'down'){ ?>until<?php } else { ?>since<?php } ?>: new Date(<?php echo $year; ?>, event_month, <?php echo $day; ?>, <?php echo $hour; ?>, <?php echo $minutes; ?>, <?php echo $seconds; ?>, 0), 
      description: '<?php echo $event_display; ?>', 
      format: '<?php echo $format; ?>'<?php if($timezone != 'SCW_NONE'){ ?>, 
      timezone: '<?php echo $timezone; ?>'<?php } ?>
    }); 

    <?php if( $color != '' ){ ?>$('#shailan-countdown-<?php echo $this->number . "_" .$countdown_shortcode_ids; ?>').css('color', '<?php echo $color; ?>'); <?php } ?>
    <?php if( $bgcolor != '' ){ ?> $('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').css('backgroundColor', '<?php echo $bgcolor; ?>'); <?php } ?> 

    $('.datepicker').datepicker({ dateFormat : 'dd-mm-yy' });

  }); 
})(jQuery);
</script>
    </div>

    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'countdown-widget'); ?> <small><a href="https://wpassist.me/plugins/countdown/help/#title" target="_blank" rel="external">(?)</a></small> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

    <p><label for="<?php echo $this->get_field_id('event'); ?>"><?php _e('Event Title:', 'countdown-widget'); ?> <small><a href="https://wpassist.me/plugins/countdown/help/#event-title" target="_blank" rel="external">(?)</a></small> <input class="widefat" id="<?php echo $this->get_field_id('event'); ?>" name="<?php echo $this->get_field_name('event'); ?>" type="text" value="<?php echo $event; ?>" /></label></p>

    <p><label for="<?php echo $this->get_field_id('direction'); ?>"> <?php _e('Count Down/Up :', 'countdown-widget'); ?></label>
      <select name="<?php echo $this->get_field_name('direction'); ?>" id="<?php echo $this->get_field_id('direction'); ?>" >
        <option value="down" <?php if($direction == "down") { ?> selected="selected" <?php } ?>>Down</option>
        <option value="up" <?php if($direction == "up") { ?> selected="selected" <?php } ?>>Up</option>
      </select> <a href="https://wpassist.me/plugins/countdown/help/#direction" target="_blank">(?)</a>
    </p>

    <p><label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Date :', 'countdown-widget'); ?></label> <small><a href="https://wpassist.me/plugins/countdown/help/#date" target="_blank" rel="external">(?)</a></small> <input type="text" class="widefat datepicker" name="<?php echo $this->get_field_name('date'); ?>" id="<?php echo $this->get_field_id('date'); ?>" value="<?php echo $date; ?>"/><br />
    <small>DD-MM-YYYY</small> </p>

    <script type="text/javascript">
      if( jQuery.datepicker ){
        jQuery('.datepicker').datepicker({
          dateFormat : 'dd-mm-yy'
        });
      }
    </script>

    <p><label for="<?php echo $this->get_field_id('hour'); ?>"><?php _e('Time :', 'countdown-widget'); ?></label><input id="<?php echo $this->get_field_id('hour'); ?>" name="<?php echo $this->get_field_name('hour'); ?>" type="number" min="0" max="23" value="<?php echo $hour; ?>" size="2" maxlength="2" />:<input id="<?php echo $this->get_field_id('minutes'); ?>" name="<?php echo $this->get_field_name('minutes'); ?>" type="number" min="0" max="59" value="<?php echo $minutes; ?>" size="2" maxlength="2" />:<input id="<?php echo $this->get_field_id('seconds'); ?>" name="<?php echo $this->get_field_name('seconds'); ?>" type="number" min="0" max="59" value="<?php echo $seconds; ?>" size="4" maxlength="4" /><br />
    <small>HH:MM:SS</small> <small><a href="https://wpassist.me/plugins/countdown/help/#time" target="_blank" rel="external">(?)</a></small></p>

    <p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format :', 'countdown-widget'); ?></label> <small><a href="https://wpassist.me/plugins/countdown/help/#format" target="_blank" rel="external">(?)</a></small> <input id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" class="widefat" type="text" value="<?php echo $format; ?>" size="10" maxlength="8" placeholder="yowdHMS" />
    </p>

    <p><label for="<?php echo $this->get_field_id('timezone'); ?>"><?php _e('Timezone :', 'countdown-widget'); ?>
      <select name="<?php echo $this->get_field_name('timezone'); ?>" id="<?php echo $this->get_field_id('timezone'); ?>" >
      <?php
        foreach ( $this->timezones as $key=>$name ) {
          $option = '<option value="'. $key .'" '. ( '_'.$timezone == '_'.$key ? ' selected="selected"' : '' ) .'>';
          $option .= $name;
          $option .= '</option>\n';
          echo $option;
        }
      ?>
      </select></label> <a href="https://wpassist.me/plugins/countdown/help/#timezone" target="_blank">(?)</a></p>

    <p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Text Color :', 'countdown-widget'); ?></label> <small><a href="https://wpassist.me/plugins/countdown/help/#color" target="_blank" rel="external">(?)</a></small> <input id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" class="widefat" type="text"  placeholder="#000000" value="<?php echo $color; ?>" size="6" /></p>

    <p><label for="<?php echo $this->get_field_id('bgcolor'); ?>"><?php _e('Background Color :', 'countdown-widget'); ?></label> <small><a href="https://wpassist.me/plugins/countdown/help/#background-color" target="_blank" rel="external">(?)</a></small> <input id="<?php echo $this->get_field_id('bgcolor'); ?>" name="<?php echo $this->get_field_name('bgcolor'); ?>" class="widefat" placeholder="#DDDDDD" type="text" value="<?php echo $bgcolor; ?>" size="6" /> </p>

    <p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width :', 'countdown-widget'); ?> <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" size="4" maxlength="4" /> px</label> <small><a href="https://wpassist.me/plugins/countdown/help/#width" target="_blank" rel="external">(?)</a></small></p>

    <p><label for="<?php echo $this->get_field_id('radius'); ?>"><?php _e('Border Radius :', 'countdown-widget'); ?> <input id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>" type="text" value="<?php echo $radius; ?>" size="4" maxlength="4" /> px</label> <small><a href="https://wpassist.me/plugins/countdown/help/#border-radius" target="_blank" rel="external">(?)</a></small></p>

    <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>"<?php checked( $link ); ?> />
    <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e( 'Remove link' , 'countdown-widget'); ?></label> <small><a href="https://wpassist.me/plugins/countdown/help/#remove-link" target="_blank" rel="external">(?)</a></small></p>

    <p><a href="options-general.php?page=wp-countdown-widget" target="_blank">Edit Settings..</a></p>

<div class="clear"></div>
        <?php

  }

  function common_header( $instance ){
    if( !wp_doing_ajax() ){

      wp_enqueue_script('jquery');
      wp_enqueue_script('countdown', plugins_url('js/jquery.countdown.min.js', __FILE__), 'jquery', '1.0', true);

    }
  }

  function print_scripts( $instance = null ){
    if( !wp_doing_ajax() ){
      $cd_settings = $this->get_plugin_settings(); ?>

<script>(function($) {
  $.countdown.regional['custom'] = {
    labels: [
      '<?php echo $cd_settings['label_years']; ?>', 
      '<?php echo $cd_settings['label_months']; ?>', 
      '<?php echo $cd_settings['label_weeks']; ?>', 
      '<?php echo $cd_settings['label_days']; ?>', 
      '<?php echo $cd_settings['label_hours']; ?>', 
      '<?php echo $cd_settings['label_minutes']; ?>', 
      '<?php echo $cd_settings['label_seconds']; ?>'
      ], 
    labels1: [
      '<?php echo $cd_settings['label_year']; ?>', 
      '<?php echo $cd_settings['label_month']; ?>', 
      '<?php echo $cd_settings['label_week']; ?>', 
      '<?php echo $cd_settings['label_day']; ?>', 
      '<?php echo $cd_settings['label_hour']; ?>', 
      '<?php echo $cd_settings['label_minute']; ?>', 
      '<?php echo $cd_settings['label_second']; ?>'
    ], 
    compactLabels: ['y', 'a', 'h', 'g'], 
    whichLabels: null, 
    timeSeparator: ':', 
    isRTL: false
  }; 
  $.countdown.setDefaults($.countdown.regional['custom']); 
})(jQuery);
</script>
<?php

    }
  }

  function admin_header( $instance ){
    if( !wp_doing_ajax() ){

      wp_enqueue_script('jquery');
      wp_enqueue_script('jquery-ui-datepicker');
      wp_enqueue_style('jquery-ui-custom-css', plugins_url('css/jquery-ui.min.css', __FILE__));

      /* If we are on options page */
      if ( @$_GET['page'] == $this->options_page ) {

        wp_enqueue_style( "countdown-widget-admin", plugins_url( '/css/countdown-admin.css' , __FILE__ ) , false, null, "all");

        if ( @$_REQUEST['action'] && 'save' == $_REQUEST['action'] ) {

          // Save settings
          $settings = $this->get_settings();

          // Set updated values
          foreach( $this->options as $option ){
            if( array_key_exists( 'id', $option ) ){
              if( $option['type'] == 'checkbox' && empty( $_REQUEST[ $option['id'] ] ) ) {
                $settings[ $option['id'] ] = 'off';
              } elseif( array_key_exists( $option['id'], $_REQUEST ) ) {
                $settings[ $option['id'] ] = $_REQUEST[ $option['id'] ];
              } else {
                // hmm no key here?
              }
            }
          }

          // Save the settings
          update_option( $this->settings_key, $settings );
          header("Location: admin.php?page=" . $this->options_page . "&saved=true&message=1");
          die;
        } else if( @$_REQUEST['action'] && 'reset' == $_REQUEST['action'] ) {

          // Start a new settings array
          $settings = array();
          delete_option( $this->settings_key );

          header("Location: admin.php?page=" . $this->options_page . "&reset=true&message=2");
          die;
        }

      }

      $page = add_options_page(
        __('WordPress Countdown Widget', 'countdown-widget'),
        __('Countdown Widget', 'countdown-widget'),
        'manage_options',
        $this->options_page,
        array( &$this, 'options_page')
      );
    }
  }

  function get_plugin_settings(){
    $settings = get_option( $this->settings_key );

    if(FALSE === $settings){
      // Options doesn't exist, install standard settings
      return $this->install_default_settings();
    } else { // Options exist, update if necessary
      if( !empty( $settings['version'] ) ){ $ver = $settings['version']; }
      else { $ver = ''; }

      if($ver != $this->version){
        // Update settings
        return $this->update_plugin_settings( $settings );
      } else {
        // Plugin is up to date, let's return
        return $settings;
      }
    }
  }

  /* Updates a single option key */
  function update_plugin_setting( $key, $value ){
    $settings = $this->get_plugin_settings();
    $settings[$key] = $value;
    update_option( $this->settings_key, $settings );
  }

  /* Retrieves a single option */
  function get_plugin_setting( $key, $default = '' ) {
    $settings = $this->get_plugin_settings();
    if( array_key_exists($key, $settings) ){
      return $settings[$key];
    } else {
      return $default;
    }

    return FALSE;
  }

  function install_default_settings(){
    // Create settings array
    $settings = array();

    // Set default values
    foreach($this->options as $option){
      if( array_key_exists( 'id', $option ) && array_key_exists( 'std', $option ) )
        $settings[ $option['id'] ] = $option['std'];
    }

    $settings['version'] = $this->version;
    // Save the settings
    update_option( $this->settings_key, $settings );
    return $settings;
  }

  function update_plugin_settings( $current_settings ){
    //Add missing keys
    foreach($this->options as $option){
      if( array_key_exists ( 'id' , $option ) && !array_key_exists ( $option['id'] ,$current_settings ) ){
        $current_settings[ $option['id'] ] = $option['std'];
      }
    }

    update_option( $this->settings_key, $current_settings );
    return $current_settings;
  }

  function options_page(){
    global $options, $current;

    $title = "WordPress Countdown Widget Options";

    $options = $this->options;
    $current = $this->get_plugin_settings();

    $messages = array(
      "1" => __("Settings are saved.", "countdown-widget"),
      "2" => __("Settings are reset.", "countdown-widget")
    );

    include_once( "countdown-options-page.php" );

  }

  function print_styles( $instance ){
    $all_widgets = $this->get_settings();

?>
<style type="text/css">
.hasCountdown{text-shadow:transparent 0 1px 1px;overflow:hidden;padding:5px}
.countdown_rtl{direction:rtl}
.countdown_holding span{background-color:#ccc}
.countdown_row{clear:both;width:100%;text-align:center}
.countdown_show1 .countdown_section{width:98%}
.countdown_show2 .countdown_section{width:48%}
.countdown_show3 .countdown_section{width:32.5%}
.countdown_show4 .countdown_section{width:24.5%}
.countdown_show5 .countdown_section{width:19.5%}
.countdown_show6 .countdown_section{width:16.25%}
.countdown_show7 .countdown_section{width:14%}
.countdown_section{display:block;float:left;font-size:75%;text-align:center;margin:3px 0}
.countdown_amount{font-size:200%}
.countdown_descr{display:block;width:100%}
a.countdown_infolink{display:block;border-radius:10px;width:14px;height:13px;float:right;font-size:9px;line-height:13px;font-weight:700;text-align:center;position:relative;top:-15px;border:1px solid}
#countdown-preview{padding:10px}
<?php

    foreach ($all_widgets as $key => $widget){
      $widget_id = $this->id_base . '-' . $key;
      if(is_active_widget(false, $widget_id, $this->id_base)){
        $countdown = $all_widgets[$key];

        if( array_key_exists( 'color', $countdown ) ){
          if( preg_match('/^#[a-f0-9]{6}$/i', $countdown['color']) == 0 && preg_match('/^[a-f0-9]{6}$/i', $countdown['color']) ){
            $color = "#" . $countdown['color'];
          }
        }

        if( array_key_exists( 'bgcolor', $countdown ) ){
          if( preg_match('/^#[a-f0-9]{6}$/i', $countdown['bgcolor']) == 0 && preg_match('/^[a-f0-9]{6}$/i', $countdown['bgcolor']) ){
            $bgcolor = "#" . $countdown['bgcolor'];
          }
        }

        echo "#shailan-countdown-".$key.", .shailan-countdown-".$key.".hasCountdown{ ";
        // Background color
        if(!empty($countdown['bgcolor'])){
          echo "background-color: ".$countdown['bgcolor'].";";
        } else {
          echo "background-color: transparent;";
        };
        // Color
        if(!empty($countdown['color'])){ echo "color: ".$countdown['color'].";"; };
        // Width
        if(!empty($countdown['width']) && $countdown['width']>0){ echo "width:".$countdown['width']."px;"; };
        if(!empty($countdown['radius']) && $countdown['radius']>0){ echo "border-radius:".$countdown['radius']."px;"; };
        echo "margin:0px auto;";
        echo "}";
        echo " #shailan-countdown-".$key.", .shailan-countdown-".$key.".hasCountdown a{ ";
        if(!empty($countdown['color'])){ echo "color: ".$countdown['color'].";"; };
        echo "}";

      }
    }

  echo "</style>\n";


  }

  function header( $instance = null ){}
  function footer( $instance = null ){}


} // class shailan_CountdownWidget


// register widget
add_action( 'widgets_init', 'wcw_register_widget' );
function wcw_register_widget(){
  return register_widget("shailan_CountdownWidget");
}


// Settings link
$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$plugin", "wcw_add_settings_link" );
function wcw_add_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=wp-countdown-widget">Settings</a>';
  $donate_link = '<a href="https://wpassist.me/donate">Donate</a>';
  array_push( $links, $settings_link );
  array_push( $links, $donate_link );
  return $links;
}


