<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumtangan_model extends CI_Model {

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
        $this->db->select("sum(pagu) as pagu");
        $this->db->from("simda_pagu");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataAkasKegiatan($kd_skpd){
        $this->db->select("sum(januari) as januari, sum(februari) as februari, sum(maret) as maret, sum(april) as april, sum(mei) as mei, sum(juni) as juni, sum(juli) as juli, sum(agustus) as agustus, sum(september) as september, sum(oktober) as oktober, sum(november) as november, sum(desember) as desember");
        $this->db->from("simda_ref_akas_kegiatan");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRencanaKeuangan($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_rencana_keuangan");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function insertData($data){
      $this->db->insert("tb_rencana_keuangan", $data);  
    }

    public function updateData($id_skpd, $data){
        $this->db->where("id_skpd", $id_skpd);
        $this->db->update("tb_rencana_keuangan", $data);
    }
}