<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rp_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/dp/Rp_model', 'model');
	}

	public function index(){
		if (empty($this->main_model->getKaSKPD($this->session->userdata('auth_id'))->row()->nama)) { 
			//Filter khusus modul laporan
			$this->load->view('pages/laporan/errors/data-ka-skpd-kosong');
		}
		else {			
			$this->load->view('pages/laporan/dp-rp/data');
		}
	}

	public function getMainDataAllSKPD($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_user = $this->model->getDataUser($this->session->userdata('auth_id'));
			$data = array();
			foreach ($result_user->result() as $rows_user) {
				if ($rows_user->status != 1) {
					$result_skpd = $this->model->getDataSKPDUnique($rows_user->id_skpd);
					foreach ($result_skpd->result() as $rows_skpd) {
						$data[] = array(
									$rows_user->status,
									$rows_skpd->id,
									"[".$rows_skpd->kd_skpd."] - ".$rows_skpd->nama_skpd
								);
					}
				}
				else{
					$result_skpd = $this->model->getDataSKPD();
					foreach ($result_skpd->result() as $rows_skpd) {
						$data[] = array(
										$rows_user->status,
										$rows_skpd->id,
									'['.$rows_skpd->kd_skpd.'] - '.$rows_skpd->nama_skpd
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
		$obj->id_skpd = $this->input->post('skpd');
		$obj->jenis_pengadaan = $this->input->post('jenis_pengadaan');
		$obj->tgl_cetak = $this->input->post('tgl_cetak');
		$obj->tahun = $this->input->post('tahun');

		$obj->kd_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->kd_skpd;
		$obj->nama_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->nama_skpd;
		
		$obj->tingkat = $smep->tingkat;
		$obj->klpd = $smep->klpd;
		$obj->footerlap = $smep->footerlap;

		$this->load->library('Excel');
		$this->load->helper('office_helper');
		$this->load->helper('other_helper');
		
		switch ($obj->jenis_pengadaan){
			//case 1: $this->getPrintDataRP($obj); break;
			default: $this->getPrintDataRP($obj);
		}
	}

	public function getPrintDataRP($obj){
		$jenis_form = 'RP';

		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('D3', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('A4', strtoupper($obj->tingkat));
		$x->setCellValue('D4', ': '.strtoupper($obj->klpd));
		$x->setCellValue('D5', ': '.$obj->tahun);
		$x->setCellValue('D7', ': '.strtoupper(jenis_pengadaan($obj->jenis_pengadaan)));
		$x->setCellValue('M7', 'Form '.$jenis_form.'-'.$obj->jenis_pengadaan);

		$mulai = 12;
		$row = $mulai;

		$prog = $this->model->getProg($obj->kd_skpd, $obj->jenis_pengadaan);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, $d->kd_gabungan.' ');
				$x->setCellValue('C'.$row, strtoupper($d->keterangan_program));
				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'C'.$row);
				xl_font($x, 'A'.$row.':C'.$row,11,'bold');
				$row++;

				$keg = $this->model->getKeg($d->id, $obj->jenis_pengadaan);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('C'.$row, $e->keterangan_kegiatan);
					
					xl_wrap($x, 'C'.$row);
					xl_font($x, 'A'.$row.':L'.$row,11,'bold');
					$row++;

					$no = 0;
					$paket = $this->model->getPaket($e->id, $obj->jenis_pengadaan);
					foreach ($paket->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, substr($f->kd_mak, -11));
						$x->setCellValue('C'.$row, $f->nama_paket);
						$x->setCellValue('D'.$row, $f->pagu_paket+0);
						$x->setCellValue('E'.$row, sumber_dana($f->sumber_dana));

						$M = (empty($f->nama))? 'Belum dipilih' : $f->nama;
						$x->setCellValue('M'.$row, $M);//Penanggung Jawab Kegiatan
						
						xl_wrap($x, 'C'.$row);
						xl_wrap($x, 'M'.$row);
						
						$F = $G = $H = $I = $J = $K = $L = '-';
						switch ($f->metode_pemilihan){
							case 1: $K='X'; break;	//E-Purchasing
							case 2: $F='X'; break;	//Tender
							case 3: $G='X'; break;	//Tender Cepat
							case 4: $H='X'; break;	//Pengadaan Langsung
							case 5: $I='X'; break;	//Penunjukkan Langsung
							case 6: $J='X';	break;	//Seleksi
							default: $L='X';
						}
						$x->setCellValue('F'.$row, $F);
						$x->setCellValue('G'.$row, $G);
						$x->setCellValue('H'.$row, $H);
						$x->setCellValue('I'.$row, $I);
						$x->setCellValue('J'.$row, $J);
						$x->setCellValue('K'.$row, $K);
						$x->setCellValue('L'.$row, $L);
						
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
		xl_align($x, 'E'.$mulai.':M'.$row);
		xl_align($x, 'A'.$mulai.':M'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		$x->setCellValue('D'.$row, '=SUM(D'.($mulai+2).':D'.($row-2).')');
		xl_font($x, 'C'.$row.':D'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':M'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'I' // Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$jenis_form.'-'.$obj->jenis_pengadaan,//Jenis Form Laporan (RP1-4 & LP1-4)
			$obj->nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$jenis_form.'-'.$obj->jenis_pengadaan.'_'.$obj->tahun);
	}
}