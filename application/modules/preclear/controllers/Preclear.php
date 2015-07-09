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
 * Preclear class
 *
 * Controller for Preclear module
 *
 * @package     Aesir
 * @subpackage  Modules
 * @author      Kode
 */
class Preclear extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->language('preclear');
    }	

	public function index()
	{
		$header_data['page_title'] = __( 'Preclear' );
		$data["active_menu"] = 'preclear';
		$this->load->view( 'header', $header_data );
		$this->load->view( 'preclear', $data );
		$this->load->view( 'footer' );
	}
}
