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

    public function getDataPaguSKPD($kd_skpd){
        // $this->db->select("(SUM(btl1)+SUM(btl2)+SUM(bl1)+SUM(bl2)+SUM(bl3)) as pagu_paket");
        $this->db->select("(SUM(bl1)+SUM(bl2)+SUM(bl3)) as pagu_paket");
        $this->db->from("sirup_struktur_anggaran");
        if ($kd_skpd != 'all') {
            $this->db->where("kd_skpd", $kd_skpd);
        }
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguRUPSKPD($kd_skpd, $jenis_pengadaan, $bulan){
        $this->db->select("sum(pagu_paket) as pagu_paket");
        $this->db->from("tb_rup");
        if ($kd_skpd != 'all') {
            $this->db->where("kd_skpd", $kd_skpd);
        }
        $this->db->where("jenis_pengadaan", $jenis_pengadaan);
        $this->db->where("date_format(tanggal_update, '%m') <=", $bulan);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaketRUP($kd_skpd, $jenis_pengadaan, $metode_pemilihan, $bulan){
        $this->db->select("count(id) as paket");
        $this->db->from("tb_rup");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("jenis_pengadaan", $jenis_pengadaan);
        $this->db->where("metode_pemilihan", $metode_pemilihan);
        $this->db->where("date_format(tanggal_update, '%m') <=", $bulan);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }
}