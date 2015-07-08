<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preclear extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->language('preclear');
    }	

	public function index()
	{
		$header_data['page_title'] = __( 'Preclear' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'preclear' );
		$this->load->view( 'footer' );
	}
}
