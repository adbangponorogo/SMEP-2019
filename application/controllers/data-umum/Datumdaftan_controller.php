<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumdaftan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('data-umum/datumdaftan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/kegiatan/data');
		}
	}

	public function editKegiatanPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/kegiatan/data-kegiatan');
		}
	}

	public function getDataProgram($id_skpd){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataProgram($id_skpd);
			echo json_encode($result);
		}
	}

	public function getDataKegiatan($id_skpd, $id_program){
		$result = $this->model->getDataKegiatan($id_skpd, $id_program);
		echo json_encode($result);
	}

	public function changeDataKegiatan($id_skpd, $id){
		$result_kegiatan = $this->model->getDataKegiatanUnique($id);
		$data = array();
		foreach ($result_kegiatan->result() as $row_kegiatan) {
			$result_program = $this->model->getDataProgramUnique($row_kegiatan->id_program, $row_kegiatan->kd_program);
			foreach ($result_program->result() as $row_program) {
				if (is_null($row_kegiatan->kelompok_sasaran)) {
					$kelompok_sasaran = '';
				}
				if (!is_null($row_kegiatan->kelompok_sasaran)) {
					$kelompok_sasaran = $row_kegiatan->kelompok_sasaran;
				}
				if (is_null($row_kegiatan->waktu_pelaksanaan)) {
					$waktu_pelaksanaan = '';
				}
				if (!is_null($row_kegiatan->waktu_pelaksanaan)) {
					$waktu_pelaksanaan = $row_kegiatan->waktu_pelaksanaan;
				}

				$result_pptk_kegiatan = $this->model->getDataPPTKKegiatan($id_skpd, $row_kegiatan->id);
				if ($result_pptk_kegiatan->num_rows() > 0) {
					foreach ($result_pptk_kegiatan->result() as $row_pptk_kegiatan) {
						$result_pptk = $this->model->getDataPPTKUnique($row_pptk_kegiatan->id_pptk);
						foreach ($result_pptk->result() as $row_pptk) {
							$id_pptk = $row_pptk->id;
							$nama = $row_pptk->nama;
							$nip = $row_pptk->nip;
							$jabatan = $row_pptk->jabatan;

							switch ($row_pptk->status) {
								case '1':
									$status = 'PPTK Perangkat Daerah';
								break;
								case '2':
									$status = 'Kepala Perangkat Daerah';
								break;
								case '3':
									$status = 'PPK (Pejabat Pembuat Komitmen)';
								break;
								case '4':
									$status = 'KPA (Kuasa Pengguna Anggaran)';
								break;
								default:
									$status = 'PPTK Perangkat Daerah';
								break;
							}
						
							$data[] = array(
										$id_skpd,
										$row_kegiatan->id,
										"[".$row_program->kd_gabungan."] - ".$row_program->keterangan_program,
										"[".$row_kegiatan->kd_gabungan."] - ".$row_kegiatan->keterangan_kegiatan,
										$row_kegiatan->lokasi,
										$kelompok_sasaran,
										$waktu_pelaksanaan,
										$row_kegiatan->output,
										$id_pptk,
										$nama,
										$nip,
										$jabatan,
										$status
									);
						}
					}
				}
				else{
					$id_pptk = '';
					$nama = '';
					$nip = '';
					$jabatan = '';
					$status = '';

					$data[] = array(
									$id_skpd,
									$row_kegiatan->id,
									"[".$row_program->kd_gabungan."] - ".$row_program->keterangan_program,
									"[".$row_kegiatan->kd_gabungan."] - ".$row_kegiatan->keterangan_kegiatan,
									$row_kegiatan->lokasi,
									$kelompok_sasaran,
									$waktu_pelaksanaan,
									$row_kegiatan->output,
									$id_pptk,
									$nama,
									$nip,
									$jabatan,
									$status
								);
				}
			}

		}
		echo json_encode($data);
	}

	public function getDataPPTK($id_skpd){
		$result = $this->model->getDataPPTK($id_skpd);
		$data = array();
		foreach ($result->result() as $row) {
			switch ($row->status) {
				case '1':
					$status = 'PPTK Perangkat Daerah';
				break;
				case '2':
					$status = 'Kepala Perangkat Daerah';
				break;
				case '3':
					$status = 'PPK (Pejabat Pembuat Komitmen)';
				break;
				case '4':
					$status = 'KPA (Kuasa Pengguna Anggaran)';
				break;
				default:
					$status = 'PPTK Perangkat Daerah';
				break;
			}
			$data[] = array(
						$row->nama,
						$row->nip,
						$row->jabatan,
						$status,
						"<button class='btn btn-primary btn-sm smep-daftandatum-pptk-get-btn' data-id='".$row->id."'>".
						"Pilih</button>",
					);
		}

		echo json_encode($data);
	}

	public function getDataPPTKUnique($id){
		$result = $this->model->getDataPPTKUnique($id);
		$data = array();
		foreach ($result->result() as $row) {
			switch ($row->status) {
				case '1':
					$status = 'PPTK Perangkat Daerah';
				break;
				case '2':
					$status = 'Kepala Perangkat Daerah';
				break;
				case '3':
					$status = 'PPK (Pejabat Pembuat Komitmen)';
				break;
				case '4':
					$status = 'KPA (Kuasa Pengguna Anggaran)';
				break;
				default:
					$status = 'PPTK Perangkat Daerah';
				break;
			}
			$data[] = array(
						$row->id,
						$row->nama,
						$row->nip,
						$row->jabatan,
						$status,
					);
		}

		echo json_encode($data);
	}

	public function saveData(){
		$id_skpd = $this->input->post("id_skpd");
		$id_kegiatan = $this->input->post("id_kegiatan");
		$id_pptk = $this->input->post("id_pptk");
		$lokasi = $this->input->post("lokasi_kegiatan");
		$kelompok_sasaran = $this->input->post("kelompok_sasaran_kegiatan");
		$waktu_pelaksanaan = $this->input->post("jangka_waktu_kegiatan");
		$output = $this->input->post("output_kegiatan");
		
		if ($id_pptk != "") {
			$result = $this->model->getDataPPTKKegiatan($id_skpd, $id_kegiatan);
			if ($result->num_rows() <= 0) {
				$data_kegiatan = array(
							"lokasi" => $lokasi,
							"kelompok_sasaran" => $kelompok_sasaran,
							"waktu_pelaksanaan" => $waktu_pelaksanaan,
							"output" => $output
						);
				$data_pptk_kegiatan = array(
							"Tahun" => "2019",
							"id_skpd" => $id_skpd,
							"id_kegiatan" => $id_kegiatan,
							"id_pptk" => $id_pptk
						);
				$this->model->updateKegiatan($id_kegiatan, $data_kegiatan);
				$this->model->insertPPTKKegiatan($data_pptk_kegiatan);
			}
			else{
				$data_kegiatan = array(
							"lokasi" => $lokasi,
							"kelompok_sasaran" => $kelompok_sasaran,
							"waktu_pelaksanaan" => $waktu_pelaksanaan,
							"output" => $output
						);
				$data_pptk_kegiatan = array(
							"id_pptk" => $id_pptk
						);
				$this->model->updateKegiatan($id_kegiatan, $data_kegiatan);
				$this->model->updatePPTKKegiatan($id_skpd, $id_kegiatan, $data_pptk_kegiatan);
			}
		}
		else{
			$data_kegiatan = array(
							"lokasi" => $lokasi,
							"kelompok_sasaran" => $kelompok_sasaran,
							"waktu_pelaksanaan" => $waktu_pelaksanaan,
							"output" => $output
						);
			$this->model->updateKegiatan($id_kegiatan, $data_kegiatan);
		}
		echo json_encode(array("status"=>TRUE));
	}
}