<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminlapan_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
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

    public function getDataRealisasiRUP($id_skpd, $jenis_pengadaan, $bulan){
        $this->db->select("sum(a.pagu_paket) as pagu_paket, sum(b.nilai_hps) as nilai_hps, sum(b.nilai_kontrak) as nilai_kontrak");
        $this->db->from("tb_rup a");
        $this->db->join("tb_realisasi_rup b", "a.id = b.id_rup");
        $this->db->where("a.id_skpd", $id_skpd);
        $this->db->where("a.jenis_pengadaan", $jenis_pengadaan);
        // $this->db->where("a.bulan", $bulan);
        $data = $this->db->get();
        return $data;
    }

}