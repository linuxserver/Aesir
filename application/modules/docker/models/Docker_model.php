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
 * Users_model class
 *
 * Model for user specific functions
 *
 * @package     Aesir v1.0
 * @subpackage  Models
 * @author      Kode
 */

class Docker_model extends CI_Model {

	public function __construct() {        
	    parent::__construct();


	}
	
    /**
     * create lang table
     *
     * @access	public
     * @return void
     */
	public function create_docker_table() {
		$this->load->dbforge();
		
		$this->dbforge->add_field('id');
		$fields = array(
        	'temp_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_desc' => array(
				'type' => 'TEXT',
			),		
        	'temp_beta' => array(
				'type' => 'INT',
				'constraint' => '1',
				'default' => '0',
			),
		);		
		$this->dbforge->add_field($fields);
		
		$this->dbforge->add_key('trans_id', true);
		$this->dbforge->create_table('templates');
		
		//$this->dbforge->create_table('categories');
		//$this->dbforge->create_table('template_categories');
		
		$data = array(
			'current_lang' => $this->config->item( 'language' )
		);
	    $this->db->set('trans_id', 1);
	    $this->db->insert('translations',$data);

	}




}