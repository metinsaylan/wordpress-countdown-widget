<?php

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

function shailan_CountdownWidget_shortcode_up( $atts, $content = null ){

	$atts['direction'] = 'up';
	return shailan_CountdownWidget_shortcode( $atts, $content );

} add_shortcode( 'countup', 'shailan_CountdownWidget_shortcode_up');
