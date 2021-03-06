<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminrupaktual_model extends CI_Model {

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

    public function getDataSKPDUrutan(){
        $this->db->select("a.*");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        $this->db->order_by('b.urutan', 'ASC');
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


    public function getAllDataRUP($id_skpd, $cara_pengadaan){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("cara_pengadaan", $cara_pengadaan);
        $this->db->where("is_aktif", 1);
        $this->db->order_by('id', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguRUP($id_skpd){
        $this->db->select("sum(pagu_paket) as pagu_paket");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaketRUP($id_skpd, $jenis_pengadaan){
        $this->db->select("count(id) as paket");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("jenis_pengadaan", $jenis_pengadaan);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }

}