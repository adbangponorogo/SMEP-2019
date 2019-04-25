<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminrupaktual_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator/adminrupaktual_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata("auth_id") != "") {
			$this->load->view('pages/administrator/rup-aktual/data');
		}
		else{
			redirect(base_url());
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
		else{
			redirect(base_url());
		}
	}

	public function getPrintData(){
		if ($this->session->userdata("auth_id") != "") {
			$skpd = $this->input->post("skpd");
			$cara_pengadaan = $this->input->post("cara_pengadaan");
			$tahun = $this->input->post("tahun");
			$tanggal_cetak = $this->input->post("tanggal_cetak");

			if ($cara_pengadaan == 1) {
				$nama_cara_pengadaan = "Penyedia";
			}
			if ($cara_pengadaan == 2) {
				$nama_cara_pengadaan = "Swakelola";
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
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | Rekap RUP Aktual - '.$nama_cara_pengadaan.'&R&P');

			if ($cara_pengadaan == 1) {
				// -------- Manual Setting Autosize -------- //
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(3);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(31.29);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(21.14);
				$object->getActiveSheet()->getColumnDimension('D')->setWidth(10.57);
				$object->getActiveSheet()->getColumnDimension('E')->setWidth(14.14);
				$object->getActiveSheet()->getColumnDimension('F')->setWidth(12.57);
				$object->getActiveSheet()->getColumnDimension('G')->setWidth(14.43);
				$object->getActiveSheet()->getColumnDimension('H')->setWidth(14);
				$object->getActiveSheet()->getColumnDimension('I')->setWidth(21.86);

				// -------- Title Form -------- //
				$title_form = 'REKAPITULASI';
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:I1');
				$object->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// -------- Subtitle Form -------- //
				$subtitle_form_first = 'RENCANA UMUM PENGADAAN (RUP) MELALUI '.strtoupper($nama_cara_pengadaan);
				$subtitle_form_second = 'ORGANISASI PERANGKAT DAERAH DI LINGKUNGAN PEMERINTAH KABUPATEN PONOROGO';
				$subtitle_form_third = 'TAHUN ANGGARAN '.$tahun;
				$object->getActiveSheet()->setCellValue('A2', $subtitle_form_first);
				$object->getActiveSheet()->setCellValue('A3', $subtitle_form_second);
				$object->getActiveSheet()->setCellValue('A4', $subtitle_form_third);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A2:A4')->getFont()->setSize(12);
				$object->getActiveSheet()->mergeCells('A2:I2');
				$object->getActiveSheet()->mergeCells('A3:I3');
				$object->getActiveSheet()->mergeCells('A4:I4');
				$object->getActiveSheet()->getStyle('A2:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				// TABLE HEADER
				$table_title_head_row_first = array("NO", "ORGANISASI PERANGKAT DAERAH", "PAGU ANGGARAN", "PAGU RUP", "JENIS PENGADAAN", "", "", "", "JUMLAH", "KETERANGAN");
				$table_title_head_row_second = array("BARANG", "PEKERJAAN KONSTRUKSI", "JASA KONSULTASI", "JASA LAINNYA");

				$start_column_first = 0;
				foreach ($table_title_head_row_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 6, $thead_first);
					$start_column_first++;
				}
				$start_column_second = 4;
				foreach ($table_title_head_row_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 7, $thead_second);
					$start_column_second++;
				}

				// SETUP
				$object->getActiveSheet()->mergeCells('A6:A7');
				$object->getActiveSheet()->mergeCells('B6:B7');
				$object->getActiveSheet()->mergeCells('C6:C7');
				$object->getActiveSheet()->mergeCells('D6:D7');
				$object->getActiveSheet()->mergeCells('E6:H6');
				$object->getActiveSheet()->mergeCells('I6:I7');
				$object->getActiveSheet()->mergeCells('J6:J7');

				$object->getActiveSheet()->getStyle('A6:J7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:J7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:J7')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A6:J7')->getFont()->setSize(7);
				$object->getActiveSheet()->getStyle('A6:J7')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A6:J7')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A6:J7')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

				$no = 1;
				$mulai = 8;
				$result_skpd = $this->model->getDataSKPDUrutan();
				foreach ($result_skpd->result() as $rows_skpd) {
					$result_pagu_skpd = $this->model->getDataPaguSKPD($rows_skpd->kd_skpd);
					foreach ($result_pagu_skpd->result() as $rows_pagu_skpd) {
						$result_pagu = $this->model->getDataPaguRUP($rows_skpd->kd_skpd, $cara_pengadaan);
						foreach ($result_pagu->result() as $rows_pagu) {
							$result_barang = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $cara_pengadaan, 1);
							foreach ($result_barang->result() as $rows_barang) {
								$result_konstruksi = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $cara_pengadaan, 2);
								foreach ($result_konstruksi->result() as $rows_konstruksi) {
									$result_konsultasi = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $cara_pengadaan, 3);
									foreach ($result_konsultasi->result() as $rows_konsultasi) {
										$result_jasalainnya = $this->model->getDataPaketRUP($rows_skpd->kd_skpd, $cara_pengadaan, 4);
										foreach ($result_jasalainnya->result() as $rows_jasalainnya) {

											// Values

											$object->getActiveSheet()->setCellValue('A'.$mulai, $no++);
											$object->getActiveSheet()->setCellValue('B'.$mulai, $rows_skpd->nama_skpd);
											$object->getActiveSheet()->setCellValue('C'.$mulai, $this->nullValue($rows_pagu_skpd->pagu_paket));
											$object->getActiveSheet()->setCellValue('D'.$mulai, $this->nullValue($rows_pagu->pagu_paket));
											$object->getActiveSheet()->setCellValue('E'.$mulai, $this->nullValue($rows_barang->paket));
											$object->getActiveSheet()->setCellValue('F'.$mulai, $this->nullValue($rows_konstruksi->paket));
											$object->getActiveSheet()->setCellValue('G'.$mulai, $this->nullValue($rows_konsultasi->paket));
											$object->getActiveSheet()->setCellValue('H'.$mulai, $this->nullValue($rows_jasalainnya->paket));
											$object->getActiveSheet()->setCellValue('I'.$mulai, "=SUM(E".($mulai).":H".($mulai).")");
											$object->getActiveSheet()->setCellValue('J'.$mulai, "");


											// SETUP
											$object->getActiveSheet()->getStyle('A'.($mulai).':J'.($mulai))->getAlignment()->setWrapText(true);
											$object->getActiveSheet()->getStyle('A'.($mulai).':J'.($mulai))->getFont()->setSize(7);

											$object->getActiveSheet()->getStyle('C'.($mulai))->getNumberFormat()->setFormatCode('#,##0');
											$object->getActiveSheet()->getStyle('D'.($mulai))->getNumberFormat()->setFormatCode('#,##0');

											$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
											$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('C'.($mulai).':I'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
											$object->getActiveSheet()->getStyle('C'.($mulai).':I'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
											$object->getActiveSheet()->getStyle('J'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
											$object->getActiveSheet()->getStyle('J'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

											$object->getActiveSheet()->getStyle('A'.($mulai).':J'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));

										}
									}
								}
							}
						}
					}
					$mulai++;
				}

				// Values
				$result_total_pagu_skpd = $this->model->getDataPaguSKPD('all');
				foreach ($result_total_pagu_skpd->result() as $rows_total_pagu_skpd) {
					$total_pagu_skpd = $rows_total_pagu_skpd->pagu_paket;
				}
				$result_total_pagu_rup = $this->model->getDataPaguRUP('all', $cara_pengadaan);
				foreach ($result_total_pagu_rup->result() as $rows_total_pagu_rup) {
					$total_pagu_rup = $rows_total_pagu_rup->pagu_paket;
				}
				$object->getActiveSheet()->setCellValue('A'.($mulai), 'TOTAL');
				$object->getActiveSheet()->setCellValue('C'.($mulai), $total_pagu_skpd);
				$object->getActiveSheet()->setCellValue('D'.($mulai), $total_pagu_rup);
				$object->getActiveSheet()->setCellValue('E'.($mulai), '=SUM(E8:E'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('F'.($mulai), '=SUM(F8:F'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('G'.($mulai), '=SUM(G8:G'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('H'.($mulai), '=SUM(H8:H'.(($mulai)-1).')');
				$object->getActiveSheet()->setCellValue('I'.($mulai), '=SUM(I8:I'.(($mulai)-1).')');


				// SETUP

				$object->getActiveSheet()->getStyle('A'.$mulai.':J'.$mulai)->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A'.($mulai).':B'.($mulai));
				$object->getActiveSheet()->getStyle('A'.$mulai.':J'.$mulai)->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('C'.$mulai)->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('D'.$mulai)->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('A'.($mulai).':J'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A'.($mulai).':J'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A'.$mulai.':J'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$object->getActiveSheet()->getStyle('A'.$mulai.':J'.$mulai)->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

			}
			if ($cara_pengadaan == 2) {
				// -------- Manual Setting Autosize -------- //
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(7);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(41);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(28);
				$object->getActiveSheet()->getColumnDimension('D')->setWidth(9);
				$object->getActiveSheet()->getColumnDimension('E')->setWidth(10.71);
				$object->getActiveSheet()->getColumnDimension('F')->setWidth(9.86);
				$object->getActiveSheet()->getColumnDimension('G')->setWidth(14.71);
				$object->getActiveSheet()->getColumnDimension('H')->setWidth(13);
				$object->getActiveSheet()->getColumnDimension('I')->setWidth(7.57);
				$object->getActiveSheet()->getColumnDimension('J')->setWidth(9.43);
				$object->getActiveSheet()->getColumnDimension('K')->setWidth(11.14);
				$object->getActiveSheet()->getColumnDimension('L')->setWidth(8.57);
				$object->getActiveSheet()->getColumnDimension('M')->setWidth(9);

				// -------- Title Form -------- //
				$title_form = 'RENCANA UMUM PENGADAAN MELALUI '.strtoupper($nama_cara_pengadaan);
				$object->getActiveSheet()->setCellValue('A1', $title_form);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
				$object->getActiveSheet()->mergeCells('A1:M1');
				$object->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				// -------- Nama Organisasi -------- //
				$info_organisasi = 'NAMA ORGANISASI ';
				$nama_organisasi = ': '.strtoupper($nama_skpd);
				$object->getActiveSheet()->setCellValue('A3', $info_organisasi);
				$object->getActiveSheet()->setCellValue('C3', $nama_organisasi);
				$object->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C3')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A3:B3');
				$object->getActiveSheet()->mergeCells('C3:M3');
				$object->getActiveSheet()->getStyle('A3:M3')->getFont()->setBold(TRUE);

				// -------- Kabupaten -------- //
				$info_kabupaten = 'KABUPATEN ';
				$nama_kabupaten = ': PONOROGO';
				$object->getActiveSheet()->setCellValue('A4', $info_kabupaten);
				$object->getActiveSheet()->setCellValue('C4', $nama_kabupaten);
				$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A4:B4');
				$object->getActiveSheet()->mergeCells('C4:M4');
				$object->getActiveSheet()->getStyle('A4:M4')->getFont()->setBold(TRUE);

				// -------- Tahun Anggaran -------- //
				$info_anggaran = 'TAHUN ANGGARAN ';
				$nama_anggaran = ': '.$tahun;
				$object->getActiveSheet()->setCellValue('A5', $info_anggaran);
				$object->getActiveSheet()->setCellValue('C5', $nama_anggaran);
				$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
				$object->getActiveSheet()->getStyle('C5')->getFont()->setSize(10);
				$object->getActiveSheet()->mergeCells('A5:B5');
				$object->getActiveSheet()->mergeCells('C5:M5');
				$object->getActiveSheet()->getStyle('A5:M5')->getFont()->setBold(TRUE);


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
	 			}
	 			else{
	 				$table_data = array("NIHIL", "-", "-", "-", "-", "-", "-", "-", "-", "-");

					foreach ($table_data as $data_table) {
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $mulai, $data_table);
						$object->getActiveSheet()->getStyle('A9:M'.$mulai)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						$mulai++;
					}
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

			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - RUP Aktual '.$cara_pengadaan.'.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Rekap - RUP Aktual '.$cara_pengadaan.'.xls"');
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