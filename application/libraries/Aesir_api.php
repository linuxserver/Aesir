<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Aesir
 *
 * A redesigned WebGUI for unRAID
 *
 * @package     Aesir
 * @author      Kode
 * @copyright   Copyright (c) 2014 Kode (admin@coderior.com)
 * @link        http://coderior.com
 * @since       Version 1.0
 */

 // --------------------------------------------------------------------

/**
 * Api class
 *
 * Library for dealing with API requests
 *
 * @package     Aesir
 * @subpackage  Libraries
 * @author      Kode
 */

class Aesir_api {

	private $_code = 200;

	public function __construct( $data ) 
	{
		//$CI =& get_instance();
		//die(print_r($CI->load->vars()));
		if( isset( $_GET['api'] ) && $_GET['api'] === '1' ) {
			$this->response( $this->json( $data ), 200 );
		} else {
			return;
		}

	}
	
	private function json($data)
	{
		if(is_array($data)){
			return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}
	}
		
	public function response($data,$status,$format="json")
	{
		$this->_code = ($status)?$status:200;
		$this->set_headers($format);
		echo $data;
		exit;
	}


	private function get_content_type($type)
	{
		switch($type) {
			case "xml": return "application/xml"; break;
			case "php": return "text/plain"; break;
			case "json": default: return "application/json"; break;
		}
	}
	
	public function get_request_method(){
		return $_SERVER['REQUEST_METHOD'];
	}	
		
	private function set_headers($format)
	{
		header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
		header("Content-Type:".$this->get_content_type($format)."; charset=utf-8");
	}


	private function get_status_message()
	{
		$status = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed',  
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return ($status[$this->_code])?$status[$this->_code]:$status[500];
	}	

}

?>