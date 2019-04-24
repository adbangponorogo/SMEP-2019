<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endasitepra_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPD($token){
        $this->db->select("id, kd_skpd, nama_skpd");
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUP($id_skpd){
      $this->db->select("*");
      $this->db->from("tb_rup");
      $this->db->where("id_skpd", $id_skpd);
      $this->db->where_in("metode_pemilihan", array(2, 3, 5, 6));
      $this->db->where("is_aktif", 1);
      $data = $this->db->get();
      return $data;
    }

    public function getDataProgram($token){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatan($token){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUPUnique($token){
      $this->db->select("*");
      $this->db->from("tb_rup");
      $this->db->where("id", $token);
      $data = $this->db->get();
      return $data;
    }

    public function getDataRealisasiTepra($id_rup){
      $this->db->select("*");
      $this->db->from("tb_realisasi_tepra");
      $this->db->where("id_rup", $id_rup);
      $data = $this->db->get();
      return $data;
    }

    public function getDataKegiatanUnique($token){
      $this->db->select("*");
      $this->db->from("simda_kegiatan");
      $this->db->where("id", $token);
      $data = $this->db->get();
      return $data;
    }

    public function insertData($data){
      $this->db->insert("tb_realisasi_tepra", $data);
    }

    public function updateData($id_rup, $data){
      $this->db->where("id_rup", $id_rup);
      $this->db->update("tb_realisasi_tepra", $data);
    }
}