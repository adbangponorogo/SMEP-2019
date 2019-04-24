<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminlapan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/adminlapan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/laporan-pengadaan/data');
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

			if ($bulan == "01") {$nama_bulan="JANUARI";}if ($bulan == "02") {$nama_bulan="FEBRUARI";}if ($bulan == "03") {$nama_bulan="MARET";}if ($bulan == "04") {$nama_bulan="APRIL";}if ($bulan == "05") {$nama_bulan="MEI";}if ($bulan == "06") {$nama_bulan="JUNI";}if ($bulan == "07") {$nama_bulan="JULI";}if ($bulan == "08") {$nama_bulan="AGUSTUS";}if ($bulan == "09") {$nama_bulan="SEPTEMBER";}if ($bulan == "10") {$nama_bulan="OKTOBER";}if ($bulan == "11") {$nama_bulan="NOVEMBER";}if ($bulan == "12") {$nama_bulan="DESEMBER";}

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
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | Rekap LP - '.$nama_jenis_pengadaan.'&R&P');
			$object->getDefaultStyle()->getFont()->setName('Times New Roman');

			$object->getActiveSheet()->getColumnDimension('A')->setWidth(8.57);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(51);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(13.29);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(13.29);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(13.29);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(13.29);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(13.29); 
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(11.14);

			// -------- Title Form -------- //
			$title_form = 'REKAPITULASI';
			$object->getActiveSheet()->setCellValue('A1', $title_form);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A1:H1');
			$object->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Subtitle Form -------- //
			$subtitle_form_first = 'LAPORAN PENGADAAN BARANG PADA ORGANISASI PERANGKAT DAERAH';
			$subtitle_form_second = 'DILINGKUNGAN PEMERINTAH KABUPATEN PONOROGO TAHUN '.$tahun;
			$subtitle_form_third = 'KEADAAN SAMPAI DENGAN BULAN '.$nama_bulan;
			$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
			$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
			$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
			$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setSize(12);
			$object->getActiveSheet()->mergeCells('A2:H2');
			$object->getActiveSheet()->mergeCells('A3:H3');
			$object->getActiveSheet()->mergeCells('A4:H4');
			$object->getActiveSheet()->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Jenis Pengadaan -------- //
			$info_organisasi = 'JENIS PENGADAAN';
			$nama_organisasi = ' : '.$nama_jenis_pengadaan;
			$object->getActiveSheet()->setCellValue('A6', $info_organisasi.$nama_organisasi);
			$object->getActiveSheet()->getStyle('A6')->getFont()->setSize(12);
			$object->getActiveSheet()->mergeCells('A6:H6');
			$object->getActiveSheet()->getStyle('A6:H6')->getFont()->setBold(TRUE);


			// TABLE HEADER
			// Values
			$table_header = array("NO", "ORGANISASI PERANGKAT DAERAH (OPD)", "PAGU ANGGARAN", "NILAI HPS", "NILAI KONTRAK", "SISA ANGGARAN", "PROSENTASE", "KETERANGAN");
			$start_column = 0;
			foreach ($table_header as $thead) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column, 8, $thead);
				$start_column++;
			}

			// SETUP
			$object->getActiveSheet()->getStyle('A8:H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A8:H8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('A8:H8')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A8:H8')->getFont()->setSize(7);
			$object->getActiveSheet()->getStyle('A8:H8')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A8:H8')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A8:H8')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');


			// VALUE TABLE
			$no = 1;
			$mulai = 9;
			$result_skpd = $this->model->getDataSKPD();
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_pagu = $this->model->getDataPaguSKPD($rows_skpd->kd_skpd);
				foreach ($result_pagu->result() as $rows_pagu) {
					$result_realisasi_rup = $this->model->getDataRealisasiRUP($rows_skpd->id, $jenis_pengadaan, $bulan);
					foreach ($result_realisasi_rup->result() as $rows_realisasi_rup) {
						$object->getActiveSheet()->setCellValue('A'.$mulai, $no++);
						$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_skpd->nama_skpd);
						$object->getActiveSheet()->setCellValue('C'.$mulai, $this->nullValue($rows_pagu->jumlah));
						$object->getActiveSheet()->setCellValue('D'.$mulai, $this->nullValue($rows_realisasi_rup->nilai_hps));
						$object->getActiveSheet()->setCellValue('E'.$mulai, $this->nullValue($rows_realisasi_rup->nilai_kontrak));
						if (!is_null($rows_realisasi_rup->pagu_paket) || $rows_realisasi_rup->pagu_paket != '') {
							$object->getActiveSheet()->setCellValue('F'.$mulai, "=C".($mulai)."-E".($mulai)."");
						}
						else{
							$object->getActiveSheet()->setCellValue('F'.$mulai, 0);
						}
						$object->getActiveSheet()->setCellValue('G'.($mulai), '=IF('
																			.'(AND(C'.$mulai.' > 0, E'.$mulai.' > 0)),'
																			.' E'.$mulai.'/C'.$mulai.','
																			.' 0)');
						$object->getActiveSheet()->setCellValue('H'.$mulai, "");
						

						// SETUP
						$object->getActiveSheet()->getStyle('A'.($mulai).':H'.($mulai))->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getStyle('A'.($mulai).':H'.($mulai))->getFont()->setSize(8);

						$object->getActiveSheet()->getStyle('c'.($mulai).':F'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
						$object->getActiveSheet()->getStyle('G'.$mulai)->getNumberFormat()->setFormatCode('0.00%');

						$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getStyle('C'.($mulai).':G'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$object->getActiveSheet()->getStyle('C'.($mulai).':G'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getStyle('H'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getStyle('H'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

						$object->getActiveSheet()->getStyle('A'.($mulai).':H'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
					}
					$mulai++;
				}
			}

			// Values
			$result_total_pagu = $this->model->getDataPaguSKPD('all');
			foreach ($result_total_pagu->result() as $rows_total_pagu) {
				$total_pagu = $rows_total_pagu->jumlah;
			}
			$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
			// $object->getActiveSheet()->setCellValue('C'.($mulai), '=SUM(C9:C'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('C'.($mulai), $total_pagu);
			$object->getActiveSheet()->setCellValue('D'.($mulai), '=SUM(D9:D'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E9:E'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('F'.($mulai), '=SUM(F9:F'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('G'.($mulai), '=IF('
																		.'(AND(C'.$mulai.' > 0, E'.$mulai.' > 0)),'
																		.' E'.$mulai.'/C'.$mulai.','
																		.' 0)');


			// SETUP

			$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A'.($mulai).':B'.($mulai));
			$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('C'.$mulai.':F'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('G'.$mulai)->getNumberFormat()->setFormatCode('0.00%');

			$object->getActiveSheet()->getStyle('A'.($mulai).':H'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A'.($mulai).':H'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');


			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - LP'.$jenis_pengadaan.'.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - LP'.$jenis_pengadaan.'.xls"');
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