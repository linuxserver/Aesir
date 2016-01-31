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
    	
		switch($command){
			case "start":
				$this->getDockerJSON( '/containers/'.$container.'/start', 'POST' );
				break;
			case "stop":
				$this->getDockerJSON( '/containers/'.$container.'/stop', 'POST' );
				break;
			case "restart":
				$this->getDockerJSON( '/containers/'.$container.'/restart', 'POST' );
				break;
			default:
				//nothing
		}
		switch($command){
			case "start":
			case "stop":
			case "restart":
				if( $main == '1' ) redirect( 'docker' );
				else redirect( 'docker/container/'.$container );
			default:
				//nothing
		}
		
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
				$repo = ( isset( $repotag[0] ) ) ? $repotag[0] : null;
				$tag = ( isset( $repotag[1] ) ) ? $repotag[1] : null;
				$status = ( isset( $parts[4] ) ) ? $parts[4] : null;
				$ports = ($arrcount === 6) ? $parts[5] : false;
				$name = end($parts);
				$details = array( 'repository' => $repo, 'tag' => $tag, 'status' => $status, 'ports' => $ports, 'name' => $name );
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
	
	public function container_stats( $id ) {
		$json = $this->getDockerJSON( '/containers/'.$id.'/stats?stream=false' );
		return $json;
	}
	
	public function image_details( $repository_name ){
		$this->db->where( 'temp_repository', $repository_name);
		$docker = $this->db->get('templates');
		if ($docker->num_rows() > 0) {
			return $docker->row();
		} else {
			return false;
		}
	}
	
	public function custom_container_stats( $full_id, $wait = 100000 ) {
	
		$time_pre = microtime(true);
		
		$memory_usage = trim(shell_exec('cat /sys/fs/cgroup/memory/docker/'.$full_id.'/memory.usage_in_bytes'));
		$maximum_memory_usage = trim(shell_exec('cat /sys/fs/cgroup/memory/docker/'.$full_id.'/memory.max_usage_in_bytes'));
		$cpu_cores = trim(shell_exec('nproc'));
		
		$lines = explode("\n",trim(shell_exec('cat /proc/meminfo')));
		$parts = explode(":",$lines[0]);
		$total_system_memory = trim(explode(" ",trim($parts[1]))[0]) * 1024;
		
		$previous_cpu_usage = trim(shell_exec('cat /sys/fs/cgroup/cpuacct/docker/'.$full_id.'/cpuacct.usage'));
		$parts = explode(" ",trim(shell_exec('grep \'cpu \' /proc/stat')));
		$previous_system_cpu_usage = 0;
		for($i = 2;$i <= 8;$i++){
			$previous_system_cpu_usage += $parts[$i];
		}
		$previous_system_cpu_usage = $previous_system_cpu_usage * 10000000;
		
		usleep($wait);
		
		$current_cpu_usage = trim(shell_exec('cat /sys/fs/cgroup/cpuacct/docker/'.$full_id.'/cpuacct.usage'));	
		$parts = explode(" ",trim(shell_exec('grep \'cpu \' /proc/stat')));
		$current_system_cpu_usage = 0;
		for($i = 2;$i <= 8;$i++){
			$current_system_cpu_usage += $parts[$i];
		}
		$current_system_cpu_usage = $current_system_cpu_usage * 10000000;
		
		$cpu_delta = $current_cpu_usage - $previous_cpu_usage;
		$system_delta = $current_system_cpu_usage - $previous_system_cpu_usage;
		
		$total_cpu_percentage = number_format((($cpu_delta / $system_delta) * 100), 2);
		$core_cpu_percentage = number_format(((($cpu_delta / $system_delta) * $cpu_cores) * 100), 2);
		
		$time_post = microtime(true);
		
		$result = array(
			"read"=>date("Y-m-d\TH:i:s"),
			"gather_time_ms"=>intval(($time_post - $time_pre) * 1000),
			"memory_stats"=>array(
				"usage"=>$memory_usage,
				"usage_formatted"=>format_bytes($memory_usage,false,'',''),
				"usage_percent"=>number_format(($memory_usage / $total_system_memory)*100,2),
				"max_usage"=>$maximum_memory_usage,
				"max_usage_formatted"=>format_bytes($maximum_memory_usage,false,'',''),
				"max_usage_percent"=>number_format(($maximum_memory_usage / $total_system_memory)*100,2),
				"total_system_bytes"=>$total_system_memory,
				"total_system_formatted"=>format_bytes($total_system_memory,false,'',''),
			),
			"cpu_stats"=>array(
				"total_system_cores"=>$cpu_cores,
				"total_usage_percent"=>$total_cpu_percentage,
				"core_usage_percent"=>$core_cpu_percentage
			)
		);
		return $result;
	}
	
	public function live_container_stats( $full_id, $wait = 500000 ) {
		echo json_encode( $this->custom_container_stats($full_id,$wait) );
	}
	
	public function image_stats() {
		$json = $this->getDockerJSON( '/images/json?all=0' );
		return $json;
	}


    public function load_tables()
    {
    	$data = file_get_contents( 'https://tools.linuxserver.io/unraid-docker-templates.json' );
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

  			$cpu = $this->input->post('cpu');
  			if( $cpu !== 'custom' ) {
  				$cpu = ( $cpu !== '1024' ) ? ' --cpu-shares='.$cpu : '';
  			} else {
  				$cpucustom = $this->input->post('cpu_custom');
  				$cpu = ( $cpucustom !== '1024' && $cpu >= '2' ) ? ' --cpu-shares='.$cpucustom : '';
  			}

  			$memory = $this->input->post('memory');
    		$memory = ( strlen( $memory ) ) ? ' -m ' . $memory : "";	

  			$bindtime = $this->input->post('bindtime');
  			$bindtime = ( $bindtime	=== 1 ) ? 'TZ="' . $this->unraid->var2['timeZone'] . '"' : '';

  			$networking = $this->input->post('networking');
  			foreach ($networking as $port ) {
  				if ( !strlen( $port['ContainerPort'] ) ) continue;
    			
    			$ports[] = sprintf( "%s:%s/%s", $port['HostPort'], $port['ContainerPort'], $port['Protocol'] );

  			}

	  		$ports = ( count( $ports ) > 0 ) ? ' -p '.implode( ' -p ', $ports ) : '';

  			$data = $this->input->post('data');
  			foreach ($data as $volume ) {
  				if ( !strlen( $volume['ContainerDir'] ) ) continue;
    			$vmode = ( isset( $volume['read_only'] ) && $volume['read_only'] == '1' ) ? 'ro' : 'rw';

    			$volumes[] = sprintf( '"%s":"%s":%s', $volume['HostDir'], $volume['ContainerDir'], $vmode );

  			}

	  		$volumes = ( count( $volumes ) > 0 ) ? ' -v '.implode( ' -v ', $volumes ) : '';

  			$environment = $this->input->post('environment');
  			foreach ($environment as $env ) {
  				if ( !strlen( $env['Name'] ) ) continue;

    			$variables[] = sprintf( '%s="%s"', $env['Name'], $env['Value'] );

  			}


	  		if ( strlen( $bindtime ) ) {
	    		$variables[] = $cmdBindTime;
	  		}

	  		$variables = ( count( $variables ) > 0 ) ? ' -e '.implode( ' -e ', $variables ) : '';

  			$execute = $this->input->post( 'execute_command' );
  
  			$cmd = sprintf(
  				'/usr/bin/docker run -d %s %s %s %s %s %s %s %s %s', 
  				$name, 
  				$cpu, 
  				$mode, 
  				$privileged, 
  				$variables,
       			$ports, 
       			$volumes, 
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
			die( print_r( $_POST ) );
			//$run = $this->buildCommand();
			//echo $run['cmd'];
			$outputfile = 'test.txt';
			$outputfile2 = 'test2.txt';
			$pidfile = 'docker_pidfile';
			//$run = sprintf('nohup %s > %s 2>&1 & echo $! >> %s', $run['cmd'], $outputfile, $pidfile);

			$image = base64_encode($_POST['repository']);

			$cmd = 'php index.php docker pull_image '.rtrim( $image, '=' );
			//die($cmd);
			//$run = sprintf('%s 2>&1 &', $run['cmd']);
			$run = sprintf('nohup %s &> /dev/null &', $cmd);
			/*//echo "c: ".$run;

			$descriptorspec = array(
   				0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   				1 => array("file", $outputfile, "a"),  // stdout is a pipe that the child will write to
   				2 => array("pipe", $outputfile2, "a") // stderr is a file to write to
			);

		$cwd = FCPATH;
		$env = NULL;

		$process = proc_open($run, $descriptorspec, $pipes, $cwd, $env);*/



		/*$command = '/usr/bin/docker run -d --name="quassel-core" --net="bridge" -e PGID="99" -e PUID="100" -e TZ="Europe/London" -p 4242:4242/tcp -p 64443:64443/tcp -v "/mnt/user/appdata/quassel-core":"/config":rw linuxserver/quassel-core';
  $descriptorspec = array(
        0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
        2 => array("pipe", "w")    // stderr is a pipe that the child will write to
        );
    	$id = mt_rand();
    	$output = array();
    $proc = proc_open($command." 2>&1", $descriptorspec, $pipes, '/', array());
    while ($out = fgets( $pipes[1] )) {
      $out = preg_replace("%[\t\n\x0B\f\r]+%", '', $out );
      @flush();
      echo htmlentities($out) . "<br />";
      @flush();
    }
    $retval = proc_close($proc);*/
  


			exec($run);
			redirect( 'docker/download/'.rtrim( $image, '=') );
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
	
	public function edit( $docker )
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = $id;
		$data['docker_images'] = $this->docker_images();
		
		$data['container_details'] = $this->container_details( $id );
		$data['image_stats'] = $this->image_stats();
		
		$this->load->view( 'header', $header_data );
		$this->load->view( 'edit', $data );
		$this->load->view( 'footer' );
	}
	
	
	public function pull_image( $image )
	{
		$image = base64_decode( $image );
		if (! preg_match("/:[\w]*$/i", $image)) $image .= ":latest";

		//die("image: ".$image);

		$file = sha1($image);

		$fp = stream_socket_client('unix:///var/run/docker.sock', $errno, $errstr);
		if (!$fp) {
			file_put_contents( 'error.txt', "$errstr ($errno)<br />\n", FILE_APPEND  );
		} else {
			$out="POST /images/create?fromImage=$image HTTP/1.1\r\nConnection: Close\r\n\r\n";
		    fwrite($fp, $out);
		    while (!feof($fp)) {
		    	$thisline = fgets($fp, 5000);
		    	$current = json_decode( $thisline, true );
		    	file_put_contents( 'text1.txt', $thisline, FILE_APPEND  );

		    	$id = ( isset( $current['id'] ) ) ? $current['id'] : "";
		    	if($current['status'] == 'Pulling fs layer') $layers[$id] = 0;
		    	elseif($current['status'] == 'Downloading' || $current['status'] == 'Extracting') {
		    		$status = $current['status'];
		    		$total = $current['progressDetail']['total'];
      				$curr = $current['progressDetail']['current'];
      				if ($total > 0) {
        				$layers[$id] = $curr;
        				$layers_total[$id] = $total;
      				} else { // doesn't know total so set at 50%
      					$layers[$id] = 500;
      					$layers_total[$id] = 1000;
      				}
		    	} elseif($current['status'] == 'Extracting') {

		    	} elseif( $current['status'] == 'Download complete' ) {
					$layers[$id] = $layers_total[$id];
		    	}

		    	$all_current = array_sum($layers);
		    	$all_total = array_sum($layers_total);

		    	$percent = round(( $all_current / $all_total ) * 100);		    	

		    	if( !empty( $layers ) ) file_put_contents( $file, json_encode( array( 'dstatus' => $status, 'total' => $all_total, 'current' => $all_current, 'percent' => $percent ) ) );
		        //echo fgets($fp, 1024);
		    }
		    fclose( $fp );
		    //@unlink( $file );
		}		
	}
    public function process_download( $image )
    {

    	//echo 'i: '.$image;

    	if( file_exists( $image ) ) {
    		$file = file_get_contents( $image );
    	} else {
    		$file = json_encode( array( 'dstatus' => 'Initialising', 'percent' => '0' ) );
    	}
    	

    	echo $file;

    }

	public function download( $image )
	{
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'docker';
		//$data['docker'] = $this->docker_model->docker_details( $docker );
		//print_r( $this->docker_search( $data['docker']->temp_repository ));
		//print_r( $this->docker_details( $data['docker']->temp_repository ));

		$image = base64_decode( $image );
		if (! preg_match("/:[\w]*$/i", $image)) $image .= ":latest";

		$shaimage = sha1( $image );




		$data['image'] = $image;
		$data['shaimage'] = $shaimage;
		$split = explode( '/', $image );
		$data['name'] = $split[1];
		$data['repo'] = $split[0];


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

	public function container( $id, $action = null )
	{
		
		$this->docker_control( $action, $id );
		
		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = $id;
		//$data['dockers'] = $this->docker_model->docker_list( );
		$data['docker_images'] = $this->docker_images();
		//$merge = array_merge( $data['docker_images']['running'], $data['docker_images']['stopped'] );
		//$data['docker'] = $merge[$id];
		
		$data['container_details'] = $this->container_details( $id );
		$data['container_stats'] = $this->custom_container_stats( $data['container_details'][0]['Id'] );
		$data['image_detail'] = $this->image_details( $data['container_details'][0]['Config']['Image'] );
		$data['image_stats'] = $this->image_stats();
		
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
	
	public function recommended( $submenu=false, $subsubmenu=false )
	{

		$page = ( isset( $_GET['page'] ) && is_numeric( $_GET['page'] ) ) ? $_GET['page'] : 1;

		$header_data['page_title'] = __( 'Docker' );
		$data["active_menu"] = 'recommended';
		$data["sub_menu"] = $submenu;
		$data["subsub_menu"] = $subsubmenu;

		$page_limit = 20;

		$dockertotal = $this->docker_model->docker_count( $submenu, $subsubmenu );

		$data['dockers'] = $this->docker_model->docker_list( $submenu, $subsubmenu, false, $page_limit, $page );

		$this->load->library('pagination');

		$config['base_url'] = site_url( 'docker/recommended/'.$submenu.'/'.$subsubmenu.'/');
		$config['total_rows'] = $dockertotal;
		$config['per_page'] = 20;
		$config['use_page_numbers'] = true;
		$config['page_query_string'] = true;
		$config['query_string_segment'] = 'page';
		$this->pagination->initialize($config);


		$this->load->view( 'header', $header_data );
		$this->load->view( 'list', $data );
		$this->load->view( 'footer' );
	}
}
