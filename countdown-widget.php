<?php 
/*
* Plugin Name: Countdown Widget
* Plugin URI: http://metinsaylan.com/wordpress/plugins/countdown/
* Description: A Beautiful jQuery Countdown Widget. Allows Multiple instances, Shortcode usage, and Customizations. Powered by: <a href="http://metinsaylan.com">metinsaylan</a>.
* Version: 2.6
* Author: Metin Saylan
* Author URI: http://metinsaylan.com/
* Text Domain: countdown-widget
*/

global $countdown_shortcode_ids;

/**
 * Shailan Countdown Widget Class
 */
class shailan_CountdownWidget extends WP_Widget {
	
    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'shailan_CountdownWidget', 'description' => __( 'jQuery Countdown/up widget' , 'countdown-widget') );
		parent::__construct( 'shailan-countdown-widget', __('CountDown/Up Timer', 'countdown-widget'), $widget_ops );
		$this->alt_option_name = 'widget_shailan_countdown';	
		
		add_action( 'admin_init', array(&$this, 'admin_header'), 10, 1 );	
		add_action( 'wp_enqueue_scripts', array(&$this, 'front_header'), 10, 1 );	
		
		$current_offset = get_option('gmt_offset');
		
		$this->defaults = array(
			'title'		=>'',
			'event'		=> '',
			
			'month'		=> date( 'm' ),
			'day'		=> date( 'd' ),
			'year'		=> date( 'Y' ),
			
			'date'		=> date( 'd-m-Y' ),
			
			'hour'		=> ( gmdate( 'H' ) + $current_offset + 1 ),
			'minutes'	=> date( 'i' ),
			'seconds'	=> date( 's' ),

			'format'	=> 'yowdHMS',
			'color'		=> '000000',
			'bgcolor'	=> '',
			'width'		=> '',
			'link'		=> false,
			'timezone'	=> $current_offset,
			'eventurl'	=> '',
			'direction' => 'Down',
			'height'	=> '',
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
		
		$path = get_plugin_path(__FILE__);
		
		$style = "";
		
		if ( ( $timestamp = strtotime( $args['date'] ) ) !== false ) {
			$month 	= date("n", $timestamp );
			$day 	= date("j", $timestamp );
			$year 	= date("Y", $timestamp );
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
				if( !$link ){echo '<div '.$style.'><small><a href="http://metinsaylan.com/wordpress/plugins/countdown/" title="Get Countdown/up Timer Widget for WordPress" style="float:right;">&uarr; Get this</a></small></div>';};
				?>
				
<script type="text/javascript"> 
<!--//
(function( $ ) {
	$(document).ready(function($) {
		var event_month = <?php echo $month; ?> - 1;
		desc = '<?php $event = addslashes(force_balance_tags($event)); echo $event; ?>';
		eventDate = new Date(<?php echo $year; ?>, event_month, <?php echo $day; ?>, <?php echo $hour; ?>, <?php echo $minutes; ?>, <?php echo $seconds; ?>, 0);
		$('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').countdown({
			<?php if($direction == 'down'){ ?>until<?php } else { ?>since<?php } ?>: eventDate, 
			description: desc,  
			format: '<?php echo $format; ?>'<?php if($timezone != 'SCW_NONE'){ ?>,
			timezone: <?php echo $timezone; } ?>
		}); 
	});
})(jQuery);
//-->
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
		
		if( !empty($instance['link']) ){ $link = (bool) $link; }
		
		if ( ( $timestamp = strtotime( $args['date'] ) ) !== false) {
			$month 	= date("n", $timestamp );
			$day 	= date("j", $timestamp );
			$year 	= date("Y", $timestamp );
		}
		
		$countdown_shortcode_ids++;
	
        ?>
		<div id="countdown-preview">
			<div id="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>" class="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?> countdown"></div>
<script type="text/javascript"> 
<!--//
(function( $ ) {

$(document).ready(function($) {
	var event_month = <?php echo $month; ?> - 1;
	desc = '<?php echo $event_display; ?>';
	eventDate = new Date(<?php echo $year; ?>, event_month, <?php echo $day; ?>, <?php echo $hour; ?>, <?php echo $minutes; ?>, <?php echo $seconds; ?>, 0);
	$('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').countdown({
		<?php if($direction == 'down'){ ?>until<?php } else { ?>since<?php } ?>: eventDate, 
		description: desc,  
		format: '<?php echo $format; ?>'<?php if($timezone != 'SCW_NONE'){ ?>,
		timezone: <?php echo $timezone; } ?>
	});
	
	<?php if( $color != '' ){ ?>
	$('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').css('color', '<?php echo $color; ?>');
	<?php } ?>
	
	<?php if( $bgcolor != '' ){ ?>
	$('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').css('backgroundColor', '<?php echo $bgcolor; ?>');
	<?php } ?>

	$('.datepicker').datepicker({
		dateFormat : 'dd-mm-yy'
	});
});

})(jQuery);
//-->
</script>
		</div>	
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:', 'countdown-widget'); ?> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#title" target="_blank" rel="external">(?)</a></small> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
		<p><label for="<?php echo $this->get_field_id('event'); ?>"><?php _e('Event Title:', 'countdown-widget'); ?> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#event-title" target="_blank" rel="external">(?)</a></small> <input class="widefat" id="<?php echo $this->get_field_id('event'); ?>" name="<?php echo $this->get_field_name('event'); ?>" type="text" value="<?php echo $event; ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('direction'); ?>"> <?php _e('Count Down/Up :', 'countdown-widget'); ?></label>
			<select name="<?php echo $this->get_field_name('direction'); ?>" id="<?php echo $this->get_field_id('direction'); ?>" >
				<option value="down" <?php if($direction == "down") { ?> selected="selected" <?php } ?>>Down</option>
				<option value="up" <?php if($direction == "up") { ?> selected="selected" <?php } ?>>Up</option>
			</select> <a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#direction">(?)</a>
		</p>
		
		<p><label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Date :', 'countdown-widget'); ?></label> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#date" target="_blank" rel="external">(?)</a></small> <input type="text" class="widefat datepicker" name="<?php echo $this->get_field_name('date'); ?>" id="<?php echo $this->get_field_id('date'); ?>" value="<?php echo $date; ?>"/><br /> 
		<small>DD-MM-YYYY</small> </p>
		
		<script type="text/javascript">
			if( jQuery.datepicker ){
				jQuery('.datepicker').datepicker({
					dateFormat : 'dd-mm-yy'
				});
			}
		</script>
		
		<p><label for="<?php echo $this->get_field_id('hour'); ?>"><?php _e('Time :', 'countdown-widget'); ?></label><input id="<?php echo $this->get_field_id('hour'); ?>" name="<?php echo $this->get_field_name('hour'); ?>" type="number" min="0" max="23" value="<?php echo $hour; ?>" size="2" maxlength="2" />:<input id="<?php echo $this->get_field_id('minutes'); ?>" name="<?php echo $this->get_field_name('minutes'); ?>" type="number" min="0" max="59" value="<?php echo $minutes; ?>" size="2" maxlength="2" />:<input id="<?php echo $this->get_field_id('seconds'); ?>" name="<?php echo $this->get_field_name('seconds'); ?>" type="number" min="0" max="59" value="<?php echo $seconds; ?>" size="4" maxlength="4" /><br /> 
		<small>HH:MM:SS</small> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#time" target="_blank" rel="external">(?)</a></small></p>
		
		<p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format :', 'countdown-widget'); ?></label> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#format" target="_blank" rel="external">(?)</a></small> <input id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" class="widefat" type="text" value="<?php echo $format; ?>" size="10" maxlength="8" placeholder="yowdHMS" /><br />  </p>
		
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
			</select></label> <a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#timezone">(?)</a></p>
		
		<p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Text Color :', 'countdown-widget'); ?></label> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#color" target="_blank" rel="external">(?)</a></small> <input id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" class="widefat" type="text"  placeholder="#000000" value="<?php echo $color; ?>" size="6" /></p>
		
		<p><label for="<?php echo $this->get_field_id('bgcolor'); ?>"><?php _e('Background Color :', 'countdown-widget'); ?></label> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#background-color" target="_blank" rel="external">(?)</a></small> <input id="<?php echo $this->get_field_id('bgcolor'); ?>" name="<?php echo $this->get_field_name('bgcolor'); ?>" class="widefat" placeholder="#DDDDDD" type="text" value="<?php echo $bgcolor; ?>" size="6" /> </p>
		
		<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width :', 'countdown-widget'); ?> <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" size="4" maxlength="4" /> px</label> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#width" target="_blank" rel="external">(?)</a></small></p>
		
		<p><label for="<?php echo $this->get_field_id('radius'); ?>"><?php _e('Border Radius :', 'countdown-widget'); ?> <input id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>" type="text" value="<?php echo $radius; ?>" size="4" maxlength="4" /> px</label> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#border-radius" target="_blank" rel="external">(?)</a></small></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>"<?php checked( $link ); ?> />
		<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e( 'Remove link' , 'countdown-widget'); ?></label> <small><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/#remove-link" target="_blank" rel="external">(?)</a></small></p>
		
		<div class="widget-control-actions">
			<p><a href="http://metinsaylan.com/wordpress/plugins/countdown/help/" >Help</a> | <a href="http://metinsaylan.com/donate" >Donate</a></p>
		</div>
			
<div class="clear"></div>
        <?php 
		
	}
	
	function admin_header( $instance ){
		
		if( !wp_doing_ajax() ){
		
		$all_widgets = $this->get_settings();
		
		foreach ($all_widgets as $key => $widget){
			
			$widget_id = $this->id_base . '-' . $key;		
			
			if( is_active_widget( false, $widget_id, $this->id_base ) ){
				
				$countdown = $all_widgets[$key];
				
				if( preg_match('/^#[a-f0-9]{6}$/i', $countdown['color']) == 0 && preg_match('/^[a-f0-9]{6}$/i', $countdown['color']) ){
					$color = "#" . $countdown['color'];
				} 
				
				if( preg_match('/^#[a-f0-9]{6}$/i', $countdown['bgcolor']) == 0 && preg_match('/^[a-f0-9]{6}$/i', $countdown['bgcolor']) ){
					$bgcolor = "#" . $countdown['bgcolor'];
				} 
			
				echo "\n<style type=\"text/css\" media=\"screen\">";
				echo "\n\t #shailan-countdown-".$key.", .shailan-countdown-".$key.".hasCountdown{ ";
				// Background color
				if(!empty($countdown['bgcolor'])){ 
					echo "\n\tbackground-color: ".$countdown['bgcolor'].";"; 
				} else {
					echo "\n\tbackground-color: transparent;";
				};
				// Color
				if(!empty($countdown['color'])){ echo "\n\tcolor: ".$countdown['color'].";"; };
				// Width
				if(!empty($countdown['width']) && $countdown['width']>0){ echo "\n\twidth:".$countdown['width']."px;"; };
				if(!empty($countdown['radius']) && $countdown['radius']>0){ echo "\n\tborder-radius:".$countdown['radius']."px;"; };
				echo "\n\tmargin:0px auto;";
				echo "}";
				echo "\n</style>\n";
			}
		}
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('countdown', plugins_url('js/jquery.countdown.min.js', __FILE__), 'jquery', '1.0', true);
		
		// localization
		$lang = get_bloginfo('language');						// Long (full) language code
		$short_lang = substr( get_bloginfo('language'), 0, 2 );	// Short language code (just two first letters)

		if( $lang!='en' ){
			if( file_exists( plugin_dir_path(__FILE__) . 'js/jquery.countdown-' . $lang . '.js' ) ){
				wp_enqueue_script('countdown-l10n', plugins_url('js/jquery.countdown-' . $lang . '.js',__FILE__), 'countdown', '1.0', true);
			} elseif( file_exists( plugin_dir_path(__FILE__) . 'js/jquery.countdown-' . $short_lang . '.js' ) ){
				wp_enqueue_script('countdown-l10n', plugins_url('js/jquery.countdown-' . $short_lang . '.js',__FILE__), 'countdown', '1.0', true);
			} 
		}
		
		wp_enqueue_style('jquery-ui-custom-css', plugins_url('css/jquery-ui.min.css', __FILE__));
		wp_enqueue_style('countdown-style', plugins_url('css/jquery.countdown.css', __FILE__), '', '1.1', false);
		
		}
	
	}
	
	function front_header( $instance ){
		
		$all_widgets = $this->get_settings();
		
		foreach ($all_widgets as $key => $widget){
			$widget_id = $this->id_base . '-' . $key;		
			if(is_active_widget(false, $widget_id, $this->id_base)){
				$countdown = $all_widgets[$key];
				
				if( preg_match('/^#[a-f0-9]{6}$/i', $countdown['color']) == 0 && preg_match('/^[a-f0-9]{6}$/i', $countdown['color']) ){
					$color = "#" . $countdown['color'];
				} 
				
				if( preg_match('/^#[a-f0-9]{6}$/i', $countdown['bgcolor']) == 0 && preg_match('/^[a-f0-9]{6}$/i', $countdown['bgcolor']) ){
					$bgcolor = "#" . $countdown['bgcolor'];
				} 
			
				echo "\n<style type=\"text/css\" media=\"screen\">";
				echo "\n\t #shailan-countdown-".$key.", .shailan-countdown-".$key.".hasCountdown{ ";
				// Background color
				if(!empty($countdown['bgcolor'])){ 
					echo "\n\tbackground-color: ".$countdown['bgcolor'].";"; 
				} else {
					echo "\n\tbackground-color: transparent;";
				};
				// Color
				if(!empty($countdown['color'])){ echo "\n\tcolor: ".$countdown['color'].";"; };
				// Width
				if(!empty($countdown['width']) && $countdown['width']>0){ echo "\n\twidth:".$countdown['width']."px;"; };
				if(!empty($countdown['radius']) && $countdown['radius']>0){ echo "\n\tborder-radius:".$countdown['radius']."px;"; };
				echo "\n\tmargin:0px auto;";
				echo "}";
				echo "\n\t #shailan-countdown-".$key.", .shailan-countdown-".$key.".hasCountdown a{ ";
				if(!empty($countdown['color'])){ echo "\n\tcolor: ".$countdown['color'].";"; };
				echo "}";
				echo "\n</style>\n";
			}
		}
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('countdown', plugins_url('js/jquery.countdown.min.js', __FILE__), 'jquery', '1.0', true);
		
		// localization
		$lang = get_bloginfo('language');						// Long (full) language code
		$short_lang = substr( get_bloginfo('language'), 0, 2 );	// Short language code (just two first letters)

		if( $lang!='en' ){
			if( file_exists( plugin_dir_path(__FILE__) . 'js/jquery.countdown-' . $lang . '.js' ) ){
				wp_enqueue_script('countdown-l10n', plugins_url('js/jquery.countdown-' . $lang . '.js',__FILE__), 'countdown', '1.0', true);
			} elseif( file_exists( plugin_dir_path(__FILE__) . 'js/jquery.countdown-' . $short_lang . '.js' ) ){
				wp_enqueue_script('countdown-l10n', plugins_url('js/jquery.countdown-' . $short_lang . '.js',__FILE__), 'countdown', '1.0', true);
			} 
		}
		
		wp_enqueue_style('countdown-style', plugins_url('css/jquery.countdown.css', __FILE__), '', '1.1', false);
	
	}
	
	function footer($instance){

	}
	

} // class shailan_CountdownWidget

function shailan_CountdownWidget_shortcode( $atts, $content = null ){
	global $post, $subpages_indexes;
	
	$args = shortcode_atts( array(
			'title'=>'',
			'event'=>'',
			'date'=>false,
			'month'=>'',
			'day'=>'',
			'hour'=>'0',
			'minutes'=>'0',
			'seconds'=>'0',
			'year'=>'',
			'format'=>'yowdHMS',
			'color'=>'',
			'bgcolor'=>'',
			'width'=>'',
			'height' => '',
			'radius' => '',
			'border' => '',
			'link'=>false,
			'timezone' => 'SCW_NONE',
			'direction' => 'down',
			'isWidget' => false
		), $atts );
		
	if( $args['date'] ){
		if ( ( $timestamp = strtotime( $args['date'] ) ) !== false) {
			$args['month'] = date("n", $timestamp );
			$args['day'] = date("j", $timestamp );
			$args['year'] = date("Y", $timestamp );
		}
	}
	
	ob_start();
	the_widget( 'shailan_CountdownWidget', $args );
	$cd_code = ob_get_contents();
	ob_end_clean();
	
	return $cd_code;
	
} add_shortcode( 'countdown', 'shailan_CountdownWidget_shortcode');

// register widget
add_action('widgets_init', create_function('', 'return register_widget("shailan_CountdownWidget");'));

if( !function_exists('get_plugin_path') ){
	function get_plugin_path($filepath){
		$plugin_path = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', $filepath);
		$plugin_path = str_replace('\\','/',$plugin_path );
		$plugin_dir  = substr($plugin_path ,0,strrpos($plugin_path ,'/'));
		$plugin_realpath = str_replace('\\','/',dirname($filepath));
		$plugin_siteurl  = get_bloginfo('wpurl');
		$plugin_siteurl  = (strpos($plugin_siteurl,'http://') === false) ? get_bloginfo('siteurl') : $plugin_siteurl;
		return $plugin_siteurl.'/wp-content/plugins/'.$plugin_dir.'/';
	}
}

