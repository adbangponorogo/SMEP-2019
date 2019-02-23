<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporbast_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laporbast_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/berita-acara-serah-terima/data');
		}
		else{
            redirect(base_url());
        }
	}

	public function getMainData($id_skpd){
		if ($this->session->userdata('auth_id') != "") {
			$result_rup = $this->model->getDataRUP($id_skpd);
			$data = array();
			$no = 1;
			foreach ($result_rup->result() as $rows_rup) {
				$result_realisasi = $this->model->getDataRealisasiRUP($rows_rup->id);
				foreach ($result_realisasi->result() as $rows_realisasi) {
					$data[] = array(
								$no++,
								$rows_rup->nama_paket,
								$this->nullValue($rows_realisasi->nomor_surat)."<br>".$this->nullValue($rows_realisasi->tanggal_surat_serah_terima),
								"Rp. ".number_format($this->nullIntValue($rows_realisasi->nilai_kontrak)),
								"Rp. ".number_format($this->nullIntValue($rows_realisasi->realisasi_keuangan)),
								$this->nullValue($rows_realisasi->nama_pemenang),
								"<button class='btn btn-info btn-sm smep-bastlapor-bast-btn' data-id='".$rows_realisasi->id."' onclick='return false;'>Bast</button>&nbsp;".
	                			"<button class='btn btn-primary btn-sm smep-bastlapor-lampiranbast-btn' data-id='".$rows_realisasi->id."' onclick='return false;'>Lampiran Bast</button>"
							);
				}
			}
			echo json_encode($data);
		}
		else{
            redirect(base_url());
        }
	}

	public function nullValue($value){
		if ($this->session->userdata('auth_id') != '') {
			if ($value == '' || is_null($value)) {
				$data = '-';
			}
			else{
				$data = $value;
			}
			return $data;
		}
		else{
            redirect(base_url());
        }
	}

	public function nullIntValue($value){
		if ($this->session->userdata('auth_id') != '') {
			if ($value == '' || is_null($value)) {
				$data = 0;
			}
			else{
				$data = $value;
			}
			return $data;
		}
		else{
            redirect(base_url());
        }
	}
}