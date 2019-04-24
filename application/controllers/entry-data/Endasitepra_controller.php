<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endasitepra_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('entry-data/Endasitepra_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/entry-data/realisasi-tepra/data');
		}
		else{
            redirect(base_url());
        }
	}

    public function formPage(){
        if ($this->session->userdata('auth_id') != "") {
        	$this->load->view('pages/entry-data/realisasi-tepra/data-form');
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
        		$result_kegiatan = $this->model->getDataKegiatanUnique($rows_rup->id_kegiatan);
        		foreach ($result_kegiatan->result() as $rows_kegiatan) {
        			$result_realisasi_tepra = $this->model->getDataRealisasiTepra($rows_rup->id);
        			if ($result_realisasi_tepra->num_rows() > 0) {
        				foreach ($result_realisasi_tepra->result() as $rows_realisasi_tepra) {
	        				$bulan = array(
	        							"-",
	        							"Januari",
	        							"Februari",
	        							"Maret",
	        							"April",
	        							"Mei",
	        							"Juni",
	        							"Juli",
	        							"Agustus",
	        							"September",
	        							"Oktober",
	        							"November",
	        							"Desember"
	        						);
	        				$proses_pengadaan = $bulan[$rows_realisasi_tepra->proses_pengadaan];
	        				$tanda_tangan_kontrak = $bulan[$rows_realisasi_tepra->tanda_tangan_kontrak];
	        				$pelaksanaan_pekerjaan = $bulan[$rows_realisasi_tepra->pelaksanaan_pekerjaan];
	        				$proses_hand_over = $bulan[$rows_realisasi_tepra->proses_hand_over];
	        			}
        			}
        			else{
        				$proses_pengadaan = '-';
        				$tanda_tangan_kontrak = '-';
        				$pelaksanaan_pekerjaan = '-';
        				$proses_hand_over = '-';
        			}

        			$data[] = array(
        					$no++,
        					$rows_kegiatan->kd_gabungan,
        					$rows_rup->nama_paket,
        					$proses_pengadaan,
        					$tanda_tangan_kontrak,
        					$pelaksanaan_pekerjaan,
        					$proses_hand_over,
        					"<button class='btn btn-primary btn-sm smep-sitepraenda-edit-btn' data-id='".$rows_rup->id."'>".
        						"<i class='fa fa-eye'></i>&nbsp;Realisasi".
        					"</button>"
        				);
        		}
        	}
        	echo json_encode($data);
        }
        else{
            redirect(base_url());
        }
    }

    public function getDataRealisasiTepra($id_rup){
    	if ($this->session->userdata('auth_id') != "") {
    		$result_rup = $this->model->getDataRUPUnique($id_rup);
	    	$data = array();
	    	foreach ($result_rup->result() as $rows_rup) {
	    		$result_program = $this->model->getDataProgram($rows_rup->id_program);
	    		foreach ($result_program->result() as $rows_program) {
	    			$result_kegiatan = $this->model->getDataKegiatan($rows_rup->id_kegiatan);
	    			foreach ($result_kegiatan->result() as $rows_kegiatan) {
	    				$result_realisasi_tepra = $this->model->getDataRealisasiTepra($rows_rup->id);
			    		if ($result_realisasi_tepra->num_rows() > 0) {
			    			foreach ($result_realisasi_tepra->result() as $rows_realisasi_tepra) {
			    				$proses_pengadaan = $rows_realisasi_tepra->proses_pengadaan;
			        			$tanda_tangan_kontrak = $rows_realisasi_tepra->tanda_tangan_kontrak;
			        			$pelaksanaan_pekerjaan = $rows_realisasi_tepra->pelaksanaan_pekerjaan;
			        			$proses_hand_over = $rows_realisasi_tepra->proses_hand_over;
			    			}
			    		}
			    		else{
			    			$proses_pengadaan = 1;
		        			$tanda_tangan_kontrak = 1;
		        			$pelaksanaan_pekerjaan = 1;
		        			$proses_hand_over = 1;
			    		}

			    		switch ($rows_rup->sumber_dana) {
		                    case '1':
		                        $sumber_dana = 'APBD';
		                    break;
		                    case '2':
		                        $sumber_dana = 'APBDP';
		                    break;
		                    case '3':
		                        $sumber_dana = 'APBN';
		                    break;
		                    case '4':
		                        $sumber_dana = 'APBNP';
		                    break;
		                    case '5':
		                        $sumber_dana = 'BLU';
		                    break;
		                    case '6':
		                        $sumber_dana = 'BLUD';
		                    break;
		                    case '7':
		                        $sumber_dana = 'BUMD';
		                    break;
		                    case '8':
		                        $sumber_dana = 'BUMN';
		                    break;
		                    case '9':
		                        $sumber_dana = 'PHLN';
		                    break;
		                    case '10':
		                        $sumber_dana = 'PNBP';
		                    break;
		                    case '11':
		                        $sumber_dana = 'Lainnya';
		                    break;
		                    default:
		                        $sumber_dana = 'Lainnya';
		                    break;
		                              }

			    		switch ($rows_rup->cara_pengadaan) {
		                    case '1':
		                         $cara_pengadaan = "Melalui Penyedia";
		                    break;
		                    case '2':
		                         $cara_pengadaan = "Melalui Swakelola";
		                    break;
		                    
		                    default:
		                         $cara_pengadaan = "Melalui Penyedia";
		                    break;
		               	}

		               	switch ($rows_rup->jenis_pengadaan) {
		                    case '1':
		                         $jenis_pengadaan = "Barang";
		                    break;
		                    case '2':
		                         $jenis_pengadaan = "Konstruksi";
		                    break;
		                    case '3':
		                         $jenis_pengadaan = "Jasa Konsultasi";
		                    break;
		                    case '4':
		                         $jenis_pengadaan = "Jasa Lainnya";
		                    break;
		                    
		                    default:
		                         $jenis_pengadaan = "Barang";
		                    break;
		               	}

		               	switch ($rows_rup->metode_pemilihan) {
		               		case '1':
		               			$metode_pemilihan = "E-Purchasing";
		               		break;
		               		case '2':
		               			$metode_pemilihan = "Tender";
		               		break;
		               		case '3':
		               			$metode_pemilihan = "Tender Cepat";
		               		break;
		               		case '4':
		               			$metode_pemilihan = "Pengadaan Langsung";
		               		break;
		               		case '5':
		               			$metode_pemilihan = "Penunjukkan Langsung";
		               		break;
		               		case '6':
		               			$metode_pemilihan = "Seleksi";
		               		break;
		               		
		               		default:
		               			$metode_pemilihan = "E-Purchasing";
		               		break;
		               	}

			    		$data[] = array(
			    				$rows_rup->id,
			    				"[".$rows_program->kd_gabungan."] - ".$rows_program->keterangan_program,
			    				"[".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan,
			    				$rows_rup->nama_paket,
			    				"Rp. ".number_format($rows_rup->pagu_paket),
			    				$sumber_dana,
			    				$cara_pengadaan,
			    				$jenis_pengadaan,
			    				$metode_pemilihan,
			    				$proses_pengadaan,
		        				$tanda_tangan_kontrak,
		        				$pelaksanaan_pekerjaan,
		        				$proses_hand_over,
			    				$rows_rup->id_rup_awal
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

    public function saveData(){
    	if ($this->session->userdata('auth_id') != '') {
    		$result = $this->model->getDataRealisasiTepra($this->input->post('id_rup'));
    		if ($result->num_rows() <= 0) {
    			$data = array(
    				"id_rup" => $this->input->post("id_rup"),
    				"id_rup_awal" => $this->input->post("id_rup_awal"),
    				"proses_pengadaan" => $this->input->post("proses_pengadaan"),
    				"tanda_tangan_kontrak" => $this->input->post("tanda_tangan_kontrak"),
    				"pelaksanaan_pekerjaan" => $this->input->post("pelaksanaan_pekerjaan"),
    				"proses_hand_over" => $this->input->post("proses_hand_over"),
    			);
    			$this->model->insertData($data);
    		}
    		else{
    			$data = array(
    				"proses_pengadaan" => $this->input->post("proses_pengadaan"),
    				"tanda_tangan_kontrak" => $this->input->post("tanda_tangan_kontrak"),
    				"pelaksanaan_pekerjaan" => $this->input->post("pelaksanaan_pekerjaan"),
    				"proses_hand_over" => $this->input->post("proses_hand_over"),
    			);
    			$this->model->updateData($this->input->post("id_rup"), $data);
    		}
    		echo json_encode(array("status"=>TRUE));
    	}
    	else{
            redirect(base_url());
        }
    }
}