<?php

// Added for backward compatibility
if( ! function_exists('wp_doing_ajax') ){
	function wp_doing_ajax() {
		return apply_filters( 'wp_doing_ajax', defined( 'DOING_AJAX' ) && DOING_AJAX );
	}
}
