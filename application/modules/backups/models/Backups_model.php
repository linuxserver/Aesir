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
	public function create_backups_table()
	{
		$this->load->dbforge();
		$fields = array(
      'server_id' => array(
				'type' => 'INT',
				'constraint' => '6',
				'auto_increment' => TRUE
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

		$fields = array(
      'settings_id' => array(
				'type' => 'INT',
				'constraint' => '6'
			),
      'settings_backup_path' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
      'settings_preexec' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
      'settings_postexec' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
      'settings_alpha' => array(
				'type' => 'INT',
				'constraint' => '3',
			),
      'settings_beta' => array(
				'type' => 'INT',
				'constraint' => '3',
			),
      'settings_gamma' => array(
				'type' => 'INT',
				'constraint' => '3',
			),
      'settings_delta' => array(
				'type' => 'INT',
				'constraint' => '3',
			),
		);		
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('settings_id', true);
		$this->dbforge->create_table('settings');

		$data = array(
			'settings_alpha' => 6,
			'settings_beta' => 7,
			'settings_gamma' => 4,
			'settings_delta' => 6,
		);
	    $this->db->set('settings_id', 1);
	    $this->db->insert('settings',$data);

		$fields = array(
      'backups_id' => array(
				'type' => 'INT',
				'constraint' => '6',
				'auto_increment' => TRUE
			),
      'backups_server_id' => array(
				'type' => 'INT',
				'constraint' => '6',
			),
      'backups_path' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
      'backups_added' => array(
				'type' => 'INT',
				'constraint' => '13',
			),
		);		
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('backups_id', true);
		$this->dbforge->create_table('backups');


		
	}

	public function add_server( $name, $address )
	{

		$data = array(
			'server_name' => $name,
			'server_address' => $address
		);
	    $this->db->insert('servers',$data);
	    return $this->db->insert_id();

	}

	public function set_backups( $server_id, $backups )
	{
		$time = time();
		foreach( $backups as $backup ) {
			$data = array(
				'backups_path' => $backup,
				'backups_server_id' => $server_id,
				'backups_added' => $time
			);
		    $this->db->insert('backups',$data);

		}

	}

    /**
     * get list of server
     *
     * @access	public
     * @return string
     */
	public function get_servers()
	{
		if( !$this->db->table_exists( 'servers' ) ) $this->create_backups_table();
		$servers = $this->db->get('servers');
		if ($servers->num_rows() > 0) {
			return $servers->result();
		} else return array();
	}
    /**
     * get server details
     *
     * @access	public
     * @return string
     */
	public function get_server( $id )
	{
		$this->db->where( 'server_id', $id );
		$servers = $this->db->get('servers');
		if ($servers->num_rows() > 0) {
			return $servers->row();
		} else return false;
	}

	/**
     * get list of backup points
     *
     * @access	public
     * @return string
     */
	public function backup_list( $server_id )
	{
		$server_backups = $this->get_backups( $server_id );
		$backups = $server_backups['backups'];
		if ( !empty( $backups ) ) {
			$a = 1;
			$output = '';
			foreach( $backups as $backup ) {
				$id = ( $a === 1 ) ? ' id="backuprow"' : '';
				$output .= '
			<div class="formrow"'.$id.'>
                <div class="label"><label>'.__('Folder').' <b>#'.$a.'</b><span>'.__('Pick the folder you want to backup').'</span></label></div><div class="input"><input type="text" name="backup[]" value="'.$backup->backups_path.'" /></div>
            </div>';
				$a++;
			}
			return $output;
		} else return '
			<div class="formrow" id="backuprow">
                <div class="label"><label>'.__('Folder').' <b>#1</b><span>'.__('Pick the folder you want to backup').'</span></label></div><div class="input"><input type="text" name="backup[]" /></div>
            </div>';
	}


	/**
     * get list of backup points
     *
     * @access	public
     * @return string
     */
	public function get_backups( $server_id )
	{
		$this->db->where( 'backups_server_id', $server_id );
		$servers = $this->db->get('backups');
		if ( ( $rowcount = $servers->num_rows() ) > 0) {
			return array( "folders" => $rowcount, "backups" => $servers->result() );
		} else return array();
	}


}
