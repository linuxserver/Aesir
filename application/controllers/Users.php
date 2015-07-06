<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {

	public function index()
	{
		
		//$config['database'] = APPPATH.'database/cerberus.db';
		//$config['dbdriver'] = 'sqlite3';
		//$this->load->database($config);

		$this->load->library('unraid');
		$array_details = $this->unraid->array_details();
		$data = $array_details;

		$data['page_title'] = 'Users';
		$data["users"] = parse_ini_file($this->config->item("ini_path")."users.ini", TRUE);
		//$this->load->database();
		$this->load->model('user_model');

		if($_POST) {
			$action = $this->input->post('action');
			switch( $action ) {
				case 'save_email':
					$user_id = $this->input->post('user_id');
					$user_email = $this->input->post('user_email');
					//$this->user_model->save_email( $user_id, $user_email );
					break;
			}
		}


		$this->load->library('gravatar');
		
		//print_r($var);
		$this->load->view('header', $data);
		$this->load->view('users', $data);
		$this->load->view('footer', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */