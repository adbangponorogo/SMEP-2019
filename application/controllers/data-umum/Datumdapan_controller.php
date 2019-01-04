<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumdapan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('data-umum/datumdapan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/data-umum/pencairan-sppd/data');
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
		if ($this->session->userdata('auth_id') != '') {
			if ($id_skpd != 'all') {
				$result_skpd = $this->model->getDataSKPDUnique($id_skpd);
				foreach ($result_skpd->result() as $rows_skpd) {
					$result_kegiatan = $this->model->getDataKegiatanUnique($rows_skpd->kd_skpd);
					foreach ($result_kegiatan->result() as $rows_kegiatan) {
						$data[] = array(
									$rows_kegiatan->kd_gabungan,
									"[".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan
								);
					}
				}
			}
			else{
				$result_kegiatan = $this->model->getDataKegiatan();
				foreach ($result_kegiatan->result() as $rows_kegiatan) {
					$data[] = array(
								$rows_kegiatan->kd_gabungan,
								"[".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan
							);
				}
			}
			echo json_encode($data);
		}
	}

	public function getPrintData(){
		if ($this->session->userdata('auth_id') != '') {
			date_default_timezone_set("Asia/Jakarta");
			$skpd = $this->input->post('skpd');
			$kegiatan = $this->input->post('kegiatan');
			$bulan = $this->input->post('bulan');
			switch ($this->input->post('urutan')) {
				case '1':
					$order = 'tgl';
				break;
				case '1':
					$order = 'kd_program_kegiatan';
				break;
				
				default:
					$order = 'tgl';
				break;
			}
			$nama_bulan = ["","JANUARI", "FEBRUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER"];

			$this->load->library("Excel");
			$object =  new PHPExcel();
			$object->setActiveSheetIndex(0);
			
			// -------- Manual Setting Autosize -------- //
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(7);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('I')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('L')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('M')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('N')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('O')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('P')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('R')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('S')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('T')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('U')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('V')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('W')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('X')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('AD')->setWidth(15);


			// -------- PAPER Setup -------- //
			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | Data Pencairan SP2D');

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
			$object->getActiveSheet()->mergeCells('A1:AD1');
			// $object->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Title User Info -------- //
			$title_info = 'Tahun Anggaran (2018)';
			$object->getActiveSheet()->setCellValue('A2', $title_info);
			$object->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A2:AD2');
			// $object->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
			$object->getActiveSheet()->mergeCells('A4:AD4');

			// -------- Kegiatan Info -------- //
			if ($kegiatan == "all") {
				$nama_kegiatan = "Semua Kegiatan";
			}
			else{
				$result_kegiatan = $this->model->getDataSKPDUnique($kegiatan);
				foreach ($result_kegiatan->result() as $rows_kegiatan) {
					$nama_kegiatan = $rows_kegiatan->keterangan_kegiatan;
				}
			}
			$title_info = 'Nama Kegiatan : '.$nama_kegiatan;
			$object->getActiveSheet()->setCellValue('A5', $title_info);
			$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A5:AD5');

			// -------- Main Table -------- //
			$table_header_column = array("No", "Tanggal", "Bulan", "No SP2D", "Kode Gabungan", "Kode Rekening", "Nama Rekening", "Uraian", "Nama Penerima", "Pagu", "Pencairan", "Lokasi", "HPS", "Jumlah Mendaftar", "Jumlah Menawar", "Tanggal Pengumuman", "Tanggal Anwijzing", "Pembukaan Penawaran", "Tanggal Penetapan", "Nama Pemenang", "Nilai Kontrak", "Tanggal Kontrak", "No Kontrak", "Tanggal SPMK/SP", "Sanggah", "Lulus Pra-Kualifikasi", "Lulus Teknis", "Klarifikasi & Negosiasi", "Nomor Surat BAST Aset", "Tanggal Surat BAST Aset");
			$start_header_column = 0;
			foreach ($table_header_column as $thead) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($start_header_column, 6, $thead);
				$object->getActiveSheet()->getStyle('A6:AD6')->getFont()->setSize(9);
				$object->getActiveSheet()->getStyle('A6:AD6')->getFont()->setBold(TRUE);
				$object->getActiveSheet()->getStyle('A6:AD6')->getAlignment()->setWrapText(true);
				$object->getActiveSheet()->getStyle('A6:AD6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:AD6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('A6:AD6')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');
				$object->getActiveSheet()->getStyle('A6:AD6')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
				$start_header_column++;
			}			

			$no = 1;
			$start_content_column = 7;
			 
			$result_skpd = $this->model->getDataSKPDUnique($skpd);
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_realisasi_ro = $this->model->getDataRealisasiRO($rows_skpd->kd_skpd, $kegiatan, $bulan, $order);
				foreach ($result_realisasi_ro->result() as $rows_realisasi_ro) {
					$result_rincian_obyek = $this->model->getDataRincianObyek($rows_realisasi_ro->kd_skpd, $rows_realisasi_ro->kd_rekening);
					foreach ($result_rincian_obyek->result() as $rows_ro) {
						$object->getActiveSheet()->setCellValue('A'.$start_content_column, $no);
						$object->getActiveSheet()->setCellValue('B'.$start_content_column, $rows_realisasi_ro->tanggal);
						$object->getActiveSheet()->setCellValue('C'.$start_content_column, $nama_bulan[$rows_realisasi_ro->bln]);
						$object->getActiveSheet()->setCellValue('D'.$start_content_column, $rows_realisasi_ro->no_spj);
						$object->getActiveSheet()->setCellValue('E'.$start_content_column, $rows_realisasi_ro->kd_program_kegiatan);
						$object->getActiveSheet()->setCellValue('F'.$start_content_column, $rows_realisasi_ro->kd_rekening);
						$object->getActiveSheet()->setCellValue('G'.$start_content_column, $rows_ro->nama_rekening);
						$object->getActiveSheet()->setCellValue('H'.$start_content_column, $rows_realisasi_ro->uraian);
						$object->getActiveSheet()->setCellValue('I'.$start_content_column, $rows_realisasi_ro->nama_penerima);
						$object->getActiveSheet()->setCellValue('J'.$start_content_column, $rows_ro->jumlah);
						$object->getActiveSheet()->setCellValue('K'.$start_content_column, $rows_realisasi_ro->nilai);
						$object->getActiveSheet()->setCellValue('L'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('M'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('N'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('O'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('P'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('Q'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('R'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('S'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('T'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('U'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('V'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('X'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('Y'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('Z'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('AA'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('AB'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('AC'.$start_content_column, '');
						$object->getActiveSheet()->setCellValue('AD'.$start_content_column, '');


						$object->getActiveSheet()->getStyle('J'.$start_content_column.':K'.$start_content_column)->getNumberFormat()->setFormatCode('#,##0');
						$object->getActiveSheet()->getStyle('A'.$start_content_column.':AD'.$start_content_column)->getFont()->setSize(8);
						$object->getActiveSheet()->getStyle('A'.$start_content_column.':G'.$start_content_column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getStyle('A'.$start_content_column.':G'.$start_content_column)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getStyle('G'.$start_content_column.':AD'.$start_content_column)->getAlignment()->setWrapText(true);

						$object->getActiveSheet()->getStyle('I'.$start_content_column.':AD'.$start_content_column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$object->getActiveSheet()->getStyle('I'.$start_content_column.':AD'.$start_content_column)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$object->getActiveSheet()->getStyle('A'.$start_content_column.':AD'.$start_content_column)->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
						$no++;
						$start_content_column++;
					}
				}
			}

			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Data Pencairan SPPD.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Data Pencairan SPPD.xls"');
				$object_writer->save('php://output');
			}
		}
	}
}