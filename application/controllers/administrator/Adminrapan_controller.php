<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminrapan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/adminrapan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/rencana-pengadaan/data');
		}
		else{
			redirect(base_url());
		}
	}

	public function getPrintData(){
		if ($this->session->userdata("auth_id") != "") {
			$jenis_pengadaan = $this->input->post("jenis_pengadaan");
			$bulan = $this->input->post("bulan");
			$tahun = $this->input->post("tahun");
			$tanggal_cetak = $this->input->post("tanggal_cetak");

			switch ($jenis_pengadaan) {
				case '1':
					$nama_jenis_pengadaan = 'BARANG';
				break;
				case '2':
					$nama_jenis_pengadaan = 'KONSTRUKSI';
				break;
				case '3':
					$nama_jenis_pengadaan = 'KONSULTASI';
				break;
				case '4':
					$nama_jenis_pengadaan = 'JASA LAINNYA';
				break;
				
				default:
					$nama_jenis_pengadaan = 'BARANG';
				break;
			}

			$this->load->library("Excel");
			$object =  new PHPExcel();
			$object->setActiveSheetIndex(0);

			// -------- PAPER Setup -------- //
			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);
			$object->getActiveSheet()->getSheetView()->setZoomScale(80);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | Rekap RP - '.$nama_jenis_pengadaan.'&R&P');
			$object->getDefaultStyle()->getFont()->setName('Times New Roman');

			$object->getActiveSheet()->getColumnDimension('A')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(38);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('I')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('J')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('K')->setWidth(8.43);
			$object->getActiveSheet()->getColumnDimension('L')->setWidth(8.43);

			// -------- Title Form -------- //
			$title_form = 'REKAPITULASI';
			$object->getActiveSheet()->setCellValue('A1', $title_form);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A1:L1');
			$object->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Subtitle Form -------- //
			$subtitle_form_first = 'LAPORAN RENCANA PENGADAAN BARANG/JASA PEMERINTAH';
			$subtitle_form_second = 'ORGANISASI PERANGKAT DAERAH DI LINGKUNGAN PEMERINTAH KABUPATEN PONOROGO';
			$subtitle_form_third = 'TAHUN ANGGARAN '.$tahun;
			$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
			$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
			$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
			$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setSize(12);
			$object->getActiveSheet()->mergeCells('A2:L2');
			$object->getActiveSheet()->mergeCells('A3:L3');
			$object->getActiveSheet()->mergeCells('A4:L4');
			$object->getActiveSheet()->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Jenis Pengadaan -------- //
			$info_organisasi = 'JENIS PENGADAAN';
			$nama_organisasi = ' : '.$nama_jenis_pengadaan;
			$object->getActiveSheet()->setCellValue('A6', $info_organisasi.$nama_organisasi);
			$object->getActiveSheet()->getStyle('A6')->getFont()->setSize(12);
			$object->getActiveSheet()->mergeCells('A6:L6');
			$object->getActiveSheet()->getStyle('A6:L6')->getFont()->setBold(TRUE);

			// TABLE HEADER
			$table_title_head_row_first = array("NO", "ORGANISASI PERANGKAT DAERAH", "DANA APBD", "PAGU RUP", "METODE PEMILIHAN PENGADAAN BARANG/JASA PEMERINTAH", "", "", "", "", "", "", "", "KETERANGAN");
			$table_title_head_row_second = array("SWAKELOLA", "PEMILIHAN PENYEDIA BARANG", "", "", "", "", "", "JUMLAH");
			$table_title_head_row_third = array("METODE PENGADAAN", "", "", "", "", "");
			$table_title_head_row_fourth = array("TENDER UMUM", "TENDER CEPAT", "PENUNJUKAN LANGSUNG", "PENGADAAN LANGSUNG", "SELEKSI", "E-PURCHASING"); 

			$start_column_first = 0;
			foreach ($table_title_head_row_first as $thead_first) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 8, $thead_first);
				$start_column_first++;
			}
			$start_column_second = 4;
			foreach ($table_title_head_row_second as $thead_second) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 9, $thead_second);
				$start_column_second++;
			}
			$start_column_third = 5;
			foreach ($table_title_head_row_third as $thead_third) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_third, 10, $thead_third);
				$start_column_third++;
			}
			$start_column_fourth = 5;
			foreach ($table_title_head_row_fourth as $thead_fourth) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_fourth, 11, $thead_fourth);
				$start_column_fourth++;
			}

			// SETUP
			$object->getActiveSheet()->mergeCells('A8:A11');
			$object->getActiveSheet()->mergeCells('B8:B11');
			$object->getActiveSheet()->mergeCells('C8:C11');
			$object->getActiveSheet()->mergeCells('D8:D11');
			$object->getActiveSheet()->mergeCells('E9:E11');

			$object->getActiveSheet()->mergeCells('E8:L8');
			$object->getActiveSheet()->mergeCells('M8:M11');
			$object->getActiveSheet()->mergeCells('E9:E11');
			$object->getActiveSheet()->mergeCells('L9:L11');
			$object->getActiveSheet()->mergeCells('F9:K9');
			$object->getActiveSheet()->mergeCells('F10:K10');

			$object->getActiveSheet()->getStyle('A8:M11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A8:M11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('A8:M11')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A8:M11')->getFont()->setSize(7);
			$object->getActiveSheet()->getStyle('A8:M11')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A8:M11')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A8:M11')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
			

			// VALUE TABLE
			$result_skpd = $this->model->getDataSKPD();
			$no = 1;
			$mulai = 12;
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_pagu = $this->model->getDataPaguSKPD($rows_skpd->kd_skpd); 
				foreach ($result_pagu->result() as $rows_pagu) {
					$result_pagu_rup = $this->model->getDataPaguRUPSKPD($rows_skpd->kd_skpd, $jenis_pengadaan, $bulan);
					foreach ($result_pagu_rup->result() as $rows_pagu_rup) {
						$result_swakelola = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $jenis_pengadaan, NULL, $bulan);
						foreach ($result_swakelola->result() as $rows_swakelola) {
							$result_ep = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $jenis_pengadaan, 1, $bulan);
							foreach ($result_ep->result() as $rows_ep) {
								$result_tu = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $jenis_pengadaan, 2, $bulan);
								foreach ($result_tu->result() as $rows_tu) {
									$result_tc = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $jenis_pengadaan, 3, $bulan);
									foreach ($result_tc->result() as $rows_tc) {
										$result_pgl = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $jenis_pengadaan, 4, $bulan);
										foreach ($result_pgl->result() as $rows_pgl) {
											$result_pjl = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $jenis_pengadaan, 5, $bulan);
											foreach ($result_pjl->result() as $rows_pjl) {
												$result_sls = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $jenis_pengadaan, 6, $bulan);
												foreach ($result_sls->result() as $rows_sls) {

													if ($rows_pagu_rup->pagu_paket != NULL) {
														$pagu_paket_rup = $rows_pagu_rup->pagu_paket;
													}
													else{
														$pagu_paket_rup = 0;
													}
													// Values

													$object->getActiveSheet()->setCellValue('A'.$mulai, $no++);
													$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_skpd->nama_skpd);
													$object->getActiveSheet()->setCellValue('C'.$mulai, $this->nullValue($rows_pagu->pagu_paket));
													$object->getActiveSheet()->setCellValue('D'.$mulai, $this->nullValue($pagu_paket_rup));
													$object->getActiveSheet()->setCellValue('E'.$mulai, $this->nullValue($rows_swakelola->paket));
													$object->getActiveSheet()->setCellValue('F'.$mulai, $this->nullValue($rows_tu->paket));
													$object->getActiveSheet()->setCellValue('G'.$mulai, $this->nullValue($rows_tc->paket));
													$object->getActiveSheet()->setCellValue('H'.$mulai, $this->nullValue($rows_pjl->paket));
													$object->getActiveSheet()->setCellValue('I'.$mulai, $this->nullValue($rows_pgl->paket));
													$object->getActiveSheet()->setCellValue('J'.$mulai, $this->nullValue($rows_sls->paket));
													$object->getActiveSheet()->setCellValue('K'.$mulai, $this->nullValue($rows_ep->paket));
													$object->getActiveSheet()->setCellValue('L'.$mulai, "=sum(E".($mulai).":K".($mulai).")");
													$object->getActiveSheet()->setCellValue('M'.$mulai, "");


													// SETUP
													$object->getActiveSheet()->getStyle('A'.($mulai).':M'.($mulai))->getAlignment()->setWrapText(true);
													$object->getActiveSheet()->getStyle('A'.($mulai).':M'.($mulai))->getFont()->setSize(8);

													$object->getActiveSheet()->getStyle('C'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

													$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
													$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('C'.($mulai).':L'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
													$object->getActiveSheet()->getStyle('C'.($mulai).':L'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
													$object->getActiveSheet()->getStyle('M'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
													$object->getActiveSheet()->getStyle('M'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

													$object->getActiveSheet()->getStyle('A'.($mulai).':M'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
												}
											}
										}
									}
								}	
							}
						}
					}
				}
				$mulai++;
			}

			// Values
			$result_total_pagu = $this->model->getDataPaguSKPD('all');
			foreach ($result_total_pagu->result() as $rows_total_pagu) {
				$total_pagu = $rows_total_pagu->pagu_paket;
			}
			$result_total_pagu_rup = $this->model->getDataPaguRUPSKPD('all', $jenis_pengadaan, $bulan);
			if ($result_total_pagu_rup->num_rows() > 0) {
				foreach ($result_total_pagu_rup->result() as $rows_total_pagu_rup) {
					$total_pagu_rup = $rows_total_pagu_rup->pagu_paket;
				}
			}
			if ($result_total_pagu_rup->num_rows() <= 0) {
				$total_pagu_rup = 0;
			}
			$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
			// $object->getActiveSheet()->setCellValue('C'.($mulai), '=SUM(C12:C'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('C'.($mulai), $this->nullValue($total_pagu));
			$object->getActiveSheet()->setCellValue('D'.($mulai), $this->nullValue($total_pagu_rup));
			$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E12:E'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('F'.($mulai), '=SUM(F12:F'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('G'.($mulai), '=SUM(G12:G'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('H'.($mulai), '=SUM(H12:H'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('I'.($mulai), '=SUM(I12:I'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('J'.($mulai), '=SUM(J12:J'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('K'.($mulai), '=SUM(K12:K'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('L'.($mulai), '=SUM(L12:L'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('M'.($mulai), '=SUM(M12:M'.(($mulai)-1).')');


			// SETUP

			$object->getActiveSheet()->getStyle('A'.$mulai.':M'.$mulai)->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A'.($mulai).':B'.($mulai));
			$object->getActiveSheet()->getStyle('A'.$mulai.':M'.$mulai)->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('C'.$mulai.':M'.$mulai)->getNumberFormat()->setFormatCode('#,##0');

			$object->getActiveSheet()->getStyle('A'.($mulai).':M'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A'.($mulai).':M'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$object->getActiveSheet()->getStyle('A'.$mulai.':M'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A'.$mulai.':M'.$mulai)->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');


			
			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - RP'.$jenis_pengadaan.'.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - RP'.$jenis_pengadaan.'.xls"');
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