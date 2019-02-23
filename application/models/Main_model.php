<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function getSKPD($id_skpd){
		$this->db->select('*');
		$this->db->from('simda_skpd');
		$this->db->where('id', $id_skpd);
		return $this->db->get();
	}

	public function getKaSKPD($id, $auth=true){
		$this->db->select('a.*');
		$this->db->from('tb_pptk a');
		$this->db->join("v_auth b", "a.id_skpd = b.id_skpd");
		$this->db->join("tb_master_rup c", "a.id_skpd = c.id_skpd AND a.status = c.sts_pimpinan");
		if ($auth)
			$this->db->where('b.id', $id);
		else
			$this->db->where('a.id_skpd', $id);
		return $this->db->get();
	}

	public function getConfig($key){
		$this->db->select('value');
		$this->db->from('tb_config');
		$this->db->where('key', $key);
		return $this->db->get();
	}

	public function getDataUser($token){
		$this->db->select("*");
		$this->db->from("v_auth");
		$this->db->where("id", $token);
		$data = $this->db->get();
		return $data;
	}

	public function getDataUserPPKAll(){
		$this->db->select('a.id, a.kd_skpd, a.nama_skpd');
		$this->db->from("simda_skpd a");
		$this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
		$this->db->order_by("b.urutan", "ASC");
		$this->db->where("b.urutan >", 0);
		$data = $this->db->get();
		return $data;
	}

	public function getDataUserPPKUnique($token){
			$this->db->select('id, kd_skpd, nama_skpd');
			$this->db->from("simda_skpd");
			$this->db->where("id", $token);
			$data = $this->db->get();
			return $data;
	}
}