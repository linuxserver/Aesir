<?php

function load_language() {
	$CI =& get_instance();
	$lang = $CI->config->item( 'language' );
	$CI->lang->load( 'aesir', $lang );
}

function __( $identifier, $variables=false ) {
	$CI =& get_instance();
	$text = $CI->lang->line( $identifier );
	if( is_array( $variables ) ) {
		$text = vsprintf( $text, $variables );
	}
	//$text = ( $text ) ? $text : $identifier;
	return $text;
}

function _e( $identifier, $variables=false ) {
	echo __( $identifier );
}
