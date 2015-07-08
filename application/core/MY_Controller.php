<?php
class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
		load_language();
    }

    public function _output($output)
	{
		$data = $this->load->get_vars();
		$this->load->library('aesir_api', $data);
	    echo $output;
	}

}
?>