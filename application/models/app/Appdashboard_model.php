<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appdashboard_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPD($token){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataAnggaranSKPD($kd_skpd){
    	$this->db->select("SUM(pagu) as pagu");
    	$this->db->from("simda_pagu");
    	$this->db->where("kd_skpd", $kd_skpd);
    	$data = $this->db->get();
    	return $data;
    }

    public function getDataRealisasiSKPD($kd_skpd){
    	$this->db->select("SUM(realisasi_keuangan) as jumlah");
    	$this->db->from("tb_realisasi_rup a");
    	$this->db->join("tb_rup b", "a.id_rup = b.id");
    	$this->db->where("b.kd_skpd", $kd_skpd);
    	$data = $this->db->get();
    	return $data;
    }

    public function getDataPaguRUP($kd_skpd){
    	$this->db->select("SUM(pagu_paket) as pagu_paket");
    	$this->db->from("tb_rup");
    	$this->db->where("kd_skpd", $kd_skpd);
    	$data = $this->db->get();
    	return $data;
    }
}