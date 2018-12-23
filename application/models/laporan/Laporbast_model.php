<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporbast_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPDUnique($token){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUP($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiRUP($id_rup){
        $this->db->select("id, nomor_surat, tanggal_surat_serah_terima, sum(nilai_kontrak) as nilai_kontrak, sum(realisasi_keuangan) as realisasi_keuangan, nama_pemenang");
        $this->db->from("tb_realisasi_rup");
        $this->db->where("id_rup", $id_rup);
        $data = $this->db->get();
        return $data;
    }
}