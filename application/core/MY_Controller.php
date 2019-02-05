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
		$smep->logo = $this->model->getConfig('logo')->row()->value;

		// Logo
		// $result_logo = $this->model->getConfig('logo');
		// if ($result_logo->num_rows() > 0) {
			// foreach ($result_logo->result() as $rows_logo) {
				// $nama_logo = $rows_logo->value;
			// }
		// }
		// else{
			// $nama_logo = '';
		// }
		// $smep->logo = $nama_logo;
	}

}

class Admin_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		if (empty($this->session->userdata('auth_id'))) { //Filter untuk semua
			if (stristr(uri_string(), 'print-data')) {
				redirect(base_url());
			}
			else {
				$this->load->view('pages/errors/data');
			}
		}
	}

}