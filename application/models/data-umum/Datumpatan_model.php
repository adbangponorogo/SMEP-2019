<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumpatan_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPD($token){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgram($id_skpd){
        $result_skpd = $this->getDataSKPD($id_skpd);
        $data = array();
        foreach ($result_skpd->result() as $row) {
            $this->db->select("*");
            $this->db->from("simda_program");
            $this->db->where("kd_skpd", $row->kd_skpd);
            $result_program = $this->db->get();
            foreach ($result_program->result() as $rows) {
                $data[] = array(
                            $rows->id,
                            $rows->kd_gabungan,
                            $rows->keterangan_program
                        );
            }
        }
        return $data;
    }

    public function getDataProgramUnique($id_program){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id", $id_program);
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgramUniqueIK($id_program, $kd_program){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id_program", $id_program);
        $this->db->where("kd_program", $kd_program);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguSKPD($id_skpd){
        $result_skpd = $this->getDataSKPD($id_skpd);
        $data = array();
        foreach ($result_skpd->result() as $row_skpd) {
            $this->db->select("sum(jumlah) as jumlah");
            $this->db->from("simda_rincian_obyek");
            $this->db->where("kd_skpd", $row_skpd->kd_skpd);
            $result_pagu = $this->db->get();
            foreach ($result_pagu->result() as $row_pagu) {
                $data[] = array("Rp. ".number_format($row_pagu->jumlah));
            }
        }
        return $data;
    }

    public function getDataKegiatan($id_skpd, $id_program){
        $result_skpd = $this->getDataSKPD($id_skpd);
        $data = array();
        $no = 1;
        foreach ($result_skpd->result() as $row_skpd) {
            $result_program = $this->getDataProgramUnique($id_program);
            foreach ($result_program->result() as $row_program) {
                $result_kegiatan = $this->db->select("*");
                $result_kegiatan = $this->db->from("simda_kegiatan");
                $result_kegiatan = $this->db->where("kd_skpd", $row_skpd->kd_skpd);
                $result_kegiatan = $this->db->where("id_program", $row_program->id_program);
                $result_kegiatan = $this->db->where("kd_program", $row_program->kd_program);
                $result_kegiatan = $this->db->get();
                foreach ($result_kegiatan->result() as $row_kegiatan) {
                    $result_pagu_kegiatan = $this->getDataPaguKegiatan($row_skpd->kd_skpd, $row_program->id_program, $row_program->kd_program, $row_kegiatan->kd_kegiatan);
                    foreach ($result_pagu_kegiatan->result() as $row_pagu) {
                        
                        $result_pagu_rup_penyedia = $this->getDataRUPPaguPenyedia($row_skpd->id, $row_program->id, $row_kegiatan->id);
                        if ($result_pagu_rup_penyedia->num_rows() > 0) {
                            foreach ($result_pagu_rup_penyedia->result() as $row_pagu_penyedia) {
                                $pagu_penyedia = $row_pagu_penyedia->pagu_penyedia;
                            }
                        }
                        else{
                            $pagu_penyedia = 0;
                        }

                        $result_pagu_rup_swakelola = $this->getDataRUPPaguSwakelola($row_skpd->id, $row_program->id, $row_kegiatan->id);
                        if ($result_pagu_rup_swakelola->num_rows() > 0) {
                            foreach ($result_pagu_rup_swakelola->result() as $row_pagu_swakelola) {
                                $pagu_swakelola = $row_pagu_swakelola->pagu_swakelola;
                            }
                        }
                        else{
                            $pagu_swakelola = 0;
                        }

                        $sisa_pagu = intval($row_pagu->pagu)-(intval($pagu_penyedia)+intval($pagu_swakelola));
                        
                        $data[] = array(
                                    $no++,
                                    $row_kegiatan->kd_gabungan,
                                    $row_kegiatan->keterangan_kegiatan,
                                    "Rp. ".number_format($row_pagu->pagu),
                                    "Rp. ".number_format($pagu_penyedia),
                                    "Rp. ".number_format($pagu_swakelola),
                                    "Rp. ".number_format($sisa_pagu),
                                    "<button class='btn btn-primary btn-sm patan-edit-button smep-patandatum-rincian-get-btn' data-id='".$row_kegiatan->id."'>".
                                        "<i class='fa fa-share-square-o'></i>&nbsp;Rincian Sumber Dana".
                                    "</button>"
                                );
                     } 
                }
            }
        }
        return $data;
    }

    public function getDataKegiatanUnique($token){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguKegiatan($kd_skpd, $id_program, $kd_program, $kd_kegiatan){
        $this->db->select("*");
        $this->db->from("simda_pagu");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("kd_program", $kd_program);
        $this->db->where("kd_kegiatan", $kd_kegiatan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUPPaguPenyedia($id_skpd, $id_program, $id_kegiatan){
        $this->db->select("sum(pagu_paket) as pagu_penyedia");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->where("cara_pengadaan", 1);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUPPaguSwakelola($id_skpd, $id_program, $id_kegiatan){
        $this->db->select("sum(pagu_paket) as pagu_swakelola");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->where("cara_pengadaan", 2);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRincianObyek($kd_skpd, $kd_gabungan){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("kd_gabungan", $kd_gabungan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSumPaguRincianObyek($kd_skpd, $kd_gabungan){
        $this->db->select("sum(jumlah) as jumlah");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("kd_gabungan", $kd_gabungan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSumberRealisasiObyek($id_rincian_obyek){
        $this->db->select("*");
        $this->db->from("tb_sumber_realisasi_obyek");
        $this->db->where("id_rincian_obyek", $id_rincian_obyek);
        $data = $this->db->get();
        return $data;
    }

    public function insertData($data){
        $this->db->insert("tb_sumber_realisasi_obyek", $data);
    }

    public function updateData($id_rincian_obyek, $data){
        $this->db->where("id_rincian_obyek", $id_rincian_obyek);
        $this->db->update("tb_sumber_realisasi_obyek", $data);
    }
}