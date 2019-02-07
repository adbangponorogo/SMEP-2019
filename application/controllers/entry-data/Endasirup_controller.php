<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endasirup_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('entry-data/Endasirup_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/entry-data/realisasi-rup/data');
		}
          else{
               redirect(base_url());
          }
	}
     
     public function realisasiPage(){
          if ($this->session->userdata('auth_id') != "") {
               $this->load->view('pages/entry-data/realisasi-rup/data-realisasi');
          }
          else{
               redirect(base_url());
          }
     }

     public function registerDataPage(){
          if ($this->session->userdata('auth_id') != "") {
               $this->load->view('pages/entry-data/realisasi-rup/data-register');
          }
          else{
               redirect(base_url());
          }
     }

     public function editDataPage(){
          if ($this->session->userdata('auth_id') != "") {
               $this->load->view('pages/entry-data/realisasi-rup/data-edit');
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataAllKegiatan($id_skpd){
          if ($this->session->userdata('auth_id') != "") {
               $result = $this->model->getDataAllKegiatan($id_skpd);
               echo json_encode($result);
          }
          else{
               redirect(base_url());
          }
     }

     public function getRUPDataAllKegiatan($id_skpd, $id_kegiatan){
          if ($this->session->userdata('auth_id') != '') {
               if ($id_kegiatan == "all") {
                    $result_rup = $this->model->getRUPDataAllKegiatan($id_skpd);
               }
               else{
                    $result_rup = $this->model->getRUPDataUniqueKegiatan($id_skpd, $id_kegiatan);
               }
               $data = array();
               $no = 1;
               foreach ($result_rup->result() as $rows_rup) {
                    $result_kegiatan = $this->model->getDataKegiatan($rows_rup->id_kegiatan);
                    foreach ($result_kegiatan->result() as $rows_kegiatan) {
                         $result_realisasi = $this->model->getDataSumRealisasiRUP($rows_rup->id);
                              foreach ($result_realisasi->result() as $rows_realisasi) {
                                   if (is_null($rows_realisasi->realisasi_keuangan)) {
                                   $realisasi_keuangan = 0;
                              }
                              if (!is_null($rows_realisasi->realisasi_keuangan)) {
                                   $realisasi_keuangan = $rows_realisasi->realisasi_keuangan;
                              }
                              if (is_null($rows_realisasi->realisasi_fisik)) {
                                   $realisasi_fisik = 0;
                              }
                              if (!is_null($rows_realisasi->realisasi_fisik)) {
                                   $realisasi_fisik = $rows_realisasi->realisasi_fisik;
                              }

                              $data[] = array(
                                        $no++,
                                        $rows_kegiatan->kd_gabungan,
                                        $rows_rup->nama_paket,
                                        number_format($rows_rup->pagu_paket),
                                        $rows_rup->lokasi_pekerjaan,
                                        "Rp. ".number_format($realisasi_keuangan),
                                        $realisasi_fisik."%",
                                        "<button class='btn btn-primary btn-sm smep-sirupenda-ro-edit-btn' data-id='".$rows_rup->id."'>".
                                        "<i class='fa fa-edit'></i>&nbsp;Realisasi</button>"
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

     public function getDataRealisasiKeuangan($id_rup){
          if ($this->session->userdata('auth_id') != "") {
               $result = $this->model->getDataSumRealisasiRUP($id_rup);
               foreach ($result->result() as $rows) {
                    if (is_null($rows->realisasi_keuangan)) {
                         $realisasi_keuangan = 0;
                    }
                    if (!is_null($rows->realisasi_keuangan)) {
                         $realisasi_keuangan = intval($rows->realisasi_keuangan);
                    }
               }
               return $realisasi_keuangan;
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataRUP($id_rup){
          if ($this->session->userdata('auth_id') != '') {
               $result_rup = $this->model->getDataRUP($id_rup);
               $data = array();
               foreach ($result_rup->result() as $rows_rup) {
                    $result_program = $this->model->getDataProgram($rows_rup->id_program);
                    foreach ($result_program->result() as $rows_program) {
                         $result_kegiatan = $this->model->getDataKegiatan($rows_rup->id_kegiatan);
                         foreach ($result_kegiatan->result() as $rows_kegiatan) {
                              $realisasi_keuangan = $this->getDataRealisasiKeuangan($rows_rup->id);
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

                              switch ($rows_rup->jenis_pengadaan) {
                                   case '1':
                                        $jenis_pengadaan = 'Barang';
                                   break;
                                   case '2':
                                        $jenis_pengadaan = 'Konstruksi';
                                   break;
                                   case '3':
                                        $jenis_pengadaan = 'Jasa Konsultasi';
                                   break;
                                   case '4':
                                        $jenis_pengadaan = 'Jasa Lainnya';
                                   break;
                                   default:
                                        $jenis_pengadaan = 'Barang';
                                   break;
                              }

                              switch ($rows_rup->metode_pemilihan) {
                                   case '1':
                                        $metode_pemilihan = 'E-Purchasing';
                                   break;
                                   case '2':
                                        $metode_pemilihan = 'Tender';
                                   break;
                                   case '3':
                                        $metode_pemilihan = 'Tender Cepat';
                                   break;
                                   case '4':
                                        $metode_pemilihan = 'Pengadaan Langsung';
                                   break;
                                   case '5':
                                        $metode_pemilihan = 'Penunjukkan Langsung';
                                   break;
                                   case '6':
                                        $metode_pemilihan = 'Seleksi';
                                   break;
                                   default:
                                        $metode_pemilihan = '-';
                                   break;
                              }

                              $sisa_pagu = intval($rows_rup->pagu_paket) - intval($realisasi_keuangan);
                              $data[] = array(
                                        "[".$rows_program->kd_gabungan."] - ".$rows_program->keterangan_program,
                                        "[".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan,
                                        $rows_rup->nama_paket,
                                        "Rp. ".number_format($rows_rup->pagu_paket),
                                        $rows_rup->pagu_paket,
                                        $sisa_pagu,
                                        $sumber_dana,
                                        $jenis_pengadaan,
                                        $metode_pemilihan
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

     public function getDataRUPRealisasi($id_rup){
          if ($this->session->userdata('auth_id') != "") {
               $result_rup = $this->model->getDataRUP($id_rup);
               $data = array();
               foreach ($result_rup->result() as $rows_rup) {
                    $result_rincian_obyek = $this->model->getDataRincianObyek($rows_rup->id_rincian_obyek);
                    foreach ($result_rincian_obyek->result() as $rows_ro) {
                         $result_realisasi = $this->model->getDataSumRealisasiRUP($rows_rup->id);
                         foreach ($result_realisasi->result() as $rows_realisasi) {
                              if (is_null($rows_realisasi->realisasi_keuangan)) {
                                   $realisasi_keuangan = 0;
                              }
                              if (!is_null($rows_realisasi->realisasi_keuangan)) {
                                   $realisasi_keuangan = $rows_realisasi->realisasi_keuangan;
                              }
                              if (is_null($rows_realisasi->realisasi_fisik)) {
                                   $realisasi_fisik = 0;
                              }
                              if (!is_null($rows_realisasi->realisasi_fisik)) {
                                   $realisasi_fisik = $rows_realisasi->realisasi_fisik;
                              }

                              $sisa_pagu = $rows_rup->pagu_paket - $realisasi_keuangan;
                              if ($sisa_pagu > 0) {
                                   $status = 0;
                              }
                              if ($sisa_pagu <= 0) {
                                   $status = 1;
                              }

                              $data[] = array(
                                        $rows_rup->nama_paket,
                                        "[".$rows_ro->kd_gabungan."] - ".$rows_ro->nama_rekening,
                                        "Rp. ".number_format($rows_rup->pagu_paket),
                                        "Rp. ".number_format($sisa_pagu),
                                        "Rp. ".number_format($realisasi_keuangan),
                                        $realisasi_fisik."%",
                                        $status
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

     public function getMainDataRealisasi($id_rup){
          if ($this->session->userdata('auth_id') != "") {
               $result = $this->model->getDataRealisasiRUP($id_rup);
               $data = array();
               $no = 1;
               foreach ($result->result() as $rows) {
                    $row_first = $no++."<input type='checkbox' name='token_data[]' style='margin-left:5px;' class='sirupenda-delete-data' value='".$rows->id."'>";
/* 
                    if (is_null($rows->nama_pemenang)) {
                         $nama_pemenang = '-';
                    }
                    if (!is_null($rows->nama_pemenang) && $rows->nama_pemenang == "") {
                         $nama_pemenang = '-';
                    }
                    if (!is_null($rows->nama_pemenang) && $rows->nama_pemenang != "") {
                         $nama_pemenang = $rows->nama_pemenang;
                    }
 */
                    if ($rows->nama_pemenang)
                         $nama_pemenang = $rows->nama_pemenang;
										else
                         $nama_pemenang = $rows->penyedia_swakelola;
											 
                    $data[] = array(
                              $row_first,
                              $rows->nomor_bukti,
                              $nama_pemenang,
                              $rows->tanggal_pencairan,
                              "Rp. ".number_format($rows->realisasi_keuangan),
                              $rows->realisasi_fisik."%",
                              "<button class='btn btn-primary btn-sm smep-sirupenda-edit-btn' data-id='".$rows->id."'><i class='fa fa-edit'></i>&nbsp;Edit</button>".
                              "&nbsp;<button class='btn btn-danger btn-sm smep-sirupenda-delete-btn' data-id='".$rows->id."'><i class='fa fa-trash'></i>&nbsp;Hapus</button>"
                         );
               }
               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

	public function uploadData(){
		if ($this->session->userdata('auth_id') != '') {

			if (count(explode('-', $this->input->post("tanggal_pencairan"))) == 3)
			list(, $bulan_pencairan, ) = explode('-', $this->input->post("tanggal_pencairan"));

			$data = array(
				"tahun" => $this->input->post("tahun"),
				"id_skpd" => $this->input->post("id_skpd"),
				"id_rup" => $this->input->post("id_rup"),
				"tanggal_pencairan" => $this->input->post("tanggal_pencairan"),
				"bulan_pencairan" => $bulan_pencairan,
				"realisasi_keuangan" => $this->input->post("realisasi_keuangan"),
				"realisasi_fisik" => $this->input->post("realisasi_fisik"),
				"nomor_kontrak" => $this->input->post("nomor_kontrak"),
				"nomor_surat" => $this->input->post("nomor_surat"),
				"nama_pemenang" => $this->input->post("nama_pemenang"),
				"sanggah" => $this->input->post("sanggah"),
				"nilai_hps" => $this->input->post("nilai_hps"),
				"nilai_kontrak" => $this->input->post("nilai_kontrak"),
				"jumlah_mendaftar" => $this->input->post("jumlah_mendaftar"),
				"jumlah_lulus_kualifikasi" => $this->input->post("jumlah_lulus_kualifikasi"),
				"jumlah_menawar" => $this->input->post("jumlah_menawar"),
				"jumlah_lulus_teknis" => $this->input->post("jumlah_lulus_teknis"),
				"tanggal_pengumuman" => $this->input->post("tanggal_pengumuman"),
				"tanggal_anwijzing" => $this->input->post("tanggal_anwijzing"),
				"tanggal_pembukaan_penawaran" => $this->input->post("tanggal_pembukaan_penawaran"),
				"tanggal_klarifikasi_negosiasi" => $this->input->post("tanggal_klarifikasi_negosiasi"),
				"tanggal_kontrak" => $this->input->post("tanggal_kontrak"),
				"tanggal_spmk" => $this->input->post("tanggal_spmk"),
				"tanggal_surat_serah_terima" => $this->input->post("tanggal_surat_serah_terima"),
				"nomor_bukti" => $this->input->post("nomor_bukti"),
				"penyedia_swakelola" => $this->input->post("penyedia_swakelola"),
				);
			$this->model->insertData($data);

			$data_rup = array(
				"nomor_kontrak" => $this->input->post("nomor_kontrak"),
				"nomor_surat" => $this->input->post("nomor_surat"),
				"nama_pemenang" => $this->input->post("nama_pemenang"),
				"sanggah" => $this->input->post("sanggah"),
				"nilai_hps" => $this->input->post("nilai_hps"),
				"nilai_kontrak" => $this->input->post("nilai_kontrak"),
				"jumlah_mendaftar" => $this->input->post("jumlah_mendaftar"),
				"jumlah_lulus_kualifikasi" => $this->input->post("jumlah_lulus_kualifikasi"),
				"jumlah_menawar" => $this->input->post("jumlah_menawar"),
				"jumlah_lulus_teknis" => $this->input->post("jumlah_lulus_teknis"),
				"tanggal_pengumuman" => $this->input->post("tanggal_pengumuman"),
				"tanggal_anwijzing" => $this->input->post("tanggal_anwijzing"),
				"tanggal_pembukaan_penawaran" => $this->input->post("tanggal_pembukaan_penawaran"),
				"tanggal_klarifikasi_negosiasi" => $this->input->post("tanggal_klarifikasi_negosiasi"),
				"tanggal_kontrak" => $this->input->post("tanggal_kontrak"),
				"tanggal_spmk" => $this->input->post("tanggal_spmk"),
				"tanggal_surat_serah_terima" => $this->input->post("tanggal_surat_serah_terima"),
				);
			$this->model->updateDataRUP($this->input->post("id_rup"), $data_rup);
			
			echo json_encode(array("status"=>TRUE));
		}
		else{
			redirect(base_url());
		}
	}

     public function changeData($token){
          if ($this->session->userdata('auth_id') != "") {
               $result = $this->model->getDataRealisasi($token);
               $data = array();
               foreach ($result->result() as $rows) {
                    $data[0][0] = $rows->id;
                    $data[0][1] = $rows->tanggal_pencairan;
                    $data[0][2] = $rows->realisasi_keuangan;
                    $data[0][3] = $rows->realisasi_fisik;
                    $data[0][21] = $rows->nomor_bukti;
                    $data[0][22] = $rows->penyedia_swakelola;
               }
               $result = $this->model->getDataRealisasiParentRUP($token);
               foreach ($result->result() as $rows) {
                    $data[0][4] = $rows->nilai_hps;
                    $data[0][5] = $rows->nilai_kontrak;
                    $data[0][6] = $rows->jumlah_mendaftar;
                    $data[0][7] = $rows->jumlah_lulus_kualifikasi;
                    $data[0][8] = $rows->jumlah_menawar;
                    $data[0][9] = $rows->jumlah_lulus_teknis;
                    $data[0][10] = $rows->tanggal_pengumuman;
                    $data[0][11] = $rows->tanggal_anwijzing;
                    $data[0][12] = $rows->tanggal_pembukaan_penawaran;
                    $data[0][13] = $rows->tanggal_klarifikasi_negosiasi;
                    $data[0][14] = $rows->tanggal_kontrak;
                    $data[0][15] = $rows->tanggal_spmk;
                    $data[0][16] = $rows->nomor_kontrak;
                    $data[0][17] = $rows->nama_pemenang;
                    $data[0][18] = $rows->sanggah;
                    $data[0][19] = $rows->nomor_surat;
                    $data[0][20] = $rows->tanggal_surat_serah_terima;
               }
               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

	public function updateData(){
		if ($this->session->userdata('auth_id') != '') {
			
			if (count(explode('-', $this->input->post("tanggal_pencairan"))) == 3)
				list(, $bulan_pencairan, ) = explode('-', $this->input->post("tanggal_pencairan"));

			$data = array(
				"tanggal_pencairan" => $this->input->post("tanggal_pencairan"),
				"bulan_pencairan" => $bulan_pencairan,
				"realisasi_keuangan" => $this->input->post("realisasi_keuangan"),
				"realisasi_fisik" => $this->input->post("realisasi_fisik"),
				"nomor_kontrak" => $this->input->post("nomor_kontrak"),
				"nomor_surat" => $this->input->post("nomor_surat"),
				"nama_pemenang" => $this->input->post("nama_pemenang"),
				"sanggah" => $this->input->post("sanggah"),
				"nilai_hps" => $this->input->post("nilai_hps"),
				"nilai_kontrak" => $this->input->post("nilai_kontrak"),
				"jumlah_mendaftar" => $this->input->post("jumlah_mendaftar"),
				"jumlah_lulus_kualifikasi" => $this->input->post("jumlah_lulus_kualifikasi"),
				"jumlah_menawar" => $this->input->post("jumlah_menawar"),
				"jumlah_lulus_teknis" => $this->input->post("jumlah_lulus_teknis"),
				"tanggal_pengumuman" => $this->input->post("tanggal_pengumuman"),
				"tanggal_anwijzing" => $this->input->post("tanggal_anwijzing"),
				"tanggal_pembukaan_penawaran" => $this->input->post("tanggal_pembukaan_penawaran"),
				"tanggal_klarifikasi_negosiasi" => $this->input->post("tanggal_klarifikasi_negosiasi"),
				"tanggal_kontrak" => $this->input->post("tanggal_kontrak"),
				"tanggal_spmk" => $this->input->post("tanggal_spmk"),
				"tanggal_surat_serah_terima" => $this->input->post("tanggal_surat_serah_terima"),
				"nomor_bukti" => $this->input->post("nomor_bukti"),
				"penyedia_swakelola" => $this->input->post("penyedia_swakelola"),
				);
			$this->model->updateData($this->input->post("token"), $data);
			
			$data_rup = array(
				"nomor_kontrak" => $this->input->post("nomor_kontrak"),
				"nomor_surat" => $this->input->post("nomor_surat"),
				"nama_pemenang" => $this->input->post("nama_pemenang"),
				"sanggah" => $this->input->post("sanggah"),
				"nilai_hps" => $this->input->post("nilai_hps"),
				"nilai_kontrak" => $this->input->post("nilai_kontrak"),
				"jumlah_mendaftar" => $this->input->post("jumlah_mendaftar"),
				"jumlah_lulus_kualifikasi" => $this->input->post("jumlah_lulus_kualifikasi"),
				"jumlah_menawar" => $this->input->post("jumlah_menawar"),
				"jumlah_lulus_teknis" => $this->input->post("jumlah_lulus_teknis"),
				"tanggal_pengumuman" => $this->input->post("tanggal_pengumuman"),
				"tanggal_anwijzing" => $this->input->post("tanggal_anwijzing"),
				"tanggal_pembukaan_penawaran" => $this->input->post("tanggal_pembukaan_penawaran"),
				"tanggal_klarifikasi_negosiasi" => $this->input->post("tanggal_klarifikasi_negosiasi"),
				"tanggal_kontrak" => $this->input->post("tanggal_kontrak"),
				"tanggal_spmk" => $this->input->post("tanggal_spmk"),
				"tanggal_surat_serah_terima" => $this->input->post("tanggal_surat_serah_terima"),
				);
			$this->model->updateDataRUP($this->input->post("id_rup"), $data_rup);
			
			echo json_encode(array("status"=>TRUE));
		}
		else{
			redirect(base_url());
		}
	}

     public function trashData($token){
          if ($this->session->userdata('auth_id') != '') {
               $this->model->deleteData($token);
               echo json_encode(array("status"=>TRUE));
          }
          else{
               redirect(base_url());
          }
     }

     public function multiTrashData(){
          if ($this->session->userdata('auth_id') != '') {
               $token = $this->input->post('token_data');
               foreach ($token as $rows) {
                    $this->model->deleteData($rows);
               }
               echo json_encode(array("status"=>TRUE));
          }
          else{
               redirect(base_url());
          }
     }
}