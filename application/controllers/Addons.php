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
 * Addons class
 *
 * Controller for addons
 *
 * @package     Aesir
 * @subpackage  Controllers
 * @author      Kode
 */

class Addons extends MY_Controller {

	public function index($section=false)
	{
		//print_r($var);
		$data = array();
		$data["tags"] = array("usenet" => "Usenet", "media" => "Media", "utilities" => "Utilities", "system" => "System", "backup" => "Backup");
		$data["section"] = $section;
		$this->load->view('header', $data);
		$this->load->view('addons', $data);
		$this->load->view('footer', $data);
	}
	public function category($section=false)
	{
		//print_r($var);
		$data = array();
		$data["tags"] = array("usenet" => "Usenet", "media" => "Media", "utilities" => "Utilities", "system" => "System", "backup" => "Backup");
		$data["section"] = $section;
		$this->load->view('header', $data);
		$this->load->view('addons', $data);
		$this->load->view('footer', $data);
	}
}

/* End of file addons.php */
/* Location: ./application/controllers/addons.php */