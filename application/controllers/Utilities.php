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
 * Utilities class
 *
 * Controller for utilities
 *
 * @package     Aesir
 * @subpackage  Controllers
 * @author      Kode
 */
class Utilities extends MY_Controller {

	public function index()
	{

		$data["disks"] = parse_ini_file($this->config->item("ini_path")."disks.ini", TRUE);
		//print_r($var);
		$this->load->view('header', $data);
		$this->load->view('utilities', $data);
		$this->load->view('footer', $data);
	}

	public function preclear() {
		$available_disks = shell_exec('uploaded/preclear_disk.sh -l');
		$split_disks = explode("/dev/", $available_disks);
		array_shift($split_disks); // remove first array item as it just has intro text
		$split_disks = array_map('trim',$split_disks); // get rid of all the whitespace

		$data = array();
		$data["disks"] = $split_disks;
		$data["disk_extra"] = parse_ini_file($this->config->item("ini_path")."disks.ini", TRUE);
		//die(print_r($data["disk_extra"]));
		$this->load->view('header', $data);
		$this->load->view('preclear', $data);
		$this->load->view('footer', $data);
	}

	public function start_preclear($disk) {
		$command = "uploaded/preclear_disk.sh /dev/".$disk;
		$outputFile = "uploaded/preclear_".$disk.".txt";
		$pid = shell_exec(sprintf(
            '%s > %s 2>&amp;1 &amp; echo $!',
            $command,
            $outputFile
        ));
        $array["preclear_".$disk] = $pid;
        $this->session->set_userdata($array);
        redirect("/index.php/utilities/preclear/");
	}



    public function getPid()
    {
        return $this->pid;
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */