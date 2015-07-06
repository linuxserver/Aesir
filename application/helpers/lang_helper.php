<?php

function load_language() {
	$CI =& get_instance();
	if( file_exists( APPPATH.'database/language.db' ) ) {
		$config['database'] = APPPATH.'database/language.db';
		$config['dbdriver'] = 'sqlite3';
		$CI->load->database($config);
		$CI->load->model('settings_model');
		$lang = $CI->settings_model->get_current_lang();
	} else {
		$lang = $CI->config->item( 'language' );
	}
	$CI->lang->load( 'aesir', $lang );
}

function __( $identifier, $variables=false ) {
	$CI =& get_instance();
	$text = $CI->lang->line( $identifier );
	if( is_array( $variables ) ) {
		$text = vsprintf( $text, $variables );
	}
	$text = ( $text ) ? $text : $identifier;
	return $text;
}

function _e( $identifier, $variables=false ) {
	echo __( $identifier );
}
