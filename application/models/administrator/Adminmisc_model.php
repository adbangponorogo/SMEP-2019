<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminmisc_model extends CI_Model {

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

    public function getDataKegiatan($id_skpd){
        $this->db->select("a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("simda_skpd b", "a.kd_skpd = b.kd_skpd");
        if ($id_skpd != 'all') {
            $this->db->where("b.id", $id_skpd);
        }
        $this->db->order_by("a.id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($kd_skpd, $id_kegiatan){
        $this->db->select("a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("simda_skpd b", "a.kd_skpd = b.kd_skpd");
        $this->db->where("b.kd_skpd", $kd_skpd);
        if ($id_kegiatan != 'all') {
            $this->db->where("a.id", $id_kegiatan);
        }
        $this->db->order_by("a.id", "ASC");
        $data = $this->db->get();
        return $data;
    }


    public function getDataRealisasiRO($kd_skpd, $kd_gabungan, $bulan){
        $this->db->select("a.tanggal, a.bln, a.no_spj as no_sp2d, a.no_spm, a.uraian, a.nama_penerima, a.kd_program_kegiatan, sum(b.jumlah) as pagu, sum(a.nilai) as pencairan, a.kd_rekening, b.kd_skpd, b.nama_rekening");
        $this->db->from("simda_realisasi_ro a");
        $this->db->join("simda_rincian_obyek b", "a.kd_skpd = b.kd_skpd and a.kd_program_kegiatan = b.kd_gabungan and a.kd_rekening = b.kd_rekening");
        $this->db->where("b.kd_skpd", $kd_skpd);
        $this->db->where("b.kd_gabungan", $kd_gabungan);
        if ($bulan != 'all') {
            $this->db->where("a.bln <=", $bulan);
        }
        $this->db->order_by('a.id', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataSPMInfo($no_spm){
        $this->db->select("sum(nilai) as nilai, nama");
        $this->db->from("simda_spm_info");
        $this->db->where("nomor", $no_spm);
        $data = $this->db->get();
        return $data;
    }

    // public function getDataRincianObyekUnique($kd_skpd, $kd_rekening){
    //     $this->db->select("*");
    //     $this->db->from("simda_rincian_obyek");
    //     $this->db->where("kd_skpd", $kd_skpd);
    //     $this->db->where("kd_rekening", $kd_rekening);
    //     $data = $this->db->get();
    //     return $data;
    // }
}