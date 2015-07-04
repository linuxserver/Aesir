<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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