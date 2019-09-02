<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endasirup_model extends CI_Model {

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

    public function getDataRUP($token){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id", $token);
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

    public function getDataRincianObyek($token){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataAllKegiatan($id_skpd){
        $result_skpd = $this->getDataSKPD($id_skpd);
        $data = array();
        foreach ($result_skpd->result() as $rows_skpd) {
            $this->db->select("*");
            $this->db->from("simda_kegiatan");
            $this->db->where("kd_skpd", $rows_skpd->kd_skpd);
            $result_kegiatan = $this->db->get();
            foreach ($result_kegiatan->result() as $rows_kegiatan) {
                $data[] = array(
                            $rows_kegiatan->id,
                            $rows_kegiatan->kd_gabungan,
                            $rows_kegiatan->keterangan_kegiatan
                        ); 
            }
        }
        return $data;
    }

    public function getDataKegiatan($token){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getRUPDataAllKegiatan($id_skpd){
          $this->db->select("*");
          $this->db->from("tb_rup");
          $this->db->where("id_skpd", $id_skpd);
          $this->db->where("is_aktif", 1);
          $data = $this->db->get();
          return $data;
    }

    public function getRUPDataUniqueKegiatan($id_skpd, $id_kegiatan){
          $this->db->select("*");
          $this->db->from("tb_rup");
          $this->db->where("id_skpd", $id_skpd);
          $this->db->where("id_kegiatan", $id_kegiatan);
          $this->db->where("is_aktif", 1);
          $data = $this->db->get();
          return $data;
    }

    public function getDataRealisasi($token){
          $this->db->select("*");
          $this->db->from("tb_realisasi_rup");
          $this->db->where("id", $token);
          $data = $this->db->get();
          return $data;
    }

    public function getDataRealisasiRUP($id_rup){
          $this->db->select("*");
          $this->db->from("tb_realisasi_rup");
          $this->db->where("id_rup", $id_rup);
          $data = $this->db->get();
          return $data;
    }

    public function getDataRealisasiParentRUP($token){
          $this->db->select("a.*");
          $this->db->from("tb_rup a");
          $this->db->join("tb_realisasi_rup b", "a.id=b.id_rup");
          $this->db->where("b.id", $token);
          $data = $this->db->get();
          return $data;
    }

    public function getDataSumRealisasiRUP($id_rup){
          $this->db->select("sum(realisasi_keuangan) as realisasi_keuangan, sum(realisasi_fisik) as realisasi_fisik");
          $this->db->from("tb_realisasi_rup");
          $this->db->where("id_rup", $id_rup);
          $data = $this->db->get();
          return $data;
    }

    public function insertData($data){
        $this->db->insert("tb_realisasi_rup", $data);
    }

    public function updateData($token, $data){
        $this->db->where("id", $token);
        $this->db->update("tb_realisasi_rup", $data);
    }

    public function updateDataRUP($token, $data){
        $this->db->where("id", $token);
        $this->db->update("tb_rup", $data);
    }

    public function deleteData($token){
        $this->db->where("id", $token);
        $this->db->delete("tb_realisasi_rup");
    }
}