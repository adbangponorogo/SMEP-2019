<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporteprarealisasi_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laporteprarealisasi_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/tepra/realisasi/data');
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

	public function getMainData($skpd, $bulan){
		if ($this->session->userdata('auth_id') != '') {
			if ($skpd != 'all') {
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				foreach ($result_skpd->result() as $rows_skpd) {
					$id_skpd = $rows_skpd->id;
					$kd_skpd = $rows_skpd->kd_skpd;
				}
			}
			else{
				$id_skpd = 'all';
				$kd_skpd = 'all';
			}

			$data = $this->tepraRealisasiProcess($id_skpd, $kd_skpd, $bulan);
			echo json_encode($data);
		}
	}

	public function getPrintData(){
		if ($this->session->userdata('auth_id') != '') {
			$skpd = $this->input->post("skpd");
			$bulan = $this->input->post("bulan");
			$tahun = $this->input->post("tahun");
			$tanggal_cetak = $this->input->post("tanggal_cetak");


			if ($bulan == "01") {$nama_bulan="JANUARI";}if ($bulan == "02") {$nama_bulan="FEBRUARI";}if ($bulan == "03") {$nama_bulan="MARET";}if ($bulan == "04") {$nama_bulan="APRIL";}if ($bulan == "05") {$nama_bulan="MEI";}if ($bulan == "06") {$nama_bulan="JUNI";}if ($bulan == "07") {$nama_bulan="JULI";}if ($bulan == "08") {$nama_bulan="AGUSTUS";}if ($bulan == "09") {$nama_bulan="SEPTEMBER";}if ($bulan == "10") {$nama_bulan="OKTOBER";}if ($bulan == "11") {$nama_bulan="NOVEMBER";}if ($bulan == "12") {$nama_bulan="DESEMBER";}

			if ($skpd != 'all') {
				$result_skpd = $this->model->getDataSKPDUnique($skpd);
				foreach ($result_skpd->result() as $rows_skpd) {
					$id_skpd = $rows_skpd->id;
					$kd_skpd = $rows_skpd->kd_skpd;
					$nama_skpd = $rows_skpd->nama_skpd;
				}
			}
			else{
				$id_skpd = 'all';
				$kd_skpd = 'all';
				$nama_skpd = 'Pemerintah Daerah';
			}

			$this->load->library("Excel");
			$object =  new PHPExcel();
			$object->setActiveSheetIndex(0);

			// -------- PAPER Setup -------- //
			$object->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
			$object->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$object->getActiveSheet()->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW);
			$object->getActiveSheet()->getSheetView()->setZoomScale(80);
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | TEPRA Realisasi - '.$nama_skpd.'&R&P');

			// -------- Title Form -------- //
			$title_form = 'LAPORAN REALISASI TEPRA';
			$object->getActiveSheet()->setCellValue('A1', $title_form);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A1:O1');
			$object->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Subtitle Form -------- //
			$subtitle_form = 'SAMPAI DENGAN BULAN '.$nama_bulan;
			$object->getActiveSheet()->setCellValue('A2', $subtitle_form);
			$object->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A2:O2');
			$object->getActiveSheet()->getStyle('A2:O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			// -------- Nama Organisasi -------- //
			$info_organisasi = 'NAMA ORGANISASI ';
			$nama_organisasi = ': '.$nama_skpd;
			$object->getActiveSheet()->setCellValue('A4', $info_organisasi);
			$object->getActiveSheet()->setCellValue('C4', $nama_organisasi);
			$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A4:B4');
			$object->getActiveSheet()->mergeCells('C4:O4');
			$object->getActiveSheet()->getStyle('A4:O4')->getFont()->setBold(TRUE);

			// -------- Kabupaten -------- //
			$info_kabupaten = 'KABUPATEN ';
			$nama_kabupaten = ': PONOROGO';
			$object->getActiveSheet()->setCellValue('A5', $info_kabupaten);
			$object->getActiveSheet()->setCellValue('C5', $nama_kabupaten);
			$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C5')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A5:B5');
			$object->getActiveSheet()->mergeCells('C5:O5');
			$object->getActiveSheet()->getStyle('A5:O5')->getFont()->setBold(TRUE);

			// -------- Tahun Anggaran -------- //
			$info_anggaran = 'TAHUN ANGGARAN ';
			$nama_anggaran = ': '.$tahun;
			$object->getActiveSheet()->setCellValue('A6', $info_anggaran);
			$object->getActiveSheet()->setCellValue('C6', $nama_anggaran);
			$object->getActiveSheet()->getStyle('A6')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C6')->getFont()->setSize(10); 
			$object->getActiveSheet()->mergeCells('A6:B6');
			$object->getActiveSheet()->mergeCells('C6:O6');
			$object->getActiveSheet()->getStyle('A6:O6')->getFont()->setBold(TRUE);


			// TABLE HEADER
			$table_title_head_row_first = array("No", "LAPORAN", "TARGET DAN REALISASI", "CAPAIAN (%)", "", "", "", "", "", "", "", "", "", "", "");
			$table_title_head_row_second = array("JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGT", "SEP", "OKT", "NOV", "DES");

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

			$object->getActiveSheet()->mergeCells('A8:A9');
			$object->getActiveSheet()->mergeCells('B8:B9');
			$object->getActiveSheet()->mergeCells('C8:C9');
			$object->getActiveSheet()->mergeCells('D8:O8');

			$object->getActiveSheet()->getStyle('A8:O9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A8:O9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('A8:O9')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A8:O9')->getFont()->setSize(7);
			$object->getActiveSheet()->getStyle('A8:O9')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A8:O9')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('A8:O9')->getFIll()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');



			// TABLE VALUES
			$data = $this->tepraRealisasiProcess($id_skpd, $kd_skpd, $bulan);

			$object->getActiveSheet()->setCellValue('A10', '1.');
			$object->getActiveSheet()->setCellValue('B10', 'PROGRESS KEUANGAN');
			$object->getActiveSheet()->setCellValue('B12', 'PROGRESS FISIK');
			$object->getActiveSheet()->setCellValue('C10', 'TARGET');
			$object->getActiveSheet()->setCellValue('C11', 'REALISASI');
			$object->getActiveSheet()->setCellValue('C12', 'TARGET');
			$object->getActiveSheet()->setCellValue('C13', 'REALISASI');

			$object->getActiveSheet()->setCellValue('A14', '2.');
			$object->getActiveSheet()->setCellValue('A15', 'a.');
			$object->getActiveSheet()->setCellValue('A16', 'b.');
			$object->getActiveSheet()->setCellValue('A17', 'c.');
			$object->getActiveSheet()->setCellValue('A18', 'd.');
			$object->getActiveSheet()->setCellValue('B14', 'AKTIVITAS STRATEGIS');
			$object->getActiveSheet()->setCellValue('B15', 'Proses Pengadaan');
			$object->getActiveSheet()->setCellValue('B16', 'Tanda Tangan Kontrak');
			$object->getActiveSheet()->setCellValue('B17', 'Pelaksanaan Pekerjaan');
			$object->getActiveSheet()->setCellValue('B18', 'Proses Hand Over');

			$rencana_keuangan = 3;
			for ($rck=0; $rck <= (sizeof($data[0])-1) ; $rck++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($rencana_keuangan, 10, $data[0][$rck]);
				$rencana_keuangan++;
			}

			$realisasi_keuangan = 3;
			for ($rlk=0; $rlk <= (sizeof($data[1])-1) ; $rlk++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($realisasi_keuangan, 11, $data[1][$rlk]);
				$realisasi_keuangan++;
			}

			$rencana_fisik = 3;
			for ($rcf=0; $rcf <= (sizeof($data[2])-1) ; $rcf++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($rencana_fisik, 12, $data[2][$rcf]);
				$rencana_fisik++;
			}

			$realisasi_fisik = 3;
			for ($rlf=0; $rlf <= (sizeof($data[3])-1) ; $rlf++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($realisasi_fisik, 13, $data[3][$rlf]);
				$realisasi_fisik++;
			}


			
			$object->getActiveSheet()->setCellValue('D14', 'PROSES PENGADAAN BARANG DAN JASA STRATEGIS (TOTAL PAKET = '.$data[4][0].')', '', '', '', '', '', '', '', '', '', '', '');


			$rup_pp = 3;
			for ($pp=0; $pp <= (sizeof($data[5])-1) ; $pp++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($rup_pp, 15, $data[5][$pp][0]);
				$rup_pp++;
			}

			$rup_ttk = 3;
			for ($ttk=0; $ttk <= (sizeof($data[6])-1) ; $ttk++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($rup_ttk, 16, $data[6][$ttk][0]);
				$rup_ttk++;
			}

			$rup_plj = 3;
			for ($plj=0; $plj <= (sizeof($data[7])-1) ; $plj++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($rup_plj, 17, $data[7][$plj][0]);
				$rup_plj++;
			}

			$rup_pho = 3;
			for ($pho=0; $pho <= (sizeof($data[8])-1) ; $pho++) {
				$object->getActiveSheet()->setCellValueByColumnAndRow($rup_pho, 18, $data[8][$pho][0]);
				$rup_pho++;
			}

			$object->getActiveSheet()->mergeCells('A10:A13');
			$object->getActiveSheet()->mergeCells('B10:B11');
			$object->getActiveSheet()->mergeCells('B12:B13');
			$object->getActiveSheet()->mergeCells('B14:C14');
			$object->getActiveSheet()->mergeCells('B15:C15');
			$object->getActiveSheet()->mergeCells('B16:C16');
			$object->getActiveSheet()->mergeCells('B17:C17');
			$object->getActiveSheet()->mergeCells('B18:C18');
			$object->getActiveSheet()->mergeCells('D14:O14');

			$object->getActiveSheet()->getStyle('A10:O18')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A10:O18')->getFont()->setSize(7);
			$object->getActiveSheet()->getStyle('D14')->getFont()->setBold(TRUE);

			$object->getActiveSheet()->getStyle('D10:O13')->getNumberFormat()->setFormatCode('#0.00');
			$object->getActiveSheet()->getStyle('D15:O18')->getNumberFormat()->setFormatCode('#0.00');

			$object->getActiveSheet()->getStyle('A10:A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A10:B18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('B10:C18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$object->getActiveSheet()->getStyle('B10:C18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('D10:O13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$object->getActiveSheet()->getStyle('D10:O13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('D14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('D14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$object->getActiveSheet()->getStyle('D15:O18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$object->getActiveSheet()->getStyle('D15:O18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$object->getActiveSheet()->getStyle('A10:O18')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));




			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Laporan TEPRA Realisasi - '.$nama_skpd.'.xls"');
			$object_writer->save('php://output');
		}
	}

	public function tepraRealisasiProcess($id_skpd, $kd_skpd, $bulan){
		if ($this->session->userdata('auth_id') != '') {

			// ----- RENCANA KEUANGAN -----//

			$result_rencana_keuangan = $this->model->getDataRencanaKeuangan($kd_skpd);
			foreach ($result_rencana_keuangan->result() as $rows_rencana_keuangan) {
				$get_rencana_januari = $this->nullValue($rows_rencana_keuangan->januari);
				$get_rencana_februari = $get_rencana_januari + $this->nullValue($rows_rencana_keuangan->februari);
				$get_rencana_maret = $get_rencana_februari + $this->nullValue($rows_rencana_keuangan->maret);
				$get_rencana_april = $get_rencana_maret + $this->nullValue($rows_rencana_keuangan->april);
				$get_rencana_mei = $get_rencana_april + $this->nullValue($rows_rencana_keuangan->mei);
				$get_rencana_juni = $get_rencana_mei + $this->nullValue($rows_rencana_keuangan->juni);
				$get_rencana_juli = $get_rencana_juni + $this->nullValue($rows_rencana_keuangan->juli);
				$get_rencana_agustus = $get_rencana_juli + $this->nullValue($rows_rencana_keuangan->agustus);
				$get_rencana_september = $get_rencana_agustus +$this->nullValue($rows_rencana_keuangan->september);
				$get_rencana_oktober = $get_rencana_september + $this->nullValue($rows_rencana_keuangan->oktober);
				$get_rencana_november = $get_rencana_oktober + $this->nullValue($rows_rencana_keuangan->november);
				$get_rencana_desember = $get_rencana_november + $this->nullValue($rows_rencana_keuangan->desember);
				$total_rencana_keuangan = $get_rencana_desember;

				$rencana_keuangan_januari = number_format($this->getMonthValue(($get_rencana_januari / $total_rencana_keuangan), $bulan, 1) * 100, 2, ".", ",");
				$rencana_keuangan_februari = number_format($this->getMonthValue(($get_rencana_februari / $total_rencana_keuangan), $bulan, 2) * 100, 2, ".", ",");
				$rencana_keuangan_maret = number_format($this->getMonthValue(($get_rencana_maret / $total_rencana_keuangan), $bulan, 3) * 100, 2, ".", ",");
				$rencana_keuangan_april = number_format($this->getMonthValue(($get_rencana_april / $total_rencana_keuangan), $bulan, 4) * 100, 2, ".", ",");
				$rencana_keuangan_mei = number_format($this->getMonthValue(($get_rencana_mei / $total_rencana_keuangan), $bulan, 5) * 100, 2, ".", ",");
				$rencana_keuangan_juni = number_format($this->getMonthValue(($get_rencana_juni / $total_rencana_keuangan), $bulan, 6) * 100, 2, ".", ",");
				$rencana_keuangan_juli = number_format($this->getMonthValue(($get_rencana_juli / $total_rencana_keuangan), $bulan, 7) * 100, 2, ".", ",");
				$rencana_keuangan_agustus = number_format($this->getMonthValue(($get_rencana_agustus / $total_rencana_keuangan), $bulan, 8) * 100, 2, ".", ",");
				$rencana_keuangan_september = number_format($this->getMonthValue(($get_rencana_september / $total_rencana_keuangan), $bulan, 9) * 100, 2, ".", ",");
				$rencana_keuangan_oktober = number_format($this->getMonthValue(($get_rencana_oktober / $total_rencana_keuangan), $bulan, 10) * 100, 2, ".", ",");
				$rencana_keuangan_november = number_format($this->getMonthValue(($get_rencana_november / $total_rencana_keuangan), $bulan, 11) * 100, 2, ".", ",");
				$rencana_keuangan_desember = number_format($this->getMonthValue(($get_rencana_desember / $total_rencana_keuangan), $bulan, 12) * 100, 2, ".", ",");

			}




			// ----- REALISASI KEUANGAN -----//

			$get_realisasi_keuangan_januari = $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 1));
			$get_realisasi_keuangan_februari = $get_realisasi_keuangan_januari + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 2));
			$get_realisasi_keuangan_maret = $get_realisasi_keuangan_februari + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 3));
			$get_realisasi_keuangan_april = $get_realisasi_keuangan_maret + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 4));
			$get_realisasi_keuangan_mei = $get_realisasi_keuangan_april + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 5));
			$get_realisasi_keuangan_juni = $get_realisasi_keuangan_mei + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 6));
			$get_realisasi_keuangan_juli = $get_realisasi_keuangan_juni + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 7));
			$get_realisasi_keuangan_agustus = $get_realisasi_keuangan_juli + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 8));
			$get_realisasi_keuangan_september = $get_realisasi_keuangan_agustus + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 9));
			$get_realisasi_keuangan_oktober = $get_realisasi_keuangan_september + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 10));
			$get_realisasi_keuangan_november = $get_realisasi_keuangan_oktober + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 11));
			$get_realisasi_keuangan_desember = $get_realisasi_keuangan_november + $this->nullValue($this->dataRealisasiKeuangan($kd_skpd, 12));

			$realisasi_keuangan_januari = number_format($this->getMonthValue(($get_realisasi_keuangan_januari / $total_rencana_keuangan), $bulan, 1) * 100, 2, ".", ",");
			$realisasi_keuangan_februari = number_format($this->getMonthValue(($get_realisasi_keuangan_februari / $total_rencana_keuangan), $bulan, 2) * 100, 2, ".", ",");
			$realisasi_keuangan_maret = number_format($this->getMonthValue(($get_realisasi_keuangan_maret / $total_rencana_keuangan), $bulan, 3) * 100, 2, ".", ",");
			$realisasi_keuangan_april = number_format($this->getMonthValue(($get_realisasi_keuangan_april / $total_rencana_keuangan), $bulan, 4) * 100, 2, ".", ",");
			$realisasi_keuangan_mei = number_format($this->getMonthValue(($get_realisasi_keuangan_mei / $total_rencana_keuangan), $bulan, 5) * 100, 2, ".", ",");
			$realisasi_keuangan_juni = number_format($this->getMonthValue(($get_realisasi_keuangan_juni / $total_rencana_keuangan), $bulan, 6) * 100, 2, ".", ",");
			$realisasi_keuangan_juli = number_format($this->getMonthValue(($get_realisasi_keuangan_juli / $total_rencana_keuangan), $bulan, 7) * 100, 2, ".", ",");
			$realisasi_keuangan_agustus = number_format($this->getMonthValue(($get_realisasi_keuangan_agustus / $total_rencana_keuangan), $bulan, 8) * 100, 2, ".", ",");
			$realisasi_keuangan_september = number_format($this->getMonthValue(($get_realisasi_keuangan_september / $total_rencana_keuangan), $bulan, 9) * 100, 2, ".", ",");
			$realisasi_keuangan_oktober = number_format($this->getMonthValue(($get_realisasi_keuangan_oktober / $total_rencana_keuangan), $bulan, 10) * 100, 2, ".", ",");
			$realisasi_keuangan_november = number_format($this->getMonthValue(($get_realisasi_keuangan_november / $total_rencana_keuangan), $bulan, 11) * 100, 2, ".", ",");
			$realisasi_keuangan_desember = number_format($this->getMonthValue(($get_realisasi_keuangan_desember / $total_rencana_keuangan), $bulan, 12) * 100, 2, ".", ",");




			// ----- RENCANA FISIK -----//

			$total_rencana_fisik = $total_rencana_keuangan;




			// ----- REALISASI KEUANGAN -----//

			$get_realisasi_fisik_januari = $this->nullValue($this->dataRealisasiFisik($id_skpd, '01'));
			$get_realisasi_fisik_februari = $get_realisasi_fisik_januari + $this->nullValue($this->dataRealisasiFisik($id_skpd, '02'));
			$get_realisasi_fisik_maret = $get_realisasi_fisik_februari + $this->nullValue($this->dataRealisasiFisik($id_skpd, '03'));
			$get_realisasi_fisik_april = $get_realisasi_fisik_maret + $this->nullValue($this->dataRealisasiFisik($id_skpd, '04'));
			$get_realisasi_fisik_mei = $get_realisasi_fisik_april + $this->nullValue($this->dataRealisasiFisik($id_skpd, '05'));
			$get_realisasi_fisik_juni = $get_realisasi_fisik_mei + $this->nullValue($this->dataRealisasiFisik($id_skpd, '06'));
			$get_realisasi_fisik_juli = $get_realisasi_fisik_juni + $this->nullValue($this->dataRealisasiFisik($id_skpd, '07'));
			$get_realisasi_fisik_agustus =$get_realisasi_fisik_juli +  $this->nullValue($this->dataRealisasiFisik($id_skpd, '08'));
			$get_realisasi_fisik_september = $get_realisasi_fisik_agustus + $this->nullValue($this->dataRealisasiFisik($id_skpd, '09'));
			$get_realisasi_fisik_oktober = $get_realisasi_fisik_september + $this->nullValue($this->dataRealisasiFisik($id_skpd, '10'));
			$get_realisasi_fisik_november = $get_realisasi_fisik_oktober + $this->nullValue($this->dataRealisasiFisik($id_skpd, '11'));
			$get_realisasi_fisik_desember = $get_realisasi_fisik_november + $this->nullValue($this->dataRealisasiFisik($id_skpd, '12'));

			$realisasi_fisik_januari = number_format($this->getMonthValue(($get_realisasi_fisik_januari / $total_rencana_fisik), $bulan, 1) * 100, 2, ".", ",");
			$realisasi_fisik_februari = number_format($this->getMonthValue(($get_realisasi_fisik_februari / $total_rencana_fisik), $bulan, 2) * 100, 2, ".", ",");
			$realisasi_fisik_maret = number_format($this->getMonthValue(($get_realisasi_fisik_maret / $total_rencana_fisik), $bulan, 3) * 100, 2, ".", ",");
			$realisasi_fisik_april = number_format($this->getMonthValue(($get_realisasi_fisik_april / $total_rencana_fisik), $bulan, 4) * 100, 2, ".", ",");
			$realisasi_fisik_mei = number_format($this->getMonthValue(($get_realisasi_fisik_mei / $total_rencana_fisik), $bulan, 5) * 100, 2, ".", ",");
			$realisasi_fisik_juni = number_format($this->getMonthValue(($get_realisasi_fisik_juni / $total_rencana_fisik), $bulan, 6) * 100, 2, ".", ",");
			$realisasi_fisik_juli = number_format($this->getMonthValue(($get_realisasi_fisik_juli / $total_rencana_fisik), $bulan, 7) * 100, 2, ".", ",");
			$realisasi_fisik_agustus = number_format($this->getMonthValue(($get_realisasi_fisik_agustus / $total_rencana_fisik), $bulan, 8) * 100, 2, ".", ",");
			$realisasi_fisik_september = number_format($this->getMonthValue(($get_realisasi_fisik_september / $total_rencana_fisik), $bulan, 9) * 100, 2, ".", ",");
			$realisasi_fisik_oktober = number_format($this->getMonthValue(($get_realisasi_fisik_oktober / $total_rencana_fisik), $bulan, 10) * 100, 2, ".", ",");
			$realisasi_fisik_november = number_format($this->getMonthValue(($get_realisasi_fisik_november / $total_rencana_fisik), $bulan, 11) * 100, 2, ".", ",");
			$realisasi_fisik_desember = number_format($this->getMonthValue(($get_realisasi_fisik_desember / $total_rencana_fisik), $bulan, 12) * 100, 2, ".", ",");


			

			// ----- PAKET RUP -----//

			$result_rup = $this->model->getDataPaketRUP($id_skpd);
			foreach ($result_rup->result() as $rows_rup) {
				$total_paket_rup = $rows_rup->jumlah_paket;
			}




			// ----- PAKET RUP - PROSES PENGADAAN-----//

			$get_rup_pp_januari = $this->getMonthValue($this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '01')), $bulan, 1);
			$get_rup_pp_februari = $this->getMonthValue($get_rup_pp_januari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '02')), $bulan, 2);
			$get_rup_pp_maret = $this->getMonthValue($get_rup_pp_februari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '03')), $bulan, 3);
			$get_rup_pp_april = $this->getMonthValue($get_rup_pp_maret + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '04')), $bulan, 4);
			$get_rup_pp_mei = $this->getMonthValue($get_rup_pp_april + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '05')), $bulan, 5);
			$get_rup_pp_juni = $this->getMonthValue($get_rup_pp_mei + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '06')), $bulan, 6);
			$get_rup_pp_juli = $this->getMonthValue($get_rup_pp_juni + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '07')), $bulan, 7);
			$get_rup_pp_agustus = $this->getMonthValue($get_rup_pp_juli + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '08')), $bulan, 8);
			$get_rup_pp_september = $this->getMonthValue($get_rup_pp_agustus + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '09')), $bulan, 9);
			$get_rup_pp_oktober = $this->getMonthValue($get_rup_pp_september + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '10')), $bulan, 10);
			$get_rup_pp_november = $this->getMonthValue($get_rup_pp_oktober + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '11')), $bulan, 11);
			$get_rup_pp_desember = $this->getMonthValue($get_rup_pp_november + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_pengadaan', '12')), $bulan, 12);

			$rup_pp_januari = number_format(($get_rup_pp_januari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_februari = number_format(($get_rup_pp_februari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_maret = number_format(($get_rup_pp_maret / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_april = number_format(($get_rup_pp_april / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_mei = number_format(($get_rup_pp_mei / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_juni = number_format(($get_rup_pp_juni / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_juli = number_format(($get_rup_pp_juli / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_agustus = number_format(($get_rup_pp_agustus / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_september = number_format(($get_rup_pp_september / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_oktober = number_format(($get_rup_pp_oktober / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_november = number_format(($get_rup_pp_november / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pp_desember = number_format(($get_rup_pp_desember / $total_paket_rup) * 100, 2, ".", ",");




			
			// ----- PAKET RUP - TANDA TANGAN KONTRAK-----//
			
			$get_rup_ttk_januari = $this->getMonthValue($this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '01')), $bulan, 1);
			$get_rup_ttk_februari = $this->getMonthValue($get_rup_ttk_januari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '02')), $bulan, 2);
			$get_rup_ttk_maret = $this->getMonthValue($get_rup_ttk_februari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '03')), $bulan, 3);
			$get_rup_ttk_april =$this->getMonthValue( $get_rup_ttk_maret + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '04')), $bulan, 4);
			$get_rup_ttk_mei = $this->getMonthValue($get_rup_ttk_april + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '05')), $bulan, 5);
			$get_rup_ttk_juni = $this->getMonthValue($get_rup_ttk_mei + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '06')), $bulan, 6);
			$get_rup_ttk_juli = $this->getMonthValue($get_rup_ttk_juni + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '07')), $bulan, 7);
			$get_rup_ttk_agustus = $this->getMonthValue($get_rup_ttk_juli + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '08')), $bulan, 8);
			$get_rup_ttk_september = $this->getMonthValue($get_rup_ttk_agustus + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '09')), $bulan, 9);
			$get_rup_ttk_oktober = $this->getMonthValue($get_rup_ttk_september + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '10')), $bulan, 10);
			$get_rup_ttk_november = $this->getMonthValue($get_rup_ttk_oktober + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '11')), $bulan, 11);
			$get_rup_ttk_desember = $this->getMonthValue($get_rup_ttk_november + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'tanda_tangan_kontrak', '12')), $bulan, 12);

			$rup_ttk_januari = number_format(($get_rup_ttk_januari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_februari = number_format(($get_rup_ttk_februari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_maret = number_format(($get_rup_ttk_maret / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_april = number_format(($get_rup_ttk_april / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_mei = number_format(($get_rup_ttk_mei / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_juni = number_format(($get_rup_ttk_juni / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_juli = number_format(($get_rup_ttk_juli / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_agustus = number_format(($get_rup_ttk_agustus / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_september = number_format(($get_rup_ttk_september / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_oktober = number_format(($get_rup_ttk_oktober / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_november = number_format(($get_rup_ttk_november / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ttk_desember = number_format(($get_rup_ttk_desember / $total_paket_rup) * 100, 2, ".", ",");




			// ----- PAKET RUP - PELAKSANAAN PEKERJAAN -----//
			
			$get_rup_ppj_januari = $this->getMonthValue($this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '01')), $bulan, 1);
			$get_rup_ppj_februari = $this->getMonthValue($get_rup_ppj_januari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '02')), $bulan, 2);
			$get_rup_ppj_maret = $this->getMonthValue($get_rup_ppj_februari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '03')), $bulan, 3);
			$get_rup_ppj_april = $this->getMonthValue($get_rup_ppj_maret + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '04')), $bulan, 4);
			$get_rup_ppj_mei = $this->getMonthValue($get_rup_ppj_april + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '05')), $bulan, 5);
			$get_rup_ppj_juni = $this->getMonthValue($get_rup_ppj_mei + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '06')), $bulan, 6);
			$get_rup_ppj_juli = $this->getMonthValue($get_rup_ppj_juni + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '07')), $bulan, 7);
			$get_rup_ppj_agustus = $this->getMonthValue($get_rup_ppj_juli + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '08')), $bulan, 8);
			$get_rup_ppj_september = $this->getMonthValue($get_rup_ppj_agustus + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '09')), $bulan, 9);
			$get_rup_ppj_oktober = $this->getMonthValue($get_rup_ppj_september + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '10')), $bulan, 10);
			$get_rup_ppj_november = $this->getMonthValue($get_rup_ppj_oktober + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '11')), $bulan, 11);
			$get_rup_ppj_desember = $this->getMonthValue($get_rup_ppj_november + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'pelaksanaan_pekerjaan', '12')), $bulan, 12);

			$rup_ppj_januari = number_format(($get_rup_ppj_januari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_februari = number_format(($get_rup_ppj_februari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_maret = number_format(($get_rup_ppj_maret / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_april = number_format(($get_rup_ppj_april / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_mei = number_format(($get_rup_ppj_mei / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_juni = number_format(($get_rup_ppj_juni / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_juli = number_format(($get_rup_ppj_juli / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_agustus = number_format(($get_rup_ppj_agustus / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_september = number_format(($get_rup_ppj_september / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_oktober = number_format(($get_rup_ppj_oktober / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_november = number_format(($get_rup_ppj_november / $total_paket_rup) * 100, 2, ".", ",");
			$rup_ppj_desember = number_format(($get_rup_ppj_desember / $total_paket_rup) * 100, 2, ".", ",");





			$get_rup_pho_januari = $this->getMonthValue($this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '01')), $bulan, 1);
			$get_rup_pho_februari = $this->getMonthValue($get_rup_pho_januari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '02')), $bulan, 2);
			$get_rup_pho_maret = $this->getMonthValue($get_rup_pho_februari + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '03')), $bulan, 3);
			$get_rup_pho_april = $this->getMonthValue($get_rup_pho_maret + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '04')), $bulan, 4);
			$get_rup_pho_mei = $this->getMonthValue($get_rup_pho_april + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '05')), $bulan, 5);
			$get_rup_pho_juni = $this->getMonthValue($get_rup_pho_mei + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '06')), $bulan, 6);
			$get_rup_pho_juli = $this->getMonthValue($get_rup_pho_juni + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '07')), $bulan, 7);
			$get_rup_pho_agustus = $this->getMonthValue($get_rup_pho_juli +  $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '08')), $bulan, 8);
			$get_rup_pho_september = $this->getMonthValue($get_rup_pho_agustus + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '09')), $bulan, 9);
			$get_rup_pho_oktober = $this->getMonthValue($get_rup_pho_september + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '10')), $bulan, 10);
			$get_rup_pho_november = $this->getMonthValue($get_rup_pho_oktober + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '11')), $bulan, 11);
			$get_rup_pho_desember = $this->getMonthValue($get_rup_pho_november + $this->nullValue($this->dataRealisasiTepra($id_skpd, 'proses_hand_over', '12')), $bulan, 12);

			$rup_pho_januari = number_format(($get_rup_pho_januari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_februari = number_format(($get_rup_pho_februari / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_maret = number_format(($get_rup_pho_maret / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_april = number_format(($get_rup_pho_april / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_mei = number_format(($get_rup_pho_mei / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_juni = number_format(($get_rup_pho_juni / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_juli = number_format(($get_rup_pho_juli / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_agustus = number_format(($get_rup_pho_agustus / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_september = number_format(($get_rup_pho_september / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_oktober = number_format(($get_rup_pho_oktober / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_november = number_format(($get_rup_pho_november / $total_paket_rup) * 100, 2, ".", ",");
			$rup_pho_desember = number_format(($get_rup_pho_desember / $total_paket_rup) * 100, 2, ".", ",");




			// ----- RESULT ----- //

			$data = [
						[
							$rencana_keuangan_januari,
							$rencana_keuangan_februari,
							$rencana_keuangan_maret,
							$rencana_keuangan_april,
							$rencana_keuangan_mei,
							$rencana_keuangan_juni,
							$rencana_keuangan_juli,
							$rencana_keuangan_agustus,
							$rencana_keuangan_september,
							$rencana_keuangan_oktober,
							$rencana_keuangan_november,
							$rencana_keuangan_desember
						],
						
						[
							$realisasi_keuangan_januari,
							$realisasi_keuangan_februari,
							$realisasi_keuangan_maret,
							$realisasi_keuangan_april,
							$realisasi_keuangan_mei,
							$realisasi_keuangan_juni,
							$realisasi_keuangan_juli,
							$realisasi_keuangan_agustus,
							$realisasi_keuangan_september,
							$realisasi_keuangan_oktober,
							$realisasi_keuangan_november,
							$realisasi_keuangan_desember
						],

						[
							$rencana_keuangan_januari,
							$rencana_keuangan_februari,
							$rencana_keuangan_maret,
							$rencana_keuangan_april,
							$rencana_keuangan_mei,
							$rencana_keuangan_juni,
							$rencana_keuangan_juli,
							$rencana_keuangan_agustus,
							$rencana_keuangan_september,
							$rencana_keuangan_oktober,
							$rencana_keuangan_november,
							$rencana_keuangan_desember
						],

						[
							$realisasi_fisik_januari,
							$realisasi_fisik_februari,
							$realisasi_fisik_maret,
							$realisasi_fisik_april,
							$realisasi_fisik_mei,
							$realisasi_fisik_juni,
							$realisasi_fisik_juli,
							$realisasi_fisik_agustus,
							$realisasi_fisik_september,
							$realisasi_fisik_oktober,
							$realisasi_fisik_november,
							$realisasi_fisik_desember
						],

						[
							$total_paket_rup
						],

						[
							[$rup_pp_januari, $get_rup_pp_januari],
							[$rup_pp_februari, $get_rup_pp_februari],
							[$rup_pp_maret, $get_rup_pp_maret],
							[$rup_pp_april, $get_rup_pp_april],
							[$rup_pp_mei, $get_rup_pp_mei],
							[$rup_pp_juni, $get_rup_pp_juni],
							[$rup_pp_juli, $get_rup_pp_juli],
							[$rup_pp_agustus, $get_rup_pp_agustus],
							[$rup_pp_september, $get_rup_pp_september],
							[$rup_pp_oktober, $get_rup_pp_oktober],
							[$rup_pp_november, $get_rup_pp_november],
							[$rup_pp_desember, $get_rup_pp_desember],
						],

						[
							[$rup_ttk_januari, $get_rup_ttk_januari],
							[$rup_ttk_februari, $get_rup_ttk_februari],
							[$rup_ttk_maret, $get_rup_ttk_maret],
							[$rup_ttk_april, $get_rup_ttk_april],
							[$rup_ttk_mei, $get_rup_ttk_mei],
							[$rup_ttk_juni, $get_rup_ttk_juni],
							[$rup_ttk_juli, $get_rup_ttk_juli],
							[$rup_ttk_agustus, $get_rup_ttk_agustus],
							[$rup_ttk_september, $get_rup_ttk_september],
							[$rup_ttk_oktober, $get_rup_ttk_oktober],
							[$rup_ttk_november, $get_rup_ttk_november],
							[$rup_ttk_desember, $get_rup_ttk_desember]
						],

						[
							[$rup_ppj_januari, $get_rup_ppj_januari],
							[$rup_ppj_februari, $get_rup_ppj_februari],
							[$rup_ppj_maret, $get_rup_ppj_maret],
							[$rup_ppj_april, $get_rup_ppj_april],
							[$rup_ppj_mei, $get_rup_ppj_mei],
							[$rup_ppj_juni, $get_rup_ppj_juni],
							[$rup_ppj_juli, $get_rup_ppj_juli],
							[$rup_ppj_agustus, $get_rup_ppj_agustus],
							[$rup_ppj_september, $get_rup_ppj_september],
							[$rup_ppj_oktober, $get_rup_ppj_oktober],
							[$rup_ppj_november, $get_rup_ppj_november],
							[$rup_ppj_desember, $get_rup_ppj_desember]
						],

						[
							[$rup_pho_januari, $get_rup_pho_januari],
							[$rup_pho_februari, $get_rup_pho_februari],
							[$rup_pho_maret, $get_rup_pho_maret],
							[$rup_pho_april, $get_rup_pho_april],
							[$rup_pho_mei, $get_rup_pho_mei],
							[$rup_pho_juni, $get_rup_pho_juni],
							[$rup_pho_juli, $get_rup_pho_juli],
							[$rup_pho_agustus, $get_rup_pho_agustus],
							[$rup_pho_september, $get_rup_pho_september],
							[$rup_pho_oktober, $get_rup_pho_oktober],
							[$rup_pho_november, $get_rup_pho_november],
							[$rup_pho_desember, $get_rup_pho_desember]
						]
					];

			return $data;
		}
	}

	public function dataRealisasiKeuangan($kd_skpd, $bulan){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataRealisasiKeuangan($kd_skpd, $bulan);
			foreach ($result->result() as $rows) {
				$data = $rows->nilai;
			}

			return $data;
		}
	}

	public function dataRealisasiFisik($id_skpd, $bulan){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataRealisasiFisik($id_skpd, $bulan);
			foreach ($result->result() as $rows) {
				$data = $rows->realisasi_keuangan;
			}

			return $data;
		}
	}

	public function dataRealisasiTepra($id_skpd, $tahap, $bulan){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataRealisasiTepra($id_skpd, $tahap, $bulan);
			foreach ($result->result() as $rows) {
				$data = $rows->jumlah_paket;
			}

			return $data;
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

	public function getMonthValue($value, $bulan, $max_bulan){
		if ($this->session->userdata('auth_id') != '') {
			if ($bulan >= $max_bulan && $bulan > 0) {
				$data = $value;
			}
			else{
				$data = 0;
			}
			return $data;
		}
	}
}