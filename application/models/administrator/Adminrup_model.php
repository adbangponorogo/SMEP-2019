<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminrup_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataUser($token){
        $this->db->select("*");
        $this->db->from("v_auth");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
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

    public function getDataSKPDUnique($skpd){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        if ($skpd != "all") {
            $this->db->where("id", $skpd);
        }
        $this->db->order_by('kd_skpd', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($id){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        if ($id != 'all') {
            $this->db->where("id", $id);
        }
        $this->db->order_by('id', 'ASC');
        $data = $this->db->get();
        return $data;
    }


    public function getAllDataRUP($id_skpd, $cara_pengadaan){
        $this->db->select("b.nama_skpd, c.keterangan_kegiatan, a.*");
        $this->db->from("tb_rup a");
        $this->db->join("simda_skpd b", "a.kd_skpd = b.kd_skpd");
        $this->db->join("simda_kegiatan c", "a.id_kegiatan = c.id");
        if ($id_skpd != 'all') {
            $this->db->where("a.id_skpd", $id_skpd);
        }
        $this->db->where("cara_pengadaan", $cara_pengadaan);
        $this->db->where("a.is_aktif", 1);
        $this->db->where("a.is_final", 1);
        $this->db->where("a.is_deleted", 0);
        $this->db->where("a.is_last_paket", 1);
        $this->db->group_by('a.id');
        $this->db->order_by('a.id', 'ASC');
        $data = $this->db->get();
        return $data;
    }
}