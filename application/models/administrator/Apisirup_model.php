<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apisirup_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}

	public function getProgram(){
		$this->db->select('a.*, UNIX_TIMESTAMP(a.updated) as updated_unix, SUM(b.jumlah) as jumlah');
		$this->db->from('simda_program a');
		$this->db->join("simda_rincian_obyek b", "a.id = b.id_parent_prog");
		$this->db->group_by('a.id');
		return $this->db->get();
	}

	// public function getKegiatan($kd_skpd, $id_program, $kd_program){
	// 	$this->db->select("a.*, UNIX_TIMESTAMP(a.updated) as updated, SUM(b.pagu) as pagu");
	// 	$this->db->from("simda_kegiatan a");
	// 	$this->db->join("simda_pagu b", "a.kd_gabungan = b.kd_gabungan");
	// 	$this->db->where("a.kd_skpd", $kd_skpd);
	// 	$this->db->where("a.id_program", $id_program);
	// 	$this->db->where("a.kd_program", $kd_program);
	// 	$this->db->group_by("a.id");
	// 	return $this->db->get();
	// }

	public function getKegiatan(){
		$this->db->select("a.*, b.id_parent_prog as id_program, UNIX_TIMESTAMP(a.updated) as updated_unix, SUM(b.jumlah) as jumlah");
		$this->db->from("simda_kegiatan a");
		$this->db->join("simda_rincian_obyek b", "a.id = b.id_parent_keg");
		$this->db->group_by("a.id");
		return $this->db->get();
	}

	public function getObjekAkun(){
		$this->db->select("*, UNIX_TIMESTAMP(updated) as updated_unix");
		$this->db->from("simda_rincian_obyek");
		$this->db->group_by("id");
		return $this->db->get();
	}

	public function getRincianObjekAkun(){
		$this->db->select("a.*, UNIX_TIMESTAMP(a.tanggal_update) as updated_unix");
		$this->db->from("tb_rup a");
		return $this->db->get();
	}
}