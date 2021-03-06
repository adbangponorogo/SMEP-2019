<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumdapan_model extends CI_Model {

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

    public function getDataKegiatan(){
        $this->db->select("a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("simda_skpd b", "a.kd_skpd = b.kd_skpd");
        $this->db->join("tb_skpd_urutan c", "b.kd_skpd = c.kd_skpd");
        $this->db->where("c.urutan >", 0);
        $this->db->order_by("c.urutan", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($kd_skpd){
        $this->db->select("a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("simda_skpd b", "a.kd_skpd = b.kd_skpd");
        $this->db->join("tb_skpd_urutan c", "b.kd_skpd = c.kd_skpd");
        $this->db->where("c.urutan >", 0);
        $this->db->where("b.kd_skpd", $kd_skpd);
        $this->db->order_by("c.urutan", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanByID($id_skpd, $kd_gabungan, $status = FALSE){
        $this->db->select("a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("simda_skpd b", "a.kd_skpd = b.kd_skpd");
        $this->db->join("tb_skpd_urutan c", "b.kd_skpd = c.kd_skpd");
        $this->db->where("c.urutan >", 0);
        if ($status == TRUE) {
            $this->db->where("a.kd_gabungan", $kd_gabungan);
        }
        if ($id_skpd != 'all') {
            $this->db->where("b.id", $id_skpd);
        }
        $this->db->order_by("c.urutan", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiRO($kd_skpd, $kd_gabungan, $bulan, $order){
        $data = $this->db->select("*");
        $data = $this->db->from("simda_realisasi_ro");
        if ($kd_skpd != 'all') {
            $data = $this->db->where('kd_skpd', $kd_skpd);
        }
        if ($kd_gabungan != 'all') {
            $data = $this->db->where('kd_program_kegiatan', $kd_gabungan);
        }
        if ($bulan != 'all') {
            $data = $this->db->where('bln', $bulan);
        }
        $this->db->order_by($order, "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataRincianObyek($kd_skpd, $kd_gabungan, $kd_rekening){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("kd_gabungan", $kd_gabungan);
        $this->db->where("kd_rekening", $kd_rekening);
        $data = $this->db->get();
        return $data;
    }
}