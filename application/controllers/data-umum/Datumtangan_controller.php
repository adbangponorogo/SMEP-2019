<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumtangan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('data-umum/datumtangan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/target-keuangan/data');
		}
	}

	public function getMainData($id_skpd){
		if ($this->session->userdata('auth_id') != "") {
			$result_skpd = $this->model->getDataSKPD($id_skpd);
			$data = array();
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_pagu = $this->model->getDataSumPagu($rows_skpd->kd_skpd);
				foreach ($result_pagu->result() as $rows_pagu) {
					$rata_rata_pagu = intval($rows_pagu->pagu)/12;
					$data[] = array(
							$rows_skpd->kd_skpd,
							$rows_skpd->nama_skpd,
							"Rp. ".number_format($rows_pagu->pagu),
							"Rp. ".number_format($rata_rata_pagu,2)
					);
				}
			}
			echo json_encode($data);
		}
	}

	public function getMainKeuanganData($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_rencana_keuangan = $this->model->getDataRencanaKeuangan($id_skpd);
			$data = array();

			$result_skpd = $this->model->getDataSKPD($id_skpd);
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_pagu = $this->model->getDataSumPagu($rows_skpd->kd_skpd);
				foreach ($result_pagu->result() as $rows_pagu) {
					$pagu  = $rows_pagu->pagu;
				}
			}
			if ($result_rencana_keuangan->num_rows() > 0) {
				foreach ($result_rencana_keuangan->result() as $rows_rencana_keuangan) {
					$januari = $rows_rencana_keuangan->januari;
					$februari = $rows_rencana_keuangan->februari;
					$maret = $rows_rencana_keuangan->maret;
					$april = $rows_rencana_keuangan->april;
					$mei = $rows_rencana_keuangan->mei;
					$juni = $rows_rencana_keuangan->juni;
					$juli = $rows_rencana_keuangan->juli;
					$agustus = $rows_rencana_keuangan->agustus; 
					$september = $rows_rencana_keuangan->september;
					$oktober = $rows_rencana_keuangan->oktober;
					$november = $rows_rencana_keuangan->november;
					$desember = $rows_rencana_keuangan->desember;
				}
			}
			else{
				$result_skpd = $this->model->getDataSKPD($id_skpd);
				foreach ($result_skpd->result() as $rows_skpd) {
					$result_akas_kegiatan = $this->model->getDataAkasKegiatan($rows_skpd->kd_skpd);
					foreach ($result_akas_kegiatan->result() as $rows_akas_kegiatan) {
						$januari = $rows_akas_kegiatan->januari;
						$februari = $rows_akas_kegiatan->februari;
						$maret = $rows_akas_kegiatan->maret;
						$april = $rows_akas_kegiatan->april;
						$mei = $rows_akas_kegiatan->mei;
						$juni = $rows_akas_kegiatan->juni;
						$juli = $rows_akas_kegiatan->juli;
						$agustus = $rows_akas_kegiatan->agustus; 
						$september = $rows_akas_kegiatan->september;
						$oktober = $rows_akas_kegiatan->oktober;
						$november = $rows_akas_kegiatan->november;
						$desember = $rows_akas_kegiatan->desember;
					}
				}
			}

			$data[] = array($pagu, $januari, $februari, $maret, $april, $mei, $juni, $juli, $agustus, $september, $oktober, $november, $desember);
			echo json_encode($data);
		}
	}

	public function saveData(){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataRencanaKeuangan($this->input->post("id_skpd"));
			if ($result->num_rows() <= 0) {
				$data = array(
							"id_skpd" => $this->input->post("id_skpd"),
							"januari" => $this->input->post("januari"),
							"februari" => $this->input->post("februari"),
							"maret" => $this->input->post("maret"),
							"april" => $this->input->post("april"),
							"mei" => $this->input->post("mei"),
							"juni" => $this->input->post("juni"),
							"juli" => $this->input->post("juli"),
							"agustus" => $this->input->post("agustus"),
							"september" => $this->input->post("september"),
							"oktober" => $this->input->post("oktober"),
							"november" => $this->input->post("november"),
							"desember" => $this->input->post("desember")
						);
				$this->model->insertData($data);
			}
			else{
				$data = array(
							"januari" => $this->input->post("januari"),
							"februari" => $this->input->post("februari"),
							"maret" => $this->input->post("maret"),
							"april" => $this->input->post("april"),
							"mei" => $this->input->post("mei"),
							"juni" => $this->input->post("juni"),
							"juli" => $this->input->post("juli"),
							"agustus" => $this->input->post("agustus"),
							"september" => $this->input->post("september"),
							"oktober" => $this->input->post("oktober"),
							"november" => $this->input->post("november"),
							"desember" => $this->input->post("desember")
						);
				$this->model->updateData($this->input->post('id_skpd'), $data);
			}

			echo json_encode(array("status"=>TRUE));
		}
	}
}