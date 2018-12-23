<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporrup_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laporrup_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/rup/data');
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
		if ($this->session->userdata('auth_id') != '') {
			$skpd = $this->input->post("skpd");
			$cara_pengadaan = $this->input->post("cara_pengadaan");
			$nama_laporan = $this->input->post("nama_laporan");
			$tahun = $this->input->post("tahun");
			$tanggal_cetak = $this->input->post("tanggal_cetak");

			if ($cara_pengadaan == 1) {
				$nama_cara_pengadaan = "Penyedia";
			}
			if ($cara_pengadaan == 2) {
				$nama_cara_pengadaan = "Swakelola";
			}

			$jenis_belanja = ["", "Belanja Pegawai", "Belanja Barang/Jasa", "Belanja Modal", "Belanja Terindentifikasi", "Belanja Bunga Utang", "Belanja Subsidi", "Belanja Hibah", "Belanja Bantuan Sosial", "Belanja Lain-Lain"] ;
			$jenis_pengadaan = ["", "Barang", "Konstruksi", "Jasa Konsultasi", "Jasa Lainnya"];
			$sumber_dana = ["","APBD","APBDP","APBN","APBNP","BLU","BLUD","BUMD","BUMN","PHLN","PNBP","Lainnya"];
			
			$this->load->library("Excel");
			$object =  new PHPExcel();
			$object->setActiveSheetIndex(0);
			$result_skpd = $this->model->getDataSKPDUnique($skpd);
			foreach ($result_skpd->result() as $rows_skpd) {
				$nama_skpd = $rows_skpd->nama_skpd;
			}

			// -------- PAPER Setup -------- //
			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);
			$object->getActiveSheet()->getSheetView()->setZoomScale(80);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | RUP '.$nama_cara_pengadaan.' - '.$nama_skpd.'&R&P');
			
			// -------- Title Form -------- //
			$title_form = 'RENCANA UMUM PENGADAAN MELALUI '.strtoupper($nama_cara_pengadaan);
			$object->getActiveSheet()->setCellValue('A1', $title_form);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A1:Q1');
			$object->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			// -------- Nama Organisasi -------- //
			$info_organisasi = 'NAMA ORGANISASI ';
			$nama_organisasi = ': '.strtoupper($nama_skpd);
			$object->getActiveSheet()->setCellValue('A3', $info_organisasi);
			$object->getActiveSheet()->setCellValue('C3', $nama_organisasi);
			$object->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C3')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A3:B3');
			$object->getActiveSheet()->mergeCells('C3:Q3');
			$object->getActiveSheet()->getStyle('A3:Q3')->getFont()->setBold(TRUE);

			// -------- Kabupaten -------- //
			$info_kabupaten = 'KABUPATEN ';
			$nama_kabupaten = ': PONOROGO';
			$object->getActiveSheet()->setCellValue('A4', $info_kabupaten);
			$object->getActiveSheet()->setCellValue('C4', $nama_kabupaten);
			$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A4:B4');
			$object->getActiveSheet()->mergeCells('C4:Q4');
			$object->getActiveSheet()->getStyle('A4:Q4')->getFont()->setBold(TRUE);

			// -------- Tahun Anggaran -------- //
			$info_anggaran = 'TAHUN ANGGARAN ';
			$nama_anggaran = ': '.$tahun;
			$object->getActiveSheet()->setCellValue('A5', $info_anggaran);
			$object->getActiveSheet()->setCellValue('C5', $nama_anggaran);
			$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C5')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A5:B5');
			$object->getActiveSheet()->mergeCells('C5:Q5');
			$object->getActiveSheet()->getStyle('A5:Q5')->getFont()->setBold(TRUE);

			if ($cara_pengadaan == 1) {
				// TABLE HEADER
				$table_header_first = array("NO", "SATUAN KERJA", "KEGIATAN", "NAMA PAKET", "LOKASI", "JENIS BELANJA", "JENIS PENGADAAN", "VOLUME", "SUMBER DANA", "KODE MAK", "PAGU (Rupiah)", "METODE PEMILIHAN PENYEDIA", "PELAKSANAAN PENGADAAN", "", "PELAKSANAAN KONTRAK", "", "DESKRIPSI");
				$table_header_second = array("AWAL", "AKHIR", "AWAL", "AKHIR");

				$start_column_first = 0;
				foreach ($table_header_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 7, $thead_first);
					$start_column_first++;
				}

				$start_column_second = 12;
				foreach ($table_header_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 8, $thead_second);
					$start_column_second++;
				}

				// TABLE HEADER SETUP
				$object->getActiveSheet()->mergeCells('A7:A8');
				$object->getActiveSheet()->mergeCells('B7:B8');
				$object->getActiveSheet()->mergeCells('C7:C8');
				$object->getActiveSheet()->mergeCells('D7:D8');
				$object->getActiveSheet()->mergeCells('E7:E8');
				$object->getActiveSheet()->mergeCells('F7:F8');
				$object->getActiveSheet()->mergeCells('G7:G8');
				$object->getActiveSheet()->mergeCells('H7:H8');
				$object->getActiveSheet()->mergeCells('I7:I8');
				$object->getActiveSheet()->mergeCells('J7:J8');
				$object->getActiveSheet()->mergeCells('K7:K8');
				$object->getActiveSheet()->mergeCells('L7:L8');
				$object->getActiveSheet()->mergeCells('M7:N7');

				$object->getActiveSheet()->mergeCells('O7:P7');

				$object->getActiveSheet()->mergeCells('Q7:Q8');

				$object->getActiveSheet()->getStyle('A7:Q8')->getFont()->setSize(8);
				$object->getActiveSheet()->getStyle('A7:Q8')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A7:Q8')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A7:Q8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A7:Q8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A7:Q8')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A7:Q8')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F39826');

				// TABLE MAIN
	 			$no = 1;
	 			$mulai = 9;
	 			$result_rup = $this->model->getAllDataRUP($skpd, $cara_pengadaan);
	 			if ($result_rup->num_rows() > 0) {
	 				foreach ($result_rup->result() as $rows_rup) {
	 					$result_skpd = $this->model->getDataSKPDUnique($rows_rup->id_skpd);
	 					foreach ($result_skpd->result() as $rows_skpd) {
	 						$result_kegiatan = $this->model->getDataKegiatanUnique($rows_rup->id_kegiatan);
		 					foreach ($result_kegiatan->result() as $rows_kegiatan) {
		 						$object->getActiveSheet()->setCellValue('A'.$mulai, $no);
		 						$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_skpd->nama_skpd);
		 						$object->getActiveSheet()->setCellValue('C'.$mulai, $rows_kegiatan->keterangan_kegiatan);
		 						$object->getActiveSheet()->setCellValue('D'.$mulai, $rows_rup->nama_paket);
		 						$object->getActiveSheet()->setCellValue('E'.$mulai, $rows_rup->lokasi_pekerjaan);
		 						$object->getActiveSheet()->setCellValue('F'.$mulai, $jenis_belanja[$rows_rup->jenis_belanja]);
		 						$object->getActiveSheet()->setCellValue('G'.$mulai, $jenis_pengadaan[$rows_rup->jenis_pengadaan]);
		 						$object->getActiveSheet()->setCellValue('H'.$mulai, $rows_rup->volume_pekerjaan);
		 						$object->getActiveSheet()->setCellValue('I'.$mulai, $sumber_dana[$rows_rup->sumber_dana]);
		 						$object->getActiveSheet()->setCellValue('J'.$mulai, $rows_rup->kd_mak);
		 						$object->getActiveSheet()->setCellValue('K'.$mulai, $rows_rup->pagu_paket);
		 						$object->getActiveSheet()->setCellValue('L'.$mulai, $rows_rup->metode_pemilihan);
		 						$object->getActiveSheet()->setCellValue('M'.$mulai, $rows_rup->pelaksanaan_pengadaan_awal);
		 						$object->getActiveSheet()->setCellValue('N'.$mulai, $rows_rup->pelaksanaan_pengadaan_akhir);
		 						$object->getActiveSheet()->setCellValue('O'.$mulai, $rows_rup->pelaksanaan_kontrak_awal);
		 						$object->getActiveSheet()->setCellValue('P'.$mulai, $rows_rup->pelaksanaan_kontrak_akhir);
		 						$object->getActiveSheet()->setCellValue('Q'.$mulai, '-');
		 					}
	 					}
		 				$no++;
		 				$mulai++;
	 				}
		 			$object->getActiveSheet()->setCellValue('I'.$mulai, 'TOTAL');
		 			$object->getActiveSheet()->setCellValue('K'.$mulai, '=SUM(K9:K'.(($mulai)-1).')');

		 			// SETUP
		 			$object->getActiveSheet()->getStyle('I'.$mulai.':K'.$mulai)->getFont()->setBold(TRUE);
		 			$object->getActiveSheet()->getStyle('A9:Q'.($mulai))->getFont()->setSize(8);
		 			$object->getActiveSheet()->mergeCells('I'.$mulai.':J'.$mulai);
		 			$object->getActiveSheet()->getStyle('A9:Q'.($mulai))->getAlignment()->setWrapText(true);

		 			$object->getActiveSheet()->getStyle('A9:A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getStyle('A9:A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getStyle('B9:E'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$object->getActiveSheet()->getStyle('B9:E'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getStyle('F9:Q'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getStyle('F9:Q'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		 			$object->getActiveSheet()->getStyle('A9:Q'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
		 			$object->getActiveSheet()->getStyle('K9:K'.($mulai))->getNumberFormat()->setFormatCode('#,##0');


	 			}
	 			else{
					$object->getActiveSheet()->setCellValue('B10', 'NIHIL');
	 			}

				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Laporan RUP - '.$nama_cara_pengadaan.'.xls"');
				$object_writer->save('php://output');
			}
			if ($cara_pengadaan == 2) {
				// TABLE HEADER
				$table_header_first = array("NO", "NAMA ORGANISASI", "NAMA KEGIATAN", " LOKASI", "JENIS BELANJA", "SUMBER DANA", "KODE MAK", "JENIS PENGADAAN", "PAGU (Rupiah)", "VOLUME", "DESKRIPSI", "PELAKSANAAN PEKERJAAN", "");
				$table_header_second = array("AWAL", "AKHIR");

				$start_column_first = 0;
				foreach ($table_header_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 7, $thead_first);
					$start_column_first++;
				}

				$start_column_second = 11;
				foreach ($table_header_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 8, $thead_second);
					$start_column_second++;
				}

				// TABLE HEADER SETUP
				$object->getActiveSheet()->mergeCells('A7:A8');
				$object->getActiveSheet()->mergeCells('B7:B8');
				$object->getActiveSheet()->mergeCells('C7:C8');
				$object->getActiveSheet()->mergeCells('D7:D8');
				$object->getActiveSheet()->mergeCells('E7:E8');
				$object->getActiveSheet()->mergeCells('F7:F8');
				$object->getActiveSheet()->mergeCells('G7:G8');
				$object->getActiveSheet()->mergeCells('H7:H8');
				$object->getActiveSheet()->mergeCells('I7:I8');
				$object->getActiveSheet()->mergeCells('J7:J8');
				$object->getActiveSheet()->mergeCells('K7:K8');
				$object->getActiveSheet()->mergeCells('L7:M7');

				$object->getActiveSheet()->getStyle('A7:M8')->getFont()->setSize(8);
				$object->getActiveSheet()->getStyle('A7:M8')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A7:M8')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A7:M8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A7:M8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A7:M8')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A7:M8')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F39826');

				// TABLE MAIN
	 			$no = 1;
	 			$mulai = 9;
	 			$result_rup = $this->model->getAllDataRUP($skpd, $cara_pengadaan);
	 			if ($result_rup->num_rows() > 0) {
		 			foreach ($result_rup->result() as $rows_rup) {
		 				$result_skpd = $this->model->getDataSKPDUnique($rows_rup->id_skpd);
		 				foreach ($result_skpd->result() as $rows_skpd) {
		 					$result_kegiatan = $this->model->getDataKegiatanUnique($rows_rup->id_kegiatan);
		 					foreach ($result_kegiatan->result() as $rows_kegiatan) {
		 						$object->getActiveSheet()->setCellValue('A'.$mulai, $no);
			 					$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_skpd->nama_skpd);
			 					$object->getActiveSheet()->setCellValue('C'.$mulai, $rows_kegiatan->keterangan_kegiatan);
			 					$object->getActiveSheet()->setCellValue('D'.$mulai, $rows_rup->lokasi_pekerjaan);
			 					$object->getActiveSheet()->setCellValue('E'.$mulai, $jenis_belanja[$rows_rup->jenis_belanja]);
			 					$object->getActiveSheet()->setCellValue('F'.$mulai, $sumber_dana[$rows_rup->sumber_dana]);
			 					$object->getActiveSheet()->setCellValue('G'.$mulai, $rows_rup->kd_mak);
			 					$object->getActiveSheet()->setCellValue('H'.$mulai, $jenis_pengadaan[$rows_rup->jenis_pengadaan]);
			 					$object->getActiveSheet()->setCellValue('I'.$mulai, $rows_rup->pagu_paket);
			 					$object->getActiveSheet()->setCellValue('J'.$mulai, $rows_rup->volume_pekerjaan);
			 					$object->getActiveSheet()->setCellValue('K'.$mulai, $rows_rup->nama_paket);
			 					$object->getActiveSheet()->setCellValue('L'.$mulai, $rows_rup->pelaksanaan_pekerjaan_awal);
			 					$object->getActiveSheet()->setCellValue('M'.$mulai, $rows_rup->pelaksanaan_pekerjaan_akhir);
			 					
		 					}
		 				}
		 				$no++;
		 				$mulai++;
		 			}
		 			$object->getActiveSheet()->setCellValue('G'.$mulai, 'TOTAL');
		 			$object->getActiveSheet()->setCellValue('I'.$mulai, '=SUM(I9:I'.(($mulai)-1).')');

		 			// SETUP
		 			$object->getActiveSheet()->getStyle('G'.$mulai.':I'.$mulai)->getFont()->setBold(TRUE);
		 			$object->getActiveSheet()->getStyle('A9:M'.($mulai))->getFont()->setSize(8);
		 			$object->getActiveSheet()->mergeCells('G'.$mulai.':H'.$mulai);
		 			$object->getActiveSheet()->getStyle('A9:M'.($mulai))->getAlignment()->setWrapText(true);

		 			$object->getActiveSheet()->getStyle('A9:A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getStyle('A9:A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getStyle('B9:D'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$object->getActiveSheet()->getStyle('B9:D'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getStyle('E9:J'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getStyle('E9:J'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getStyle('K9:K'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$object->getActiveSheet()->getStyle('K9:K'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$object->getActiveSheet()->getStyle('L9:M'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$object->getActiveSheet()->getStyle('L9:M'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		 			$object->getActiveSheet()->getStyle('A9:M'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
		 			$object->getActiveSheet()->getStyle('I9:I'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

	 			}
	 			else{
	 				$object->getActiveSheet()->setCellValue('B10', 'NIHIL');
	 			}

				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Laporan RUP - '.$nama_cara_pengadaan.'.xls"');
				$object_writer->save('php://output');
			}

		}
	}
}