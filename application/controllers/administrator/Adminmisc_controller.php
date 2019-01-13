<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminmisc_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/adminmisc_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/misc/data');
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

	public function getMainDataAllKegiatan($id_skpd){
		if ($this->session->userdata("auth_id") != "") {
			$result_kegiatan = $this->model->getDataKegiatan($id_skpd);
			$data = array();
			foreach ($result_kegiatan->result() as $rows_kegiatan) {
				$data[] = array(
							$rows_kegiatan->id,
							"[".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan
						);
			}
			echo json_encode($data);
		}
	}

	public function getPrintData(){
		if ($this->session->userdata("auth_id") != "") {
			$skpd = $this->input->post("skpd");
			$kegiatan = $this->input->post("kegiatan");
			$jenis = $this->input->post("jenis");
			$tahun = $this->input->post("tahun");
			$bulan = $this->input->post("bulan");
			$tanggal_cetak = $this->input->post("tanggal_cetak");

			$nama_bulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			
			switch ($jenis) {
				case '1':
					$nama_jenis = 'Laporan Pajak';
				break;
				case '2':
					$nama_jenis = 'Buku Kas Umum';
				break;
				
				default:
					$nama_jenis = '-';
				break;
			}

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
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | Misc - '.$nama_jenis.' - '.$nama_skpd.'&R&P');

			if ($jenis == 1) {
				// -------- Title Form -------- //
				if ($bulan == "all") {
					$bulan_pencairan = "SEMUA BULAN";
				}
				else{
					$bulan_pencairan = "BULAN ".$nama_bulan[$bulan];
				}
				$title_form = 'DATA PENCAIRAN SP2D BAGIAN - '.$bulan_pencairan;
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A1:O1');

				// -------- Title User Info -------- //
				$title_info = 'TAHUN ANGGARAN '.$tahun;
				$object->getActiveSheet()->setCellValue('A2', $title_info);
				$object->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A2:O2');

				// -------- User Info -------- //
				if ($skpd == "all") {
					$nama_skpd = "Pemerintah Daerah";
				}
				else{
					$result_skpd = $this->model->getDataSKPDUnique($skpd);
					foreach ($result_skpd->result() as $rows_skpd) {
						$nama_skpd = $rows_skpd->nama_skpd;
					}
				}

				$title_info = 'Nama SKPD : '.$nama_skpd;
				$object->getActiveSheet()->setCellValue('A4', $title_info);
				$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A4:O4');

				// -------- Kegiatan Info -------- //
				if ($kegiatan == "all") {
					$nama_kegiatan = "Semua Kegiatan";
				}
				else{
					$result_skpd = $this->model->getDataSKPDUnique($skpd);
					foreach ($result_skpd->result() as $rows_skpd) {
						$result_kegiatan = $this->model->getDataSKPDUnique($rows_skpd->kd_skpd, $kegiatan);
						foreach ($result_kegiatan->result() as $rows_kegiatan) {
							$nama_kegiatan = $rows_kegiatan->keterangan_kegiatan;
						}
					}
				}
				$title_info = 'Nama Kegiatan : '.$nama_kegiatan;
				$object->getActiveSheet()->setCellValue('A5', $title_info);
				$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A5:O5');

				// TABLE HEADER
				$table_header = array("No", "Tgl", "Bln", "No SP2D", "Uraian", "Nama Penerima", "Pagu", "Pencairan", "PPN", "PPh 21", "PPh 22", "PPh23", "PP1", "Jumlah", "Total");
				$start_column = 0;
				foreach ($table_header as $thead) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column, 6, $thead);
					$start_column++;
				}
				$object->getActiveSheet()->getStyle('A6:O6')->getFont()->setSize(9);
				$object->getActiveSheet()->getStyle('A6:O6')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A6:O6')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A6:O6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:O6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:O6')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
				$object->getActiveSheet()->getStyle('A6:O6')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));


				// Values
				$no = 1;
				$mulai = 7; 
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				if ($result_skpd->num_rows() > 0) {
					foreach ($result_skpd->result() as $rows_skpd) {
						$result_kegiatan = $this->model->getDataKegiatanUnique($rows_skpd->kd_skpd, $kegiatan);
						foreach ($result_kegiatan->result() as $rows_kegiatan) {
							$result_realisasi_ro = $this->model->getDataRealisasiRO($rows_skpd->kd_skpd, $rows_kegiatan->kd_gabungan, $bulan);
							foreach ($result_realisasi_ro->result() as $rows_realisasi_ro) {
								if ($rows_realisasi_ro->tanggal != '' || $rows_realisasi_ro->bln != '' || $rows_realisasi_ro->no_sp2d != '' || $rows_realisasi_ro->uraian != '' || $rows_realisasi_ro->nama_penerima != '' || $rows_realisasi_ro->pagu != '' || $rows_realisasi_ro->pencairan != '') {
									$result_spm = $this->model->getDataSPMInfo($rows_realisasi_ro->no_spm);
									foreach ($result_spm->result() as $rows_spm) {
										$object->getActiveSheet()->setCellValue('A'.($mulai++), $no++);
										$object->getActiveSheet()->setCellValue('B'.(($mulai)-1), substr($rows_realisasi_ro->tanggal, 0, 2));
										$object->getActiveSheet()->setCellValue('C'.(($mulai)-1), $nama_bulan[$rows_realisasi_ro->bln]);
										$object->getActiveSheet()->setCellValue('D'.(($mulai)-1), $rows_realisasi_ro->no_sp2d);
										$object->getActiveSheet()->setCellValue('E'.(($mulai)-1), $rows_realisasi_ro->uraian);
										$object->getActiveSheet()->setCellValue('F'.(($mulai)-1), $rows_realisasi_ro->nama_penerima);
										$object->getActiveSheet()->setCellValue('G'.(($mulai)-1), $rows_realisasi_ro->pagu);
										$object->getActiveSheet()->setCellValue('H'.(($mulai)-1), $rows_realisasi_ro->pencairan);
										if ($rows_spm->nama == 'Pajak Pertambahan Nilai ( PPN )') {
											$object->getActiveSheet()->setCellValue('I'.(($mulai)-1), $rows_spm->nilai);
										}
										if ($rows_spm->nama == 'Pajak Penghasilan Ps 21') {
											$object->getActiveSheet()->setCellValue('J'.(($mulai)-1), $rows_spm->nilai);
										}
										if ($rows_spm->nama == 'Pajak Penghasilan Ps 22') {
											$object->getActiveSheet()->setCellValue('K'.(($mulai)-1), $rows_spm->nilai);
										}
										if ($rows_spm->nama == 'Pajak Penghasilan Ps 23') {
											$object->getActiveSheet()->setCellValue('L'.(($mulai)-1), $rows_spm->nilai);
										}
										if ($rows_spm->nama == 'PP1') {
											$object->getActiveSheet()->setCellValue('M'.(($mulai)-1), $rows_spm->nilai);
										}
										$object->getActiveSheet()->setCellValue('N'.(($mulai)-1), '=SUM(I'.$mulai.':M'.$mulai.')');
										$object->getActiveSheet()->setCellValue('O'.(($mulai)-1), '=H'.$mulai.'+N'.$mulai.'');

										$object->getActiveSheet()->setCellValue('E'.($mulai), $rows_realisasi_ro->kd_rekening);
										$object->getActiveSheet()->setCellValue('F'.($mulai), $rows_realisasi_ro->nama_rekening);
										$object->getActiveSheet()->setCellValue('G'.($mulai), $rows_realisasi_ro->pagu);
										$object->getActiveSheet()->setCellValue('H'.($mulai), $rows_realisasi_ro->pencairan);





										// SETUP
										$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':O'.($mulai))->getAlignment()->setWrapText(true);
										$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':O'.($mulai))->getFont()->setSize(7);

										$object->getActiveSheet()->getStyle('G'.(($mulai)-1).':O'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

										$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':D'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
										$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':D'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
										$object->getActiveSheet()->getStyle('E'.(($mulai)-1).':F'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
										$object->getActiveSheet()->getStyle('E'.(($mulai)-1).':F'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
										$object->getActiveSheet()->getStyle('G'.(($mulai)-1).':O'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
										$object->getActiveSheet()->getStyle('G'.(($mulai)-1).':O'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

										$object->getActiveSheet()->getStyle('A'.(($mulai)-1).':O'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
									

										$mulai++;
									}
								}
							}
						}
					}
				}
				else{
					$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

					foreach ($table_data as $data_table) {
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
						$object->getActiveSheet()->getStyle('A7:O'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						$mulai++;
					}
				}

				// Values

				$object->getActiveSheet()->setCellValue('G'.($mulai), 'TOTAL');
				$object->getActiveSheet()->setCellValue('H'.($mulai), '=SUM(H7:H'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('I'.($mulai), '-');
				$object->getActiveSheet()->setCellValue('J'.($mulai), '-');
				$object->getActiveSheet()->setCellValue('K'.($mulai), '-');
				$object->getActiveSheet()->setCellValue('L'.($mulai), '-');
				$object->getActiveSheet()->setCellValue('M'.($mulai), '-');
				$object->getActiveSheet()->setCellValue('N'.($mulai), '-');
				$object->getActiveSheet()->setCellValue('O'.($mulai), '-');


				// SETUP

				$object->getActiveSheet()->getStyle('A'.$mulai.':O'.$mulai)->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('A'.$mulai.':O'.$mulai)->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('H'.$mulai)->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('A'.($mulai).':O'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A'.($mulai).':O'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.$mulai.':O'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A'.$mulai.':O'.$mulai)->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
			}
			if ($jenis == 2) {

				// -------- Title Form -------- //
				$title_form = 'PEMERINTAH KABUPATEN PONOROGO';
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:H1');
				$object->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Subtitle Form -------- //
				$subtitle_form = 'BUKU KAS UMUM';
				$object->getActiveSheet()->setCellValue('A2', $subtitle_form);
				$object->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A2:H2');
				$object->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Nama Organisasi -------- //
				$info_organisasi = 'SKPD ';
				$nama_organisasi = ': SEKRETARIAT DAERAH';
				$object->getActiveSheet()->setCellValue('A4', $info_organisasi);
				$object->getActiveSheet()->setCellValue('C4', $nama_organisasi);
				$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A4:B4');
				$object->getActiveSheet()->mergeCells('C4:H4');
				$object->getActiveSheet()->getStyle('A4:H4')->getFont()->setBold(TRUE);

				// -------- Nama Bagian -------- //
				$info_bagian = 'Bagian ';
				$nama_bagian = ': ADMINISTRASI PEMBANGUNAN';
				$object->getActiveSheet()->setCellValue('A5', $info_bagian);
				$object->getActiveSheet()->setCellValue('C5', $nama_bagian);
				$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C5')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A5:B5');
				$object->getActiveSheet()->mergeCells('C5:H5');
				$object->getActiveSheet()->getStyle('A5:H5')->getFont()->setBold(TRUE);

				// -------- Kode Organisasi -------- //
				$info_kode_organisasi = 'Kode Organisasi ';
				$nama_kode_organisasi = ': 1.06.1.20.03';
				$object->getActiveSheet()->setCellValue('A6', $info_kode_organisasi);
				$object->getActiveSheet()->setCellValue('C6', $nama_kode_organisasi);
				$object->getActiveSheet()->getStyle('A6')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C6')->getFont()->setSize(10); 
				$object->getActiveSheet()->mergeCells('A6:B6');
				$object->getActiveSheet()->mergeCells('C6:H6');
				$object->getActiveSheet()->getStyle('A6:H6')->getFont()->setBold(TRUE);

				// -------- PPTK -------- //
				$info_pptk = 'Nama PPTK ';
				$nama_pptk = ': WIDODO PUTRO, S.Sos., SKM., M.M.';
				$object->getActiveSheet()->setCellValue('A7', $info_pptk);
				$object->getActiveSheet()->setCellValue('C7', $nama_pptk);
				$object->getActiveSheet()->getStyle('A7')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C7')->getFont()->setSize(10); 
				$object->getActiveSheet()->mergeCells('A7:B7');
				$object->getActiveSheet()->mergeCells('C7:H7');
				$object->getActiveSheet()->getStyle('A7:H7')->getFont()->setBold(TRUE);

				// -------- Bagian Bulan -------- //
				$info_bagian_bulan = 'Bagian Bulan ';
				$nama_bagian_bulan = ': Desember 2018';
				$object->getActiveSheet()->setCellValue('A8', $info_bagian_bulan);
				$object->getActiveSheet()->setCellValue('C8', $nama_bagian_bulan);
				$object->getActiveSheet()->getStyle('A8')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C8')->getFont()->setSize(10); 
				$object->getActiveSheet()->mergeCells('A8:B8');
				$object->getActiveSheet()->mergeCells('C8:H8');
				$object->getActiveSheet()->getStyle('A8:H8')->getFont()->setBold(TRUE);

				// TABLE HEADER
				$table_header = array("No", "Tanggal", "Kode Rekening", "Uraian", "-", "Pencairan", "-", "Total");
				$start_column = 0;
				foreach ($table_header as $thead) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column, 10, $thead);
					$start_column++;
				}
				$object->getActiveSheet()->getStyle('A10:H10')->getFont()->setSize(9);
				$object->getActiveSheet()->getStyle('A10:H10')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A10:H10')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A10:H10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A10:H10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A10:H10')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
				$object->getActiveSheet()->getStyle('A10:H10')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));

				// Values

				$no = 1;
				$mulai = 11;
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				if ($result_skpd->num_rows() > 0) {
					foreach ($result_skpd->result() as $rows_skpd) {
						$result_kegiatan = $this->model->getDataKegiatanUnique($rows_skpd->kd_skpd, $kegiatan);
						foreach ($result_kegiatan->result() as $rows_kegiatan) {
							$result_realisasi_ro = $this->model->getDataRealisasiRO($rows_skpd->kd_skpd, $rows_kegiatan->kd_gabungan, $bulan);
							foreach ($result_realisasi_ro->result() as $rows_realisasi_ro) {
								if ($rows_realisasi_ro->tanggal != '' || $rows_realisasi_ro->bln != '' || $rows_realisasi_ro->no_sp2d != '' || $rows_realisasi_ro->uraian != '' || $rows_realisasi_ro->nama_penerima != '' || $rows_realisasi_ro->pagu != '' || $rows_realisasi_ro->pencairan != '') {
									
									// Values

									$object->getActiveSheet()->setCellValue('A'.$mulai, $no++);
									$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_realisasi_ro->tanggal);
									$object->getActiveSheet()->setCellValue('C'.$mulai, $rows_realisasi_ro->kd_rekening);
									$object->getActiveSheet()->setCellValue('D'.$mulai, 'Terima SP2D No.'.$rows_realisasi_ro->no_sp2d.' tanggal '.$rows_realisasi_ro->tanggal);
									$object->getActiveSheet()->setCellValue('E'.$mulai, 'Rp.');
									$object->getActiveSheet()->setCellValue('F'.$mulai, $rows_realisasi_ro->pencairan);
									$object->getActiveSheet()->setCellValue('G'.$mulai, 'Rp.');
									$object->getActiveSheet()->setCellValue('H'.$mulai, '=F'.$mulai.'+0');



									// SETUP
									$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getAlignment()->setWrapText(true);
									$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getFont()->setSize(7);

									$object->getActiveSheet()->getStyle('F'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
									$object->getActiveSheet()->getStyle('H'.$mulai)->getNumberFormat()->setFormatCode('#,##0');

									$object->getActiveSheet()->getStyle('A'.($mulai).':C'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('A'.($mulai).':C'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('D'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$object->getActiveSheet()->getStyle('D'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('E'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('E'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('F'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									$object->getActiveSheet()->getStyle('F'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('G'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$object->getActiveSheet()->getStyle('G'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
									$object->getActiveSheet()->getStyle('H'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									$object->getActiveSheet()->getStyle('H'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

									$object->getActiveSheet()->getStyle('A'.($mulai).':H'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));

									$mulai++;
								}
							}
						}
					}
				}
				else{
					$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

					foreach ($table_data as $data_table) {
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
						$object->getActiveSheet()->getStyle('A11:H'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						$mulai++;
					}
				}

				// Values

				$object->getActiveSheet()->setCellValue('D'.($mulai), 'TOTAL JUMLAH');
				$object->getActiveSheet()->setCellValue('E'.($mulai), 'Rp.');
				$object->getActiveSheet()->setCellValue('F'.($mulai), '=SUM(F11:F'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('G'.($mulai), 'Rp.');
				$object->getActiveSheet()->setCellValue('H'.($mulai), '=SUM(H11:H'.(($mulai)-1).')');


				// SETUP

				$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('F'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('H'.$mulai)->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('D'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$object->getActiveSheet()->getStyle('D'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('E'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('E'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('F'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$object->getActiveSheet()->getStyle('F'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('G'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('G'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('H'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$object->getActiveSheet()->getStyle('H'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A'.$mulai.':H'.$mulai)->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
			}



			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="'.$nama_jenis.'.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="'.$nama_jenis.'.xls"');
				$object_writer->save('php://output');
			}
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
	}
}