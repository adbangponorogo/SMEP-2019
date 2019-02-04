<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminkonfigurasi_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('administrator/adminkonfigurasi_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/konfigurasi/data');
		}
		else{
			redirect(base_url());
		}
	}

	public function changeData(){
		if ($this->session->userdata("auth_id") != "") {
			// ============== Tingkat ============== //
			$result_tingkat = $this->model->getDataConfig('tingkat');
			if ($result_tingkat->num_rows() > 0) {
				foreach ($result_tingkat->result() as $rows_tingkat) {
					$tingkat = $rows_tingkat->value;
				}
			}
			else{
				$tingkat = '';
			}

			// ============== K/L/P/D ============== //
			$result_klpd = $this->model->getDataConfig('klpd');
			if ($result_klpd->num_rows() > 0) {
				foreach ($result_klpd->result() as $rows_klpd) {
					$klpd = $rows_klpd->value;
				}
			}
			else{
				$klpd = '';
			}

			// ============= Footerlap ============= //
			$result_footerlap = $this->model->getDataConfig('footerlap');
			if ($result_footerlap->num_rows() > 0) {
				foreach ($result_footerlap->result() as $rows_footerlap) {
					$footerlap = $rows_footerlap->value;
				}
			}
			else{
				$footerlap = '';
			}

			// ========== Image (Logo/Icon) ======== //
			$result_logo = $this->model->getDataConfig('logo');
			if ($result_logo->num_rows() > 0) {
				foreach ($result_logo->result() as $rows_logo) {
					$status_logo = 0;
					$nama_logo = $rows_logo->value;
				}
			}
			else{
				$status_logo = 1;
				$nama_logo = '';
			}

			$data = array(
						$tingkat,
						$klpd,
						$footerlap,
						[$status_logo, $nama_logo]
					);

			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}

	public function updateData(){
		if ($this->session->userdata("auth_id") != "") {
			// // ============== Tingkat ============== //
			$result_tingkat = $this->model->getDataConfig('tingkat');
			if ($result_tingkat->num_rows() > 0) {
				$keyword = "tingkat";
				$data = array("value" => $this->input->post("tingkat"));
				$this->model->updateData($keyword, $data);
			}
			else{
				$data = array(
								"key"	=> "tingkat",
								"value" => $this->input->post("tingkat")
							);
				$this->model->uploadData($data);
			}

			// // ============== K/L/P/D ============== //
			$result_klpd = $this->model->getDataConfig('klpd');
			if ($result_klpd->num_rows() > 0) {
				$keyword = "klpd";
				$data = array("value" => $this->input->post("klpd"));
				$this->model->updateData($keyword, $data);
			}
			else{
				$data = array(
								"key"	=> "klpd",
								"value" => $this->input->post("klpd")
							);
				$this->model->uploadData($data);
			}

			// // ============= Footerlap ============= //
			$result_footerlap = $this->model->getDataConfig('footerlap');
			if ($result_footerlap->num_rows() > 0) {
				$keyword = "footerlap";
				$data = array("value" => $this->input->post("footerlap"));
				$this->model->updateData($keyword, $data);
			}
			else{
				$data = array(
								"key"	=> "footerlap",
								"value" => $this->input->post("footerlap")
							);
				$this->model->uploadData($data);
			}

			// // ========== Image (Logo/Icon) ======== //
			$result_logo = $this->model->getDataConfig('logo');
			if ($result_logo->num_rows() <= 0) {
				if (isset($_FILES["logo"]["name"])) {
					$config['upload_path']="./assets/uploads/";
			        $config['allowed_types']='gif|jpg|jpeg|png';
			        $config['encrypt_name'] = TRUE;

			        $this->load->library('upload', $config);
			        if ($this->upload->do_upload('logo')) {
			        	$data = $this->upload->data();
			        	$data_upload = array(
			            						"key"	=> "logo",
			            						"value" => $data['file_name']
			            					);
						$this->model->uploadData($data_upload);
			        }
				}
			}

			echo json_encode(array("status"=>TRUE));
		}
		else{
			redirect(base_url());
		}
	}

	public function trashImage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->model->deleteImage();
			echo json_encode(array("status"=>TRUE));
		}
		else{
			redirect(base_url());
		}
	}
}