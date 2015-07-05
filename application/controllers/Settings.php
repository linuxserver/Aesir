<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

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
		$this->load->library('unraid');
		$array_details = $this->unraid->array_details();
		$data = $array_details;

		$data['all_languages'] = $this->get_languages();
		$data['current_language'] = $this->config->item('language');
		$data['lang_count'] = count($data['all_languages']);

		if ( $section === false ) {
			$this->load->view('header', $data);
			$this->load->view('settings/translation_list', $data);
			$this->load->view('footer', $data);
		}
	}

	public function set_language( $lang )
	{
		$this->config->set_item( 'language', $lang );
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