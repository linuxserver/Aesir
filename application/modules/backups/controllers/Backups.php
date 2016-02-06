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
 * Backups class
 *
 * Simple solution to backup remote servers
 *
 * @package     Aesir
 * @subpackage  Modules
 * @author      Kode
 */
class Backups extends MY_Controller {

	public $container = '3ff9ca9f3d76';

    function __construct()
    {
        parent::__construct();
        $this->load->language('backups');
    }	

	public function index()
	{
		$header_data['page_title'] = __( 'Backups' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'index' );
		$this->load->view( 'footer' );
	}
}
