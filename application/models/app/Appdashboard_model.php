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
    	$this->db->select("COUNT(DISTINCT(id_rup)) as total_paket_realisasi, SUM(realisasi_keuangan) as jumlah");
    	$this->db->from("tb_realisasi_rup a");
    	$this->db->join("tb_rup b", "a.id_rup = b.id");
    	$this->db->where("b.kd_skpd", $kd_skpd);
    	$data = $this->db->get();
    	return $data;
    }

    public function getDataRUP($kd_skpd){
    	$this->db->select("COUNT(DISTINCT(id)) as total_paket, SUM(pagu_paket) as pagu_paket");
    	$this->db->from("tb_rup");
    	$this->db->where("kd_skpd", $kd_skpd);
    	$data = $this->db->get();
    	return $data;
    }

    public function getDataRUPNonRealisasi($kd_skpd){
        $this->db->select("nama_paket, pagu_paket");
        $this->db->from("tb_rup");
        $this->db->where_not_in("SELECT DISTINCT(id_rup) from tb_realisasi_rup a JOIN tb_rup b on a.id_rup = b.id WHERE b.kd_skpd =".$kd_skpd);
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }
}