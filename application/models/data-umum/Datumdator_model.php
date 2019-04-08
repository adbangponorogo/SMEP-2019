<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumdator_model extends CI_Model {

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

    public function getDataMasterRefRUP($kd_skpd){
        $this->db->select("*");
        $this->db->from("sirup_struktur_anggaran");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataMasterRUP($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_master_rup");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function insertData($data){
        $this->db->insert("tb_master_rup", $data);
    }

    public function updateData($id_skpd, $data){
        $this->db->where("id_skpd", $id_skpd);
        $this->db->update("tb_master_rup", $data);
    }
}