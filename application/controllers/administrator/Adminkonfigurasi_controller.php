<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminkonfigurasi_controller extends Admin_Controller {

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
		global $smep;

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
			$status_logo = 0;
			$nama_logo = $smep->logo;

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
		global $smep;
		
		if ($this->session->userdata("auth_id") != "") {
			// // ============== Tingkat ============== //
			$keyword_1 = "tingkat";
			$data_1 = array("value" => $this->input->post("tingkat"));
			$this->model->updateData($keyword_1, $data_1);
			

			// // ============== K/L/P/D ============== //
			$keyword_2 = "klpd";
			$data_2 = array("value" => $this->input->post("klpd"));
			$this->model->updateData($keyword_2, $data_2);
			

			// // ============= Footerlap ============= //
			$keyword_3 = "footerlap";
			$data_3 = array("value" => $this->input->post("footerlap"));
			$this->model->updateData($keyword_3, $data_3);

			// ========== Image (Logo/Icon) ======== //
			if (isset($_FILES["logo"]["name"])) {
				if (!file_exists(LOGOPATH.$smep->logo)) {

				}
				else{
					unlink(LOGOPATH.$smep->logo);
				}
				$config['upload_path']=LOGOPATH;
				$config['allowed_types']='gif|jpg|jpeg|png'; 
				//$config['encrypt_name'] = TRUE;

				$this->load->library('upload', $config);
				if ($this->upload->do_upload('logo')) {
					$data_upload = $this->upload->data();
					$keyword_4 = "logo";
					$data_4 = array("value" => $data_upload['file_name']);
					$this->model->updateData($keyword_4, $data_4);
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
			echo json_encode(array("status"=>TRUE));
		}
		else{
			redirect(base_url());
		}
	}
}