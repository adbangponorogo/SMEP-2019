<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laportepraperencanaan_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/Laportepraperencanaan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/tepra/perencanaan/data');
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

	public function getDataDana($id_skpd){
		if ($this->session->userdata('auth_id') != '') {
			$result_skpd = $this->model->getDataSKPDUnique($id_skpd);
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_ref_rup = $this->model->getDataRefRUP($rows_skpd->kd_skpd);
				foreach ($result_ref_rup->result() as $rows_ref_rup) {
					$belanja_daerah = intval($rows_ref_rup->btl1)+intval($rows_ref_rup->btl2)+intval($rows_ref_rup->bl1)+intval($rows_ref_rup->bl2)+intval($rows_ref_rup->bl3);
					$btl = intval($rows_ref_rup->btl1)+intval($rows_ref_rup->btl2);
					$bl = intval($rows_ref_rup->bl1)+intval($rows_ref_rup->bl2)+intval($rows_ref_rup->bl3);
					$btl_pegawai = intval($rows_ref_rup->btl1);
					$btl_non_pegawai = intval($rows_ref_rup->btl2);
					$bl_pegawai = intval($rows_ref_rup->bl1);
					$bl_non_pegawai = intval($rows_ref_rup->bl2)+intval($rows_ref_rup->bl3);

					$result_bj_rup = $this->model->getDataBelanjaRUP($rows_skpd->id, 2);
					foreach ($result_bj_rup->result() as $rows_bj_rup) {
						$bj_pagu = $rows_bj_rup->jumlah;
						$bj_pkt = $rows_bj_rup->pagu_paket;
					}

					$result_md_rup = $this->model->getDataBelanjaRUP($rows_skpd->id, 3);
					foreach ($result_md_rup->result() as $rows_md_rup) {
						$md_pagu = $rows_md_rup->jumlah;
						$md_pkt = $rows_md_rup->pagu_paket;
					}
				}
			}
			$belanja_daerah = $this->nullIntValue($belanja_daerah);
			$btl = $this->nullIntValue($btl);
			$bl = $this->nullIntValue($bl);
			$btl_pegawai = $this->nullIntValue($btl_pegawai);
			$btl_non_pegawai = $this->nullIntValue($btl_non_pegawai);
			$bl_pegawai = $this->nullIntValue($bl_pegawai);
			$bl_non_pegawai = $this->nullIntValue($bl_non_pegawai);
			$bj_pagu = $this->nullIntValue($bj_pagu);
			$bj_pkt = $this->nullIntValue($bj_pkt);
			$md_pagu = $this->nullIntValue($md_pagu);
			$md_pkt = $this->nullIntValue($md_pkt);

			$data = [
						[number_format($belanja_daerah)],
						[number_format($btl)],
						[number_format($bl)],
						[number_format($btl_pegawai)],
						[number_format($btl_non_pegawai)],
						[number_format($bl_pegawai)],
						[number_format($bl_non_pegawai)],
						[number_format($bj_pagu)],
						[$bj_pkt],
						[number_format($md_pagu)],
						[$md_pkt]
					];
			echo json_encode($data);
		}
	}

	public function getDataPaketPaguRUP($id_skpd, $metode_pemilihan){
		if ($this->session->userdata('auth_id') != '') {
			$result = $this->model->getDataPaguRUP($id_skpd, $metode_pemilihan);
			foreach ($result->result() as $rows) {
				$data = [[number_format($rows->pagu_paket)]];
			}
			echo json_encode($data);
		}
	}

	public function getDataPaketRUP($id_skpd, $metode_pemilihan){
		if ($this->session->userdata('auth_id') != '') {
			$result_rup = $this->model->getDataRUP($id_skpd, $metode_pemilihan);
			$data = array();
			$no = 1;
			foreach ($result_rup->result() as $rows_rup) {
				$result_program = $this->model->getDataProgramUnique($rows_rup->id_program);
				foreach ($result_program->result() as $rows_program) {
					$result_kegiatan = $this->model->getDataKegiatanUnique($rows_rup->id_kegiatan);
					foreach ($result_kegiatan->result() as $rows_kegiatan) {
						$data[] = array(
									$no++,
									"- [".$rows_program->kd_gabungan."] - ".$rows_program->keterangan_program."<br>".
									"- [".$rows_kegiatan->kd_gabungan."] - ".$rows_kegiatan->keterangan_kegiatan."<br>".
									"- ".$rows_rup->nama_paket,
									number_format($rows_rup->pagu_paket)
								);
					}
				}
			}
			echo json_encode($data);
		}
	}

	public function getDataRekapRUP($id_skpd){
		if ($this->session->userdata('auth_id') != '') {

			// ---------- Barang -----------
			$result_penyedia_barang1 = $this->model->getDataRekapRUP($id_skpd, 1, 1, "and pagu_paket <= 200000000");
			foreach ($result_penyedia_barang1->result() as $rows_penyedia_barang1) {
				$pkt11 = intval($this->nullIntValue($rows_penyedia_barang1->jumlah));
				$pagu11 = intval($this->nullIntValue($rows_penyedia_barang1->pagu_paket));
			}
			$result_penyedia_barang2 = $this->model->getDataRekapRUP($id_skpd, 1, 1, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
			foreach ($result_penyedia_barang2->result() as $rows_penyedia_barang2) {
				$pkt12 = intval($this->nullIntValue($rows_penyedia_barang2->jumlah));
				$pagu12 = intval($this->nullIntValue($rows_penyedia_barang2->pagu_paket));
			}
			$result_penyedia_barang3 = $this->model->getDataRekapRUP($id_skpd, 1, 1, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
			foreach ($result_penyedia_barang3->result() as $rows_penyedia_barang3) {
				$pkt13 = intval($this->nullIntValue($rows_penyedia_barang3->jumlah));
				$pagu13 = intval($this->nullIntValue($rows_penyedia_barang3->pagu_paket));
			}
			$result_penyedia_barang4 = $this->model->getDataRekapRUP($id_skpd, 1, 1, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
			foreach ($result_penyedia_barang4->result() as $rows_penyedia_barang4) {
				$pkt14 = intval($this->nullIntValue($rows_penyedia_barang4->jumlah));
				$pagu14 = intval($this->nullIntValue($rows_penyedia_barang4->pagu_paket));
			}
			$result_penyedia_barang5 = $this->model->getDataRekapRUP($id_skpd, 1, 1, "and pagu_paket> 100000000000");
			foreach ($result_penyedia_barang5->result() as $rows_penyedia_barang5) {
				$pkt15 = intval($this->nullIntValue($rows_penyedia_barang5->jumlah));
				$pagu15 = intval($this->nullIntValue($rows_penyedia_barang5->pagu_paket));
			}
			$result_swakelola_barang = $this->model->getDataRekapRUP($id_skpd, 2, 1, "");
			foreach ($result_swakelola_barang->result() as $rows_swakelola_barang) {
				$pkt16 = intval($this->nullIntValue($rows_swakelola_barang->jumlah));
				$pagu16 = intval($this->nullIntValue($rows_swakelola_barang->pagu_paket));
			}
			$result_total_barang = $this->model->getDataRekapTotalRUP($id_skpd, 1);
			foreach ($result_total_barang->result() as $rows_total_barang) {
				$pkt17 = intval($this->nullIntValue($rows_total_barang->jumlah));
				$pagu17 = intval($this->nullIntValue($rows_total_barang->pagu_paket));
			}


			// ---------- Konstruksi -----------

			$result_penyedia_konstruksi1 = $this->model->getDataRekapRUP($id_skpd, 1, 2, "and pagu_paket <= 200000000");
			foreach ($result_penyedia_konstruksi1->result() as $rows_penyedia_konstruksi1) {
				$pkt21 = intval($this->nullIntValue($rows_penyedia_konstruksi1->jumlah));
				$pagu21 = intval($this->nullIntValue($rows_penyedia_konstruksi1->pagu_paket));
			}
			$result_penyedia_konstruksi2 = $this->model->getDataRekapRUP($id_skpd, 1, 2, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
			foreach ($result_penyedia_konstruksi2->result() as $rows_penyedia_konstruksi2) {
				$pkt22 = intval($this->nullIntValue($rows_penyedia_konstruksi2->jumlah));
				$pagu22 = intval($this->nullIntValue($rows_penyedia_konstruksi2->pagu_paket));
			}
			$result_penyedia_konstruksi3 = $this->model->getDataRekapRUP($id_skpd, 1, 2, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
			foreach ($result_penyedia_konstruksi3->result() as $rows_penyedia_konstruksi3) {
				$pkt23 = intval($this->nullIntValue($rows_penyedia_konstruksi3->jumlah));
				$pagu23 = intval($this->nullIntValue($rows_penyedia_konstruksi3->pagu_paket));
			}
			$result_penyedia_konstruksi4 = $this->model->getDataRekapRUP($id_skpd, 1, 2, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
			foreach ($result_penyedia_konstruksi4->result() as $rows_penyedia_konstruksi4) {
				$pkt24 = intval($this->nullIntValue($rows_penyedia_konstruksi4->jumlah));
				$pagu24 = intval($this->nullIntValue($rows_penyedia_konstruksi4->pagu_paket));
			}
			$result_penyedia_konstruksi5 = $this->model->getDataRekapRUP($id_skpd, 1, 2, "and pagu_paket> 100000000000");
			foreach ($result_penyedia_konstruksi5->result() as $rows_penyedia_konstruksi5) {
				$pkt25 = intval($this->nullIntValue($rows_penyedia_konstruksi5->jumlah));
				$pagu25 = intval($this->nullIntValue($rows_penyedia_konstruksi5->pagu_paket));
			}
			$result_swakelola_konstruksi = $this->model->getDataRekapRUP($id_skpd, 2, 2, "");
			foreach ($result_swakelola_konstruksi->result() as $rows_swakelola_konstruksi) {
				$pkt26 = intval($this->nullIntValue($rows_swakelola_konstruksi->jumlah));
				$pagu26 = intval($this->nullIntValue($rows_swakelola_konstruksi->pagu_paket));
			}
			$result_total_konstruksi = $this->model->getDataRekapTotalRUP($id_skpd, 2);
			foreach ($result_total_konstruksi->result() as $rows_total_konstruksi) {
				$pkt27 = intval($this->nullIntValue($rows_total_konstruksi->jumlah));
				$pagu27 = intval($this->nullIntValue($rows_total_konstruksi->pagu_paket));
			}
			

			// ---------- Konsultasi -----------

			$result_penyedia_konsultasi1 = $this->model->getDataRekapRUP($id_skpd, 1, 3, "and pagu_paket <= 200000000");
			foreach ($result_penyedia_konsultasi1->result() as $rows_penyedia_konsultasi1) {
				$pkt31 = intval($this->nullIntValue($rows_penyedia_konsultasi1->jumlah));
				$pagu31 = intval($this->nullIntValue($rows_penyedia_konsultasi1->pagu_paket));
			}
			$result_penyedia_konsultasi2 = $this->model->getDataRekapRUP($id_skpd, 1, 3, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
			foreach ($result_penyedia_konsultasi2->result() as $rows_penyedia_konsultasi2) {
				$pkt32 = intval($this->nullIntValue($rows_penyedia_konsultasi2->jumlah));
				$pagu32 = intval($this->nullIntValue($rows_penyedia_konsultasi2->pagu_paket));
			}
			$result_penyedia_konsultasi3 = $this->model->getDataRekapRUP($id_skpd, 1, 3, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
			foreach ($result_penyedia_konsultasi3->result() as $rows_penyedia_konsultasi3) {
				$pkt33 = intval($this->nullIntValue($rows_penyedia_konsultasi3->jumlah));
				$pagu33 = intval($this->nullIntValue($rows_penyedia_konsultasi3->pagu_paket));
			}
			$result_penyedia_konsultasi4 = $this->model->getDataRekapRUP($id_skpd, 1, 3, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
			foreach ($result_penyedia_konsultasi4->result() as $rows_penyedia_konsultasi4) {
				$pkt34 = intval($this->nullIntValue($rows_penyedia_konsultasi4->jumlah));
				$pagu34 = intval($this->nullIntValue($rows_penyedia_konsultasi4->pagu_paket));
			}
			$result_penyedia_konsultasi5 = $this->model->getDataRekapRUP($id_skpd, 1, 3, "and pagu_paket> 100000000000");
			foreach ($result_penyedia_konsultasi5->result() as $rows_penyedia_konsultasi5) {
				$pkt35 = intval($this->nullIntValue($rows_penyedia_konsultasi5->jumlah));
				$pagu35 = intval($this->nullIntValue($rows_penyedia_konsultasi5->pagu_paket));
			}
			$result_swakelola_konsultasi = $this->model->getDataRekapRUP($id_skpd, 2, 3, "");
			foreach ($result_swakelola_konsultasi->result() as $rows_swakelola_konsultasi) {
				$pkt36 = intval($this->nullIntValue($rows_swakelola_konsultasi->jumlah));
				$pagu36 = intval($this->nullIntValue($rows_swakelola_konsultasi->pagu_paket));
			}
			$result_total_konsultasi = $this->model->getDataRekapTotalRUP($id_skpd, 3);
			foreach ($result_total_konsultasi->result() as $rows_total_konsultasi) {
				$pkt37 = intval($this->nullIntValue($rows_total_konsultasi->jumlah));
				$pagu37 = intval($this->nullIntValue($rows_total_konsultasi->pagu_paket));
			}


			// ---------- Jasa Lainnya -----------

			$result_penyedia_jasalainnya1 = $this->model->getDataRekapRUP($id_skpd, 1, 4, "and pagu_paket <= 200000000");
			foreach ($result_penyedia_jasalainnya1->result() as $rows_penyedia_jasalainnya1) {
				$pkt41 = intval($this->nullIntValue($rows_penyedia_jasalainnya1->jumlah));
				$pagu41 = intval($this->nullIntValue($rows_penyedia_jasalainnya1->pagu_paket));
			}
			$result_penyedia_jasalainnya2 = $this->model->getDataRekapRUP($id_skpd, 1, 4, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
			foreach ($result_penyedia_jasalainnya2->result() as $rows_penyedia_jasalainnya2) {
				$pkt42 = intval($this->nullIntValue($rows_penyedia_jasalainnya2->jumlah));
				$pagu42 = intval($this->nullIntValue($rows_penyedia_jasalainnya2->pagu_paket));
			}
			$result_penyedia_jasalainnya3 = $this->model->getDataRekapRUP($id_skpd, 1, 4, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
			foreach ($result_penyedia_jasalainnya3->result() as $rows_penyedia_jasalainnya3) {
				$pkt43 = intval($this->nullIntValue($rows_penyedia_jasalainnya3->jumlah));
				$pagu43 = intval($this->nullIntValue($rows_penyedia_jasalainnya3->pagu_paket));
			}
			$result_penyedia_jasalainnya4 = $this->model->getDataRekapRUP($id_skpd, 1, 4, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
			foreach ($result_penyedia_jasalainnya4->result() as $rows_penyedia_jasalainnya4) {
				$pkt44 = intval($this->nullIntValue($rows_penyedia_jasalainnya4->jumlah));
				$pagu44 = intval($this->nullIntValue($rows_penyedia_jasalainnya4->pagu_paket));
			}
			$result_penyedia_jasalainnya5 = $this->model->getDataRekapRUP($id_skpd, 1, 4, "and pagu_paket> 100000000000");
			foreach ($result_penyedia_jasalainnya5->result() as $rows_penyedia_jasalainnya5) {
				$pkt45 = intval($this->nullIntValue($rows_penyedia_jasalainnya5->jumlah));
				$pagu45 = intval($this->nullIntValue($rows_penyedia_jasalainnya5->pagu_paket));
			}
			$result_swakelola_jasalainnya = $this->model->getDataRekapRUP($id_skpd, 2, 4, "");
			foreach ($result_swakelola_jasalainnya->result() as $rows_swakelola_jasalainnya) {
				$pkt46 = intval($this->nullIntValue($rows_swakelola_jasalainnya->jumlah));
				$pagu46 = intval($this->nullIntValue($rows_swakelola_jasalainnya->pagu_paket));
			}
			$result_total_jasalainnya = $this->model->getDataRekapTotalRUP($id_skpd, 4);
			foreach ($result_total_jasalainnya->result() as $rows_total_jasalainnya) {
				$pkt47 = intval($this->nullIntValue($rows_total_jasalainnya->jumlah));
				$pagu47 = intval($this->nullIntValue($rows_total_jasalainnya->pagu_paket));
			}


			// ---------- Total Lainnya ------------

			$total_pkt1 = $pkt11 + $pkt21 + $pkt31 + $pkt41;
			$total_pkt2 = $pkt12 + $pkt22 + $pkt32 + $pkt42;
			$total_pkt3 = $pkt13 + $pkt23 + $pkt33 + $pkt43;
			$total_pkt4 = $pkt14 + $pkt24 + $pkt34 + $pkt44;
			$total_pkt5 = $pkt15 + $pkt25 + $pkt35 + $pkt45;
			$total_pkt6 = $pkt16 + $pkt26 + $pkt36 + $pkt46;
			$total_pkt7 = $pkt17 + $pkt27 + $pkt37 + $pkt47;

			$total_pagu1 = $pagu11 + $pagu21 + $pagu31 + $pagu41;
			$total_pagu2 = $pagu12 + $pagu22 + $pagu32 + $pagu42;
			$total_pagu3 = $pagu13 + $pagu23 + $pagu33 + $pagu43;
			$total_pagu4 = $pagu14 + $pagu24 + $pagu34 + $pagu44;
			$total_pagu5 = $pagu15 + $pagu25 + $pagu35 + $pagu45;
			$total_pagu6 = $pagu16 + $pagu26 + $pagu36 + $pagu46;
			$total_pagu7 = $pagu17 + $pagu27 + $pagu37 + $pagu47;

			$data = [
						[$pkt11, $pagu11, $pkt12, $pagu12, $pkt13, $pagu13, $pkt14, $pagu14, $pkt15, $pagu15, $pkt16, $pagu16, $pkt17, $pagu17],
						[$pkt21, $pagu21, $pkt22, $pagu22, $pkt23, $pagu23, $pkt24, $pagu24, $pkt25, $pagu25, $pkt26, $pagu26, $pkt27, $pagu27],
						[$pkt31, $pagu31, $pkt32, $pagu32, $pkt33, $pagu33, $pkt34, $pagu34, $pkt35, $pagu35, $pkt36, $pagu36, $pkt37, $pagu37],
						[$pkt41, $pagu41, $pkt42, $pagu42, $pkt43, $pagu43, $pkt44, $pagu44, $pkt45, $pagu45, $pkt46, $pagu46, $pkt47, $pagu47],
						[
							$total_pkt1, $total_pagu1,
							$total_pkt2, $total_pagu2,
							$total_pkt3, $total_pagu3,
							$total_pkt4, $total_pagu4,
							$total_pkt5, $total_pagu5,
							$total_pkt6, $total_pagu6,
							$total_pkt7, $total_pagu7
						]
					];
			echo json_encode($data);
		}
	}
	
	public function nullIntValue($value){
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

	public function getPrintData(){
		if ($this->session->userdata('auth_id') != '') {
			$skpd = $this->input->post("skpd");
			$tahun = $this->input->post("tahun");
			$tanggal_cetak = $this->input->post("tanggal_cetak");

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
			$object->getActiveSheet()->getHeaderFooter()->setOddFooter('&L https:://smep.ponorogo.go.id/smep_2019 | TEPRA Perencanaan - '.$nama_skpd.'&R&P');

			// -------- Title Form -------- //
			$title_form = 'LAPORAN PERENCANAAN TEPRA';
			$object->getActiveSheet()->setCellValue('A1', $title_form);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
			$object->getActiveSheet()->mergeCells('A1:O1');
			$object->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// -------- Nama Organisasi -------- //
			$info_organisasi = 'NAMA ORGANISASI ';
			$nama_organisasi = ': '.$nama_skpd;
			$object->getActiveSheet()->setCellValue('A3', $info_organisasi);
			$object->getActiveSheet()->setCellValue('C3', $nama_organisasi);
			$object->getActiveSheet()->getStyle('A3')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C3')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A3:B3');
			$object->getActiveSheet()->mergeCells('C3:O3');
			$object->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(TRUE);

			// -------- Kabupaten -------- //
			$info_kabupaten = 'KABUPATEN ';
			$nama_kabupaten = ': PONOROGO';
			$object->getActiveSheet()->setCellValue('A4', $info_kabupaten);
			$object->getActiveSheet()->setCellValue('C4', $nama_kabupaten);
			$object->getActiveSheet()->getStyle('A4')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C4')->getFont()->setSize(10);
			$object->getActiveSheet()->mergeCells('A4:B4');
			$object->getActiveSheet()->mergeCells('C4:O4');
			$object->getActiveSheet()->getStyle('A4:O4')->getFont()->setBold(TRUE);

			// -------- Tahun Anggaran -------- //
			$info_anggaran = 'TAHUN ANGGARAN ';
			$nama_anggaran = ': '.$tahun;
			$object->getActiveSheet()->setCellValue('A5', $info_anggaran);
			$object->getActiveSheet()->setCellValue('C5', $nama_anggaran);
			$object->getActiveSheet()->getStyle('A5')->getFont()->setSize(10);
			$object->getActiveSheet()->getStyle('C5')->getFont()->setSize(10); 
			$object->getActiveSheet()->mergeCells('A5:B5');
			$object->getActiveSheet()->mergeCells('C5:O5');
			$object->getActiveSheet()->getStyle('A5:O5')->getFont()->setBold(TRUE);


			// ------------ Diagram ------------
			// ---- Header ----
			$object->getActiveSheet()->setCellValue('F7', '5. BELANJA DAERAH');
			$object->getActiveSheet()->setCellValue('C10', '5.1 BELANJA TIDAK LANGSUNG');
			$object->getActiveSheet()->setCellValue('H10', '5.2 BELANJA LANGSUNG');
			$object->getActiveSheet()->setCellValue('B13', '5.1.1 PEGAWAI');
			$object->getActiveSheet()->setCellValue('E13', '5.1.2 NON PEGAWAI');
			$object->getActiveSheet()->setCellValue('H13', '5.2.1 PEGAWAI');
			$object->getActiveSheet()->setCellValue('K13', 'NON PEGAWAI');
			$object->getActiveSheet()->setCellValue('I16', '5.2.2 BARANG/JASA');
			$object->getActiveSheet()->setCellValue('M16', '5.2.3 MODAL');

			$object->getActiveSheet()->mergeCells('F7:H7');
			$object->getActiveSheet()->mergeCells('C10:F10');
			$object->getActiveSheet()->mergeCells('H10:L10');
			$object->getActiveSheet()->mergeCells('B13:C13');
			$object->getActiveSheet()->mergeCells('E13:F13');
			$object->getActiveSheet()->mergeCells('H13:I13');
			$object->getActiveSheet()->mergeCells('K13:M13');
			$object->getActiveSheet()->mergeCells('I16:K16');
			$object->getActiveSheet()->mergeCells('M16:O16');


			// SETUP
		 	$object->getActiveSheet()->getStyle('A7:O18')->getFont()->setSize(8);
		 	$object->getActiveSheet()->getStyle('A7:O18')->getFont()->setBold(TRUE);
			$object->getActiveSheet()->getStyle('A7:O18')->getAlignment()->setWrapText(true);
			$object->getActiveSheet()->getStyle('A7:O18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$object->getActiveSheet()->getStyle('A7:O18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$object->getActiveSheet()->getStyle('F7:H7')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('C10:F10')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('H10:L10')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('B13:C13')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('E13:F13')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('H13:I13')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('K13:M13')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('I16:K16')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('M16:O16')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));


			// ---- Pagu Value ----
			$result_skpd = $this->model->getDataSKPDUnique($skpd);
			foreach ($result_skpd->result() as $rows_skpd) {
				$result_ref_rup = $this->model->getDataRefRUP($rows_skpd->kd_skpd);
				foreach ($result_ref_rup->result() as $rows_ref_rup) {
					$belanja_daerah = intval($rows_ref_rup->btl1)+intval($rows_ref_rup->btl2)+intval($rows_ref_rup->bl1)+intval($rows_ref_rup->bl2)+intval($rows_ref_rup->bl3);
					$btl = intval($rows_ref_rup->btl1)+intval($rows_ref_rup->btl2);
					$bl = intval($rows_ref_rup->bl1)+intval($rows_ref_rup->bl2)+intval($rows_ref_rup->bl3);
					$btl_pegawai = intval($rows_ref_rup->btl1);
					$btl_non_pegawai = intval($rows_ref_rup->btl2);
					$bl_pegawai = intval($rows_ref_rup->bl1);
					$bl_non_pegawai = intval($rows_ref_rup->bl2)+intval($rows_ref_rup->bl3);

					$result_bj_rup = $this->model->getDataBelanjaRUP($rows_skpd->id, 2);
					foreach ($result_bj_rup->result() as $rows_bj_rup) {
						$bj_pagu = $rows_bj_rup->jumlah;
						$bj_pkt = $rows_bj_rup->pagu_paket;
					}

					$result_md_rup = $this->model->getDataBelanjaRUP($rows_skpd->id, 3);
					foreach ($result_md_rup->result() as $rows_md_rup) {
						$md_pagu = $rows_md_rup->jumlah;
						$md_pkt = $rows_md_rup->pagu_paket;
					}
				}
			}
			$belanja_daerah = $this->nullIntValue($belanja_daerah);
			$btl = $this->nullIntValue($btl);
			$bl = $this->nullIntValue($bl);
			$btl_pegawai = $this->nullIntValue($btl_pegawai);
			$btl_non_pegawai = $this->nullIntValue($btl_non_pegawai);
			$bl_pegawai = $this->nullIntValue($bl_pegawai);
			$bl_non_pegawai = $this->nullIntValue($bl_non_pegawai);
			$bj_pagu = $this->nullIntValue($bj_pagu);
			$bj_pkt = $this->nullIntValue($bj_pkt);
			$md_pagu = $this->nullIntValue($md_pagu);
			$md_pkt = $this->nullIntValue($md_pkt);


			$object->getActiveSheet()->setCellValue('F8', $belanja_daerah);
			$object->getActiveSheet()->setCellValue('C11', $btl);
			$object->getActiveSheet()->setCellValue('H11', $bl);
			$object->getActiveSheet()->setCellValue('B14', $btl_pegawai);
			$object->getActiveSheet()->setCellValue('E14', $btl_non_pegawai);
			$object->getActiveSheet()->setCellValue('H14', $bl_pegawai);
			$object->getActiveSheet()->setCellValue('K14', $bl_non_pegawai);
			$object->getActiveSheet()->setCellValue('I17', $bj_pagu);
			$object->getActiveSheet()->setCellValue('I18', $bj_pkt." Paket");
			$object->getActiveSheet()->setCellValue('M17', $md_pagu);
			$object->getActiveSheet()->setCellValue('M18', $md_pkt." Paket");

			$object->getActiveSheet()->mergeCells('F8:H8');
			$object->getActiveSheet()->mergeCells('C11:F11');
			$object->getActiveSheet()->mergeCells('H11:L11');
			$object->getActiveSheet()->mergeCells('B14:C14');
			$object->getActiveSheet()->mergeCells('E14:F14');
			$object->getActiveSheet()->mergeCells('H14:I14');
			$object->getActiveSheet()->mergeCells('K14:M14');
			$object->getActiveSheet()->mergeCells('I17:K17');
			$object->getActiveSheet()->mergeCells('I18:K18');
			$object->getActiveSheet()->mergeCells('M17:O17');
			$object->getActiveSheet()->mergeCells('M18:O18');

			// SETUP
			$object->getActiveSheet()->getStyle('F8')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('C11')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('H11')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('B14')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('E14')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('H14')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('K14')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('I17')->getNumberFormat()->setFormatCode('#,##0');
			$object->getActiveSheet()->getStyle('M17')->getNumberFormat()->setFormatCode('#,##0');


			$object->getActiveSheet()->getStyle('F8:H8')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('C11:F11')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('H11:L11')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('B14:C14')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('E14:F14')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('H14:I14')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('K14:M14')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('I17:K17')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('I18:K18')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('M17:O17')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));
			$object->getActiveSheet()->getStyle('M18:O18')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));


			// ------------ Diagram ------------
			// ------ Header -------
				$table_title_head_row_first = array("Jenis Pengadaan Barang/Jasa dan Modal", "Penyedia", "", "", "", "", "", "", "", "", "", "Swakelola", "", "Total", "");
				$table_title_head_row_second = array("<=Rp. 200 jt", "", ">Rp. 200 jt <=Rp. 2,5 Miliar", "", ">Rp. 2,5 Miliar <=Rp. 50 Miliar", "", ">Rp. 50 Miliar <=Rp. 100 Miliar", "", ">Rp. 100 Miliar", "", "", "", "Penyedia + Swakelola", "");
				$table_title_head_row_third = array("Pkt", "Rp.", "Pkt", "Rp.", "Pkt", "Rp.", "Pkt", "Rp.", "Pkt", "Rp.", "", "", "Pkt", "Rp.");

				$start_column_first = 0;
				foreach ($table_title_head_row_first as $thead_first) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_first, 21, $thead_first);
					$start_column_first++;
				}
				$start_column_second = 1;
				foreach ($table_title_head_row_second as $thead_second) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_second, 22, $thead_second);
					$start_column_second++;
				}
				$start_column_third = 1;
				foreach ($table_title_head_row_third as $thead_third) {
					$object->getActiveSheet()->setCellValueByColumnAndRow($start_column_third, 23, $thead_third);
					$start_column_third++;
				}

				// SETUP
				$object->getActiveSheet()->mergeCells('A21:A23');
				$object->getActiveSheet()->mergeCells('B21:K21');
				$object->getActiveSheet()->mergeCells('L21:M21');
				$object->getActiveSheet()->mergeCells('N21:O21');
				$object->getActiveSheet()->mergeCells('B22:C22');
				$object->getActiveSheet()->mergeCells('D22:E22');
				$object->getActiveSheet()->mergeCells('F22:G22');
				$object->getActiveSheet()->mergeCells('H22:I22');
				$object->getActiveSheet()->mergeCells('J22:K22');
				$object->getActiveSheet()->mergeCells('N22:O22');
				$object->getActiveSheet()->mergeCells('L21:L23');

				$object->getActiveSheet()->getStyle('A21:O23')->getFont()->setBold(TRUE);

				$object->getActiveSheet()->getStyle('A21:O23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A21:O23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



				// ------ Rekap Value -------

				// ---------- Barang -----------
				$result_penyedia_barang1 = $this->model->getDataRekapRUP($skpd, 1, 1, "and pagu_paket <= 200000000");
				foreach ($result_penyedia_barang1->result() as $rows_penyedia_barang1) {
					$pkt11 = intval($this->nullIntValue($rows_penyedia_barang1->jumlah));
					$pagu11 = intval($this->nullIntValue($rows_penyedia_barang1->pagu_paket));
				}
				$result_penyedia_barang2 = $this->model->getDataRekapRUP($skpd, 1, 1, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
				foreach ($result_penyedia_barang2->result() as $rows_penyedia_barang2) {
					$pkt12 = intval($this->nullIntValue($rows_penyedia_barang2->jumlah));
					$pagu12 = intval($this->nullIntValue($rows_penyedia_barang2->pagu_paket));
				}
				$result_penyedia_barang3 = $this->model->getDataRekapRUP($skpd, 1, 1, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
				foreach ($result_penyedia_barang3->result() as $rows_penyedia_barang3) {
					$pkt13 = intval($this->nullIntValue($rows_penyedia_barang3->jumlah));
					$pagu13 = intval($this->nullIntValue($rows_penyedia_barang3->pagu_paket));
				}
				$result_penyedia_barang4 = $this->model->getDataRekapRUP($skpd, 1, 1, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
				foreach ($result_penyedia_barang4->result() as $rows_penyedia_barang4) {
					$pkt14 = intval($this->nullIntValue($rows_penyedia_barang4->jumlah));
					$pagu14 = intval($this->nullIntValue($rows_penyedia_barang4->pagu_paket));
				}
				$result_penyedia_barang5 = $this->model->getDataRekapRUP($skpd, 1, 1, "and pagu_paket> 100000000000");
				foreach ($result_penyedia_barang5->result() as $rows_penyedia_barang5) {
					$pkt15 = intval($this->nullIntValue($rows_penyedia_barang5->jumlah));
					$pagu15 = intval($this->nullIntValue($rows_penyedia_barang5->pagu_paket));
				}
				$result_swakelola_barang = $this->model->getDataRekapRUP($skpd, 2, 1, "");
				foreach ($result_swakelola_barang->result() as $rows_swakelola_barang) {
					$pkt16 = intval($this->nullIntValue($rows_swakelola_barang->jumlah));
					$pagu16 = intval($this->nullIntValue($rows_swakelola_barang->pagu_paket));
				}
				$result_total_barang = $this->model->getDataRekapTotalRUP($skpd, 1);
				foreach ($result_total_barang->result() as $rows_total_barang) {
					$pkt17 = intval($this->nullIntValue($rows_total_barang->jumlah));
					$pagu17 = intval($this->nullIntValue($rows_total_barang->pagu_paket));
				}


				// ---------- Konstruksi -----------

				$result_penyedia_konstruksi1 = $this->model->getDataRekapRUP($skpd, 1, 2, "and pagu_paket <= 200000000");
				foreach ($result_penyedia_konstruksi1->result() as $rows_penyedia_konstruksi1) {
					$pkt21 = intval($this->nullIntValue($rows_penyedia_konstruksi1->jumlah));
					$pagu21 = intval($this->nullIntValue($rows_penyedia_konstruksi1->pagu_paket));
				}
				$result_penyedia_konstruksi2 = $this->model->getDataRekapRUP($skpd, 1, 2, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
				foreach ($result_penyedia_konstruksi2->result() as $rows_penyedia_konstruksi2) {
					$pkt22 = intval($this->nullIntValue($rows_penyedia_konstruksi2->jumlah));
					$pagu22 = intval($this->nullIntValue($rows_penyedia_konstruksi2->pagu_paket));
				}
				$result_penyedia_konstruksi3 = $this->model->getDataRekapRUP($skpd, 1, 2, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
				foreach ($result_penyedia_konstruksi3->result() as $rows_penyedia_konstruksi3) {
					$pkt23 = intval($this->nullIntValue($rows_penyedia_konstruksi3->jumlah));
					$pagu23 = intval($this->nullIntValue($rows_penyedia_konstruksi3->pagu_paket));
				}
				$result_penyedia_konstruksi4 = $this->model->getDataRekapRUP($skpd, 1, 2, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
				foreach ($result_penyedia_konstruksi4->result() as $rows_penyedia_konstruksi4) {
					$pkt24 = intval($this->nullIntValue($rows_penyedia_konstruksi4->jumlah));
					$pagu24 = intval($this->nullIntValue($rows_penyedia_konstruksi4->pagu_paket));
				}
				$result_penyedia_konstruksi5 = $this->model->getDataRekapRUP($skpd, 1, 2, "and pagu_paket> 100000000000");
				foreach ($result_penyedia_konstruksi5->result() as $rows_penyedia_konstruksi5) {
					$pkt25 = intval($this->nullIntValue($rows_penyedia_konstruksi5->jumlah));
					$pagu25 = intval($this->nullIntValue($rows_penyedia_konstruksi5->pagu_paket));
				}
				$result_swakelola_konstruksi = $this->model->getDataRekapRUP($skpd, 2, 2, "");
				foreach ($result_swakelola_konstruksi->result() as $rows_swakelola_konstruksi) {
					$pkt26 = intval($this->nullIntValue($rows_swakelola_konstruksi->jumlah));
					$pagu26 = intval($this->nullIntValue($rows_swakelola_konstruksi->pagu_paket));
				}
				$result_total_konstruksi = $this->model->getDataRekapTotalRUP($skpd, 2);
				foreach ($result_total_konstruksi->result() as $rows_total_konstruksi) {
					$pkt27 = intval($this->nullIntValue($rows_total_konstruksi->jumlah));
					$pagu27 = intval($this->nullIntValue($rows_total_konstruksi->pagu_paket));
				}
				

				// ---------- Konsultasi -----------

				$result_penyedia_konsultasi1 = $this->model->getDataRekapRUP($skpd, 1, 3, "and pagu_paket <= 200000000");
				foreach ($result_penyedia_konsultasi1->result() as $rows_penyedia_konsultasi1) {
					$pkt31 = intval($this->nullIntValue($rows_penyedia_konsultasi1->jumlah));
					$pagu31 = intval($this->nullIntValue($rows_penyedia_konsultasi1->pagu_paket));
				}
				$result_penyedia_konsultasi2 = $this->model->getDataRekapRUP($skpd, 1, 3, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
				foreach ($result_penyedia_konsultasi2->result() as $rows_penyedia_konsultasi2) {
					$pkt32 = intval($this->nullIntValue($rows_penyedia_konsultasi2->jumlah));
					$pagu32 = intval($this->nullIntValue($rows_penyedia_konsultasi2->pagu_paket));
				}
				$result_penyedia_konsultasi3 = $this->model->getDataRekapRUP($skpd, 1, 3, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
				foreach ($result_penyedia_konsultasi3->result() as $rows_penyedia_konsultasi3) {
					$pkt33 = intval($this->nullIntValue($rows_penyedia_konsultasi3->jumlah));
					$pagu33 = intval($this->nullIntValue($rows_penyedia_konsultasi3->pagu_paket));
				}
				$result_penyedia_konsultasi4 = $this->model->getDataRekapRUP($skpd, 1, 3, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
				foreach ($result_penyedia_konsultasi4->result() as $rows_penyedia_konsultasi4) {
					$pkt34 = intval($this->nullIntValue($rows_penyedia_konsultasi4->jumlah));
					$pagu34 = intval($this->nullIntValue($rows_penyedia_konsultasi4->pagu_paket));
				}
				$result_penyedia_konsultasi5 = $this->model->getDataRekapRUP($skpd, 1, 3, "and pagu_paket> 100000000000");
				foreach ($result_penyedia_konsultasi5->result() as $rows_penyedia_konsultasi5) {
					$pkt35 = intval($this->nullIntValue($rows_penyedia_konsultasi5->jumlah));
					$pagu35 = intval($this->nullIntValue($rows_penyedia_konsultasi5->pagu_paket));
				}
				$result_swakelola_konsultasi = $this->model->getDataRekapRUP($skpd, 2, 3, "");
				foreach ($result_swakelola_konsultasi->result() as $rows_swakelola_konsultasi) {
					$pkt36 = intval($this->nullIntValue($rows_swakelola_konsultasi->jumlah));
					$pagu36 = intval($this->nullIntValue($rows_swakelola_konsultasi->pagu_paket));
				}
				$result_total_konsultasi = $this->model->getDataRekapTotalRUP($skpd, 3);
				foreach ($result_total_konsultasi->result() as $rows_total_konsultasi) {
					$pkt37 = intval($this->nullIntValue($rows_total_konsultasi->jumlah));
					$pagu37 = intval($this->nullIntValue($rows_total_konsultasi->pagu_paket));
				}


				// ---------- Jasa Lainnya -----------

				$result_penyedia_jasalainnya1 = $this->model->getDataRekapRUP($skpd, 1, 4, "and pagu_paket <= 200000000");
				foreach ($result_penyedia_jasalainnya1->result() as $rows_penyedia_jasalainnya1) {
					$pkt41 = intval($this->nullIntValue($rows_penyedia_jasalainnya1->jumlah));
					$pagu41 = intval($this->nullIntValue($rows_penyedia_jasalainnya1->pagu_paket));
				}
				$result_penyedia_jasalainnya2 = $this->model->getDataRekapRUP($skpd, 1, 4, "and pagu_paket > 200000000 and pagu_paket <= 2500000000");
				foreach ($result_penyedia_jasalainnya2->result() as $rows_penyedia_jasalainnya2) {
					$pkt42 = intval($this->nullIntValue($rows_penyedia_jasalainnya2->jumlah));
					$pagu42 = intval($this->nullIntValue($rows_penyedia_jasalainnya2->pagu_paket));
				}
				$result_penyedia_jasalainnya3 = $this->model->getDataRekapRUP($skpd, 1, 4, "and pagu_paket > 2500000000 and pagu_paket <= 50000000000");
				foreach ($result_penyedia_jasalainnya3->result() as $rows_penyedia_jasalainnya3) {
					$pkt43 = intval($this->nullIntValue($rows_penyedia_jasalainnya3->jumlah));
					$pagu43 = intval($this->nullIntValue($rows_penyedia_jasalainnya3->pagu_paket));
				}
				$result_penyedia_jasalainnya4 = $this->model->getDataRekapRUP($skpd, 1, 4, "and pagu_paket > 50000000000 and pagu_paket <= 100000000000");
				foreach ($result_penyedia_jasalainnya4->result() as $rows_penyedia_jasalainnya4) {
					$pkt44 = intval($this->nullIntValue($rows_penyedia_jasalainnya4->jumlah));
					$pagu44 = intval($this->nullIntValue($rows_penyedia_jasalainnya4->pagu_paket));
				}
				$result_penyedia_jasalainnya5 = $this->model->getDataRekapRUP($skpd, 1, 4, "and pagu_paket> 100000000000");
				foreach ($result_penyedia_jasalainnya5->result() as $rows_penyedia_jasalainnya5) {
					$pkt45 = intval($this->nullIntValue($rows_penyedia_jasalainnya5->jumlah));
					$pagu45 = intval($this->nullIntValue($rows_penyedia_jasalainnya5->pagu_paket));
				}
				$result_swakelola_jasalainnya = $this->model->getDataRekapRUP($skpd, 2, 4, "");
				foreach ($result_swakelola_jasalainnya->result() as $rows_swakelola_jasalainnya) {
					$pkt46 = intval($this->nullIntValue($rows_swakelola_jasalainnya->jumlah));
					$pagu46 = intval($this->nullIntValue($rows_swakelola_jasalainnya->pagu_paket));
				}
				$result_total_jasalainnya = $this->model->getDataRekapTotalRUP($skpd, 4);
				foreach ($result_total_jasalainnya->result() as $rows_total_jasalainnya) {
					$pkt47 = intval($this->nullIntValue($rows_total_jasalainnya->jumlah));
					$pagu47 = intval($this->nullIntValue($rows_total_jasalainnya->pagu_paket));
				}


			// ---------- Total Lainnya ------------

			$total_pkt1 = $pkt11 + $pkt21 + $pkt31 + $pkt41;
			$total_pkt2 = $pkt12 + $pkt22 + $pkt32 + $pkt42;
			$total_pkt3 = $pkt13 + $pkt23 + $pkt33 + $pkt43;
			$total_pkt4 = $pkt14 + $pkt24 + $pkt34 + $pkt44;
			$total_pkt5 = $pkt15 + $pkt25 + $pkt35 + $pkt45;
			$total_pkt6 = $pkt16 + $pkt26 + $pkt36 + $pkt46;
			$total_pkt7 = $pkt17 + $pkt27 + $pkt37 + $pkt47;

			$total_pagu1 = $pagu11 + $pagu21 + $pagu31 + $pagu41;
			$total_pagu2 = $pagu12 + $pagu22 + $pagu32 + $pagu42;
			$total_pagu3 = $pagu13 + $pagu23 + $pagu33 + $pagu43;
			$total_pagu4 = $pagu14 + $pagu24 + $pagu34 + $pagu44;
			$total_pagu5 = $pagu15 + $pagu25 + $pagu35 + $pagu45;
			$total_pagu6 = $pagu16 + $pagu26 + $pagu36 + $pagu46;
			$total_pagu7 = $pagu17 + $pagu27 + $pagu37 + $pagu47;



				// Barang
				$object->getActiveSheet()->setCellValue('A24', 'BARANG');
				$object->getActiveSheet()->setCellValue('B24', $pkt11);
				$object->getActiveSheet()->setCellValue('C24', $pagu11);
				$object->getActiveSheet()->setCellValue('D24', $pkt12);
				$object->getActiveSheet()->setCellValue('E24', $pagu12);
				$object->getActiveSheet()->setCellValue('F24', $pkt13);
				$object->getActiveSheet()->setCellValue('G24', $pagu13);
				$object->getActiveSheet()->setCellValue('H24', $pkt14);
				$object->getActiveSheet()->setCellValue('I24', $pagu14);
				$object->getActiveSheet()->setCellValue('J24', $pkt15);
				$object->getActiveSheet()->setCellValue('K24', $pagu15);
				$object->getActiveSheet()->setCellValue('L24', $pkt16);
				$object->getActiveSheet()->setCellValue('M24', $pagu16);
				$object->getActiveSheet()->setCellValue('N24', $pkt17);
				$object->getActiveSheet()->setCellValue('O24', $pagu17);

				// Konstruksi
				$object->getActiveSheet()->setCellValue('A25', 'KONSTRUKSI');
				$object->getActiveSheet()->setCellValue('B25', $pkt21);
				$object->getActiveSheet()->setCellValue('C25', $pagu21);
				$object->getActiveSheet()->setCellValue('D25', $pkt22);
				$object->getActiveSheet()->setCellValue('E25', $pagu22);
				$object->getActiveSheet()->setCellValue('F25', $pkt23);
				$object->getActiveSheet()->setCellValue('G25', $pagu23);
				$object->getActiveSheet()->setCellValue('H25', $pkt24);
				$object->getActiveSheet()->setCellValue('I25', $pagu24);
				$object->getActiveSheet()->setCellValue('J25', $pkt25);
				$object->getActiveSheet()->setCellValue('K25', $pagu25);
				$object->getActiveSheet()->setCellValue('L25', $pkt26);
				$object->getActiveSheet()->setCellValue('M25', $pagu26);
				$object->getActiveSheet()->setCellValue('N25', $pkt27);
				$object->getActiveSheet()->setCellValue('O25', $pagu27);

				// Konsultasi
				$object->getActiveSheet()->setCellValue('A26', 'KONSULTASI');
				$object->getActiveSheet()->setCellValue('B26', $pkt31);
				$object->getActiveSheet()->setCellValue('C26', $pagu31);
				$object->getActiveSheet()->setCellValue('D26', $pkt32);
				$object->getActiveSheet()->setCellValue('E26', $pagu32);
				$object->getActiveSheet()->setCellValue('F26', $pkt33);
				$object->getActiveSheet()->setCellValue('G26', $pagu33);
				$object->getActiveSheet()->setCellValue('H26', $pkt34);
				$object->getActiveSheet()->setCellValue('I26', $pagu34);
				$object->getActiveSheet()->setCellValue('J26', $pkt35);
				$object->getActiveSheet()->setCellValue('K26', $pagu35);
				$object->getActiveSheet()->setCellValue('L26', $pkt36);
				$object->getActiveSheet()->setCellValue('M26', $pagu36);
				$object->getActiveSheet()->setCellValue('N26', $pkt37);
				$object->getActiveSheet()->setCellValue('O26', $pagu37);

				// Jasa Lainnya
				$object->getActiveSheet()->setCellValue('A27', 'JASA LAINNYA');
				$object->getActiveSheet()->setCellValue('B27', $pkt41);
				$object->getActiveSheet()->setCellValue('C27', $pagu41);
				$object->getActiveSheet()->setCellValue('D27', $pkt42);
				$object->getActiveSheet()->setCellValue('E27', $pagu42);
				$object->getActiveSheet()->setCellValue('F27', $pkt43);
				$object->getActiveSheet()->setCellValue('G27', $pagu43);
				$object->getActiveSheet()->setCellValue('H27', $pkt44);
				$object->getActiveSheet()->setCellValue('I27', $pagu44);
				$object->getActiveSheet()->setCellValue('J27', $pkt45);
				$object->getActiveSheet()->setCellValue('K27', $pagu45);
				$object->getActiveSheet()->setCellValue('L27', $pkt46);
				$object->getActiveSheet()->setCellValue('M27', $pagu46);
				$object->getActiveSheet()->setCellValue('N27', $pkt47);
				$object->getActiveSheet()->setCellValue('O27', $pagu47);

				// Total Jumlah
				$object->getActiveSheet()->setCellValue('A28', 'TOTAL JUMLAH');
				$object->getActiveSheet()->setCellValue('B28', $total_pkt1);
				$object->getActiveSheet()->setCellValue('C28', $total_pagu1);
				$object->getActiveSheet()->setCellValue('D28', $total_pkt2);
				$object->getActiveSheet()->setCellValue('E28', $total_pagu2);
				$object->getActiveSheet()->setCellValue('F28', $total_pkt3);
				$object->getActiveSheet()->setCellValue('G28', $total_pagu3);
				$object->getActiveSheet()->setCellValue('H28', $total_pkt4);
				$object->getActiveSheet()->setCellValue('I28', $total_pagu4);
				$object->getActiveSheet()->setCellValue('J28', $total_pkt5);
				$object->getActiveSheet()->setCellValue('K28', $total_pagu5);
				$object->getActiveSheet()->setCellValue('L28', $total_pkt6);
				$object->getActiveSheet()->setCellValue('M28', $total_pagu6);
				$object->getActiveSheet()->setCellValue('N28', $total_pkt7);
				$object->getActiveSheet()->setCellValue('O28', $total_pagu7);

				// SETUP
				$object->getActiveSheet()->getStyle('A21:O28')->getAlignment()->setWrapText(true);

				$object->getActiveSheet()->getStyle('C24:C28')->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('E24:E28')->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('G24:G28')->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('I24:I28')->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('K24:K28')->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('M24:M28')->getNumberFormat()->setFormatCode('#,##0');
				$object->getActiveSheet()->getStyle('O24:O28')->getNumberFormat()->setFormatCode('#,##0');

				$object->getActiveSheet()->getStyle('A24:A28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$object->getActiveSheet()->getStyle('A24:A28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$object->getActiveSheet()->getStyle('B24:O28')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$object->getActiveSheet()->getStyle('B24:O28')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$object->getActiveSheet()->getStyle('A21:O28')->applyFromArray(array('borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN))));



			if ($_SERVER["SERVER_NAME"] == "localhost") {
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Laporan TEPRA Perencanaan - '.$nama_skpd.'.xlsx"');
				$object_writer->save('php://output');
			}
			else{
				$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Laporan TEPRA Perencanaan - '.$nama_skpd.'.xls"');
				$object_writer->save('php://output');
			}
		}
	}
}
