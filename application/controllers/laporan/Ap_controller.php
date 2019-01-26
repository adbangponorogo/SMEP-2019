<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ap_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('auth_id'))) { //Filter untuk semua
			if (stristr(uri_string(), 'main-page'))
				$this->load->view('pages/errors/data');
			else
				redirect(base_url());
		}
		$this->load->model('Main_model', 'model');
		$this->load->model('laporan/Ap_model', 'ap_model');
	}

	public function index(){
		if (empty($this->model->getKaSKPD($this->session->userdata('skpd_id'))->row()->nama)) { //Filter khusus modul laporan
			$this->load->view('pages/laporan/errors/data-ka-skpd-kosong');
		}
		else {			
			$this->load->view('pages/laporan/ap/data');
		}
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
		$obj->skpd = $this->input->post("skpd");
		$obj->tahun = $this->input->post("tahun");
		$obj->bulan = $this->input->post("bulan");
		$obj->tgl_cetak = $this->input->post("tgl_cetak");
		
		$obj->kd_skpd = $this->model->getSKPD($obj->skpd)->row()->kd_skpd;
		$obj->nama_skpd = $this->model->getSKPD($obj->skpd)->row()->nama_skpd;
		
		$obj->tingkat = $smep->tingkat;
		$obj->klpd = $smep->klpd;
		$obj->footerlap = $smep->footerlap;

		switch ($obj->jenis_realisasi){
			case 1: $this->getPrintDataAP1($obj); break;
			case 2: $this->getPrintDataAP2($obj); break;
			case 3: $this->getPrintDataAP3($obj);
		}
	}

	public function getPrintDataAP1($obj){
		//echo 'skpd:'.$obj->skpd.' | jenis_realisasi:'.$obj->jenis_realisasi.' | tahun:'.$obj->tahun.' | bulan:'.$obj->bulan.' | tgl_cetak:'.$obj->tgl_cetak.' | kd_skpd:'.$obj->kd_skpd.' | nama_skpd:'.$obj->nama_skpd.' | tingkat:'.$obj->tingkat.' | klpd:'.$obj->klpd.' | footerlap:'.$obj->footerlap;
		
 		$this->load->library('Excel');
 		$this->load->helper('office_helper');
 		$this->load->helper('other_helper');

		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load('assets/tpl/ap1.xlsx');
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
				$x->setCellValue('D'.$row, $d->jumlah);
				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'C'.$row);
				xl_font($x, 'B'.$row.':D'.$row,11,'bold');
				$row++;

				$keg = $this->ap_model->getKeg($d->id);
				foreach ($keg->result() as $e){
					// -------- Value ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('C'.$row, $e->keterangan_kegiatan);
					$x->setCellValue('D'.$row, $e->jumlah);
					
					xl_wrap($x, 'C'.$row);
					xl_font($x, 'B'.$row.':D'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->ap_model->getRO($e->id);
					foreach ($ro->result() as $f){
						// -------- Value ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->kd_rekening);
						$x->setCellValue('C'.$row, $f->nama_rekening);
						$x->setCellValue('D'.$row, $f->jumlah);
						
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
		xl_number_format($x, 'D'.$mulai.':D'.$row);
		xl_align($x, 'E'.$mulai.':U'.$row);
		xl_align($x, 'A'.$mulai.':U'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		$x->setCellValue('D'.$row, '=SUM(D'.$mulai.':D'.($row-2).')/3');
		xl_font($x, 'C'.$row.':D'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':U'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->model->getKaSKPD($obj->skpd)->row(),//data kepala SKPD
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

	public function getPrintDataAP2($obj){
		echo 'skpd:'.$obj->skpd.' | jenis_realisasi:'.$obj->jenis_realisasi.' | tahun:'.$obj->tahun.' | bulan:'.$obj->bulan.' | tgl_cetak:'.$obj->tgl_cetak;
	}

	public function getPrintDataAP3($obj){
		echo 'skpd:'.$obj->skpd.' | jenis_realisasi:'.$obj->jenis_realisasi.' | tahun:'.$obj->tahun.' | bulan:'.$obj->bulan.' | tgl_cetak:'.$obj->tgl_cetak;
	}
}