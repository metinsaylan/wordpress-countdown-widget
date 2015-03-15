<?php 
/*
Plugin Name: Countdown Widget
Plugin URI: http://shailan.com/plugins/countdown/
Description: A beautiful jquery countdown widget. Allows Multiple instances, Shortcode usage, and Customizations. Powered by: <a href="http://shailan.com" title="Wordpress, Web design, Freelancing">shailan.com</a>.
Version: 2.5.7
Author: Matt Say
Author URI: http://shailan.com/
*/

global $countdown_shortcode_ids;

/**
 * Shailan Countdown Widget Class
 */
class shailan_CountdownWidget extends WP_Widget {
    /** constructor */
    function shailan_CountdownWidget() {
		$widget_ops = array('classname' => 'shailan_CountdownWidget', 'description' => __( 'jQuery Countdown/up widget' ) );
		$this->WP_Widget('shailan-countdown-widget', __('CountDown/Up Timer'), $widget_ops);
		$this->alt_option_name = 'widget_shailan_countdown';	
		
		// localization
		$lang = substr( get_bloginfo('language'), 0, 2 );

		wp_enqueue_script('jquery');
		wp_enqueue_script('countdown', plugins_url('js/jquery.countdown.min.js', __FILE__), 'jquery', '1.0', false);
		if($lang!='en' && file_exists( plugin_dir_path(__FILE__) . 'js/jquery.countdown-' . $lang . '.js') ){
			wp_enqueue_script('countdown-l10n', plugins_url('js/jquery.countdown-' . $lang . '.js',__FILE__), 'countdown', '1.0', false);
		}
		wp_enqueue_style('countdown-style', plugins_url('css/jquery.countdown.css', __FILE__), '', '1.1', false);
		
		add_action( 'wp_head', array(&$this, 'header'), 10, 1 );	
		//add_action( 'wp_footer', array(&$this, 'footer'), 10, 1 );	
		
		$current_offset = get_option('gmt_offset');
		
		$this->defaults = array(
			'title'		=>'',
			'event'		=> '',
			'month'		=> date( 'm' ),
			'day'		=> date( 'd' ),
			'hour'		=> ( gmdate( 'H' ) + $current_offset + 1 ),
			'minutes'	=> date( 'i' ),
			'seconds'	=> date( 's' ),
			'year'		=> date( 'Y' ),
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
	
    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $post, $countdown_shortcode_ids;
	
        extract( $args );
		
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );
		
		// Get a new id
		$countdown_shortcode_ids++;
		
		if(!empty($instance['link'])){ $link = (bool) $link; }
		// $height = 80*$width/250;
		
		$path = get_plugin_path(__FILE__);
		
		$style = "";
		
		// If this is not a widget
		if(isset($isWidget) && false === $isWidget){
			$style=" style=\"";
		
			// Fix HEX color codes
			if( (preg_match('/^#[a-f0-9]{6}$/i', $color) != 1 && preg_match('/^[a-f0-9]{6}$/i', $color) ) || (preg_match('/^#[a-f0-9]{3}$/i', $color) != 1 && preg_match('/^[a-f0-9]{3}$/i', $color) ) ){
				$color = "#" . $color;
			} 
			if( (preg_match('/^#[a-f0-9]{6}$/i', $bgcolor) != 1 && preg_match('/^[a-f0-9]{6}$/i', $bgcolor) ) || (preg_match('/^#[a-f0-9]{3}$/i', $bgcolor) != 1 && preg_match('/^[a-f0-9]{3}$/i', $bgcolor)) ){
				$bgcolor = "#" . $bgcolor;
			}
		
			if(!empty($bgcolor)){ 
				$style .= "background-color:".$bgcolor.";"; 
			} 
			if(!empty($color)){ $style .=  " color:".$color. ";"; }
			if(!empty($width) && $width>0){ $style .= " width:".$width."px;"; }
			if(!empty($radius) && $radius>0){ $style .= " border-radius:".$radius."px;"; }
			// if(!empty($height) && $height>0){ $style .= " height:".$height."px;"; } // Gotta work on this
				$style .= " margin:0px auto; \"";

		}
			
			?>
				  <?php echo $before_widget; ?>
					<?php if ( $title )
							echo $before_title . $title . $after_title;
					?>

				<div id="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>" class="shailan-countdown-<?php echo $this->number ?> countdown" <?php echo $style; ?>></div>
				
				<?php				
				if(!$link){echo '<div '.$style.'><small><a href="http://shailan.com/plugins/countdown/" title="Get Countdown/up Timer Widget for WordPress" style="float:right;">&uarr; Get this</a></small></div>';};
				?>
				
<script type="text/javascript"> 
<!--//
// Dom Ready
	jQuery(document).ready(function($) {
		var event_month = <?php echo $month; ?> - 1;
		desc = '<?php echo $event; ?>';
		eventDate = new Date(<?php echo $year; ?>, event_month, <?php echo $day; ?>, <?php echo $hour; ?>, <?php echo $minutes; ?>, <?php echo $seconds; ?>, 0);
		$('#shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>').countdown({
			<?php if($direction == 'down'){ ?>until<?php } else { ?>since<?php } ?>: eventDate, 
			description: desc,  
			format: '<?php echo $format; ?>'<?php if($timezone != 'SCW_NONE'){ ?>,
			timezone: <?php echo $timezone; } ?>
		}); 
	});
//-->
</script>				
				  <?php echo $after_widget; ?>
			<?php
		
    }

    function update($new_instance, $old_instance) {				
		// Check month
		if( $new_instance['month'] < 1 ) { $new_instance['month'] = '1'; }
		if( $new_instance['month'] > 12 ) { $new_instance['month'] = '12'; }
		if( $new_instance['day'] < 1 ) { $new_instance['day'] = '1'; }
		if( $new_instance['timezone'] == '' ){ $new_instance['timezone'] = 'SCW_NONE'; }
		
        return $new_instance;
    }

    function form($instance) {
		global $post, $countdown_shortcode_ids;
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );
		
		$event = esc_attr($event);
		
		if(!empty($instance['link'])){ $link = (bool) $link; }
		$height = 80*$width/250;
		
		$countdown_shortcode_ids++;
	
        ?>
		<p><small>Preview:</small></p>
		<div id="countdown-preview">
			<div id="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?>" class="shailan-countdown-<?php echo $this->number . "_" . $countdown_shortcode_ids; ?> countdown"></div>
			<script type="text/javascript"> 
			<!--//
			// Dom Ready
				jQuery(document).ready(function($) {
					var event_month = <?php echo $month; ?> - 1;
					desc = '<?php echo $event; ?>';
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

				});
			//-->
			</script>
		</div>		
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?> <small><a href="http://shailan.com/plugins/countdown/help/#title" target="_blank" rel="external">(?)</a></small> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
		<p><label for="<?php echo $this->get_field_id('event'); ?>"><?php _e('Event Title:'); ?> <small><a href="http://shailan.com/plugins/countdown/help/#event-title" target="_blank" rel="external">(?)</a></small> <input class="widefat" id="<?php echo $this->get_field_id('event'); ?>" name="<?php echo $this->get_field_name('event'); ?>" type="text" value="<?php echo $event; ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('direction'); ?>"> <?php _e('Count Down/Up :'); ?></label>
			<select name="<?php echo $this->get_field_name('direction'); ?>" id="<?php echo $this->get_field_id('direction'); ?>" >
				<option value="down" <?php if($direction == "down") { ?> selected="selected" <?php } ?>>Down</option>
				<option value="up" <?php if($direction == "up") { ?> selected="selected" <?php } ?>>Up</option>
			</select> <a href="http://shailan.com/plugins/countdown/help/#direction">(?)</a>
		</p>
		
		<p><label for="<?php echo $this->get_field_id('month'); ?>"><?php _e('Date:'); ?></label><input id="<?php echo $this->get_field_id('month'); ?>" name="<?php echo $this->get_field_name('month'); ?>" type="text" value="<?php echo $month; ?>" size="2" maxlength="2" />/<input id="<?php echo $this->get_field_id('day'); ?>" name="<?php echo $this->get_field_name('day'); ?>" type="text" value="<?php echo $day; ?>" size="2" maxlength="2" />/<input id="<?php echo $this->get_field_id('year'); ?>" name="<?php echo $this->get_field_name('year'); ?>" type="text" value="<?php echo $year; ?>" size="4" maxlength="4" /><br /> 
		<small>MM DD YYYY</small> <small><a href="http://shailan.com/plugins/countdown/help/#date" target="_blank" rel="external">(?)</a></small></p>
		
		<p><label for="<?php echo $this->get_field_id('hour'); ?>"><?php _e('Time:'); ?></label><input id="<?php echo $this->get_field_id('hour'); ?>" name="<?php echo $this->get_field_name('hour'); ?>" type="text" value="<?php echo $hour; ?>" size="2" maxlength="2" />:<input id="<?php echo $this->get_field_id('minutes'); ?>" name="<?php echo $this->get_field_name('minutes'); ?>" type="text" value="<?php echo $minutes; ?>" size="2" maxlength="2" />:<input id="<?php echo $this->get_field_id('seconds'); ?>" name="<?php echo $this->get_field_name('seconds'); ?>" type="text" value="<?php echo $seconds; ?>" size="4" maxlength="4" /><br /> 
		<small>HH:MM:SS</small> <small><a href="http://shailan.com/plugins/countdown/help/#time" target="_blank" rel="external">(?)</a></small></p>
		
		<p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format:'); ?> <input id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" class="widefat" type="text" value="<?php echo $format; ?>" size="10" maxlength="8" /></label><br /> 
		<small>(Default : yowdHMS)</small> <small><a href="http://shailan.com/plugins/countdown/help/#format" target="_blank" rel="external">(?)</a></small> </p>
		
		<p><label for="<?php echo $this->get_field_id('timezone'); ?>"><?php _e('Timezone :'); ?>
			<select name="<?php echo $this->get_field_name('timezone'); ?>" id="<?php echo $this->get_field_id('timezone'); ?>" >
			<?php 
				foreach ( $this->timezones as $key=>$name ) {
					$option = '<option value="'. $key .'" '. ( '_'.$timezone == '_'.$key ? ' selected="selected"' : '' ) .'>';
					$option .= $name; 
					$option .= '</option>\n';
					echo $option;
				} 
			?>
			</select></label> <a href="http://shailan.com/plugins/countdown/help/#timezone">(?)</a></p>
		
		<p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color:'); ?> <input id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" class="widefat" type="text" value="<?php echo $color; ?>" size="6" /></label> <small><a href="http://shailan.com/plugins/countdown/help/#color" target="_blank" rel="external">(?)</a></small></p>
		
		<p><label for="<?php echo $this->get_field_id('bgcolor'); ?>"><?php _e('Background color:'); ?> <input id="<?php echo $this->get_field_id('bgcolor'); ?>" name="<?php echo $this->get_field_name('bgcolor'); ?>" class="widefat" type="text" value="<?php echo $bgcolor; ?>" size="6" /></label> <small><a href="http://shailan.com/plugins/countdown/help/#background-color" target="_blank" rel="external">(?)</a></small></p>
		
		<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?> <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" size="4" maxlength="4" />px</label> <small><a href="http://shailan.com/plugins/countdown/help/#width" target="_blank" rel="external">(?)</a></small></p>
		
		<p><label for="<?php echo $this->get_field_id('radius'); ?>"><?php _e('Border Radius:'); ?> <input id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>" type="text" value="<?php echo $radius; ?>" size="4" maxlength="4" />px</label> <small><a href="http://shailan.com/plugins/countdown/help/#border-radius" target="_blank" rel="external">(?)</a></small></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>"<?php checked( $link ); ?> />
		<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e( 'Remove link' ); ?></label> <small><a href="http://shailan.com/plugins/countdown/help/#remove-link" target="_blank" rel="external">(?)</a></small></p>
		
		<div class="widget-control-actions">
			<p><small>Powered by <a href="http://shailan.com/wordpress/plugins/countdown" title="Wordpress Tips and tricks, Freelancing, Web Design">Shailan.com</a> | <a href="http://shailan.com/wordpress/" title="Get more wordpress widgets and themes">Get more..</a></small></p>
		</div>
			
<div class="clear"></div>
        <?php 
		
	}
	
	function header($instance){
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
				echo "\n</style>\n";
			}
		}
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
		if (($timestamp = strtotime( $args['date'] )) !== false) {
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

if(!function_exists('get_plugin_path')){
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

