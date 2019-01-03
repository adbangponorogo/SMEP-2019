<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporteprarealisasi_model extends CI_Model {

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

    public function getDataRencanaKeuangan($kd_skpd){
        $this->db->select("sum(januari) as januari, sum(februari) as februari, sum(maret) as maret, sum(april) as april, sum(mei) as mei, sum(juni) as juni, sum(juli) as juli, sum(agustus) as agustus, sum(september) as september, sum(oktober) as oktober, sum(november) as november, sum(desember) as desember");
        $this->db->from("simda_ref_akas_kegiatan");
        if ($kd_skpd != "all") {
            $this->db->where("kd_skpd", $kd_skpd);
        }
        $data = $this->db->get();
        return $data;
    }

   public function getDataRealisasiKeuangan($kd_skpd, $bulan){
        $this->db->select("sum(nilai) as nilai");
        $this->db->from("simda_realisasi_ro");
        if ($kd_skpd != 'all') {
            $this->db->where("kd_skpd", $kd_skpd);
        }
        $this->db->where("bln", $bulan);
        $data = $this->db->get();
        return $data;
   }

   public function getDataRealisasiFisik($id_skpd, $bulan){
        $this->db->select("sum(a.realisasi_keuangan) as realisasi_keuangan");
        $this->db->from("tb_realisasi_rup a");
        $this->db->join("tb_rup b", "a.id_rup = b.id");
        if ($id_skpd != 'all') {
            $this->db->where("b.id_skpd", $id_skpd);
        }
        $this->db->where("date_format(str_to_date(a.tanggal_pencairan, '%d-%m-%Y'), '%m') = ", $bulan);
        $data = $this->db->get();
        return $data;
   }

   public function getDataPaketRUP($id_skpd){
        $this->db->select("sum(jumlah_paket) as jumlah_paket");
        $this->db->from("tb_rup");
        if ($id_skpd != 'all') {
            $this->db->where("id_skpd", $id_skpd);
        }
        $data = $this->db->get();
        return $data;
   }

   public function getDataRealisasiTepra($id_skpd, $tahap, $bulan){
        $this->db->select("sum(jumlah_paket) as jumlah_paket");
        $this->db->from("tb_realisasi_tepra a");
        $this->db->join("tb_rup b", "a.id_rup = b.id");
        if ($id_skpd != 'all') {
            $this->db->where("b.id_skpd", $id_skpd);
        }
        $this->db->where("a.".$tahap.">", 0);
        $this->db->where("date_format(b.tanggal_buat, '%m') = ", $bulan);
        $data = $this->db->get();
        return $data;
   }
}