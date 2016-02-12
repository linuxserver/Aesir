<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Aesir v1.0
 *
 * Web GUI for unRAID
 *
 * @package     Aesir v1.0
 * @author      Kode
 * @copyright   Copyright (c) 2015 coderior.com
 * @link        http://coderior.com
 * @since       Version 1.0
 */
 // --------------------------------------------------------------------
/**
 * Backups_model class
 *
 * Model for backups
 *
 * @package     Aesir v1.0
 * @subpackage  Models
 * @author      Kode
 */
class Backups_model extends CI_Model {
	public $db;
	public function __construct() {        
	    parent::__construct();
	}
	
    /**
     * create backups table
     *
     * @access	public
     * @return void
     */
	public function create_backups_table() {
		$this->load->dbforge();
		$fields = array(
      'server_id' => array(
				'type' => 'INT',
				'constraint' => '6',
			),
      'server_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
      'server_address' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			),
		);		
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('server_id', true);
		$this->dbforge->create_table('servers');
		
	}

	public function add_server( $name, $address )
	{

		$data = array(
			'server_name' => $name,
			'server_address' => $address
		);
	    $this->db->insert('servers',$data);

	}

}
