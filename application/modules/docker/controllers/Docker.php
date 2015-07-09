<?php defined('BASEPATH') OR exit('No direct script access allowed');
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
 * Docker class
 *
 * Class for all the docker functions
 *
 * @package     Aesir
 * @subpackage  Modules
 * @author      Kode
 */
class Docker extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->language('docker');
    }	

	public function index()
	{
		$header_data['page_title'] = __( 'Docker' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'index' );
		$this->load->view( 'footer' );
	}
	
	public function docker_list()
	{
		$header_data['page_title'] = __( 'Docker' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'list' );
		$this->load->view( 'footer' );
	}
}
