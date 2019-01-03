<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endarup_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('entry-data/Endarup_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/entry-data/rup/data');
		}
	}

	public function registerDataPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/entry-data/rup/data-register');
		}
	}

	public function editDataPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/entry-data/rup/data-edit');
		}
	}

     public function getDataUser(){
          if ($this->session->userdata("auth_id") != "") {
               $result = $this->model->getDataUsers($this->session->userdata('auth_id'));
               $data = array();
               foreach ($result->result() as $rows) {
                    $data[] = array(
                                   $rows->id,
                                   $rows->nama,
                                   $rows->status
                              );
               }
               echo json_encode($data); 
          }
     }

	public function getDataUserPPK($id_skpd){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataUsers($this->session->userdata('auth_id'));
			$data = array();
			foreach ($result->result() as $rows) {
                    if ($rows->status == 3) {
     				$data[] = array(
     							$rows->id,
     							$rows->nama,
     							$rows->status
     						);
                    }
                    else{
                         $result_all = $this->model->getDataUsersAllPPK($id_skpd);
                         foreach ($result_all->result() as $rows_all) {
                              $data[] = array(
                                        $rows_all->id,
                                        $rows_all->nama,
                                        $rows_all->status
                                   );
                         }
                    }
			}
			echo json_encode($data); 
		}
	}

	public function getMainDataAllProgram($id_skpd){
     	$result = $this->model->getMainDataAllProgram($id_skpd);
     	$data = array();
     	$no = 1;
     	foreach ($result->result() as $rows) {
     		switch ($rows->jenis_belanja) {
     			case '1':
     				$jenis_belanja = "Belanja Pegawai";
     			break;
     			case '2':
     				$jenis_belanja = "Belanja Barang/Jasa";
     			break;
     			case '3':
     				$jenis_belanja = "Belanja Modal";
     			break;
     			case '4':
     				$jenis_belanja = "Belum Teridentifikasi";
     			break;
                    case '5':
                         $jenis_belanja = "Belanja Bunga Utang";
                    break;
                    case '6':
                         $jenis_belanja = "Belanja Subsidi";
                    break;
                    case '7':
                         $jenis_belanja = "Belanja Hibah";
                    break;
                    case '8':
                         $jenis_belanja = "Belanja Bantuan Sosial";
                    break;
                    case '9':
                         $jenis_belanja = "Belanja Lain-Lain";
                    break;
     			
     			default:
     				$jenis_belanja = "Belanja Pegawai";
     			break;
     		}

               switch ($rows->cara_pengadaan) {
                    case '1':
                         $cara_pengadaan = "Penyedia";
                    break;
                    case '2':
                         $cara_pengadaan = "Swakelola";
                    break;
                    
                    default:
                         $cara_pengadaan = "Penyedia";
                    break;
               }

               $row_first = $no++."<input type='checkbox' name='token_data[]' style='margin-left:5px;' class='rupenda-delete-data' value='".$rows->id."'>";

     		$data[] = array(
     					$row_first,
     					$rows->nama_paket,
     					$jenis_belanja,
                              $cara_pengadaan,
     					number_format($rows->pagu_paket),
     					"<button class='btn btn-primary btn-sm smep-rupenda-edit-btn' data-id='".$rows->id."'><i class='fa fa-edit'></i>&nbsp;Edit</button>".
						"&nbsp;<button class='btn btn-danger btn-sm smep-rupenda-delete-btn' data-id='".$rows->id."'><i class='fa fa-trash'></i>&nbsp;Hapus</button>"
     				);
     	}
     	echo json_encode($data);   
    }

    public function getMainDataAllKegiatan($id_skpd, $id_program){
     	$result = $this->model->getMainDataAllKegiatan($id_skpd, $id_program);
     	$data = array();
     	$no = 1;
     	foreach ($result->result() as $rows) {
     		switch ($rows->jenis_belanja) {
                    case '1':
                         $jenis_belanja = "Belanja Pegawai";
                    break;
                    case '2':
                         $jenis_belanja = "Belanja Barang/Jasa";
                    break;
                    case '3':
                         $jenis_belanja = "Belanja Modal";
                    break;
                    case '4':
                         $jenis_belanja = "Belum Teridentifikasi";
                    break;
                    case '5':
                         $jenis_belanja = "Belanja Bunga Utang";
                    break;
                    case '6':
                         $jenis_belanja = "Belanja Subsidi";
                    break;
                    case '7':
                         $jenis_belanja = "Belanja Hibah";
                    break;
                    case '8':
                         $jenis_belanja = "Belanja Bantuan Sosial";
                    break;
                    case '9':
                         $jenis_belanja = "Belanja Lain-Lain";
                    break;
                    
                    default:
                         $jenis_belanja = "Belanja Pegawai";
                    break;
               }

               switch ($rows->cara_pengadaan) {
                    case '1':
                         $cara_pengadaan = "Penyedia";
                    break;
                    case '2':
                         $cara_pengadaan = "Swakelola";
                    break;
                    
                    default:
                         $cara_pengadaan = "Penyedia";
                    break;
               }

               $row_first = $no++."<input type='checkbox' name='token_data[]' style='margin-left:5px;' class='rupenda-delete-data' value='".$rows->id."'>";

               $data[] = array(
                              $row_first,
                              $rows->nama_paket,
                              $jenis_belanja,
                              $cara_pengadaan,
                              number_format($rows->pagu_paket),
                              "<button class='btn btn-primary btn-sm smep-rupenda-edit-btn' data-id='".$rows->id."'><i class='fa fa-edit'></i>&nbsp;Edit</button>".
                              "&nbsp;<button class='btn btn-danger btn-sm smep-rupenda-delete-btn' data-id='".$rows->id."'><i class='fa fa-trash'></i>&nbsp;Hapus</button>"
                         );
     	}
     	echo json_encode($data);   
    }

    public function getMainDataAllRincianObyek($id_skpd, $id_program, $id_kegiatan){
     	$result = $this->model->getMainDataAllRincianObyek($id_skpd, $id_program, $id_kegiatan);
     	$data = array();
     	$no = 1;
     	foreach ($result->result() as $rows) {
     		switch ($rows->jenis_belanja) {
                    case '1':
                         $jenis_belanja = "Belanja Pegawai";
                    break;
                    case '2':
                         $jenis_belanja = "Belanja Barang/Jasa";
                    break;
                    case '3':
                         $jenis_belanja = "Belanja Modal";
                    break;
                    case '4':
                         $jenis_belanja = "Belum Teridentifikasi";
                    break;
                    case '5':
                         $jenis_belanja = "Belanja Bunga Utang";
                    break;
                    case '6':
                         $jenis_belanja = "Belanja Subsidi";
                    break;
                    case '7':
                         $jenis_belanja = "Belanja Hibah";
                    break;
                    case '8':
                         $jenis_belanja = "Belanja Bantuan Sosial";
                    break;
                    case '9':
                         $jenis_belanja = "Belanja Lain-Lain";
                    break;
                    
                    default:
                         $jenis_belanja = "Belanja Pegawai";
                    break;
               }

               switch ($rows->cara_pengadaan) {
                    case '1':
                         $cara_pengadaan = "Penyedia";
                    break;
                    case '2':
                         $cara_pengadaan = "Swakelola";
                    break;
                    
                    default:
                         $cara_pengadaan = "Penyedia";
                    break;
               }

               $row_first = $no++."<input type='checkbox' name='token_data[]' style='margin-left:5px;' class='rupenda-delete-data' value='".$rows->id."'>";

               $data[] = array(
                              $row_first,
                              $rows->nama_paket,
                              $jenis_belanja,
                              $cara_pengadaan,
                              number_format($rows->pagu_paket),
                              "<button class='btn btn-primary btn-sm smep-rupenda-edit-btn' data-id='".$rows->id."'><i class='fa fa-edit'></i>&nbsp;Edit</button>".
                              "&nbsp;<button class='btn btn-danger btn-sm smep-rupenda-delete-btn' data-id='".$rows->id."'><i class='fa fa-trash'></i>&nbsp;Hapus</button>"
                         );
     	}
     	echo json_encode($data);   
    }
    public function getMainDataUniqueRincianObyek($id_skpd, $id_program, $id_kegiatan, $id_rincian_obyek){
     	$result = $this->model->getMainDataUniqueRincianObyek($id_skpd, $id_program, $id_kegiatan, $id_rincian_obyek);
     	$data = array();
     	$no = 1;
     	foreach ($result->result() as $rows) {
     		switch ($rows->jenis_belanja) {
                    case '1':
                         $jenis_belanja = "Belanja Pegawai";
                    break;
                    case '2':
                         $jenis_belanja = "Belanja Barang/Jasa";
                    break;
                    case '3':
                         $jenis_belanja = "Belanja Modal";
                    break;
                    case '4':
                         $jenis_belanja = "Belum Teridentifikasi";
                    break;
                    case '5':
                         $jenis_belanja = "Belanja Bunga Utang";
                    break;
                    case '6':
                         $jenis_belanja = "Belanja Subsidi";
                    break;
                    case '7':
                         $jenis_belanja = "Belanja Hibah";
                    break;
                    case '8':
                         $jenis_belanja = "Belanja Bantuan Sosial";
                    break;
                    case '9':
                         $jenis_belanja = "Belanja Lain-Lain";
                    break;
                    
                    default:
                         $jenis_belanja = "Belanja Pegawai";
                    break;
               }

               switch ($rows->cara_pengadaan) {
                    case '1':
                         $cara_pengadaan = "Penyedia";
                    break;
                    case '2':
                         $cara_pengadaan = "Swakelola";
                    break;
                    
                    default:
                         $cara_pengadaan = "Penyedia";
                    break;
               }

               $row_first = $no++."<input type='checkbox' name='token_data[]' style='margin-left:5px;' class='rupenda-delete-data' value='".$rows->id."'>";

               $data[] = array(
                              $row_first,
                              $rows->nama_paket,
                              $jenis_belanja,
                              $cara_pengadaan,
                              number_format($rows->pagu_paket),
                              "<button class='btn btn-primary btn-sm smep-rupenda-edit-btn' data-id='".$rows->id."'><i class='fa fa-edit'></i>&nbsp;Edit</button>".
                              "&nbsp;<button class='btn btn-danger btn-sm smep-rupenda-delete-btn' data-id='".$rows->id."'><i class='fa fa-trash'></i>&nbsp;Hapus</button>"
                         );
     	}
     	echo json_encode($data);   
    }

	public function getDataMAK($token){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataSKPD($token);
			foreach ($result->result() as $rows) {
				$skpd = explode(".", $rows->kd_skpd);
			}
			$kd_urusan 			= sprintf("%01s", $skpd[0]);
			$kd_kelompok_urusan = sprintf("%02s", $skpd[1]);
			$kd_opd 			= $kd_urusan.".".$kd_kelompok_urusan.".".sprintf("%03s", $skpd[2]);
			echo json_encode(array($kd_urusan, $kd_kelompok_urusan, $kd_opd));
		}
	}

	public function getDataProgram($id_skpd){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataProgram($id_skpd);
			echo json_encode($result);
		}
	}

	public function getDataKegiatan($id_program){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataKegiatan($id_program);
			echo json_encode($result);
		}
	}

	public function getDataRincianObyek($id_kegiatan){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataRincianObyek($id_kegiatan);
			echo json_encode($result);
		}
	}

	public function getDataRincianObyekUnique($token){
		if ($this->session->userdata("auth_id") != "") {
			$result = $this->model->getDataRincianObyekUnique($token);
			echo json_encode($result);
		}
	}

	public function uploadData(){
		if ($this->session->userdata("auth_id") != "") {
			$kd_mak = $this->input->post("kd_urusan").".".$this->input->post("kd_bidang").".".$this->input->post("kd_opd").".".$this->input->post("kd_program").".".$this->input->post("kd_kegiatan").".".$this->input->post("kd_rekening");
               if (is_null($this->input->post("produk_dalam_negeri"))) {
                    $pdn = '-';
               }
               if (!is_null($this->input->post("produk_dalam_negeri"))) {
                    $pdn = $this->input->post("produk_dalam_negeri");
               }
               if (is_null($this->input->post("usaha_kecil"))) {
                    $usaha_kecil = '-';
               }
               if (!is_null($this->input->post("usaha_kecil"))) {
                    $usaha_kecil = $this->input->post("usaha_kecil");
               }
               if (is_null($this->input->post("tipe_swakelola"))) {
                    $tipe_swakelola = '-';
               }
               if (!is_null($this->input->post("tipe_swakelola"))) {
                    $tipe_swakelola = $this->input->post("tipe_swakelola");
               }
               if (is_null($this->input->post("metode_pemilihan"))) {
                    $metode_pemilihan = '-';
               }
               if (!is_null($this->input->post("metode_pemilihan"))) {
                    $metode_pemilihan = $this->input->post("metode_pemilihan");
               }
               if ($this->input->post("pelaksanaan_pengadaan_awal") != "") {
                    $pelaksanaan_pengadaan_awal = $this->input->post("pelaksanaan_pengadaan_awal");
               }
               if ($this->input->post("pelaksanaan_pengadaan_awal") == "") {
                    $pelaksanaan_pengadaan_awal = '-';
               }
               if ($this->input->post("pelaksanaan_pengadaan_akhir") != "") {
                    $pelaksanaan_pengadaan_akhir = $this->input->post("pelaksanaan_pengadaan_akhir");
               }
               if ($this->input->post("pelaksanaan_pengadaan_akhir") == "") {
                    $pelaksanaan_pengadaan_akhir = '-';
               }
               if ($this->input->post("pelaksanaan_kontrak_awal") != "") {
                    $pelaksanaan_kontrak_awal = $this->input->post("pelaksanaan_kontrak_awal");
               }
               if ($this->input->post("pelaksanaan_kontrak_awal") == "") {
                    $pelaksanaan_kontrak_awal = '-';
               }
               if ($this->input->post("pelaksanaan_kontrak_akhir") != "") {
                    $pelaksanaan_kontrak_akhir = $this->input->post("pelaksanaan_kontrak_akhir");
               }
               if ($this->input->post("pelaksanaan_kontrak_akhir") == "") {
                    $pelaksanaan_kontrak_akhir = '-';
               }
               if ($this->input->post("pelaksanaan_pemanfaatan") != "") {
                    $pelaksanaan_pemanfaatan = $this->input->post("pelaksanaan_pemanfaatan");
               }
               if ($this->input->post("pelaksanaan_pemanfaatan") == "") {
                    $pelaksanaan_pemanfaatan = '-';
               }
               if ($this->input->post("pelaksanaan_pekerjaan_awal") != "") {
                    $pelaksanaan_pekerjaan_awal = $this->input->post("pelaksanaan_pekerjaan_awal");
               }
               if ($this->input->post("pelaksanaan_pekerjaan_awal") == "") {
                    $pelaksanaan_pekerjaan_awal = '-';
               }
               if ($this->input->post("pelaksanaan_pekerjaan_akhir") != "") {
                    $pelaksanaan_pekerjaan_akhir = $this->input->post("pelaksanaan_pekerjaan_akhir");
               }
               if ($this->input->post("pelaksanaan_pekerjaan_akhir") == "") {
                    $pelaksanaan_pekerjaan_akhir = '-';
               }
			$data = array(
				"tahun" => $this->input->post("tahun"),
				"id_skpd" => $this->input->post("idskpd"),
				"id_program" => $this->input->post("idprogram"),
				"id_kegiatan" => $this->input->post("idkegiatan"),
				"id_rincian_obyek" => $this->input->post("idrincianobyek"),
				"id_user_ppk" => $this->input->post("iduserppk"),
				"nama_paket" => $this->input->post("nama_paket"),
				"volume_pekerjaan" => $this->input->post("volume_pekerjaan"),
				"jumlah_paket" => $this->input->post("jumlah_paket"),
				"uraian_pekerjaan" => $this->input->post("uraian_pekerjaan"),
				"lokasi_pekerjaan" => $this->input->post("lokasi_pekerjaan"),
				"produk_dalam_negeri" => $pdn,
				"usaha_kecil" => $usaha_kecil,
				"sumber_dana" => $this->input->post("sumber_dana"),
                    "pra_dipa" => $this->input->post("pra_dipa"),
				"nomor_renja" => $this->input->post("nomor_renja"),
				"kd_mak" => $kd_mak,
				"pagu_paket" => $this->input->post("pagu_paket"),
                    "cara_pengadaan" => $this->input->post("cara_pengadaan"),
                    "tipe_swakelola" => $tipe_swakelola,
				"jenis_belanja" => $this->input->post("jenis_belanja"),
				"jenis_pengadaan" => $this->input->post("jenis_pengadaan"),
				"metode_pemilihan" => $metode_pemilihan,
				"umumkan_paket" => $this->input->post("umumkan_paket"),
                    "pelaksanaan_pengadaan_awal" => $pelaksanaan_pengadaan_awal,
                    "pelaksanaan_pengadaan_akhir" => $pelaksanaan_pengadaan_akhir,
                    "pelaksanaan_kontrak_awal" => $pelaksanaan_kontrak_awal,
                    "pelaksanaan_kontrak_akhir" => $pelaksanaan_kontrak_akhir,
                    "pelaksanaan_pemanfaatan" => $pelaksanaan_pemanfaatan,
                    "pelaksanaan_pekerjaan_awal" => $pelaksanaan_pekerjaan_awal,
				"pelaksanaan_pekerjaan_akhir" => $pelaksanaan_pekerjaan_akhir,
			);
			$this->model->insertData($data);
			echo json_encode(array("status"=>TRUE));
		}
	}

	public function changeData($token){
		if ($this->session->userdata("auth_id") != "") {
			$result_rup = $this->model->getData($token);
			$data = array();
			foreach ($result_rup->result() as $rows_rup) {
				$result_ppk = $this->model->getDataUsers($rows_rup->id_user_ppk);
				foreach ($result_ppk->result() as $rows_ppk) {
					$result_program = $this->model->getDataProgramUnique($rows_rup->id_program);
					foreach ($result_program->result() as $rows_program) {
						$result_kegiatan = $this->model->getDataKegiatanUnique($rows_rup->id_kegiatan);
						foreach ($result_kegiatan->result() as $rows_kegiatan) {
							$result_ro = $this->model->getDataRincianObyekUniqueID($rows_rup->id_rincian_obyek);
							foreach ($result_ro->result() as $rows_ro) {
                                        $result_pagu = $this->model->getDataRincianObyekUnique($rows_ro->id);
                                        $pagu = intval($result_pagu[0][0]);
								
                                        $kd_mak = explode(".", $rows_rup->kd_mak);
                                        switch ($rows_rup->cara_pengadaan) {
                                             case '1':
                                                  $cara_pengadaan = "Penyedia";
                                             break;
                                             case '2':
                                                  $cara_pengadaan = "Swakelola";
                                             break;                                             
                                             default:
                                                  $cara_pengadaan = "Penyedia";
                                             break;
                                        }
								$data[] = array(
											$rows_rup->id,
											"[".$rows_program->kd_gabungan."] - ".$rows_program->keterangan_program,
											"[".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan,
											"[".$rows_ro->kd_rekening."] - ".$rows_ro->nama_rekening,
											$rows_ppk->nama,
											$rows_rup->nama_paket,
											$rows_rup->volume_pekerjaan,
											$rows_rup->jumlah_paket,
											$rows_rup->uraian_pekerjaan,
											$rows_rup->lokasi_pekerjaan,
											$rows_rup->produk_dalam_negeri,
											$rows_rup->usaha_kecil,
											$rows_rup->sumber_dana,
                                                       $rows_rup->pra_dipa,
											$rows_rup->nomor_renja,
											$kd_mak[0],
											$kd_mak[1],
											$kd_mak[2].".".$kd_mak[3].".".$kd_mak[4],
											$kd_mak[5],
											$kd_mak[6],
											$kd_mak[7].".".$kd_mak[8].".".$kd_mak[9].".".$kd_mak[10].".".$kd_mak[11],
											$rows_rup->pagu_paket,
                                                       $pagu,
                                                       $rows_rup->cara_pengadaan,
                                                       $cara_pengadaan,
                                                       $rows_rup->tipe_swakelola,
                                                       $rows_rup->jenis_belanja,
                                                       $rows_rup->jenis_pengadaan,
											$rows_rup->metode_pemilihan,
											$rows_rup->umumkan_paket,
											$rows_rup->pelaksanaan_pengadaan_awal,
											$rows_rup->pelaksanaan_pengadaan_akhir,
											$rows_rup->pelaksanaan_kontrak_awal,
											$rows_rup->pelaksanaan_kontrak_akhir,
                                                       $rows_rup->pelaksanaan_pemanfaatan,
                                                       $rows_rup->pelaksanaan_pekerjaan_awal,
											$rows_rup->pelaksanaan_pekerjaan_akhir
										);
							}
						}
					}
				}
			}
			echo json_encode($data);
		}
	}

     public function updateData(){
          if ($this->session->userdata("auth_id") != "") {
               if ($this->input->post("cara_pengadaan") == 1) {
                    $data = array(
                         "nama_paket" => $this->input->post("nama_paket"),
                         "volume_pekerjaan" => $this->input->post("volume_pekerjaan"),
                         "jumlah_paket" => $this->input->post("jumlah_paket"),
                         "uraian_pekerjaan" => $this->input->post("uraian_pekerjaan"),
                         "lokasi_pekerjaan" => $this->input->post("lokasi_pekerjaan"),
                         "produk_dalam_negeri" => $this->input->post("produk_dalam_negeri"),
                         "usaha_kecil" => $this->input->post("usaha_kecil"),
                         "sumber_dana" => $this->input->post("sumber_dana"),
                         "pra_dipa" => $this->input->post("pra_dipa"),
                         "nomor_renja" => $this->input->post("nomor_renja"),
                         "pagu_paket" => $this->input->post("pagu_paket"),
                         "jenis_belanja" => $this->input->post("jenis_belanja"),
                         "jenis_pengadaan" => $this->input->post("jenis_pengadaan"),
                         "metode_pemilihan" => $this->input->post("metode_pemilihan"),
                         "umumkan_paket" => $this->input->post("umumkan_paket"),
                         "pelaksanaan_pengadaan_awal" => $this->input->post("pelaksanaan_pengadaan_awal"),
                         "pelaksanaan_pengadaan_akhir" => $this->input->post("pelaksanaan_pengadaan_akhir"),
                         "pelaksanaan_kontrak_awal" => $this->input->post("pelaksanaan_kontrak_awal"),
                         "pelaksanaan_kontrak_akhir" => $this->input->post("pelaksanaan_kontrak_akhir"),
                         "pelaksanaan_pemanfaatan" => $this->input->post("pelaksanaan_pemanfaatan"),
                    );
               }
               if ($this->input->post("cara_pengadaan") == 2) {
                    $data = array(
                         "nama_paket" => $this->input->post("nama_paket"),
                         "volume_pekerjaan" => $this->input->post("volume_pekerjaan"),
                         "jumlah_paket" => $this->input->post("jumlah_paket"),
                         "uraian_pekerjaan" => $this->input->post("uraian_pekerjaan"),
                         "lokasi_pekerjaan" => $this->input->post("lokasi_pekerjaan"),
                         "sumber_dana" => $this->input->post("sumber_dana"),
                         "pra_dipa" => $this->input->post("pra_dipa"),
                         "nomor_renja" => $this->input->post("nomor_renja"),
                         "pagu_paket" => $this->input->post("pagu_paket"),
                         "tipe_swakelola" => $this->input->post("tipe_swakelola"),
                         "jenis_belanja" => $this->input->post("jenis_belanja"),
                         "jenis_pengadaan" => $this->input->post("jenis_pengadaan"),
                         "umumkan_paket" => $this->input->post("umumkan_paket"),
                         "pelaksanaan_pekerjaan_awal" => $this->input->post("pelaksanaan_pekerjaan_awal"),
                         "pelaksanaan_pekerjaan_akhir" => $this->input->post("pelaksanaan_pekerjaan_akhir")
                    );
               }
               $this->model->updateData($this->input->post("token"), $data);
               echo json_encode(array("status"=>TRUE));
          }
     }

     public function trashData($token){
          if ($this->session->userdata('auth_id') != "") {
               $this->model->deleteData($token);
               echo json_encode(array("status"=>TRUE));
          }
     }

     public function multiTrashData(){
          if ($this->session->userdata('auth_id') != "") {
               $token = $this->input->post('token_data');
               foreach ($token as $rows) {
                    $this->model->deleteData($rows);
               }
               echo json_encode(array("status"=>TRUE));
          }
     }
}