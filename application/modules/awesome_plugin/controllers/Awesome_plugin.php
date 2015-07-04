<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Awesome_plugin extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->language('awesome_plugin');
    }	

	public function index()
	{
		$header_data['page_title'] = __( 'Awesome Plugin' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'index' );
		$this->load->view( 'footer' );
	}
}
