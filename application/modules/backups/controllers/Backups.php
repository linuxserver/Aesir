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
 * Backups class
 *
 * Simple solution to backup remote servers
 *
 * @package     Aesir
 * @subpackage  Modules
 * @author      Kode
 */
class Backups extends MY_Controller {

	public $container = false; // docker ps and find the rsnapshot image maybe?
	public $stoperrors = false; // errors that will stop the module from working, don't display anything else
	public $notices = false; // errors or notices that are informational

    function __construct()
    {
        parent::__construct();
        $this->load->language('backups');
        $this->load->model('backups_model');
        $this->load->helper( 'docker/docker' );
        
	$defercreate = false;
	if( !file_exists( APPPATH.'database/language.db' ) ) $defercreate = true;
		
	$config['database'] = APPPATH.'database/backups.db';
	$config['dbdriver'] = 'sqlite3';
	$setdb = $this->load->database($config, true);
	$this->backups_model->db = $setdb;
	$this->db = $setdb; // without this dbforge doesn't work
		
	if( $defercreate ) $this->backups_model->create_backups_table(); // datbase is silently created if it doesn't exist, $defercreate ensures it's populated if it didn't exist to begin with
		        

    }	

    public function get_container()
    {
    	
    	$containers = getDockerJSON( '/containers/json' );
    	foreach ( $containers as $container ) {
    		if ( $container['Image'] == 'lsiodev/rsnapshot' ) return $container['Id'];
    	}
    	return false;
    }

    public function install_docker()
    {
    	if( $_POST ) {
    		//docker run -d --name=rsnapshot -v /mnt/cache/appdata/rsnapshot:/data  lsiodev/rsnapshot
    		$image = "lsiodev/rsnapshot";
    		$backup_path = $this->input->post('backup_location');
    		/*$pull = (object)array(
    			"Image" => $image,
    			"Volumes" => (object)array( 
    				"/backups" => (object)array( $backup_path ),
    				"/config" => (object)array( "/mnt/appdata/aesir-snapshot" )
    			)

    		);


    		//$build = do_post_request( '/containers/create?name=aesir-rsnapshot', $pull );
    		//$build = CallAPI( 'POST', '/containers/create?name=aesir-rsnapshot', $pull );
    		getDockerJSON( '/images/create?fromImage='.$image.'&tag=latest', 'POST' );
    		$build = getDockerJSON( '/containers/create?name=aesir-rsnapshot', 'POST', $pull );
    		$build_json = json_decode( $build );
    		getDockerJSON( '/containers/'.$build_json['Id'].'/start', 'POST' );*/
    		exec( 'docker run -d --name=aesir-rsnapshot -v /mnt/cache/appdata/aesir-rsnapshot:/config -v '.$backup_path.':/backups  '.$image );
    		redirect( 'backups' );
    	}
 		$header_data['page_title'] = __( 'Install Module' );
		$this->load->view( 'header', $header_data );

		$this->load->view( 'install_docker' );

		$this->load->view( 'footer' );   	
    }

	public function index()
	{
		$this->container = $this->get_container();
        if( $this->container === false ) {
        	$this->stoperrors[] = 'Required backup module is missing, <a href="/backups/install_docker">Install now?</a>';
        }

		$header_data['page_title'] = __( 'Backups' );
		$this->load->view( 'header', $header_data );

		if( $this->stoperrors !== false ) $this->load->view( 'stoperrors', [ 'stoperrors' => $this->stoperrors ] );
		else $this->load->view( 'index' );

		$this->load->view( 'footer' );
	}

	public function add_server()
	{
	    $this->container = $this->get_container();
        if( $this->container === false ) {
        	$this->stoperrors[] = 'Required backup module is missing, <a href="/backups/install_docker">Install now?</a>';
        }

		$header_data['page_title'] = __( 'Add Server' );
		if( $_POST ) {
			$server_ip = $this->input->post('server_address');
			$server_password = "'".$this->input->post('server_password')."'";
			unset( $output );

			$cmd = 'docker exec -t '.$this->container.' /usr/bin/expect ~/.ssh/addserver.expect root '.$server_ip.' '.$server_password;
			echo "c: ".$cmd;
			exec( $cmd, $output );
			print_r( $output );
			if( $this->successfully_added( $output ) ) {
				// add server to database
				$name = $this->input->post('server_name');
				$address = $this->input->post('server_address');
				$this->backups_model->add_server( $name, $address );
			} else {
				// something went wrong
			}
		}
		$this->load->view( 'header', $header_data );
		if( $this->stoperrors !== false ) $this->load->view( 'stoperrors', [ 'stoperrors' => $this->stoperrors ] );
		else $this->load->view( 'add_server' );
		$this->load->view( 'footer' );

	}
	private function successfully_added( $output ) {
		$success = array(
			'Number of key(s) added: 1',
			'/usr/bin/ssh-copy-id: WARNING: All keys were skipped because they already exist on the remote system.'
		);
		return (bool)array_intersect( $success, $output );
	}
}
