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

				$result_realisasi_ro = $this->model->getDataRealisasiROSKPD($rows_skpd->kd_skpd);
				if ($result_realisasi_ro->num_rows() > 0) {
					foreach ($result_realisasi_ro->result() as $rows_realisasi_ro) {
						$pagu_realisasi_skpd = $rows_realisasi_ro->nilai;
					}
				}
				else{
					$pagu_realisasi_skpd = 0;
				}

				$result_realisasi = $this->model->getDataRealisasiSKPD($rows_skpd->kd_skpd, 'all');
				if ($result_realisasi->num_rows() > 0) {
					foreach ($result_realisasi->result() as $rows_realisasi) {
						$total_paket_realisasi = $rows_realisasi->total_paket_realisasi;
					}
				}
				else{
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

	public function getDataRealisasi($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_skpd = $this->model->getDataSKPD($id_skpd);
			$data = array();
			$no = 1;
			foreach ($result_skpd->result() as $rows_skpd) {
				$data[] = array(
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 1))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 2))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 3))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 4))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 5))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 6))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 7))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 8))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 9))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 10))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 11))),
									$this->valInt(strval($this->getDataRealisasiMonth($rows_skpd->kd_skpd, 12))),
							);
			}
			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}
	}

	public function getDataTemporary(){
		if ($this->session->userdata('auth_id') != '') {
			$result_user = $this->model->getDataUsers($this->session->userdata('auth_id'));
			foreach ($result_user->result() as $rows_user) {
				$result_skpd = $this->model->getDataSKPD($rows_user->id_skpd);
				$data = array();
				$no = 1;
				foreach ($result_skpd->result() as $rows_skpd) {
					$result_temp = $this->model->getDataTemporarySKPD($rows_user->id, $rows_skpd->kd_skpd);
					foreach ($result_temp->result() as $rows_temp) {
						$data[] = array(
									$no++,
									"<b>Tanggal : </b>".$rows_temp->tanggal."<br>".
									"<b>User : </b>[".$rows_temp->id_users."] - ".$rows_temp->nama_user."<br>".
									"<b>Keterangan : </b>".$rows_temp->keterangan,
									$rows_temp->ip_address
								);
					}
				}
			}
			echo json_encode($data);
		}
		else{
			redirect(base_url());
		}	
	}






	// ================= MISC ================= //

	public function getDataRealisasiMonth($kd_skpd, $bulan){
		$result_realisasi = $this->model->getDataRealisasiSKPD($kd_skpd, $bulan);
		if ($result_realisasi->num_rows() > 0) {
			foreach ($result_realisasi->result() as $rows_realisasi) {
				$realisasi = $rows_realisasi->jumlah;
			}
		}
		else{
			$realisasi = 0;
		}

		return $realisasi;
	}

	public function valInt($value){
		if ($value != '' || $value != NULL) {
			$val = intval($value);
		}
		else{
			$val = intval(0);
		}
		return $val;
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