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
			if ($id_skpd != 'all') {
				$result_skpd = $this->model->getDataSKPDUnique($id_skpd);
				foreach ($result_skpd->result() as $rows_skpd) {
					$kd_skpd = $rows_skpd->kd_skpd;
					$id_skpd_par = $rows_skpd->id_skpd;
				}
			}
			else{
				$kd_skpd = 'all';
				$id_skpd_par = 'all';
			}
			
			$result_ref_rup = $this->model->getDataRefRUP($kd_skpd);
			foreach ($result_ref_rup->result() as $rows_ref_rup) {
				$belanja_daerah = $rows_ref_rup->btl1+$rows_ref_rup->btl2+$rows_ref_rup->bl1+$rows_ref_rup->bl2+$rows_ref_rup->bl3;
				$btl = $rows_ref_rup->btl1+$rows_ref_rup->btl2;
				$bl = $rows_ref_rup->bl1+$rows_ref_rup->bl2+$rows_ref_rup->bl3;
				$btl_pegawai = $rows_ref_rup->btl1;
				$btl_non_pegawai = $rows_ref_rup->btl2;
				$bl_pegawai = $rows_ref_rup->bl1;
				$bl_non_pegawai = $rows_ref_rup->bl2+$rows_ref_rup->bl3;

				$result_bj_rup = $this->model->getDataBelanjaRUP($id_skpd_par, 2);
				foreach ($result_bj_rup->result() as $rows_bj_rup) {
					$bj_pagu = $rows_bj_rup->jumlah;
					$bj_pkt = $rows_bj_rup->pagu_paket;
				}

				$result_md_rup = $this->model->getDataBelanjaRUP($id_skpd_par, 3);
				foreach ($result_md_rup->result() as $rows_md_rup) {
					$md_pagu = $rows_md_rup->jumlah;
					$md_pkt = $rows_md_rup->pagu_paket;
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
						[number_format($bj_pkt)],
						[number_format($md_pagu)],
						[number_format($md_pkt)]
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
				if ($id_skpd != 'all') {
					$result_skpd = $this->model->getDataSKPDUnique($id_skpd);
					foreach ($result_skpd->result() as $rows_skpd) {
						$kd_skpd = $rows_skpd->kd_skpd;
					}
				}
				if ($id_skpd == 'all') {
					$kd_skpd = 'all';
				}

				// ---------- Barang -----------
				$pkt11 = 0;
				$pagu11 = 0;
				$result_penyedia_barang1 = $this->model->getDataRekapRUP($kd_skpd, 1, 1, 200000000);
				foreach ($result_penyedia_barang1->result() as $rows_penyedia_barang1) {
					$pkt11 = $this->nullIntValue($rows_penyedia_barang1->jumlah)+0;
					$pagu11 = $this->nullIntValue($rows_penyedia_barang1->pagu_paket)+0;
				}
				$pkt12 = 0;
				$pagu12 = 0;
				$result_penyedia_barang2 = $this->model->getDataRekapRUP($kd_skpd, 1, 1, 2500000000, 200000000);
				foreach ($result_penyedia_barang2->result() as $rows_penyedia_barang2) {
					$pkt12 = $this->nullIntValue($rows_penyedia_barang2->jumlah)+0;
					$pagu12 = $this->nullIntValue($rows_penyedia_barang2->pagu_paket)+0;
				}
				$pkt13 = 0;
				$pagu13 = 0;
				$result_penyedia_barang3 = $this->model->getDataRekapRUP($kd_skpd, 1, 1, 50000000000, 2500000000);
				foreach ($result_penyedia_barang3->result() as $rows_penyedia_barang3) {
					$pkt13 = $this->nullIntValue($rows_penyedia_barang3->jumlah)+0;
					$pagu13 = $this->nullIntValue($rows_penyedia_barang3->pagu_paket)+0;
				}
				$pkt14 = 0;
				$pagu14 = 0;
				$result_penyedia_barang4 = $this->model->getDataRekapRUP($kd_skpd, 1, 1, 100000000000, 50000000000);
				foreach ($result_penyedia_barang4->result() as $rows_penyedia_barang4) {
					$pkt14 = $this->nullIntValue($rows_penyedia_barang4->jumlah)+0;
					$pagu14 = $this->nullIntValue($rows_penyedia_barang4->pagu_paket)+0;
				}
				$pkt15 = 0;
				$pagu15 = 0;
				$result_penyedia_barang5 = $this->model->getDataRekapRUP($kd_skpd, 1, 1, 0, 100000000000);
				foreach ($result_penyedia_barang5->result() as $rows_penyedia_barang5) {
					$pkt15 = $this->nullIntValue($rows_penyedia_barang5->jumlah)+0;
					$pagu15 = $this->nullIntValue($rows_penyedia_barang5->pagu_paket)+0;
				}
				$pkt16 = 0;
				$pagu16 = 0;
				$result_swakelola_barang = $this->model->getDataRekapRUP($kd_skpd, 2, 1, 0);
				foreach ($result_swakelola_barang->result() as $rows_swakelola_barang) {
					$pkt16 = $this->nullIntValue($rows_swakelola_barang->jumlah)+0;
					$pagu16 = $this->nullIntValue($rows_swakelola_barang->pagu_paket)+0;
				}
				$pkt17 = $pkt11+$pkt12+$pkt13+$pkt14+$pkt15+$pkt16;
				$pagu17 = $pagu11+$pagu12+$pagu13+$pagu14+$pagu15+$pagu16;
				

				// ---------- Konstruksi -----------

				$pkt21 = 0;
				$pagu21 = 0;
				$result_penyedia_konstruksi1 = $this->model->getDataRekapRUP($kd_skpd, 1, 2, 200000000);
				foreach ($result_penyedia_konstruksi1->result() as $rows_penyedia_konstruksi1) {
					$pkt21 = $this->nullIntValue($rows_penyedia_konstruksi1->jumlah)+0;
					$pagu21 = $this->nullIntValue($rows_penyedia_konstruksi1->pagu_paket)+0;
				}
				$pkt22 = 0;
				$pagu22 = 0;
				$result_penyedia_konstruksi2 = $this->model->getDataRekapRUP($kd_skpd, 1, 2, 2500000000, 200000000);
				foreach ($result_penyedia_konstruksi2->result() as $rows_penyedia_konstruksi2) {
					$pkt22 = $this->nullIntValue($rows_penyedia_konstruksi2->jumlah)+0;
					$pagu22 = $this->nullIntValue($rows_penyedia_konstruksi2->pagu_paket)+0;
				}
				$pkt23 = 0;
				$pagu23 = 0;
				$result_penyedia_konstruksi3 = $this->model->getDataRekapRUP($kd_skpd, 1, 2, 50000000000, 2500000000);
				foreach ($result_penyedia_konstruksi3->result() as $rows_penyedia_konstruksi3) {
					$pkt23 = $this->nullIntValue($rows_penyedia_konstruksi3->jumlah)+0;
					$pagu23 = $this->nullIntValue($rows_penyedia_konstruksi3->pagu_paket);
				}
				$pkt24 = 0;
				$pagu24 = 0;
				$result_penyedia_konstruksi4 = $this->model->getDataRekapRUP($kd_skpd, 1, 2, 100000000000, 50000000000);
				foreach ($result_penyedia_konstruksi4->result() as $rows_penyedia_konstruksi4) {
					$pkt24 = $this->nullIntValue($rows_penyedia_konstruksi4->jumlah)+0;
					$pagu24 = $this->nullIntValue($rows_penyedia_konstruksi4->pagu_paket)+0;
				}
				$pkt25 = 0;
				$pagu25 = 0;
				$result_penyedia_konstruksi5 = $this->model->getDataRekapRUP($kd_skpd, 1, 2, 0, 100000000000);
				foreach ($result_penyedia_konstruksi5->result() as $rows_penyedia_konstruksi5) {
					$pkt25 = $this->nullIntValue($rows_penyedia_konstruksi5->jumlah)+0;
					$pagu25 = $this->nullIntValue($rows_penyedia_konstruksi5->pagu_paket)+0;
				}
				$pkt26 = 0;
				$pagu26 = 0;
				$result_swakelola_konstruksi = $this->model->getDataRekapRUP($kd_skpd, 2, 2, 0);
				foreach ($result_swakelola_konstruksi->result() as $rows_swakelola_konstruksi) {
					$pkt26 = $this->nullIntValue($rows_swakelola_konstruksi->jumlah)+0;
					$pagu26 = $this->nullIntValue($rows_swakelola_konstruksi->pagu_paket)+0;
				}
				
				$pkt27 = $pkt21+$pkt22+$pkt23+$pkt24+$pkt25+$pkt26;
				$pagu27 = $pagu21+$pagu22+$pagu23+$pagu24+$pagu25+$pagu26;

				// ---------- Konsultasi -----------

				$pkt31 = 0;
				$pagu31 = 0;
				$result_penyedia_konsultasi1 = $this->model->getDataRekapRUP($kd_skpd, 1, 3, 200000000);
				foreach ($result_penyedia_konsultasi1->result() as $rows_penyedia_konsultasi1) {
					$pkt31 = $this->nullIntValue($rows_penyedia_konsultasi1->jumlah)+0;
					$pagu31 = $this->nullIntValue($rows_penyedia_konsultasi1->pagu_paket)+0;
				}
				$pkt32 = 0;
				$pagu32 = 0;
				$result_penyedia_konsultasi2 = $this->model->getDataRekapRUP($kd_skpd, 1, 3, 2500000000, 200000000);
				foreach ($result_penyedia_konsultasi2->result() as $rows_penyedia_konsultasi2) {
					$pkt32 = $this->nullIntValue($rows_penyedia_konsultasi2->jumlah)+0;
					$pagu32 = $this->nullIntValue($rows_penyedia_konsultasi2->pagu_paket)+0;
				}
				$pkt33 = 0;
				$pagu33 = 0;
				$result_penyedia_konsultasi3 = $this->model->getDataRekapRUP($kd_skpd, 1, 3, 50000000000, 2500000000);
				foreach ($result_penyedia_konsultasi3->result() as $rows_penyedia_konsultasi3) {
					$pkt33 = $this->nullIntValue($rows_penyedia_konsultasi3->jumlah)+0;
					$pagu33 = $this->nullIntValue($rows_penyedia_konsultasi3->pagu_paket)+0;
				}
				$pkt34 = 0;
				$pagu34 = 0;
				$result_penyedia_konsultasi4 = $this->model->getDataRekapRUP($kd_skpd, 1, 3, 100000000000, 50000000000);
				foreach ($result_penyedia_konsultasi4->result() as $rows_penyedia_konsultasi4) {
					$pkt34 = $this->nullIntValue($rows_penyedia_konsultasi4->jumlah)+0;
					$pagu34 = $this->nullIntValue($rows_penyedia_konsultasi4->pagu_paket)+0;
				}
				$pkt35 = 0;
				$pagu35 = 0;
				$result_penyedia_konsultasi5 = $this->model->getDataRekapRUP($kd_skpd, 1, 3, 0, 100000000000);
				foreach ($result_penyedia_konsultasi5->result() as $rows_penyedia_konsultasi5) {
					$pkt35 = $this->nullIntValue($rows_penyedia_konsultasi5->jumlah)+0;
					$pagu35 = $this->nullIntValue($rows_penyedia_konsultasi5->pagu_paket)+0;
				}
				$pkt36 = 0;
				$pagu36 = 0;
				$result_swakelola_konsultasi = $this->model->getDataRekapRUP($kd_skpd, 2, 3, 0);
				foreach ($result_swakelola_konsultasi->result() as $rows_swakelola_konsultasi) {
					$pkt36 = $this->nullIntValue($rows_swakelola_konsultasi->jumlah)+0;
					$pagu36 = $this->nullIntValue($rows_swakelola_konsultasi->pagu_paket)+0;
				}
				$pkt37 = $pkt31+$pkt32+$pkt33+$pkt34+$pkt35+$pkt36;
				$pagu37 = $pagu31+$pagu32+$pagu33+$pagu34+$pagu35+$pagu36;


				// ---------- Jasa Lainnya -----------

				$pkt41 = 0;
				$pagu41 = 0;
				$result_penyedia_jasalainnya1 = $this->model->getDataRekapRUP($kd_skpd, 1, 4, 200000000);
				foreach ($result_penyedia_jasalainnya1->result() as $rows_penyedia_jasalainnya1) {
					$pkt41 = $this->nullIntValue($rows_penyedia_jasalainnya1->jumlah)+0;
					$pagu41 = $this->nullIntValue($rows_penyedia_jasalainnya1->pagu_paket)+0;
				}
				$pkt42 = 0;
				$pagu42 = 0;
				$result_penyedia_jasalainnya2 = $this->model->getDataRekapRUP($kd_skpd, 1, 4, 2500000000, 200000000);
				foreach ($result_penyedia_jasalainnya2->result() as $rows_penyedia_jasalainnya2) {
					$pkt42 = $this->nullIntValue($rows_penyedia_jasalainnya2->jumlah)+0;
					$pagu42 = $this->nullIntValue($rows_penyedia_jasalainnya2->pagu_paket)+0;
				}
				$pkt43 = 0;
				$pagu43 = 0;
				$result_penyedia_jasalainnya3 = $this->model->getDataRekapRUP($kd_skpd, 1, 4, 50000000000, 2500000000);
				foreach ($result_penyedia_jasalainnya3->result() as $rows_penyedia_jasalainnya3) {
					$pkt43 = $this->nullIntValue($rows_penyedia_jasalainnya3->jumlah)+0;
					$pagu43 = $this->nullIntValue($rows_penyedia_jasalainnya3->pagu_paket)+0;
				}
				$pkt44 = 0;
				$pagu44 = 0;
				$result_penyedia_jasalainnya4 = $this->model->getDataRekapRUP($kd_skpd, 1, 4, 100000000000, 50000000000);
				foreach ($result_penyedia_jasalainnya4->result() as $rows_penyedia_jasalainnya4) {
					$pkt44 = $this->nullIntValue($rows_penyedia_jasalainnya4->jumlah)+0;
					$pagu44 = $this->nullIntValue($rows_penyedia_jasalainnya4->pagu_paket)+0;
				}
				$pkt45 = 0;
				$pagu45 = 0;
				$result_penyedia_jasalainnya5 = $this->model->getDataRekapRUP($kd_skpd, 1, 4, 0, 100000000000);
				foreach ($result_penyedia_jasalainnya5->result() as $rows_penyedia_jasalainnya5) {
					$pkt45 = $this->nullIntValue($rows_penyedia_jasalainnya5->jumlah)+0;
					$pagu45 = $this->nullIntValue($rows_penyedia_jasalainnya5->pagu_paket)+0;
				}
				$pkt46 = 0;
				$pagu46 = 0;
				$result_swakelola_jasalainnya = $this->model->getDataRekapRUP($kd_skpd, 2, 4, 0);
				foreach ($result_swakelola_jasalainnya->result() as $rows_swakelola_jasalainnya) {
					$pkt46 = $this->nullIntValue($rows_swakelola_jasalainnya->jumlah)+0;
					$pagu46 = $this->nullIntValue($rows_swakelola_jasalainnya->pagu_paket)+0;
				}
				
				$pkt47 = $pkt41+$pkt42+$pkt43+$pkt44+$pkt45+$pkt46;
				$pagu47 = $pagu41+$pagu42+$pagu43+$pagu44+$pagu45+$pagu46;


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
							[
								$pkt11, number_format($pagu11),
								$pkt12, number_format($pagu12),
								$pkt13, number_format($pagu13),
								$pkt14, number_format($pagu14),
								$pkt15, number_format($pagu15),
								$pkt16, number_format($pagu16),
								$pkt17, number_format($pagu17)
							],
							[
								$pkt21, number_format($pagu21), 
								$pkt22, number_format($pagu22), 
								$pkt23, number_format($pagu23), 
								$pkt24, number_format($pagu24), 
								$pkt25, number_format($pagu25), 
								$pkt26, number_format($pagu26), 
								$pkt27, number_format($pagu27)
							],
							[
								$pkt31, number_format($pagu31), 
								$pkt32, number_format($pagu32), 
								$pkt33, number_format($pagu33), 
								$pkt34, number_format($pagu34), 
								$pkt35, number_format($pagu35), 
								$pkt36, number_format($pagu36), 
								$pkt37, number_format($pagu37)
							],
							[
								$pkt41, number_format($pagu41), 
								$pkt42, number_format($pagu42), 
								$pkt43, number_format($pagu43), 
								$pkt44, number_format($pagu44), 
								$pkt45, number_format($pagu45), 
								$pkt46, number_format($pagu46), 
								$pkt47, number_format($pagu47)
							],
							[
								$total_pkt1, number_format($total_pagu1),
								$total_pkt2, number_format($total_pagu2),
								$total_pkt3, number_format($total_pagu3),
								$total_pkt4, number_format($total_pagu4),
								$total_pkt5, number_format($total_pagu5),
								$total_pkt6, number_format($total_pagu6),
								$total_pkt7, number_format($total_pagu7)
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
		
		if ($obj->id_skpd != 'all') {
			$obj->kd_skpd = $this->model->getDataSKPDUnique($obj->id_skpd)->row()->kd_skpd;
			$obj->nama_skpd = $this->model->getDataSKPDUnique($obj->id_skpd)->row()->nama_skpd;
			$obj->id_skpd_par = $this->model->getDataSKPDUnique($obj->id_skpd)->row()->id;
		}
		if ($obj->id_skpd == 'all') {
			$obj->kd_skpd = 'all';
			$obj->nama_skpd = 'Pemerintah Daerah';
			$obj->id_skpd_par = 'all';
		}
		
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
		
		$belanja = $this->model->getBelanja($obj->kd_skpd)->row();
		$x->setCellValue('B14', $belanja->btl1+0);
		$x->setCellValue('E14', $belanja->btl2+0);
		$x->setCellValue('H14', $belanja->bl1+0);
		$x->setCellValue('I18', $belanja->bl2+0);
		$x->setCellValue('M18', $belanja->bl3+0);
		
		// Belanja Pegawai
		$BP = $this->model->getBP($obj->kd_skpd)->row()->jml;
		$x->setCellValue('H15', $BP+0);

		// Belanja Modal
		$BM = $this->model->getBM($obj->kd_skpd)->row()->jml;
		$x->setCellValue('N19', $BM+0);

		// Mengisi tabel Tepra Perencanaan
		$mulai = 25;
		$row = $mulai - 1;
		
		$rs = $this->model->getPaket($obj->kd_skpd, 'penyedia', 200000000);
		if ($rs->num_rows() > 0) {
			foreach ($rs->result() as $d){
				$x->setCellValue('B'.($row + $d->jenis_pengadaan), $d->jml+0);
				$x->setCellValue('C'.($row + $d->jenis_pengadaan), $d->pagu+0);
			}
		}
		if ($rs->num_rows() <= 0) {
			for ($i=1; $i <= 4; $i++) {
				$x->setCellValue('B'.(($row+$i)+0), 0);
				$x->setCellValue('C'.(($row+$i)+0), 0);
			}
		}
		
		$rs = $this->model->getPaket($obj->kd_skpd, 'penyedia', 2500000000, 200000000);
		if ($rs->num_rows() > 0) {
			foreach ($rs->result() as $d){
				$x->setCellValue('D'.($row + $d->jenis_pengadaan), $d->jml+0);
				$x->setCellValue('E'.($row + $d->jenis_pengadaan), $d->pagu+0);
			}
		}
		if ($rs->num_rows() <= 0) {
			for ($i=1; $i <= 4; $i++) {
				$x->setCellValue('D'.(($row+$i)+0), 0);
				$x->setCellValue('E'.(($row+$i)+0), 0);
			}
		}
		
		$rs = $this->model->getPaket($obj->kd_skpd, 'penyedia', 50000000000, 2500000000);
		if ($rs->num_rows() > 0) {
			foreach ($rs->result() as $d){
				$x->setCellValue('F'.($row + $d->jenis_pengadaan), $d->jml+0);
				$x->setCellValue('G'.($row + $d->jenis_pengadaan), $d->pagu+0);
			}
		}
		if ($rs->num_rows() <= 0) {
			for ($i=1; $i <= 4; $i++) {
				$x->setCellValue('F'.(($row+$i)+0), 0);
				$x->setCellValue('G'.(($row+$i)+0), 0);
			}
		}
		
		$rs = $this->model->getPaket($obj->kd_skpd, 'penyedia', 100000000000, 50000000000);
		if ($rs->num_rows() > 0) {
			foreach ($rs->result() as $d){
				$x->setCellValue('H'.($row + $d->jenis_pengadaan), $d->jml+0);
				$x->setCellValue('I'.($row + $d->jenis_pengadaan), $d->pagu+0);
			}
		}
		if ($rs->num_rows() <= 0) {
			for ($i=1; $i <= 4; $i++) {
				$x->setCellValue('H'.(($row+$i)+0), 0);
				$x->setCellValue('I'.(($row+$i)+0), 0);
			}
		}

		$rs = $this->model->getPaket($obj->kd_skpd, 'penyedia', 0, 100000000000);
		if ($rs->num_rows() > 0) {
			foreach ($rs->result() as $d){
				$x->setCellValue('J'.($row + $d->jenis_pengadaan), $d->jml+0);
				$x->setCellValue('K'.($row + $d->jenis_pengadaan), $d->pagu+0);
			}
		}
		if ($rs->num_rows() <= 0) {
			for ($i=1; $i <= 4; $i++) {
				$x->setCellValue('J'.(($row+$i)+0), 0);
				$x->setCellValue('K'.(($row+$i)+0), 0);
			}
		}

		$rs = $this->model->getPaket($obj->kd_skpd, 'swakelola', 0);
		if ($rs->num_rows() > 0) {
			foreach ($rs->result() as $d){
				$x->setCellValue('L'.($row + $d->jenis_pengadaan), $d->jml+0);
				$x->setCellValue('M'.($row + $d->jenis_pengadaan), $d->pagu+0);
			}
		}
		if ($rs->num_rows() <= 0) {
			for ($i=1; $i <= 4; $i++) {
				$x->setCellValue('L'.(($row+$i)+0), 0);
				$x->setCellValue('M'.(($row+$i)+0), 0);
			}
		}

		// Mengisi bagian ttd
		$fixrow = 31;
		getPenanggungJawab(
			$x,
			$fixrow-2,
			$obj->klpd,
			$obj->tgl_cetak,
			$this->main_model->getKaSKPD($obj->id_skpd_par, false)->row(),//data kepala SKPD
			'L' //Posisi kolom penanggungjawab
		);
		
		xl_footer(
			$x,
			$obj->footerlap,//footer laporan sebelah kiri
			$obj->jenis_form,//Jenis Form Laporan TEPRA-P
			$obj->nama_skpd
		);
		$nama_skpd = str_replace(' ', '-', $obj->nama_skpd);
		$nama_skpd = str_replace(' ', ',', $obj->nama_skpd);
		export2xl($p, str_replace([' ', ','], '-', $obj->nama_skpd).'_'.$obj->jenis_form.'_'.$obj->tahun);
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