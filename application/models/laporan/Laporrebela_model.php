<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporrebela_model extends CI_Model {

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
        $this->db->join("tb_rup c", "a.id = c.id_skpd");
        if ($skpd != "all") {
            $this->db->where("a.id", $skpd);
        }
        $this->db->order_by('b.urutan', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataSumberROAP1($id_skpd){
        $this->db->select("distinct(a.sumber_dana) as sumber_dana");
        $this->db->from("tb_sumber_realisasi_obyek a");
        $this->db->join("tb_rup b", "a.id_rincian_obyek = b.id_rincian_obyek");
        $this->db->where("b.id_skpd", $id_skpd);
        $this->db->where_in("a.sumber_dana", array(1,2,3,4,5,6));
        $data = $this->db->get();
        return $data;
    }

    public function getDataSumberROAP3($id_skpd){
        $this->db->select("distinct(a.sumber_dana) as sumber_dana");
        $this->db->from("tb_sumber_realisasi_obyek a");
        $this->db->join("tb_rup b", "a.id_rincian_obyek = b.id_rincian_obyek");
        $this->db->where("b.id_skpd", $id_skpd);
        $this->db->where_in("a.sumber_dana", array(7,8,9));
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgramUniqueAP1($kd_skpd){
        $this->db->select("distinct(a.id), a.*");
        $this->db->from("simda_program a");
        $this->db->join("tb_rup b", "a.id = b.id_program");
        $this->db->join("tb_sumber_realisasi_obyek c", "b.id_rincian_obyek = c.id_rincian_obyek");
        $this->db->where("a.kd_skpd", $kd_skpd);
        $this->db->where_in("c.sumber_dana", array(1,2,3,4,5,6));
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgramUniqueAP2($kd_skpd){
        $this->db->select("distinct(a.id), a.*");
        $this->db->from("simda_program a");
        $this->db->join("tb_rup b", "a.id = b.id_program");
        $this->db->where("a.kd_skpd", $kd_skpd);
        $this->db->where("b.jenis_pengadaan", 2);
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgramUniqueAP3($kd_skpd){
        $this->db->select("distinct(a.id), a.*");
        $this->db->from("simda_program a");
        $this->db->join("tb_rup b", "a.id = b.id_program");
        $this->db->join("tb_sumber_realisasi_obyek c", "b.id_rincian_obyek = c.id_rincian_obyek");
        $this->db->where("a.kd_skpd", $kd_skpd);
        $this->db->where_in("c.sumber_dana", array(7,8,9));
        $data = $this->db->get();
        return $data;
    }

    public function getDataSumberDanaProgram($id_skpd, $id_program){
        $this->db->select("distinct(a.sumber_dana) as sumber_dana");
        $this->db->from("tb_sumber_realisasi_obyek a");
        $this->db->join("tb_rup b", "a.id_rincian_obyek = b.id_rincian_obyek");
        $this->db->where("b.id_skpd", $id_skpd);
        $this->db->where("b.id_program", $id_program);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguProgram($id_skpd, $id_program, $sumber_dana){
        $this->db->select("sum(a.pagu_paket) as pagu");
        $this->db->from("tb_rup a");
        $this->db->join("tb_sumber_realisasi_obyek b", "a.id_rincian_obyek = b.id_rincian_obyek");
        $this->db->where("a.id_skpd", $id_skpd);
        $this->db->where("a.id_program", $id_program);
        $this->db->where("b.sumber_dana", $sumber_dana);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguProgramKonstruksi($id_skpd, $id_program){
        $this->db->select("sum(pagu_paket) as pagu");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("jenis_pengadaan", 2);
        $data = $this->db->get();
        return $data;
    }
    
    public function getDataRealisasiProgram($id_skpd, $id_program, $sumber_dana){
        $this->db->select("sum(realisasi_keuangan) as realisasi_keuangan");
        $this->db->from("tb_realisasi_rup a");
        $this->db->join("tb_rup b", "a.id_rup=b.id");
        $this->db->join("tb_sumber_realisasi_obyek c", "b.id_rincian_obyek = c.id_rincian_obyek");
        $this->db->where("b.id_skpd", $id_skpd);
        $this->db->where("b.id_program", $id_program);
        $this->db->where("c.sumber_dana", $sumber_dana);
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($kd_skpd, $id_program){
        $this->db->select("distinct(a.id), a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("tb_rup b", "a.id = b.id_kegiatan");
        $this->db->where("a.kd_skpd", $kd_skpd);
        $this->db->where("b.id_program", $id_program);
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUniqueKonstruksi($kd_skpd, $id_program){
        $this->db->select("distinct(a.id), a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("tb_rup b", "a.id = b.id_kegiatan");
        $this->db->where("a.kd_skpd", $kd_skpd);
        $this->db->where("b.id_program", $id_program);
        $this->db->where("b.jenis_pengadaan", 2);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSumberDanaKegiatan($id_skpd, $id_program, $id_kegiatan){
        $this->db->select("distinct(a.sumber_dana) as sumber_dana");
        $this->db->from("tb_sumber_realisasi_obyek a");
        $this->db->join("tb_rup b", "a.id_rincian_obyek = b.id_rincian_obyek");
        $this->db->where("b.id_skpd", $id_skpd);
        $this->db->where("b.id_program", $id_program);
        $this->db->where("b.id_kegiatan", $id_kegiatan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguKegiatan($id_skpd, $id_program, $id_kegiatan, $sumber_dana){
        $this->db->select("sum(a.pagu_paket) as pagu");
        $this->db->from("tb_rup a");
        $this->db->join("tb_sumber_realisasi_obyek b", "a.id_rincian_obyek = b.id_rincian_obyek");
        $this->db->where("a.id_skpd", $id_skpd);
        $this->db->where("a.id_program", $id_program);
        $this->db->where("a.id_kegiatan", $id_kegiatan);
        $this->db->where("b.sumber_dana", $sumber_dana);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguKegiatanKonstruksi($id_skpd, $id_program, $id_kegiatan){
        $this->db->select("sum(pagu_paket) as pagu");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->where("jenis_pengadaan", 2);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiKegiatan($id_skpd, $id_program, $id_kegiatan, $sumber_dana){
        $this->db->select("sum(realisasi_keuangan) as realisasi_keuangan");
        $this->db->from("tb_realisasi_rup a");
        $this->db->join("tb_rup b", "a.id_rup=b.id");
        $this->db->join("tb_sumber_realisasi_obyek c", "b.id_rincian_obyek = c.id_rincian_obyek");
        $this->db->where("b.id_skpd", $id_skpd);
        $this->db->where("b.id_program", $id_program);
        $this->db->where("b.id_kegiatan", $id_kegiatan);
        $this->db->where("c.sumber_dana", $sumber_dana);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRincianObyekUnique($kd_skpd, $kd_gabungan){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("kd_gabungan", $kd_gabungan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSumberDanaRO($id_rincian_obyek){
        $this->db->select("*");
        $this->db->from("tb_sumber_realisasi_obyek");
        $this->db->where("id_rincian_obyek", $id_rincian_obyek);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguRO($id_skpd, $id_program, $id_kegiatan, $id_rincian_obyek){
        $this->db->select("sum(pagu_paket) as pagu");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->where("id_rincian_obyek", $id_rincian_obyek);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiRO($id_skpd, $id_program, $id_kegiatan, $id_rincian_obyek){
        $this->db->select("sum(a.realisasi_keuangan) as realisasi_keuangan");
        $this->db->from("tb_realisasi_rup a");
        $this->db->join("tb_rup b", "a.id_rup = b.id");
        $this->db->where("b.id_skpd", $id_skpd);
        $this->db->where("b.id_program", $id_program);
        $this->db->where("b.id_kegiatan", $id_kegiatan);
        $this->db->where("b.id_rincian_obyek", $id_rincian_obyek);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUP($id_skpd, $id_program, $id_kegiatan){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->where("jenis_pengadaan", 2);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRincianObyekRUP($token){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRealisasiRUP($id_rup){
        $this->db->select("sum(realisasi_keuangan) as realisasi_keuangan, tanggal_kontrak, nomor_kontrak, nama_pemenang, tanggal_spmk, tanggal_surat_serah_terima");
        $this->db->from("tb_realisasi_rup");
        $this->db->where("id_rup", $id_rup);
        $this->db->limit(1);
        $data = $this->db->get();
        return $data;
    }
}