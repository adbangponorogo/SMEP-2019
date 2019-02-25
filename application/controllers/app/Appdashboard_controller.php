<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appdashboard_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('app/appdashboard_model', 'model');
	}

	public function mainPage(){
		$this->load->view('pages/app/dashboard/data');
	}

	public function getDataRekap($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_skpd = $this->model->getDataSKPD($id_skpd);
			foreach ($result_skpd->result() as $rows_skpd) {

				// ================= Anggaran SKPD ================= //
				// ================================================= //
				$result_anggaran = $this->model->getDataAnggaranSKPD($rows_skpd->kd_skpd);
				if ($result_anggaran->num_rows() > 0) {
					foreach ($result_anggaran->result() as $rows_anggaran) {
						$anggaran_skpd = intval($rows_anggaran->pagu);
					}
				}
				else{
					$anggaran_skpd = intval(0);
				}

				// ================= Realisasi SKPD ================ //
				// ================================================= //
				$result_realisasi = $this->model->getDataRealisasiSKPD($rows_skpd->kd_skpd);
				if ($result_realisasi->num_rows() > 0) {
					foreach ($result_realisasi->result() as $rows_realisasi) {
						$realisasi_skpd = intval($rows_realisasi->jumlah);
					}
				}
				else{
					$realisasi_skpd = intval(0);
				}

				// ============== Sisa Pagu RUP SKPD =============== //
				// ================================================= //
				$result_pagu_rup = $this->model->getDataPaguRUP($rows_skpd->kd_skpd);
				if ($result_pagu_rup->num_rows() > 0) {
					foreach ($result_pagu_rup->result() as $rows_pagu_rup) {
						$pagu_rup = intval($rows_pagu_rup->pagu_paket);
						$sisa_pagu_rup = $anggaran_skpd - $pagu_rup;
					}
				}
				else{
					$pagu_rup = intval(0);
					$sisa_pagu_rup = $anggaran_skpd - $pagu_rup;
				}

				// =========== Sisa Pagu Realisasi SKPD ============ //
				// ================================================= //
				$sisa_pagu_non_terealisasi = $pagu_rup - $realisasi_skpd;


			}
		}
		else{
			redirect(base_url());
		}
	}

	public function MoneyFormat($money){
		if ($this->session->userdata('auth_id') != '') {
			return "Rp.".number_format($money);
		}
		else{
			redirect(base_url());
		}
	}

}