<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporrebela_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laporrebela_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/realisasi-belanja-langsung/data');
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
			$jenis_realisasi = $this->input->post("jenis_realisasi");
			$tahun = $this->input->post("tahun");
			$bulan = $this->input->post("bulan");

			switch ($jenis_realisasi) {
				case '1':
					$nama_jenis_realisasi = 'REALISASI FISIK DAN KEUANGAN BELANJA LANGSUNG';
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
			if ($skpd != 'all') {
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				foreach ($result_skpd->result() as $rows_skpd) {
					$nama_skpd = $rows_skpd->nama_skpd;
				}
			}
			else{
				$nama_skpd = 'Pemerintah Daerah';
			}

			// -------- PAPER Setup -------- //
			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);
			$object->getActiveSheet()->getSheetView()->setZoomScale(80);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | AP'.$jenis_realisasi.' - '.$nama_skpd.'&R&P');


			if ($jenis_realisasi == 1){
				$object->getActiveSheet()->getColumnDimension('A')->setWidth('3.14');
				$object->getActiveSheet()->getColumnDimension('B')->setWidth('12.43');
				$object->getActiveSheet()->getColumnDimension('C')->setWidth('48.29');
				$object->getActiveSheet()->getColumnDimension('D')->setWidth('14.29');
				$object->getActiveSheet()->getColumnDimension('E')->setWidth('14.29');
				$object->getActiveSheet()->getColumnDimension('F')->setWidth('14.29');
				$object->getActiveSheet()->getColumnDimension('G')->setWidth('14.29');
				$object->getActiveSheet()->getColumnDimension('H')->setWidth('14.29');
				$object->getActiveSheet()->getColumnDimension('I')->setWidth('14.29');
				$object->getActiveSheet()->getColumnDimension('J')->setWidth('12.86');
				$object->getActiveSheet()->getColumnDimension('K')->setWidth('6.57');
				$object->getActiveSheet()->getColumnDimension('L')->setWidth('12.86');
				$object->getActiveSheet()->getColumnDimension('M')->setWidth('6.57');
				$object->getActiveSheet()->getColumnDimension('N')->setWidth('12.86');
				$object->getActiveSheet()->getColumnDimension('O')->setWidth('6.57');
				$object->getActiveSheet()->getColumnDimension('P')->setWidth('12.86');
				$object->getActiveSheet()->getColumnDimension('Q')->setWidth('6.57');
				$object->getActiveSheet()->getColumnDimension('R')->setWidth('12.86');
				$object->getActiveSheet()->getColumnDimension('S')->setWidth('6.57');
				$object->getActiveSheet()->getColumnDimension('T')->setWidth('12.86');
				$object->getActiveSheet()->getColumnDimension('U')->setWidth('6.57');
				
				// -------- Title Form -------- //
				$title_form = 'LAPORAN '.$nama_jenis_realisasi;
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:U1');
				$object->getActiveSheet()->getStyle('A1:U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Subtitle Form -------- //
				$subtitle_form_first = 'PEMERINTAH KABUPATEN PONOROGO';
				$subtitle_form_second = 'TAHUN ANGGARAN '.$tahun;
				$subtitle_form_third = 'KEADAAN SAMPAI DENGAN BULAN '.$nama_bulan;
				$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
				$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
				$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A2:U2');
				$object->getActiveSheet()->mergeCells('A3:U3');
				$object->getActiveSheet()->mergeCells('A4:U4');
				$object->getActiveSheet()->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Nama Organisasi -------- //
				$info_organisasi = 'NAMA ORGANISASI ';
				$nama_organisasi = ': '.$nama_skpd;
				$object->getActiveSheet()->setCellValue('A6', $info_organisasi);
				$object->getActiveSheet()->setCellValue('D6', $nama_organisasi);
				$object->getActiveSheet()->getStyle('A6')->getFont()->setSize(12);
				$object->getActiveSheet()->getStyle('D6')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A6:C6');
				$object->getActiveSheet()->mergeCells('D6:U6');
				$object->getActiveSheet()->getStyle('A6:U6')->getFont()->setBold(TRUE);

				// TABLE HEADER
				$table_title_head_row_first = array("NO", "NO REKENING",  "KEGIATAN", "SUMBER DANA", "", "", "", "", "", "REALISASI FISIK (%) DAN KEUANGAN (Rp)", "", "", "", "", "", "", "", "", "", "", "");
				$table_title_head_row_second = array("APBN", " BLN/PLN", "APBD PROV", "APBD KAB", "DAK", "DBHCT", "APBN", "", "BLN/PLN", "", "APBD PROV", "", "APBD KAB", "", "DAK", "", "DBHCT", "");
				$table_title_head_row_third = array("Keu", "Fisik", "Keu", "Fisik", "Keu", "Fisik", "Keu", "Fisik", "Keu", "Fisik", "Keu", "Fisik");

				$start_column_first = 0;
				foreach ($table_title_head_row_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 8, $thead_first);
					$start_column_first++;
				}
				$start_column_second = 3;
				foreach ($table_title_head_row_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 9, $thead_second);
					$start_column_second++;
				}
				$start_column_third = 9;
				foreach ($table_title_head_row_third as $thead_third) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_third, 10, $thead_third);
					$start_column_third++;
				}

				$object->getActiveSheet()->mergeCells('A8:A10');
				$object->getActiveSheet()->mergeCells('B8:B10');
				$object->getActiveSheet()->mergeCells('C8:C10');
				$object->getActiveSheet()->mergeCells('D8:I8');
				$object->getActiveSheet()->mergeCells('D9:D10');
				$object->getActiveSheet()->mergeCells('E9:E10');
				$object->getActiveSheet()->mergeCells('F9:F10');
				$object->getActiveSheet()->mergeCells('G9:G10');
				$object->getActiveSheet()->mergeCells('H9:H10');
				$object->getActiveSheet()->mergeCells('I9:I10');
				$object->getActiveSheet()->mergeCells('J8:U8');
				$object->getActiveSheet()->mergeCells('J9:K9');
				$object->getActiveSheet()->mergeCells('L9:M9');
				$object->getActiveSheet()->mergeCells('N9:O9');
				$object->getActiveSheet()->mergeCells('P9:Q9');
				$object->getActiveSheet()->mergeCells('R9:S9');
				$object->getActiveSheet()->mergeCells('T9:U9');

				$object->getActiveSheet()->getStyle('A8:U10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A8:U10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A8:U10')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A8:U10')->getFont()->setSize(7);
				$object->getActiveSheet()->getStyle('A8:U10')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A8:U10')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A8:U10')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

 
	 			$no = 1;
	 			$mulai = 11;
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				if ($result_skpd->num_rows() > 0) {
					foreach ($result_skpd->result() as $rows_skpd) {
						$result_sumber_ro = $this->model->getDataSumberROAP1($rows_skpd->id);
						if ($result_sumber_ro->num_rows() > 0) {
							foreach ($result_sumber_ro->result() as $rows_sumber_ro) {
								if ($rows_sumber_ro->sumber_dana == 1 || $rows_sumber_ro->sumber_dana == 2 || $rows_sumber_ro->sumber_dana == 3 || $rows_sumber_ro->sumber_dana == 4 || $rows_sumber_ro->sumber_dana == 5 || $rows_sumber_ro->sumber_dana == 6) {
									$status_sumber_dana = TRUE;
								}
								else{
									$status_sumber_dana = FALSE;
								}
							}

							if ($status_sumber_dana == TRUE) {

								// -------------- Program ---------------
									
									$result_program = $this->model->getDataProgramUniqueAP1($rows_skpd->kd_skpd);
									if ($result_program->num_rows() > 0) {
										foreach ($result_program->result() as $rows_program) {
											
											// -------------- Value ---------------

											$object->getActiveSheet()->setCellValue('A'.($mulai++), $rows_program->kd_gabungan);
											$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_program->keterangan_program);

											$result_program_sumber_dana = $this->model->getDataSumberDanaProgram($rows_skpd->id, $rows_program->id);
											foreach ($result_program_sumber_dana->result() as $rows_program_sumber_dana) {
												$result_program_pagu = $this->model->getDataPaguProgram($rows_skpd->id, $rows_program->id, $rows_program_sumber_dana->sumber_dana);
												foreach ($result_program_pagu->result() as $rows_program_pagu) {
													$result_program_realisasi = $this->model->getDataRealisasiProgram($rows_skpd->id, $rows_program->id, $rows_program_sumber_dana->sumber_dana);
													foreach ($result_program_realisasi->result() as $rows_program_realisasi) {
														if ($rows_program_sumber_dana->sumber_dana == 1) {
															$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('N'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), '=SUM(N'.(($mulai)-1).'/F'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), '');
															}
														}
														if ($rows_program_sumber_dana->sumber_dana == 2) {
															$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('P'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), '=SUM(P'.(($mulai)-1).'/G'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), '');
															}
														}
														if ($rows_program_sumber_dana->sumber_dana == 3) {
															$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), '=SUM(J'.(($mulai)-1).'/D'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), '');
															}
														}
														if ($rows_program_sumber_dana->sumber_dana == 4) {
															$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('L'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), '=SUM(L'.(($mulai)-1).'/E'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), '');
															}
														}
														if ($rows_program_sumber_dana->sumber_dana == 5) {
															$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('R'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), '=SUM(R'.(($mulai)-1).'/H'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), '');
															}
														}
														if ($rows_program_sumber_dana->sumber_dana == 6) {
															$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('T'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('U'.(($mulai)-1), '=SUM(T'.(($mulai)-1).'/I'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('U'.(($mulai)-1), '');
															}
														}
													}
												}

											}

											// -------------- Setup ---------------

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->getFont()->setBold(TRUE);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setWrapText(true);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
											$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
														

											// -------------- Kegiatan ---------------
														
											$result_kegiatan = $this->model->getDataKegiatanUnique($rows_skpd->kd_skpd, $rows_program->id);
											foreach ($result_kegiatan->result() as $rows_kegiatan) {
												$object->getActiveSheet()->setCellValue('A'.($mulai++), $rows_kegiatan->kd_gabungan);
												$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_kegiatan->keterangan_kegiatan);

												$result_kegiatan_sumber_dana = $this->model->getDataSumberDanaKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id);
												foreach ($result_kegiatan_sumber_dana->result() as $rows_kegiatan_sumber_dana) {
													$result_kegiatan_pagu = $this->model->getDataPaguKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_kegiatan_sumber_dana->sumber_dana);
													foreach ($result_kegiatan_pagu->result() as $rows_kegiatan_pagu) {
														$result_kegiatan_realisasi = $this->model->getDataRealisasiKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_kegiatan_sumber_dana->sumber_dana);
														foreach ($result_kegiatan_realisasi->result() as $rows_kegiatan_realisasi) {

															if ($rows_kegiatan_sumber_dana->sumber_dana == 1) {
																$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('N'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), '=SUM(N'.(($mulai)-1).'/F'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('o'.(($mulai)-1), '');
																}
															}
															if ($rows_kegiatan_sumber_dana->sumber_dana == 2) {
																$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('P'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), '=SUM(P'.(($mulai)-1).'/G'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), '');
																}
															}
															if ($rows_kegiatan_sumber_dana->sumber_dana == 3) {
																$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), '=SUM(J'.(($mulai)-1).'/D'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), '');
																}
															}
															if ($rows_kegiatan_sumber_dana->sumber_dana == 4) {
																$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('L'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), '=SUM(L'.(($mulai)-1).'/E'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), '');
																}
															}
															if ($rows_kegiatan_sumber_dana->sumber_dana == 5) {
																$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('R'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), '=SUM(R'.(($mulai)-1).'/H'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), '');
																}
															}
															if ($rows_kegiatan_sumber_dana->sumber_dana == 6) {
																$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('T'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('U'.(($mulai)-1), '=SUM(T'.(($mulai)-1).'/I'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('U'.(($mulai)-1), '');
																}
															}
														}
													}

													// -------------- Setup ---------------

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->getFont()->setBold(TRUE);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setWrapText(true);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
													$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
												}


												// -------------- Rincian Obyek ---------------
												
												$result_ro = $this->model->getDataRincianObyekUnique($rows_skpd->kd_skpd, $rows_kegiatan->kd_gabungan);
												foreach ($result_ro->result() as $rows_ro) {
													$object->getActiveSheet()->setCellValue('A'.($mulai++), $no++);
													$object->getActiveSheet()->setCellValue('B'.(($mulai)-1), $rows_ro->kd_rekening);
													$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_ro->nama_rekening);

													$result_ro_sumber_dana = $this->model->getDataSumberDanaRO($rows_ro->id);
													foreach ($result_ro_sumber_dana->result() as $rows_ro_sumber_dana) {
														$result_ro_pagu = $this->model->getDataPaguRO($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_ro->id);
														foreach ($result_ro_pagu->result() as $rows_ro_pagu) {
															$result_ro_realisasi = $this->model->getDataRealisasiRO($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_ro->id);
															foreach ($result_ro_realisasi->result() as $rows_ro_realisasi) {

																if ($rows_ro_sumber_dana->sumber_dana == 1) {
																	$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('N'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), '=SUM(N'.(($mulai)-1).'/F'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), '');
																	}
																}
																if ($rows_ro_sumber_dana->sumber_dana == 2) {
																	$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('P'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), '=SUM(P'.(($mulai)-1).'/G'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), '');
																	}

																}
																if ($rows_ro_sumber_dana->sumber_dana == 3) {
																	$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), '=SUM(J'.(($mulai)-1).'/D'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), '');
																	}
																}
																if ($rows_ro_sumber_dana->sumber_dana == 4) {
																	$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('L'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), '=SUM(L'.(($mulai)-1).'/E'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), '');
																	}
																}
																if ($rows_ro_sumber_dana->sumber_dana == 5) {
																	$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('R'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), '=SUM(R'.(($mulai)-1).'/H'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), '');
																	}
																}
																if ($rows_ro_sumber_dana->sumber_dana == 6) {
																	$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('T'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('U'.(($mulai)-1), '=SUM(T'.(($mulai)-1).'/I'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('U'.(($mulai)-1), '');
																	}
																}

																// -------------- Setup ---------------

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getFont()->setBold(TRUE);

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setWrapText(true);

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
																$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
																$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
																$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
																$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
																$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':U'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':U'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
															}
														}
													}
												}
											}
											$mulai++;
										}
									}
									else{
										$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

										foreach ($table_data as $data_table) {
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
											$object->getActiveSheet()->getStyle('A11:U'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
											$mulai++;
										}
									}
							}
							else{
								$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

								foreach ($table_data as $data_table) {
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
									$object->getActiveSheet()->getStyle('A11:U'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
									$mulai++;
								}
							}
						}
						else{
							$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

								foreach ($table_data as $data_table) {
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
									$object->getActiveSheet()->getStyle('A11:U'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
									$mulai++;
								}
						}
					}
				}
				else{
					$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

					foreach ($table_data as $data_table) {
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
						$object->getActiveSheet()->getStyle('A11:U'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						$mulai++;
					}
				}

				// VALUES
				$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
				$object->getActiveSheet()->setCellValue('D'.($mulai), '=SUM(D11:D'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E11:E'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('F'.($mulai), '=SUM(F11:F'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('G'.($mulai), '=SUM(G11:G'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('H'.($mulai), '=SUM(H11:H'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('I'.($mulai), '=SUM(I11:I'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('J'.($mulai), '=SUM(J11:J'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('K'.($mulai), '=J'.$mulai.'/D'.$mulai.'');
				$object->getActiveSheet()->setCellValue('L'.($mulai), '=SUM(L11:L'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('M'.($mulai), '=L'.$mulai.'/E'.$mulai.'');
				$object->getActiveSheet()->setCellValue('N'.($mulai), '=SUM(N11:N'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('O'.($mulai), '=N'.$mulai.'/F'.$mulai.'');
				$object->getActiveSheet()->setCellValue('P'.($mulai), '=SUM(P11:P'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('Q'.($mulai), '=P'.$mulai.'/G'.$mulai.'');
				$object->getActiveSheet()->setCellValue('R'.($mulai), '=SUM(R11:R'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('S'.($mulai), '=R'.$mulai.'/H'.$mulai.'');
				$object->getActiveSheet()->setCellValue('T'.($mulai), '=SUM(T11:T'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('U'.($mulai), '=T'.$mulai.'/I'.$mulai.'');

				// SETUP
				$object->getActiveSheet()->mergeCells('A'.($mulai).':C'.($mulai));

				$object->getActiveSheet()->getStyle('A'.$mulai.':U'.$mulai)->getFont()->setBold(TRUE);

				$object->getActiveSheet()->getStyle('D11:I'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('J11:J'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('L11:L'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('N11:N'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('P11:P'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('R11:R'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('T11:T'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('K11:K'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('M11:M'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('O11:O'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('Q11:Q'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('S11:S'.$mulai)->getNumberFormat()->setFormatCode('0.00%');
				$object->getActiveSheet()->getStyle('U11:U'.$mulai)->getNumberFormat()->setFormatCode('0.00%');

				$object->getActiveSheet()->getStyle('A'.($mulai).':U'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A'.($mulai).':U'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.($mulai).':U'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));


			}
			if ($jenis_realisasi == 2){
				// -------- Title Form -------- //
				$title_form = 'PEMERINTAH KABUPATEN PONOROGO';
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:S1');
				$object->getActiveSheet()->getStyle('A1:S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Subtitle Form -------- //
				$subtitle_form_first = 'LAPORAN '.$nama_jenis_realisasi;
				$subtitle_form_second = 'TAHUN ANGGARAN '.$tahun;
				$subtitle_form_third = 'KEADAAN SAMPAI DENGAN BULAN '.$nama_bulan;
				$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
				$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
				$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A2:S2');
				$object->getActiveSheet()->mergeCells('A3:S3');
				$object->getActiveSheet()->mergeCells('A4:S4');
				$object->getActiveSheet()->getStyle('A2:S4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Nama Organisasi -------- //
				$info_organisasi = 'NAMA ORGANISASI ';
				$nama_organisasi = ': '.$nama_skpd;
				$object->getActiveSheet()->setCellValue('A6', $info_organisasi);
				$object->getActiveSheet()->setCellValue('D6', $nama_organisasi);
				$object->getActiveSheet()->getStyle('A6')->getFont()->setSize(12);
				$object->getActiveSheet()->getStyle('D6')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A6:C6');
				$object->getActiveSheet()->mergeCells('D6:S6');
				$object->getActiveSheet()->getStyle('A6:S6')->getFont()->setBold(TRUE);

				// TABLE HEADER
				$table_title_head_row_first = array("NO", "KODE REKENING", "PROGRAM / KEGIATAN", "SUMBER DANA", "PAGU", "NILAI KONTRAK", "TGL / NO KONTRAK", "PENYEDIA BARANG/JASA", "WAKTU PELAKSANAAN", "", "REALISASI", "", "SISTEM PENGADAAN", "", "", "", "", "", "");
				$table_title_head_row_second = array("MULAI", "SELESAI", "KEUANGAN", "FISIK", "TU", "TC", "PGL", "PJL", "SLS", "EP", "SWA");

				$start_column_first = 0;
				foreach ($table_title_head_row_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 8, $thead_first);
					$start_column_first++;
				}
				$start_column_second = 8;
				foreach ($table_title_head_row_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 9, $thead_second);
					$start_column_second++;
				}

				$object->getActiveSheet()->mergeCells('A8:A9');
				$object->getActiveSheet()->mergeCells('B8:B9');
				$object->getActiveSheet()->mergeCells('C8:c9');
				$object->getActiveSheet()->mergeCells('D8:D9');
				$object->getActiveSheet()->mergeCells('E8:E9');
				$object->getActiveSheet()->mergeCells('F8:F9');
				$object->getActiveSheet()->mergeCells('G8:G9');
				$object->getActiveSheet()->mergeCells('H8:H9');
				$object->getActiveSheet()->mergeCells('I8:J8');
				$object->getActiveSheet()->mergeCells('K8:L8');
				$object->getActiveSheet()->mergeCells('M8:S8');
				$object->getActiveSheet()->getStyle('A8:S9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A8:S9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A8:S9')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A8:S9')->getFont()->setSize(7);
				$object->getActiveSheet()->getStyle('A8:S9')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A8:S9')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A8:S9')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

				$no = 1;
	 			$mulai = 10;
	 			$sumber_dana = ["", "APBD","APBDP","APBN","APBNP","BLU","BLUD","BUMD","BUMN","PHLN","PNBP","Lainnya"];
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				if ($result_skpd->num_rows() > 0) {
					foreach ($result_skpd->result() as $rows_skpd) {
					
						// -------------- Program ---------------
									
						$result_program = $this->model->getDataProgramUniqueAP2($rows_skpd->kd_skpd);
						if ($result_program->num_rows() > 0) {
							foreach ($result_program->result() as $rows_program) {
								$result_pagu_program = $this->model->getDataPaguProgramKonstruksi($rows_skpd->id, $rows_program->id);
								foreach ($result_pagu_program->result() as $rows_pagu_program) {

									// -------------- Value ---------------

									$object->getActiveSheet()->setCellValue('A'.($mulai++), $rows_program->kd_gabungan);
									$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_program->keterangan_program);
									$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_pagu_program->pagu);

									// -------------- Setup ---------------

									$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->getFont()->setBold(TRUE);

									$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setWrapText(true);

									$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

									$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));

									// -------------- Kegiatan ---------------

									$result_kegiatan = $this->model->getDataKegiatanUniqueKonstruksi($rows_skpd->kd_skpd, $rows_program->id);
									foreach ($result_kegiatan->result() as $rows_kegiatan) {
										$result_pagu_kegiatan = $this->model->getDataPaguKegiatanKonstruksi($rows_skpd->id, $rows_program->id, $rows_kegiatan->id);
										foreach ($result_pagu_kegiatan->result() as $rows_pagu_kegiatan) {

											// -------------- Value ---------------

											$object->getActiveSheet()->setCellValue('A'.($mulai++), $rows_kegiatan->kd_gabungan);
											$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_kegiatan->keterangan_kegiatan);
											$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_pagu_kegiatan->pagu);

											// -------------- Setup ---------------

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->getFont()->setBold(TRUE);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setWrapText(true);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
											$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));

											// -------------- Realisasi RUP ---------------

											$result_rup = $this->model->getDataRUP($rows_skpd->id, $rows_program->id, $rows_kegiatan->id);
											foreach ($result_rup->result() as $rows_rup) {
												$result_rekening_rup = $this->model->getDataRincianObyekRUP($rows_rup->id_rincian_obyek);
												foreach ($result_rekening_rup->result() as $rows_rekening_rup) {

													// -------------- Value ---------------

													$object->getActiveSheet()->setCellValue('A'.($mulai++), $no++);
													$object->getActiveSheet()->setCellValue('B'.(($mulai)-1), $rows_rekening_rup->kd_rekening);
													$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_rup->nama_paket);
													$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $sumber_dana[$rows_rup->sumber_dana]);
													$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_rup->pagu_paket);

													if ($rows_rup->metode_pemilihan == 1) {
														$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), 'X');
													}
													if ($rows_rup->metode_pemilihan != 1) {
														$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), '-');
													}
													if ($rows_rup->metode_pemilihan == 2) {
														$object->getActiveSheet()->setCellValue('N'.(($mulai)-1), 'X');
													}
													if ($rows_rup->metode_pemilihan != 2) {
														$object->getActiveSheet()->setCellValue('N'.(($mulai)-1), '-');
													}
													if ($rows_rup->metode_pemilihan == 3) {
														$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), 'X');
													
													}
													if ($rows_rup->metode_pemilihan != 3) {
														$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), '-');
													
													}
													if ($rows_rup->metode_pemilihan == 4) {
														$object->getActiveSheet()->setCellValue('P'.(($mulai)-1), 'X');
													
													}
													if ($rows_rup->metode_pemilihan != 4) {
														$object->getActiveSheet()->setCellValue('P'.(($mulai)-1), '-');
													
													}
													if ($rows_rup->metode_pemilihan == 5) {
														$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), 'X');
													
													}
													if ($rows_rup->metode_pemilihan != 5) {
														$object->getActiveSheet()->setCellValue('Q'.(($mulai)-1), '-');
													
													}
													if ($rows_rup->metode_pemilihan == 6) {
														$object->getActiveSheet()->setCellValue('R'.(($mulai)-1), 'X');
													
													}
													if ($rows_rup->metode_pemilihan != 6) {
														$object->getActiveSheet()->setCellValue('R'.(($mulai)-1), '-');
													
													}
													if ($rows_rup->metode_pemilihan == '' || is_null($rows_rup->metode_pemilihan)) {
														$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), 'X');
													}
													if ($rows_rup->metode_pemilihan != '' || !is_null($rows_rup->metode_pemilihan)) {
														$object->getActiveSheet()->setCellValue('S'.(($mulai)-1), '-');
													}	
													

													$result_realisasi_rup = $this->model->getDataRealisasiRUP($rows_rup->id);
													if ($result_realisasi_rup->num_rows() > 0) {
														foreach ($result_realisasi_rup->result() as $rows_realisasi_rup) {
															$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), $rows_realisasi_rup->realisasi_keuangan);

															if ($rows_realisasi_rup->tanggal_kontrak != '' && $rows_realisasi_rup->nomor_kontrak) {
																$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_realisasi_rup->tanggal_kontrak." / ".$rows_realisasi_rup->nomor_kontrak);
															}

															$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), $rows_realisasi_rup->nama_pemenang);
															$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_realisasi_rup->tanggal_spmk);
															$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), $rows_realisasi_rup->tanggal_surat_serah_terima);
															$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), $rows_realisasi_rup->realisasi_keuangan);

															if ($rows_realisasi_rup->realisasi_keuangan != '') {
																$object->getActiveSheet()->setCellValue('L'.(($mulai)-1), '=SUM(K'.(($mulai)-1).'/E'.(($mulai)-1).')');
															}
														}
													}

													// -------------- Setup ---------------

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':B'.(($mulai)-1))->getFont()->setBold(TRUE);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setWrapText(true);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
													$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
													$object->getActiveSheet()->getStyle('C'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('D'.(($mulai)-1).':S'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':S'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
												}
											}
										}
									}
								}
								$mulai++;
							}
						}
						else{
							$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

							foreach ($table_data as $data_table) {
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
								$object->getActiveSheet()->getStyle('A10:U'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
								$mulai++;
							}
						}
					}
				}
				else{
					$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

					foreach ($table_data as $data_table) {
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
						$object->getActiveSheet()->getStyle('A10:S'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						$mulai++;
					}
				}


				// VALUES
				$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
				$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E10:E'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('F'.($mulai), '=SUM(F10:F'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('K'.($mulai), '=SUM(K10:K'.($mulai-1).')');
				$object->getActiveSheet()->setCellValue('L'.($mulai), '=K'.($mulai).'/E'.($mulai).'');


				// SETUP
				$object->getActiveSheet()->mergeCells('A'.($mulai).':D'.($mulai));

				$object->getActiveSheet()->getStyle('A'.$mulai.':S'.$mulai)->getFont()->setBold(TRUE);

				$object->getActiveSheet()->getStyle('E10:E'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('F10:F'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('K10:K'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('L10:L'.$mulai)->getNumberFormat()->setFormatCode('0.00%');

				$object->getActiveSheet()->getStyle('A'.($mulai).':S'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A'.($mulai).':S'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.($mulai).':S'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			}
			if ($jenis_realisasi == 3){
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

				// -------- Nama Organisasi -------- //
				$info_organisasi = 'NAMA ORGANISASI ';
				$nama_organisasi = ': '.$nama_skpd;
				$object->getActiveSheet()->setCellValue('A7', $info_organisasi);
				$object->getActiveSheet()->setCellValue('C7', $nama_organisasi);
				$object->getActiveSheet()->getStyle('A7')->getFont()->setSize(12);
				$object->getActiveSheet()->getStyle('C7')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A7:B7');
				$object->getActiveSheet()->mergeCells('C7:K7');
				$object->getActiveSheet()->getStyle('A7:K7')->getFont()->setBold(TRUE);

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
	 			$mulai = 12;
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				if ($result_skpd->num_rows() > 0) {
					foreach ($result_skpd->result() as $rows_skpd) {
						$result_sumber_ro = $this->model->getDataSumberROAP3($rows_skpd->id);
						if ($result_sumber_ro->num_rows() > 0) {
							foreach ($result_sumber_ro->result() as $rows_sumber_ro) {
								if ($rows_sumber_ro->sumber_dana == 7 || $rows_sumber_ro->sumber_dana == 8 || $rows_sumber_ro->sumber_dana == 9) {
									$status_sumber_dana = TRUE;
								}
								else{
									$status_sumber_dana = FALSE;
								}
							}

							if ($status_sumber_dana == TRUE) {

								// -------------- Program ---------------
									
									$result_program = $this->model->getDataProgramUniqueAP3($rows_skpd->kd_skpd);
									if ($result_program->num_rows() > 0) {
										foreach ($result_program->result() as $rows_program) {
											
											// -------------- Value ---------------

											$object->getActiveSheet()->setCellValue('A'.($mulai++), $rows_program->kd_gabungan);
											$object->getActiveSheet()->setCellValue('B'.(($mulai)-1), $rows_program->keterangan_program);

											$result_program_sumber_dana = $this->model->getDataSumberDanaProgram($rows_skpd->id, $rows_program->id);
											foreach ($result_program_sumber_dana->result() as $rows_program_sumber_dana) {
												$result_program_pagu = $this->model->getDataPaguProgram($rows_skpd->id, $rows_program->id, $rows_program_sumber_dana->sumber_dana);
												foreach ($result_program_pagu->result() as $rows_program_pagu) {
													$result_program_realisasi = $this->model->getDataRealisasiProgram($rows_skpd->id, $rows_program->id, $rows_program_sumber_dana->sumber_dana);
													foreach ($result_program_realisasi->result() as $rows_program_realisasi) {
														if ($rows_program_sumber_dana->sumber_dana == 7) {
															$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), '=SUM(G'.(($mulai)-1).'/C'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), '');
															}
														}
														if ($rows_program_sumber_dana->sumber_dana == 8) {
															$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), '=SUM(I'.(($mulai)-1).'/D'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), '');
															}
														}
														if ($rows_program_sumber_dana->sumber_dana == 9) {
															$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_program_pagu->pagu);
															$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), $rows_program_realisasi->realisasi_keuangan);
															if (!is_null($rows_program_pagu->pagu) && !is_null($rows_program_realisasi->realisasi_keuangan)) {
																$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), '=SUM(K'.(($mulai)-1).'/E'.(($mulai)-1).')');
															}
															else{
																$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), '');
															}
														}
													}
												}

											}

											// -------------- Setup ---------------

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->getFont()->setBold(TRUE);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setWrapText(true);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
											$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('C'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('C'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

											$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
														

											// -------------- Kegiatan ---------------
														
											$result_kegiatan = $this->model->getDataKegiatanUnique($rows_skpd->kd_skpd, $rows_program->id);
											foreach ($result_kegiatan->result() as $rows_kegiatan) {
												$object->getActiveSheet()->setCellValue('A'.($mulai++), $rows_kegiatan->kd_gabungan);
												$object->getActiveSheet()->setCellValue('B'.(($mulai)-1), $rows_kegiatan->keterangan_kegiatan);

												$result_kegiatan_sumber_dana = $this->model->getDataSumberDanaKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id);
												foreach ($result_kegiatan_sumber_dana->result() as $rows_kegiatan_sumber_dana) {
													$result_kegiatan_pagu = $this->model->getDataPaguKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_kegiatan_sumber_dana->sumber_dana);
													foreach ($result_kegiatan_pagu->result() as $rows_kegiatan_pagu) {
														$result_kegiatan_realisasi = $this->model->getDataRealisasiKegiatan($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_kegiatan_sumber_dana->sumber_dana);
														foreach ($result_kegiatan_realisasi->result() as $rows_kegiatan_realisasi) {

															if ($rows_kegiatan_sumber_dana->sumber_dana == 7) {
																$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), '=SUM(G'.(($mulai)-1).'/C'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), '');
																}
															}
															if ($rows_kegiatan_sumber_dana->sumber_dana == 8) {
																$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), '=SUM(I'.(($mulai)-1).'/D'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), '');
																}
															}
															if ($rows_kegiatan_sumber_dana->sumber_dana == 9) {
																$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_kegiatan_pagu->pagu);
																$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), $rows_kegiatan_realisasi->realisasi_keuangan);
																if (!is_null($rows_kegiatan_pagu->pagu) && !is_null($rows_kegiatan_realisasi->realisasi_keuangan)) {
																	$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), '=SUM(K'.(($mulai)-1).'/E'.(($mulai)-1).')');
																}
																else{
																	$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), '');
																}
															}
														}
													}

													// -------------- Setup ---------------

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->getFont()->setBold(TRUE);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setWrapText(true);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
													$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('C'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('C'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

													$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
												}


												// -------------- Rincian Obyek ---------------
												
												$result_ro = $this->model->getDataRincianObyekUnique($rows_skpd->kd_skpd, $rows_kegiatan->kd_gabungan);
												foreach ($result_ro->result() as $rows_ro) {
													$object->getActiveSheet()->setCellValue('A'.($mulai++), $no++);
													$object->getActiveSheet()->setCellValue('B'.(($mulai)-1), "[".$rows_ro->kd_rekening."] - ".$rows_ro->nama_rekening);

													$result_ro_sumber_dana = $this->model->getDataSumberDanaRO($rows_ro->id);
													foreach ($result_ro_sumber_dana->result() as $rows_ro_sumber_dana) {
														$result_ro_pagu = $this->model->getDataPaguRO($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_ro->id);
														foreach ($result_ro_pagu->result() as $rows_ro_pagu) {
															$result_ro_realisasi = $this->model->getDataRealisasiRO($rows_skpd->id, $rows_program->id, $rows_kegiatan->id, $rows_ro->id);
															foreach ($result_ro_realisasi->result() as $rows_ro_realisasi) {

																if ($rows_ro_sumber_dana->sumber_dana == 7) {
																	$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), '=SUM(G'.(($mulai)-1).'/C'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), '');
																	}
																}
																if ($rows_ro_sumber_dana->sumber_dana == 8) {
																	$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), '=SUM(I'.(($mulai)-1).'/D'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), '');
																	}

																}
																if ($rows_ro_sumber_dana->sumber_dana == 9) {
																	$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_ro_pagu->pagu);
																	$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), $rows_ro_realisasi->realisasi_keuangan);
																	if (!is_null($rows_ro_pagu->pagu) && !is_null($rows_ro_realisasi->realisasi_keuangan)) {
																		$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), '=SUM(K'.(($mulai)-1).'/E'.(($mulai)-1).')');
																	}
																	else{
																		$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), '');
																	}
																}

																// -------------- Setup ---------------

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getFont()->setBold(TRUE);

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setWrapText(true);

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
																$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
																$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
																$object->getActiveSheet()->getStyle('C'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																$object->getActiveSheet()->getStyle('C'.(($mulai)-1).':K'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

																$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':K'.(($mulai)-1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
															}
														}
													}
												}
											}
											$mulai++;
										}
									}
									else{
										$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

										foreach ($table_data as $data_table) {
											$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
											$object->getActiveSheet()->getStyle('A11:K'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
											$mulai++;
										}
									}
							}
							else{
								$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

								foreach ($table_data as $data_table) {
									$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
									$object->getActiveSheet()->getStyle('A11:K'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
									$mulai++;
								}
							}
						}
						else{
							$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

							foreach ($table_data as $data_table) {
								$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
								$object->getActiveSheet()->getStyle('A11:K'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
								$mulai++;
							}
						}
					}
				}
				else{
					$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

					foreach ($table_data as $data_table) {
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
						$object->getActiveSheet()->getStyle('A11:K'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
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

				$object->getActiveSheet()->getStyle('A'.($mulai).':K'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			}

			
			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Laporan Realisasi - AP'.$jenis_realisasi.'.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Laporan Realisasi - AP'.$jenis_realisasi.'.xls"');
				$object_writer->save('php://output');
			}
		}
	}
}