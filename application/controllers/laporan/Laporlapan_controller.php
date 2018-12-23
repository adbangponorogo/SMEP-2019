<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporlapan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laporlapan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/laporan-pengadaan/data');
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
			$jenis_pengadaan = $this->input->post("jenis_pengadaan");
			$tahun = $this->input->post("tahun");
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

			if ($bulan == "01") {$nama_bulan="JANUARI";}if ($bulan == "02") {$nama_bulan="FEBRUARI";}if ($bulan == "03") {$nama_bulan="MARET";}if ($bulan == "04") {$nama_bulan="APRIL";}if ($bulan == "05") {$nama_bulan="MEI";}if ($bulan == "06") {$nama_bulan="JUNI";}if ($bulan == "07") {$nama_bulan="JULI";}if ($bulan == "08") {$nama_bulan="AGUSTUS";}if ($bulan == "09") {$nama_bulan="SEPTEMBER";}if ($bulan == "10") {$nama_bulan="OKTOBER";}if ($bulan == "11") {$nama_bulan="NOVEMBER";}if ($bulan == "12") {$nama_bulan="DESEMBER";}

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
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | LP'.$jenis_pengadaan.' - '.$nama_skpd.'&R&P');

			// -------- Manual Setting Autosize -------- //
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(7.14);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(27);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(6);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(8);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(8);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(8);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(8);
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(6.29);
			$object->getActiveSheet()->getColumnDimension('I')->setWidth(7.71);
			$object->getActiveSheet()->getColumnDimension('J')->setWidth(7.71);

			$object->getActiveSheet()->getColumnDimension('K')->setWidth(5.71);
			$object->getActiveSheet()->getColumnDimension('L')->setWidth(5.71);
			$object->getActiveSheet()->getColumnDimension('M')->setWidth(5.71);
			$object->getActiveSheet()->getColumnDimension('N')->setWidth(5.71);
			$object->getActiveSheet()->getColumnDimension('O')->setWidth(7.86);
			$object->getActiveSheet()->getColumnDimension('P')->setWidth(7.86);
			$object->getActiveSheet()->getColumnDimension('Q')->setWidth(7.86);
			$object->getActiveSheet()->getColumnDimension('R')->setWidth(8.43);

			// -------- Title Form -------- //
			$title_form = 'LAPORAN PENGADAAN BARANG/JASA PEMERINTAH TAHUN '.$tahun;
			$object->getActiveSheet()->setCellValue('A1', $title_form);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A1:R1');
			$object->getActiveSheet()->getStyle('A1:R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Subtitle Form -------- //
			$subtitle_form = 'KEADAAN SAMPAI DENGAN BULAN '.$nama_bulan;
			$object->getActiveSheet()->setCellValue('A2', $subtitle_form);
			$object->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A2:R2');
			$object->getActiveSheet()->getStyle('A2:R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Nama Organisasi -------- //
			$info_organisasi = 'NAMA ORGANISASI ';
			$nama_organisasi = ': '.$nama_skpd;
			$object->getActiveSheet()->setCellValue('A4', $info_organisasi);
			$object->getActiveSheet()->setCellValue('C4', $nama_organisasi);
			$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A4:B4');
			$object->getActiveSheet()->mergeCells('C4:K4');
			$object->getActiveSheet()->getStyle('A4:K4')->getFont()->setBold(TRUE);

			// -------- Kabupaten -------- //
			$info_kabupaten = 'KABUPATEN ';
			$nama_kabupaten = ': PONOROGO';
			$object->getActiveSheet()->setCellValue('A5', $info_kabupaten);
			$object->getActiveSheet()->setCellValue('C5', $nama_kabupaten);
			$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C5')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A5:B5');
			$object->getActiveSheet()->mergeCells('C5:K5');
			$object->getActiveSheet()->getStyle('A5:K5')->getFont()->setBold(TRUE);

			// -------- Tahun Anggaran -------- //
			$info_anggaran = 'TAHUN ANGGARAN ';
			$nama_anggaran = ': '.$tahun;
			$object->getActiveSheet()->setCellValue('A6', $info_anggaran);
			$object->getActiveSheet()->setCellValue('C6', $nama_anggaran);
			$object->getActiveSheet()->getStyle('A6')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C6')->getFont()->setSize(10); 
			$object->getActiveSheet()->mergeCells('A6:B6');
			$object->getActiveSheet()->mergeCells('C6:K6');
			$object->getActiveSheet()->getStyle('A6:K6')->getFont()->setBold(TRUE);

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

			// TABLE HEADER
			$table_title_head_row_first = array("NO", "NAMA PAKET / KEGIATAN", "SUMBER DANA", "PAGU ANGGARAN", "NILAI HPS", "NILAI KONTRAK", "SISA ANGGARAN", "METODE PENGADAAN DAN SISTEM EVALUASI", "JUMLAH PESERTA", "", "TANGGAL");
			$table_title_head_row_second = array("NAMA PERUSAHAAN PEMENANG NOMOR DAN TANGGAL KONTRAK / SURAT PESANAN", "SURAT PERINTAH MULAI KERJA (SPMK)", "SANGGAH / SANGGAH BANDING / PENGADUAN", "KETARANGAN");
			$table_title_head_jumlah_peserta = array("MENDAFTAR", "MENAWAR");
			$table_title_head_tanggal = array("PENGUMUMAN LELANG", "PENJELASAN (ANWIJZING)", "PEMBUKAAN / PENAWARAN", "PENETAPAN PEMENANG");

			$start_column_first = 0;
			foreach ($table_title_head_row_first as $table_head_first) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 10, $table_head_first);
				$start_column_first++;
			}
			$start_column_second = 14;
			foreach ($table_title_head_row_second as $table_head_second) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 10, $table_head_second);
				$start_column_second++;
			}
			$start_column_jumlah_peserta = 8;
			foreach ($table_title_head_jumlah_peserta as $table_head_jumlah_peserta) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_jumlah_peserta, 11, $table_head_jumlah_peserta);
				$start_column_jumlah_peserta++;
			}
			$start_column_tanggal = 10;
			foreach ($table_title_head_tanggal as $table_head_tanggal) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_tanggal, 11, $table_head_tanggal);
				$start_column_tanggal++;
			}
			$object->getActiveSheet()->mergeCells('A10:A11');
			$object->getActiveSheet()->mergeCells('B10:B11');
			$object->getActiveSheet()->mergeCells('C10:C11');
			$object->getActiveSheet()->mergeCells('D10:D11');
			$object->getActiveSheet()->mergeCells('E10:E11');
			$object->getActiveSheet()->mergeCells('F10:F11');
			$object->getActiveSheet()->mergeCells('G10:G11');
			$object->getActiveSheet()->mergeCells('H10:H11');
			$object->getActiveSheet()->mergeCells('I10:J10');
			$object->getActiveSheet()->mergeCells('K10:N10');
			$object->getActiveSheet()->mergeCells('O10:O11');
			$object->getActiveSheet()->mergeCells('P10:P11');
			$object->getActiveSheet()->mergeCells('Q10:Q11');
			$object->getActiveSheet()->mergeCells('R10:R11');


			$object->getActiveSheet()->getStyle('A10:R11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A10:R11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('A10:R11')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A10:R11')->getFont()->setSize(7);
			$object->getActiveSheet()->getStyle('A10:R11')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A10:R11')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A10:R11')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

 			$sumber_dana = ["", "APBD","APBDP","APBN","APBNP","BLU","BLUD","BUMD","BUMN","PHLN","PNBP","Lainnya"];
 			$no = 1;
 			$mulai = 12;
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
						// -------- Value ---------
						$object->getActiveSheet()->setCellValue('A'.(($mulai++)+1), $rows_kegiatan->kd_gabungan);					
						$object->getActiveSheet()->setCellValue('B'.($mulai++), $rows_kegiatan->keterangan_kegiatan);
						
						// -------- Object ---------
						// --- A ---
						$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getFont()->setSize(8);
						$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getFont()->setBold(TRUE);
						$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getStyle('A'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						// --- B ---
						$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getFont()->setSize(10);
						$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getFont()->setBold(TRUE);
						$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$object->getActiveSheet()->getStyle('B'.(($mulai)-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

						$result_rup = $this->model->getDataRUP($skpd, $rows_program->id, $rows_kegiatan->id, $jenis_pengadaan, 'all');
						foreach ($result_rup->result() as $rows_rup) {
							$result_realisasi_rup = $this->model->getDataRealisasiRUP($rows_rup->id);
							if ($result_realisasi_rup->num_rows() > 0) {
								foreach ($result_realisasi_rup->result() as $rows_realisasi_rup) {
									$sumber_dana = array("", "APBD", "APBDP", "APBN", "APBNP", "BLU", "BLUD", "BUMD", "BUMN", "PHLN", "PNBP", "LAINNYA");
									// -------- Value ---------
									$object->getActiveSheet()->setCellValue('A'.($mulai++), $no++);					
									$object->getActiveSheet()->setCellValue('B'.(($mulai++)-1), $rows_rup->nama_paket);
									$object->getActiveSheet()->setCellValue('C'.(($mulai)-2), $sumber_dana[$rows_rup->sumber_dana]);
									$object->getActiveSheet()->setCellValue('D'.(($mulai)-2), $rows_rup->pagu_paket);
									$object->getActiveSheet()->setCellValue('E'.(($mulai)-2), $this->null_value($rows_realisasi_rup->nilai_hps));
									$object->getActiveSheet()->setCellValue('F'.(($mulai)-2), $this->null_value($rows_realisasi_rup->realisasi_keuangan));
									$object->getActiveSheet()->setCellValue('G'.(($mulai)-2), "=sum(D".(($mulai)-2)."-F".(($mulai)-2).")");
									$object->getActiveSheet()->setCellValue('H'.(($mulai)-2), $this->null_value($rows_rup->metode_pemilihan));
									$object->getActiveSheet()->setCellValue('I'.(($mulai)-2), $this->null_value($rows_realisasi_rup->jumlah_mendaftar));
									$object->getActiveSheet()->setCellValue('J'.(($mulai)-2), $this->null_value($rows_realisasi_rup->jumlah_menawar));
									$object->getActiveSheet()->setCellValue('K'.(($mulai)-2), $this->null_value($rows_realisasi_rup->tanggal_pengumuman));
									$object->getActiveSheet()->setCellValue('L'.(($mulai)-2), $this->null_value($rows_realisasi_rup->tanggal_anwijzing));
									$object->getActiveSheet()->setCellValue('M'.(($mulai)-2), $this->null_value($rows_realisasi_rup->tanggal_pembukaan_penawaran));
									$object->getActiveSheet()->setCellValue('N'.(($mulai)-2), $this->null_value($rows_realisasi_rup->tanggal_penetapan_pemenang));
									$object->getActiveSheet()->setCellValue('O'.(($mulai)-2), $this->null_value($rows_realisasi_rup->nama_pemenang));
									$object->getActiveSheet()->setCellValue('P'.(($mulai)-2), $this->null_value($rows_realisasi_rup->tanggal_spmk));
									$object->getActiveSheet()->setCellValue('Q'.(($mulai)-2), $this->null_value($rows_realisasi_rup->sanggah));
									$object->getActiveSheet()->setCellValue('R'.(($mulai)-2), '-');
								}
							}
							else{
								$sumber_dana = array("", "APBD", "APBDP", "APBN", "APBNP", "BLU", "BLUD", "BUMD", "BUMN", "PHLN", "PNBP", "LAINNYA");
									// -------- Value ---------
									$object->getActiveSheet()->setCellValue('A'.($mulai++), $no++);					
									$object->getActiveSheet()->setCellValue('B'.(($mulai++)-1), $rows_rup->nama_paket);
									$object->getActiveSheet()->setCellValue('C'.(($mulai)-2), $sumber_dana[$rows_rup->sumber_dana]);
									$object->getActiveSheet()->setCellValue('D'.(($mulai)-2), $rows_rup->pagu_paket);
									$object->getActiveSheet()->setCellValue('E'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('F'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('G'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('H'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('I'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('J'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('K'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('L'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('M'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('N'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('O'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('P'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('Q'.(($mulai)-2), '-');
									$object->getActiveSheet()->setCellValue('R'.(($mulai)-2), '-');
							}
							


							// -------- Object ---------
							// --- A ---
							$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getFont()->setSize(8);
							$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getFont()->setBold(TRUE);
							$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$object->getActiveSheet()->getStyle('A'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							// --- B ---
							$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getFont()->setSize(10);
							$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$object->getActiveSheet()->getStyle('B'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							// --- C ---
							$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getFont()->setSize(10);
							$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$object->getActiveSheet()->getStyle('C'.(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							// --- D ---
							$object->getActiveSheet()->getStyle('D'.(($mulai)-2).":R".(($mulai)-2))->getFont()->setSize(8);
							$object->getActiveSheet()->getStyle('D'.(($mulai)-2).":R".(($mulai)-2))->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('D'.(($mulai)-2).":R".(($mulai)-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('D'.(($mulai)-2).":R".(($mulai)-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						}
					}	
					}
				}
				else{
					$object->getActiveSheet()->setCellValue('B12', 'NIHIL');
				}
				$mulai++;
			}

			$object->getActiveSheet()->setCellValue('A'.$mulai, 'Total');
			$object->getActiveSheet()->getStyle('A'.$mulai.':G'.$mulai)->getFont()->setBold(TRUE);
			$object->getActiveSheet()->mergeCells('A'.$mulai.':C'.$mulai);
			$object->getActiveSheet()->setCellValue('D'.$mulai, '=SUM(D12:D'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('E'.$mulai, '=SUM(E12:E'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('F'.$mulai, '=SUM(F12:F'.(($mulai)-1).')');
			$object->getActiveSheet()->setCellValue('G'.$mulai, '=SUM(G12:G'.(($mulai)-1).')');

			$object->getActiveSheet()->getStyle('D12:D'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('E12:E'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('F12:F'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('G12:G'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

			$object->getActiveSheet()->getStyle('A12:R'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Laporan Pengadaan - '.$nama_jenis_pengadaan.'.xls"');
			$object_writer->save('php://output');
		}
	}

	public function null_value($value){
		if ($this->session->userdata('auth_id') != '') {
			if (is_null($value) || $value == '') {
				$data = '-';
			}
			else{
				$data = $value;
			}
			return $data;
		}
	}
	
}