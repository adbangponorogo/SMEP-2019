<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| PHP OTHER HELPER
| -------------------------------------------------------------------------
	if ( !function_exists('periode') )
	{
		function periode($index)
		{
			$periode = array(
				'smt1'=>'Semester I',
				'smt2'=>'Semester II',
				'1'=>'Januari',
				'2'=>'Februari',
				'3'=>'Maret',
				'4'=>'April',
				'5'=>'Mei',
				'6'=>'Juni',
				'7'=>'Juli',
				'8'=>'Agustus',
				'9'=>'September',
				'10'=>'Oktober',
				'11'=>'November',
				'12'=>'Desember'
			);

			$rs = $periode[$index];
			if ($index >= 1)
				return 'Bulan '. $rs;
			else
				return $rs;

			return $periode[$index];
		}
	}
*/

	if ( !function_exists('getKolomSumberDana') )
	{
		function getKolomSumberDana($sumber_dana, $jenis='pagu') // $jenis = 'pagu' / 'real' / 'fisik'
		{
			if ($jenis == 'pagu') {
				switch ($sumber_dana) {
					case 'APBN':
						$klm_xl = 'D';
						break;
					case 'APBD PROV':
						$klm_xl = 'F';
						break;
					case 'DAK':
						$klm_xl = 'H';
						break;
					case 'DBHCHT':
						$klm_xl = 'I';
						break;
					default: //APBD KAB
						$klm_xl = 'G';
				}
			}
			elseif ($jenis == 'real') {
				switch ($sumber_dana) {
					case 'APBN':
						$klm_xl = 'J';
						break;
					case 'APBD PROV':
						$klm_xl = 'N';
						break;
					case 'DAK':
						$klm_xl = 'R';
						break;
					case 'DBHCHT':
						$klm_xl = 'T';
						break;
					default: //APBD KAB
						$klm_xl = 'P';
				}
			}
			else { // fisik
				switch ($sumber_dana) {
					case 'APBN':
						$klm_xl = 'K';
						break;
					case 'APBD PROV':
						$klm_xl = 'O';
						break;
					case 'DAK':
						$klm_xl = 'S';
						break;
					case 'DBHCHT':
						$klm_xl = 'U';
						break;
					default: //APBD KAB
						$klm_xl = 'Q';
				}
			}
			return $klm_xl;
		}
	}

	if ( !function_exists('sumber_dana') )
	{
		function sumber_dana($i)
		{
			$sumber_dana = array(
								'',
								'APBD',//1
								'APBDP',//6
								'APBN',//2
								'APBNP',//5
								'BLU',//7
								'BLUD',//8
								'BUMD',//10
								'BUMN',//9
								'PHLN',//3
								'PNBP',//4
								'LAINNYA'//11
								);
			return $sumber_dana[$i];
		}
	}

	if ( !function_exists('metode_pemilihan') )
	{
		function metode_pemilihan($i)
		{
			$metode_pemilihan = array(
									'',
									'e-Purchasing',//9
									'Tender',//13
									'Tender Cepat',//14
									'Pengadaan Langsung',//8
									'Penunjukkan Langsung',//7
									'Seleksi'//15
									);
			return ($metode_pemilihan[$i])? $metode_pemilihan[$i] : '-';
		}
	}

	if ( !function_exists('cara_pengadaan') )
	{
		function cara_pengadaan($i)
		{
			$cara_pengadaan = array(
									'',
									'Penyedia',
									'Swakelola'
									);
			return $cara_pengadaan[$i];
		}
	}

	if ( !function_exists('sanggah') )
	{
		function sanggah($i)
		{
			$i = empty($i)? 1 : $i;
			$sanggah = array(
									'',
									'-',
									'Sanggah',
									'Sanggah Banding',
									'Pengaduan'
									);
			return $sanggah[$i];
		}
	}

	if ( !function_exists('jenis_belanja') )
	{
		function jenis_belanja($i)
		{
			$jenis_belanja = array(
									'',
									'Belanja Pegawai',
									'Belanja Barang/Jasa',
									'Belanja Modal',
									'Belum Teridentifikasi',
									'Belanja Bunga Utang',
									'Belanja Subsidi',
									'Belanja Hibah',
									'Belanja Bantuan Sosial',
									'Belanja Lain-Lain'
									);
			return $jenis_belanja[$i];
		}
	}

	if ( !function_exists('jenis_pengadaan') )
	{
		function jenis_pengadaan($i)
		{
			$jenis_pengadaan = array(
									'',
									'Barang',//1
									'Pekerjaan Konstruksi',//2
									'Jasa Konsultansi',//3
									'Jasa Lainnya'//4
									);
			return $jenis_pengadaan[$i];
		}
	}

	if ( !function_exists('periode') )
	{
		function periode($smt)
		{
			if (!$smt) $smt = date('n')<7? 'smt1' : 'smt2'; // pilih semester otomatis

			$periode = array(
				'smt1'=>'Semester I',
				'smt2'=>'Semester II'
			);
			return $periode[$smt];
		}
	}

	if ( !function_exists('akhir_periode') )
	{
		function akhir_periode($smt)
		{
			if (!$smt) $smt = date('n')<7? 'smt1' : 'smt2'; // pilih semester otomatis

			$akhir_periode = array(
				'smt1'=>'30 Jun ',
				'smt2'=>'31 Des '
			);
			return $akhir_periode[$smt];
		}
	}

	if ( !function_exists('tgl_indo') )
	{
		function tgl_indo($tgl)
		{
			if (!$tgl) $tgl = date('j-n-Y');
			
			$bln = array(
				1=>'Januari',
				2=>'Februari',
				3=>'Maret',
				4=>'April',
				5=>'Mei',
				6=>'Juni',
				7=>'Juli',
				8=>'Agustus',
				9=>'September',
				10=>'Oktober',
				11=>'November',
				12=>'Desember'
			);
			list($tg, $bl, $th) = explode('-', $tgl);
			return $tg.' '.$bln[$bl+0].' '.$th;
		}
	}

	if ( !function_exists('bln_indo') )
	{
		function bln_indo($bl)
		{
			$bln = array(
				1=>'Januari',
				2=>'Februari',
				3=>'Maret',
				4=>'April',
				5=>'Mei',
				6=>'Juni',
				7=>'Juli',
				8=>'Agustus',
				9=>'September',
				10=>'Oktober',
				11=>'November',
				12=>'Desember'
			);
			return $bln[$bl+0];
		}
	}

?>