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
 * Rebame class
 *
 * What does it do
 *
 * @package     Aesir
 * @subpackage  Modules
 * @author      Kode
 */
class Rename extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->language('rename');
    }	

	public function index()
	{
		$header_data['page_title'] = __( 'Rename' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'index' );
		$this->load->view( 'footer' );
	}
}
