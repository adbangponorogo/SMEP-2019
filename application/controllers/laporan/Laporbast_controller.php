<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once APPPATH."/third_party/PHPWord/bootstrap.php";

require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
// use PhpOffice\PhpWord\TemplateProcessor;

class Laporbast_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laporbast_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/berita-acara-serah-terima/data');
		}
		else{
            redirect(base_url());
        }
	}

	public function getMainData($id_skpd){ 
		if ($this->session->userdata('auth_id') != "") {
			$result_rup = $this->model->getDataRUP($id_skpd);
			$data = array();
			$no = 1;
			foreach ($result_rup->result() as $rows_rup) {
				$result_realisasi = $this->model->getDataRealisasiRUP($rows_rup->id);
				foreach ($result_realisasi->result() as $rows_realisasi) {
					if ($rows_realisasi->nomor_surat != '' && $rows_realisasi->tanggal_surat_serah_terima != '') {
						$aksi = "<form action='laporan/Laporbast_controller/getPrintBastData' method='POST'>".
									"<input type='hidden' name='id' value='".$rows_realisasi->id."'>".
									"<input type='hidden' name='kd_skpd' value='".$rows_rup->kd_skpd."'>".
									"<button class='btn btn-info btn-sm smep-bastlapor-bast-btn'>Bast</button>".
								"</form>&nbsp;".
								"<form action='laporan/Laporbast_controller/getPrintLampiranBastData' method='POST'>".
									"<input type='hidden' name='id' value='".$rows_realisasi->id."'>".
									"<input type='hidden' name='kd_skpd' value='".$rows_rup->kd_skpd."'>".
	                				"<button class='btn btn-primary btn-sm smep-bastlapor-lampiranbast-btn'>Lampiran Bast</button>".
	                			"</form>";
					}
					if ($rows_realisasi->nomor_surat == '' && $rows_realisasi->tanggal_surat_serah_terima == '') {
						$aksi = 'Nomor dan Tanggal BAST Belum dientry-kan';
					}
					$data[] = array(
								$no++,
								$rows_rup->nama_paket,
								$this->nullValue($rows_realisasi->nomor_surat)."<br>".$this->nullValue($rows_realisasi->tanggal_surat_serah_terima),
								"Rp. ".number_format($this->nullIntValue($rows_realisasi->nilai_kontrak)),
								"Rp. ".number_format($this->nullIntValue($rows_realisasi->realisasi_keuangan)),
								$this->nullValue($rows_realisasi->nama_pemenang),
								$aksi
							);
				}
			}
			echo json_encode($data);
		}
		else{
            redirect(base_url());
        }
	}

	public function getPrintBastData(){
		if ($this->session->userdata('auth_id') != '') {
			$id_realisasi = $this->input->post("id");
			$kd_skpd = $this->input->post("kd_skpd");
			$nama_pimpinan = ["", "", "PENGGUNA ANGGARAN", "", "KUASA PENGGUNA ANGGARAN"];

			$result_skpd = $this->model->getDataSKPDByKD($kd_skpd);
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_realisasi = $this->model->getDataRealisasiByID($id_realisasi);
				foreach ($result_realisasi->result() as $rows_realisasi) {
					$result_rup = $this->model->getDataRUPByID($rows_realisasi->id_rup);
					foreach ($result_rup->result() as $rows_rup) {
						$result_struktur_anggaran = $this->model->getDataSirupMasterRUP($rows_skpd->kd_skpd);
						foreach ($result_struktur_anggaran->result() as $rows_struktur_anggaran) {
							$result_master_rup = $this->model->getDataTBMasterRUP($rows_skpd->id);
							foreach ($result_master_rup->result() as $rows_master_rup) {
								$result_pptk = $this->model->getDataPPTK($rows_skpd->id, $rows_master_rup->sts_pimpinan);
								foreach ($result_pptk->result() as $rows_pptk) {
									$result_ppk = $this->model->getDataPPK($rows_rup->id_user_ppk);
									foreach ($result_ppk->result() as $rows_ppk) {
										// SKPD
										$nama_skpd			= $rows_skpd->nama_skpd;
										$alamat_skpd		= $rows_struktur_anggaran->alamat;
										$kd_pos				= $rows_master_rup->kode_pos;
										$pmp_nama			= $rows_pptk->nama;
										$pmp_nip			= $rows_pptk->nip;
										$pmp_pangkat		= $rows_pptk->pangkat;
										$pmp_gol			= $rows_pptk->golongan;
										$pmp_jabatan		= $rows_pptk->jabatan;
										$pimpinan 			= $nama_pimpinan[$rows_master_rup->sts_pimpinan];
										$ppk_nama			= $rows_ppk->nama;
										$ppk_nip			= $rows_ppk->nip;
										$ppk_pangkat		= $rows_ppk->pangkat;
										$ppk_gol			= $rows_ppk->golongan;
										$ppk_jabatan		= $rows_ppk->jabatan;


										// RUP
										$tahun				= $rows_rup->tahun;
										$no_bast			= $rows_realisasi->nomor_surat;
										$nilai_kontrak		= $rows_realisasi->nilai_kontrak;
										$tanggal_bast		= $rows_realisasi->tanggal_surat_serah_terima;
									}
								}
							}
						}
					}
				}
			}

			$phpWord = new \PhpOffice\PhpWord\PhpWord();
			$object = $phpWord->loadTemplate('custom/tpl/bast.docx');

			$tanggal_bap = explode("-", $tanggal_bast);
			$bulan = $tanggal_bap[1];
			if ($bulan == "01") {$nama_bulan="JANUARI";}if ($bulan == "02") {$nama_bulan="FEBRUARI";}if ($bulan == "03") {$nama_bulan="MARET";}if ($bulan == "04") {$nama_bulan="APRIL";}if ($bulan == "05") {$nama_bulan="MEI";}if ($bulan == "06") {$nama_bulan="JUNI";}if ($bulan == "07") {$nama_bulan="JULI";}if ($bulan == "08") {$nama_bulan="AGUSTUS";}if ($bulan == "09") {$nama_bulan="SEPTEMBER";}if ($bulan == "10") {$nama_bulan="OKTOBER";}if ($bulan == "11") {$nama_bulan="NOVEMBER";}if ($bulan == "12") {$nama_bulan="DESEMBER";}

			// SKPD
			$object->setValue('skpd', $nama_skpd);
			$object->setValue('alamat', $alamat_skpd);
			$object->setValue('kd_pos', $kd_pos);
			$object->setValue('nama_kepala', $pmp_nama);
			$object->setValue('nip_kepala', $pmp_nip);
			$object->setValue('pangkat_kepala', $pmp_pangkat);
			$object->setValue('gol_kepala', $pmp_gol);
			$object->setValue('jabatan_kepala', $pmp_jabatan);
			$object->setValue('nama_ppk', $ppk_nama);
			$object->setValue('nip_ppk', $ppk_nip);
			$object->setValue('pangkat_ppk', $ppk_pangkat);
			$object->setValue('gol_ppk', $ppk_gol);
			$object->setValue('jabatan_ppk', $ppk_jabatan);
			$object->setValue('kuasa', $pimpinan);

			// RUP
			$object->setValue('tahun', $tahun);
			$object->setValue('no_bast', $no_bast);
			$object->setValue('nilai_kontrak', number_format($nilai_kontrak, 2));
			$object->setValue('terbilang', trim($this->terbilang($nilai_kontrak)));
			$object->setValue('tanggal', $tanggal_bap[0]." ".ucfirst(strtolower($nama_bulan))." ".$tanggal_bap[2]);
			$object->setValue('tgl', $tanggal_bap[0]);
			$object->setValue('bln', ucfirst(strtolower($nama_bulan)));
			$object->setValue('thn', $tanggal_bap[2]);

			$nama_skpd = str_replace([" ", ",", "."], "-", $nama_skpd);
			header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
			header("Content-Disposition: attachment; filename='BAST_".strtoupper($nama_skpd).".docx'");
			$object->saveAs('php://output');
		}
		else{
            redirect(base_url());
        }
	}

	public function getPrintLampiranBastData(){
		if ($this->session->userdata('auth_id') != '') {
			$id_realisasi = $this->input->post("id");
			$kd_skpd = $this->input->post("kd_skpd");
			$nama_pimpinan = ["", "", "PENGGUNA ANGGARAN", "", "KUASA PENGGUNA ANGGARAN"];

			// -------- PAPER Setup -------- //
			$this->load->library("Excel");
			$object =  new PHPExcel();
			$object->setActiveSheetIndex(0);

			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);
			$object->getActiveSheet()->getSheetView()->setZoomScale(80);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | LAMPIRAN BAST &R&P');

			$object->getActiveSheet()->getColumnDimension('A')->setWidth(11.71);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(43.29);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(10.43);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(12.43);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(12.57);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(12.14);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(21.00);
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(14.57);
			$object->getActiveSheet()->getColumnDimension('I')->setWidth(11.86);
			$object->getActiveSheet()->getColumnDimension('J')->setWidth(11.71);
			$object->getActiveSheet()->getColumnDimension('K')->setWidth(11.14);
			$object->getActiveSheet()->getColumnDimension('L')->setWidth(7.14);
			$object->getActiveSheet()->getColumnDimension('M')->setWidth(11.71);
			$object->getActiveSheet()->getColumnDimension('N')->setWidth(10.43);

			$object->getActiveSheet()->getRowDimension('5')->setRowHeight(25.50);
			$object->getActiveSheet()->getRowDimension('6')->setRowHeight(33.00);

			// -------- Data Title Form -------- //
			$result_title_realisasi = $this->model->getDataRealisasiByID($id_realisasi);
			foreach ($result_title_realisasi->result() as $rows_title_realisasi) {
				$nomor_surat = $rows_title_realisasi->nomor_surat;
				$tanggal_bap = explode("-", $rows_title_realisasi->tanggal_surat_serah_terima);
			}

			// -------- Title Form -------- //
			$object_form = 'LAMPIRAN';
			$object->getActiveSheet()->setCellValue('J1', $object_form);
			$object->getActiveSheet()->getStyle('J1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('J1')->getFont()->setSize(12);

			$title_form = ': BERITA ACARA PENYERAHAN';
			$object->getActiveSheet()->setCellValue('K1', $title_form);
			$object->getActiveSheet()->mergeCells('K1:N1');
			$object->getActiveSheet()->getStyle('K1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('K1')->getFont()->setSize(12);


			// -------- Nomor Form -------- //
			$object_nomor_form = 'NOMOR';
			$object->getActiveSheet()->setCellValue('J2', $object_nomor_form);
			$object->getActiveSheet()->getStyle('J2')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('J2')->getFont()->setSize(12);

			$nomor_form = ': '.$nomor_surat;
			$object->getActiveSheet()->setCellValue('K2', $nomor_form);
			$object->getActiveSheet()->mergeCells('K2:N2');
			$object->getActiveSheet()->getStyle('K2')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('K2')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('K2')->getFont()->setSize(12);


			// -------- Tanggal Form -------- //
			$object_tanggal_form = 'TANGGAL';
			$object->getActiveSheet()->setCellValue('J3', $object_tanggal_form);
			$object->getActiveSheet()->getStyle('J3')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('J3')->getFont()->setSize(12);

			$tanggal_form = ': '.$tanggal_bap[0]." ".$this->getBulan($tanggal_bap[1])." ".$tanggal_bap[2];
			$object->getActiveSheet()->setCellValue('K3', $tanggal_form);
			$object->getActiveSheet()->mergeCells('K3:N3');
			$object->getActiveSheet()->getStyle('K3')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('K3')->getFont()->setSize(12);

			$thead_first = array("KODE REKENING", "PROGRAM / KEGIATAN", "SUMBER DANA", "PAGU (Rp.)", "VOLUME", "NILAI KONTRAK (Rp.)", "TANGGAL & NOMOR KONTRAK", "PENYEDIA BARANG JASA", " WAKTU PELAKSANAAN", "", "REALISASI", "", "SISTEM PENGADAAN", "HASIL KEGIATAN");
			$thead_second = array("MULAI", "SELESAI", "KEUANGAN (Rp.)", "FISIK (%)");
			
			$thead_first_start = 0;
			foreach ($thead_first as $header_first) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($thead_first_start, 5, $header_first);
				$thead_first_start++;
			}

			$thead_second_start = 8;
			foreach ($thead_second as $header_second) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($thead_second_start, 6, $header_second);
				$thead_second_start++;
			}


			$object->getActiveSheet()->mergeCells('A5:A6');
			$object->getActiveSheet()->mergeCells('B5:B6');
			$object->getActiveSheet()->mergeCells('C5:C6');
			$object->getActiveSheet()->mergeCells('D5:D6');
			$object->getActiveSheet()->mergeCells('E5:E6');
			$object->getActiveSheet()->mergeCells('F5:F6');
			$object->getActiveSheet()->mergeCells('G5:G6');
			$object->getActiveSheet()->mergeCells('H5:H6');
			$object->getActiveSheet()->mergeCells('I5:J5');
			$object->getActiveSheet()->mergeCells('K5:L5');
			$object->getActiveSheet()->mergeCells('M5:M6');
			$object->getActiveSheet()->mergeCells('N5:N6');

			$object->getActiveSheet()->getRowDimension('7')->setRowHeight(8.25);

			$object->getActiveSheet()->getStyle('A5:N7')->getFont()->setSize(8);
			$object->getActiveSheet()->getStyle('A5:N7')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A5:N7')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A5:N7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A5:N7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('A5:N7')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A5:N6')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');




			$mulai = 8;
			$sumber_dana = ["", "APBD","APBDP","APBN","APBNP","BLU","BLUD","BUMD","BUMN","PHLN","PNBP","Lainnya"];
			$cara_pengadaan = ["", "Penyedia", "Swakelola"];
			$result_skpd = $this->model->getDataSKPDByKD($kd_skpd);
			foreach ($result_skpd->result() as $rows_skpd) {

				// ===== PROGRAM ===== //

				$result_program = $this->model->getDataProgram($rows_skpd->kd_skpd, $id_realisasi);
				foreach ($result_program->result() as $rows_program) {
					// -------- Value ---------
					$object->getActiveSheet()->setCellValue('A'.($mulai), $rows_program->kd_gabungan);
					$object->getActiveSheet()->setCellValue('B'.($mulai), $rows_program->keterangan_program);
					// echo $rows_program->kd_gabungan."/".$rows_program->keterangan_program;
						

					// -------- Object ---------
					$object->getActiveSheet()->getRowDimension($mulai)->setRowHeight(38.25);

					// --- A ---
					$object->getActiveSheet()->getStyle('A'.($mulai))->getFont()->setSize(11);
					$object->getActiveSheet()->getStyle('A'.($mulai))->getFont()->setBold(TRUE);
					$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$object->getActiveSheet()->getStyle('A'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						
					// --- B ---
					$object->getActiveSheet()->getStyle('B'.($mulai))->getFont()->setSize(11);
					$object->getActiveSheet()->getStyle('B'.($mulai))->getFont()->setBold(TRUE);
					$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setWrapText(true);
					$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$object->getActiveSheet()->getStyle('B'.($mulai))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$object->getActiveSheet()->getStyle('A'.($mulai).':N'.($mulai))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));




					// ===== KEGIATAN ===== //

					$result_kegiatan = $this->model->getDataKegiatan($rows_skpd->kd_skpd, $id_realisasi);
					foreach ($result_kegiatan->result() as $rows_kegiatan) {
						// -------- Value ---------
						$object->getActiveSheet()->setCellValue('A'.(($mulai)+1), $rows_kegiatan->kd_gabungan);
						$object->getActiveSheet()->setCellValue('B'.(($mulai)+1), $rows_kegiatan->keterangan_kegiatan);


						// -------- Object ---------
						$object->getActiveSheet()->getRowDimension(($mulai)+1)->setRowHeight(38.25);

						// --- A ---
						$object->getActiveSheet()->getStyle('A'.(($mulai)+1))->getFont()->setSize(11);
						$object->getActiveSheet()->getStyle('A'.(($mulai)+1))->getFont()->setBold(TRUE);
						$object->getActiveSheet()->getStyle('A'.(($mulai)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getStyle('A'.(($mulai)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							
						// --- B ---
						$object->getActiveSheet()->getStyle('B'.(($mulai)+1))->getFont()->setSize(11);
						$object->getActiveSheet()->getStyle('B'.(($mulai)+1))->getFont()->setBold(TRUE);
						$object->getActiveSheet()->getStyle('B'.(($mulai)+1))->getAlignment()->setWrapText(true);
						$object->getActiveSheet()->getStyle('B'.(($mulai)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$object->getActiveSheet()->getStyle('B'.(($mulai)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

						$object->getActiveSheet()->getStyle('A'.(($mulai)+1).':N'.(($mulai)+1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));




						// ===== REALISASI/RUP ===== //

						$result_realisasi = $this->model->getDataRealisasiRUPByIDExcel($rows_skpd->kd_skpd, $id_realisasi);
						foreach ($result_realisasi->result() as $rows_realisasi) {
							// -------- Value ---------
							$object->getActiveSheet()->setCellValue('B'.((($mulai)+1)+1), $rows_realisasi->nama_paket);
							$object->getActiveSheet()->setCellValue('C'.((($mulai)+1)+1), $sumber_dana[$rows_realisasi->sumber_dana]);
							$object->getActiveSheet()->setCellValue('D'.((($mulai)+1)+1), $rows_realisasi->pagu_paket+0);
							$object->getActiveSheet()->setCellValue('E'.((($mulai)+1)+1), $rows_realisasi->volume_pekerjaan);
							$object->getActiveSheet()->setCellValue('F'.((($mulai)+1)+1), $rows_realisasi->nilai_kontrak+0);
							$object->getActiveSheet()->setCellValue('G'.((($mulai)+1)+1), $rows_realisasi->tanggal_kontrak." ".$rows_realisasi->nomor_kontrak);
							$object->getActiveSheet()->setCellValue('H'.((($mulai)+1)+1), $rows_realisasi->nama_pemenang);
							$object->getActiveSheet()->setCellValue('I'.((($mulai)+1)+1), $rows_realisasi->tanggal_spmk);
							$object->getActiveSheet()->setCellValue('J'.((($mulai)+1)+1), $rows_realisasi->tanggal_surat_serah_terima);
							$object->getActiveSheet()->setCellValue('K'.((($mulai)+1)+1), $rows_realisasi->realisasi_keuangan+0);
							$object->getActiveSheet()->setCellValue('L'.((($mulai)+1)+1), $rows_realisasi->realisasi_fisik);
							$object->getActiveSheet()->setCellValue('M'.((($mulai)+1)+1), $cara_pengadaan[$rows_realisasi->cara_pengadaan]);
							$object->getActiveSheet()->setCellValue('N'.((($mulai)+1)+1), 'Baik');


							// -------- Object ---------
							$object->getActiveSheet()->getRowDimension((($mulai)+1)+1)->setRowHeight(38.25);
								
							// --- All Column ---
							$object->getActiveSheet()->getStyle('B'.((($mulai)+1)+1).':N'.((($mulai)+1)+1))->getFont()->setSize(10);
							$object->getActiveSheet()->getStyle('B'.((($mulai)+1)+1).':N'.((($mulai)+1)+1))->applyFromArray(array('font' => array('name' => 'Arial')));
							// $object->getActiveSheet()->getStyle('B'.((($mulai)+1)+1).':N'.((($mulai)+1)+1))->getFont()->setBold(TRUE);
							$object->getActiveSheet()->getStyle('B'.((($mulai)+1)+1).':N'.((($mulai)+1)+1))->getAlignment()->setWrapText(true);
							$object->getActiveSheet()->getStyle('B'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$object->getActiveSheet()->getStyle('B'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('C'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('C'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('D'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$object->getActiveSheet()->getStyle('D'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('E'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('E'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('F'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$object->getActiveSheet()->getStyle('F'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('G'.((($mulai)+1)+1).':J'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('G'.((($mulai)+1)+1).':J'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('K'.((($mulai)+1)+1).':L'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$object->getActiveSheet()->getStyle('K'.((($mulai)+1)+1).':L'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$object->getActiveSheet()->getStyle('M'.((($mulai)+1)+1).':N'.((($mulai)+1)+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$object->getActiveSheet()->getStyle('M'.((($mulai)+1)+1).':N'.((($mulai)+1)+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

							$object->getActiveSheet()->getStyle('D'.((($mulai)+1)+1))->getNumberFormat()->setFormatCode('#,##0');
							$object->getActiveSheet()->getStyle('F'.((($mulai)+1)+1))->getNumberFormat()->setFormatCode('#,##0');
							$object->getActiveSheet()->getStyle('K'.((($mulai)+1)+1))->getNumberFormat()->setFormatCode('#,##0');
							$object->getActiveSheet()->getStyle('A'.((($mulai)+1)+1).':N'.((($mulai)+1)+1))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						}


					}
				}
			}


			// Footer Table
			$object->getActiveSheet()->getRowDimension(($mulai)+3)->setRowHeight(8.25);
			$object->getActiveSheet()->getStyle('A'.(($mulai)+3).':N'.(($mulai)+3))->getFont()->setSize(8);
			$object->getActiveSheet()->getStyle('A'.(($mulai)+3).':N'.(($mulai)+3))->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A'.(($mulai)+3).':N'.(($mulai)+3))->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A'.(($mulai)+3).':N'.(($mulai)+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A'.(($mulai)+3).':N'.(($mulai)+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('A'.(($mulai)+3).':N'.(($mulai)+3))->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));



			// Bagian Tanda Tangan PPTK
			$result_ttd_skpd = $this->model->getDataSKPDByKD($kd_skpd);
			foreach ($result_ttd_skpd->result() as $rows_ttd_skpd) {
				$result_ttd_master_rup = $this->model->getDataTBMasterRUP($rows_ttd_skpd->id);
				foreach ($result_ttd_master_rup->result() as $rows_ttd_master_rup) {
					$result_ttd_pptk = $this->model->getDataPPTK($rows_ttd_master_rup->id_skpd, $rows_ttd_master_rup->sts_pimpinan);
					foreach ($result_ttd_pptk->result() as $rows_ttd_pptk) {
						$kuasa_pptk = $nama_pimpinan[$rows_ttd_pptk->status];
						$nama_pptk = $rows_ttd_pptk->nama;
						$pangkat_pptk = $rows_ttd_pptk->pangkat;
						$nip_pptk = $rows_ttd_pptk->nip;
					}
				}
			}

			$ttd_pertama_title 		= 'PIHAK KEDUA';
			$ttd_pertama_kuasa 		= $kuasa_pptk;
			$ttd_pertama_nama 		= $nama_pptk;
			$ttd_pertama_pangkat	= $pangkat_pptk;
			$ttd_pertama_nip		= 'NIP. '.$nip_pptk; 
			$object->getActiveSheet()->setCellValue('B'.((($mulai)+3)+4), $ttd_pertama_title);
			$object->getActiveSheet()->setCellValue('B'.((($mulai)+3)+5), $ttd_pertama_kuasa);
			$object->getActiveSheet()->setCellValue('B'.((($mulai)+3)+10), $ttd_pertama_nama);
			$object->getActiveSheet()->setCellValue('B'.((($mulai)+3)+11), $ttd_pertama_pangkat);
			$object->getActiveSheet()->setCellValue('B'.((($mulai)+3)+12), $ttd_pertama_nip);
			
			$object->getActiveSheet()->getStyle('B'.((($mulai)+3)+10))->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('B'.((($mulai)+3)+4).':B'.((($mulai)+3)+12))->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('B'.((($mulai)+3)+4).':B'.((($mulai)+3)+12))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('B'.((($mulai)+3)+4).':B'.((($mulai)+3)+12))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('B'.((($mulai)+3)+4).':B'.((($mulai)+3)+12))->getFont()->setSize(12);




			// Bagian Tanda Tangan PPK
			$result_ppk_realisasi = $this->model->getDataRealisasiByID($id_realisasi);
			foreach ($result_ppk_realisasi->result() as $rows_ppk_realisasi) {
				$result_ppk_rup = $this->model->getDataRUPByID($rows_ppk_realisasi->id_rup);
				foreach ($result_ppk_rup->result() as $rows_ppk_rup) {
					$result_ppk_datappk = $this->model->getDataPPK($rows_ppk_rup->id_user_ppk);
					foreach ($result_ppk_datappk->result() as $rows_ppk_datappk) {
						$nama_ppk = $rows_ppk_datappk->nama;
						$pangkat_ppk = $rows_ppk_datappk->pangkat;
						$nip_ppk = $rows_ppk_datappk->nip;
					}
				}
			}

			$ttd_kedua_tgl 			= 'Ponorogo, '.$tanggal_bap[0]." ".$this->getBulan($tanggal_bap[1])." ".$tanggal_bap[2];
			$ttd_kedua_title 		= 'PIHAK KEDUA';
			$ttd_kedua_kuasa 		= 'PEJABAT PEMBUAT KOMITMEN';
			$ttd_kedua_nama 		= $nama_ppk;
			$ttd_kedua_pangkat		= $pangkat_ppk;
			$ttd_kedua_nip			= 'NIP. '.$nip_ppk; 
			$object->getActiveSheet()->setCellValue('J'.((($mulai)+3)+3), $ttd_kedua_tgl);
			$object->getActiveSheet()->setCellValue('J'.((($mulai)+3)+4), $ttd_kedua_title);
			$object->getActiveSheet()->setCellValue('J'.((($mulai)+3)+5), $ttd_kedua_kuasa);
			$object->getActiveSheet()->setCellValue('J'.((($mulai)+3)+10), $ttd_kedua_nama);
			$object->getActiveSheet()->setCellValue('J'.((($mulai)+3)+11), $ttd_kedua_pangkat);
			$object->getActiveSheet()->setCellValue('J'.((($mulai)+3)+12), $ttd_kedua_nip);
			

			$object->getActiveSheet()->mergeCells('J'.((($mulai)+3)+3).':L'.((($mulai)+3)+3));
			$object->getActiveSheet()->mergeCells('J'.((($mulai)+3)+4).':L'.((($mulai)+3)+4));
			$object->getActiveSheet()->mergeCells('J'.((($mulai)+3)+5).':L'.((($mulai)+3)+5));
			$object->getActiveSheet()->mergeCells('J'.((($mulai)+3)+10).':L'.((($mulai)+3)+10));
			$object->getActiveSheet()->mergeCells('J'.((($mulai)+3)+11).':L'.((($mulai)+3)+11));
			$object->getActiveSheet()->mergeCells('J'.((($mulai)+3)+12).':L'.((($mulai)+3)+12));

			$object->getActiveSheet()->getStyle('J'.((($mulai)+3)+10))->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('J'.((($mulai)+3)+3).':J'.((($mulai)+3)+12))->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('J'.((($mulai)+3)+3).':J'.((($mulai)+3)+12))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('J'.((($mulai)+3)+3).':J'.((($mulai)+3)+12))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('J'.((($mulai)+3)+3).':J'.((($mulai)+3)+12))->getFont()->setSize(12);





			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Lampiran_BAST.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Lampiran_BAST.xls"');
				$object_writer->save('php://output');	
			}
			
		}
		else{
            redirect(base_url());
        }
	}

	public function nullValue($value){
		if ($this->session->userdata('auth_id') != '') {
			if ($value == '' || is_null($value)) {
				$data = '-';
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

	public function nullIntValue($value){
		if ($this->session->userdata('auth_id') != '') {
			if ($value == '' || is_null($value)) {
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

	public function terbilang($x){
		$x = intval($x,10);
		$bilangan = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		if ($x < 12) {
			return " ".$bilangan[$x];
		}
		elseif ($x < 20){
 			return self::terbilang($x - 10) . " Belas";
		}
		elseif ($x < 100){
			return self::terbilang($x / 10) . " Puluh" . self::terbilang($x % 10);
		}
		elseif ($x < 200){
			return " Seratus" . terbilang($x - 100);
		}
		elseif ($x < 1000){
			return self::terbilang($x / 100) . " Ratus" . self::terbilang($x % 100);
		}
		elseif ($x < 2000){
			return " Seribu" . self::Terbilang($x - 1000);
		}
		elseif ($x < 1000000){
			return self::terbilang($x / 1000) . " Ribu" . self::terbilang($x % 1000);
		}
		elseif ($x < 1000000000) {
			return self::terbilang($x / 1000000) . " Juta" . self::terbilang($x % 1000000);
		}
	}

	public function getBulan($bulan){
		if ($this->session->userdata('auth_id') != '') {
			if ($bulan == "01") {$nama_bulan="JANUARI";}if ($bulan == "02") {$nama_bulan="FEBRUARI";}if ($bulan == "03") {$nama_bulan="MARET";}if ($bulan == "04") {$nama_bulan="APRIL";}if ($bulan == "05") {$nama_bulan="MEI";}if ($bulan == "06") {$nama_bulan="JUNI";}if ($bulan == "07") {$nama_bulan="JULI";}if ($bulan == "08") {$nama_bulan="AGUSTUS";}if ($bulan == "09") {$nama_bulan="SEPTEMBER";}if ($bulan == "10") {$nama_bulan="OKTOBER";}if ($bulan == "11") {$nama_bulan="NOVEMBER";}if ($bulan == "12") {$nama_bulan="DESEMBER";}
			return $nama_bulan;
		}
		else{
			redirect(base_url());
		}
	}
}