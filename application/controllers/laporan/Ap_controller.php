<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ap_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Ap_model', 'ap_model');
	}

	public function index(){
		if (empty($this->main_model->getKaSKPD($this->session->userdata('auth_id'))->row()->nama)) { //Filter khusus modul laporan
			$this->load->view('pages/laporan/errors/data-ka-skpd-kosong');
		}
		else {
		}
			$this->load->view('pages/laporan/ap/data');
	}

	public function getMainDataAllSKPD($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_user = $this->ap_model->getDataUser($this->session->userdata('auth_id'));
			$data = array();
			foreach ($result_user->result() as $rows_user) {
				if ($rows_user->status != 1) {
					$result_skpd = $this->ap_model->getDataSKPDUnique($rows_user->id_skpd);
					foreach ($result_skpd->result() as $rows_skpd) {
						$data[] = array(
									$rows_user->status,
									$rows_skpd->id,
									"[".$rows_skpd->kd_skpd."] - ".$rows_skpd->nama_skpd
								);
					}
				}
				else{
					$result_skpd = $this->ap_model->getDataSKPD();
					foreach ($result_skpd->result() as $rows_skpd) {
						$data[] = array(
										$rows_user->status,
										$rows_skpd->id,
									"[".$rows_skpd->kd_skpd."] - ".$rows_skpd->nama_skpd
								);
					}
				}
			}
			echo json_encode($data);
		}
	}

	public function getPrintData(){
		global $smep;
		
		$obj = new stdClass();
		$obj->jenis_realisasi = $this->input->post("jenis_realisasi");
		$obj->id_skpd = $this->input->post("skpd");
		$obj->tahun = $this->input->post("tahun");
		$obj->bulan = $this->input->post("bulan");
		$obj->tgl_cetak = $this->input->post("tgl_cetak");
		
		$obj->kd_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->kd_skpd;
		$obj->nama_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->nama_skpd;
		
		$obj->tingkat = $smep->tingkat;
		$obj->klpd = $smep->klpd;
		$obj->footerlap = $smep->footerlap;

 		$this->load->library('Excel');
 		$this->load->helper('office_helper');
 		$this->load->helper('other_helper');

		switch ($obj->jenis_realisasi){
			case 1: $this->getPrintDataAP1($obj); break;
			case 2: $this->getPrintDataAP2($obj); break;
			case 3: $this->getPrintDataAP3($obj);
		}
	}

	public function getPrintDataAP1($obj){
		$jenis_form = 'AP-1';
		
		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('A2', 'PEMERINTAH '.strtoupper($obj->tingkat.' '.$obj->klpd));
		$x->setCellValue('A3', 'TAHUN ANGGARAN '.$obj->tahun);
		$x->setCellValue('A4', 'KEADAAN SAMPAI DENGAN BULAN '.strtoupper(bln_indo($obj->bulan)));
		$x->setCellValue('C6', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('U6', 'Form '.$jenis_form);

		$mulai = 11;
		$row = $mulai;

		$prog = $this->ap_model->getProg($obj->kd_skpd);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, $d->kd_gabungan.' ');
				$x->setCellValue('C'.$row, strtoupper($d->keterangan_program));

				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'C'.$row);
				xl_font($x, 'B'.$row.':C'.$row,11,'bold');
				$row++;

				$keg = $this->ap_model->getKeg($d->id);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('C'.$row, $e->keterangan_kegiatan);

					xl_wrap($x, 'C'.$row);
					xl_font($x, 'B'.$row.':C'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->ap_model->getRO($e->id, ($obj->bulan+0));
					foreach ($ro->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->kd_rekening);
						$x->setCellValue('C'.$row, $f->nama_rekening);

						$klm_pagu = getKolomSumberDana($f->sumber_dana); //pagu
						$klm_real = getKolomSumberDana($f->sumber_dana, 'real');
						$klm_fisik = getKolomSumberDana($f->sumber_dana, 'fisik');
					
						$x->setCellValue($klm_pagu.$row, ($f->pagu+0));
						$x->setCellValue($klm_real.$row, ($f->real_keu+0));
						
						$persen_fisik = ($x->getCell($klm_pagu.$row)->getValue())? '='.$klm_real.$row.'/'.$klm_pagu.$row : 0;
						$x->setCellValue($klm_fisik.$row, $persen_fisik);
						
						xl_persen($x, $klm_fisik.$row);
						xl_wrap($x, 'C'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('C'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'D'.$mulai.':J'.$row);
		xl_number_format($x, 'L'.$mulai.':L'.$row);
		xl_number_format($x, 'N'.$mulai.':N'.$row);
		xl_number_format($x, 'P'.$mulai.':P'.$row);
		xl_number_format($x, 'R'.$mulai.':R'.$row);
		xl_number_format($x, 'T'.$mulai.':T'.$row);
		xl_align($x, 'D'.$mulai.':I'.$row, 'right');
		xl_align($x, 'A'.$mulai.':U'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		
		// Total pagu
		$x->setCellValue('D'.$row, '=SUM(D'.$mulai.':D'.($row-2).')');
		$x->setCellValue('E'.$row, '=SUM(E'.$mulai.':E'.($row-2).')');
		$x->setCellValue('F'.$row, '=SUM(F'.$mulai.':F'.($row-2).')');
		$x->setCellValue('G'.$row, '=SUM(G'.$mulai.':G'.($row-2).')');
		$x->setCellValue('H'.$row, '=SUM(H'.$mulai.':H'.($row-2).')');
		$x->setCellValue('I'.$row, '=SUM(I'.$mulai.':I'.($row-2).')');
		
		// Total real keu
		$x->setCellValue('J'.$row, '=SUM(J'.$mulai.':J'.($row-2).')');
		$x->setCellValue('L'.$row, '=SUM(L'.$mulai.':L'.($row-2).')');
		$x->setCellValue('N'.$row, '=SUM(N'.$mulai.':N'.($row-2).')');
		$x->setCellValue('P'.$row, '=SUM(P'.$mulai.':P'.($row-2).')');
		$x->setCellValue('R'.$row, '=SUM(R'.$mulai.':R'.($row-2).')');
		$x->setCellValue('T'.$row, '=SUM(T'.$mulai.':T'.($row-2).')');

		$sumD = ($x->getCell('D'.$row)->getCalculatedValue())? '=J'.$row.'/D'.$row : 0;
		$sumE = ($x->getCell('E'.$row)->getCalculatedValue())? '=L'.$row.'/E'.$row : 0;
		$sumF = ($x->getCell('F'.$row)->getCalculatedValue())? '=N'.$row.'/F'.$row : 0;
		$sumG = ($x->getCell('G'.$row)->getCalculatedValue())? '=P'.$row.'/G'.$row : 0;
		$sumH = ($x->getCell('H'.$row)->getCalculatedValue())? '=R'.$row.'/H'.$row : 0;
		$sumI = ($x->getCell('I'.$row)->getCalculatedValue())? '=T'.$row.'/I'.$row 	: 0;
		
		// Total real fisik
		//$x->setCellValue('K'.$row, '"'.$x->getCell('D'.$row)->getValue().'"'); //Get whatever value in cell, the formula.
		$x->setCellValue('K'.$row, $sumD);
		$x->setCellValue('M'.$row, $sumE);
		$x->setCellValue('O'.$row, $sumF);
		$x->setCellValue('Q'.$row, $sumG);
		$x->setCellValue('S'.$row, $sumH);
		$x->setCellValue('U'.$row, $sumI);
		
		xl_persen($x, 'K'.$row);
		xl_persen($x, 'M'.$row);
		xl_persen($x, 'O'.$row);
		xl_persen($x, 'Q'.$row);
		xl_persen($x, 'S'.$row);
		xl_persen($x, 'U'.$row);
		
		xl_font($x, 'C'.$row.':U'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':U'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'P' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$jenis_form,//Jenis Form Laporan (AP1-2)
			$obj->nama_skpd
		);
		// export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$jenis_form);
		export2xl($p, 'AP-1');
	}

/*
	public function getPrintDataAP1_plus_jumlah($obj){
 		$this->load->library('Excel');
 		$this->load->helper('office_helper');
 		$this->load->helper('other_helper');

		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.'ap1.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('A2', 'PEMERINTAH '.strtoupper($obj->tingkat.' '.$obj->klpd));
		$x->setCellValue('A3', 'TAHUN ANGGARAN '.$obj->tahun);
		$x->setCellValue('A4', 'KEADAAN SAMPAI DENGAN BULAN '.strtoupper(bln_indo($obj->bulan)));
		$x->setCellValue('C6', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('U6', 'Form AP-1');

		$mulai = 11;
		$row = $mulai;

		$prog = $this->ap_model->getProg($obj->kd_skpd);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Value ---------
				$x->setCellValue('B'.$row, $d->kd_gabungan.' ');
				$x->setCellValue('C'.$row, strtoupper($d->keterangan_program));

				$klm_pagu = getKolomSumberDana($d->sumber_dana);
				
				// $d->jumlah di tambah 0 (nol) untuk menghindari error excel:
				// "The number in this cell is formatted as text or preceded by an aphostrope"
				
				$x->setCellValue($klm_pagu.$row, ($d->jumlah+0));
				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'C'.$row);
				xl_font($x, 'B'.$row.':U'.$row,11,'bold');
				$row++;

				$keg = $this->ap_model->getKeg($d->id);
				foreach ($keg->result() as $e){
					// -------- Value ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('C'.$row, $e->keterangan_kegiatan);

					$klm_pagu = getKolomSumberDana($e->sumber_dana);
					
					$x->setCellValue($klm_pagu.$row, ($e->jumlah+0));
					
					xl_wrap($x, 'C'.$row);
					xl_font($x, 'B'.$row.':U'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->ap_model->getRO($e->id);
					foreach ($ro->result() as $f){
						// -------- Value ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->kd_rekening);
						$x->setCellValue('C'.$row, $f->nama_rekening);

						$klm_pagu = getKolomSumberDana($f->sumber_dana);
					
						$x->setCellValue($klm_pagu.$row, ($f->jumlah+0));
						
						xl_wrap($x, 'C'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('C'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'D'.$mulai.':I'.$row);
		xl_align($x, 'D'.$mulai.':I'.$row, 'right');
		xl_align($x, 'A'.$mulai.':U'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		$x->setCellValue('D'.$row, '=SUM(D'.$mulai.':D'.($row-2).')/3');
		$x->setCellValue('E'.$row, '=SUM(E'.$mulai.':E'.($row-2).')/3');
		$x->setCellValue('F'.$row, '=SUM(F'.$mulai.':F'.($row-2).')/3');
		$x->setCellValue('G'.$row, '=SUM(G'.$mulai.':G'.($row-2).')/3');
		$x->setCellValue('H'.$row, '=SUM(H'.$mulai.':H'.($row-2).')/3');
		$x->setCellValue('I'.$row, '=SUM(I'.$mulai.':I'.($row-2).')/3');
		xl_font($x, 'C'.$row.':I'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':U'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'P' // Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			'AP-1',//Jenis Form Laporan (AP1-2, RP1-4, LP1-4 dst)
			$obj->nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_AP-1');
	}
*/

	public function getPrintDataAP2($obj){
		$jenis_form = 'AP-2';
		
		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('A1', 'PEMERINTAH '.strtoupper($obj->tingkat.' '.$obj->klpd));
		$x->setCellValue('A3', 'TAHUN ANGGARAN '.$obj->tahun);
		$x->setCellValue('A4', 'KEADAAN SAMPAI DENGAN BULAN '.strtoupper(bln_indo($obj->bulan)));
		$x->setCellValue('A6', 'ORGANISASI : '.strtoupper($obj->nama_skpd));
		$x->setCellValue('M6', 'Form '.$jenis_form);

		$mulai = 11;
		$row = $mulai;

		$prog = $this->ap_model->getProgAP2($obj->kd_skpd);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, $d->kd_gabungan.' ');
				$x->setCellValue('C'.$row, strtoupper($d->keterangan_program));

				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'C'.$row);
				xl_font($x, 'B'.$row.':C'.$row,11,'bold');
				$row++;

				$keg = $this->ap_model->getKegAP2($d->id);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('C'.$row, $e->keterangan_kegiatan);

					xl_wrap($x, 'C'.$row);
					xl_font($x, 'B'.$row.':C'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->ap_model->getPaketAP2($e->id, ($obj->bulan+0));
					foreach ($ro->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->kd_rekening);
						$x->setCellValue('C'.$row, $f->nama_paket);
						$x->setCellValue('D'.$row, sumber_dana($f->sumber_dana));

						$x->setCellValue('E'.$row, ($f->pagu_paket+0));
						$x->setCellValue('K'.$row, ($f->real_keu+0));
						$x->setCellValue('L'.$row, ($f->real_fisik+0));
						
						xl_persen($x, 'L'.$row);
						xl_wrap($x, 'C'.$row.':H'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('C'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'E'.$mulai.':E'.$row);
		xl_number_format($x, 'K'.$mulai.':K'.$row);
		xl_align($x, 'D'.$mulai.':D'.$row);
		xl_align($x, 'M'.$mulai.':M'.$row);
		xl_align($x, 'A'.$mulai.':M'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		
		// Total pagu
		$x->setCellValue('E'.$row, '=SUM(E'.$mulai.':E'.($row-2).')');
		
		// Total real keu
		$x->setCellValue('K'.$row, '=SUM(K'.$mulai.':K'.($row-2).')');

		$sumE = ($x->getCell('E'.$row)->getCalculatedValue())? '=K'.$row.'/E'.$row : 0;
		
		// Total real fisik
		//$x->setCellValue('K'.$row, '"'.$x->getCell('D'.$row)->getValue().'"'); //Get whatever value in cell, the formula.
		$x->setCellValue('L'.$row, $sumE);
		
		xl_persen($x, 'L'.$row);
		
		xl_font($x, 'C'.$row.':M'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':M'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'J' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$jenis_form,//Jenis Form Laporan (AP1-2)
			$obj->nama_skpd
		);
		// export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$jenis_form);
		export2xl($p, 'AP-2');
	}

	public function getPrintDataAP3($obj){
		echo 'skpd:'.$obj->id_skpd.' | jenis_realisasi:'.$obj->jenis_realisasi.' | tahun:'.$obj->tahun.' | bulan:'.$obj->bulan.' | tgl_cetak:'.$obj->tgl_cetak;
	}
}