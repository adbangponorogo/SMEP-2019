<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laportepraperencanaan_model extends CI_Model {

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

    public function getDataProgramUnique($token){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id", $token);
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

    public function getDataRefRUP($kd_skpd){
        $this->db->select("sum(btl1) as btl1, sum(btl2) as btl2, sum(bl1) as bl1, sum(bl2) as bl2, sum(bl3) as bl3");
        $this->db->from("simda_ref_master_rup");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUP($id_skpd, $metode_pemilihan){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("metode_pemilihan", $metode_pemilihan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguRUP($id_skpd, $metode_pemilihan){
        $this->db->select("sum(pagu_paket) as pagu_paket");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("metode_pemilihan", $metode_pemilihan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataBelanjaRUP($id_skpd, $jenis_belanja){
        $this->db->select("count(id) as jumlah, sum(pagu_paket) as pagu_paket");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("jenis_belanja", $jenis_belanja);
        $data = $this->db->get();
        return $data;
    }


    public function getDataRekapRUP($id_skpd, $cara_pengadaan, $jenis_pengadaan, $max_pagu){
        $data = $this->db->query("select count(id) as jumlah, sum(pagu_paket) as pagu_paket from tb_rup where id_skpd = ".$id_skpd." and cara_pengadaan = ".$cara_pengadaan." ".$max_pagu." and jenis_pengadaan = ".$jenis_pengadaan."");
        return $data;
    }

    public function getDataRekapTotalRUP($id_skpd, $jenis_pengadaan){
        $data = $this->db->query("select count(id) as jumlah, sum(pagu_paket) as pagu_paket from tb_rup where id_skpd = ".$id_skpd." and jenis_pengadaan = ".$jenis_pengadaan."");
        return $data;
    }
}