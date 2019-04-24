<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endarup_controller extends CI_Controller {

     public function __construct()
     {
          parent::__construct();
          $this->load->model('entry-data/Endarup_model', 'model');
     }


     // *
     // ============================== //
     // ********** Main Data ********* //
     // ============================== //
     // *

     public function mainPage(){
          if ($this->session->userdata('auth_id') != "") {
               $this->load->view('pages/entry-data/rup/data');
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataProgram($id_skpd){
          if ($this->session->userdata('auth_id') != "") {
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               $data = array();
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_program = $this->model->getAllDataProgram($rows_skpd->kd_skpd);
                    foreach ($result_program->result() as $rows_program) {
                         // Create Data
                         $data[] = array(
                                        "id"                => $rows_program->id,
                                        "kd_gabungan"       => $rows_program->kd_gabungan,
                                        "nama_program"      => $rows_program->keterangan_program
                                   );
                    }
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataKegiatan($id_skpd, $id_program){
          if ($this->session->userdata('auth_id') != "") {
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               $data = array();
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_kegiatan = $this->model->getAllDataKegiatan($rows_skpd->kd_skpd, $id_program);
                    foreach ($result_kegiatan->result() as $rows_kegiatan) {
                         // Create Data
                         $data[] = array(
                                        "id"                => $rows_kegiatan->id,
                                        "kd_gabungan"       => $rows_kegiatan->kd_gabungan,
                                        "nama_kegiatan"     => $rows_kegiatan->keterangan_kegiatan
                                   );
                    }
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataRO($id_skpd, $id_program, $id_kegiatan){
          if ($this->session->userdata('auth_id') != "") {
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               $data = array();
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_ro = $this->model->getAllDataRO($rows_skpd->kd_skpd, $id_program, $id_kegiatan);
                    foreach ($result_ro->result() as $rows_ro) {
                         // Create Data
                         $data[] = array(
                                        "id"                => $rows_ro->id,
                                        "kd_rekening"       => $rows_ro->kd_rekening,
                                        "nama_rekening"     => $rows_ro->nama_rekening
                                   );
                    }
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataRUP($id_skpd, $id_program, $id_kegiatan, $id_ro){
          if ($this->session->userdata('auth_id') != "") {
               // Update Table
               $upt_tbl_dltd_rup = $this->model->deleteRUPIDZero();
               if ($upt_tbl_dltd_rup) {
                    $upt_tbl_dltd_hstryrup = $this->model->deleteHistoryRevisiByIDRUPBaru();
                    if ($upt_tbl_dltd_hstryrup) {
                         $upt_tbl_rl_rup_tepra = $this->model->updateRealisasiRUPTepraByIDRUPAwal();
                         if ($upt_tbl_rl_rup_tepra) {
                              $upt_tbl_hstry_rvs = $this->model->updateHistoryRUPRevisiKe();
                         }
                    }
               }

               // Proses Data
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               $data = array();
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_rup = $this->model->getAllDataRUP($rows_skpd->kd_skpd, $id_program, $id_kegiatan, $id_ro);
                    $no = 1;
                    foreach ($result_rup->result() as $rows_rup) {
                         // Create Data
                         $data[] = array(
                                        "no"                => $no++,
                                        "id"                => $rows_rup->id,
                                        "nama_paket"        => $rows_rup->nama_paket,
                                        "volume_pekerjaan"  => $rows_rup->volume_pekerjaan,
                                        "cara_pengadaan"    => $this->getCaraPengadaan($rows_rup->cara_pengadaan),
                                        "jenis_belanja"     => $this->getJenisBelanja($rows_rup->jenis_belanja),
                                        "jenis_pengadaan"   => $this->getJenisPengadaan($rows_rup->jenis_pengadaan),
                                        "metode_pemilihan"  => $this->getMetodePemilihan($rows_rup->metode_pemilihan),
                                        "pagu_paket"        => "Rp.".number_format($rows_rup->pagu_paket),
                                        "tanggal_buat"      => date_format(date_create($rows_rup->tanggal_buat), "H:i:s d-m-Y"),
                                        "tanggal_update"    => date_format(date_create($rows_rup->tanggal_update), "H:i:s d-m-Y"),
                                        "is_aktif"          => $rows_rup->is_aktif
                                   );
                    }
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }


     public function getDataPPK($id_skpd){
          if ($this->session->userdata('auth_id') != '') {
               $data = array();
               $result = $this->model->getAllDataPPK('', $id_skpd);
               foreach ($result->result() as $rows) {
                    $data[] = array(
                                   "id"           => $rows->id,
                                   "username"     => $rows->username,
                                   "nama"         => $rows->nama
                              );
               }
               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataSKPDSwakelola($id_skpd){
          if ($this->session->userdata('auth_id') != '') {
               $data = array();
               $result = $this->model->getAllDataSKPDByNotIn($id_skpd);
               foreach ($result->result() as $rows) {
                    $data[] = array(
                                   "id"           => $rows->id,
                                   "kd_skpd"      => $rows->kd_skpd,
                                   "nama_skpd"    => $rows->nama_skpd
                              );
               }
               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataSKPDForm($id_skpd){
          if ($this->session->userdata('auth_id') != "") {
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               $data = array();
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_ref_skpd = $this->model->getAllDataRefMasterRUP($rows_skpd->kd_skpd);
                    if ($result_ref_skpd->num_rows() > 0) {
                         foreach ($result_ref_skpd->result() as $rows_ref_skpd) {
                              $alamat = $rows_ref_skpd->alamat;
                         }
                    }
                    else{
                         $alamat = '-';
                    }

                    $explode = explode(".", $rows_skpd->kd_skpd);
                    $data[] = array(
                                   "id_skpd"      => $rows_skpd->id,
                                   "kd_skpd"      => $rows_skpd->kd_skpd,
                                   "kd_urusan"    => sprintf("%01s", $explode[0]),
                                   "kd_kelompok"  => sprintf("%02s", $explode[1]),
                                   "kd_opd"       => sprintf("%03s", $explode[2]),
                                   "alamat"       => $alamat
                              );
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }



     public function getDataProgramByID($id_program){
          if ($this->session->userdata('auth_id') != "") {
               $result = $this->model->getAllDataProgramByID($id_program);
               $data = array();
               foreach ($result->result() as $rows) {
                    $data[] = array(
                                   "mak_program"       => sprintf("%02s", $rows->kd_program),
                                   "kd_gabungan"       => $rows->kd_gabungan,
                                   "nama_program"      => $rows->keterangan_program
                              );
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataKegiatanByID($id_kegiatan){
          if ($this->session->userdata('auth_id') != "") {
               $result = $this->model->getAllDataKegiatanByID($id_kegiatan);
               $data = array();
               foreach ($result->result() as $rows) {
                    $data[] = array(
                                   "mak_kegiatan"      => sprintf("%03s", $rows->kd_kegiatan),
                                   "kd_gabungan"       => $rows->kd_gabungan,
                                   "nama_kegiatan"     => $rows->keterangan_kegiatan
                              );
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function getDataROByID($id_ro){
          if ($this->session->userdata('auth_id') != "") {
               $result_ro = $this->model->getAllDataROByID($id_ro);
               $data = array();
               foreach ($result_ro->result() as $rows_ro) {
                    $explode = explode(".", $rows_ro->kd_rekening);

                    $result_rup = $this->model->getAllDataRUPByIDRO($id_ro);
                    if ($result_rup->num_rows() > 0) {
                         foreach ($result_rup->result() as $rows_rup) {
                              $pagu_terpaketkan = $rows_ro->jumlah - $rows_rup->total_paket;
                         }
                    }
                    else{
                         $pagu_terpaketkan = $rows_ro->jumlah - 0;
                    }
                    $data[] = array(
                                   "mak_rekening"      =>  sprintf("%01s", $explode[0]).".".
                                                            sprintf("%01s", $explode[1]).".".
                                                            sprintf("%01s", $explode[2]).".".
                                                            sprintf("%02s", $explode[3]).".".
                                                            sprintf("%02s", $explode[4]),
                                   "kd_rekening"       => $rows_ro->kd_rekening,
                                   "nama_paket"        => $rows_ro->nama_rekening,
                                   "pagu_tersedia"     => $pagu_terpaketkan,
                                   "jumlah"            => $rows_ro->jumlah
                              );
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // ============================== //
     // ********** Register ********** //
     // ============================== //
     // *

     public function uploadData(){
          if ($this->session->userdata('auth_id') != "") {
               // Variable
               $tahun                        = $this->input->post("tahun");
               $id_skpd                      = $this->input->post("id_skpd");
               $kd_skpd                      = $this->input->post("kd_skpd");
               $id_program                   = $this->input->post("id_program");
               $id_kegiatan                  = $this->input->post("id_kegiatan");
               $id_rincian_obyek             = $this->input->post("id_ro");
               $id_user_ppk                  = explode("-", $this->input->post("id_user_ppk"));
               $nama_paket                   = $this->input->post("nama_paket");
               $volume_pekerjaan             = $this->input->post("volume_pekerjaan");
               $jumlah_paket                 = $this->input->post("jumlah_paket");
               $uraian_pekerjaan             = $this->input->post("uraian_pekerjaan");
               $spesifikasi_pekerjaan        = $this->input->post("spesifikasi_pekerjaan");
               $lokasi_pekerjaan             = $this->input->post("lokasi_pekerjaan");
               $produk_dalam_negeri          = $this->input->post("produk_dalam_negeri");
               $usaha_kecil                  = $this->input->post("usaha_kecil");
               $sumber_dana                  = $this->input->post("sumber_dana");
               $pra_dipa                     = $this->input->post("pra_dipa");
               $nomor_renja                  = $this->input->post("nomor_renja");
               $no_izin_tahun_jamak          = $this->input->post("no_izin_tahun_jamak");
               $kd_mak                       = $this->input->post("kd_urusan").".".
                                               $this->input->post("kd_kelompok").".".
                                               $this->input->post("kd_opd").".".
                                               $this->input->post("kd_program").".".
                                               $this->input->post("kd_kegiatan").".".
                                               $this->input->post("kd_rekening");
               $pagu_paket                   = $this->input->post("pagu_paket");
               $cara_pengadaan               = $this->input->post("cara_pengadaan");
               $tipe_swakelola               = $this->input->post("tipe_swakelola");
               $id_skpd_swakelola            = $this->input->post("skpd_swakelola");
               $jenis_belanja                = $this->input->post("jenis_belanja");
               $jenis_pengadaan              = $this->input->post("jenis_pengadaan");
               $metode_pemilihan             = $this->input->post("metode_pemilihan");
               $umumkan_paket                = $this->input->post("umumkan_paket");
               $pelaksanaan_pengadaan_awal   = $this->input->post("pelaksanaan_pengadaan_awal");
               $pelaksanaan_pengadaan_akhir  = $this->input->post("pelaksanaan_pengadaan_akhir");
               $pelaksanaan_kontrak_awal     = $this->input->post("pelaksanaan_kontrak_awal");
               $pelaksanaan_kontrak_akhir    = $this->input->post("pelaksanaan_kontrak_akhir");
               $pelaksanaan_pemanfaatan      = $this->input->post("pelaksanaan_pemanfaatan");
               $pelaksanaan_pekerjaan_awal   = $this->input->post("pelaksanaan_pekerjaan_awal");
               $pelaksanaan_pekerjaan_akhir  = $this->input->post("pelaksanaan_pekerjaan_akhir");


               if ($cara_pengadaan == 1) {
                    $tipe_swakelola                = NULL;
                    $id_skpd_swakelola             = NULL;
                    $pelaksanaan_pekerjaan_awal    = NULL;
                    $pelaksanaan_pekerjaan_akhir   = NULL;
               }
               else{
                    $produk_dalam_negeri           = NULL;
                    $usaha_kecil                   = NULL;
                    $metode_pemilihan              = NULL;
                    $pelaksanaan_pengadaan_awal    = NULL;
                    $pelaksanaan_pengadaan_akhir   = NULL;
                    $pelaksanaan_kontrak_awal      = NULL;
                    $pelaksanaan_kontrak_akhir     = NULL;
                    $pelaksanaan_pemanfaatan       = NULL;

                    if ($tipe_swakelola == 2) {
                         $explode_id_skpd_swakelola = explode("-", $id_skpd_swakelola);
                         $id_skpd_swakelola         = $explode_id_skpd_swakelola[0];
                    }
                    else{
                         $id_skpd_swakelola       = NULL;
                    }
               }

               if ($pra_dipa == 0) {
                    $nomor_renja = $nomor_renja;
               }
               else{
                    $nomor_renja = NULL;
               }

               // Create Data
               $data = array(
                         "tahun"                       => $tahun,
                         "id_skpd"                     => $id_skpd,
                         "kd_skpd"                     => $kd_skpd,
                         "id_program"                  => $id_program,
                         "id_kegiatan"                 => $id_kegiatan,
                         "id_rincian_obyek"            => $id_rincian_obyek,
                         "id_user_ppk"                 => $id_user_ppk[0],
                         "nama_paket"                  => $nama_paket,
                         "volume_pekerjaan"            => $volume_pekerjaan,
                         "jumlah_paket"                => $jumlah_paket,
                         "uraian_pekerjaan"            => $uraian_pekerjaan,
                         "spesifikasi_pekerjaan"       => $spesifikasi_pekerjaan,
                         "lokasi_pekerjaan"            => $lokasi_pekerjaan,
                         "produk_dalam_negeri"         => $produk_dalam_negeri,
                         "usaha_kecil"                 => $usaha_kecil,
                         "sumber_dana"                 => $sumber_dana,
                         "pra_dipa"                    => $pra_dipa,
                         "nomor_renja"                 => $nomor_renja,
                         "no_izin_tahun_jamak"         => $no_izin_tahun_jamak,
                         "kd_mak"                      => $kd_mak,
                         "pagu_paket"                  => $pagu_paket,
                         "cara_pengadaan"              => $cara_pengadaan,
                         "tipe_swakelola"              => $tipe_swakelola,
                         "id_skpd_swakelola"           => $id_skpd_swakelola,
                         "jenis_belanja"               => $jenis_belanja,
                         "jenis_pengadaan"             => $jenis_pengadaan,
                         "metode_pemilihan"            => $metode_pemilihan,
                         "umumkan_paket"               => $umumkan_paket,
                         "pelaksanaan_pengadaan_awal"  => $pelaksanaan_pengadaan_awal,
                         "pelaksanaan_pengadaan_akhir" => $pelaksanaan_pengadaan_akhir,
                         "pelaksanaan_kontrak_awal"    => $pelaksanaan_kontrak_awal,
                         "pelaksanaan_kontrak_akhir"   => $pelaksanaan_kontrak_akhir,
                         "pelaksanaan_pemanfaatan"     => $pelaksanaan_pemanfaatan,
                         "pelaksanaan_pekerjaan_awal"  => $pelaksanaan_pekerjaan_awal,
                         "pelaksanaan_pekerjaan_akhir" => $pelaksanaan_pekerjaan_akhir
                    );
               $this->model->insertData($data);
               echo json_encode(array("status" => TRUE));
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // ============================== //
     // ************ Edit ************ //
     // ============================== //
     // *
     public function revisiData($id_rup){
          if ($this->session->userdata('auth_id') != "") {
               $result_rup = $this->model->getAllDataRUPByID($id_rup);
               $data = array();
               foreach ($result_rup->result() as $rows_rup) {
                    if ($rows_rup->id_user_ppk != "") {
                         $result_ppk = $this->model->getAllDataPPK($rows_rup->id_user_ppk, $rows_rup->id_skpd);
                         foreach ($result_ppk->result() as $rows_ppk) {
                              $id_user_ppk = $rows_ppk->id."-[".$rows_ppk->username."]-".$rows_ppk->nama;
                         }
                    }
                    if ($rows_rup->id_user_ppk == "") {
                         $id_user_ppk = 'none';
                    }

                    if ($rows_rup->id_skpd_swakelola != NULL || $rows_rup->id_skpd_swakelola != 0) {
                         $result_skpd_swakelola = $this->model->getAllDataSKPDByID($rows_rup->id_skpd_swakelola);
                         foreach ($result_skpd_swakelola->result() as $rows_skpd_swakelola) {
                              $id_skpd_swakelola = $rows_skpd_swakelola->id.
                                                  "-[".$rows_skpd_swakelola->kd_skpd."]-".
                                                  $rows_skpd_swakelola->nama_skpd;
                         }
                    }
                    if ($rows_rup->id_skpd_swakelola == NULL || $rows_rup->id_skpd_swakelola == 0) {
                         $id_skpd_swakelola = "none";
                    }

                    $result_rup_pagu = $this->model->getAllDataRUPByIDRO($rows_rup->id_rincian_obyek);
                    if ($result_rup_pagu->num_rows() > 0) {
                         foreach ($result_rup_pagu->result() as $rows_rup_pagu) {
                              $pagu_terpaketkan = $rows_rup->pagu_jumlah - $rows_rup_pagu->total_paket;
                         }
                    }
                    if ($result_rup_pagu->num_rows() <= 0) {
                         $pagu_terpaketkan = $rows_ro->jumlah - 0;
                    }

                    if (intval($rows_rup->id_rup_awal) > 0) {
                         $result_history_revisi = $this->model->getAllDataHistoryRevisiEditRUP($rows_rup->id_rup_awal);
                         if ($result_history_revisi->num_rows() > 0) {
                              foreach ($result_history_revisi->result() as $rows_history_revisi) {
                                   $revisi_paket[] = $rows_history_revisi->id_rup_sebelumnya;
                              }
                         }
                         else{
                              $revisi_paket = '-';
                         }
                    }
                    if (intval($rows_rup->id_rup_awal) <= 0) {
                         $revisi_paket = '-';
                    }

                    $kd_mak = explode(".", $rows_rup->kd_mak);

                    // Create Data
                    $data[] = array(
                                   "id"                          => $rows_rup->id,
                                   "id_program"                  => $rows_rup->id_program,
                                   "kd_program"                  => $rows_rup->kd_program,
                                   "nama_program"                => $rows_rup->nama_program,
                                   "id_kegiatan"                 => $rows_rup->id_kegiatan,
                                   "kd_kegiatan"                 => $rows_rup->kd_kegiatan,
                                   "nama_kegiatan"               => $rows_rup->nama_kegiatan,
                                   "id_rincian_obyek"            => $rows_rup->id_rincian_obyek,
                                   "kd_rekening"                 => $rows_rup->kd_rekening,
                                   "nama_rekening"               => $rows_rup->nama_rekening,
                                   "kd_urusan_mak"               => $kd_mak[0],
                                   "kd_kelompok_mak"             => $kd_mak[1],
                                   "kd_opd_mak"                  => $kd_mak[2].".".$kd_mak[3].".".$kd_mak[4],
                                   "kd_program_mak"              => $kd_mak[5],
                                   "kd_kegiatan_mak"             => $kd_mak[6],
                                   "kd_rekening_mak"             => $kd_mak[7].".".
                                                                    $kd_mak[8].".".
                                                                    $kd_mak[9].".".
                                                                    $kd_mak[10].".".
                                                                    $kd_mak[11],
                                   "id_user_ppk"                 => $id_user_ppk,
                                   "nama_paket"                  => $rows_rup->nama_paket,
                                   "volume_pekerjaan"            => $rows_rup->volume_pekerjaan,
                                   "jumlah_paket"                => $rows_rup->jumlah_paket,
                                   "uraian_pekerjaan"            => $rows_rup->uraian_pekerjaan,
                                   "spesifikasi_pekerjaan"       => $this->is_emptyValue($rows_rup->spesifikasi_pekerjaan),
                                   "lokasi_pekerjaan"            => $rows_rup->lokasi_pekerjaan,
                                   "produk_dalam_negeri"         => $rows_rup->produk_dalam_negeri,
                                   "usaha_kecil"                 => $rows_rup->usaha_kecil,
                                   "sumber_dana"                 => $rows_rup->sumber_dana,
                                   "pra_dipa"                    => $rows_rup->pra_dipa,
                                   "nomor_renja"                 => $rows_rup->nomor_renja,
                                   "no_izin_tahun_jamak"         => $rows_rup->no_izin_tahun_jamak,
                                   "pagu_paket"                  => $rows_rup->pagu_paket,
                                   "pagu_jumlah"                 => $pagu_terpaketkan,
                                   "cara_pengadaan"              => $rows_rup->cara_pengadaan,
                                   "tipe_swakelola"              => $rows_rup->tipe_swakelola,
                                   "id_skpd_swakelola"           => $id_skpd_swakelola,
                                   "jenis_belanja"               => $rows_rup->jenis_belanja,
                                   "jenis_pengadaan"             => $rows_rup->jenis_pengadaan,
                                   "metode_pemilihan"            => $rows_rup->metode_pemilihan,
                                   "umumkan_paket"               => $rows_rup->umumkan_paket,
                                   "pelaksanaan_pengadaan_awal"  => $rows_rup->pelaksanaan_pengadaan_awal,
                                   "pelaksanaan_pengadaan_akhir" => $rows_rup->pelaksanaan_pengadaan_akhir,
                                   "pelaksanaan_kontrak_awal"    => $rows_rup->pelaksanaan_kontrak_awal,
                                   "pelaksanaan_kontrak_akhir"   => $rows_rup->pelaksanaan_kontrak_akhir,
                                   "pelaksanaan_pemanfaatan"     => $rows_rup->pelaksanaan_pemanfaatan,
                                   "pelaksanaan_pekerjaan_awal"  => $rows_rup->pelaksanaan_pekerjaan_awal,
                                   "pelaksanaan_pekerjaan_akhir" => $rows_rup->pelaksanaan_pekerjaan_akhir,
                                   "is_aktif"                    => $rows_rup->is_aktif,
                                   "id_rup_awal"                 => $rows_rup->id_rup_awal,
                                   "is_last_paket"               => $rows_rup->is_last_paket,
                                   "revisi_paket"                => $revisi_paket
                              );
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }

     public function uploadRevisi(){
          if ($this->session->userdata('auth_id') != "") {
               // Variable
               $id                           = $this->input->post("id");
               $tahun                        = $this->input->post("tahun");
               $id_skpd                      = $this->input->post("id_skpd");
               $kd_skpd                      = $this->input->post("kd_skpd");
               $id_program                   = $this->input->post("id_program");
               $id_kegiatan                  = $this->input->post("id_kegiatan");
               $id_rincian_obyek             = $this->input->post("id_ro");
               $id_user_ppk                  = explode("-", $this->input->post("id_user_ppk"));
               $nama_paket                   = $this->input->post("nama_paket");
               $volume_pekerjaan             = $this->input->post("volume_pekerjaan");
               $jumlah_paket                 = $this->input->post("jumlah_paket");
               $uraian_pekerjaan             = $this->input->post("uraian_pekerjaan");
               $spesifikasi_pekerjaan        = $this->input->post("spesifikasi_pekerjaan");
               $lokasi_pekerjaan             = $this->input->post("lokasi_pekerjaan");
               $produk_dalam_negeri          = $this->input->post("produk_dalam_negeri");
               $usaha_kecil                  = $this->input->post("usaha_kecil");
               $sumber_dana                  = $this->input->post("sumber_dana");
               $pra_dipa                     = $this->input->post("pra_dipa");
               $nomor_renja                  = $this->input->post("nomor_renja");
               $no_izin_tahun_jamak          = $this->input->post("no_izin_tahun_jamak");
               $kd_mak                       = $this->input->post("kd_urusan").".".
                                               $this->input->post("kd_kelompok").".".
                                               $this->input->post("kd_opd").".".
                                               $this->input->post("kd_program").".".
                                               $this->input->post("kd_kegiatan").".".
                                               $this->input->post("kd_rekening");
               $pagu_paket                   = $this->input->post("pagu_paket");
               $cara_pengadaan               = $this->input->post("cara_pengadaan");
               $tipe_swakelola               = $this->input->post("tipe_swakelola");
               $id_skpd_swakelola            = $this->input->post("skpd_swakelola");
               $jenis_belanja                = $this->input->post("jenis_belanja");
               $jenis_pengadaan              = $this->input->post("jenis_pengadaan");
               $metode_pemilihan             = $this->input->post("metode_pemilihan");
               $umumkan_paket                = $this->input->post("umumkan_paket");
               $pelaksanaan_pengadaan_awal   = $this->input->post("pelaksanaan_pengadaan_awal");
               $pelaksanaan_pengadaan_akhir  = $this->input->post("pelaksanaan_pengadaan_akhir");
               $pelaksanaan_kontrak_awal     = $this->input->post("pelaksanaan_kontrak_awal");
               $pelaksanaan_kontrak_akhir    = $this->input->post("pelaksanaan_kontrak_akhir");
               $pelaksanaan_pemanfaatan      = $this->input->post("pelaksanaan_pemanfaatan");
               $pelaksanaan_pekerjaan_awal   = $this->input->post("pelaksanaan_pekerjaan_awal");
               $pelaksanaan_pekerjaan_akhir  = $this->input->post("pelaksanaan_pekerjaan_akhir");
               $id_rup_awal                  = $this->input->post("id_rup_awal");
               $is_aktif                     = $this->input->post("is_aktif");
               $is_last_paket                = $this->input->post("is_last_paket");
               $alasan_revisi                = $this->input->post("alasan_revisi");


               if ($cara_pengadaan == 1) {
                    $tipe_swakelola                = NULL;
                    $id_skpd_swakelola             = NULL;
                    $pelaksanaan_pekerjaan_awal    = NULL;
                    $pelaksanaan_pekerjaan_akhir   = NULL;
               }
               if ($cara_pengadaan == 2) {
                    $produk_dalam_negeri           = NULL;
                    $usaha_kecil                   = NULL;
                    $metode_pemilihan              = NULL;
                    $pelaksanaan_pengadaan_awal    = NULL;
                    $pelaksanaan_pengadaan_akhir   = NULL;
                    $pelaksanaan_kontrak_awal      = NULL;
                    $pelaksanaan_kontrak_akhir     = NULL;
                    $pelaksanaan_pemanfaatan       = NULL;

                    if ($tipe_swakelola == 2) {
                         $explode_id_skpd_swakelola = explode("-", $id_skpd_swakelola);
                         $id_skpd_swakelola         = $explode_id_skpd_swakelola[0];
                    }
                    else{
                         $id_skpd_swakelola       = NULL;
                    }
               }

               if ($pra_dipa == 0) {
                    $nomor_renja = $nomor_renja;
               }
               if ($pra_dipa == 1) {
                    $nomor_renja = NULL;
               }


               if ($id_rup_awal == 0) {
                    $id_rup_awal = $id;
               }
               if ($id_rup_awal > 0) {
                    $id_rup_awal = $id_rup_awal;
               }

               // Create Data
               $data = array(
                         "tahun"                       => $tahun,
                         "id_skpd"                     => $id_skpd,
                         "kd_skpd"                     => $kd_skpd,
                         "id_program"                  => $id_program,
                         "id_kegiatan"                 => $id_kegiatan,
                         "id_rincian_obyek"            => $id_rincian_obyek,
                         "id_user_ppk"                 => $id_user_ppk[0],
                         "nama_paket"                  => $nama_paket,
                         "volume_pekerjaan"            => $volume_pekerjaan,
                         "jumlah_paket"                => $jumlah_paket,
                         "uraian_pekerjaan"            => $uraian_pekerjaan,
                         "spesifikasi_pekerjaan"       => $spesifikasi_pekerjaan,
                         "lokasi_pekerjaan"            => $lokasi_pekerjaan,
                         "produk_dalam_negeri"         => $produk_dalam_negeri,
                         "usaha_kecil"                 => $usaha_kecil,
                         "sumber_dana"                 => $sumber_dana,
                         "pra_dipa"                    => $pra_dipa,
                         "nomor_renja"                 => $nomor_renja,
                         "no_izin_tahun_jamak"         => $no_izin_tahun_jamak,
                         "kd_mak"                      => $kd_mak,
                         "pagu_paket"                  => $pagu_paket,
                         "cara_pengadaan"              => $cara_pengadaan,
                         "tipe_swakelola"              => $tipe_swakelola,
                         "id_skpd_swakelola"           => $id_skpd_swakelola,
                         "jenis_belanja"               => $jenis_belanja,
                         "jenis_pengadaan"             => $jenis_pengadaan,
                         "metode_pemilihan"            => $metode_pemilihan,
                         "umumkan_paket"               => $umumkan_paket,
                         "pelaksanaan_pengadaan_awal"  => $pelaksanaan_pengadaan_awal,
                         "pelaksanaan_pengadaan_akhir" => $pelaksanaan_pengadaan_akhir,
                         "pelaksanaan_kontrak_awal"    => $pelaksanaan_kontrak_awal,
                         "pelaksanaan_kontrak_akhir"   => $pelaksanaan_kontrak_akhir,
                         "pelaksanaan_pemanfaatan"     => $pelaksanaan_pemanfaatan,
                         "pelaksanaan_pekerjaan_awal"  => $pelaksanaan_pekerjaan_awal,
                         "pelaksanaan_pekerjaan_akhir" => $pelaksanaan_pekerjaan_akhir,
                         "id_rup_awal"                 => $id_rup_awal,
                         "id_rup_sebelumnya"           => $id,
                         "is_aktif"                    => $is_aktif,
                         "is_last_paket"               => $is_last_paket,
                         "alasan_revisi"               => $alasan_revisi
                    );
               $this->model->updateData($data);
               echo json_encode(array("status"=>TRUE));
          }
          else{
               redirect(base_url());
          }
     }

     public function updateRUPDataTable(){
          if ($this->session->userdata('auth_id') != "") {
               $tipe_form          = $this->input->post("tipe_form");
               $id_rup             = $this->input->post("id_rup");
               $alasan_revisi      = $this->input->post("alasan_revisi");
               $tahun              = $this->input->post("tahun");
               $get_tipe_form      = ['-', 'SATUKESATU', 'SATUKEBANYAK', 'PENGAKTIFAN', 'NONAKTIFKAN', 'PEMBATALAN'];
               $get_cara_pengadaan = ['-', 'PENYEDIA', 'SWAKELOLA']; 

               $result_rup = $this->model->getAllDataRUPByID($id_rup);
               foreach ($result_rup->result() as $rows_rup) {
                    $cara_pengadaan     = $rows_rup->cara_pengadaan;
                    $check_id_rup_awal  = $rows_rup->id_rup_awal;
               }

               if ($check_id_rup_awal == 0) {
                    $id_rup_awal = $id_rup;
               }
               if ($check_id_rup_awal != 0) {
                    $id_rup_awal = $check_id_rup_awal;
               }

               $check_revisi_ke    = $this->model->getAllDataHistoryRevisiRUP($id_rup);
               if ($check_revisi_ke->num_rows() > 0) {
                    foreach ($check_revisi_ke->result() as $rows_revisi_ke) {
                         $revisi_paket       = $rows_revisi_ke->revisi_ke + 1;
                    }
               }
               if ($check_revisi_ke->num_rows() <= 0) {
                    $revisi_paket       = 1;
               }


               // Update Data RUP
               if ($get_tipe_form[$tipe_form] == "PENGAKTIFAN") {
                    $data_rup                = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 1);
                    $data_realisasi_rup      = array("is_aktif"    => 1);
               }
               if ($get_tipe_form[$tipe_form] == "NONAKTIFKAN") {
                    $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0);
                    $data_realisasi_rup      = array("is_aktif"    => 0);
               }
               if ($get_tipe_form[$tipe_form] == "PEMBATALAN") {
                    $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0, "is_deleted" => 1);
                    $data_realisasi_rup      = array("is_aktif"    => 0);
               }
               $this->model->updateDataRUPByID($id_rup, $data_rup);
               $this->model->updateDataRealisasiRUPByID($id_rup, $data_realisasi_rup);
               


               // Create History Paket
               $data_history  = array(
                                             "tahun"             => $tahun,
                                             "id_rup_awal"       => $id_rup_awal,
                                             "id_rup_sebelumnya" => $id_rup,
                                             "id_rup_baru"       => $id_rup,
                                             "revisi_ke"         => $revisi_paket,
                                             "jenis"             => $get_cara_pengadaan[$cara_pengadaan],
                                             "tipe"              => $get_tipe_form[$tipe_form],
                                             "alasan_revisi"     => $alasan_revisi
                                        ); 

               $this->model->insertDataHistoryPaket($data_history);
               echo json_encode(array("status"=>TRUE));
          }
          else{
               redirect(base_url());
          }    
     }

     public function updateRUPDataTableForm(){
          if ($this->session->userdata('auth_id') != "") {
               $tipe_form          = $this->input->post("kategori_eksekusi");
               $id_rup             = $this->input->post("token_data");
               $alasan_revisi      = $this->input->post("alasan_revisi");
               $tahun              = $this->input->post("tahun");
               $get_tipe_form      = ['-', 'SATUKESATU', 'SATUKEBANYAK', 'PENGAKTIFAN', 'NONAKTIFKAN', 'PEMBATALAN'];
               $get_cara_pengadaan = ['-', 'PENYEDIA', 'SWAKELOLA']; 

               foreach ($id_rup as $id) {
                    $result_rup = $this->model->getAllDataRUPByID($id);
                    foreach ($result_rup->result() as $rows_rup) {
                         $cara_pengadaan     = $rows_rup->cara_pengadaan;
                         $check_id_rup_awal  = $rows_rup->id_rup_awal;
                    }

                    if ($check_id_rup_awal == 0) {
                         $id_rup_awal = $id;
                    }
                    if ($check_id_rup_awal != 0) {
                         $id_rup_awal = $check_id_rup_awal;
                    }

                    $check_revisi_ke    = $this->model->getAllDataHistoryRevisiRUP($id_rup);
                    if ($check_revisi_ke->num_rows() > 0) {
                         foreach ($check_revisi_ke->result() as $rows_revisi_ke) {
                              $revisi_paket       = $rows_revisi_ke->revisi_ke + 1;
                         }
                    }
                    if ($check_revisi_ke->num_rows() <= 0) {
                         $revisi_paket       = 1;
                    }


                    // Update Data RUP
                    if ($get_tipe_form[$tipe_form] == "PENGAKTIFAN") {
                         $data_rup                = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 1);
                         $data_realisasi_rup      = array("is_aktif"    => 1);
                    }
                    if ($get_tipe_form[$tipe_form] == "NONAKTIFKAN") {
                         $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0);
                         $data_realisasi_rup      = array("is_aktif"    => 0);
                    }
                    if ($get_tipe_form[$tipe_form] == "PEMBATALAN") {
                         $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0, "is_deleted" => 1);
                         $data_realisasi_rup      = array("is_aktif"    => 0);
                    }
                    $this->model->updateDataRUPByID($id, $data_rup);
                    $this->model->updateDataRealisasiRUPByID($id, $data_realisasi_rup);
                    


                    // Create History Paket
                    $data_history  = array(
                                                  "tahun"             => $tahun,
                                                  "id_rup_awal"       => $id_rup_awal,
                                                  "id_rup_sebelumnya" => $id,
                                                  "id_rup_baru"       => $id,
                                                  "revisi_ke"         => $revisi_paket,
                                                  "jenis"             => $get_cara_pengadaan[$cara_pengadaan],
                                                  "tipe"              => $get_tipe_form[$tipe_form],
                                                  "alasan_revisi"     => $alasan_revisi
                                             ); 

                    $this->model->insertDataHistoryPaket($data_history);
               }
               echo json_encode(array("status"=>TRUE));
          }
          else{
               redirect(base_url());
          }    
     }



     // *
     // ============================== //
     // ************ Misc ************ //
     // ============================== //
     // *

     public function getCaraPengadaan($id_cara){
          if ($this->session->userdata('auth_id') != "") {
               $cara_pengadaan = [
                                   1    => "Penyedia",
                                   2    => "Swakelola"
                              ];
               $get_data = $cara_pengadaan[$id_cara];
               return $get_data;
          }
          else{
               redirect(base_url());
          }
     }

     public function getJenisBelanja($id_jenis){
          if ($this->session->userdata('auth_id') != "") {
               $jenis_belanja = [
                                   1    => "Belanja Pegawai",
                                   2    => "Belanja Barang/Jasa",
                                   3    => "Belanja Modal",
                                   4    => "Belum Teridentifikasi",
                                   5    => "Belanja Bunga Utang",
                                   6    => "Belanja Subsidi",
                                   7    => "Belanja Hibah",
                                   8    => "Belanja Bantuan Sosial",
                                   9    => "Belanja Lain-Lain",
                                   10   => "Belanja Pegawai"
                              ];
               $data = $jenis_belanja[$id_jenis];
               return $data;
          }
          else{
               redirect(base_url());
          }    
     }

     public function getJenisPengadaan($id_jenis){
          if ($this->session->userdata('auth_id') != "") {
               $jenis_pengadaan = [
                                   1    => "Barang",
                                   2    => "Konstruksi",
                                   3    => "Jasa Konsultasi",
                                   4    => "Jasa Lainnya"
                              ];
               $data = $jenis_pengadaan[$id_jenis];
               return $data;
          }
          else{
               redirect(base_url());
          }    
     }

     public function getMetodePemilihan($id_metode){
          if ($this->session->userdata('auth_id') != "") {
               if ($id_metode != NULL) {
                    $metode_pemilihan = [
                                        0    => ":: Paket Swakelola ::",
                                        1    => "Purchasing",
                                        2    => "Tender",
                                        3    => "Tender Cepat",
                                        4    => "Pengadaan Langsung",
                                        5    => "Penunjukan Langsung",
                                        6    => "Seleksi"
                                   ];
                    $data = $metode_pemilihan[$id_metode];
               }
               else{
                    $data = ":: Paket Swakelola ::";
               }
               return $data;
          }
          else{
               redirect(base_url());
          }
     }

     public function is_emptyValue($value){
          if ($value == "" || $value == NULL) {
               $data = "-";
          }
          else{
               $data = $value;
          }
          return $data;
     }

     public function getTester(){
          if ($this->session->userdata('auth_id') != "") {
               $result_skpd = $this->model->getAllDataSKPD();
               foreach ($result_skpd->result() as $rows_skpd) {
                    echo "[".$rows_skpd->kd_skpd."] - ".$rows_skpd->nama_skpd."<br>";
                    $result_ppk = $this->model->getAllDataPPK('', $rows_skpd->id);
                    if ($result_ppk->num_rows() > 0) {
                         foreach ($result_ppk->result() as $rows_ppk) {
                              echo "ID : ".$rows_ppk->id." ||| Username : ".$rows_ppk->username." ||| Nama : ".$rows_ppk->nama."<p>";
                         }
                    }
                    else{
                         echo "-";
                    }
               }
          }
          else{
               redirect(base_url());
          }
     }
}