<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apisirup_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}

	public function getProgram(){
		$this->db->select('a.*, UNIX_TIMESTAMP(a.updated) updated, SUM(b.jumlah) jumlah');
		$this->db->from('simda_program a');
		$this->db->join("simda_rincian_obyek b", "a.id=b.id_parent_prog");
		$this->db->group_by('a.id');
		return $this->db->get();
	}

	public function getKegiatan(){
		$this->db->select('a.*, UNIX_TIMESTAMP(a.updated) as updated, SUM(c.jumlah) as jumlah');
		$this->db->from('simda_kegiatan a');
		$this->db->join('simda_program b', 'a.id_parent_prog = b.id');
		$this->db->join('simda_rincian_obyek c', 'a.id = c.id_parent_keg');
		$this->db->group_by('a.id');
		return $this->db->get();
	}

	public function getObjekAkun(){
		$this->db->select("*, UNIX_TIMESTAMP(a.updated) as updated");
		$this->db->from("simda_rincian_obyek");
		$this->db->group_by("id");
		return $this->db->get();
	}

	public function getRincianObjekAkun(){
		$this->db->select("a.*, b.kd_rekening as kd_rekening, UNIX_TIMESTAMP(a.tanggal_update) as updated");
		$this->db->from("tb_rup a");
		$this->db->join("simda_rincian_obyek b", "a.id_rincian_obyek = b.id");
		$this->db->group_by("a.id");
		return $this->db->get();
	}

	public function getPenyedia(){
		$this->db->select("*, UNIX_TIMESTAMP(a.tanggal_update) as updated");
		$this->db->from("tb_rup");
		$this->db->where("cara_pengadaan", 1);
		$this->db->group_by("id");
		return $this->db->get();
	}

	public function getSwakelola(){
		$this->db->select("*, UNIX_TIMESTAMP(a.tanggal_update) as updated");
		$this->db->from("tb_rup");
		$this->db->where("cara_pengadaan", 1);
		$this->db->group_by("id");
		return $this->db->get();
	}	
}