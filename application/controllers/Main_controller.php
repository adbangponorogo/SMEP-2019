<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){
		global $smep;
		
		if (empty($this->session->userdata('auth_id'))) {
			redirect("app/auth/loginPage");
		}
		else{
			$data['title'] = strtoupper('SMEP - '.$smep->tingkat.' '.$smep->klpd);
			$data['logo'] = $smep->logo;
			$data["user_data"] = $this->getUserData();
			$this->load->view('Main_view', $data);
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

	public function getDataTester(){
		if ($this->session->userdata('auth_id') != '') {
			// $password = 123456;
			// echo md5($password);
		}
	}

}