<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminrapan_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPD(){
        $this->db->select("a.*");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        $this->db->order_by('b.urutan', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguSKPD($id_skpd, $jenis_pengadaan){
        $this->db->select("sum(pagu_paket) as pagu_paket");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("jenis_pengadaan", $jenis_pengadaan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaketRUP($id_skpd, $jenis_pengadaan, $metode_pemilihan){
        $this->db->select("count(id) as paket");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("jenis_pengadaan", $jenis_pengadaan);
        $this->db->where("metode_pemilihan", $metode_pemilihan);
        $data = $this->db->get();
        return $data;
    }
}