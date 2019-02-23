<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminrebela_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/adminrebela_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/realisasi-belanja-langsung/data');
		}
		else{
			redirect(base_url());
		}
	}

	public function getPrintData(){
		if ($this->session->userdata("auth_id") != "") {
			$jenis_realisasi = $this->input->post("jenis_realisasi");
			$tahun = $this->input->post("tahun");
			$bulan = $this->input->post("bulan");

			switch ($jenis_realisasi) {
				case '1':
					$nama_jenis_realisasi = 'REKAPITULASI APBD - BELANJA LANGSUNG PER OPD';
				break;
				case '2':
					$nama_jenis_realisasi = 'LAPORAN REALISASI FISIK DAN KEUANGAN JASA KONSTRUKSI';
				break;
				case '3':
					$nama_jenis_realisasi = 'DANA DEKONSENTRASI, HIBAH, DAN SWADAYA / PIHAK KE-3';
				break;
				
				default:
					$nama_jenis_realisasi = '-';
				break;
			}

			if ($bulan == "01") {$nama_bulan="JANUARI";}if ($bulan == "02") {$nama_bulan="FEBRUARI";}if ($bulan == "03") {$nama_bulan="MARET";}if ($bulan == "04") {$nama_bulan="APRIL";}if ($bulan == "05") {$nama_bulan="MEI";}if ($bulan == "06") {$nama_bulan="JUNI";}if ($bulan == "07") {$nama_bulan="JULI";}if ($bulan == "08") {$nama_bulan="AGUSTUS";}if ($bulan == "09") {$nama_bulan="SEPTEMBER";}if ($bulan == "10") {$nama_bulan="OKTOBER";}if ($bulan == "11") {$nama_bulan="NOVEMBER";}if ($bulan == "12") {$nama_bulan="DESEMBER";}


			$this->load->library("Excel");
			$object =  new PHPExcel();
			$object->setActiveSheetIndex(0);

			// -------- PAPER Setup -------- //
			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);
			$object->getActiveSheet()->getSheetView()->setZoomScale(80);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | Rekap AP-'.$jenis_realisasi.'&R&P');

			if ($jenis_realisasi == 1) {
				// -------- Title Form -------- //
				$title_form = 'PEMERINTAH KABUPATEN PONOROGO';
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:G1');
				$object->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Subtitle Form -------- //
				$subtitle_form_first = $nama_jenis_realisasi;
				$subtitle_form_second = 'TAHUN ANGGARAN '.$tahun;
				$subtitle_form_third = 'KEADAAN SAMPAI DENGAN BULAN '.$nama_bulan;
				$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
				$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
				$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A2:G2');
				$object->getActiveSheet()->mergeCells('A3:G3');
				$object->getActiveSheet()->mergeCells('A4:G4');
				$object->getActiveSheet()->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// TABLE HEADER
				$table_title_head_row_first = array("NO", "ORGANISASI", "DANA APBD", "BELANJA TIDAK LANGSUNG", "BELANJA LANGSUNG", "REALISASI");
				$table_title_head_row_second = array("RP", "%");

				$start_column_first = 0;
				foreach ($table_title_head_row_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 7, $thead_first);
					$start_column_first++;
				}
				$start_column_second = 5;
				foreach ($table_title_head_row_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 8, $thead_second);
					$start_column_second++;
				}

				// SETUP
				$object->getActiveSheet()->mergeCells('F7:G7');
				$object->getActiveSheet()->mergeCells('A7:A8');
				$object->getActiveSheet()->mergeCells('B7:B8');
				$object->getActiveSheet()->mergeCells('C7:C8');
				$object->getActiveSheet()->mergeCells('D7:D8');
				$object->getActiveSheet()->mergeCells('E7:E8');

				$object->getActiveSheet()->getStyle('A7:G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A7:G8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A7:G8')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A7:G8')->getFont()->setSize(7);
				$object->getActiveSheet()->getStyle('A7:G8')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A7:G8')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A7:G8')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');


				$no = 1;
				$mulai = 9;
				$result_skpd = $this->model->getDataSKPD();
				foreach ($result_skpd->result() as $rows_skpd) {
					$result_pagu = $this->model->getDataPaguSKPD($rows_skpd->kd_skpd);
					foreach ($result_pagu->result() as $rows_pagu) {
						$result_realisasi = $this->model->getDataRealisasiSKPD($rows_skpd->id);
						foreach ($result_realisasi->result() as $rows_realisasi) {

							// Values

							$object->getActiveSheet()->setCellValue('A'.$mulai, $no++);
							$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_skpd->nama_skpd);
							$object->getActiveSheet()->setCellValue('C'.$mulai, intval($rows_pagu->btl)+intval($rows_pagu->bl));
							$object->getActiveSheet()->setCellValue('D'.$mulai, $rows_pagu->btl);
							$object->getActiveSheet()->setCellValue('E'.$mulai, $rows_pagu->bl);
							$object->getActiveSheet()->setCellValue('F'.$mulai, $this->nullValue($rows_realisasi->realisasi_keuangan));
							if (!is_null($rows_pagu->bl) || $rows_pagu->bl != '') {
								if (!is_null($rows_realisasi->realisasi_keuangan) || $rows_realisasi->realisasi_keuangan != '') {
									$object->getActiveSheet()->setCellValue('G'.$mulai, '=F'.$mulai.'/E'.$mulai.'');
								}
							}

							// SETUP

							$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->getFont()->setSize(10);
							$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('C'.$mulai.':F'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
							$object->getActiveSheet()->getStyle('G'.$mulai)->getNumberFormat()->setFormatCode('0.00%');

							$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('C'.($mulai).':G'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$object->getActiveSheet()->getStyle('C'.($mulai).':G'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

							$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						}
					}

					$mulai++;
				}

				// Values

				$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
				$object->getActiveSheet()->setCellValue('C'.($mulai), '=SUM(C9:C'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('D'.($mulai), '=SUM(D9:D'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E9:E'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('F'.($mulai), '=SUM(F9:F'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('G'.($mulai), '=F'.$mulai.'/E'.$mulai.'');


				// SETUP

				$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A'.($mulai).':B'.($mulai));
				$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('C'.$mulai.':F'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('G'.$mulai)->getNumberFormat()->setFormatCode('0.00%');

				$object->getActiveSheet()->getStyle('A'.($mulai).':G'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A'.($mulai).':G'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

			}
			if ($jenis_realisasi == 2) {
				// -------- Title Form -------- //
				$title_form = 'PEMERINTAH KABUPATEN PONOROGO';
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:E1');
				$object->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Subtitle Form -------- //
				$subtitle_form_first = $nama_jenis_realisasi;
				$subtitle_form_second = 'TAHUN ANGGARAN '.$tahun;
				$subtitle_form_third = 'KEADAAN SAMPAI DENGAN BULAN '.$nama_bulan;
				$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
				$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
				$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A2:E2');
				$object->getActiveSheet()->mergeCells('A3:E3');
				$object->getActiveSheet()->mergeCells('A4:E4');
				$object->getActiveSheet()->getStyle('A2:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// TABLE HEADER
				$table_title_head_row_first = array("NO", "ORGANISASI", "PAGU", "REALISASI", "");
				$table_title_head_row_second = array("FISIK", "KEUANGAN");

				$start_column_first = 0;
				foreach ($table_title_head_row_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 6, $thead_first);
					$start_column_first++;
				}
				$start_column_second = 3;
				foreach ($table_title_head_row_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 7, $thead_second);
					$start_column_second++;
				}

				// SETUP
				$object->getActiveSheet()->mergeCells('A6:A7');
				$object->getActiveSheet()->mergeCells('B6:B7');
				$object->getActiveSheet()->mergeCells('C6:C7');
				$object->getActiveSheet()->mergeCells('D6:E6');

				$object->getActiveSheet()->getStyle('A6:E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:E7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:E7')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A6:E7')->getFont()->setSize(7);
				$object->getActiveSheet()->getStyle('A6:E7')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A6:E7')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A6:E7')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
				

				$no = 1;
				$mulai = 8;
				$result_skpd = $this->model->getDataSKPD();
				foreach ($result_skpd->result() as $rows_skpd) {
					$result_pagu = $this->model->getDataPaguKonstruksiSKPD($rows_skpd->id);
					foreach ($result_pagu->result() as $rows_pagu) {
						$result_realisasi = $this->model->getDataRealisasiSKPD($rows_skpd->id);
						foreach ($result_realisasi->result() as $rows_realisasi) {

							// Values

							$object->getActiveSheet()->setCellValue('A'.$mulai, $no++);
							$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_skpd->nama_skpd);
							$object->getActiveSheet()->setCellValue('C'.$mulai, $this->nullValue($rows_pagu->pagu_paket));
							if (!is_null($rows_pagu->pagu_paket) || $rows_pagu->pagu_paket != '') {
								if (!is_null($rows_realisasi->realisasi_keuangan) || $rows_realisasi->realisasi_keuangan != '') {
									$object->getActiveSheet()->setCellValue('D'.$mulai, '=E'.$mulai.'/C'.$mulai.'');
								}
							}
							$object->getActiveSheet()->setCellValue('E'.$mulai, $this->nullValue($rows_realisasi->realisasi_keuangan));

							// SETUP

							$object->getActiveSheet()->getStyle('A'.$mulai.':E'.$mulai)->getFont()->setSize(10);
							$object->getActiveSheet()->getStyle('A'.$mulai.':E'.$mulai)->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('C'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
							$object->getActiveSheet()->getStyle('D'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
							$object->getActiveSheet()->getStyle('E'.$mulai)->getNumberFormat()->setFormatCode('#,##0');

							$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('C'.($mulai).':E'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$object->getActiveSheet()->getStyle('C'.($mulai).':E'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

							$object->getActiveSheet()->getStyle('A'.$mulai.':E'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						}
					}
					$mulai++;
				}


				// Values

				$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
				$object->getActiveSheet()->setCellValue('C'.($mulai), '=SUM(C8:C'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('D'.($mulai), '=E'.$mulai.'/C'.$mulai.'');
				$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E8:E'.(($mulai)-1).')');


				// SETUP

				$object->getActiveSheet()->getStyle('A'.$mulai.':E'.$mulai)->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A'.($mulai).':B'.($mulai));
				$object->getActiveSheet()->getStyle('A'.$mulai.':E'.$mulai)->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('C'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('D'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('E'.$mulai)->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('A'.($mulai).':E'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A'.($mulai).':E'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.$mulai.':E'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A'.$mulai.':E'.$mulai)->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

			}
			if ($jenis_realisasi == 3) {
				// -------- Title Form -------- //
				$title_form = 'PEMERINTAH KABUPATEN PONOROGO';
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:K1');
				$object->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Subtitle Form -------- //
				$subtitle_form_first = 'LAPORAN REALISASI FISIK DAN KEUANGAN';
				$subtitle_form_second = $nama_jenis_realisasi;
				$subtitle_form_third = 'TAHUN ANGGARAN '.$tahun;
				$subtitle_form_fourth = 'KEADAAN SAMPAI DENGAN BULAN '.$nama_bulan;
				$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
				$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
				$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
				$object->getActiveSheet()->setCellValue('A5', $subtitle_form_fourth);
				$object->getActiveSheet()->getStyle('A2:A5')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2:A5')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A2:K2');
				$object->getActiveSheet()->mergeCells('A3:K3');
				$object->getActiveSheet()->mergeCells('A4:K4');
				$object->getActiveSheet()->mergeCells('A5:K5');
				$object->getActiveSheet()->getStyle('A2:A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// TABLE HEADER
				$table_title_head_row_first = array("NO", "KEGIATAN", "SUMBER DANA", "", "", "REALISASI", "", "", "", "", "");
				$table_title_head_row_second = array("DEKONSENTRASI", "HIBAH", "SWADAYA / PIHAK KE-3", "DEKONSENTRASI", "", "HIBAH", "", "SWADAYA / PIHAK KE-3", "");
				$table_title_head_row_third = array("FISIK", "KEUANGAN", "FISIK", "KEUANGAN", "FISIK", "KEUANGAN");

				$start_column_first = 0;
				foreach ($table_title_head_row_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 9, $thead_first);
					$start_column_first++;
				}
				$start_column_second = 2;
				foreach ($table_title_head_row_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 10, $thead_second);
					$start_column_second++;
				}
				$start_column_third = 5;
				foreach ($table_title_head_row_third as $thead_third) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_third, 11, $thead_third);
					$start_column_third++;
				}

				$object->getActiveSheet()->mergeCells('A9:A11');
				$object->getActiveSheet()->mergeCells('B9:B11');
				$object->getActiveSheet()->mergeCells('C9:E9');
				$object->getActiveSheet()->mergeCells('F9:K9');
				$object->getActiveSheet()->mergeCells('C10:C11');
				$object->getActiveSheet()->mergeCells('D10:D11');
				$object->getActiveSheet()->mergeCells('E10:E11');
				$object->getActiveSheet()->mergeCells('F10:G10');
				$object->getActiveSheet()->mergeCells('H10:I10');
				$object->getActiveSheet()->mergeCells('J10:K10');
				$object->getActiveSheet()->getStyle('A9:K11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A9:K11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A9:K11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A9:K11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A9:K11')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A9:K11')->getFont()->setSize(7);
				$object->getActiveSheet()->getStyle('A9:K11')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A9:K11')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A9:K11')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

				$no = 1;
	 			$mulai = 11;
				$result_sumber_ro = $this->model->getDataSumberROAP3();
				if ($result_sumber_ro->num_rows() > 0) {
					$result_skpd = $this->model->getDataSKPD();
					foreach ($result_skpd->result() as $rows_skpd) {
						// -------------- Program ---------------
								
						$result_program = $this->model->getDataProgramUniqueAP3($rows_skpd->kd_skpd);
						if ($result_program->num_rows() > 0) {
							foreach ($result_program->result() as $rows_program) {
										
							// -------------- Value ---------------

							$object->getActiveSheet()->setCellValue('A'.(($mulai++)+1), $rows_program->kd_gabungan);
							$object->getActiveSheet()->setCellValue('B'.(($mulai)), $rows_program->keterangan_program);

							$result_program_sumber_dana = $this->model->getDataSumberDanaProgram($rows_skpd->id, $rows_program->id);
							foreach ($result_program_sumber_dana->result() as $rows_program_sumber_dana) {
								$result_program_pagu = $this->model->getDataPaguProgram($rows_skpd->id, $rows_program->id, $rows_program_sumber_dana->sumber_dana);
								foreach ($result_program_pagu->result() as $rows_program_pagu) {
									$result_program_realisasi = $this->model->getDataRealisasiProgram($rows_skpd->id, $rows_program->id, $rows_program_sumber_dana->sumber_dana);
									foreach ($result_program_realisasi->result() as $rows_program_realisasi) {
										if ($rows_program_sumber_dana->sumber_dana == 7) {
											$object->getActiveSheet()->setCellValue('C'.(($mulai)), $rows_program_pagu->pagu);
											$object->getActiveSheet()->setCellValue('G'.(($mulai)), $rows_program_realisasi->realisasi_keuangan);
											if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
												$object->getActiveSheet()->setCellValue('F'.(($mulai)), '=SUM(G'.(($mulai)).'/C'.(($mulai)).')');
											}
											else{
												$object->getActiveSheet()->setCellValue('F'.(($mulai)), '');
											}
										}
										if ($rows_program_sumber_dana->sumber_dana == 8) {
											$object->getActiveSheet()->setCellValue('D'.(($mulai)), $rows_program_pagu->pagu);
											$object->getActiveSheet()->setCellValue('I'.(($mulai)), $rows_program_realisasi->realisasi_keuangan);
											if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
												$object->getActiveSheet()->setCellValue('H'.(($mulai)), '=SUM(I'.(($mulai)).'/D'.(($mulai)).')');
											}
											else{
												$object->getActiveSheet()->setCellValue('H'.(($mulai)), '');
											}
										}
										if ($rows_program_sumber_dana->sumber_dana == 9) {
											$object->getActiveSheet()->setCellValue('E'.(($mulai)), $rows_program_pagu->pagu);
											$object->getActiveSheet()->setCellValue('K'.(($mulai)), $rows_program_realisasi->realisasi_keuangan);
											if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
												$object->getActiveSheet()->setCellValue('J'.(($mulai)), '=SUM(K'.(($mulai)).'/E'.(($mulai)).')');
											}
											else{
												$object->getActiveSheet()->setCellValue('J'.(($mulai)), '');
											}
										}
									}
								}
							}

							// -------------- Setup ---------------
							$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->getFont()->setBold(TRUE);
							$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('A'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('A'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('B'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$object->getActiveSheet()->getStyle('B'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('C'.(($mulai)).':K'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('C'.(($mulai)).':K'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));

							// -------------- Kegiatan ---------------
													
							$result_kegiatan = $this->model->getDataKegiatanUnique($rows_skpd->kd_skpd, $rows_program->id);
							foreach ($result_kegiatan->result() as $rows_kegiatan) {
								$object->getActiveSheet()->setCellValue('A'.(($mulai++)+1), $rows_kegiatan->kd_gabungan);
								$object->getActiveSheet()->setCellValue('B'.(($mulai)), $rows_kegiatan->keterangan_kegiatan);

								$result_kegiatan_sumber_dana = $this->model->getDataSumberDanaKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id);
								foreach ($result_kegiatan_sumber_dana->result() as $rows_kegiatan_sumber_dana) {
									$result_kegiatan_pagu = $this->model->getDataPaguKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_kegiatan_sumber_dana->sumber_dana);
									foreach ($result_kegiatan_pagu->result() as $rows_kegiatan_pagu) {
										$result_kegiatan_realisasi = $this->model->getDataRealisasiKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_kegiatan_sumber_dana->sumber_dana);
										foreach ($result_kegiatan_realisasi->result() as $rows_kegiatan_realisasi) {

											if ($rows_kegiatan_sumber_dana->sumber_dana == 7) {
												$object->getActiveSheet()->setCellValue('C'.(($mulai)), $rows_kegiatan_pagu->pagu);
												$object->getActiveSheet()->setCellValue('G'.(($mulai)), $rows_kegiatan_realisasi->realisasi_keuangan);
												
												if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)){
													$object->getActiveSheet()->setCellValue('F'.(($mulai)), '=SUM(G'.(($mulai)).'/C'.(($mulai)).')');
												}
												else{
													$object->getActiveSheet()->setCellValue('F'.(($mulai)), '');
												}
											}
											if ($rows_kegiatan_sumber_dana->sumber_dana == 8) {
												$object->getActiveSheet()->setCellValue('D'.(($mulai)), $rows_kegiatan_pagu->pagu);
												$object->getActiveSheet()->setCellValue('I'.(($mulai)), $rows_kegiatan_realisasi->realisasi_keuangan);
												
												if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
													$object->getActiveSheet()->setCellValue('H'.(($mulai)), '=SUM(I'.(($mulai)).'/D'.(($mulai)).')');
												}
												else{
													$object->getActiveSheet()->setCellValue('H'.(($mulai)), '');
												}
											}
											if ($rows_kegiatan_sumber_dana->sumber_dana == 9) {
												$object->getActiveSheet()->setCellValue('E'.(($mulai)), $rows_kegiatan_pagu->pagu);
												$object->getActiveSheet()->setCellValue('K'.(($mulai)), $rows_kegiatan_realisasi->realisasi_keuangan);
												
												if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
													$object->getActiveSheet()->setCellValue('J'.(($mulai)), '=SUM(K'.(($mulai)).'/E'.(($mulai)).')');
												}
												else{
													$object->getActiveSheet()->setCellValue('J'.(($mulai)), '');
												}
											}
										}
									}

									// -------------- Setup ---------------

									$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->getFont()->setBold(TRUE);
									$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->getAlignment()->setWrapText(true);
											
									$object->getActiveSheet()->getStyle('A'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('A'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('B'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$object->getActiveSheet()->getStyle('B'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('C'.(($mulai)).':K'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('C'.(($mulai)).':K'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

									$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
								}

								
								// -------------- Rincian Obyek ---------------
											
								$result_ro = $this->model->getDataRincianObyekUnique($rows_skpd->kd_skpd, $rows_kegiatan->kd_gabungan);
								foreach ($result_ro->result() as $rows_ro) {
									$object->getActiveSheet()->setCellValue('A'.(($mulai++)+1), $no++);
									$object->getActiveSheet()->setCellValue('B'.(($mulai)), "[".$rows_ro->kd_rekening."] - ".$rows_ro->nama_rekening);

									$result_ro_sumber_dana = $this->model->getDataSumberDanaRO($rows_ro->id);
									foreach ($result_ro_sumber_dana->result() as $rows_ro_sumber_dana) {
										$result_ro_pagu = $this->model->getDataPaguRO($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_ro->id);
										foreach ($result_ro_pagu->result() as $rows_ro_pagu) {
											$result_ro_realisasi = $this->model->getDataRealisasiRO($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_ro->id);
											foreach ($result_ro_realisasi->result() as $rows_ro_realisasi) {

												if ($rows_ro_sumber_dana->sumber_dana == 7) {
													$object->getActiveSheet()->setCellValue('C'.(($mulai)), $rows_ro_pagu->pagu);
													$object->getActiveSheet()->setCellValue('G'.(($mulai)), $rows_ro_realisasi->realisasi_keuangan);
													
													if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
														$object->getActiveSheet()->setCellValue('F'.(($mulai)), '=SUM(G'.(($mulai)).'/C'.(($mulai)).')');
													}
													else{
														$object->getActiveSheet()->setCellValue('F'.(($mulai)), '');
													}
												}
												if ($rows_ro_sumber_dana->sumber_dana == 8) {
													$object->getActiveSheet()->setCellValue('D'.(($mulai)), $rows_ro_pagu->pagu);
													$object->getActiveSheet()->setCellValue('I'.(($mulai)), $rows_ro_realisasi->realisasi_keuangan);
													
													if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
														$object->getActiveSheet()->setCellValue('H'.(($mulai)), '=SUM(I'.(($mulai)).'/D'.(($mulai)).')');
													}
													else{
														$object->getActiveSheet()->setCellValue('H'.(($mulai)), '');
													}
												}
												if ($rows_ro_sumber_dana->sumber_dana == 9) {
													$object->getActiveSheet()->setCellValue('E'.(($mulai)), $rows_ro_pagu->pagu);
													$object->getActiveSheet()->setCellValue('K'.(($mulai)), $rows_ro_realisasi->realisasi_keuangan);
													
													if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
														$object->getActiveSheet()->setCellValue('J'.(($mulai)), '=SUM(K'.(($mulai)).'/E'.(($mulai)).')');
													}
													else{
														$object->getActiveSheet()->setCellValue('J'.(($mulai)), '');
													}
												}

												// -------------- Setup ---------------

												$object->getActiveSheet()->getStyle('A'.(($mulai)))->getFont()->setBold(TRUE);
												$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->getAlignment()->setWrapText(true);

												$object->getActiveSheet()->getStyle('A'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
												$object->getActiveSheet()->getStyle('A'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
												$object->getActiveSheet()->getStyle('B'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
												$object->getActiveSheet()->getStyle('B'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
												$object->getActiveSheet()->getStyle('C'.(($mulai)).':K'.(($mulai)))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
												$object->getActiveSheet()->getStyle('C'.(($mulai)).':K'.(($mulai)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

												$object->getActiveSheet()->getStyle('A'.(($mulai)).':K'.(($mulai)))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
											}
										}
									}
								}											
							}

								$mulai++;
							}
						}
					}	
				}
				else{ 
					$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

					foreach ($table_data as $data_table) {
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, (($mulai)+1), $data_table);
						$object->getActiveSheet()->getStyle('A12:K'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						$mulai++;
					}
				}


				// VALUES
				$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
				$object->getActiveSheet()->setCellValue('C'.($mulai), '=SUM(C12:C'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('D'.($mulai), '=SUM(D12:D'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E12:E'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('F'.($mulai), '=G'.$mulai.'/C'.$mulai.'');
				$object->getActiveSheet()->setCellValue('G'.($mulai), '=SUM(G12:G'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('H'.($mulai), '=I'.$mulai.'/D'.$mulai.'');
				$object->getActiveSheet()->setCellValue('I'.($mulai), '=SUM(I12:I'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('J'.($mulai), '=K'.$mulai.'/E'.$mulai.'');
				$object->getActiveSheet()->setCellValue('K'.($mulai), '=SUM(K12:K'.($mulai-1).')');


				// SETUP
				$object->getActiveSheet()->mergeCells('A'.($mulai).':B'.($mulai));

				$object->getActiveSheet()->getStyle('A'.$mulai.':K'.$mulai)->getFont()->setBold(TRUE);

				$object->getActiveSheet()->getStyle('C12:E'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('G12:G'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('I12:I'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('K11:K'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('F12:F'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('H12:H'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('J12:J'.$mulai)->getNumberFormat()->setFormatCode('0.00%');

				$object->getActiveSheet()->getStyle('A'.($mulai).':K'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A'.($mulai).':K'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.($mulai-1).':K'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			}

			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - AP'.$jenis_realisasi.'.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - AP'.$jenis_realisasi.'.xls"');
				$object_writer->save('php://output');
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function nullValue($value){
		if ($this->session->userdata('auth_id') != '') {
			if (is_null($value) || $value == '') {
				$data = 0;
			}
			else{
				$data = $value;
			}
			return $data;
		}
		else{
			redirect(base_url());
		}
	}
}