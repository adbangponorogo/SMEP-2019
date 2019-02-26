<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tepraperencanaan_controller extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan/dp/Tepraperencanaan_model', 'model');
	}

	public function mainPage(){
		if ($this->session->userdata('auth_id') != "") {
			$this->load->view('pages/laporan/tepra/perencanaan/data');
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
		else{
			redirect(base_url());
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
		else{
			redirect(base_url());
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
		else{
			redirect(base_url());
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
		global $smep;
		
		$obj = new stdClass();
		//$obj->jenis_pengadaan = $this->input->post("jenis_pengadaan");
		$obj->id_skpd = $this->input->post("skpd");
		$obj->tahun = $this->input->post("tahun");
		//$obj->bulan = $this->input->post("bulan");
		$obj->tgl_cetak = $this->input->post("tgl_cetak");
		
		$obj->kd_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->kd_skpd;
		$obj->nama_skpd = $this->main_model->getSKPD($obj->id_skpd)->row()->nama_skpd;
		
		$obj->tingkat = $smep->tingkat;
		$obj->klpd = $smep->klpd;
		$obj->footerlap = $smep->footerlap;

 		$this->load->library('Excel');
 		$this->load->helper('office_helper');
 		$this->load->helper('other_helper');

		switch (true){
			//case 3:
				//$this->getPrintDataTepraR($obj);
				//break;
			default:
				$obj->jenis_form = 'TEPRA-P';
				$this->getPrintDataTepraR($obj);
		}
	}

	public function getPrintDataTepraR($obj){
		$r = PHPExcel_IOFactory::createReader('Excel2007');
		$p = $r->load(TPLPATH.$obj->jenis_form.'.xlsx');
		$x = $p->getActiveSheet();

		$x->setCellValue('B3', ': '.strtoupper($obj->nama_skpd));
		$x->setCellValue('B5', ': '.$obj->tahun);
		
		$belanja = $this->model->getBelanja($obj->id_skpd)->row();
		$x->setCellValue('B14', $belanja->btl1+0);
		$x->setCellValue('E14', $belanja->btl2+0);
		$x->setCellValue('H14', $belanja->bl1+0);
		$x->setCellValue('I18', $belanja->bl2+0);
		$x->setCellValue('M18', $belanja->bl3+0);
		
		// Belanja Pegawai
		$BP = $this->model->getBP($obj->id_skpd)->row()->jml;
		$x->setCellValue('H15', $BP+0);

		// Belanja Modal
		$BM = $this->model->getBM($obj->id_skpd)->row()->jml;
		$x->setCellValue('N19', $BM+0);

		// Mengisi tabel Tepra Perencanaan
		$mulai = 25;
		$row = $mulai - 1;
		
		$rs = $this->model->getPaket($obj->id_skpd, 'penyedia', 200000000);
		foreach ($rs->result() as $d){
			$x->setCellValue('B'.($row + $d->jenis_pengadaan), $d->jml+0);
			$x->setCellValue('C'.($row + $d->jenis_pengadaan), $d->pagu+0);
		}
		
		$rs = $this->model->getPaket($obj->id_skpd, 'penyedia', 2500000000, 200000000);
		foreach ($rs->result() as $d){
			$x->setCellValue('D'.($row + $d->jenis_pengadaan), $d->jml+0);
			$x->setCellValue('E'.($row + $d->jenis_pengadaan), $d->pagu+0);
		}
		
		$rs = $this->model->getPaket($obj->id_skpd, 'penyedia', 50000000000, 2500000000);
		foreach ($rs->result() as $d){
			$x->setCellValue('F'.($row + $d->jenis_pengadaan), $d->jml+0);
			$x->setCellValue('G'.($row + $d->jenis_pengadaan), $d->pagu+0);
		}
		
		$rs = $this->model->getPaket($obj->id_skpd, 'penyedia', 100000000000, 50000000000);
		foreach ($rs->result() as $d){
			$x->setCellValue('H'.($row + $d->jenis_pengadaan), $d->jml+0);
			$x->setCellValue('I'.($row + $d->jenis_pengadaan), $d->pagu+0);
		}

		$rs = $this->model->getPaket($obj->id_skpd, 'penyedia', 0, 100000000000);
		foreach ($rs->result() as $d){
			$x->setCellValue('J'.($row + $d->jenis_pengadaan), $d->jml+0);
			$x->setCellValue('K'.($row + $d->jenis_pengadaan), $d->pagu+0);
		}

		$rs = $this->model->getPaket($obj->id_skpd, 'swakelola', 0);
		foreach ($rs->result() as $d){
			$x->setCellValue('L'.($row + $d->jenis_pengadaan), $d->jml+0);
			$x->setCellValue('M'.($row + $d->jenis_pengadaan), $d->pagu+0);
		}

		// Mengisi bagian ttd
		$fixrow = 31;
		getPenanggungJawab(
			$x,
			$fixrow-2,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd, false)->row(),//data kepala SKPD
			'L' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$obj->jenis_form,//Jenis Form Laporan TEPRA-P
			$obj->nama_skpd
		);
		export2xl($p, str_replace(' ', '-', $obj->nama_skpd).'_'.$obj->jenis_form.'_'.$obj->tahun);
	}
}