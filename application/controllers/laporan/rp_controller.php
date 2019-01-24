<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rp_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Main_model', 'model');
		$this->load->model('laporan/Rp_model', 'rp_model');
	}

	public function index(){
		if ($this->session->userdata('auth_id') != "") {
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
		$skpd = $this->input->post('skpd');
		$jenis_pengadaan = $this->input->post('jenis_pengadaan');
		$tgl_cetak = $this->input->post('tgl_cetak');
		$tahun = $this->input->post('tahun');
		
		$kd_skpd = $this->rp_model->getSKPD($skpd)->row()->kd_skpd;
		$nama_skpd = $this->rp_model->getSKPD($skpd)->row()->nama_skpd;
		$klpd = $this->model->getConfig('klpd')->row()->value;
		$footerlap = $this->model->getConfig('footerlap')->row()->value;
		
 		$this->load->library('Excel');
 		$this->load->helper('office_helper');
 		$this->load->helper('other_helper');

		$nama_jenis_pengadaan = getJenisPengadaan($jenis_pengadaan);

		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load('assets/tpl/rp.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('C3', ': '.strtoupper($nama_skpd));
		$x->setCellValue('C4', ': '.strtoupper($klpd));
		$x->setCellValue('C5', ': '.$tahun);
		$x->setCellValue('C7', ': '.strtoupper($nama_jenis_pengadaan));
		$x->setCellValue('K7', 'Form RP-'.$jenis_pengadaan);

		$mulai = 12;
		$row = $mulai;

		$prog = $this->rp_model->getProg($kd_skpd, $jenis_pengadaan);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Value ---------
				$x->setCellValue('A'.$row, $d->kd_gabungan.' ');
				$x->setCellValue('B'.$row, strtoupper($d->keterangan_program));
				xl_autoheight($x, 'A'.$row);
				xl_wrap($x, 'B'.$row);
				xl_font($x, 'A'.$row.':K'.$row,11,'bold');
				$row++;

				$keg = $this->rp_model->getKeg($kd_skpd, $d->id, $jenis_pengadaan);
				foreach ($keg->result() as $e){
					// -------- Value ---------
					$x->setCellValue('A'.$row, $e->kd_gabungan);
					$x->setCellValue('B'.$row, $e->keterangan_kegiatan);
					
					$K = '-';
					if (!empty($e->nama)) $K = $e->nama;
					$x->setCellValue('K'.$row, $K);//Penanggung Jawab Kegiatan
					
					xl_autoheight($x, 'A'.$row);
					xl_wrap($x, 'B'.$row);
					xl_wrap($x, 'K'.$row);
					xl_font($x, 'A'.$row.':K'.$row,11,'bold');
					$row++;

					$paket = $this->rp_model->getPaket($kd_skpd, $e->id, $jenis_pengadaan);
					foreach ($paket->result() as $f){
						// -------- Value ---------
						$x->setCellValue('A'.$row, substr($f->kd_mak, -11));
						$x->setCellValue('B'.$row, $f->nama_paket);
						$x->setCellValue('C'.$row, $f->pagu_paket);
						$x->setCellValue('D'.$row, sumber_dana($f->sumber_dana));
						
						$E = $F = $G = $H = $I = $J = '-';
						switch ($f->metode_pemilihan){
							case 1: $J='X'; break;	//E-Purchasing
							case 2: $E='X'; break;	//Tender
							case 3: $F='X'; break;	//Tender Cepat
							case 4: $G='X'; break;	//Pengadaan Langsung
							case 5: $H='X'; break;	//Penunjukkan Langsung
							case 6: $I='X';			//Seleksi
						}
						$x->setCellValue('E'.$row, $E);
						$x->setCellValue('F'.$row, $F);
						$x->setCellValue('G'.$row, $G);
						$x->setCellValue('H'.$row, $H);
						$x->setCellValue('I'.$row, $I);
						$x->setCellValue('J'.$row, $J);
						
						xl_autoheight($x, 'A'.$row);
						xl_wrap($x, 'B'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('B'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'C'.$mulai.':C'.$row);
		xl_align($x, 'D'.$mulai.':K'.$row);
		xl_align($x, 'A'.$mulai.':K'.$row, 'top');

		$x->setCellValue('B'.$row, 'Jumlah:');
		xl_align($x, 'B'.$row, 'right');
		$x->setCellValue('C'.$row, '=SUM(C'.($mulai+2).':C'.($row-2).')');
		xl_borderall($x, 'A'.$mulai.':K'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$klpd,
			$tgl_cetak,
			$this->rp_model->getKaSKPD($skpd)->row(),//data kepala SKPD
			'H' // Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$footerlap,//footer laporan sebelah kiri
			'RP-'.$jenis_pengadaan,//Jenis Form Laporan (AP1-2, RP1-4, LP1-4 dst)
			$nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $nama_skpd).'_RP-'.$jenis_pengadaan);
	}
}