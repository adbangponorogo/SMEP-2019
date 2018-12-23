<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumdator_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('data-umum/datumdator_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/data-organisasi/data');
		}
	}

	public function getMainData($id_skpd){
		if ($this->session->userdata('auth_id') != "") {
			$result = $this->model->getDataMasterRUP($id_skpd);
			$data = array();
			if ($result->num_rows() > 0) {
				foreach ($result->result() as $row) {
					$alamat = $row->alamat;
					$kode_pos = $row->kode_pos;
					$btl_pegawai = $row->btl_pegawai;
					$btl_non_pegawai = $row->btl_non_pegawai;
					$bl_pegawai = $row->belanja_langsung_pegawai;
					$bl_barang_jasa = $row->belanja_barang_jasa;
					$bl_modal = $row->belanja_modal;

					$data[] = array(
									$alamat,
									$kode_pos,
									$btl_pegawai,
									$btl_non_pegawai,
									$bl_pegawai,
									$bl_barang_jasa,
									$bl_modal
							);
				}
			}
			else{
				$result_skpd = $this->model->getDataSKPD($id_skpd);
				foreach ($result_skpd->result() as $row_skpd) {
					$result_ref_master_rup = $this->model->getDataMasterRefRUP($row_skpd->kd_skpd);
					foreach ($result_ref_master_rup->result() as $row_ref_master_rup) {
						$data[] = array(
										$row_ref_master_rup->alamat,
										"",
										$row_ref_master_rup->btl1,
										$row_ref_master_rup->btl2,
										$row_ref_master_rup->bl1,
										$row_ref_master_rup->bl2,
										$row_ref_master_rup->bl3,
								);
					}
				}
			}
			echo json_encode($data);
		}
	}

	public function saveData(){
		if ($this->session->userdata('auth_id') != "") {
			$id_skpd = $this->input->post("id_skpd");
			$alamat = $this->input->post("alamat");
			$kode_pos = $this->input->post("kode_pos");
			$btl_pegawai = $this->input->post("btl_pegawai");
			$btl_non_pegawai = $this->input->post("btl_non_pegawai");
			$bl_pegawai = $this->input->post("bl_pegawai");
			$bl_barang_jasa = $this->input->post("bl_barang_jasa");
			$bl_modal = $this->input->post("bl_modal");
			
			$result = $this->model->getDataMasterRUP($id_skpd);
			if ($result->num_rows() > 0) {
				$data = array(
							"id_skpd" => $id_skpd,
							"alamat" => $alamat,
							"kode_pos" => $kode_pos,
							"btl_pegawai" => $btl_pegawai,
							"btl_non_pegawai" => $btl_non_pegawai,
							"belanja_langsung_pegawai" => $bl_pegawai,
							"belanja_barang_jasa" => $bl_barang_jasa,
							"belanja_modal" => $bl_modal,
						);
				$this->model->updateData($id_skpd, $data);
			}
			else{
				$data = array(
							"id_skpd" => $id_skpd,
							"alamat" => $alamat,
							"kode_pos" => $kode_pos,
							"btl_pegawai" => $btl_pegawai,
							"btl_non_pegawai" => $btl_non_pegawai,
							"belanja_langsung_pegawai" => $bl_pegawai,
							"belanja_barang_jasa" => $bl_barang_jasa,
							"belanja_modal" => $bl_modal,
						);
				$this->model->insertData($data);
			}
			echo json_encode(array("status"=>TRUE));
		}
	}
}