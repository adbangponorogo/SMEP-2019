<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpengsi_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/adminpengsi_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/pengguna-aplikasi/data');
		}
	}

	public function userPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/pengguna-aplikasi/data-user');
		}
	}

	public function getMainData(){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getMainData();
			echo json_encode($result);
		}
	}

	public function getUserData($token){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataUser($token);
			$data = array();
			$no = 1;
			foreach ($result->result() as $rows) {
				switch ($rows->status) {
					case '1':
						$status = 'Root - Administrator';
					break;
					case '2':
						$status = 'Operator - PA/KPA SKPD';
					break;
					case '3':
						$status = 'Pejabat Pembuat Komitmen (PPK)';
					break;
					default:
						$status = 'Operator - PA/KPA SKPD';
					break;
				}
				$data[] = array(
							$no++,
							$rows->nama,
							$rows->username,
							$status,
							$rows->alamat,
							"<button class='btn btn-info btn-sm smep-pengsiadmin-user-edit-btn' data-id='".$rows->id."'><i class='fa fa-edit'></i>&nbsp;Edit</button>&nbsp;".
							"<button class='btn btn-danger btn-sm smep-pengsiadmin-user-delete-btn' data-id='".$rows->id."'><i class='fa fa-trash'></i>&nbsp;Hapus</button>"
						);
			}
			echo json_encode($data);
		}
	}

	public function getDataSKPD($token){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataSKPD($token);
			$data = array();
			foreach ($result->result() as $rows) {
				$data[] = array(
							$rows->id,
							$rows->kd_skpd,
							$rows->nama_skpd,
						);
			}
			echo json_encode($data);
		}
	}

	public function sendData(){
		if ($this->session->userdata("auth_id") != "") {
			$resultUsername = $this->model->getDatauserUnique($this->input->post("username"));
			if ($resultUsername->num_rows() <= 0) {
				$id = "USER-".date("Ymdhis").rand(0,9);
				$data_users = array(
							"id" => $id,
							"status" => $this->input->post("status"),
							"id_skpd" => $this->input->post("skpd"),
							"nama" => $this->input->post("nama"),
							"username" => $this->input->post("username"),
							"password" => password_hash($this->input->post("password"), PASSWORD_BCRYPT, array("cost"=>9)),
							"email" => $this->input->post("email"),
							"telepon" => $this->input->post("telepon"),
							"alamat" => $this->input->post("alamat"),
						);
				$this->model->insertData($data_users);
				echo json_encode("0"); 
			}
			else{
				echo json_encode("1"); 
			}
		}
	}

	public function changeData($token){
		if ($this->session->userdata("auth_id") != "") {
			$resultUser = $this->model->getData($token);
			$data = array();
			foreach ($resultUser->result() as $rows_user) {
				$resultSKPD = $this->model->getDataSKPD($rows_user->id_skpd);
				switch ($rows_user->status) {
					case '1':
						$status = 'Root - Administrator';
						break;
					case '2':
						$status = 'Operator - PA/KPA SKPD';
						break;
					case '3':
						$status = 'Pejabat Pembuat Komitmen';
						break;
					
					default:
						break;
				}
				foreach ($resultSKPD->result() as $rows_SKPD) {
					$data[] = array(
								$rows_user->id,
								$rows_user->status,
								$status,
								"[".$rows_SKPD->kd_skpd."] - ".$rows_SKPD->nama_skpd,
								$rows_user->nama,
								$rows_user->username,
								$rows_user->email,
								$rows_user->telepon,
								$rows_user->alamat,
							);
					echo json_encode($data);
				}
			}
		}
	}

	public function updateData(){
		if ($this->session->userdata("auth_id") != "") {
			if ($this->input->post("status") == 2 || $this->input->post("status") == 3) {
				$data = array(
							"nama" => $this->input->post("nama"),
							"email" => $this->input->post("email"),
							"telepon" => $this->input->post("telepon"),
							"alamat" => $this->input->post("alamat"),
						);
				$this->model->updateDataUsers($this->input->post("token"), $data);
				echo json_encode(array("status"=>TRUE));
			}
			if ($this->input->post("status") == 1) {
				if ($this->input->post("password") == "Jika tidak ingin rubah password - harap tidak merubah inputan ini") {
					$data = array(
							"nama" => $this->input->post("nama"),
							"email" => $this->input->post("email"),
							"telepon" => $this->input->post("telepon"),
							"alamat" => $this->input->post("alamat"),
						);
					$this->model->updateDataUsers($this->input->post("token"), $data);
					echo json_encode(array("status"=>TRUE));
				}
				else{
					$data = array(
							"nama" => $this->input->post("nama"),
							"password" => password_hash($this->input->post("password"), PASSWORD_BCRYPT, array("cost"=>9)),
							"email" => $this->input->post("email"),
							"telepon" => $this->input->post("telepon"),
							"alamat" => $this->input->post("alamat"),
						);
					$this->model->updateDataUsers($this->input->post("token"), $data);
					echo json_encode(array("status"=>TRUE));
				}
			}
		}
	}

	public function trashData($token){
		if ($this->session->userdata("auth_id") != "") {
			$this->model->deleteDataUsers($token);
			echo json_encode(array("status"=>TRUE));
		}
	}
}