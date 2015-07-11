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

		//$defercreate = false;
		//if( !file_exists( APPPATH.'database/docker.db' ) ) $defercreate = true;
		
		$config['database'] = APPPATH.'database/docker2.db';
		$config['dbdriver'] = 'sqlite3';
		$dockdb = $this->load->database($config, true);

		$this->load->model('docker_model');
		$this->docker_model->db = $dockdb;
		
		//if( $defercreate ) {
		//	$this->docker_model->create_docker_table(); // datbase is silently created if it doesn't exist, $defercreate ensures it's populated if it didn't exist to begin with
		//	$this->load_tables();
    	//}


    }	

    public function docker_control( $command, $container, $main = false ) 
    {
    	$container = preg_replace('/[^\da-z]/i', '', $container);
    	switch( $command ) {
    		case 'stop':
    			$cmd = 'docker stop --time=10 '.$container;
    			break;
    		case 'start':
    			$cmd = 'docker start '.$container;
    			break;
    	}
    	//die(base64_decode( $return ));
    	exec( $cmd, $output, $return );
    	if( $main == '1' ) redirect( 'docker' );
    	else redirect( 'docker/container/'.$container );
    }


    public function docker_images() 
    {
    	//$cmd = 'docker images';
    	$cmd = 'docker ps -a';
		//unset( $output );
		exec( $cmd, $output, $return );
		//var_dump($return);
		if( $return === 0) {
			$running = array();
			$stopped = array();
			foreach( $output as $key => $dock ) {
				$parts = preg_split('~  +~', $dock, -1,  PREG_SPLIT_NO_EMPTY);

				if( $key == '0') continue;
				$arrcount = count($parts);
				$repotag = explode(':', $parts[1]);
				$repo = $repotag[0];
				$tag = $repotag[1];
				$status = $parts[4];
				$ports = ($arrcount === 6) ? $parts[5] : false;
				$name = end($parts);
				$details = array( 'repository' => $repo, 'tag' => $tag, 'status' => $status, 'ports' => $ports, 'name' => $name);
				if( substr( $status, 0, 2 ) == 'Up' ) $running[$parts[0]] = $details;
				else $stopped[$parts[0]] = $details;

			}

			uasort($running, function($a, $b) {
			    return strnatcasecmp ($a['name'], $b['name']);
			});
			uasort($stopped, function($a, $b) {
			    return strnatcasecmp ($a['name'], $b['name']);
			});

		}
		return array( 'running' => $running, 'stopped' => $stopped );
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
		$data['dockers'] = $this->docker_model->docker_list( );
		$data['docker_images'] = $this->docker_images();

		$this->load->view( 'header', $header_data );
		$this->load->view( 'index', $data );
		$this->load->view( 'footer' );
	}
	public function container( $id )
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = $id;
		//$data['dockers'] = $this->docker_model->docker_list( );
		$data['docker_images'] = $this->docker_images();
		$merge = array_merge( $data['docker_images']['running'], $data['docker_images']['stopped'] );

		$data['docker'] = $merge[$id];

		$this->load->view( 'header', $header_data );
		$this->load->view( 'container', $data );
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
