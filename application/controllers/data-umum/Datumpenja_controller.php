<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumpenja_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('data-umum/datumpenja_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/penanggung-jawab/data');
		}
		else{
			redirect(base_url());
		}
	}

	public function getData($id_skpd){
		if ($this->session->userdata('auth_id') != "") {
			$result = $this->model->getAllData($id_skpd);
			$data = array();
			$no = 1;
			foreach ($result->result() as $rows) {
				switch ($rows->status) {
					case '1':
						$status = 'PPTK (Pejabat Pelaksana Teknis Kegiatan)';
					break;
					case '2':
						$status = 'PA (Pengguna Anggaran)';
					break;
					case '3':
						$status = 'PPK (Pejabat Pembuat Komitmen)';
					break;
					case '4':
						$status = 'KPA (Kuasa Pengguna Anggaran)';
					break;
					default:
						$status = 'PPTK Perangkat Daerah';
					break;
				}
				$data[] = array(
							$no++,
							$rows->nama,
							$rows->jabatan,
							$status,
							"<button class='btn btn-primary btn-sm smep-penjadatum-edit-btn' data-id='".$rows->id."'><i class='fa fa-edit'></i>&nbsp;Edit</button>".
							"&nbsp;<button class='btn btn-danger btn-sm smep-penjadatum-delete-btn' data-id='".$rows->id."'><i class='fa fa-trash'></i>&nbsp;Hapus</button>"
						);
			}
			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}

	public function sendData(){
		if ($this->session->userdata('auth_id') != "") {
			$data = array(
						"id_skpd" => $this->input->post("id_skpd"),
						"nama" => $this->input->post("nama"),
						"nip" => $this->input->post("nip"),
						"jabatan" => $this->input->post("jabatan"),
						"status" => $this->input->post("status"),
						"golongan" => $this->input->post("golongan"),
						"pangkat" => $this->input->post("pangkat")
					);
			$this->model->insertData($data);
			echo json_encode(array("status"=>TRUE));	
		}
		else{
			redirect(base_url());
		}
	}

	public function changeData($token){
		if ($this->session->userdata('auth_id') != "") {
			$result = $this->model->getData($token);
			$data = array();
			foreach ($result->result() as $rows) {
				$data[] = array(
							$rows->id,
							$rows->nama,
							$rows->nip,
							$rows->jabatan,
							$rows->status,
							$rows->golongan,
							$rows->pangkat
						);
			}
			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}
	
	public function updateData(){
		if ($this->session->userdata('auth_id') != "") {
			$data = array(
						"nama" => $this->input->post("nama"),
						"nip" => $this->input->post("nip"),
						"jabatan" => $this->input->post("jabatan"),
						"status" => $this->input->post("status"),
						"golongan" => $this->input->post("golongan"),
						"pangkat" => $this->input->post("pangkat")
					);
			$this->model->updateData($this->input->post("token"), $data);
			echo json_encode(array("status"=>TRUE));	
		}
		else{
			redirect(base_url());
		}
	}

	public function trashData($token){
		if ($this->session->userdata('auth_id')) {
			$this->model->deleteData($token);
			echo json_encode(array("status"=>TRUE));
		}
		else{
			redirect(base_url());
		}
	}
}