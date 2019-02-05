<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminkonfigurasi_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('administrator/adminkonfigurasi_model', 'cfg_model');
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
			$result_tingkat = $this->cfg_model->getDataConfig('tingkat');
			if ($result_tingkat->num_rows() > 0) {
				foreach ($result_tingkat->result() as $rows_tingkat) {
					$tingkat = $rows_tingkat->value;
				}
			}
			else{
				$tingkat = '';
			}

			// ============== K/L/P/D ============== //
			$result_klpd = $this->cfg_model->getDataConfig('klpd');
			if ($result_klpd->num_rows() > 0) {
				foreach ($result_klpd->result() as $rows_klpd) {
					$klpd = $rows_klpd->value;
				}
			}
			else{
				$klpd = '';
			}

			// ============= Footerlap ============= //
			$result_footerlap = $this->cfg_model->getDataConfig('footerlap');
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
			$result_tingkat = $this->cfg_model->getDataConfig('tingkat');
			if ($result_tingkat->num_rows() > 0) {
				$keyword = "tingkat";
				$data = array("value" => $this->input->post("tingkat"));
				$this->cfg_model->updateData($keyword, $data);
			}
			else{
				$data = array(
								"key"	=> "tingkat",
								"value" => $this->input->post("tingkat")
							);
				$this->cfg_model->uploadData($data);
			}

			// // ============== K/L/P/D ============== //
			$result_klpd = $this->cfg_model->getDataConfig('klpd');
			if ($result_klpd->num_rows() > 0) {
				$keyword = "klpd";
				$data = array("value" => $this->input->post("klpd"));
				$this->cfg_model->updateData($keyword, $data);
			}
			else{
				$data = array(
								"key"	=> "klpd",
								"value" => $this->input->post("klpd")
							);
				$this->cfg_model->uploadData($data);
			}

			// // ============= Footerlap ============= //
			$result_footerlap = $this->cfg_model->getDataConfig('footerlap');
			if ($result_footerlap->num_rows() > 0) {
				$keyword = "footerlap";
				$data = array("value" => $this->input->post("footerlap"));
				$this->cfg_model->updateData($keyword, $data);
			}
			else{
				$data = array(
								"key"	=> "footerlap",
								"value" => $this->input->post("footerlap")
							);
				$this->cfg_model->uploadData($data);
			}

			// ========== Image (Logo/Icon) ======== //
			if (isset($_FILES["logo"]["name"])) {
				if (file_exists(LOGOPATH.$smep->logo)) unlink(LOGOPATH.$smep->logo);
				$config['upload_path']=LOGOPATH;
				$config['allowed_types']='gif|jpg|jpeg|png';
				//$config['encrypt_name'] = TRUE;

				$this->load->library('upload', $config);
				if ($this->upload->do_upload('logo')) {
					$data = $this->upload->data();
					$data_upload = array(
												"key"	=> "logo",
												"value" => $data['file_name']
											);
					$this->cfg_model->uploadData($data_upload);
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