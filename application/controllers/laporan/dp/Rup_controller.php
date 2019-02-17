<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rup_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/dp/Rup_model', 'model');
	}

	public function index(){
		if (empty($this->main_model->getKaSKPD($this->session->userdata('auth_id'))->row()->nama)) { //Filter khusus modul laporan
			$this->load->view('pages/laporan/errors/data-ka-skpd-kosong');
		}
		else {
			$this->load->view('pages/laporan/dp-rup/data');
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
		$obj->cara_pengadaan = $this->input->post("cara_pengadaan");
		$obj->id_skpd = $this->input->post("skpd");
		$obj->tahun = $this->input->post("tahun");
		$obj->bulan = 2;
		$obj->tgl_cetak = $this->input->post("tgl_cetak");
		
		$obj->kd_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->kd_skpd;
		$obj->nama_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->nama_skpd;
		
		$obj->tingkat = $smep->tingkat;
		$obj->klpd = $smep->klpd;
		$obj->footerlap = $smep->footerlap;

 		$this->load->library('Excel');
 		$this->load->helper('office_helper');
 		$this->load->helper('other_helper');

		switch ($obj->cara_pengadaan){
			case 1: $this->getPrintDataPenyedia($obj); break;
			default: $this->getPrintDataSwakelola($obj);
		}
	}

	public function getPrintDataPenyedia($obj){
		$jenis_form = 'RUP-P';
		
		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('C3', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('A4', strtoupper($obj->tingkat));
		$x->setCellValue('C4', ': '.strtoupper($obj->klpd));
		$x->setCellValue('C5', ': '.$obj->tahun);
		$x->setCellValue('O6', 'Form '.$jenis_form);

		$mulai = 11;
		$row = $mulai;
		$prog = $this->model->getProg($obj->kd_skpd, $obj->cara_pengadaan);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, strtoupper($d->keterangan_program));

				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'B'.$row);
				xl_font($x, 'B'.$row,11,'bold');
				$row++;

				$keg = $this->model->getKeg($d->id, $obj->cara_pengadaan);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('B'.$row, $e->keterangan_kegiatan);

					xl_wrap($x, 'B'.$row);
					xl_font($x, 'B'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->model->getPaket($e->id, $obj->cara_pengadaan);
					foreach ($ro->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->nama_paket);
						$x->setCellValue('C'.$row, $f->volume_pekerjaan);
						$x->setCellValue('D'.$row, $f->pagu_paket+0);
						$x->setCellValue('E'.$row, sumber_dana($f->sumber_dana));
						$x->setCellValue('F'.$row, $f->kd_mak);
						$x->setCellValue('G'.$row, jenis_belanja($f->jenis_belanja));
						$x->setCellValue('H'.$row, jenis_pengadaan($f->jenis_pengadaan));
						$x->setCellValue('I'.$row, metode_pemilihan($f->metode_pemilihan));
						$x->setCellValue('J'.$row, $f->lokasi_pekerjaan);
						$x->setCellValue('K'.$row, $f->pelaksanaan_pengadaan_awal);
						$x->setCellValue('L'.$row, $f->pelaksanaan_pengadaan_akhir);
						$x->setCellValue('M'.$row, $f->pelaksanaan_kontrak_awal);
						$x->setCellValue('N'.$row, $f->pelaksanaan_kontrak_akhir);
						$x->setCellValue('O'.$row, $f->pelaksanaan_pemanfaatan);
						
						xl_wrap($x, 'B'.$row);
						xl_wrap($x, 'E'.$row);
						xl_wrap($x, 'G'.$row.':J'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('B'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'D'.$mulai.':D'.$row);
		xl_align($x, 'A'.$mulai.':O'.$row, 'top');
		xl_align($x, 'C'.$mulai.':C'.$row);
		xl_align($x, 'E'.$mulai.':I'.$row);
		xl_align($x, 'K'.$mulai.':O'.$row);

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		
		// Total pagu
		$x->setCellValue('D'.$row, '=SUM(D'.$mulai.':D'.($row-2).')');
		
		xl_font($x, 'C'.$row.':D'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':O'.$row);

		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'K' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$jenis_form,//Jenis Form Laporan (RUP)
			$obj->nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$jenis_form.'_'.$obj->tahun);
	}

	public function getPrintDataSwakelola($obj){
		$jenis_form = 'RUP-S';
		
		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('C3', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('A4', strtoupper($obj->tingkat));
		$x->setCellValue('C4', ': '.strtoupper($obj->klpd));
		$x->setCellValue('C5', ': '.$obj->tahun);
		$x->setCellValue('K6', 'Form '.$jenis_form);

		$mulai = 11;
		$row = $mulai;

		$prog = $this->model->getProg($obj->kd_skpd, $obj->cara_pengadaan);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, strtoupper($d->keterangan_program));

				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'B'.$row);
				xl_font($x, 'B'.$row,11,'bold');
				$row++;

				$keg = $this->model->getKeg($d->id, $obj->cara_pengadaan);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('B'.$row, $e->keterangan_kegiatan);

					xl_wrap($x, 'B'.$row);
					xl_font($x, 'B'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->model->getPaket($e->id, $obj->cara_pengadaan);
					foreach ($ro->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->nama_paket);
						$x->setCellValue('C'.$row, $f->lokasi_pekerjaan);
						$x->setCellValue('D'.$row, jenis_belanja($f->jenis_belanja));
						$x->setCellValue('E'.$row, sumber_dana($f->sumber_dana));
						$x->setCellValue('F'.$row, $f->kd_mak);
						$x->setCellValue('G'.$row, jenis_pengadaan($f->jenis_pengadaan));
						$x->setCellValue('H'.$row, $f->pagu_paket+0);
						$x->setCellValue('I'.$row, $f->volume_pekerjaan);
						$x->setCellValue('J'.$row, $f->pelaksanaan_pekerjaan_awal	);
						$x->setCellValue('K'.$row, $f->pelaksanaan_pekerjaan_akhir);
						
						xl_wrap($x, 'B'.$row.':E'.$row);
						xl_wrap($x, 'G'.$row);
						xl_wrap($x, 'I'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('B'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'H'.$mulai.':H'.$row);
		xl_align($x, 'A'.$mulai.':K'.$row, 'top');
		xl_align($x, 'D'.$mulai.':G'.$row);
		xl_align($x, 'I'.$mulai.':K'.$row);

		$x->setCellValue('G'.$row, 'Jumlah:');
		xl_align($x, 'G'.$row, 'right');
		
		// Total pagu
		$x->setCellValue('H'.$row, '=SUM(H'.$mulai.':H'.($row-2).')');
		
		xl_font($x, 'G'.$row.':H'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':K'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'H' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$jenis_form,//Jenis Form Laporan (RUP)
			$obj->nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$jenis_form.'_'.$obj->tahun);
	}
}