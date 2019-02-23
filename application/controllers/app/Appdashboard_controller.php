<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appdashboard_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('app/appdashboard_model', 'model');
	}

	public function mainPage(){
		$this->load->view('pages/app/dashboard/data');
	}

}