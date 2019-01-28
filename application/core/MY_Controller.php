<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		global $smep;

		parent::__construct();
		$this->load->model('Main_model', 'model');

		$smep->tingkat = $this->model->getConfig('tingkat')->row()->value;
		$smep->klpd = $this->model->getConfig('klpd')->row()->value;
		$smep->footerlap = $this->model->getConfig('footerlap')->row()->value;
	}

}

class Admin_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('auth_id'))) { //Filter untuk semua
			$this->load->view('pages/errors/data');
		}
	}

}