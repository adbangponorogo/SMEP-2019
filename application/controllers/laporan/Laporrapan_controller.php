<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporrapan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laporrapan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/rencana-pengadaan/data');
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
		date_default_timezone_set("Asia/Jakarta");
		$skpd = $this->input->post("skpd");
		$jenis_pengadaan = $this->input->post("jenis_pengadaan");
		$bulan = $this->input->post("bulan");

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
		$result_skpd = $this->model->getDataSKPDUnique($skpd);
		foreach ($result_skpd->result() as $rows_skpd) {
			$nama_skpd = $rows_skpd->nama_skpd;
		}

			// -------- PAPER Setup -------- //
			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);
			$object->getActiveSheet()->getSheetView()->setZoomScale(80);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | RP'.$jenis_pengadaan.' - '.$nama_skpd.'&R&P');

			// -------- Manual Setting Autosize -------- //
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(30);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(13);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(13);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth();
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(12);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(12);
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(12);
			$object->getActiveSheet()->getColumnDimension('I')->setWidth(12);
			$object->getActiveSheet()->getColumnDimension('J')->setWidth(12);
			$object->getActiveSheet()->getColumnDimension('K')->setWidth(15);

			// -------- Title Form -------- //
			$title_form = 'Laporan Rencana Pengadaan Barang / Jasa Pemerintah';
			$object->getActiveSheet()->setCellValue('A1', $title_form);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A1:K1');
			$object->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Nama Organisasi -------- //
			$info_organisasi = 'NAMA ORGANISASI ';
			$nama_organisasi = ': '.$nama_skpd;
			$object->getActiveSheet()->setCellValue('A3', $info_organisasi);
			$object->getActiveSheet()->setCellValue('C3', $nama_organisasi);
			$object->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C3')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A3:B3');
			$object->getActiveSheet()->mergeCells('C3:K3');
			$object->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(TRUE);

			// -------- Kabupaten -------- //
			$info_kabupaten = 'KABUPATEN ';
			$nama_kabupaten = ': PONOROGO';
			$object->getActiveSheet()->setCellValue('A4', $info_kabupaten);
			$object->getActiveSheet()->setCellValue('C4', $nama_kabupaten);
			$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A4:B4');
			$object->getActiveSheet()->mergeCells('C4:K4');
			$object->getActiveSheet()->getStyle('A4:K4')->getFont()->setBold(TRUE);

			// -------- Tahun Anggaran -------- //
			$info_anggaran = 'TAHUN ANGGARAN ';
			$nama_anggaran = ': 2019';
			$object->getActiveSheet()->setCellValue('A5', $info_anggaran);
			$object->getActiveSheet()->setCellValue('C5', $nama_anggaran);
			$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C5')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A5:B5');
			$object->getActiveSheet()->mergeCells('C5:K5');
			$object->getActiveSheet()->getStyle('A5:K5')->getFont()->setBold(TRUE);

			// -------- Jenis Pengadaan -------- //
			$info_jenis_pengadaan = 'JENIS PENGADAAN ';
			$nama_jenis_pengadaan = ': '.$nama_jenis_pengadaan;
			$object->getActiveSheet()->setCellValue('A8', $info_jenis_pengadaan);
			$object->getActiveSheet()->setCellValue('C8', $nama_jenis_pengadaan);
			$object->getActiveSheet()->getStyle('A8')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C8')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A8:B8');
			$object->getActiveSheet()->mergeCells('C8:K8');
			$object->getActiveSheet()->getStyle('A8:K8')->getFont()->setBold(TRUE);

			// -------- Header Table -------- //
				$object->getActiveSheet()->setCellValue('A9', 'N0');
				$object->getActiveSheet()->mergeCells('A9:A10');
				$object->getActiveSheet()->setCellValue('B9', 'NAMA PAKET / KEGIATAN');
				$object->getActiveSheet()->mergeCells('B9:B10');
				$object->getActiveSheet()->setCellValue('C9', 'PAGU ANGGARAN');
				$object->getActiveSheet()->mergeCells('C9:C10');
				$object->getActiveSheet()->setCellValue('D9', 'SUMBER DANA');
				$object->getActiveSheet()->mergeCells('D9:D10');
				$object->getActiveSheet()->setCellValue('E9', 'METODE PENGADAAN');
				$object->getActiveSheet()->mergeCells('E9:J9');
					$object->getActiveSheet()->setCellValue('E10', 'TENDER');
					$object->getActiveSheet()->setCellValue('F10', 'TENDER CEPAT');
					$object->getActiveSheet()->setCellValue('G10', 'PENGADAAN LANGSUNG');
					$object->getActiveSheet()->setCellValue('H10', 'PENUNJUKAN LANGSUNG');
					$object->getActiveSheet()->setCellValue('I10', 'SELEKSI');
					$object->getActiveSheet()->setCellValue('J10', 'E-PURCHASING');
				$object->getActiveSheet()->setCellValue('K9', 'PPTK / PPK');
				$object->getActiveSheet()->mergeCells('K9:K10');

			$object->getActiveSheet()->getStyle('A9:K9')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A10:K10')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A9:K9')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('A10:K10')->getFont()->setSize(10);

			$object->getActiveSheet()->getStyle('A9:D9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('K9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('E10:J10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$object->getActiveSheet()->getStyle('A9:D9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('K9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('E10:J10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$object->getActiveSheet()->getStyle('A9:D9')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('E10:J10')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('K9')->getAlignment()->setWrapText(true);

			$object->getActiveSheet()->getStyle('A9:K9')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A10:K10')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));

			$object->getActiveSheet()->getStyle('A9:K9')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
			$object->getActiveSheet()->getStyle('A10:K10')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

			$sumber_dana = ["", "APBD","APBDP","APBN","APBNP","BLU","BLUD","BUMD","BUMN","PHLN","PNBP","Lainnya"];
 			$no = 1;
 			$mulai = 11;
			$result_skpd = $this->model->getDataSKPDUnique($skpd);
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_program = $this->model->getDataProgramUnique($rows_skpd->kd_skpd, $jenis_pengadaan);
				if ($result_program->num_rows() > 0) {
					foreach ($result_program->result() as $rows_program) {
							// -------- Value ---------
							$object->getActiveSheet()->setCellValue('A'.($mulai), $rows_program->kd_gabungan);
							$object->getActiveSheet()->setCellValue('B'.($mulai), $rows_program->keterangan_program);
							
							// -------- Object ---------
							// --- A ---
							$object->getActiveSheet()->getStyle('A'.($mulai))->getFont()->setSize(8);
							$object->getActiveSheet()->getStyle('A'.($mulai))->getFont()->setBold(TRUE);
							$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							// --- B ---
							$object->getActiveSheet()->getStyle('B'.($mulai))->getFont()->setSize(10);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getFont()->setBold(TRUE);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

							$result_kegiatan = $this->model->getDataKegiatanUnique($rows_program->id_program, $rows_program->kd_program);
							foreach ($result_kegiatan->result() as $rows_kegiatan) {
								$result_pptk_kegiatan = $this->model->getDataPPTKKegiatan($rows_kegiatan->id);
								if ($result_pptk_kegiatan->num_rows() > 0) {
									foreach ($result_pptk_kegiatan->result() as $rows_pptk) {
										$nama_pptk_kegiatan = $rows_pptk->nama;
									}
								}
								else{
									$nama_pptk_kegiatan = '-';
								}

									// -------- Value ---------
									$object->getActiveSheet()->setCellValue('A'.(($mulai++)+1), $rows_kegiatan->kd_gabungan);					
									$object->getActiveSheet()->setCellValue('B'.($mulai++), $rows_kegiatan->keterangan_kegiatan);
									$object->getActiveSheet()->setCellValue('K'.(($mulai++)-1), $nama_pptk_kegiatan);
										
									// -------- Object ---------
									// --- A ---
									$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getFont()->setSize(8);
									$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getFont()->setBold(TRUE);
									$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									// --- B ---
									$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getFont()->setSize(10);
									$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getFont()->setBold(TRUE);
									$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									// --- C ---
									$object->getActiveSheet()->getStyle('K'.(($mulai)-2))->getFont()->setSize(10);
									$object->getActiveSheet()->getStyle('K'.(($mulai)-2))->getFont()->setBold(TRUE);
									$object->getActiveSheet()->getStyle('K'.(($mulai)-2))->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getStyle('K'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$object->getActiveSheet()->getStyle('K'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

									$result_rup = $this->model->getDataRUP($skpd, $rows_program->id, $rows_kegiatan->id, $jenis_pengadaan, $bulan);
									foreach ($result_rup->result() as $rows_rup) {
										$sumber_dana = array("", "APBD", "APBDP", "APBN", "APBNP", "BLU", "BLUD", "BUMD", "BUMN", "PHLN", "PNBP", "LAINNYA");
										// -------- Value ---------
										$object->getActiveSheet()->setCellValue('A'.($mulai++), $no++);					
										$object->getActiveSheet()->setCellValue('B'.(($mulai++)-1), $rows_rup->nama_paket);
										$object->getActiveSheet()->setCellValue('C'.(($mulai)-2), $rows_rup->pagu_paket);
										$object->getActiveSheet()->setCellValue('D'.(($mulai)-2), $sumber_dana[$rows_rup->sumber_dana]);
										if ($rows_rup->metode_pemilihan == 2) {
											$object->getActiveSheet()->setCellValue('E'.(($mulai)-2), 'X');
										}
										if ($rows_rup->metode_pemilihan != 2) {
											$object->getActiveSheet()->setCellValue('E'.(($mulai)-2), '-');
										}
										if ($rows_rup->metode_pemilihan == 3) {
											$object->getActiveSheet()->setCellValue('F'.(($mulai)-2), 'X');
										}
										if ($rows_rup->metode_pemilihan != 3) {
											$object->getActiveSheet()->setCellValue('F'.(($mulai)-2), '-');
										}
										if ($rows_rup->metode_pemilihan == 4) {
											$object->getActiveSheet()->setCellValue('G'.(($mulai)-2), 'X');
										}
										if ($rows_rup->metode_pemilihan != 4) {
											$object->getActiveSheet()->setCellValue('G'.(($mulai)-2), '-');
										}
										if ($rows_rup->metode_pemilihan == 5) {
											$object->getActiveSheet()->setCellValue('H'.(($mulai)-2), 'X');
										}
										if ($rows_rup->metode_pemilihan != 5) {
											$object->getActiveSheet()->setCellValue('H'.(($mulai)-2), '-');
										}
										if ($rows_rup->metode_pemilihan == 6) {
											$object->getActiveSheet()->setCellValue('I'.(($mulai)-2), 'X');
										}
										if ($rows_rup->metode_pemilihan != 6) {
											$object->getActiveSheet()->setCellValue('I'.(($mulai)-2), '-');
										}
										if ($rows_rup->metode_pemilihan == 1) {
											$object->getActiveSheet()->setCellValue('J'.(($mulai)-2), 'X');
										}
										if ($rows_rup->metode_pemilihan != 1) {
											$object->getActiveSheet()->setCellValue('J'.(($mulai)-2), '-');
										}

										// -------- Object ---------
										// --- A ---
										$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getFont()->setSize(8);
										$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getFont()->setBold(TRUE);
										$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
										$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
										// --- B ---
										$object->getActiveSheet()->getStyle('B'.(($mulai)-3))->getFont()->setSize(10);
										$object->getActiveSheet()->getStyle('B'.(($mulai)-3))->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getStyle('B'.(($mulai)-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
										$object->getActiveSheet()->getStyle('B'.(($mulai)-3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
										// --- C ---
										$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getFont()->setSize(10);
										$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
										$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									}
							}
					}			
				}
				else{
					$object->getActiveSheet()->setCellValue('B11', 'NIHIL');
				}
				$mulai++;
			}

			$object->getActiveSheet()->setCellValue('A'.$mulai, 'TOTAL');
		 	$object->getActiveSheet()->setCellValue('C'.$mulai, '=SUM(C11:C'.(($mulai)-1).')');

		 	// SETUP
		 	$object->getActiveSheet()->getStyle('A'.$mulai.':C'.$mulai)->getFont()->setBold(TRUE);
		 	$object->getActiveSheet()->getStyle('A11:K'.($mulai))->getFont()->setSize(8);
		 	$object->getActiveSheet()->mergeCells('A'.$mulai.':B'.$mulai);
		 	$object->getActiveSheet()->getStyle('A11:K'.($mulai))->getAlignment()->setWrapText(true);

		 	$object->getActiveSheet()->getStyle('D11:K'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('D11:K'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('A'.$mulai.':C'.$mulai)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A'.$mulai.':C'.$mulai)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		 	$object->getActiveSheet()->getStyle('A11:K'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
		 	$object->getActiveSheet()->getStyle('C11:C'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Laporan Rencana Pengadaan - '.$nama_jenis_pengadaan.'.xls"');
			$object_writer->save('php://output');
	}
}