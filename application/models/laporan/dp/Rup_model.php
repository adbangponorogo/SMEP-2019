<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rup_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function getProg($kd_skpd, $cara_pengadaan){
		$this->db->select('a.*');
		$this->db->from('simda_program a');
		$this->db->join("tb_rup b", "a.id = b.id_program");
		$this->db->group_by('a.id');
		$this->db->where('a.kd_skpd', $kd_skpd);
		$this->db->where('b.cara_pengadaan', $cara_pengadaan);
		$this->db->where('b.is_aktif', 1);
		return $this->db->get();
	}

	public function getKeg($id_parent_prog, $cara_pengadaan){
		$this->db->select('a.*');
		$this->db->from('simda_kegiatan a');
		$this->db->join("tb_rup b", "a.id = b.id_kegiatan");
		$this->db->group_by('a.id');
		$this->db->where('a.id_parent_prog', $id_parent_prog);
		$this->db->where('b.cara_pengadaan', $cara_pengadaan);
		$this->db->where('b.is_aktif', 1);
		return $this->db->get();
	}

	public function getPaket($id_keg, $cara_pengadaan){
		$this->db->select('*');
		$this->db->from('tb_rup');
		$this->db->where('id_kegiatan', $id_keg);
		$this->db->where('cara_pengadaan', $cara_pengadaan);
		$this->db->where('is_aktif', 1);
		return $this->db->get();
	}

	public function getDataUser($token){
		$this->db->select("*");
		$this->db->from("v_auth");
		$this->db->where("id", $token);
		return $this->db->get();
	}

	public function getDataSKPD(){
		$this->db->select("a.*");
		$this->db->from("simda_skpd a");
		$this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
		$this->db->where("b.urutan >", 0);
		$this->db->order_by("b.urutan", "ASC");
		return $this->db->get();
	}

	public function getDataSKPDUnique($skpd){
		$this->db->select("a.*");
		$this->db->from("simda_skpd a");
		$this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
		if ($skpd != "all") {
			$this->db->where("a.id", $skpd);
		}
		$this->db->order_by('b.urutan', 'ASC');
		return $this->db->get();
	}
}