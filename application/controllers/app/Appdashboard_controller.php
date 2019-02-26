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
			$data = array();
			foreach ($result_skpd->result() as $rows_skpd) {

				// ================= Anggaran SKPD ================= //
				// ================================================= //
				$result_anggaran = $this->model->getDataAnggaranSKPD($rows_skpd->kd_skpd);
				if ($result_anggaran->num_rows() > 0) {
					foreach ($result_anggaran->result() as $rows_anggaran) {
						$anggaran_skpd = $rows_anggaran->pagu;
					}
				}
				else{
					$anggaran_skpd = 0;
				}


				// =================== RUP SKPD ==================== //
				// ================================================= //
				$result_rup = $this->model->getDataRUP($rows_skpd->kd_skpd);
				if ($result_rup->num_rows() > 0) {
					foreach ($result_rup->result() as $rows_rup) {
						$total_paket_rup = $rows_rup->total_paket;
						$pagu_rup = $rows_rup->pagu_paket;
					}
				}
				else{
					$total_paket_rup = 0;
					$pagu_rup = 0;
				}


				// ================= Realisasi SKPD ================ //
				// ================================================= //
				$result_realisasi = $this->model->getDataRealisasiSKPD($rows_skpd->kd_skpd);
				if ($result_realisasi->num_rows() > 0) {
					foreach ($result_realisasi->result() as $rows_realisasi) {
						$total_paket_realisasi = $rows_realisasi->total_paket_realisasi;
						$pagu_realisasi_skpd = $rows_realisasi->jumlah;
					}
				}
				else{
					$pagu_realisasi_skpd = 0;
					$total_paket_realisasi = 0;
				}

				// ================= Akunting Data ================= //
				// ================================================= //
				$sisa_pagu_rup = $anggaran_skpd - $pagu_rup;
				$pagu_non_terealisasi = $pagu_rup - $pagu_realisasi_skpd;
				$paket_non_terealisasi = $total_paket_rup - $total_paket_realisasi;

				$data[] = array(
									$this->MoneyFormat($anggaran_skpd),
									$this->MoneyFormat($pagu_rup),
									$this->MoneyFormat($sisa_pagu_rup),
									$total_paket_rup,
									$this->MoneyFormat($pagu_realisasi_skpd),
									$this->MoneyFormat($pagu_non_terealisasi),
									$total_paket_realisasi,
									$paket_non_terealisasi
							);
			}

			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}

	public function getDataPaketNonRealisasi($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_skpd = $this->model->getDataSKPD($id_skpd);
			$data = array();
			$no = 1;
			foreach ($result_skpd->result() as $rows_skpd) {
				$result = $this->model->getDataRUPNonRealisasi($rows_skpd->kd_skpd);
				foreach ($result->result() as $rows) {
					$data[] = array($no++, $rows->nama_paket, $this->MoneyFormat($rows->pagu_paket));
				}
			}
			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}

	public function MoneyFormat($money){
		if ($this->session->userdata('auth_id') != '') {
			return "Rp. ".number_format($money);
		}
		else{
			redirect(base_url());
		}
	}

}