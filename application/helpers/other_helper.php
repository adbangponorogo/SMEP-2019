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

	if ( !function_exists('getMetodePemilihan') )
	{
		function getMetodePemilihan($i)
		{
			$metode_pemilihan = array(
									'',
									'E-Purchasing',//9
									'Tender',//13
									'Tender Cepat',//14
									'Pengadaan Langsung',//8
									'Penunjukkan Langsung',//7
									'Seleksi'//15
									);
			return $metode_pemilihan[$i];
		}
	}

	if ( !function_exists('getJenisPengadaan') )
	{
		function getJenisPengadaan($i)
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