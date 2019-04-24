<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rp_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function getProg($kd_skpd, $jenis_pengadaan){
		$this->db->select('a.*');
		$this->db->from('simda_program a');
		$this->db->join("tb_rup b", "a.id = b.id_program");
		$this->db->group_by('a.id');
		$this->db->where('a.kd_skpd', $kd_skpd);
		$this->db->where('b.jenis_pengadaan', $jenis_pengadaan);
		$this->db->where('b.is_aktif', 1);
		return $this->db->get();
	}

	public function getKeg($id_parent_prog, $jenis_pengadaan){
		$this->db->select('a.*');
		$this->db->from('simda_kegiatan a');
		$this->db->join("tb_rup b", "a.id = b.id_kegiatan");
		$this->db->group_by('a.id');
		$this->db->where('a.id_parent_prog', $id_parent_prog);
		$this->db->where('b.jenis_pengadaan', $jenis_pengadaan);
		$this->db->where('b.is_aktif', 1);
		return $this->db->get();
	}

	public function getPaket($id_keg, $jenis_pengadaan){
		$this->db->select('a.*, b.nama');
		$this->db->from('tb_rup a');
		$this->db->join("spse_pegawai b", "a.id_user_ppk = b.id", 'left');
		$this->db->where('a.id_kegiatan', $id_keg);
		$this->db->where('a.jenis_pengadaan', $jenis_pengadaan);
		$this->db->where('a.is_aktif', 1);
		return $this->db->get();
	}

	public function getDataUser($token){
		$this->db->select("*");
		$this->db->from("v_auth");
		$this->db->where("id", $token);
		$data = $this->db->get();
		return $data;
	}

	public function getDataSKPD(){
		$this->db->select("a.*");
		$this->db->from("simda_skpd a");
		$this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
		$this->db->where("b.urutan >", 0);
		$this->db->order_by("b.urutan", "ASC");
		$data = $this->db->get();
		return $data;
	}

	public function getDataSKPDUnique($skpd){
		$this->db->select("a.*");
		$this->db->from("simda_skpd a");
		$this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
		if ($skpd != "all") {
			$this->db->where("a.id", $skpd);
		}
		$this->db->order_by('b.urutan', 'ASC');
		$data = $this->db->get();
		return $data;
	}

	public function getDataProgramUnique($kd_skpd, $jenis_pengadaan){
		$this->db->select("distinct(a.id), a.*");
		$this->db->from("simda_program a");
		$this->db->join("tb_rup b", "a.id = b.id_program");
		$this->db->where("a.kd_skpd", $kd_skpd);
		$this->db->where("b.jenis_pengadaan", $jenis_pengadaan);
		$this->db->where('b.is_aktif', 1);
		$data = $this->db->get();
		return $data;
	}

	public function getDataKegiatanUnique($id_program, $kd_program){
		$this->db->select("distinct(a.id), a.*");
		$this->db->from("simda_kegiatan a");
		$this->db->join("tb_rup b", "a.id = b.id_kegiatan");
		$this->db->where("a.id_program", $id_program);
		$this->db->where("a.kd_program", $kd_program);
		$this->db->where('b.is_aktif', 1);
		$this->db->order_by('a.id', 'ASC');
		$data = $this->db->get();
		return $data;
	}

	public function getDataRUP($id_skpd, $id_program, $id_kegiatan, $jenis_pengadaan, $bulan){
		$this->db->select("*");
		$this->db->from("tb_rup");
		$this->db->where("id_skpd", $id_skpd);
		$this->db->where("id_program", $id_program);
		$this->db->where("id_kegiatan", $id_kegiatan);
		$this->db->where("jenis_pengadaan", $jenis_pengadaan);
		if ($bulan != 'all') {
			$this->db->where("date_format(tanggal_buat, '%m') = ", $bulan);
		}
		$this->db->where('is_aktif', 1);
		$this->db->order_by("id");
		$data = $this->db->get();
		return $data;
	}

	public function getDataPPTKKegiatan($id_kegiatan){
		$this->db->select("b.nama as nama");
		$this->db->from("tb_pptk_kegiatan a");
		$this->db->join("tb_pptk b", "a.id_pptk=b.id");
		$this->db->where("a.id_kegiatan", $id_kegiatan);
		$data = $this->db->get();
		return $data;
	}
}