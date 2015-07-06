<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {
	
	public function __construct() {        
	    parent::__construct();
		$this->load->model('settings_model');
		$this->settings_model->form_listener();
	}

	public function index($section=false)
	{
		$this->load->library('unraid');
		$array_details = $this->unraid->array_details();
		$data = $array_details;

		$data["tags"] = array("dashboard" => "Dashboard", "users" => "Users");
		$data["section"] = $section;
		$data["disks"] = parse_ini_file($this->config->item("ini_path")."disks.ini", TRUE);
		//print_r($var);
		$this->load->view('header', $data);
		$this->load->view('settings', $data);
		$this->load->view('footer', $data);
	}

	public function translations( $section=false )
	{
		$defercreate = false;
		if( !file_exists( APPPATH.'database/language.db' ) ) $defercreate = true;
		
		$config['database'] = APPPATH.'database/language.db';
		$config['dbdriver'] = 'sqlite3';
		$this->load->database($config);
		
		if( $defercreate ) $this->settings_model->create_lang_table(); // datbase is silently created if it doesn't exist, $defercreate ensures it's populated if it didn't exist to begin with
		
		$this->load->library('unraid');
		$array_details = $this->unraid->array_details();
		$data = $array_details;

		$data['all_languages'] = $this->get_languages();
		$data['current_language'] = $this->settings_model->get_current_lang();
		$data['lang_count'] = count($data['all_languages']);
		$data['page_title'] = 'Translations';
		$data['trans_active'] = 'overview';
		if ( $section === false ) {
			$this->load->view('header', $data);
			$this->load->view('settings/translation_list', $data);
			$this->load->view('footer', $data);
		}
	}
	
	public function edit_translation( $lang ) {
		$this->load->library('unraid');
		$array_details = $this->unraid->array_details();
		$data = $array_details;
		$data['all_languages'] = $this->get_languages();
		$data['lang_count'] = count($data['all_languages']);
		
		$data['page_title'] = 'Edit Translation';
		$data['trans_active'] = 'edit';
		
		$data['english'] = $this->settings_model->lang_array( 'en' );
		$current = $this->settings_model->lang_array( $lang );
		$_POST['__folder'] = $lang;
		foreach( $current as $key => $val ) {
			$_POST[$key] = $val;
		}
		
		$this->load->view('header', $data);
		$this->load->view('settings/translation_edit', $data);
		$this->load->view('footer', $data);
		
	}
	
	public function duplicate_translation( $lang ) {
		$this->load->library('unraid');
		$array_details = $this->unraid->array_details();
		$data = $array_details;
		$data['all_languages'] = $this->get_languages();
		$data['lang_count'] = count($data['all_languages']);
		
		$data['page_title'] = 'Duplicate '.$lang;
		$data['trans_active'] = 'edit';
		
		$data['english'] = $this->settings_model->lang_array( 'en' );
		$current = $this->settings_model->lang_array( $lang );
		foreach( $current as $key => $val ) {
			$_POST[$key] = $val;
		}
		
		$this->load->view('header', $data);
		$this->load->view('settings/translation_edit', $data);
		$this->load->view('footer', $data);
		
	}
	
	public function delete_translation( $lang, $plugin=false ) {
		$current_language = $this->settings_model->get_current_lang();
		$lang = preg_replace('/[^\da-z_-]/i', '', $lang);
		if( $lang == 'en' || empty( $lang ) ) return false; // do not delete the default language
		$path = ( $plugin!==false ) ? APPPATH.'modules/'.$plugin.'/language' : APPPATH.'language';
		if( is_dir( $path."/".$lang."/" ) ) {
			deleteDir( $path."/".$lang );
		}
		if( $lang == $current_language ) {
			$this->settings_model->set_lang( 'en' );	
		}
		
		redirect( site_url( 'settings/translations' ) );
	}
	
	
	public function new_translation( $load=false ) {
		$this->load->library('unraid');
		$array_details = $this->unraid->array_details();
		$data = $array_details;
		$data['all_languages'] = $this->get_languages();
		$data['lang_count'] = count($data['all_languages']);
		
		$data['page_title'] = 'New Translation';
		$data['trans_active'] = 'new';
		
		$data['english'] = $this->settings_model->lang_array( 'en' );
		
		$this->load->view('header', $data);
		$this->load->view('settings/translation_edit', $data);
		$this->load->view('footer', $data);
		
	}
	

	public function set_language( $lang )
	{
		$this->settings_model->set_lang( $lang );
		redirect( 'settings/translations' );
	}

	protected function get_languages( $plugin=false ) 
	{
		$path = ( $plugin!==false ) ? APPPATH.'modules/'.$plugin.'/language' : APPPATH.'language';
		$langfile = ( $plugin!==false ) ? $plugin.'_lang.php' : 'aesir_lang.php';

		$langs = scandir( $path );

        $scanned_directory = array_diff( $langs, array( '..', '.', 'index.html' ) );

        $all_langs = array();
        //die(print_r($scanned_directory));
        foreach( $scanned_directory as $lang_dir ) {
        	$file = $path."/".$lang_dir."/".$langfile;
            if( file_exists( $file ) ) {

            	unset( $lang );
            	include( $file ); // include the file to access thr $lang array
            	$lang_items = count( $lang );
            	$modified = filemtime( $file );
            	$all_langs[$lang_dir] = array( "items" => $lang_items, "modified" => $modified );
            }
        }
        //print_r($all_langs);

        $all_langs = array('en' => $all_langs['en']) + $all_langs; // move en to the top of the list

        return $all_langs;

	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */