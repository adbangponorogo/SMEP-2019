<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lp_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/dp/Lp_model', 'model');
	}

	public function index(){
		if (empty($this->main_model->getKaSKPD($this->session->userdata('auth_id'))->row()->nama)) { //Filter khusus modul laporan
			$this->load->view('pages/laporan/errors/data-ka-skpd-kosong');
		}
		else {
			$this->load->view('pages/laporan/dp-lp/data');
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
		$obj->jenis_pengadaan = $this->input->post("jenis_pengadaan");
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

		switch ($obj->jenis_pengadaan){
			case 3: $this->getPrintDataLP3($obj); break;
			default: $this->getPrintDataLP($obj);
		}
	}

	public function getPrintDataLP($obj){
		$jenis_form = 'LP';
		
		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('A2', 'KEADAAN SAMPAI DENGAN BULAN '.strtoupper(bln_indo($obj->bulan)));
		$x->setCellValue('C4', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('A5', strtoupper($obj->tingkat));
		$x->setCellValue('C5', ': '.strtoupper($obj->klpd));
		$x->setCellValue('C6', ': '.$obj->tahun);
		$x->setCellValue('C8', ': '.strtoupper(jenis_pengadaan($obj->jenis_pengadaan)));
		$x->setCellValue('R8', 'Form '.$jenis_form.'-'.$obj->jenis_pengadaan);

		$mulai = 13;
		$row = $mulai;

		$prog = $this->model->getProg($obj->kd_skpd, $obj->jenis_pengadaan);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, strtoupper($d->keterangan_program));

				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'B'.$row);
				xl_font($x, 'B'.$row,11,'bold');
				$row++;

				$keg = $this->model->getKeg($d->id, $obj->jenis_pengadaan);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->keterangan_kegiatan);

					xl_wrap($x, 'B'.$row);
					xl_font($x, 'B'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->model->getPaket($e->id, $obj->bulan+0, $obj->jenis_pengadaan);
					foreach ($ro->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->nama_paket);
						$x->setCellValue('C'.$row, sumber_dana($f->sumber_dana));
						$x->setCellValue('D'.$row, $f->pagu_paket+0);
						$x->setCellValue('E'.$row, $f->real_keu+0);
						$x->setCellValue('F'.$row, '=D'.$row.'-E'.$row);
						$x->setCellValue('G'.$row, $f->nilai_hps+0);
						$x->setCellValue('H'.$row, $f->nilai_kontrak+0);
						$x->setCellValue('I'.$row, metode_pemilihan($f->metode_pemilihan));
						$x->setCellValue('J'.$row, empty($f->jumlah_mendaftar)? '-' : $f->jumlah_mendaftar);
						$x->setCellValue('K'.$row, empty($f->jumlah_menawar)? '-' : $f->jumlah_menawar);
						$x->setCellValue('L'.$row, empty($f->tanggal_pengumuman)? '-' : $f->tanggal_pengumuman);
						$x->setCellValue('M'.$row, empty($f->tanggal_anwijzing)? '-' : $f->tanggal_anwijzing);
						$x->setCellValue('N'.$row, empty($f->tanggal_pembukaan_penawaran)?  '-' : $f->tanggal_pembukaan_penawaran);
						$x->setCellValue('O'.$row, empty($f->tanggal_penetapan_pemenang)?  '-' : $f->tanggal_penetapan_pemenang);
						
						$pemenang = $f->nama_pemenang;
						$pemenang .= $pemenang? "\r" : '';
						$pemenang .= $f->nomor_kontrak? $f->nomor_kontrak : '';
						$pemenang .= $pemenang? "\r" : '';
						$pemenang .= $f->tanggal_kontrak? $f->tanggal_kontrak : '';

						$x->setCellValue('P'.$row, empty($pemenang)? '-' : $pemenang);
						$x->setCellValue('Q'.$row, empty($f->tanggal_spmk)? '-' : $f->tanggal_spmk);
						$x->setCellValue('R'.$row, sanggah($f->sanggah));
						
						xl_wrap($x, 'B'.$row);
						xl_wrap($x, 'I'.$row);
						xl_wrap($x, 'P'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('C'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'D'.$mulai.':H'.$row);
		xl_align($x, 'C'.$mulai.':C'.$row);
		xl_align($x, 'I'.$mulai.':R'.$row);
		xl_align($x, 'A'.$mulai.':R'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		
		// Total pagu
		$x->setCellValue('D'.$row, '=SUM(D'.$mulai.':D'.($row-2).')');
		$x->setCellValue('E'.$row, '=SUM(E'.$mulai.':E'.($row-2).')');
		$x->setCellValue('F'.$row, '=SUM(F'.$mulai.':F'.($row-2).')');
		
		xl_font($x, 'C'.$row.':F'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':R'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'O' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$jenis_form.'-'.$obj->jenis_pengadaan,//Jenis Form Laporan (LP1,2 & 4)
			$obj->nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$jenis_form.'-'.$obj->jenis_pengadaan.'_'.$obj->bulan.'-'.$obj->tahun);
	}

	public function getPrintDataLP3($obj){
		$jenis_form = 'LP-3';
		
		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('A2', 'KEADAAN SAMPAI DENGAN BULAN '.strtoupper(bln_indo($obj->bulan)));
		$x->setCellValue('C4', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('A5', strtoupper($obj->tingkat));
		$x->setCellValue('C5', ': '.strtoupper($obj->klpd));
		$x->setCellValue('C6', ': '.$obj->tahun);
		$x->setCellValue('C8', ': '.strtoupper(jenis_pengadaan($obj->jenis_pengadaan)));
		$x->setCellValue('U8', 'Form '.$jenis_form);

		$mulai = 13;
		$row = $mulai;

		$prog = $this->model->getProg($obj->kd_skpd, $obj->jenis_pengadaan);
		if ($prog->num_rows()){
			foreach ($prog->result() as $d){
				// -------- Program ---------
				$x->setCellValue('B'.$row, strtoupper($d->keterangan_program));

				xl_autoheight($x, 'B'.$row);
				xl_wrap($x, 'B'.$row);
				xl_font($x, 'B'.$row,11,'bold');
				$row++;

				$keg = $this->model->getKeg($d->id, $obj->jenis_pengadaan);
				foreach ($keg->result() as $e){
					// -------- Kegiatan ---------
					$x->setCellValue('B'.$row, $e->keterangan_kegiatan);

					xl_wrap($x, 'B'.$row);
					xl_font($x, 'B'.$row,11,'bold');
					$row++;

					$no = 0;
					$ro = $this->model->getPaket($e->id, $obj->bulan+0, $obj->jenis_pengadaan);
					foreach ($ro->result() as $f){
						// -------- Rincian Obyek ---------
						$x->setCellValue('A'.$row, ++$no);
						$x->setCellValue('B'.$row, $f->nama_paket);
						$x->setCellValue('C'.$row, sumber_dana($f->sumber_dana));
						$x->setCellValue('D'.$row, $f->pagu_paket+0);
						$x->setCellValue('E'.$row, $f->real_keu+0);
						$x->setCellValue('F'.$row, '=D'.$row.'-E'.$row);
						$x->setCellValue('G'.$row, $f->nilai_hps+0);
						$x->setCellValue('H'.$row, $f->nilai_kontrak+0);
						$x->setCellValue('I'.$row, metode_pemilihan($f->metode_pemilihan));
						$x->setCellValue('J'.$row, empty($f->jumlah_mendaftar)? '-' : $f->jumlah_mendaftar);
						$x->setCellValue('K'.$row, empty($f->jumlah_lulus_kualifikasi)? '-' : $f->jumlah_lulus_kualifikasi);
						$x->setCellValue('L'.$row, empty($f->jumlah_menawar)? '-' : $f->jumlah_menawar);
						$x->setCellValue('M'.$row, empty($f->jumlah_lulus_teknis)? '-' : $f->jumlah_lulus_teknis);
						$x->setCellValue('N'.$row, empty($f->tanggal_pengumuman)? '-' : $f->tanggal_pengumuman);
						$x->setCellValue('O'.$row, empty($f->tanggal_anwijzing)? '-' : $f->tanggal_anwijzing);
						$x->setCellValue('P'.$row, empty($f->tanggal_pembukaan_penawaran)?  '-' : $f->tanggal_pembukaan_penawaran);
						$x->setCellValue('Q'.$row, empty($f->tanggal_klarifikasi_negosiasi)?  '-' : $f->tanggal_klarifikasi_negosiasi);
						$x->setCellValue('R'.$row, empty($f->tanggal_penetapan_pemenang)?  '-' : $f->tanggal_penetapan_pemenang);
						
						$pemenang = $f->nama_pemenang;
						$pemenang .= $pemenang? "\r" : '';
						$pemenang .= $f->nomor_kontrak? $f->nomor_kontrak : '';
						$pemenang .= $pemenang? "\r" : '';
						$pemenang .= $f->tanggal_kontrak? $f->tanggal_kontrak : '';

						$x->setCellValue('S'.$row, empty($pemenang)? '-' : $pemenang);
						$x->setCellValue('T'.$row, empty($f->tanggal_spmk)? '-' : $f->tanggal_spmk);
						$x->setCellValue('U'.$row, sanggah($f->sanggah));
						
						xl_wrap($x, 'B'.$row);
						xl_wrap($x, 'I'.$row);
						xl_wrap($x, 'S'.$row);
						xl_wrap($x, 'U'.$row);
						$row++;
					}
					$row++;
				}
			}
		}else{
			$x->setCellValue('C'.$row, 'NIHIL');
			$row+=10;
		}
		xl_number_format($x, 'D'.$mulai.':H'.$row);
		xl_align($x, 'C'.$mulai.':C'.$row);
		xl_align($x, 'I'.$mulai.':U'.$row);
		xl_align($x, 'A'.$mulai.':U'.$row, 'top');

		$x->setCellValue('C'.$row, 'Jumlah:');
		xl_align($x, 'C'.$row, 'right');
		
		// Total pagu
		$x->setCellValue('D'.$row, '=SUM(D'.$mulai.':D'.($row-2).')');
		$x->setCellValue('E'.$row, '=SUM(E'.$mulai.':E'.($row-2).')');
		$x->setCellValue('F'.$row, '=SUM(F'.$mulai.':F'.($row-2).')');
		
		xl_font($x, 'C'.$row.':F'.$row,11,'bold');
		xl_borderall($x, 'A'.$mulai.':U'.$row);
		
		getPenanggungJawab(
			$x,
			$row,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'Q' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$jenis_form,//Jenis Form Laporan (LP-3)
			$obj->nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$jenis_form.'_'.$obj->bulan.'-'.$obj->tahun);
	}
}