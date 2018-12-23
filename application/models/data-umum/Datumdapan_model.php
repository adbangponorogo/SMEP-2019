<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumdapan_model extends CI_Model {

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

    public function getDataSKPDUnique($token){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $this->db->order_by('id', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatan(){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($kd_skpd){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->order_by("id", "ASC");
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

    public function getDataRincianObyek($kd_skpd, $kd_rekening){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("kd_rekening", $kd_rekening);
        $data = $this->db->get();
        return $data;
    }
}