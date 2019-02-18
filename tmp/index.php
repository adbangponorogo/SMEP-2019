<?php
	ini_set('date.timezone', 'Asia/Jakarta'); //25-05-2010 23:09:43
	ini_set('max_execution_time', 1800); //1800 seconds = 30 minutes
	
	error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

	define('DB_HOST',			'localhost');
	define('DB_USER',			'root');
	define('DB_PASSWORD',	'');
	define('DBSMEP_NAMA',	'db_ponorogo_2018');
	
	function smep_buka() {
		mysql_select_db(DBSMEP_NAMA, mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) or die (mysql_error());
	}
	
	$teppa_jenis = array(
		'Barang'=>1,
		'Pekerjaan Konstruksi'=>2,
		'Jasa Konsultansi'=>3,
		'Jasa Lainnya'=>4,
		);
		
	$teppa_jenis = array(
		'Barang'=>1,
		'Pekerjaan Konstruksi'=>2,
		'Jasa Konsultansi'=>3,
		'Jasa Lainnya'=>4,
		);
		
	$teppa_metode = array(
		'lu'=>1,		//Lelang Umum
		'ls'=>2,		//Lelang Sederhana
		'lt'=>3,		//Lelang Terbatas
		'su'=>4,		//Seleksi Umum
		'ss'=>5,		//Seleksi Sederhana
		'pml'=>6,		//Pemilihan Langsung
		'pnl'=>7,		//Penunjukan Langsung
		'pgl'=>8,		//Pengadaan Langsung
		'ep'=>9,		//E-Purchasing
		'syb'=>10,	//Sayembara
		'ks'=>11,		//Kontes
		'lc'=>12		//Lelang Cepat
		);
		
	$id_satker = array(
		'4.1.3.3',	//Bagian Administrasi Pemerintahan Umum
		'4.1.3.5',	//Bagian Administrasi Kesejahteraan Rakyat dan Kemasyarakatan
		'4.1.3.7',	//Bagian Hukum
		'4.1.3.2',	//Bagian Administrasi Perekonomian
		'4.1.3.4',	//Bagian Administrasi Pembangunan
		'4.1.3.6',	//Bagian Administrasi Sumber Daya Alam
		'4.1.3.10',	//Bagian Layanan Pengadaan
		'4.1.3.1',	//Bagian Umum
		'4.1.3.8',	//Bagian Organisasi
		'4.1.3.9',	//Bagian Humas dan Protokol
		'4.1.4.1',	//Sekretariat DPRD
		'4.2.1.1',	//Inspektorat
		'1.1.1.1',	//Dinas Pendidikan - SKPD
		'3.2.1.1',	//Dinas Pariwisata
		'2.13.1.1', //Dinas Pemuda dan Olah Raga
		'1.2.1.1',	//Dinas Kesehatan
		'1.2.1.3',	//Puskesmas Ponorogo Utara
		'1.2.1.4',	//Puskesmas Ponorogo Selatan
		'1.2.1.9',	//Puskesmas Babadan
		'1.2.1.10',	//Puskesmas Sukosari
		'1.2.1.22',	//Puskesmas Ngrayun
		'1.2.1.19',	//Puskesmas Slahung
		'1.2.1.20',	//Puskesmas Nailan
		'1.2.1.21',	//Puskesmas Bungkal
		'1.2.1.23',	//Puskesmas Sambit
		'1.2.1.24',	//Puskesmas Wringinanom
		'1.2.1.25',	//Puskesmas Sawoo
		'1.2.1.26',	//Puskesmas Bondrang
		'1.2.1.31',	//Puskesmas Sooko
		'1.2.1.29',	//Puskesmas Pulung
		'1.2.1.30',	//Puskesmas Kesugihan
		'1.2.1.27',	//Puskesmas Mlarak
		'1.2.1.5',	//Puskesmas Siman
		'1.2.1.6',	//Puskesmas Ronowijayan
		'1.2.1.28',	//Puskesmas Jetis
		'1.2.1.18',	//Puskesmas Balong
		'1.2.1.12',	//Puskesmas Kauman
		'1.2.1.13',	//Puskesmas Ngrandu
		'1.2.1.14',	//Puskesmas Badegan
		'1.2.1.16',	//Puskesmas Sampung
		'1.2.1.17',	//Puskesmas Kunti
		'1.2.1.11',	//Puskesmas Sukorejo
		'1.2.1.7',	//Puskesmas Jenangan
		'1.2.1.8',	//Puskesmas Setono
		'1.2.1.32',	//Puskesmas Ngebel
		'1.2.1.15',	//Puskesmas Jambon
		'1.2.1.33',	//Puskesmas Pudak
		'1.2.2.1',	//RSUD Dr. Harjono - SKPD
		'1.2.2.2',	//RSUD Dr. Harjono - BLUD
		'1.6.1.1',	//Dinas Sosial Pemberdayaan Perempuan dan Perlindungan Anak
		'2.8.1.1',	//Dinas Pengendalian Penduduk dan Keluarga Berencana
		'2.6.1.1',	//Dinas Kependudukan dan Pencatatan Sipil
		'2.7.1.1',	//Dinas Pemberdayaan Masyarakat dan Desa
		'1.5.2.1',	//Satuan Polisi Pamong Praja
		'2.12.1.1',	//Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (PTSP)
		'2.11.1.1',	//Dinas Perdagangan Koperasi dan Usaha Mikro
		'2.1.1.1',	//Dinas Tenaga Kerja
		'2.10.1.1',	//Dinas Komunikasi Informatika dan Statistik
		'1.3.1.1',	//Dinas Pekerjaan Umum dan Penataan Ruang
		'1.4.1.1',	//Dinas Perumahan dan Kawasan Permukiman
		'2.9.1.1',	//Dinas Perhubungan
		'2.3.1.1',	//Dinas Ketahanan Pangan
		'3.3.1.1',	//Dinas Pertanian dan Perikanan
		'2.5.1.1',	//Dinas Lingkungan Hidup
		'2.17.1.1',	//Dinas Perpustakaan dan Kearsipan
		'4.4.5.1',	//Badan Pendapatan Pengelolaan Keuangan  dan Aset Daerah (BPPKAD) - SKPD
		'4.5.1.1',	//Badan Kepegawaian Pendidikan dan Pelatihan Daerah
		'4.3.1.1',	//Badan Perencanaan Pembangunan Daerah Penelitian dan Pengembangan
		'1.5.1.1',	//Badan Kesatuan Bangsa dan Politik
		'1.5.3.1',	//Badan Penanggulangan Bencana Daerah
		'4.1.22.1',	//Kecamatan Ponorogo
		'4.1.9.1',	//Kecamatan Jenangan
		'4.1.11.1',	//Kecamatan Babadan
		'4.1.24.1',	//Kecamatan Siman
		'4.1.17.1',	//Kecamatan Kauman
		'4.1.29.1',	//Kecamatan Sukorejo
		'4.1.25.1',	//Kecamatan Sampung
		'4.1.20.1',	//Kecamatan Badegan
		'4.1.26.1',	//Kecamatan Jambon
		'4.1.15.1',	//Kecamatan Balong
		'4.1.23.1',	//Kecamatan Slahung
		'4.1.28.1',	//Kecamatan Bungkal
		'4.1.10.1',	//Kecamatan Ngrayun
		'4.1.16.1',	//Kecamatan Sambit
		'4.1.14.1',	//Kecamatan Sawoo
		'4.1.13.1',	//Kecamatan Mlarak
		'4.1.12.1',	//Kecamatan Jetis
		'4.1.21.1',	//Kecamatan Pulung
		'4.1.18.1',	//Kecamatan Ngebel
		'4.1.19.1',	//Kecamatan Sooko
		'4.1.27.1'	//Kecamatan Pudak
	);

	class RUP
	{
		function RUP()
		{
		}
		function GetProgram($tahun=2018, $mulai=0, $selesai=0)
		{
			global $id_satker;
			
			$q="
				SELECT
					a.*,
					SUM(b.Pagu) pagu
				FROM ta_program a
				JOIN ta_pagu b ON
					a.kd_skpd=b.kd_skpd AND
					a.ID_Prog=b.Id_Prog AND
					a.Kd_Prog=b.Kd_Prog
				WHERE
					a.kd_skpd IN ( '".implode("','", $id_satker)."' )
				GROUP BY
					a.kd_skpd,
					a.kd_gab
					";//echo $q;exit;
			smep_buka();
			$rs = mysql_query($q);
			echo mysql_error();
			while($d = mysql_fetch_array($rs))
			{
				$list[]=array(
					'ID_PROGRAM'=>$d['ID_Prog'].$d['Kd_Prog']+0,
					'ID_SATKER'=>$d['kd_skpd'],
					'NAMA_PROGRAM'=>$d["Ket_Program"],
					'PAGU'=>$d["pagu"]+0,
					'IS_DELETED'=>false
				);
			}
			return json_encode($list, JSON_UNESCAPED_SLASHES);
		}
		function GetKegiatan($tahun=2018, $mulai=0, $selesai=0)
		{
			global $id_satker;
			
			$q="
				SELECT
					a.*,
					SUM(b.Pagu) pagu
				FROM ta_kegiatan a
				JOIN ta_pagu b ON
					a.kd_skpd=b.kd_skpd AND
					a.ID_Prog=b.Id_Prog AND
					a.Kd_Prog=b.Kd_Prog AND
					a.Kd_Keg=b.Kd_Keg
				WHERE
					a.kd_skpd IN ( '".implode("','", $id_satker)."' )
				GROUP BY
					a.kd_skpd,
					a.kd_gab
					";
			smep_buka();
			$rs = mysql_query($q);
			echo mysql_error();
			while($d = mysql_fetch_array($rs))
			{
				$list[]=array(
					'ID_PROGRAM'=>$d['ID_Prog'].$d['Kd_Prog']+0,
					'ID_KEGIATAN'=>$d['ID_Prog'].$d['Kd_Prog'].$d['Kd_Keg']+0,
					'ID_SATKER'=>$d['kd_skpd'],
					'NAMA_KEGIATAN'=>$d["ket_kegiatan"],
					'PAGU'=>$d["pagu"]+0,
					'IS_DELETED'=>false
				);
			}
			return json_encode($list, JSON_UNESCAPED_SLASHES);
		}
		function GetPenyedia($tahun=2018, $mulai=0, $selesai=0, $id=0)
		{
			global $teppa_jenis, $teppa_metode, $id_satker;
			
			if ($mulai>0){
				$filter1="AND UNIX_TIMESTAMP(updated) >= '".$mulai."'";
			}
			if ($selesai>0){
				$filter2="AND UNIX_TIMESTAMP(updated) <= '".$selesai."'";
			}
			if ($id){
				$id = substr_replace($id,'',0,4);
				$filter3="AND Id_RUP = '".$id."'";
			}

			$q="
				SELECT
					Tahun,
					Id_RUP,
					ID_Prog,
					Kd_Prog,
					Kd_Keg,
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					kd_skpd,
					Nm_Paket,
					Lokasi,
					teppa_jns,
					Volume,
					Satuan_Volume,
					Keterangan,
					Langsung,
					Sumber_Dana,
					teppa_metode,
					kd_prokeg,
					UNIX_TIMESTAMP(Mulai) as Mulai,
					UNIX_TIMESTAMP(Selesai) as Selesai,
					UNIX_TIMESTAMP(Mulai_1) as Mulai_1,
					UNIX_TIMESTAMP(Selesai_1) as Selesai_1,
					UNIX_TIMESTAMP(created) as created,
					UNIX_TIMESTAMP(updated) as updated
				FROM ta_rup
				WHERE
					Kd_Pengadaan='PB' AND publish='t'
					".$filter1."
					".$filter2."
					".$filter3."
					";
			smep_buka();
			$rs = mysql_query($q);
			echo mysql_error();
			while($d = mysql_fetch_array($rs))
			{
				$ket = $d["Keterangan"];
				if (strlen($ket)>255)
					$ket = substr($ket,0,255).' ... ';
				elseif ($ket!='')
					$ket = $ket.' ... ';
				$ket .= '#IDSMEP:'.$d['Id_RUP'];
				
				$list[]=array(
					'ID_RUP'=>$d['Tahun'].$d['Id_RUP']+0,
					'ID_SATKER'=>$d['kd_skpd'],
					'ID_PROGRAM'=>$d['ID_Prog'].$d['Kd_Prog']+0,
					'ID_KEGIATAN'=>$d['ID_Prog'].$d['Kd_Prog'].$d['Kd_Keg']+0,
					'NAMA_PAKET'=>$d["Nm_Paket"],
					'LOKASI_PEKERJAAN'=>array(14834),
					'DETAIL_LOKASI_PEKERJAAN'=>$d["Lokasi"],
					'JENIS_PENGADAAN'=>array($teppa_jenis[$d['teppa_jns']]),
					'VOLUME'=>$d['Volume'].' '.$d['Satuan_Volume'],
					'URAIAN_PEKERJAAN'=>$ket,
					'SPESIFIKASI'=>$d["Nm_Paket"],
					'TKDN'=>true,
					'PRADIPA'=>false,
					'TOTAL_PAGU'=>$d["Langsung"]+0,
					'LIST_PAKET_ANGGARAN'=>array(array(
						'SUMBER_DANA'=>$d['Sumber_Dana']==2? 1 : ($d['Sumber_Dana']==1? 2 : $d['Sumber_Dana']),
						'ASAL_DANA'=>'D196',
						'ASAL_DANA_SATKER'=>$d['kd_skpd'],
						'MAK'=>substr($d['ID_Prog'],0,1).'.'.substr($d['ID_Prog'],1,2).'.'.$d['kd_skpd'].'.'.sprintf("%02d", $d['Kd_Prog']).'.'.sprintf("%03d", $d['Kd_Keg']),
						'PAGU'=>$d['Langsung']+0,
						'TAHUN_ANGGARAN'=>$d['Tahun']
						)),
					'METODE_PENGADAAN'=>$teppa_metode[$d['teppa_metode']],
					'BULAN_PEMILIHAN_MULAI'=>$d['Mulai']+0,
					'BULAN_PEMILIHAN_AKHIR'=>$d['Selesai']+0,
					'BULAN_PEKERJAAN_MULAI'=>$d['Mulai_1']+0,
					'BULAN_PEKERJAAN_AKHIR'=>$d['Selesai_1']+0,
					'CREATE_TIME'=>$d['created']+0,
					'LASTUPDATE_TIME'=>$d['updated']+0,
					'AKTIF'=>true,
					'UMUMKAN'=>true,
					'ID_SWAKELOLA'=>''
				);
			}
			return json_encode($list, JSON_UNESCAPED_SLASHES);
		}
		function GetSwakelola($tahun=2018, $mulai=0, $selesai=0, $id=0)
		{
			global $teppa_jenis, $teppa_metode, $id_satker;
			
			if ($mulai){
				$filter1="AND UNIX_TIMESTAMP(updated) >= '".$mulai."'";
			}
			if ($selesai){
				$filter2="AND UNIX_TIMESTAMP(updated) <= '".$selesai."'";
			}
			if ($id){
				$id = substr_replace($id,'',0,4);
				$filter3="AND Id_RUP = '".$id."'";
			}
			
			$q="
				SELECT
					Tahun,
					Id_RUP,
					ID_Prog,
					Kd_Prog,
					Kd_Keg,
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					kd_skpd,
					Nm_Paket,
					Lokasi,
					teppa_jns,
					Volume,
					Satuan_Volume,
					Keterangan,
					Langsung,
					Sumber_Dana,
					teppa_metode,
					kd_prokeg,
					UNIX_TIMESTAMP(Mulai) as Mulai,
					UNIX_TIMESTAMP(Selesai) as Selesai,
					UNIX_TIMESTAMP(Mulai_1) as Mulai_1,
					UNIX_TIMESTAMP(Selesai_1) as Selesai_1,
					UNIX_TIMESTAMP(created) as created,
					UNIX_TIMESTAMP(updated) as updated
				FROM ta_rup
				WHERE
					Kd_Pengadaan='SW' AND publish='t'
					".$filter1."
					".$filter2."
					".$filter3."
					";
			smep_buka();
			$rs = mysql_query($q);
			echo mysql_error();
			while($d = mysql_fetch_array($rs))
			{
				$ket = $d["Keterangan"];
				if (strlen($ket)>255)
					$ket = substr($ket,0,255).' ... ';
				elseif ($ket!='')
					$ket = $ket.' ... ';
				$ket .= '#IDSMEP:'.$d['Id_RUP'];

				$list[]=array(
					'ID_RUP'=>$d['Tahun'].$d['Id_RUP']+0,
					'ID_SATKER'=>$d['kd_skpd'],
					'ID_PROGRAM'=>$d['ID_Prog'].$d['Kd_Prog']+0,
					'ID_KEGIATAN'=>$d['ID_Prog'].$d['Kd_Prog'].$d['Kd_Keg']+0,
					'NAMA_PAKET'=>$d["Nm_Paket"],
					'LOKASI_PEKERJAAN'=>array(14834),
					'DETAIL_LOKASI_PEKERJAAN'=>$d["Lokasi"],
					'VOLUME'=>$d['Volume'].' '.$d['Satuan_Volume'],
					'URAIAN_PEKERJAAN'=>$ket,
					'TOTAL_PAGU'=>$d["Langsung"]+0,
					'LIST_PAKET_ANGGARAN'=>array(array(
						'SUMBER_DANA'=>$d['Sumber_Dana']==2? 1 : ($d['Sumber_Dana']==1? 2 : $d['Sumber_Dana']),
						'ASAL_DANA'=>'D196',
						'ASAL_DANA_SATKER'=>$d['kd_skpd'],
						'MAK'=>substr($d['ID_Prog'],0,1).'.'.substr($d['ID_Prog'],1,2).'.'.$d['kd_skpd'].'.'.sprintf("%02d", $d['Kd_Prog']).'.'.sprintf("%03d", $d['Kd_Keg']),
						'PAGU'=>$d['Langsung']+0,
						)),
					'BULAN_PEMILIHAN_MULAI'=>$d['Mulai']+0,
					'BULAN_PEMILIHAN_AKHIR'=>$d['Selesai']+0,
					'BULAN_PEKERJAAN_MULAI'=>$d['Mulai_1']+0,
					'BULAN_PEKERJAAN_AKHIR'=>$d['Selesai_1']+0,
					'CREATE_TIME'=>$d['created']+0,
					'LASTUPDATE_TIME'=>$d['updated']+0,
					'AKTIF'=>true,
					'UMUMKAN'=>true,
				);
			}
			return json_encode($list, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );
		}
	}
	$tahun = mysql_real_escape_string($_REQUEST['y']);
	$tahun = $tahun? $tahun : date('Y');
	
	$mulai = mysql_real_escape_string($_GET['date']);
	$selesai = mysql_real_escape_string($_GET['dateEnd']);
	$id = mysql_real_escape_string($_GET['id']);
	
	$rup = new RUP();
	
	if ($_GET['m']=='GetProgram')
		echo $rup->GetProgram($tahun, $mulai, $selesai);
	elseif ($_GET['m']=='GetKegiatan')
		echo $rup->GetKegiatan($tahun, $mulai, $selesai);
	elseif ($_GET['m']=='GetPenyedia')
		echo $rup->GetPenyedia($tahun, $mulai, $selesai, $id);
	elseif ($_GET['m']=='GetSwakelola')
		echo $rup->GetSwakelola($tahun, $mulai, $selesai, $id);
	else
	{
		$prog	= 'GetProgram';
		$keg	= 'GetKegiatan';
		$py		= 'GetPenyedia';
		$sw		= 'GetSwakelola';
		$host	= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		echo 'Incorrect URL! Just click
		<a href="?m='.$prog.'" target="_blank">'.$host.'?m='.$prog.'</a> or
		<a href="?m='.$keg.'" target="_blank">'.$host.'?m='.$keg.'</a> or
		<a href="?m='.$py.'" target="_blank">'.$host.'?m='.$py.'</a> or
		<a href="?m='.$sw.'" target="_blank">'.$host.'?m='.$sw.'</a>'
		;
	}
?>