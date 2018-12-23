<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporrup_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    } 

    public function getDataUser($token){
        $this->db->select("*");
        $this->db->from("tb_users");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSKPD(){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->order_by('id', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataSKPDUnique($skpd){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        if ($skpd != "all") {
            $this->db->where("id", $skpd);
        }
        $this->db->order_by('id', 'ASC');
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
        $this->db->order_by('id', 'ASC');
        $data = $this->db->get();
        return $data;
    }
}