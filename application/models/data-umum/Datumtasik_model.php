<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumtasik_model extends CI_Model {

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

    public function getDataSumPagu($kd_skpd){
        $this->db->select("sum(Pagu) as pagu");
        $this->db->from("simda_pagu");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRencanaFisik($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_rencana_fisik");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function insertData($data){
        $this->db->insert("tb_rencana_fisik", $data);
    }

    public function updateData($id_skpd, $data){
        $this->db->where("id_skpd", $id_skpd);
        $this->db->update("tb_rencana_fisik", $data);
    }
}