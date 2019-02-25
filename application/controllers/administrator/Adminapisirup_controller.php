<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminapisirup_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/apisirup_model', 'model');
	}

	public function index(){
		$this->load->view('pages/administrator/api-sirup/data');
	}

	public function program($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getProgram($tahun, $date, $dateEnd);
		$data = array();
		$boolean = [TRUE, FALSE];
		foreach ($result->result() as $row) {
			$data[] = array(
								"ID_PROGRAM"		=> ('2019'.$row->id_sirup)+0,
								"ID_SATKER" 		=> "14834",
								"NAMA_PROGRAM" 		=> $row->keterangan_program,
								"KODE_PROGRAM" 		=> $row->kd_gabungan,
								"PAGU" 				=> $row->jumlah+0,
								"IS_DELETED" 		=> $boolean[1],
								"CREATE_TIME" 		=> $row->updated+0,
								"LASTUPDATE_TIME" 	=> $row->updated+0
						);
		}
		echo json_encode($data);
	}

	public function kegiatan($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getKegiatan($tahun, $date, $dateEnd);
		$data = array();
		$boolean = [TRUE, FALSE];
		foreach ($result->result() as $row) {
			$data[] = 	array(
								"ID_PROGRAM"		=> ('2019'.$row->id_sirup_program)+0,
								"ID_KEGIATAN"		=> ('2019'.$row->id_sirup)+0,
								"ID_SATKER"			=> "14834",
								"NAMA_KEGIATAN"		=> $row->keterangan_kegiatan,
								"KODE_KEGIATAN"		=> $row->kd_gabungan,
								"PAGU"				=> $row->jumlah+0,
								"IS_DELETED"		=> $boolean[1],
								"CREATE_TIME"		=> $row->updated+0,
								"LASTUPDATE_TIME"	=> $row->updated+0
						);
		}
		echo json_encode($data);
	}

	public function objekAkun($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getObjekAkun($tahun, $date, $dateEnd);
		$data = array();
		$boolean = [TRUE, FALSE];
		foreach ($result->result() as $row) {
			$data[] = 	array(
								"ID_PROGRAM"		=> ('2019'.$row->id_sirup_program)+0,
								"ID_KEGIATAN"		=> ('2019'.$row->id_sirup_kegiatan)+0,
								"ID_OBJEK_AKUN"		=> ('2019'.$row->id_sirup)+0,
								"ID_SATKER"			=> "14834",
								"NAMA_OBJEK_AKUN"	=> $row->nama_rekening,
								"KODE_OBJEK_AKUN"	=> $row->kd_rekening,
								"PAGU"				=> $row->jumlah+0,
								"IS_DELETED"		=> $boolean[1],
								"CREATE_TIME"		=> $row->updated+0,
								"LASTUPDATE_TIME"	=> $row->updated+0
						);
		}
		echo json_encode($data);
	}

	public function rincianObjekAkun($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getRincianObjekAkun($tahun, $date, $dateEnd);
		$data = array();
		$boolean = [TRUE, FALSE];
		foreach ($result->result() as $row) {
			$data[] = 	array(
								"ID_PROGRAM"				=> ('2019'.$row->id_sirup_program)+0,
								"ID_KEGIATAN"				=> ('2019'.$row->id_sirup_kegiatan)+0,
								"ID_OBJEK_AKUN"				=> ('2019'.$row->id_sirup_ro)+0,
								"ID_RINCI_OBJEK_AKUN"		=> ('2019'.$row->id)+0,
								"ID_SATKER"					=> "14834",
								"NAMA_RINCI_OBJEK_AKUN"		=> $row->nama_paket,
								"KODE_RINCI_OBJEK_AKUN"		=> $row->kd_rekening,
								"PAGU"						=> $row->pagu_paket+0,
								"IS_DELETED"				=> $boolean[1],
								"CREATE_TIME"				=> $row->updated+0,
								"LASTUPDATE_TIME"			=> $row->updated+0
						);
		}
		echo json_encode($data);
	}

	public function penyedia($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getPenyedia($tahun, $date, $dateEnd);
		$data = array();
		$boolean = [TRUE, FALSE];
		$sumber_dana = ["-", 1, 6, 2, 5, 7, 8, 10, 9, 3, 4, 11];
		$metode_pengadaan = ["-", 9, 13, 14, 8, 7, 15];
		foreach ($result->result() as $row) {

			// ======= Pejabat Pembuat Komitmen ======= //
			$result_ppk = $this->model->getPPKByID($row->id_user_ppk);
			if ($result_ppk->num_rows() > 0) {
				foreach ($result_ppk->result() as $rows_ppk) {
					$ppk = $rows_ppk->id;
				}
			}
			else{
				$ppk = NULL;	
			}

			$data[] = 	array(
								"ID_RUP"					=> $row->id+0,
							    "ID_SWAKELOLA"				=> NULL,
							    "ID_SATKER"					=> 14834,
							    "NAMA_PAKET"				=> $row->nama_paket,
							    "LIST_LOKASI_PEKERJAAN"		=> [
							        [
							            "ID_PROVINSI"		=> 15,
							            "ID_KABUPATEN"		=> 14834,
							            "DETIL_LOKASI"		=> $row->lokasi_pekerjaan,
							        ]
							    ],
							    "VOLUME"					=> $row->volume_pekerjaan,
							    "URAIAN_PEKERJAAN"			=> $row->uraian_pekerjaan,
							    "SPESIFIKASI"				=> "-",
							    "PDN"						=> $boolean[$row->produk_dalam_negeri+0],
							    "UMUK"						=> $boolean[$row->usaha_kecil+0],
							    "PRADIPA"					=> $boolean[$row->pra_dipa+0],
							    "NO_RENJA"					=> $row->nomor_renja,
							    "TOTAL_PAGU"				=> $row->pagu_paket+0,
							    "LIST_PAKET_ANGGARAN"		=> [
							    	[
								        "TAHUN_ANGGARAN"		=> $row->tahun+0,
								        "SUMBER_DANA"			=> $sumber_dana[$row->sumber_dana+0],
								        "ASAL_DANA"				=> "D196",
								        "ASAL_DANA_SATKER"		=> $row->kd_skpd,
								        "MAK"					=> $row->kd_mak,
								        "pagu"					=> $row->pagu_paket+0,
								        "ID_KEGIATAN"			=> ('2019'.$row->id_sirup_kegiatan+0),
								        "ID_RINCI_OBJEK_AKUN"	=> ('2019'.$row->id_sirup_ro+0)
							    	]
							    ],
							    "IZIN_TAHUN_JAMAK"			=> NULL,
							    "LIST_PAKET_JENIS_PENGADAAN"=> [
							    	[
							    		"JENIS_ID"			=> $row->jenis_pengadaan+0,
							    		"JUMLAH_PAGU"		=> $row->pagu_paket+0
							    	]
							    ],
							    "METODE_PENGADAAN"			=> $metode_pengadaan[$row->metode_pemilihan+0],
							    "TANGGAL_KEBUTUHAN"			=> $row->pelaksanaan_pemanfaatan+0,
							    "BULAN_PEKERJAAN_AKHIR"		=> $row->pelaksanaan_kontrak_akhir+0,
							    "BULAN_PEKERJAAN_MULAI"		=> $row->pelaksanaan_kontrak_awal+0,
							    "BULAN_PEMILIHAN_AKHIR"		=> $row->pelaksanaan_pengadaan_akhir+0,
							    "BULAN_PEMILIHAN_MULAI"		=> $row->pelaksanaan_pengadaan_awal+0,
							    "CREATE_TIME"				=> $row->updated+0,
							    "LASTUPDATE_TIME"			=> $row->updated+0,
							    "AKTIF"						=> $boolean[0],
							    "UMUMKAN"					=> $boolean[$row->umumkan_paket],
							    "IS_FINAL"					=> $boolean[0],
							    "ID_PPK"					=> $ppk,
							    "IS_DELETED"				=> $boolean[1]
						);
		}
		echo json_encode($data);
	}

	public function swakelola($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getSwakelola($tahun, $date, $dateEnd);
		$data = array();
		$boolean = [TRUE, FALSE];
		$sumber_dana = ["-", 1, 6, 2, 5, 7, 8, 10, 9, 3, 4, 11];
		$metode_pengadaan = ["-", 9, 13, 14, 8, 7, 15];
		foreach ($result->result() as $row) {

			// ======= Satuan Kerja ======= //
			$result_satker = $this->model->getSKPD($row->id_skpd_swakelola+0);
			if ($result_satker->num_rows() > 0) {
				foreach ($result_satker->result() as $rows_satker) {
					$klpd_satker = 'D196';
					$nama_satker = $rows_satker->nama_skpd;
				}
			}
			else{
				$klpd_satker = NULL;
				$nama_satker = NULL;
			}


			// ======= Pejabat Pembuat Komitmen ======= //
			$result_ppk = $this->model->getPPKByID($row->id_user_ppk);
			if ($result_ppk->num_rows() > 0) {
				foreach ($result_ppk->result() as $rows_ppk) {
					$ppk = $rows_ppk->id;
				}
			}
			else{
				$ppk = NULL;	
			}

			$data[] = 	array(
								"ID_RUP"					=> $row->id+0,
							    "ID_SATKER"					=> 14834,
							    "TIPE_SWAKELOLA"			=> $row->tipe_swakelola+0,
							    "NAMA_KLPD_LAIN"			=> $klpd_satker,
							    "NAMA_SATKER_LAIN"			=> $nama_satker,
							    "NAMA_PAKET"				=> $row->nama_paket,
							    "LIST_LOKASI_PEKERJAAN"		=> [
							        [
							            "ID_PROVINSI"		=> 15,
							            "ID_KABUPATEN"		=> 14834,
							            "DETIL_LOKASI"		=> $row->lokasi_pekerjaan,
							        ],
							     ],
							    "VOLUME"					=> $row->volume_pekerjaan,
							    "URAIAN_PEKERJAAN"			=> $row->uraian_pekerjaan,
							    "TOTAL_PAGU"				=> $row->pagu_paket+0,
							    "LIST_PAKET_ANGGARAN"		=> [
							      	[
								        "TAHUN_ANGGARAN"		=> $row->tahun+0,
								        "SUMBER_DANA"			=> $sumber_dana[$row->sumber_dana+0],
								        "ASAL_DANA"				=> "D196",
								        "ASAL_DANA_SATKER"		=> $row->kd_skpd,
								        "MAK"					=> $row->kd_mak,
								        "pagu"					=> $row->pagu_paket+0,
								        "ID_KEGIATAN"			=> ('2019'.$row->id_sirup_kegiatan+0),
								        "ID_RINCI_OBJEK_AKUN"	=> ('2019'.$row->id_sirup_ro+0)
							      	]
							    ],
							    "BULAN_PEKERJAAN_MULAI"		=> $row->pelaksanaan_pekerjaan_awal,
							    "BULAN_PEKERJAAN_AKHIR"		=> $row->pelaksanaan_pekerjaan_akhir,
							    "CREATE_TIME"				=> $row->updated+0,
							    "LASTUPDATE_TIME"			=> $row->updated+0,
							    "AKTIF"						=> $boolean[0],
							    "UMUMKAN"					=> $boolean[$row->umumkan_paket+0],
							    "IS_FINAL"					=> $boolean[0],
							    "ID_PPK"					=> $ppk,
							    "IS_DELETED"				=> $boolean[1],
							    "ID_KEGIATAN"				=> $row->id_sirup_kegiatan+0
						);
		}
		echo json_encode($data);
	}

	public function ppk($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getPPK($tahun, $date, $dateEnd);
		$data = array();
		$boolean = [TRUE, FALSE];
		foreach ($result->result() as $row) {
			$data[] = 	array(
								"ID"				=> $row->id,
						        "NAMA"				=> $row->nama,
						        "JABATAN"			=> $row->jabatan,
						        "ALAMAT"			=> $row->alamat,
						        "STATUS_PENGGUNA"	=> 1,
						        "NIP"				=> $row->nip+0,
						        "GOLONGAN"			=> $row->golongan,
						        "NO_TELEPON"		=> $row->telepon+0,
						        "EMAIL"				=> $row->email,
						        "NO_SK"				=> $row->no_sk,
						        "CREATED_TIME"		=> $row->updated+0,
						        "LASTUPDATE_TIME"	=> $row->updated+0,
						        "ID_SATKER"			=> 14834,
						        "IS_DELETED"		=> $boolean[1]
						);
		}
		echo json_encode($data);
	}

	public function strukturAnggaran($tahun, $date, $dateEnd = NULL){
		$result = $this->model->getStrukturAnggaran($tahun, $date, $dateEnd);
		$data = array();
		foreach ($result->result() as $row) {
			$btl = intval($row->btl1)+intval($row->btl2);
			$bl = intval($row->bl1)+intval($row->bl2)+intval($row->bl3);
			$btl_pegawai = intval($row->btl1);
			$btl_non_pegawai = intval($row->btl2);
			$bl_pegawai = intval($row->bl1);
			$bl_non_pegawai = intval($row->bl2)+intval($row->bl3);
			
			$data[] = array(
										"ID"													=> $row->id_sirup,
						        "BELANJA_LANGSUNG_PEGAWAI"								=> $bl_pegawai,
						        "BELANJA_LANGSUNG_BUKAN_PEGAWAI"						=> $bl_non_pegawai,

						        "BELANJA_LANGSUNG_BUKAN_PEGAWAI_BARANGJASA"				=> 10000000,
						        "BELANJA_LANGSUNG_BUKAN_PEGAWAI_MODAL"					=> 10000000,
						        
						        "BELANJA_TIDAK_LANGSUNG_PEGAWAI"						=> $btl_pegawai,
						        "BELANJA_TIDAK_LANGSUNG_BUKAN_PEGAWAI"					=> $btl_non_pegawai,
						        
						        "BELANJA_TIDAK_LANGSUNG_BUKAN_PEGAWAI_BUKAN_PENGADAAN"	=> 10000000,
						        "BELANJA_TIDAK_LANGSUNG_BUKAN_PEGAWAI_PENGADAAN"		=> 10000000,
						        
						        "ID_SATKER"												=> 14834,
						        "ID_KLDI"												=> 'D141'
						);
		}
		echo json_encode($data);
	}
}