<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endarup_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPD($token){
        $this->db->select("a.id, a.kd_skpd, a.nama_skpd");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        $this->db->where("a.id", $token);
        $this->db->where("b.urutan >", 0);
        $this->db->order_by("b.urutan", 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataSKPDUnique($token){
        $this->db->select("a.id, a.kd_skpd, a.nama_skpd");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        $this->db->where("b.urutan >", 0);
        $this->db->where_not_in("a.id", $token);
        $this->db->order_by("b.urutan", 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataUsers($token){
        $this->db->select("*");
        $this->db->from("v_auth");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataUsersAllPPK($id_skpd){
        $this->db->select("*");
        $this->db->from("v_auth");
        $this->db->where("status", 3);
        $this->db->where("id_skpd", $id_skpd);
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
                            sprintf("%02s", $rows->kd_program),
                            $rows->kd_gabungan,
                            $rows->keterangan_program
                        );
            }
        }
        return $data;
    }

    public function getDataProgramUnique($id){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id", $id);
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatan($id_program){
        $result_program = $this->getDataProgramUnique($id_program);
        $data = array();
        foreach ($result_program->result() as $rows_program) {
            $this->db->select("*");
            $this->db->from("simda_kegiatan");
            $this->db->where("kd_skpd", $rows_program->kd_skpd);
            $this->db->where("id_program", $rows_program->id_program);
            $this->db->where("kd_program", $rows_program->kd_program);
            $result_kegiatan = $this->db->get();
            foreach ($result_kegiatan->result() as $rows_kegiatan) {
                $data[] = array(
                            $rows_kegiatan->id,
                            sprintf("%02s", $rows_program->kd_program),
                            sprintf("%03s", $rows_kegiatan->kd_kegiatan),
                            $rows_kegiatan->kd_gabungan,
                            $rows_kegiatan->keterangan_kegiatan,
                        );
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

    public function getDataRincianObyek($id_kegiatan){
        $result_kegiatan = $this->getDataKegiatanUnique($id_kegiatan);
        $data = array();
        foreach ($result_kegiatan->result() as $rows_kegiatan) {
            $this->db->select("*");
            $this->db->from("simda_rincian_obyek");
            $this->db->where("kd_skpd", $rows_kegiatan->kd_skpd);
            $this->db->where("kd_gabungan", $rows_kegiatan->kd_gabungan);
            $result_rincian_obyek = $this->db->get();
            foreach ($result_rincian_obyek->result() as $rows_rincian_obyek) {
                $kd_rekening = explode(".", $rows_rincian_obyek->kd_rekening);
                $data[] = array(
                            $rows_rincian_obyek->id,
                            sprintf("%03s", $rows_kegiatan->kd_kegiatan),
                            sprintf("%01s", $kd_rekening[0]).".".sprintf("%01s", $kd_rekening[1]).".".sprintf("%01s", $kd_rekening[2]).".".sprintf("%02s", $kd_rekening[3]).".".sprintf("%02s", $kd_rekening[4]),
                            $rows_rincian_obyek->kd_rekening,
                            $rows_rincian_obyek->nama_rekening
                        );
            }
        }
        return $data;
    }
 
    public function getDataRincianObyekUnique($id){
        $result_ro = $this->db->select("*");
        $result_ro = $this->db->from("simda_rincian_obyek");
        $result_ro = $this->db->where("id", $id);
        $result_ro = $this->db->get();
        $data = array();
        foreach ($result_ro->result() as $rows_ro) {
            $result_skpd = $this->db->select("id");
            $result_skpd = $this->db->from("simda_skpd");
            $result_skpd = $this->db->where("kd_skpd", $rows_ro->kd_skpd);
            $result_skpd = $this->db->get();
            foreach ($result_skpd->result() as $rows_skpd) {
                $result_rup = $this->db->select("(pagu_paket) as pagu_paket");
                $result_rup = $this->db->from("tb_rup");
                $result_rup = $this->db->where("id_skpd", $rows_skpd->id);
                $result_rup = $this->db->where("id_rincian_obyek", $rows_ro->id);
                $result_rup = $this->db->get();
                if ($result_rup->num_rows() > 0) {
                    foreach ($result_rup->result() as $rows_rup) {
                        $pagu = intval($rows_ro->jumlah) - intval($rows_rup->pagu_paket);
                    }
                }
                else{
                        $pagu = intval($rows_ro->jumlah) - intval(0);
                }
            }

            $kd_rekening = explode(".", $rows_ro->kd_rekening);
            $data[] = array(
                $pagu,
                sprintf("%01s", $kd_rekening[0]).".".sprintf("%01s", $kd_rekening[1]).".".
                sprintf("%01s", $kd_rekening[2]).".".sprintf("%02s", $kd_rekening[3]).".".
                sprintf("%02s", $kd_rekening[4]),
                $rows_ro->nama_rekening
            );
        }
        return $data;
    }

    public function getDataRincianObyekUniqueID($id){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("id", $id);
        $result = $this->db->get();
        return $result;
    }

    public function getMainDataAllProgram($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $result = $this->db->get();
        return $result;
    }

    public function getMainDataAllKegiatan($id_skpd, $id_program){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $result = $this->db->get();
        return $result;
    }

    public function getMainDataAllRincianObyek($id_skpd, $id_program, $id_kegiatan){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $result = $this->db->get();
        return $result;
    }
    
    public function getMainDataUniqueRincianObyek($id_skpd, $id_program, $id_kegiatan, $id_rincian_obyek){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->where("id_rincian_obyek", $id_rincian_obyek);
        $result = $this->db->get();
        return $result;
    }

    public function getDataMasterRefRUP($kd_skpd){
        $this->db->select("*");
        $this->db->from("v_ref_master_rup");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function insertData($data){
        $this->db->insert("tb_rup", $data);
    }

    public function getData($token){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function updateData($token, $data){
        $this->db->where("id", $token);
        $this->db->update("tb_rup", $data);
    }

    public function deleteData($token){
        $this->db->where("id", $token);
        $this->db->delete("tb_rup");
    }
}