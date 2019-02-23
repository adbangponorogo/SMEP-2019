<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporrup_model extends CI_Model {

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
        $this->db->select("a.*");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        if ($skpd != "all") {
            $this->db->where("a.id", $skpd);
        }
        $this->db->order_by('b.urutan', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataRUP($id_skpd, $cara_pengadaan){
        $this->db->select("a.*");
        $this->db->from("tb_rup a");
        $this->db->join("simda_skpd b", "a.id_skpd = b.id");
        $this->db->join("tb_skpd_urutan c", "b.kd_skpd = c.kd_skpd");
        if ($id_skpd != 'all') {
            $this->db->where("a.id_skpd", $id_skpd);
        }
        $this->db->where("a.cara_pengadaan", $cara_pengadaan);
        $this->db->order_by('c.urutan', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($id){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("id", $id);
        $this->db->order_by('id', 'ASC');
        $data = $this->db->get();
        return $data;
    }
}