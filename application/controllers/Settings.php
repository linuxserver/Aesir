<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

	public function index($section=false)
	{
		$data["tags"] = array("dashboard" => "Dashboard", "users" => "Users");
		$data["section"] = $section;
		$data["disks"] = parse_ini_file($this->config->item("ini_path")."disks.ini", TRUE);
		//print_r($var);
		$this->load->view('header', $data);
		$this->load->view('settings', $data);
		$this->load->view('footer', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */