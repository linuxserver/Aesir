<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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