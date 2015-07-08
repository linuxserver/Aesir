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
 * Home class
 *
 * Controller for dashboard
 *
 * @package     Aesir
 * @subpackage  Controllers
 * @author      Kode
 */
class Home extends MY_Controller {

	public function index()
	{
		$this->load->library('unraid');
		$this->load->library('linuxstat');
		$disks = $this->unraid->disks;
		
		$array_details = $this->unraid->array_details();
		
		$data = $array_details;
		
		$cpuinfo = $this->linuxstat->getCpuInfo();
		$processes = $this->linuxstat->getProcesses();
		$uptime = $this->linuxstat->getUptime();
		$memory = $this->linuxstat->getMemStat();
		$data['page_title'] = 'Dashboard';
		$data["cpuinfo"] = $cpuinfo["model name"];
		$data["processcount"] = $processes;
		$data["uptime"] = $uptime["uptime"];
		$data["load"] = $uptime["load"];
		//print_r($memory);
		$data["memory"] = round(intval($memory["MemTotal"])/1048576).'GB';
		//print_r($var);
		$this->load->view('header', $data);
		$this->load->view('home', $data);
		$this->load->view('footer', $data);
	}
	
	
	public function about()
	{

		$data["disks"] = $this->config->item("unraid_disks");
		//print_r($var);
		$this->load->view('header', $data);
		$this->load->view('about', $data);
		$this->load->view('footer', $data);
	}
	public function spin_disk($diskidx, $direction="down")
	{
		$cmd = "/root/mdcmd spin{$direction} {$diskidx}";
		//echo $cmd;
		exec($cmd);
		redirect( 'home' );
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */