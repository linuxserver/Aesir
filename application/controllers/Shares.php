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
 * Shares class
 *
 * Controller for shares
 *
 * @package     Aesir
 * @subpackage  Controllers
 * @author      Kode
 */
class Shares extends MY_Controller {

	public function index()
	{

		$data["disks"] = parse_ini_file($this->config->item("ini_path")."disks.ini", TRUE);
		//print_r($var);
		$this->load->view('header', $data);
		$this->load->view('shares', $data);
		$this->load->view('footer', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */