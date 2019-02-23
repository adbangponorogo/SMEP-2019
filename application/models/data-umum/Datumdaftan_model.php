<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumdaftan_model extends CI_Model {

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

    public function getDataProgramUnique($id_program, $kd_program){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id_program", $id_program);
        $this->db->where("kd_program", $kd_program);
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatan($id_skpd, $id_program){
        $result_skpd = $this->getDataSKPD($id_skpd);
        $data = array();
        $no = 1;
        foreach ($result_skpd->result() as $rows_skpd) {
            $result_program = $this->db->select("*  ");
            $result_program = $this->db->from("simda_program");
            $result_program = $this->db->where("kd_skpd", $rows_skpd->kd_skpd);
            $result_program = $this->db->where("id", $id_program);
            $result_program = $this->db->get();
            foreach ($result_program->result() as $rows_program) {
                $result_kegiatan = $this->db->select("*");
                $result_kegiatan = $this->db->from("simda_kegiatan");
                $result_kegiatan = $this->db->where("kd_skpd", $rows_skpd->kd_skpd);
                $result_kegiatan = $this->db->where("id_program", $rows_program->id_program);
                $result_kegiatan = $this->db->where("kd_program", $rows_program->kd_program);
                $result_kegiatan = $this->db->get();
                foreach ($result_kegiatan->result() as $rows_kegiatan) {
                    $result_pptk_kegiatan = $this->getDataPPTKKegiatan($rows_skpd->id, $rows_kegiatan->id);
                    if ($result_pptk_kegiatan->num_rows() > 0) {
                        foreach ($result_pptk_kegiatan->result() as $rows_pptk_kegiatan) {
                            $result_pptk = $this->getDataPPTKUnique($rows_pptk_kegiatan->id_pptk);
                            foreach ($result_pptk->result() as $rows_pptk) {
                                $nama_pptk = $rows_pptk->nama;
                            }
                        }
                    }
                    else{
                        $nama_pptk = '-';
                    }

                    $data[] = array(
                                $no++,
                                $rows_kegiatan->kd_gabungan,
                                $rows_kegiatan->keterangan_kegiatan,
                                $nama_pptk,
                                "<button class='btn btn-primary btn-sm smep-daftandatum-edit-btn' data-id='".$rows_kegiatan->id."'><i class='fa fa-edit'></i>&nbsp;Indikator</button>"
                            );
                }
            }
        }
        return $data;
    }

    public function getDataKegiatanUnique($id){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("id", $id);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPPTK($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_pptk");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPPTKUnique($id){
        $this->db->select("*");
        $this->db->from("tb_pptk");
        $this->db->where("id", $id); 
        $data = $this->db->get();
        return $data;
    }

    public function getDataPPTKKegiatan($id_skpd, $id_kegiatan){
        $this->db->select("*");
        $this->db->from("tb_pptk_kegiatan");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $data = $this->db->get();
        return $data;
    }

    public function updateKegiatan($token, $data){
        $this->db->where("id", $token);
        $this->db->update("simda_kegiatan", $data);
    }

    public function insertPPTKKegiatan($data){
        $this->db->insert("tb_pptk_kegiatan", $data);   
    }

    public function updatePPTKKegiatan($id_skpd, $id_kegiatan, $data){
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->update("tb_pptk_kegiatan", $data);
    }
}