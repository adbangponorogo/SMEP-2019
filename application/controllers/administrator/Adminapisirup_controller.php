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
										"ID"											=> $row->id_sirup,
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


	public function APIcheckStatus(){
		// Style
		echo "<style type='text/css'>b{font-family:Calibri;}</style>";
		
		// **
		// **
		//   Struktur Anggaran
		// **
		// **
		echo "<b>1) Struktur Anggaran PEMDA</b> <br>";
		echo "{<br>";
			$result_struktur_anggaran_ID = $this->model->checkAPIStrukturAnggaran_ID();
			if ($result_struktur_anggaran_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_struktur_anggaran_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			else{
				echo "- ID : Status => OK <br>";
			}
		echo "}<br>";
		




		// **
		// **
		//   Swakelola
		// **
		// **
		echo "<b>2) Swakelola</b><br>";
		echo "{<br>";
			$result_swakelola_ID = $this->model->checkAPISwakelola_ID();
			if ($result_swakelola_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_swakelola_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_swakelola_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
			}

			$result_swakelola_idkegiatan = $this->model->checkAPISwakelola_IDKegiatan();
			if ($result_swakelola_idkegiatan->num_rows() > 0) {
				echo "- ID Kegiatan : Status => Ada ".$result_swakelola_idkegiatan->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_swakelola_ID->num_rows() <= 0) {
				echo "- ID Kegiatan : Status => OK <br>";
			}

			$result_swakelola_ppk = $this->model->checkAPISwakelola_PPK();
			if ($result_swakelola_ppk->num_rows() > 0) {
				echo "- ID PPK : Status => Ada ".$result_swakelola_ppk->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_swakelola_ID->num_rows() <= 0) {
				echo "- ID PPK : Status => OK <br>";
			}
		echo "}<br>";





		// **
		// **
		//   Penyedia
		// **
		// **
		echo "<b>3) Penyedia</b><br>";
		echo "{<br>";
			$result_penyedia_ID = $this->model->checkAPIPenyedia_ID();
			if ($result_penyedia_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_penyedia_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_penyedia_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
			}

			$result_penyedia_idkegiatan = $this->model->checkAPIPenyedia_PPK();
			if ($result_penyedia_idkegiatan->num_rows() > 0) {
				echo "- ID Kegiatan : Status => Ada ".$result_penyedia_idkegiatan->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_penyedia_idkegiatan->num_rows() <= 0) {
				echo "- ID Kegiatan : Status => OK <br>";
			}

			$result_penyedia_ppk = $this->model->checkAPIPenyedia_IDKegiatan();
			if ($result_penyedia_ppk->num_rows() > 0) {
				echo "- ID PPK : Status => Ada ".$result_penyedia_ppk->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_penyedia_ppk->num_rows() <= 0) {
				echo "- ID PPK : Status => OK <br>";
			}
		echo "}<br>";





		// **
		// **
		//   Rincian Obyek Akun
		// **
		// **
		echo "<b>4) Rincian Obyek Akun</b><br>";
		echo "{<br>";
			$result_RincianObjekAkun_ID = $this->model->checkAPIRincianObyekAkun_ID();
			if ($result_RincianObjekAkun_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_RincianObjekAkun_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_RincianObjekAkun_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
			}

			$result_RincianObjekAkun_IDSKPD = $this->model->checkAPIRincianObyekAkun_IDSKPD();
			if ($result_RincianObjekAkun_IDSKPD->num_rows() > 0) {
				echo "- ID SKPD : Status => Ada ".$result_RincianObjekAkun_IDSKPD->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_RincianObjekAkun_IDSKPD->num_rows() <= 0) {
				echo "- ID SKPD : Status => OK <br>";
			}

			$result_RincianObjekAkun_IDProgram = $this->model->checkAPIRincianObyekAkun_IDProgram();
			if ($result_RincianObjekAkun_IDProgram->num_rows() > 0) {
				echo "- ID Program : Status => Ada ".$result_RincianObjekAkun_IDProgram->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_RincianObjekAkun_IDProgram->num_rows() <= 0) {
				echo "- ID Program : Status => OK <br>";
			}

			$result_RincianObjekAkun_IDKegiatan = $this->model->checkAPIRincianObyekAkun_IDKegiatan();
			if ($result_RincianObjekAkun_IDKegiatan->num_rows() > 0) {
				echo "- ID Kegiatan : Status => Ada ".$result_RincianObjekAkun_IDKegiatan->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_RincianObjekAkun_IDKegiatan->num_rows() <= 0) {
				echo "- ID Kegiatan : Status => OK <br>";
			}

			$result_RincianObjekAkun_IDRO = $this->model->checkAPIRincianObyekAkun_IDRincianObyek();
			if ($result_RincianObjekAkun_IDRO->num_rows() > 0) {
				echo "- ID RO : Status => Ada ".$result_RincianObjekAkun_IDRO->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_RincianObjekAkun_IDRO->num_rows() <= 0) {
				echo "- ID RO : Status => OK <br>";
			}

			$result_RincianObjekAkun_IDPPK = $this->model->checkAPIPenyedia_IDPPK();
			if ($result_RincianObjekAkun_IDPPK->num_rows() > 0) {
				echo "- ID PPK : Status => Ada ".$result_RincianObjekAkun_IDPPK->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_RincianObjekAkun_IDPPK->num_rows() <= 0) {
				echo "- ID PPK : Status => OK <br>";
			}
		echo "}<br>";





		// **
		// **
		//   Obyek Akun
		// **
		// **
		echo "<b>5) Obyek Akun</b><br>";
		echo "{<br>";
			$result_ObjekAkun_ID = $this->model->checkAPIObyekAkun_ID();
			if ($result_ObjekAkun_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_ObjekAkun_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_ObjekAkun_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
			}

			$result_ObjekAkun_idprogram = $this->model->checkAPIObyekAkun_IDProgram();
			if ($result_ObjekAkun_idprogram->num_rows() > 0) {
				echo "- ID Program : Status => Ada ".$result_ObjekAkun_idprogram->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_ObjekAkun_idprogram->num_rows() <= 0) {
				echo "- ID Program : Status => OK <br>";
			}

			$result_ObjekAkun_idkegiatan = $this->model->checkAPIObyekAkun_IDKegiatan();
			if ($result_ObjekAkun_idkegiatan->num_rows() > 0) {
				echo "- ID Kegiatan : Status => Ada ".$result_ObjekAkun_idkegiatan->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_ObjekAkun_idkegiatan->num_rows() <= 0) {
				echo "- ID Kegiatan : Status => OK <br>";
			}

			$result_ObjekAkun_skpd = $this->model->checkAPIObyekAkun_IDSKPD();
			if ($result_ObjekAkun_skpd->num_rows() > 0) {
				echo "- ID SKPD : Status => Ada ".$result_ObjekAkun_skpd->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_ObjekAkun_skpd->num_rows() <= 0) {
				echo "- ID SKPD : Status => OK <br>";
			}
		echo "}<br>";





		// **
		// **
		//   History Kaji Ulang
		// **
		// **
		echo "<b>6) History Kaji Ulang</b><br>";
		echo "{<br>";
			$result_HistoryRevisi_ID = $this->model->checkAPIHistoryKajiUlang_ID();
			if ($result_HistoryRevisi_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_HistoryRevisi_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_HistoryRevisi_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
			}

			$result_HistoryRevisi_IDAwal = $this->model->checkAPIHistoryKajiUlang_IDRUPAwal();
			if ($result_HistoryRevisi_IDAwal->num_rows() > 0) {
				echo "- ID RUP Awal: Status => Ada ".$result_HistoryRevisi_IDAwal->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_HistoryRevisi_IDAwal->num_rows() <= 0) {
				echo "- ID RUP Awal : Status => OK <br>";
			}

			$result_HistoryRevisi_IDSebelumnya = $this->model->checkAPIHistoryKajiUlang_IDRUPSebelumnya();
			if ($result_HistoryRevisi_IDSebelumnya->num_rows() > 0) {
				echo "- ID RUP Sebelumnya : Status => Ada ".$result_HistoryRevisi_IDSebelumnya->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_HistoryRevisi_IDSebelumnya->num_rows() <= 0) {
				echo "- ID RUP Sebelumnya : Status => OK <br>";
			}

			$result_HistoryRevisi_IDBaru = $this->model->checkAPIHistoryKajiUlang_IDRUPBaru();
			if ($result_HistoryRevisi_IDBaru->num_rows() > 0) {
				echo "- ID Baru : Status => Ada ".$result_HistoryRevisi_IDBaru->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_HistoryRevisi_IDBaru->num_rows() <= 0) {
				echo "- ID Baru : Status => OK <br>";
			}
		echo "}<br>";





		// **
		// **
		//   PPK
		// **
		// **
		echo "<b>7) PPK</b><br>";
		echo "{<br>";
			$result_PPK_ID = $this->model->checkAPIPPK_ID();
			if ($result_PPK_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_PPK_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_PPK_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
			}
		echo "}<br>";





		// **
		// **
		//   Program
		// **
		// **
		echo "<b>8) Program</b><br>";
		echo "{<br>";
			$result_Program_ID = $this->model->checkAPIProgram_ID();
			if ($result_Program_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_Program_ID->num_rows()." Data Yang Tidak Diketahui <br>";
				echo "- ID SKPD : Status => Ada ".$result_Program_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_Program_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
				echo "- ID SKPD : Status => OK <br>";
			}
		echo "}<br>";





		// **
		// **
		//   Kegiatan
		// **
		// **
		echo "<b>9) Kegiatan</b><br>";
		echo "{<br>";
			$result_Kegiatan_ID = $this->model->checkAPIKegiatan_ID();
			if ($result_Kegiatan_ID->num_rows() > 0) {
				echo "- ID : Status => Ada ".$result_Kegiatan_ID->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_Kegiatan_ID->num_rows() <= 0) {
				echo "- ID : Status => OK <br>";
			}

			$result_Kegiatan_IDProgram = $this->model->checkAPIKegiatan_IDProgram();
			if ($result_Kegiatan_IDProgram->num_rows() > 0) {
				echo "- ID Program : Status => Ada ".$result_Kegiatan_IDProgram->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_Kegiatan_IDProgram->num_rows() <= 0) {
				echo "- ID Program : Status => OK <br>";
			}

			$result_Kegiatan_IDSKPD = $this->model->checkAPIKegiatan_IDSKPD();
			if ($result_Kegiatan_IDSKPD->num_rows() > 0) {
				echo "- ID SKPD : Status => Ada ".$result_Kegiatan_IDSKPD->num_rows()." Data Yang Tidak Diketahui <br>";
			}
			if ($result_Kegiatan_IDSKPD->num_rows() <= 0) {
				echo "- ID SKPD : Status => OK <br>";
			}
		echo "}<br>";
		echo "}<br>";
	}


}