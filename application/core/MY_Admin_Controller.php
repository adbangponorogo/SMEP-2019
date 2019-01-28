<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Admin_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('auth_id'))) { //Filter untuk semua
			$this->load->view('pages/errors/data');
		}
	}

}