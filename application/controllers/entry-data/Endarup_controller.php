<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endarup_controller extends CI_Controller {

     // *
     // ============================== //
     // ********** Main Data ********* //
     // ============================== //
     // *

     public function __construct()
     {
          parent::__construct();
          $this->load->model('entry-data/Endarup_model', 'model');
     }

     public function mainPage(){
          if ($this->session->userdata('auth_id') != "") {
               $this->load->view('pages/entry-data/rup/data');
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ********* Categories Data ********* //
     // =================================== //
     // *

     public function getDataProgram($id_skpd){
          if ($this->session->userdata('auth_id') != "") {
               $data = array();
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_program = $this->model->getAllDataProgram($rows_skpd->kd_skpd);
                    foreach ($result_program->result() as $rows_program) {
                         $mak_program       =    explode(".", $rows_program->kd_gabungan);
                         $data[] = array(
                                             "id"           =>   $rows_program->id,
                                             "kd_gabungan"  =>   $rows_program->kd_gabungan,
                                             "mak_program"  =>   sprintf("%01s", $mak_program[0]).".".
                                                                 sprintf("%02s", $mak_program[1]),
                                             "nama_program" =>   $rows_program->keterangan_program
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
               $data = array();
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_kegiatan = $this->model->getAllDataKegiatan($rows_skpd->kd_skpd, $id_program);
                    foreach ($result_kegiatan->result() as $rows_kegiatan) {
                         $mak_kegiatan       =    explode(".", $rows_kegiatan->kd_gabungan);
                         $data[] = array(
                                             "id"                =>   $rows_kegiatan->id,
                                             "kd_gabungan"       =>   $rows_kegiatan->kd_gabungan,
                                             "mak_kegiatan"      =>   sprintf("%01s", $mak_kegiatan[0]).".".
                                                                      sprintf("%02s", $mak_kegiatan[1]).".".
                                                                      sprintf("%03s", $mak_kegiatan[2]),
                                             "nama_kegiatan"     =>   $rows_kegiatan->keterangan_kegiatan
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
               $data = array();
               $result_skpd = $this->model->getAllDataSKPDByID($id_skpd);
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_ro = $this->model->getAllDataRincianObyek($rows_skpd->kd_skpd, $id_program, $id_kegiatan);
                    foreach ($result_ro->result() as $rows_ro) {
                         
                         $result_rup = $this->model->getPaguDataRUPByIDRO($rows_skpd->kd_skpd, $rows_ro->id);
                         if ($result_rup->num_rows() > 0) {
                              foreach ($result_rup->result() as $rows_rup) {
                                   $pagu_tersedia =    ($rows_ro->jumlah+0)-($rows_rup->pagu_paket+0);
                              }
                         }
                         else{
                              $pagu_tersedia      =    0;
                         }

                         $mak_rekening       =    explode(".", $rows_ro->kd_rekening);
                         $data[] = array(
                                             "id"                =>   $rows_ro->id,
                                             "kd_rekening"       =>   $rows_ro->kd_rekening,
                                             "mak_rekening"      =>   sprintf("%01s", $mak_rekening[0]).".".
                                                                      sprintf("%01s", $mak_rekening[1]).".".
                                                                      sprintf("%01s", $mak_rekening[2]).".".
                                                                      sprintf("%02s", $mak_rekening[3]).".".
                                                                      sprintf("%02s", $mak_rekening[4]),
                                             "nama_rekening"     =>   $rows_ro->nama_rekening
                                   );
                    }
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ************* RUP Data ************ //
     // =================================== //
     // *

     public function getDataRUP(){
          if ($this->session->userdata('auth_id') != "") {

               // Delete Data Junks
               $process_delete_junks = $this->model->deleteJunks();
               if ($process_delete_junks == TRUE) {
                    // Get Data From AJAX
                    $id_skpd            = $this->input->post("id_skpd");
                    $result_skpd        = $this->model->getAllDataSKPDByID($id_skpd);
                    if ($result_skpd->num_rows() > 0) {
                         foreach ($result_skpd->result() as $rows_skpd) {
                              $kd_skpd  = $rows_skpd->kd_skpd;
                         }
                    }
                    if ($result_skpd->num_rows() <= 0) {
                         $kd_skpd       = '4.1.3.4';
                    }
                    $id_program         = $this->input->post("id_program");
                    $id_kegiatan        = $this->input->post("id_kegiatan");
                    $id_ro              = $this->input->post("id_ro");
                    $parameter          = array(
                                                  "kd_skpd"      => $kd_skpd,
                                                  "id_program"   => $id_program,
                                                  "id_kegiatan"  => $id_kegiatan,
                                                  "id_ro"        => $id_ro
                                             );


                    // Get Data From AJAX Datatable
                    $column_order       =     array( 
                                                       0    =>   'id',
                                                       1    =>   'nama_paket',
                                                       2    =>   'pagu_paket',
                                                       3    =>   'jenis_belanja',
                                                       4    =>   'metode_pemilihan',
                                                       5    =>   'pagu'
                                             );
                    $limit              = $this->input->post('length');
                    $start              = $this->input->post('start');
                    $order              = $column_order[$this->input->post('order')[0]['column']];
                    $dir                = $this->input->post('order')[0]['dir'];


                    // Process
                    $result_rup_count                  = $this->model->getDataAllRUPCount($parameter);
                    $result_rup_count_filtered         = $result_rup_count; 
                 
                    if(empty($this->input->post('search')['value'])){            
                         $result_rup                   = $this->model->getDataAllRUP($limit, $start, $order, $dir, $parameter);
                    }
                    else{
                         $search                       = $this->input->post('search')['value'];
                         $result_rup                   = $this->model->getDataAllRUPSearch($limit, $start, $search, $order, $dir, $parameter);
                         $result_rup_count_filtered    = $this->model->getDataAllRUPSearchCount($search, $parameter);
                    }

                    $data     = array();
                    $no       = 1;
                    if(!empty($result_rup)){
                         foreach ($result_rup as $rows_rup){
                              if ($rows_rup->is_aktif == 1) {
                                   $option   =    "<button class='btn btn-primary btn-sm smep-rupenda-revisi-btn'".
                                                  "data-id='".$rows_rup->id."' style='margin-bottom:5px'>".
                                                       "<i class='fa fa-edit'></i>&nbsp; Revisi Paket".
                                                  "</button>&nbsp;".

                                                  "<button class='btn btn-warning btn-sm smep-rupenda-non-aktif-btn'".
                                                  "data-id='".$rows_rup->id."' style='margin-bottom:5px'>".
                                                       "<i class='fa fa-close'></i>&nbsp; Non-aktifkan Paket".
                                                  "</button>&nbsp;".

                                                  "<button class='btn btn-danger btn-sm smep-rupenda-batalkan-btn'".
                                                  "data-id='".$rows_rup->id."' style='margin-bottom:5px'>".
                                                       "<i class='fa fa-edit'></i>&nbsp; Batalkan Paket".
                                                  "</button>&nbsp;";
                              }
                              if ($rows_rup->is_aktif == 0) {
                                   $option   =    "<button class='btn btn-primary btn-sm smep-rupenda-aktif-btn'".
                                                  "data-id='".$rows_rup->id."' style='margin-bottom:5px'>".
                                                       "<i class='fa fa-edit'></i>&nbsp;Aktifkan Paket".
                                                  "</button>&nbsp;";
                              }



                              // Insert To Value

                              $dataColumn['no']                  =    $no++.
                                                                      "<input type='checkbox' name='token_data[]' style='margin-left:5px;' class='rupenda-idrup-data' value='".$rows_rup->id."'>";
                              $dataColumn['nama_paket']          =    "<b><a class='smep-rupenda-data-modal-open-btn' data-id='".$rows_rup->id.
                                                                           "' style='cursor:pointer;'>".
                                                                                $rows_rup->nama_paket.
                                                                           "</a>".
                                                                      "</b><br>".
                                                                      "- ID RUP SMEP : 2019".$rows_rup->id."<br>".
                                                                      "- Volume Pekerjaan : ".$rows_rup->volume_pekerjaan."<br>".
                                                                      "- Cara Pengadaan : ".$this->getCaraPengadaan($rows_rup->cara_pengadaan)."<br>".
                                                                      "- Tanggal Buat : ".date_format(date_create($rows_rup->tanggal_buat), "H:i:s d-m-Y")."<br>".
                                                                      "- Tanggal Update : ".date_format(date_create($rows_rup->tanggal_update), "H:i:s d-m-Y");
                              $dataColumn['jenis_belanja']       =    $this->getJenisBelanja($rows_rup->jenis_belanja);
                              $dataColumn['metode_pemilihan']    =    $this->getMetodePemilihan($rows_rup->metode_pemilihan);
                              $dataColumn['pagu']                =    "Rp.".number_format(($rows_rup->pagu_paket)+0);
                              $dataColumn['aksi']                =    $option;
                              
                              $data[] = $dataColumn;
                         }
                    }

                    $json_data = array(
                                        "draw"              => intval($this->input->post('draw')),
                                        "recordsTotal"      => intval($result_rup_count),
                                        "recordsFiltered"   => intval($result_rup_count_filtered),
                                        "data"              => $data
                                   );
                 
                    echo json_encode($json_data); 
               }
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ********** SKPD Form Data ********* //
     // =================================== //
     // *
     
     public function getDataSKPDForm($id_skpd){
          if ($this->session->userdata('auth_id') != "") {
               $result_skpd   = $this->model->getAllDataSKPDByID($id_skpd);
               $data          = array();
               foreach ($result_skpd->result() as $rows_skpd) {
                    $result_struktur_anggaran = $this->model->getDataStrukturAnggaran($rows_skpd->kd_skpd);
                    foreach ($result_struktur_anggaran->result() as $rows_struktur_anggaran) {
                         $kd_skpd       = explode(".", $rows_struktur_anggaran->kd_skpd);
                         $data[]   =    array(
                                             "id_skpd"           =>   $rows_struktur_anggaran->id,
                                             "kd_skpd"           =>   $rows_struktur_anggaran->kd_skpd,
                                             "mak_skpd"          =>   sprintf("%01s", $kd_skpd[0]).".".
                                                                      sprintf("%02s", $kd_skpd[1]).".".
                                                                      sprintf("%03s", $kd_skpd[2]).".".
                                                                      sprintf("%03s", $kd_skpd[3]),
                                             "alamat"            =>   $rows_struktur_anggaran->alamat,
                                        );
                    }
               }

               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ************* PPK Data ************ //
     // =================================== //
     // *
     
     public function getDataPPK($id_skpd){
          if ($this->session->userdata('auth_id') != "") {
               $result_ppk    = $this->model->getDataPPKByIDSKPD($id_skpd);
               $data          = array();
               if ($result_ppk->num_rows() > 0) {
                    foreach ($result_ppk->result() as $rows_ppk) { 
                         $data[]   = array(
                                        "id"             => $rows_ppk->id,
                                        "username"       => strtoupper($rows_ppk->username),
                                        "nama"           => strtoupper($rows_ppk->nama),
                                   );
                    }
               }
               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ******* SKPD Swakelola Data ******* //
     // =================================== //
     // *
     
     public function getDataSKPDSwakelola($id_skpd){
          if ($this->session->userdata('auth_id') != "") {
               $result_skpd   = $this->model->getDataSKPDSwakelola($id_skpd);
               $data          = array();
               if ($result_skpd->num_rows() > 0) {
                    foreach ($result_skpd->result() as $rows_skpd) {
                         $data[]   = array(
                                        "id"                => $rows_skpd->id,
                                        "kd_skpd"           => $rows_skpd->kd_skpd,
                                        "nama_skpd"         => $rows_skpd->nama_skpd,
                                   );
                    }
               }
               echo json_encode($data);
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ********** Pagu By ID RO ********** //
     // =================================== //
     // *
     
     public function getDataPaguByIDRO($id_rincian_obyek){
          if ($this->session->userdata('auth_id') != "") {
               $result_ro     =    $this->model->getPaguRincianObyekByID($id_rincian_obyek);
               foreach ($result_ro->result() as $rows_ro) {
                    $result_rup    =    $this->model->getPaguDataRUPByIDRO($rows_ro->kd_skpd, $rows_ro->id);
                    if ($result_rup->num_rows() > 0) {
                         foreach ($result_rup->result() as $rows_rup) {
                              $pagu_rup      = (($rows_ro->jumlah+0) - ($rows_rup->pagu_paket+0));
                         }
                    }
                    else{
                         $pagu_rup      = (($rows_ro->jumlah+0) - 0);
                    }
               }

               echo json_encode(array(array("pagu_tersedia" => $pagu_rup)));
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ******** RUP REGISTER DATA ******** //
     // =================================== //
     // *
     
     public function uploadData(){
          if ($this->session->userdata('auth_id') != "") {
               $tahun                        =   $this->input->post("tahun");
               $id_skpd                      =   $this->input->post("id_skpd");
               $kd_skpd                      =   $this->input->post("kd_skpd");
               $program                      =   explode("_", $this->input->post("program"));
               $kegiatan                     =   explode("_", $this->input->post("kegiatan"));
               $rincian_obyek                =   explode("_", $this->input->post("rincian_obyek"));
               $kd_urusan                    =   $this->input->post("kd_urusan");
               $kd_kelompok                  =   $this->input->post("kd_kelompok");
               $kd_opd                       =   $this->input->post("kd_opd");
               $kd_program                   =   $this->input->post("kd_program");
               $kd_kegiatan                  =   $this->input->post("kd_kegiatan");
               $kd_rekening                  =   $this->input->post("kd_rekening");
               $id_ppk                       =   explode("-", $this->input->post("id_user_ppk"));
               $nama_paket                   =   $this->input->post("nama_paket");
               $volume_pekerjaan             =   $this->input->post("volume_pekerjaan");
               $cara_pengadaan               =   $this->input->post("cara_pengadaan");
               $tipe_swakelola               =   $this->input->post("tipe_swakelola");
               $skpd_swakelola               =   $this->input->post("skpd_swakelola");
               $jenis_belanja                =   $this->input->post("jenis_belanja");
               $uraian_pekerjaan             =   $this->input->post("uraian_pekerjaan");
               $spesifikasi_pekerjaan        =   $this->input->post("spesifikasi_pekerjaan");
               $lokasi_pekerjaan             =   $this->input->post("lokasi_pekerjaan");
               $pagu_paket                   =   $this->input->post("pagu_paket");
               $pra_dipa                     =   $this->input->post("pra_dipa");
               $nomor_renja                  =   $this->input->post("nomor_renja");
               $nomor_izin_tahun_jamak       =   $this->input->post("no_izin_tahun_jamak");
               $sumber_dana                  =   $this->input->post("sumber_dana");
               $produk_dalam_negeri          =   $this->input->post("produk_dalam_negeri");
               $usaha_kecil                  =   $this->input->post("usaha_kecil");
               $umumkan_paket                =   $this->input->post("umumkan_paket");
               $jumlah_paket                 =   $this->input->post("jumlah_paket");
               $jenis_pengadaan              =   $this->input->post("jenis_pengadaan");
               $metode_pemilihan             =   $this->input->post("metode_pemilihan");
               $pengadaan_awal               =   $this->input->post("pelaksanaan_pengadaan_awal");
               $pengadaan_akhir              =   $this->input->post("pelaksanaan_pengadaan_akhir");
               $kontrak_awal                 =   $this->input->post("pelaksanaan_kontrak_awal");
               $kontrak_akhir                =   $this->input->post("pelaksanaan_kontrak_akhir");
               $penggunaan_awal              =   $this->input->post("pelaksanaan_penggunaan_awal");
               $penggunaan_akhir             =   $this->input->post("pelaksanaan_penggunaan_akhir");
               $pekerjaan_awal               =   $this->input->post("pelaksanaan_pekerjaan_awal");
               $pekerjaan_akhir              =   $this->input->post("pelaksanaan_pekerjaan_akhir");


               if ($cara_pengadaan == 1) {
                    $tipe_swakelola                = NULL;
                    $id_skpd_swakelola             = NULL;
                    $pekerjaan_awal                = NULL;
                    $pekerjaan_akhir               = NULL;
               }
               if ($cara_pengadaan == 2) {
                    $produk_dalam_negeri           = NULL;
                    $usaha_kecil                   = NULL;
                    $metode_pemilihan              = NULL;
                    $pengadaan_awal                = NULL;
                    $pengadaan_akhir               = NULL;
                    $kontrak_awal                  = NULL;
                    $kontrak_akhir                 = NULL;
                    $penggunaan_awal               = NULL;
                    $penggunaan_akhir              = NULL;

                    if ($tipe_swakelola == 2) {
                         $explode_skpd_swakelola   = explode("-", $skpd_swakelola);
                         $id_skpd_swakelola        = $explode_skpd_swakelola[0];
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

               $kd_mak       =     $kd_urusan.".".$kd_kelompok.".".$kd_opd.".".$kd_program.".".$kd_kegiatan.".".$kd_rekening;

               // Create Data
               $data = array(
                         "tahun"                            => $tahun,
                         "kd_skpd"                          => $kd_skpd,
                         "id_skpd"                          => $id_skpd,
                         "id_program"                       => $program[0],
                         "id_kegiatan"                      => $kegiatan[0],
                         "id_rincian_obyek"                 => $rincian_obyek[0],
                         "id_user_ppk"                      => $id_ppk[0],
                         "nama_paket"                       => $nama_paket,
                         "volume_pekerjaan"                 => $volume_pekerjaan,
                         "jumlah_paket"                     => $jumlah_paket,
                         "uraian_pekerjaan"                 => $uraian_pekerjaan,
                         "spesifikasi_pekerjaan"            => $spesifikasi_pekerjaan,
                         "lokasi_pekerjaan"                 => $lokasi_pekerjaan,
                         "produk_dalam_negeri"              => $produk_dalam_negeri,
                         "usaha_kecil"                      => $usaha_kecil,
                         "sumber_dana"                      => $sumber_dana,
                         "pra_dipa"                         => $pra_dipa,
                         "nomor_renja"                      => $nomor_renja,
                         "no_izin_tahun_jamak"              => $nomor_izin_tahun_jamak,
                         "kd_mak"                           => $kd_mak,
                         "pagu_paket"                       => $pagu_paket,
                         "cara_pengadaan"                   => $cara_pengadaan,
                         "tipe_swakelola"                   => $tipe_swakelola,
                         "id_skpd_swakelola"                => $id_skpd_swakelola,
                         "jenis_belanja"                    => $jenis_belanja,
                         "jenis_pengadaan"                  => $jenis_pengadaan,
                         "metode_pemilihan"                 => $metode_pemilihan,
                         "umumkan_paket"                    => $umumkan_paket,
                         "pelaksanaan_pengadaan_awal"       => $pengadaan_awal,
                         "pelaksanaan_pengadaan_akhir"      => $pengadaan_akhir,
                         "pelaksanaan_kontrak_awal"         => $kontrak_awal,
                         "pelaksanaan_kontrak_akhir"        => $kontrak_akhir,
                         "pelaksanaan_pemanfaatan_awal"     => $penggunaan_awal,
                         "pelaksanaan_pemanfaatan"          => $penggunaan_akhir,
                         "pelaksanaan_pekerjaan_awal"       => $pekerjaan_awal,
                         "pelaksanaan_pekerjaan_akhir"      => $pekerjaan_akhir,
                         "is_aktif"                         => 1,
                         "is_final"                         => 1,
                         "is_deleted"                       => 0,
                         "id_rup_awal"                      => 0,
                         "is_last_paket"                    => 1
                    );
               $process_upload     =    $this->model->insertRUPData($data);
               if ($process_upload == TRUE) {
                    echo json_encode(array("status" => TRUE));
               }
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // =================================== //
     // ********* RUP Revisi DATA ********* //
     // =================================== //
     // *
     
     public function revisiData($id_rup){
          if ($this->session->userdata('auth_id') != "") {

               $result_rup    = $this->model->getDataRUPByID($id_rup);
               $data          = array();
               foreach ($result_rup->result() as $rows_rup) {
                    $kd_mak   = explode(".", $rows_rup->kd_mak);

                    // Result PPK
                    $result_ppk         =    $this->model->getDataPPKByID($rows_rup->id_user_ppk);
                    $id_user_ppk   =    null;
                    if ($result_ppk->num_rows() > 0) {
                         foreach ($result_ppk->result() as $rows_ppk) {
                              $id_user_ppk   =    $rows_ppk->id."-".strtoupper($rows_ppk->username)."-".$rows_ppk->nama;
                         }
                    }


                    // Result Pagu Rincian Obyek
                    $result_pagu_ro     =    $this->model->getPaguRincianObyekByID($rows_rup->id_rincian_obyek);
                    $pagu_ro = 0;
                    if ($result_pagu_ro->num_rows() > 0) {
                         foreach ($result_pagu_ro->result() as $rows_pagu_ro) {
                              $pagu_ro = ($rows_pagu_ro->jumlah+0);
                         }
                    }

                    // Result Pagu RUP
                    $result_pagu_rup     =    $this->model->getPaguDataRUPByIDRO($rows_rup->kd_skpd, $rows_rup->id_rincian_obyek);
                    $pagu_rup = 0;
                    if ($result_pagu_rup->num_rows() > 0) {
                         foreach ($result_pagu_rup->result() as $rows_pagu_rup) {
                              $pagu_rup = ($rows_pagu_rup->pagu_paket+0);
                         }
                    }

                    $pagu_tersedia = (($pagu_ro+0) - ($pagu_rup+0) + ($rows_rup->pagu_paket+0));


                    // SKPD Swakelola
                    $id_skpd_swakelola = NULL;
                    if ($rows_rup->cara_pengadaan == 2) {
                         if ($rows_rup->tipe_swakelola == 2) {
                              $result_skpd_swakelola = $this->model->getAllDataSKPDByID($rows_rup->id_skpd_swakelola);
                              foreach ($result_skpd_swakelola->result() as $rows_skpd_swakelola) {
                                   $id_skpd_swakelola = $rows_skpd_swakelola->id."-".$rows_skpd_swakelola->kd_skpd."-".$rows_skpd_swakelola->nama_skpd;
                              }
                         }
                    }

                    // GET Data
                    $data[] = array(
                                   "kd_skpd"                          => $rows_rup->kd_skpd,
                                   "id_skpd"                          => $rows_rup->id_skpd,
                                   "id_program"                       => $rows_rup->id_program,
                                   "kd_program"                       => $rows_rup->kd_program,
                                   "nama_program"                     => $rows_rup->keterangan_program,
                                   "id_kegiatan"                      => $rows_rup->id_kegiatan,
                                   "kd_kegiatan"                      => $rows_rup->kd_kegiatan,
                                   "nama_kegiatan"                    => $rows_rup->keterangan_kegiatan,
                                   "id_rincian_obyek"                 => $rows_rup->id_rincian_obyek,
                                   "kd_rekening"                      => $rows_rup->kd_rekening,
                                   "nama_rekening"                    => $rows_rup->nama_rekening,
                                   "id_user_ppk"                      => $id_user_ppk,
                                   "nama_paket"                       => $rows_rup->nama_paket,
                                   "volume_pekerjaan"                 => $rows_rup->volume_pekerjaan,
                                   "jumlah_paket"                     => $rows_rup->jumlah_paket,
                                   "uraian_pekerjaan"                 => $rows_rup->uraian_pekerjaan,
                                   "spesifikasi_pekerjaan"            => $rows_rup->spesifikasi_pekerjaan,
                                   "lokasi_pekerjaan"                 => $rows_rup->lokasi_pekerjaan,
                                   "produk_dalam_negeri"              => $rows_rup->produk_dalam_negeri,
                                   "usaha_kecil"                      => $rows_rup->usaha_kecil,
                                   "sumber_dana"                      => $rows_rup->sumber_dana,
                                   "pra_dipa"                         => $rows_rup->pra_dipa,
                                   "nomor_renja"                      => $rows_rup->nomor_renja,
                                   "no_izin_tahun_jamak"              => $rows_rup->no_izin_tahun_jamak,
                                   "kd_mak_program"                   => $kd_mak[5],
                                   "kd_mak_kegiatan"                  => $kd_mak[6],
                                   "kd_mak_rekening"                  => $kd_mak[7].".".
                                                                         $kd_mak[8].".".
                                                                         $kd_mak[9].".".
                                                                         $kd_mak[10].".".
                                                                         $kd_mak[11],
                                   "pagu_paket"                       => ($rows_rup->pagu_paket+0),
                                   "pagu_tersedia"                    => $pagu_tersedia,
                                   "cara_pengadaan"                   => $rows_rup->cara_pengadaan,
                                   "tipe_swakelola"                   => $rows_rup->tipe_swakelola,
                                   "id_skpd_swakelola"                => $id_skpd_swakelola,
                                   "jenis_belanja"                    => $rows_rup->jenis_belanja,
                                   "jenis_pengadaan"                  => $rows_rup->jenis_pengadaan,
                                   "metode_pemilihan"                 => $rows_rup->metode_pemilihan,
                                   "umumkan_paket"                    => $rows_rup->umumkan_paket,
                                   "pelaksanaan_pengadaan_awal"       => $rows_rup->pelaksanaan_pengadaan_awal,
                                   "pelaksanaan_pengadaan_akhir"      => $rows_rup->pelaksanaan_pengadaan_akhir,
                                   "pelaksanaan_kontrak_awal"         => $rows_rup->pelaksanaan_kontrak_awal,
                                   "pelaksanaan_kontrak_akhir"        => $rows_rup->pelaksanaan_kontrak_akhir,
                                   "pelaksanaan_pemanfaatan_awal"     => $rows_rup->pelaksanaan_pemanfaatan_awal,
                                   "pelaksanaan_pemanfaatan_akhir"    => $rows_rup->pelaksanaan_pemanfaatan,
                                   "pelaksanaan_pekerjaan_awal"       => $rows_rup->pelaksanaan_pekerjaan_awal,
                                   "pelaksanaan_pekerjaan_akhir"      => $rows_rup->pelaksanaan_pekerjaan_akhir,
                                   "id_rup_sebelumnya"                => $rows_rup->id,
                                   "id_rup_awal"                      => $rows_rup->id_rup_awal
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
               $tahun                        =   $this->input->post("tahun");
               $id_skpd                      =   $this->input->post("id_skpd");
               $kd_skpd                      =   $this->input->post("kd_skpd");
               $id_rup_sebelumnya            =   $this->input->post("id_rup");
               $id_rup_awal                  =   $this->input->post("id_rup_awal");
               $program                      =   $this->input->post("program");
               $kegiatan                     =   $this->input->post("kegiatan");
               $rincian_obyek                =   $this->input->post("rincian_obyek");
               $kd_urusan                    =   $this->input->post("kd_urusan");
               $kd_kelompok                  =   $this->input->post("kd_kelompok");
               $kd_opd                       =   $this->input->post("kd_opd");
               $kd_program                   =   $this->input->post("kd_program");
               $kd_kegiatan                  =   $this->input->post("kd_kegiatan");
               $kd_rekening                  =   $this->input->post("kd_rekening");
               $id_ppk                       =   explode("-", $this->input->post("id_user_ppk"));
               $nama_paket                   =   $this->input->post("nama_paket");
               $volume_pekerjaan             =   $this->input->post("volume_pekerjaan");
               $cara_pengadaan               =   $this->input->post("cara_pengadaan");
               $tipe_swakelola               =   $this->input->post("tipe_swakelola");
               $skpd_swakelola               =   $this->input->post("skpd_swakelola");
               $jenis_belanja                =   $this->input->post("jenis_belanja");
               $uraian_pekerjaan             =   $this->input->post("uraian_pekerjaan");
               $spesifikasi_pekerjaan        =   $this->input->post("spesifikasi_pekerjaan");
               $lokasi_pekerjaan             =   $this->input->post("lokasi_pekerjaan");
               $pagu_paket                   =   $this->input->post("pagu_paket");
               $pra_dipa                     =   $this->input->post("pra_dipa");
               $nomor_renja                  =   $this->input->post("nomor_renja");
               $nomor_izin_tahun_jamak       =   $this->input->post("no_izin_tahun_jamak");
               $sumber_dana                  =   $this->input->post("sumber_dana");
               $produk_dalam_negeri          =   $this->input->post("produk_dalam_negeri");
               $usaha_kecil                  =   $this->input->post("usaha_kecil");
               $umumkan_paket                =   $this->input->post("umumkan_paket");
               $jumlah_paket                 =   $this->input->post("jumlah_paket");
               $jenis_pengadaan              =   $this->input->post("jenis_pengadaan");
               $metode_pemilihan             =   $this->input->post("metode_pemilihan");
               $pengadaan_awal               =   $this->input->post("pelaksanaan_pengadaan_awal");
               $pengadaan_akhir              =   $this->input->post("pelaksanaan_pengadaan_akhir");
               $kontrak_awal                 =   $this->input->post("pelaksanaan_kontrak_awal");
               $kontrak_akhir                =   $this->input->post("pelaksanaan_kontrak_akhir");
               $penggunaan_awal              =   $this->input->post("pelaksanaan_penggunaan_awal");
               $penggunaan_akhir             =   $this->input->post("pelaksanaan_penggunaan_akhir");
               $pekerjaan_awal               =   $this->input->post("pelaksanaan_pekerjaan_awal");
               $pekerjaan_akhir              =   $this->input->post("pelaksanaan_pekerjaan_akhir");
               $alasan_revisi                =   $this->input->post("alasan_revisi");


               if ($cara_pengadaan == 1) {
                    $tipe_swakelola                = NULL;
                    $id_skpd_swakelola             = NULL;
                    $pekerjaan_awal                = NULL;
                    $pekerjaan_akhir               = NULL;
               }
               if ($cara_pengadaan == 2) {
                    $produk_dalam_negeri           = NULL;
                    $usaha_kecil                   = NULL;
                    $metode_pemilihan              = NULL;
                    $pengadaan_awal                = NULL;
                    $pengadaan_akhir               = NULL;
                    $kontrak_awal                  = NULL;
                    $kontrak_akhir                 = NULL;
                    $penggunaan_awal               = NULL;
                    $penggunaan_akhir              = NULL;

                    if ($tipe_swakelola == 2) {
                         $explode_skpd_swakelola   = explode("-", $skpd_swakelola);
                         $id_skpd_swakelola        = $explode_skpd_swakelola[0];
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

               if ($id_rup_awal <= 0) {
                    $id_rup_awal = $id_rup_sebelumnya;
               }
               if ($id_rup_awal > 0) {
                    $id_rup_awal = $id_rup_awal;
               }

               $kd_mak       =     $kd_urusan.".".$kd_kelompok.".".$kd_opd.".".$kd_program.".".$kd_kegiatan.".".$kd_rekening;

               // Create Data
               $data_upload = array(
                                   "tahun"                            => $tahun,
                                   "kd_skpd"                          => $kd_skpd,
                                   "id_skpd"                          => $id_skpd,
                                   "id_program"                       => $program,
                                   "id_kegiatan"                      => $kegiatan,
                                   "id_rincian_obyek"                 => $rincian_obyek,
                                   "id_user_ppk"                      => $id_ppk[0],
                                   "nama_paket"                       => $nama_paket,
                                   "volume_pekerjaan"                 => $volume_pekerjaan,
                                   "jumlah_paket"                     => $jumlah_paket,
                                   "uraian_pekerjaan"                 => $uraian_pekerjaan,
                                   "spesifikasi_pekerjaan"            => $spesifikasi_pekerjaan,
                                   "lokasi_pekerjaan"                 => $lokasi_pekerjaan,
                                   "produk_dalam_negeri"              => $produk_dalam_negeri,
                                   "usaha_kecil"                      => $usaha_kecil,
                                   "sumber_dana"                      => $sumber_dana,
                                   "pra_dipa"                         => $pra_dipa,
                                   "nomor_renja"                      => $nomor_renja,
                                   "no_izin_tahun_jamak"              => $nomor_izin_tahun_jamak,
                                   "kd_mak"                           => $kd_mak,
                                   "pagu_paket"                       => $pagu_paket,
                                   "cara_pengadaan"                   => $cara_pengadaan,
                                   "tipe_swakelola"                   => $tipe_swakelola,
                                   "id_skpd_swakelola"                => $id_skpd_swakelola,
                                   "jenis_belanja"                    => $jenis_belanja,
                                   "jenis_pengadaan"                  => $jenis_pengadaan,
                                   "metode_pemilihan"                 => $metode_pemilihan,
                                   "umumkan_paket"                    => $umumkan_paket,
                                   "pelaksanaan_pengadaan_awal"       => $pengadaan_awal,
                                   "pelaksanaan_pengadaan_akhir"      => $pengadaan_akhir,
                                   "pelaksanaan_kontrak_awal"         => $kontrak_awal,
                                   "pelaksanaan_kontrak_akhir"        => $kontrak_akhir,
                                   "pelaksanaan_pemanfaatan_awal"     => $penggunaan_awal,
                                   "pelaksanaan_pemanfaatan"          => $penggunaan_akhir,
                                   "pelaksanaan_pekerjaan_awal"       => $pekerjaan_awal,
                                   "pelaksanaan_pekerjaan_akhir"      => $pekerjaan_akhir,
                                   "is_aktif"                         => 1,
                                   "is_final"                         => 1,
                                   "is_deleted"                       => 0,
                                   "id_rup_awal"                      => $id_rup_awal,
                                   "is_last_paket"                    => 1
                              );
               $process_upload     =    $this->model->insertRevisiRUPData($data_upload);
               if ($process_upload["status"] == TRUE) {
                    $data_update_rup = array(
                                             "is_aktif"      => 0,
                                             "id_rup_awal"   => $id_rup_awal,
                                             "is_last_paket" => 0
                                        );
                    $update_rup    =    $this->model->updateRUPData($id_rup_sebelumnya, $data_update_rup);
                    if ($update_rup == TRUE) {
                         $data_update_realisasi_rup = array(
                                                            "id_rup"        => $process_upload["id_rup_baru"]
                                                       );
                         $update_realisasi_rup = $this->model->updateRealisasiRUPData($id_rup_sebelumnya, $data_update_realisasi_rup);
                         if ($update_realisasi_rup == TRUE) {
                              $check_rows_history = $this->model->getAllDataHistoryRevisiRUP($id_rup_awal)->num_rows();
                              $revisi_ke = ($check_rows_history+0) + 1;
                              $nama_cara_pengadaan = ["-", "PENYEDIA", "SWAKELOLA", "PENYEDIADALAMSWAKELOLA"];

                              $data_history_revisi = array(
                                                            "tahun"               => $tahun,
                                                            "id_rup_awal"         => $id_rup_awal,
                                                            "id_rup_sebelumnya"   => $id_rup_sebelumnya,
                                                            "id_rup_baru"         => $process_upload["id_rup_baru"],
                                                            "revisi_ke"           => $revisi_ke,
                                                            "jenis"               => $nama_cara_pengadaan[$cara_pengadaan],
                                                            "tipe"                => "SATUKESATU",
                                                            "alasan_revisi"       => $alasan_revisi,
                                                       );
                              $process_upload_history = $this->model->insertHistoryRUPData($data_history_revisi);
                              if ($process_upload_history == TRUE) {
                                   echo json_encode(array("status" => TRUE));
                              }
                         }
                    }
               }
               
          }
          else{
               redirect(base_url());
          }
     }






     // *
     // ============================================== //
     // ************ Update - Revisi Data ************ //
     // ============================================== //
     // *
     public function updateRUPData(){
          if ($this->session->userdata('auth_id') != "") {
               $tipe_form          = $this->input->post("tipe_form");
               $id_rup             = $this->input->post("id_rup");
               $alasan_revisi      = $this->input->post("alasan_revisi");
               $tahun              = $this->input->post("tahun");
               $get_cara_pengadaan = ['-', 'PENYEDIA', 'SWAKELOLA']; 

               $result_rup = $this->model->getDataRUPByID($id_rup);
               foreach ($result_rup->result() as $rows_rup) {
                    $cara_pengadaan     = $rows_rup->cara_pengadaan;
                    $check_id_rup_awal  = $rows_rup->id_rup_awal;
               }

               if ($check_id_rup_awal <= 0) {
                    $id_rup_awal = $id_rup;
               }
               if ($check_id_rup_awal > 0) {
                    $id_rup_awal = $check_id_rup_awal;
               }

               $check_revisi_ke    = $this->model->getAllDataHistoryRevisiRUP($id_rup_awal)->num_rows();
               $revisi_ke          = ($check_revisi_ke+0) + 1;


               // Update Data RUP
               if ($tipe_form == "PENGAKTIFAN") {
                    $data_rup                = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 1, "is_deleted" => 0);
                    $data_realisasi_rup      = array("is_aktif"    => 1);
               }
               if ($tipe_form == "NONAKTIFKAN") {
                    $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0, "is_deleted" => 0);
                    $data_realisasi_rup      = array("is_aktif"    => 0);
               }
               if ($tipe_form == "PEMBATALAN") {
                    $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0, "is_deleted" => 1);
                    $data_realisasi_rup      = array("is_aktif"    => 0);
               }
               $process_update_rup = $this->model->updateRUPData($id_rup, $data_rup);
               if ($process_update_rup == TRUE) {
                    $process_update_realisasi = $this->model->updateRealisasiRUPData($id_rup, $data_realisasi_rup);
                    if ($process_update_realisasi == TRUE) {
                         // Create History Paket
                         $data_history  = array(
                                                       "tahun"             => $tahun,
                                                       "id_rup_awal"       => $id_rup_awal,
                                                       "id_rup_sebelumnya" => $id_rup,
                                                       "id_rup_baru"       => $id_rup,
                                                       "revisi_ke"         => $revisi_ke,
                                                       "jenis"             => $get_cara_pengadaan[$cara_pengadaan],
                                                       "tipe"              => $tipe_form,
                                                       "alasan_revisi"     => $alasan_revisi
                                                  ); 

                         $process_upload_history = $this->model->insertHistoryRUPData($data_history);
                         if ($process_upload_history == TRUE) {
                              echo json_encode(array("status"=>TRUE));
                         }
                    }
               }
          }
          else{
               redirect(base_url());
          }
     }






     // *
     // ==================================================== //
     // ************ Update - Multi Revisi Data ************ //
     // ==================================================== //
     // *
     public function updateRUPMultiData(){
          if ($this->session->userdata('auth_id') != "") {
               $tipe_form          = $this->input->post("kategori_eksekusi");
               $id                 = $this->input->post("token_data");
               $alasan_revisi      = $this->input->post("alasan_revisi");
               $tahun              = $this->input->post("tahun");
               $get_cara_pengadaan = ['-', 'PENYEDIA', 'SWAKELOLA']; 

               foreach ($id as $id_rup) {
                    $result_rup = $this->model->getDataRUPByID($id_rup);
                    foreach ($result_rup->result() as $rows_rup) {
                         $cara_pengadaan     = $rows_rup->cara_pengadaan;
                         $check_id_rup_awal  = $rows_rup->id_rup_awal;
                    }

                    if ($check_id_rup_awal <= 0) {
                         $id_rup_awal = $id_rup;
                    }
                    if ($check_id_rup_awal > 0) {
                         $id_rup_awal = $check_id_rup_awal;
                    }

                    $check_revisi_ke    = $this->model->getAllDataHistoryRevisiRUP($id_rup_awal)->num_rows();
                    $revisi_ke          = ($check_revisi_ke+0) + 1;


                    // Update Data RUP
                    if ($tipe_form == "PENGAKTIFAN") {
                         $data_rup                = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 1, "is_deleted" => 0);
                         $data_realisasi_rup      = array("is_aktif"    => 1);
                    }
                    if ($tipe_form == "NONAKTIFKAN") {
                         $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0, "is_deleted" => 0);
                         $data_realisasi_rup      = array("is_aktif"    => 0);
                    }
                    if ($tipe_form == "PEMBATALAN") {
                         $data_rup      = array("id_rup_awal" => $id_rup_awal, "is_aktif" => 0, "is_deleted" => 1);
                         $data_realisasi_rup      = array("is_aktif"    => 0);
                    }
                    $process_update_rup = $this->model->updateRUPData($id_rup, $data_rup);
                    if ($process_update_rup == TRUE) {
                         $process_update_realisasi = $this->model->updateRealisasiRUPData($id_rup, $data_realisasi_rup);
                         if ($process_update_realisasi == TRUE) {
                              // Create History Paket
                              $data_history  = array(
                                                            "tahun"             => $tahun,
                                                            "id_rup_awal"       => $id_rup_awal,
                                                            "id_rup_sebelumnya" => $id_rup,
                                                            "id_rup_baru"       => $id_rup,
                                                            "revisi_ke"         => $revisi_ke,
                                                            "jenis"             => $get_cara_pengadaan[$cara_pengadaan],
                                                            "tipe"              => $tipe_form,
                                                            "alasan_revisi"     => $alasan_revisi
                                                       ); 

                              $this->model->insertHistoryRUPData($data_history);
                         }
                    }
               }
               echo json_encode(array("status"=>TRUE));
          }
          else{
               redirect(base_url());
          }
     }





     // *
     // ==================================================== //
     // ************ Update - Multi Revisi Data ************ //
     // ==================================================== //
     // *
     public function getDataRUPDataTable($id_rup){
          if ($this->session->userdata('auth_id') != "") {
               $result_rup = $this->model->getDataRUPByID($id_rup);
               $data  = array();
               if ($result_rup->num_rows() > 0) {
                    foreach ($result_rup->result() as $rows_rup) {
                         // Result PPK
                         $result_ppk         =    $this->model->getDataPPKByID($rows_rup->id_user_ppk);
                         if ($result_ppk->num_rows() > 0) {
                              foreach ($result_ppk->result() as $rows_ppk) {
                                   $id_user_ppk   =    "[".strtoupper($rows_ppk->username)."] -".strtoupper($rows_ppk->nama);
                              }
                         }
                         if ($result_ppk->num_rows() <= 0) {
                              $id_user_ppk   =    "[ABCD] - PPK Tidak Ditemukan";
                         }

                         if ($rows_rup->id_rup_awal > 0) {
                              $result_rup_relation = $this->model->getDataIDRUPByIDRUPAwal($rows_rup->id_rup_awal);
                              foreach ($result_rup_relation->result() as $rows_rup_relation) {
                                   if ($rows_rup_relation->id != $rows_rup->id) {
                                        $action[] = "<button class='btn btn-primary smep-rupenda-data-modal-change-btn' style='margin-right:3px;' data-id='".$rows_rup_relation->id."'>Paket : "."2019".$rows_rup_relation->id."</button>";
                                   }
                              }
                         }
                         if ($rows_rup->id_rup_awal <= 0) {
                              $action[]   =    "<input type='text' class='form-control' readonly='readonly' value='Tidak Pernah Direvisi Sebelumnya'>";
                         }

                         $get_boolean = array("YA", "TIDAK");
                         $get_sumber_dana = array("","APBD","APBDP","APBN","APBNP","BLU","BLUD","BUMD","BUMN","PHLN","PNBP","Lainnya");

                         $data   =    array(

                                             // GENERAL

                                             "id_rup"                           => "2019".$rows_rup->id+0,
                                             "program"                          => "[".$rows_rup->kd_program."] - ".$rows_rup->keterangan_program,
                                             "kegiatan"                         => "[".$rows_rup->kd_kegiatan."] - ".$rows_rup->keterangan_kegiatan,
                                             "rincian_obyek"                    => "[".$rows_rup->kd_rekening."] - ".$rows_rup->nama_rekening,
                                             "kode_mak"                         => $rows_rup->kd_mak,
                                             "nama_ppk"                         => $id_user_ppk,
                                             "nama_paket"                       => $rows_rup->nama_paket,
                                             "volume_pekerjaan"                 => $rows_rup->volume_pekerjaan,
                                             "cara_pengadaan"                   => strtoupper($this->getCaraPengadaan($rows_rup->cara_pengadaan)),
                                             "tipe_swakelola"                   => $rows_rup->tipe_swakelola,
                                             "id_skpd_swakelola"                => $rows_rup->id_skpd_swakelola,
                                             "jenis_belanja"                    => strtoupper($this->getJenisBelanja($rows_rup->jenis_belanja)),
                                             "uraian_pekerjaan"                 => $rows_rup->uraian_pekerjaan,
                                             "spesifikasi_pekerjaan"            => $rows_rup->spesifikasi_pekerjaan,
                                             "lokasi_pekerjaan"                 => $rows_rup->lokasi_pekerjaan,
                                             "pagu_paket"                       => ($rows_rup->pagu_paket+0),
                                             "pra_dipa"                         => $get_boolean[$rows_rup->pra_dipa],
                                             "nomor_renja"                      => $rows_rup->nomor_renja,
                                             "no_izin_tahun_jamak"              => $this->is_emptyValue($rows_rup->no_izin_tahun_jamak),
                                             "produk_dalam_negeri"              => $rows_rup->produk_dalam_negeri,
                                             "usaha_kecil"                      => $rows_rup->usaha_kecil,
                                             "sumber_dana"                      => $get_sumber_dana[$rows_rup->sumber_dana],
                                             
                                             // TEPRA
                                             "jumlah_paket"                     => $rows_rup->jumlah_paket,
                                             "jenis_pengadaan"                  => strtoupper($this->getJenisPengadaan($rows_rup->jenis_pengadaan)),
                                             "metode_pemilihan"                 => strtoupper($this->getMetodePemilihan($rows_rup->metode_pemilihan)),

                                             // Jadwal Pekerjaan
                                             "pelaksanaan_pengadaan_awal"       => $rows_rup->pelaksanaan_pengadaan_awal,
                                             "pelaksanaan_pengadaan_akhir"      => $rows_rup->pelaksanaan_pengadaan_akhir,
                                             "pelaksanaan_kontrak_awal"         => $rows_rup->pelaksanaan_kontrak_awal,
                                             "pelaksanaan_kontrak_akhir"        => $rows_rup->pelaksanaan_kontrak_akhir,
                                             "pelaksanaan_pemanfaatan_awal"     => $rows_rup->pelaksanaan_pemanfaatan_awal,
                                             "pelaksanaan_pemanfaatan_akhir"    => $rows_rup->pelaksanaan_pemanfaatan,
                                             "pelaksanaan_pekerjaan_awal"       => $rows_rup->pelaksanaan_pekerjaan_awal,
                                             "pelaksanaan_pekerjaan_akhir"      => $rows_rup->pelaksanaan_pekerjaan_akhir,

                                             // Misc
                                             "umumkan_paket"                    => $rows_rup->umumkan_paket,
                                             "tanggal_buat"                     => $rows_rup->tanggal_buat,
                                             "tanggal_update"                   => $rows_rup->tanggal_update,
                                             "action"                           => $action,
                                        );
                    }
               }
               
               echo json_encode($data);
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