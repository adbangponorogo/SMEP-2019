<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumtasik_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('data-umum/datumtasik_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/target-fisik/data');
		}
	}

	public function getMainData($id_skpd){
		if ($this->session->userdata('auth_id') != "") {
			$result_skpd = $this->model->getDataSKPD($id_skpd);
			$data = array();
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_pagu = $this->model->getDataSumPagu($rows_skpd->kd_skpd);
				foreach ($result_pagu->result() as $rows_pagu) {
					$data[] = array(
							$rows_skpd->kd_skpd,
							$rows_skpd->nama_skpd,
							"Rp. ".number_format($rows_pagu->pagu)
					);
				}
			}
			echo json_encode($data);
		}
	}

	public function getMainFisikData($id_skpd){
		if ($this->session->userdata('auth_id') != "") {
			$result_rencana_fisik = $this->model->getDataRencanaFisik($id_skpd);
			$data = array();
			if ($result_rencana_fisik->num_rows() > 0) {
				foreach ($result_rencana_fisik->result() as $rows_rencana_fisik) {
					$januari = $rows_rencana_fisik->januari;
					$februari = $rows_rencana_fisik->februari;
					$maret = $rows_rencana_fisik->maret;
					$april = $rows_rencana_fisik->april;
					$mei = $rows_rencana_fisik->mei;
					$juni = $rows_rencana_fisik->juni;
					$juli = $rows_rencana_fisik->juli;
					$agustus = $rows_rencana_fisik->agustus;
					$september = $rows_rencana_fisik->september;
					$oktober = $rows_rencana_fisik->oktober;
					$november = $rows_rencana_fisik->november;
					$desember = $rows_rencana_fisik->desember;
				}
			}
			else{
				$januari = 0;
				$februari = 0;
				$maret = 0;
				$april = 0;
				$mei = 0;
				$juni = 0;
				$juli = 0;
				$agustus = 0;
				$september = 0;
				$oktober = 0;
				$november = 0;
				$desember = 0;
			}
			$data[] = array(
						$januari,
						$februari,
						$maret,
						$april,
						$mei,
						$juni,
						$juli,
						$agustus,
						$september,
						$oktober,
						$november,
						$desember
					);
			echo json_encode($data);
		}
	}

	public function saveData(){
		if ($this->session->userdata('auth_id') != "") {
			$result = $this->model->getDataRencanaFisik($this->input->post("id_skpd"));
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
				$this->model->updateData($this->input->post("id_skpd"), $data);
			}
			echo json_encode(array("status"=>TRUE));
		}
	}
}