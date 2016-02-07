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

	public $container = '7c14f99c9f06'; // docker ps and find the rsnapshot image maybe?

    function __construct()
    {
        parent::__construct();
        $this->load->language('backups');
    }	

	public function index()
	{
		$header_data['page_title'] = __( 'Backups' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'index' );
		$this->load->view( 'footer' );
	}

	public function add_server()
	{
		$header_data['page_title'] = __( 'Add Server' );
		if( $_POST ) {
			$server_ip = $this->input->post('server_address');
			$server_password = $this->input->post('server_password');
			unset( $output );
			$cmd = "docker exec -it ".$this->container." bash -c '/usr/bin/expect ~/.ssh/addserver.expect root ".$server_ip." ".$server_password."'", $output;
			echo "c: ".$cmd;
			exec( $cmd );
			print_r( $output );
		}
		$this->load->view( 'header', $header_data );
		$this->load->view( 'add_server' );
		$this->load->view( 'footer' );

	}
}
