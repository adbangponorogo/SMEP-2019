<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporbast_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPDUnique($token){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSKPDByKD($kd_skpd){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUP($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiRUP($id_rup){
        $this->db->select("id, nomor_surat, tanggal_surat_serah_terima, sum(nilai_kontrak) as nilai_kontrak, sum(realisasi_keuangan) as realisasi_keuangan, nama_pemenang");
        $this->db->from("tb_realisasi_rup");
        $this->db->where("id_rup", $id_rup);
        $this->db->order_by("id", 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiByID($id){
        $this->db->select("id, id_rup, nomor_surat, tanggal_surat_serah_terima, sum(nilai_kontrak) as nilai_kontrak");
        $this->db->from("tb_realisasi_rup");
        $this->db->where("id", $id);
        $this->db->where("nomor_surat IS NOT NULL");
        $this->db->where("tanggal_surat_serah_terima IS NOT NULL");
        $this->db->order_by("id", 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUPByID($id){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id", $id);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSirupMasterRUP($kd_skpd){
        $this->db->select("*");
        $this->db->from("sirup_struktur_anggaran");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataTBMasterRUP($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_master_rup");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPPTK($id_skpd, $status){
        $this->db->select("*");
        $this->db->from("tb_pptk");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("status", $status);
        $this->db->limit(1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPPK($id_ppk){
        $this->db->select("*");
        $this->db->from("spse_pegawai");
        $this->db->where("id", $id_ppk);
        // $this->db->where("status", 3);
        $this->db->limit(1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgram($kd_skpd, $id_realisasi){
        $this->db->select("c.*");
        $this->db->from("tb_realisasi_rup a");
        $this->db->join("tb_rup b", "a.id_rup = b.id");
        $this->db->join("simda_program c", "b.id_program = c.id");
        $this->db->where("c.kd_skpd", $kd_skpd);
        $this->db->where("a.id", $id_realisasi);
        $this->db->group_by("a.id");
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatan($kd_skpd, $id_realisasi){
        $this->db->select("c.*");
        $this->db->from("tb_realisasi_rup a");
        $this->db->join("tb_rup b", "a.id_rup = b.id");
        $this->db->join("simda_kegiatan c", "b.id_kegiatan = c.id");
        $this->db->where("c.kd_skpd", $kd_skpd);
        $this->db->where("a.id", $id_realisasi);
        $this->db->group_by("a.id");
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiRUPByIDExcel($kd_skpd, $id_realisasi){
        $this->db->select("b.nama_paket, b.sumber_dana, b.pagu_paket, b.volume_pekerjaan, SUM(a.nilai_kontrak) AS nilai_kontrak, a.nomor_kontrak, a.tanggal_kontrak, a.nama_pemenang, a.tanggal_spmk, a.tanggal_surat_serah_terima, SUM(a.realisasi_keuangan) AS realisasi_keuangan, a.realisasi_fisik, b.cara_pengadaan");
        $this->db->from("tb_realisasi_rup a");
        $this->db->join("tb_rup b", "a.id_rup = b.id");
        $this->db->join("simda_skpd c", "b.kd_skpd = c.kd_skpd");
        $this->db->where("c.kd_skpd", $kd_skpd);
        $this->db->where("a.id", $id_realisasi);
        $this->db->where("a.nomor_surat IS NOT NULL");
        $this->db->where("a.tanggal_surat_serah_terima IS NOT NULL");
        $this->db->group_by("a.id");
        $data = $this->db->get();
        return $data;
    }
}
