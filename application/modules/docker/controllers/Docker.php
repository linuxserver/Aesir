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
 * Docker class
 *
 * Class for all the docker functions
 *
 * @package     Aesir
 * @subpackage  Modules
 * @author      Kode
 */
class Docker extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->language('docker');
        $this->load->model('docker_model');

		$this->db->close(); // have to close the connection otherwise it uses the wrong table

		$defercreate = false;
		if( !file_exists( APPPATH.'database/docker.db' ) ) $defercreate = true;
		
		$config['database'] = APPPATH.'database/docker.db';
		$config['dbdriver'] = 'sqlite3';
		$this->load->database($config);
		
		if( $defercreate ) {
			$this->docker_model->create_docker_table(); // datbase is silently created if it doesn't exist, $defercreate ensures it's populated if it didn't exist to begin with
			$this->load_tables();
    	}
    }	

    public function load_tables()
    {
    	$data = file_get_contents( 'https://fanart.tv/webservice/unraid/docker.php' );
    	$data_json = json_decode( $data, true );
    	$this->docker_model->load_tables( $data_json );
    }

	public function index()
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'docker';
		$this->load->view( 'header', $header_data );
		$this->load->view( 'index', $data );
		$this->load->view( 'footer' );
	}
	
	public function docker_list( $submenu=false, $subsubmenu=false )
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'docker_list';
		$data["sub_menu"] = $submenu;
		$data["subsub_menu"] = $subsubmenu;
		$data['dockers'] = $this->docker_model->docker_list( $submenu, $subsubmenu );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'list', $data );
		$this->load->view( 'footer' );
	}
}
