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
		
		$fields = array(
        	'temp_id' => array(
				'type' => 'INT',
				'constraint' => '9',
			),		
        	'temp_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_overview' => array(
				'type' => 'TEXT',
			),		
        	'temp_desc' => array(
				'type' => 'TEXT',
			),		
        	'temp_registry' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_github' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_repository' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_support' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_icon' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_banner' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_base' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'temp_downloads' => array(
				'type' => 'INT',
			),		
        	'temp_beta' => array(
				'type' => 'INT',
				'constraint' => '1',
				'default' => '0',
			),
		);		
		$this->dbforge->add_field($fields);

		
		$this->dbforge->add_key('temp_id', true);
		$this->dbforge->create_table('templates');

		$fields = array(
        	'cat_id' => array(
				'type' => 'INT',
				'auto_increment' => TRUE
			),		
        	'cat_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),		
        	'cat_parent' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'default' => 'none',
			),		
		);		
		$this->dbforge->add_field($fields);
		
		$this->dbforge->add_key('cat_id', TRUE);
		$this->dbforge->add_key('cat_parent');
		$this->dbforge->create_table('categories');


		$this->dbforge->add_field('id');
		$fields = array(
        	'fk_cat_id' => array(
				'type' => 'INT',
			),		
        	'fk_temp_id' => array(
				'type' => 'INT',
			),		
		);		
		$this->dbforge->add_field($fields);
		
		$this->dbforge->add_key('fk_cat_id', TRUE);
		$this->dbforge->add_key('fk_temp_id', TRUE);
		$this->dbforge->create_table('template_categories');
		
		//$data = array(
		//	'current_lang' => $this->config->item( 'language' )
		//);
	    //$this->db->set('trans_id', 1);
	    //$this->db->insert('translations',$data);

	}

	public function cat_list( $parent = 0 ) {
		//$this->db->select( 'cat_name' );
		//$this->db->join('template_categories', 'template_categories.fk_cat_id = categories.cat_id', 'left');
		$this->db->where( 'cat_parent', $parent);
		$cats = $this->db->get('categories');
		if ($cats->num_rows() > 0) {
			return $cats->result();
		} else {
			return array();
		}
	}
	public function docker_list( $cat=false, $subcat=false ) {
		//$this->db->select( 'cat_name' );
		//$this->db->join('template_categories', 'template_categories.fk_cat_id = categories.cat_id', 'left');
		if($subcat !== false) {
			$this->db->join('template_categories', 'template_categories.fk_temp_id = templates.temp_id', 'left');
			$this->db->where( 'fk_cat_id', $subcat);
		}
		elseif($cat !== false) {
			$this->db->join('template_categories', 'template_categories.fk_temp_id = templates.temp_id', 'left');
			$this->db->where( 'fk_cat_id', $cat);
		}
		$cats = $this->db->get('templates');
		if ($cats->num_rows() > 0) {
			return $cats->result();
		} else {
			return array();
		}
	}

	public function get_cat( $cat, $parent=0 ) {
		$this->db->where('cat_name', $cat);
		$this->db->where('cat_parent', $parent);
		$cats = $this->db->get('categories');
		if ($cats->num_rows() > 0) {
			return $cats->row();
		} else {
			return false;
		}

	}

	public function cat_insert( $cat, $parent=0 ) {
		if( ( $qcat = $this->get_cat( $cat, $parent ) ) !== false ) {
			return $qcat->cat_id;
		} else {
			$data['cat_name'] = $cat;
			$data['cat_parent'] = ( is_numeric( $parent ) ) ? $parent : 0;
			$this->db->insert( 'categories', $data ); 
			return $this->db->insert_id();
		}
	}

	public function load_tables( $json )
	{
		$this->db->truncate('categories'); 
		$this->db->truncate('templates'); 
		$this->db->truncate('template_categories'); 

		//$this->_build_cats( $json );
		$this->_load_tables( $json );
	}


	/*public function _build_cats( $json )
	{
		

		$applist = $json['applist'];

		foreach( $applist as $app ) {

			if( isset( $app['Category'] ) && !empty($app['Category']) ) {

				$_cat_split = explode( ' ', $app['Category'] );

				foreach( $_cat_split as $split ) {
					$_cat_list = explode( ':', $split );
					if( !empty( $_cat_list[1] ) ) { // has sub cat
						$this->
						$cat_list[$_cat_list[0]][$_cat_list[1]] = $_cat_list[1];
					} else { //does not have subcat
						$cat_list[$_cat_list[0]] = $_cat_list[0];
					}
					
					//$cat_list = array_merge( $cat_list, $_cat_list );
				}
				
				//$_cat_list = array_map('trim',$_cat_list);
				//$cat_list = array_merge( $cat_list, $_cat_list );
			}



		}
		//print_r( $applist );

	}*/


	public function _load_tables( $json )
	{

		$applist = $json['applist'];
		$cat_list = array();
		$mcat = array();
		$appcat = array();
		$a = 0;
		foreach( $applist as $app ) {

			$a++;

			$beta = ( isset( $app['Beta'] ) && $app['Beta'] == 'True' ) ? 1 : 0;

			$appName = (isset($app['Name']) && !empty($app['Name'])) ? $app['Name'] : '';
			$appOverview = (isset($app['Overview']) && !empty($app['Overview'])) ? trim((string)$app['Overview']) : '';
			//$appDescription = (isset($app['Description']) && !empty($app['Description'])) ? trim((string)$app['Description']) : '';
			if (isset($app['Description']) && !empty($app['Description'])) {
				$appDescription = ( is_array( $app['Description'] )) ? $app['Description']['Overview'] : $app['Description'];
			} else $appDescription = ''; // just a work around for a bug in needos template

			$appRegistry = (isset($app['Registry']) && !empty($app['Registry'])) ? (string)$app['Registry'] : '';
			$appGitHub = (isset($app['GitHub']) && !empty($app['GitHub'])) ? (string)$app['GitHub'] : '';
			$appRepository = (isset($app['Repository']) && !empty($app['Repository'])) ? (string)$app['Repository'] : '';
			$appSupport = (isset($app['Support']) && !empty($app['Support'])) ? (string)$app['Support'] : '';
			$appIcon = (isset($app['Icon']) && !empty($app['Icon'])) ? (string)$app['Icon'] : '';
			$appBanner = (isset($app['Banner']) && !empty($app['Banner'])) ? (string)$app['Banner'] : '';
			$appBase = (isset($app['Base']) && !empty($app['Base'])) ? (string)$app['Base'] : '';
			$appDownloads = (isset($app['Downloads']) && !empty($app['Downloads'])) ? (string)$app['Downloads'] : '';

			$mapp[] = array(
				'temp_id' => $a,
				'temp_name' => $appName,
				'temp_beta' => $beta,
				'temp_overview' => $appOverview,
				'temp_desc' => $appDescription,
				'temp_registry' => $appRegistry,
				'temp_github' => $appGitHub,
				'temp_repository' => $appRepository,
				'temp_support' => $appSupport,
				'temp_icon' => $appIcon,
				'temp_banner' => $appBanner,
				'temp_base' => $appBase,
				'temp_downloads' => $appDownloads,
			);

			if( isset( $app['Category'] ) && !empty($app['Category']) ) {
				$_cat_split = explode( ' ', $app['Category'] );
				foreach( $_cat_split as $split ) {
					$_clist = explode( ':', $split );
					if( !empty( $_clist[1] ) ) {
						$parent = $this->cat_insert( $_clist[0] );
						$sub_cat = $this->cat_insert( $_clist[1], $parent );

						$appcat[] = array( 'fk_cat_id' => $parent, 'fk_temp_id' => $a );
						$appcat[] = array( 'fk_cat_id' => $sub_cat, 'fk_temp_id' => $a );

					} else {
						$parent = $this->cat_insert( $_clist[0] );
						$appcat[] = array( 'fk_cat_id' => $parent, 'fk_temp_id' => $a );

					}
					
					//$cat_list = array_merge( $cat_list, $_cat_list );
				}
				
				//$_cat_list = array_map('trim',$_cat_list);
				//$cat_list = array_merge( $cat_list, $_cat_list );
			}

		}
		
		//print_r( $mapp );

		$this->db->insert_batch('templates', $mapp); 
		//$this->db->insert_batch('categories', $mcat); 
		$this->db->insert_batch('template_categories', $appcat); 

		//print_r( $applist );

	}


}