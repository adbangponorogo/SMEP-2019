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
	
/*
	PROGRAM [{
		"ID_PROGRAM": 855, // id sequence anda
		"ID_SATKER": "506327",
		"NAMA_PROGRAM": "Peningkatan Sarana dan Prasarana",
		"KODE_PROGRAM" : "01",
		"PAGU": 100012413,
		"IS_DELETED": false,
		"CREATE_TIME": 12973273,
		"LASTUPDATE_TIME": 1463964143
	}]
*/

	public function program(){
		$rs = $this->model->getProgram();
		$data = array();
		foreach ($rs->result() as $d) {
			$data[] = array(
												"ID_PROGRAM" => ('2019'.$d->id_sirup)+0,
												"ID_SATKER" => "14834",
												"NAMA_PROGRAM" => $d->keterangan_program,
												"KODE_PROGRAM" => $d->kd_gabungan,
												"PAGU" => $d->jumlah+0,
												"IS_DELETED" => false,
												"CREATE_TIME" => $d->updated+0,
												"LASTUPDATE_TIME" => $d->updated+0
											);
		}
		echo json_encode($data);
	}

	public function kegiatan(){
	}
}