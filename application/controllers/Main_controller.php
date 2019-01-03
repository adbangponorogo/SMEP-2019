<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('main_model', 'model');
	}

	public function index(){
		if ($this->session->userdata('auth_id') != "") {
			$data["user_data"] = $this->getUserData();
			$this->load->view('main_view', $data);
		}
		else{
			redirect("app/auth/loginPage");
		}
	}

	public function getUserData(){
		if ($this->session->userdata('auth_id') != '') {
			$auth = $this->session->userdata('auth_id');
			$result = $this->model->getDataUser($auth);
			return $result;
		}
	}

	public function getDataSKPD(){
		if ($this->session->userdata('auth_id') != '') {
			$resultUser = $this->getUserData();
			$data = array();
			foreach ($resultUser->result() as $rows_user) {
				if ($rows_user->status == 2 || $rows_user->status == 3) {
					$resultSKPD = $this->model->getDataUserPPKUnique($rows_user->id_skpd);
					foreach ($resultSKPD->result() as $rows_SKPD) {
						$data[] = array(
									$rows_SKPD->id,
									$rows_SKPD->kd_skpd,
									$rows_SKPD->nama_skpd
								);
					}
				}
				if ($rows_user->status == 1) {
					$resultSKPD = $this->model->getDataUserPPKAll($rows_user->id_skpd);
					foreach ($resultSKPD->result() as $rows_SKPD) {
						$data[] = array(
									$rows_SKPD->id,
									$rows_SKPD->kd_skpd,
									$rows_SKPD->nama_skpd
								);
					}
				}
			}

			echo json_encode($data);
		}
	}

}