<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applogin_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("app/Applogin_model", "app_model");
	}

	public function loginPage(){
		global $smep;

		if (empty($this->session->userdata('auth_id'))) {
			$data['title'] = strtoupper('SMEP '.$smep->tingkat.' '.$smep->klpd);
			$this->load->view('Login_view', $data);
		}
		else{
			redirect(base_url());
		}
	}

	public function loginProcess(){
		$username = $this->input->post("username");
		$password = md5($this->input->post("password"));
		$resultUsers = $this->app_model->getDataUsers($username, $password);
		if ($resultUsers->num_rows() > 0) {
			foreach ($resultUsers->result() as $rows_users) {
				$this->session->set_userdata(array(
					"auth_id" => $rows_users->id,
					"skpd_id" => $rows_users->id_skpd
					));
			echo json_encode(array("status"=>TRUE));
			}
		}
	} 

	public function checkSession(){
		if ($this->session->userdata('auth_id') != '') {
			echo json_encode(0);
		}
		else{
			echo json_encode(1);
		}
	}

	public function logoutProcess(){
		$this->session->unset_userdata(array('auth_id', 'skpd_id'));
		echo json_encode(array("status"=>TRUE));
	}

}