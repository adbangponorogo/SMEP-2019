<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rp_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Rp_model', 'rp_model');
	}

	public function index(){
		if (empty($this->model->getKaSKPD($this->session->userdata('auth_id'))->row()->nama)) { //Filter khusus modul laporan
			$this->load->view('pages/laporan/errors/data-ka-skpd-kosong');
		}
		else {			
			$this->load->view('pages/laporan/rp/data');
		}
	}

	public function getMainDataAllSKPD($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_user = $this->rp_model->getDataUser($this->session->userdata('auth_id'));
			$data = array();
			foreach ($result_user->result() as $rows_user) {
				if ($rows_user->status != 1) {
					$result_skpd = $this->rp_model->getDataSKPDUnique($rows_user->id_skpd);
					foreach ($result_skpd->result() as $rows_skpd) {
						$data[] = array(
									$rows_user->status,
									$rows_skpd->id,
									"[".$rows_skpd->kd_skpd."] - ".$rows_skpd->nama_skpd
								);
					}
				}
				else{
					$result_skpd = $this->rp_model->getDataSKPD();
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
		
		$jenis_form = 'RP';

		$id_skpd = $this->input->post('skpd');
		$jenis_pengadaan = $this->input->post('jenis_pengadaan');
		$tgl_cetak = $this->input->post('tgl_cetak');
		$tahun = $this->input->post('tahun');

		$kd_skpd = $this->model->getSKPD($id_skpd)->row()->kd_skpd;
		$nama_skpd = $this->model->getSKPD($id_skpd)->row()->nama_skpd;
		
		$this->load->library('Excel');
		$this->load->helper('office_helper');
		$this->load->helper('other_helper');

		$nama_jenis_pengadaan = getJenisPengadaan($jenis_pengadaan);

		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('D3', ': '.strtoupper($nama_skpd));
		$x->setCellValue('A4', strtoupper($smep->tingkat));
		$x->setCellValue('D4', ': '.strtoupper($smep->klpd));
		$x->setCellValue('D5', ': '.$tahun);
		$x->setCellValue('D7', ': '.strtoupper($nama_jenis_pengadaan));
		$x->setCellValue('L7', 'Form '.$jenis_form.'-'.$jenis_pengadaan);

		$mulai = 12;
		$row = $mulai;

		$prog = $this->rp_model->getProg($kd_skpd, $jenis_pengadaan);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, $d->kd_gabungan.' ');
				$x->setCellValue('C'.$row, strtoupper($d->keterangan_program));
				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'C'.$row);
				xl_font($x, 'A'.$row.':C'.$row,11,'bold');
				$row++;

				$keg = $this->rp_model->getKeg($d->id, $jenis_pengadaan);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->kd_gabungan);
					$x->setCellValue('C'.$row, $e->keterangan_kegiatan);
					
					$L = '-';
					if (!empty($e->nama)) $L = $e->nama;
					$x->setCellValue('L'.$row, $L);//Penanggung Jawab Kegiatan
					
					xl_wrap($x, 'C'.$row);
					xl_wrap($x, 'L'.$row);
					xl_font($x, 'A'.$row.':L'.$row,11,'bold');
					$row++;

					$no = 0;
					$paket = $this->rp_model->getPaket($e->id, $jenis_pengadaan);
					foreach ($paket->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, substr($f->kd_mak, -11));
						$x->setCellValue('C'.$row, $f->nama_paket);
						$x->setCellValue('D'.$row, $f->pagu_paket);
						$x->setCellValue('E'.$row, sumber_dana($f->sumber_dana));
						
						$F = $G = $H = $I = $J = $K = '-';
						switch ($f->metode_pemilihan){
							case 1: $K='X'; break;	//E-Purchasing
							case 2: $F='X'; break;	//Tender
							case 3: $G='X'; break;	//Tender Cepat
							case 4: $H='X'; break;	//Pengadaan Langsung
							case 5: $I='X'; break;	//Penunjukkan Langsung
							case 6: $J='X';			//Seleksi
						}
						$x->setCellValue('F'.$row, $F);
						$x->setCellValue('G'.$row, $G);
						$x->setCellValue('H'.$row, $H);
						$x->setCellValue('I'.$row, $I);
						$x->setCellValue('J'.$row, $J);
						$x->setCellValue('K'.$row, $K);
						
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
		xl_align($x, 'E'.$mulai.':L'.$row);
		xl_align($x, 'A'.$mulai.':L'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		$x->setCellValue('D'.$row, '=SUM(D'.($mulai+2).':D'.($row-2).')');
		xl_font($x, 'C'.$row.':D'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':L'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$smep->klpd,
			$tgl_cetak,
			$this->model->getKaSKPD($id_skpd, false)->row(),//data kepala SKPD
			'I' // Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$smep->footerlap,//footer laporan sebelah kiri
			$jenis_form.'-'.$jenis_pengadaan,//Jenis Form Laporan (RP1-4 & LP1-4)
			$nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $nama_skpd).'_'.$jenis_form.'-'.$jenis_pengadaan);
	}
}