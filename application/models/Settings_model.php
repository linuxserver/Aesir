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

class Settings_model extends CI_Model {

	public function __construct() {        
	    parent::__construct();


	}
	
    /**
     * create lang table
     *
     * @access	public
     * @return void
     */
	public function create_lang_table() {
		$this->load->dbforge();
		$fields = array(
        	'trans_id' => array(
				'type' => 'INT',
				'constraint' => '3',
			),
        	'current_lang' => array(
				'type' => 'VARCHAR',
				'constraint' => '10',
			),
		);		
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('trans_id', true);
		$this->dbforge->create_table('translations');
		
		$data = array(
			'current_lang' => $this->config->item( 'language' )
		);
	    $this->db->set('trans_id', 1);
	    $this->db->insert('translations',$data);

	}

    /**
     * get user details by id
     *
     * @access	public
     * @return string
     */
	public function get_current_lang() {
		$this->db->select( 'trans_id, current_lang' );
		$lang = $this->db->get_where('translations', array('trans_id' => 1));
		if ($lang->num_rows() > 0) {
			$current = $lang->row();
			return $current->current_lang;
		} else {
			return $this->config->item( 'language' );
		}
	}

    /**
     * Save current language translation
     *
     * @access	public
     * @param	string		$lang iso code of language (the folder it is in)
     * @return void
     */
	public function set_lang( $lang ) {

		$data = array(
			'current_lang' => $lang
		);

	    $this->db->set('trans_id', 1);
	    $this->db->update('translations',$data);

	}
	
    /**
     * Load translated values from $lang array
     *
     * @access	public
     * @param	string		$lang_folder iso code of language (the folder it is in)
     * @param	boolean		$assoc if assoc is not true, array is numerically indexed
     * @param	boolean		$plugin if true tries to get the plugins language file
     * @return array
     */
	public function lang_array( $lang_folder, $assoc=true, $plugin=false ) {
		$path = ( $plugin!==false ) ? APPPATH.'modules/'.$plugin.'/language' : APPPATH.'language';
		$langfile = ( $plugin!==false ) ? $plugin.'_lang.php' : 'aesir_lang.php';
		$file = $path."/".$lang_folder."/".$langfile;
		if( file_exists( $file ) ) {
        	include( $file ); // include the file to access thr $lang array
		} else $lang = array();
		//$lang = array('Dashboard' => 'test');
		return ( $assoc === true ) ? $lang : array_values($lang);		
	}

    /**
     * Load translated keys from $lang array
     *
     * @access	public
     * @param	string		$lang_folder iso code of language (the folder it is in)
     * @param	boolean		$assoc if assoc is not true, array is numerically indexed
     * @param	boolean		$plugin if true tries to get the plugins language file
     * @return array
     */
	public function lang_array_key( $lang_folder, $assoc=true, $plugin=false ) {
		$path = ( $plugin!==false ) ? APPPATH.'modules/'.$plugin.'/language' : APPPATH.'language';
		$langfile = ( $plugin!==false ) ? $plugin.'_lang.php' : 'aesir_lang.php';
		$file = $path."/".$lang_folder."/".$langfile;
		if( file_exists( $file ) ) {
        	include( $file ); // include the file to access thr $lang array
		} else $lang = array();
		//$lang = array('Dashboard' => 'test');
		return ( $assoc === true ) ? $lang : array_keys($lang);		
	}

	
    /**
     * Listens to form posts for settings
     *
     * @access	public
     * @return void
     */
	public function form_listener() {
		$action = $this->input->post('__action');
		
		switch( $action ) {
			case 'save_translation':
				$plugin = false; // change this when plugin support is added
				$folder = $this->input->post('__folder');
				$folder = preg_replace('/[^\da-z_-]/i', '', $folder);
				
				$path = ( $plugin!==false ) ? APPPATH.'modules/'.$plugin.'/language' : APPPATH.'language';
				$langfile = ( $plugin!==false ) ? $plugin.'_lang.php' : 'aesir_lang.php';
				$file = $path."/".$folder."/".$langfile;
				
				if( !is_dir( $path."/".$folder."/" ) ) mkdir( $path."/".$folder."/", 0755 );

				if( $folder == 'en' || empty( $folder ) ) return false; // do not overwrite the default language through this
				$string = '<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
/**
 * Project: Aesir translation
 * Locale: '.$folder.'
 */
';

				$keys = $this->lang_array_key('en', false);
				$posts = $_POST;
				unset($posts['__action'], $posts['__folder']);
				$posts = array_values($posts);
				//print_r($keys);
				//print_r($posts);
				
				foreach( $keys as $key => $val ) {
					if( empty( $posts[$key] ) ) continue;
					$string .= '$lang[\''.$val.'\'] = \''.$posts[$key]."';\n";
				}
				file_put_contents($file, $string);
				redirect( site_url( 'settings/translations' ) );
				break;	
		}
	}



}