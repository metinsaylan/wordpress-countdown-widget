<?php

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

// Added for backward compatibility
if( ! function_exists('wp_doing_ajax') ){
	function wp_doing_ajax() {
		return apply_filters( 'wp_doing_ajax', defined( 'DOING_AJAX' ) && DOING_AJAX );
	}
}
