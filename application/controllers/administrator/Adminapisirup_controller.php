<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminapisirup_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/apisirup_model', 'model');
	}

	public function index(){
		$this->load->view('pages/administrator/api-sirup/data');
	}

	public function program(){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getProgram();
			$data = array();
			foreach ($result->result() as $rows) {
				$data[]		= 	array(
									"ID_PROGRAM" => ('2019'.$rows->id_sirup)+0,
									"ID_SATKER" => "14834",
									"NAMA_PROGRAM" => $rows->keterangan_program,
									"KODE_PROGRAM" => $rows->kd_gabungan,
									"PAGU" => $rows->jumlah+0,
									"IS_DELETED" => false,
									"CREATE_TIME" => $rows->updated_unix+0,
									"LASTUPDATE_TIME" => $rows->updated_unix+0
								);
			}
			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}

	public function kegiatan(){
		if ($this->session->userdata('auth_id') != '') {
			$result_program = $this->model->getProgram();
			$data = array();
			foreach ($result_program->result() as $rows_program) {
				$result_kegiatan = $this->model->getKegiatan($rows_program->kd_skpd, $rows_program->id_program, $rows_program->kd_program);
				foreach ($result_kegiatan->result() as $rows_kegiatan) {
					$data[]	=	array(
									"ID_PROGRAM" => 855,
							        "ID_KEGIATAN" => 857,
							        "ID_SATKER" => "506327",
							        "NAMA_KEGIATAN" => "Peningkatan Sarana dan Prasarana",
							        "KODE_KEGIATAN" => "8942",
							        "PAGU" => 100012413,
							        "IS_DELETED" => false,
							        "CREATE_TIME" => 12973273,
							        "LASTUPDATE_TIME" => 1463964143
								);
				}
			}
			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}

	public function objekAkun(){
		if ($this->session->userdata('auth_id') != '') {
			
		}
		else{
			redirect(base_url());
		}
	}

	public function rincianObjekAkun(){
		if ($this->session->userdata('auth_id') != '') {
			
		}
		else{
			redirect(base_url());
		}
	}

	public function usersPPK(){
		if ($this->session->userdata('auth_id') != '') {
			
		}
		else{
			redirect(base_url());
		}
	}

	public function penyedia(){
		if ($this->session->userdata('auth_id') != '') {
			
		}
		else{
			redirect(base_url());
		}
	}

	public function swakelola(){
		if ($this->session->userdata('auth_id') != '') {
			
		}
		else{
			redirect(base_url());
		}
	}

	public function historiRevisiPaket(){
		if ($this->session->userdata('auth_id') != '') {
			
		}
		else{
			redirect(base_url());
		}
	}

	public function strukturAnggaran(){
		if ($this->session->userdata('auth_id') != '') {
			
		}
		else{
			redirect(base_url());
		}
	}
}