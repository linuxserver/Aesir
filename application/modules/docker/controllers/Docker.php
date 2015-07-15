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
		$this->db = $dockdb;
		
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
		$running = array();
		$stopped = array();

    	//$cmd = 'docker images';
    	$cmd = 'docker ps -a';
		//unset( $output );
		exec( $cmd, $output, $return );
		//var_dump($return);
		if( $return === 0) {
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

	private function unchunk($result) {
		return preg_replace_callback(
			'/(?:(?:\r\n|\n)|^)([0-9A-F]+)(?:\r\n|\n){1,2}(.*?)'
			.'((?:\r\n|\n)(?:[0-9A-F]+(?:\r\n|\n))|$)/si',
			create_function('$matches','return hexdec($matches[1]) == strlen($matches[2]) ? $matches[2] :$matches[0];'), $result);
	}

	private function getDockerJSON($url, $method = "GET"){
		$fp = stream_socket_client('unix:///var/run/docker.sock', $errno, $errstr);
		if ($fp === false) {
			echo "Couldn't create socket: [$errno] $errstr";
			return NULL;
		}
		$out="$method {$url} HTTP/1.1\r\nConnection: Close\r\n\r\n";
		fwrite($fp, $out);
		// Strip headers out
		while (($line = fgets($fp)) !== false) {
			if (rtrim($line) == '') {
				break;
			}
		}
		$data = '';
		while (($line = fgets($fp)) !== false) {
			$data .= $line;
		}
		fclose($fp);
		$data = $this->unchunk($data);
		$json = json_decode( $data, true );
		if ($json === null) {
			$json = array();
		} else if (!array_key_exists(0, $json) && !empty($json)) {
			$json = [ $json ];
		}
		return $json;
	}

	public function docker_search( $term ) {
		$json = $this->getDockerJSON( '/images/search?term='.$term );
		return $json;
	}

	public function docker_details( $repo ) {
		$json = $this->getDockerJSON( '/images/'.$repo.'/json' );
		return $json;
	}

	public function container_details( $id ) {
		$json = $this->getDockerJSON( '/containers/'.$id.'/json' );
		return $json;
	}


    public function load_tables()
    {
    	$data = file_get_contents( 'https://fanart.tv/webservice/unraid/docker.php' );
    	$data_json = json_decode( $data, true );
    	$this->docker_model->load_tables( $data_json );
    }

    public function buildCommand() {
    	if( isset( $_POST ) && !empty( $_POST ) ) {

    		$this->load->library('unraid');

    		$ports = array();
    		$volumes = array();
    		$variables = array();

    		$name = $this->input->post('name');
    		$name = ( strlen( $name ) ) ? '--name="' . $name . '"' : "";

  			$privileged = $this->input->post('privileged');
  			$privileged = ( $privileged	=== 1) ? '--privileged="true"' : "";
  			
  			$repository = $this->input->post('repository');

  			$mode = $this->input->post('nettype');
  			$mode = '--net="'.strtolower( $mode ).'"';

  			$bindtime = $this->input->post('bindtime');
  			$bindtime = ( $bindtime	=== 1 ) ? 'TZ="' . $this->unraid->var2['timeZone'] . '"' : '';

  			$networking = $this->input->post('networking');
  			foreach ($networking as $port ) {
  				if ( !strlen( $port['ContainerPort'] ) ) continue;
    			
    			$ports[] = sprintf( "%s:%s/%s", $port['HostPort'], $port['ContainerPort'], $port['Protocol'] );

  			}

  			$data = $this->input->post('data');
  			foreach ($data as $volume ) {
  				if ( !strlen( $volume['ContainerDir'] ) ) continue;
    			$vmode = ( isset( $volume['read_only'] ) && $volume['read_only'] == '1' ) ? 'ro' : 'rw';

    			$volumes[] = sprintf( '"%s":"%s":%s', $volume['HostDir'], $volume['ContainerDir'], $vmode );

  			}

  			$environment = $this->input->post('environment');
  			foreach ($environment as $env ) {
  				if ( !strlen( $env['Name'] ) ) continue;

    			$variables[] = sprintf( '%s="%s"', $env['Name'], $env['Value'] );

  			}


	  		if ( strlen( $bindtime ) ) {
	    		$variables[] = $cmdBindTime;
	  		}

  			$execute = $this->input->post( 'execute_command' );
  
  			$cmd = sprintf(
  				'/usr/bin/docker run -d %s %s %s %s %s %s %s %s', 
  				$name, 
  				$mode, 
  				$privileged, 
  				' -e '.implode( ' -e ', $variables ),
       			' -p '.implode( ' -p ', $ports ), 
       			' -v '.implode( ' -v ', $volumes ), 
       			$execute, 
       			$repository
       		);
  			$cmd = preg_replace( '/\s+/', ' ', $cmd );
  			return array( 'cmd' => $cmd, 'name' => $name, 'repository' => $repository );
    	}
    }

	public function install( $docker )
	{

		if( isset( $_POST ) && !empty( $_POST ) ) {
			$run = $this->buildCommand();
			echo $run['cmd'];
			die();
		}

		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'docker';
		$data['docker'] = $this->docker_model->docker_details( $docker );
		$data['environment'] = unserialize(base64_decode( $data['docker']->temp_environment ));
		$data['networking'] = unserialize(base64_decode( $data['docker']->temp_networking ));
		$data['data'] = unserialize(base64_decode( $data['docker']->temp_data ));
		//print_r( $this->docker_search( $data['docker']->temp_repository ));
		//print_r( $this->docker_details( $data['docker']->temp_repository ));

		$this->load->view( 'header', $header_data );
		$this->load->view( 'install', $data );
		$this->load->view( 'footer' );
	}

	public function download( $docker )
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'docker';
		$data['docker'] = $this->docker_model->docker_details( $docker );
		//print_r( $this->docker_search( $data['docker']->temp_repository ));
		//print_r( $this->docker_details( $data['docker']->temp_repository ));

		$this->load->view( 'header', $header_data );
		$this->load->view( 'download', $data );
		$this->load->view( 'footer' );
	}

	public function search()
	{
		$term = $_GET['s'];
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'docker';
		$data['dockers'] = $this->docker_search( $term );
		$data['docker_images'] = $this->docker_images();

		$this->load->view( 'header', $header_data );
		$this->load->view( 'search', $data );
		$this->load->view( 'footer' );
	}

	public function index()
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'docker';
		$data['dockers'] = $this->docker_model->docker_list( false, false, true );
		$data['docker_images'] = $this->docker_images();

		$this->load->view( 'header', $header_data );
		$this->load->view( 'index', $data );
		$this->load->view( 'footer' );
	}
	
	public function folder( $dir=false ) {
		$dir = ($dir===false) ? FCPATH : $dir;
		$dirs = scan( $dir );
		print_r( $dirs );
	}

	public function container( $id )
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = $id;
		//$data['dockers'] = $this->docker_model->docker_list( );
		$data['docker_images'] = $this->docker_images();
		//$merge = array_merge( $data['docker_images']['running'], $data['docker_images']['stopped'] );

		//$data['docker'] = $merge[$id];

		$data['container_details'] = $this->container_details( $id );
		$image = $data['container_details'][0]['Config']['Image'];
		//$data['docker_details'] = $this->docker_details( $image );
		print_r( $this->getDockerJSON( '/images/ubuntu/json' ) );
		//print_r( $data['container_details'] );
		//print_r( $data['docker_details'] );
		//die();
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
