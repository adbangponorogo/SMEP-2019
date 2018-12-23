<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumpatan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('data-umum/datumpatan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/pagu-kegiatan/data');
		}
	}

	public function rincianPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/pagu-kegiatan/data-rincian');
		}
	}  

	public function getDataProgram($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataProgram($id_skpd);
			echo json_encode($result);
		}
	}

	public function getDataPaguSKPD($id_skpd){
		if ($this->session->userdata('auth_id') != "") {
			$result = $this->model->getDataPaguSKPD($id_skpd);
			echo json_encode($result);
		}
	}

	public function getDataKegiatan($id_skpd, $id_program){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataKegiatan($id_skpd, $id_program);
			echo json_encode($result);
		}
	}

	public function getDataKegiatanSumberDana($id_skpd, $token){
		if ($this->session->userdata('auth_id') != '') {
			$result_kegiatan = $this->model->getDataKegiatanUnique($token);
			$data = array();
			foreach ($result_kegiatan->result() as $rows_kegiatan) {
				$result_program = $this->model->getDataProgramUniqueIK($rows_kegiatan->id_program, $rows_kegiatan->kd_program);
				foreach ($result_program->result() as $rows_program) {
					$program = "[".$rows_program->kd_gabungan."] - ".$rows_program->keterangan_program;
				}
				$result_skpd = $this->model->getDataSKPD($id_skpd);
				foreach ($result_skpd->result() as $rows_skpd) {
					$result_rincian_obyek = $this->model->getDataSumPaguRincianObyek($rows_skpd->kd_skpd, $rows_kegiatan->kd_gabungan);
					foreach ($result_rincian_obyek->result() as $rows_rincian_obyek) {
						$total_pagu = "Rp. ".number_format($rows_rincian_obyek->jumlah);
					}
				}
				$data[] = array(
							$program,
							"[".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan,
							$total_pagu
						);
			}
			echo json_encode($data);
		}
	}

	public function getDataSumberData($id_skpd, $id_kegiatan){
		if ($this->session->userdata('auth_id') != '') {
			$result_skpd = $this->model->getDataSKPD($id_skpd);
			$data = array();
			$no = 1;
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_kegiatan = $this->model->getDataKegiatanUnique($id_kegiatan);
				foreach ($result_kegiatan->result() as $rows_kegiatan) {
					$result_rincian_obyek = $this->model->getDataRincianObyek($rows_skpd->kd_skpd, $rows_kegiatan->kd_gabungan);
					foreach ($result_rincian_obyek->result() as $rows_rincian_obyek) {
						$result_sumber_ro = $this->model->getDataSumberRealisasiObyek($rows_rincian_obyek->id);
						if ($result_sumber_ro->num_rows() > 0) {
							foreach ($result_sumber_ro->result() as $rows_sumber_ro) {
								switch ($rows_sumber_ro->sumber_dana) {
									case '1':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1' selected='selected'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '2':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2' selected='selected'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '3':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3' selected='selected'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '4':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4' selected='selected'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '5':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5' selected='selected'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '6':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6' selected='selected'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '7':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7' selected='selected'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '8':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8' selected='selected'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									case '9':
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9' selected='selected'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
									
									default:
										$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
										                    "<option value='1'>APBD Provinsi</option>".
										                    "<option value='2' selected='selected'>APBD Kabupaten/Kota</option>".
										                    "<option value='3'>APBN Pusat</option>".
										                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
										                    "<option value='5'>Dana Alokasi Khusus</option>".
										                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
										                    "<option value='7'>Dana Dekonsentrasi</option>".
										                    "<option value='8'>HIBAH dari Masyarakat</option>".
										                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
										                "</select>";
									break;
								}
							}
						}
						else{
							$sumber_dana = 	"<select class='form-control patandatum-sumber-rincian-obyek-update' data-id='".$rows_rincian_obyek->id."'>".
							                    "<option value='1'>APBD Provinsi</option>".
							                    "<option value='2' selected='selected'>APBD Kabupaten/Kota</option>".
							                    "<option value='3'>APBN Pusat</option>".
							                    "<option value='4'>Bantuan Luar Negeri / Pinjaman Luar Negeri</option>".
							                    "<option value='5'>Dana Alokasi Khusus</option>".
							                    "<option value='6'>Dana Bagi Hasil Cukai dan Tembakau</option>".
							                    "<option value='7'>Dana Dekonsentrasi</option>".
							                    "<option value='8'>HIBAH dari Masyarakat</option>".
							                    "<option value='9'>Hasil Swadaya Pihak ke-3</option>".
							                "</select>";
						}
						$data[] = array(
									$no++,
									"[".$rows_rincian_obyek->kd_rekening."]",
									$rows_rincian_obyek->nama_rekening,
									"Rp. ".number_format($rows_rincian_obyek->jumlah),
									$sumber_dana
								);
					}
				}
			}
			echo json_encode($data);
		}
	}

	public function saveData($id_rincian_obyek, $sumber_dana){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataSumberRealisasiObyek($id_rincian_obyek);
			if ($result->num_rows() <= 0) {
				$data = array("id_rincian_obyek"=>$id_rincian_obyek, "sumber_dana"=>$sumber_dana);
				$this->model->insertData($data);
			}
			else{
				$data = array("sumber_dana"=>$sumber_dana);
				$this->model->updateData($id_rincian_obyek, $data);
			}
			echo json_encode(array("status"=>TRUE));
		}
	}
}